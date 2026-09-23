<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi Bahasa Indonesia
    |--------------------------------------------------------------------------
    */

    'accepted'             => ':attribute harus diterima.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa array.',
    'before'               => ':attribute harus tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
        'file'    => 'Ukuran :attribute harus antara :min dan :max kilobyte.',
        'string'  => ':attribute harus berisi antara :min dan :max karakter.',
        'array'   => ':attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean'              => 'Kolom :attribute harus bernilai benar atau salah.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_equals'          => ':attribute harus tanggal yang sama dengan :date.',
    'date_format'          => ':attribute tidak cocok dengan format :format.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus berisikan :digits digit.',
    'digits_between'       => ':attribute harus bernilai antara :min dan :max digit.',
    'dimensions'           => 'Ukuran gambar :attribute tidak valid.',
    'distinct'             => 'Kolom :attribute memiliki nilai duplikat.',
    'email'                => 'Format :attribute tidak valid.',
    'ends_with'            => ':attribute harus diakhiri salah satu dari berikut: :values.',
    'exists'               => ':attribute yang dipilih tidak valid.',
    'file'                 => ':attribute harus berupa file.',
    'filled'               => 'Kolom :attribute wajib diisi.',
    'gt'                   => [
        'numeric' => ':attribute harus lebih besar dari :value.',
        'file'    => 'Ukuran :attribute harus lebih besar dari :value kilobyte.',
        'string'  => ':attribute harus lebih dari :value karakter.',
        'array'   => ':attribute harus memiliki lebih dari :value item.',
    ],
    'gte'                  => [
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'file'    => 'Ukuran :attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'string'  => ':attribute harus minimal :value karakter.',
        'array'   => ':attribute harus memiliki :value item atau lebih.',
    ],
    'image'                => ':attribute harus berupa gambar (JPG, PNG, WEBP).',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'in_array'             => 'Kolom :attribute tidak ada di dalam :other.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':attribute harus berupa string JSON yang valid.',
    'lt'                   => [
        'numeric' => ':attribute harus kurang dari :value.',
        'file'    => 'Ukuran :attribute harus kurang dari :value kilobyte.',
        'string'  => ':attribute harus kurang dari :value karakter.',
        'array'   => ':attribute harus memiliki kurang dari :value item.',
    ],
    'lte'                  => [
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'file'    => 'Ukuran :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'string'  => ':attribute tidak boleh lebih dari :value karakter.',
        'array'   => ':attribute tidak boleh memiliki lebih dari :value item.',
    ],
    'max'                  => [
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'file'    => 'Ukuran :attribute tidak boleh lebih besar dari :max kilobyte.',
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'mimes'                => ':attribute harus berupa file berjenis: :values.',
    'mimetypes'            => ':attribute harus berupa file berjenis: :values.',
    'min'                  => [
        'numeric' => ':attribute minimal bernilai :min.',
        'file'    => 'Ukuran :attribute minimal :min kilobyte.',
        'string'  => ':attribute minimal berisi :min karakter.',
        'array'   => ':attribute minimal memiliki :min item.',
    ],
    'multiple_of'          => ':attribute harus merupakan kelipatan dari :value.',
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => [
        'letters'       => ':attribute harus mengandung minimal satu huruf.',
        'mixed'         => ':attribute harus mengandung minimal satu huruf besar dan satu huruf kecil.',
        'numbers'       => ':attribute harus mengandung minimal satu angka.',
        'symbols'       => ':attribute harus mengandung minimal satu simbol khusus (misal: ! @ # $ %).',
        'uncompromised' => ':attribute yang dimasukkan pernah bocor dalam data breach. Silakan gunakan password lain.',
    ],
    'present'              => 'Kolom :attribute wajib ada.',
    'prohibited'           => 'Kolom :attribute dilarang diisi.',
    'prohibited_if'        => 'Kolom :attribute dilarang diisi jika :other bernilai :value.',
    'prohibited_unless'    => 'Kolom :attribute dilarang diisi kecuali :other ada di dalam :values.',
    'prohibits'            => 'Kolom :attribute melarang :other untuk hadir.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => 'Kolom :attribute wajib diisi.',
    'required_array_keys'  => 'Kolom :attribute harus berisi entri untuk: :values.',
    'required_if'          => 'Kolom :attribute wajib diisi bila :other adalah :value.',
    'required_unless'      => 'Kolom :attribute wajib diisi kecuali :other ada dalam :values.',
    'required_with'        => 'Kolom :attribute wajib diisi bila terdapat :values.',
    'required_with_all'    => 'Kolom :attribute wajib diisi bila terdapat :values.',
    'required_without'     => 'Kolom :attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => 'Kolom :attribute wajib diisi bila sama sekali tidak terdapat :values.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => ':attribute harus berukuran :size.',
        'file'    => 'Ukuran :attribute harus :size kilobyte.',
        'string'  => ':attribute harus berisi :size karakter.',
        'array'   => ':attribute harus mengandung :size item.',
    ],
    'starts_with'          => ':attribute harus diawali salah satu dari berikut: :values.',
    'string'               => ':attribute harus berupa teks.',
    'timezone'             => ':attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute sudah terdaftar / sudah digunakan.',
    'uploaded'             => ':attribute gagal diunggah.',
    'url'                  => 'Format :attribute tidak valid.',
    'uuid'                 => ':attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes (Nama Kolom dalam Bahasa Indonesia)
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'email'                 => 'Email',
        'password'              => 'Password',
        'password_confirmation' => 'Konfirmasi Password',
        'name'                  => 'Nama Lengkap',
        'phone'                 => 'Nomor Telepon',
        'address'               => 'Alamat',
        'location'              => 'Lokasi Pemakaian',
        'purpose'               => 'Keperluan Peminjaman',
        'borrow_date'           => 'Tanggal Mulai Pinjam',
        'return_date'           => 'Tanggal Pengembalian',
        'quantity'              => 'Jumlah Unit',
        'id_card_image'         => 'Dokumen Jaminan KTP/KK',
        'payment_proof'         => 'Bukti Pembayaran',
        'payment_method'        => 'Metode Pembayaran',
        'stock'                 => 'Stok Barang',
        'price_per_day'         => 'Harga Sewa per Hari',
        'code'                  => 'Kode Barang',
        'category_id'           => 'Kategori Barang',
        'storage_location'      => 'Lokasi Penyimpanan',
        'condition'             => 'Kondisi Barang',
        'notes'                 => 'Catatan Tambahan',
        'otp'                   => 'Kode OTP Verifikasi',
    ],

];
