<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * Send email directly via Gmail SMTP socket with TLS
     */
    public static function sendHtml($toEmail, $subject, $htmlContent, $toName = '')
    {
        try {
            $smtpHost = config('mail.mailers.smtp.host', 'smtp.gmail.com');
            $smtpPort = (int) config('mail.mailers.smtp.port', 587);
            $username = config('mail.mailers.smtp.username', env('MAIL_USERNAME'));
            $password = config('mail.mailers.smtp.password', env('MAIL_PASSWORD'));
            $fromEmail = config('mail.from.address', $username);
            $fromName = config('mail.from.name', 'PT Nusantara Digital Express');

            if (!$username || !$password || config('mail.default') === 'log') {
                Log::info("MailService [LOG MODE]: To: $toEmail | Subject: $subject");
                return false;
            }

            // Connection with 5 seconds timeout
            $fp = @fsockopen($smtpHost, $smtpPort, $errno, $errstr, 5);
            if (!$fp) {
                Log::warning("MailService: Cannot connect to $smtpHost:$smtpPort - $errstr ($errno)");
                return false;
            }

            // Set socket read/write timeout to 5 seconds to prevent hanging
            stream_set_timeout($fp, 5);

            $getResponse = function ($fp) {
                $res = "";
                while (!feof($fp)) {
                    $line = fgets($fp, 512);
                    if ($line === false) {
                        $meta = stream_get_meta_data($fp);
                        if (!empty($meta['timed_out'])) {
                            Log::warning("MailService: Socket read timed out.");
                            return false;
                        }
                        break;
                    }
                    $res .= $line;
                    if (strlen($line) >= 4 && $line[3] === ' ') {
                        break;
                    }
                }
                return $res;
            };

            $writeAll = function ($fp, $data) {
                $total = strlen($data);
                $written = 0;
                while ($written < $total) {
                    $n = @fwrite($fp, substr($data, $written, 16384));
                    if ($n === false || $n <= 0) {
                        $meta = stream_get_meta_data($fp);
                        if (!empty($meta['timed_out'])) {
                            Log::warning("MailService: Socket write timed out.");
                        }
                        return false;
                    }
                    $written += $n;
                }
                return true;
            };

            // 1. Read greeting
            $res = $getResponse($fp);
            if ($res === false) { fclose($fp); return false; }

            // 2. EHLO
            $writeAll($fp, "EHLO client.example.com\r\n");
            $res = $getResponse($fp);
            if ($res === false) { fclose($fp); return false; }

            // 3. STARTTLS
            $writeAll($fp, "STARTTLS\r\n");
            $res = $getResponse($fp);
            if ($res === false) { fclose($fp); return false; }

            // 4. Encrypt TLS
            @stream_context_set_option($fp, 'ssl', 'verify_peer', false);
            @stream_context_set_option($fp, 'ssl', 'verify_peer_name', false);
            if (!@stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($fp);
                Log::warning("MailService: TLS encryption failed");
                return false;
            }

            // Re-apply timeout after crypto handshake
            stream_set_timeout($fp, 5);

            // 5. Post-TLS EHLO
            $writeAll($fp, "EHLO client.example.com\r\n");
            $res = $getResponse($fp);
            if ($res === false) { fclose($fp); return false; }

            // 6. AUTH LOGIN
            $writeAll($fp, "AUTH LOGIN\r\n");
            $res = $getResponse($fp);
            if ($res === false) { fclose($fp); return false; }

            $writeAll($fp, base64_encode($username) . "\r\n");
            $res = $getResponse($fp);
            if ($res === false) { fclose($fp); return false; }

            $writeAll($fp, base64_encode($password) . "\r\n");
            $authRes = $getResponse($fp);
            if ($authRes === false || strpos($authRes, '235') === false) {
                fclose($fp);
                Log::warning("MailService: Authentication failed: " . ($authRes ?: 'timeout'));
                return false;
            }

            // 7. MAIL FROM & RCPT TO
            $writeAll($fp, "MAIL FROM: <$fromEmail>\r\n");
            $res = $getResponse($fp);
            if ($res === false) { fclose($fp); return false; }

            $writeAll($fp, "RCPT TO: <$toEmail>\r\n");
            $res = $getResponse($fp);
            if ($res === false) { fclose($fp); return false; }

            // 8. DATA
            $writeAll($fp, "DATA\r\n");
            $res = $getResponse($fp);
            if ($res === false) { fclose($fp); return false; }

            // 9. Headers & Body (Prefer lightweight logo_email.png)
            $logoEmailPath = public_path('images/logo_email.png');
            $logoRegularPath = public_path('images/logo.png');
            $logoPath = file_exists($logoEmailPath) ? $logoEmailPath : (file_exists($logoRegularPath) ? $logoRegularPath : null);
            $hasLogo = !empty($logoPath);

            $headers  = "From: $fromName <$fromEmail>\r\n";
            $headers .= "To: " . ($toName ? "$toName <$toEmail>" : "<$toEmail>") . "\r\n";
            $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            $headers .= "MIME-Version: 1.0\r\n";

            if ($hasLogo) {
                $boundary = "----=_Part_" . bin2hex(random_bytes(16));
                $headers .= "Content-Type: multipart/related; boundary=\"$boundary\"\r\n\r\n";

                $safeHtml = preg_replace('/^\./m', '..', $htmlContent);
                $logoData = chunk_split(base64_encode(file_get_contents($logoPath)));

                $body  = "--$boundary\r\n";
                $body .= "Content-Type: text/html; charset=UTF-8\r\n";
                $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
                $body .= $safeHtml . "\r\n\r\n";

                $body .= "--$boundary\r\n";
                $body .= "Content-Type: image/png; name=\"logo.png\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-ID: <company_logo>\r\n";
                $body .= "Content-Disposition: inline; filename=\"logo.png\"\r\n\r\n";
                $body .= $logoData . "\r\n";
                $body .= "--$boundary--\r\n";

                $message = $headers . $body . "\r\n.\r\n";
            } else {
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $headers .= "Content-Transfer-Encoding: 8bit\r\n\r\n";

                $safeBody = preg_replace('/^\./m', '..', $htmlContent);
                $message = $headers . $safeBody . "\r\n.\r\n";
            }

            // Write entire message in chunks
            $ok = $writeAll($fp, $message);
            if (!$ok) {
                fclose($fp);
                Log::warning("MailService: Failed to write complete message to socket.");
                return false;
            }

            $sendRes = $getResponse($fp);

            $writeAll($fp, "QUIT\r\n");
            $getResponse($fp);
            fclose($fp);

            if ($sendRes && strpos($sendRes, '250') !== false) {
                Log::info("MailService: Email sent to $toEmail successfully! Response: " . trim($sendRes));
                return true;
            } else {
                Log::warning("MailService: SMTP responded with: " . ($sendRes ?: 'no response'));
                return false;
            }
        } catch (\Throwable $e) {
            Log::warning("MailService: Exception while sending email: " . $e->getMessage());
            return false;
        }
    }
}
