<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted' => 'Kolom :attribute harus diterima.',
    'accepted_if' => 'Kolom :attribute harus diterima bila :other adalah :value.',
    'active_url' => 'Kolom :attribute harus berupa URL yang valid.',
    'after' => 'Field :attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => 'Kolom :attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => 'Kolom :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Bidang :attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => 'Field :attribute hanya boleh berisi huruf dan angka.',
    'array' => 'Bidang :attribute harus berupa array.',
    'ascii' => 'Kolom :attribute hanya boleh berisi karakter dan simbol alfanumerik byte tunggal.',
    'before' => 'Kolom :attribute harus berisi tanggal sebelum :date.',
    'before_or_equal' => 'Kolom :attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'antara' => [
        'array' => 'Kolom :attribute harus berisi antara item :min dan :max.',
        'file' => 'Kolom :attribute harus antara :min dan :max kilobyte.',
        'numeric' => 'Kolom :attribute harus berada di antara :min dan :max.',
        'string' => 'Kolom :attribute harus berada di antara karakter :min dan :max.',
    ],

    'boolean' => 'Bidang :attribute harus benar atau salah.',
    'can' => 'Bidang :attribute berisi nilai yang tidak sah.',
    'confirmed' => 'Konfirmasi kolom :attribute tidak cocok.',
    'current_password' => 'Kata sandi salah.',
    'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
    'date_equals' => 'Field :attribute harus berisi tanggal yang sama dengan :date.',
    'date_format' => 'Kolom :attribute harus cocok dengan format :format.',
    'decimal' => 'Kolom :attribute harus memiliki :desimal desimal.',
    'declined' => 'Kolom :attribute harus ditolak.',
    'declined_if' => 'Kolom :attribute harus ditolak bila :other adalah :value.',
    'different' => 'Field :attribute dan :other harus berbeda.',
    'digits' => 'Bidang :attribute harus berupa :digits digit.',
    'digits_between' => 'Bidang :attribute harus berada di antara angka :min dan :max.',
    'dimensions' => 'Bagian :attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => 'Bidang :attribute mempunyai nilai duplikat.',
    'doesnt_end_with' => 'Bidang :attribute tidak boleh diakhiri dengan salah satu dari berikut ini: :values.',
    'doesnt_start_with' => 'Kolom :attribute tidak boleh diawali dengan salah satu dari berikut ini: :values.',
    'email' => 'Kolom :attribute harus berupa alamat email yang valid.',
    'ends_with' => 'Bidang :attribute harus diakhiri dengan salah satu dari yang berikut: :values.',
    'enum' => ' :Atribut yang dipilih tidak valid.',
    'exists' => ' :Atribut yang dipilih tidak valid.',
    'file' => 'Kolom :attribute harus berupa file.',
    'filled' => 'Kolom :attribute harus mempunyai nilai.',
    'gt' => [
        'array' => 'Kolom :attribute harus berisi lebih dari :value item.',
        'file' => 'Kolom :attribute harus lebih besar dari :value kilobytes.',
        'numeric' => 'Bidang :attribute harus lebih besar dari :value.',
        'string' => 'Bidang :attribute harus lebih besar dari karakter :value.',
    ],
    'gte' => [
        'array' => 'Kolom :attribute harus berisi item :value atau lebih.',
        'file' => 'Kolom :attribute harus lebih besar atau sama dengan :value kilobyte.',
        'numeric' => 'Bidang :attribute harus lebih besar atau sama dengan :value.',
        'string' => 'Bidang :attribute harus lebih besar atau sama dengan karakter :value.',
    ],

    'image' => 'Kolom :attribute harus berupa gambar.',
    'in' => ' :Atribut yang dipilih tidak valid.',
    'in_array' => 'Field :attribute harus ada di :other.',
    'integer' => 'Bidang :attribute harus berupa bilangan bulat.',
    'ip' => 'Kolom :attribute harus berupa alamat IP yang valid.',
    'ipv4' => 'Kolom :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => 'Kolom :attribute harus berupa alamat IPv6 yang valid.',
    'json' => 'Kolom :attribute harus berupa string JSON yang valid.',
    'huruf kecil' => 'Kolom :attribute harus menggunakan huruf kecil.',
    'lt' => [
        'array' => 'Kolom :attribute harus berisi kurang dari :value item.',
        'file' => 'Kolom :attribute harus kurang dari :value kilobytes.',
        'numeric' => 'Bidang :attribute harus kurang dari :value.',
        'string' => 'Bidang :attribute harus kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => 'Kolom :attribute tidak boleh berisi lebih dari :value item.',
        'file' => 'Kolom :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'numeric' => 'Bidang :attribute harus lebih kecil atau sama dengan :value.',
        'string' => 'Bidang :attribute harus kurang dari atau sama dengan karakter :value.',
    ],
    'mac_address' => 'Kolom :attribute harus berupa alamat MAC yang valid.',
    'maks' => [
        'array' => 'Kolom :attribute tidak boleh berisi lebih dari :max item.',
        'file' => 'Kolom :attribute tidak boleh lebih besar dari :max kilobyte.',
        'numeric' => 'Bidang :attribute tidak boleh lebih besar dari :max.',
        'string' => 'Bidang :attribute tidak boleh lebih besar dari :max karakter.',
    ],
    'max_digits' => 'Kolom :attribute tidak boleh lebih dari :max digit.',
    'mimes' => 'Kolom :attribute harus berupa file dengan tipe: :values.',
    'mimetypes' => 'Kolom :attribute harus berupa file dengan tipe: :values.',
    'menit' => [
        'array' => 'Kolom :attribute harus memiliki setidaknya :min item.',
        'file' => 'Kolom :attribute minimal harus :min kilobyte.',
        'numeric' => 'Kolom :attribute minimal harus :min.',
        'string' => 'Kolom :attribute minimal harus berisi :min karakter.',
    ],
    'min_digits' => 'Kolom :attribute harus berisi setidaknya :min digit.',
    'missing' => 'Kolom :attribute harus hilang.',
    'missing_if' => 'Kolom :attribute harus hilang jika :other adalah :value.',
    'missing_unless' => 'Kolom :attribute harus hilang kecuali :other adalah :value.',
    'missing_with' => 'Kolom :attribute harus hilang jika :values ada.',
    'missing_with_all' => 'Bidang :attribute harus hilang jika :values ada.',
    'multiple_of' => 'Bidang :attribute harus kelipatan :value.',
    'not_in' => 'Atribut yang dipilih tidak valid.',
    'not_regex' => 'Format kolom :attribute tidak valid.',
    'numeric' => 'Kolom :attribute harus berupa angka.',
    'kata sandi' => [
        'letters' => 'Bidang :attribute harus berisi setidaknya satu huruf.',
        'mixed' => 'Kolom :attribute harus berisi setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => 'Kolom :attribute harus berisi setidaknya satu angka.',
        'symbols' => 'Bidang :attribute harus berisi setidaknya satu simbol.',
        'uncompromised' => ' :Attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :atribut yang lain.',
    ],

    'present' => 'Kolom :attribute harus ada.',
    'prohibited' => 'Bidang :attribute dilarang.',
    'prohibited_if' => 'Bidang :attribute dilarang jika :other adalah :value.',
    'prohibited_unless' => 'Kolom :attribute dilarang kecuali :other ada di :values.',
    'prohibits' => 'Bidang :attribute melarang :other untuk hadir.',
    'regex' => 'Format kolom :attribute tidak valid.',
    'required' => 'Kolom :attribute wajib diisi.',
    'required_array_keys' => 'Kolom :attribute harus berisi entri untuk: :values.',
    'required_if' => 'Kolom :attribute wajib diisi jika :other adalah :value.',
    'required_if_accepted' => 'Kolom :attribute wajib diisi jika :other diterima.',
    'required_unless' => 'Kolom :attribute wajib diisi kecuali :other ada di :values.',
    'required_with' => 'Kolom :attribute wajib diisi bila :values ada.',
    'required_with_all' => 'Bidang :attribute diperlukan jika :values ada.',
    'required_without' => 'Bidang :attribute diperlukan bila :values tidak ada.',
    'required_without_all' => 'Kolom :attribute wajib diisi jika :values tidak ada.',
    'same' => 'Kolom :attribute harus cocok dengan :other.',
    'ukuran' => [
        'array' => 'Field :attribute harus berisi item :size.',
        'file' => 'Kolom :attribute harus :ukuran kilobyte.',
        'numeric' => 'Bidang :attribute harus :ukuran.',
        'string' => 'Kolom :attribute harus berisi :karakter ukuran.',
    ],
    'starts_with' => 'Bidang :attribute harus diawali dengan salah satu dari yang berikut: :values.',
    'string' => 'Bidang :attribute harus berupa string.',
    'timezone' => 'Kolom :attribute harus berupa zona waktu yang valid.',
    'unique' => ' :attribute sudah dipakai.',
    'uploaded' => ' :Attribute gagal diunggah.',
    'uppercase' => 'Bidang :attribute harus menggunakan huruf besar.',
    'url' => 'Kolom :attribute harus berupa URL yang valid.',
    'ulid' => 'Kolom :attribute harus berupa ULID yang valid.',
    'uuid' => 'Field :attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
