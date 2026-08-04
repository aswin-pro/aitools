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

    "accepted" => "Atribut: harus diterima.",
    "accepted_if" => "Atribut: Atribut harus diterima ketika: Lainnya adalah: nilai.",
    "active_url" => "Atribut: bukan URL yang valid.",
    "after" => "Atribut: Tanggal: Tanggal: Tanggal.",
    "after_or_equal" => "Atribut: harus berupa tanggal setelah atau sama dengan: tanggal.",
    "alpha" => "Atribut: Atribut hanya mengandung huruf.",
    "alpha_dash" => "Atribut: Atribut hanya berisi huruf, angka, tanda hubung, dan garis bawah.",
    "alpha_num" => "Atribut: Atribut hanya berisi huruf dan angka.",
    "array" => "Atribut: Atribut harus berupa array.",
    "before" => "Atribut: harus berupa tanggal sebelum: Tanggal.",
    "before_or_equal" => "Atribut: harus berupa tanggal sebelum atau sama dengan: Tanggal.",
    "between" => [
        "numeric" => "Atribut: Atribut harus antara: min dan: max.",
        "file" => "Atribut: harus antara: min dan: max kilobytes.",
        "string" => "Atribut: Karakter Min dan: Max.",
        "array" => "Atribut: Atribut yang harus dimiliki antara: min dan: item maks."
    ],
    "boolean" => "Bidang: Atribut harus benar atau salah.",
    "confirmed" => "Konfirmasi atribut: tidak cocok.",
    "current_password" => "Kata sandi salah.",
    "date" => "Atribut: bukan tanggal yang valid.",
    "date_equals" => "Atribut: Tanggal yang sama dengan: Tanggal.",
    "date_format" => "Atribut: Format Format: Format.",
    "declined" => "Atribut: harus ditolak.",
    "declined_if" => "Atribut: harus ditolak ketika: Lainnya adalah: nilai.",
    "different" => "Atribut:: Lainnya harus berbeda.",
    "digits" => "Atribut: Digit digit.",
    "digits_between" => "Atribut: Atribut harus antara: Min dan: Max Digit.",
    "dimensions" => "Atribut: memiliki dimensi gambar yang tidak valid.",
    "distinct" => ": Bidang atribut memiliki nilai duplikat.",
    "email" => "Atribut: Atribut harus berupa alamat email yang valid.",
    "ends_with" => "Atribut: harus diakhiri dengan salah satu dari yang berikut :: nilai.",
    "enum" => "Atribut yang dipilih: tidak valid.",
    "exists" => "Atribut yang dipilih: tidak valid.",
    "file" => "Atribut: harus berupa file.",
    "filled" => "Bidang: Atribut harus memiliki nilai.",
    "gt" => [
        "numeric" => "Atribut: Nilai harus lebih besar dari:.",
        "file" => "Atribut: Atribut harus lebih besar dari: nilai kilobytes.",
        "string" => "Atribut: Karakter nilai lebih besar dari: nilai.",
        "array" => "Atribut: Atribut harus memiliki lebih dari: item nilai."
    ],
    "gte" => [
        "numeric" => "Atribut: harus lebih besar dari atau sama dengan: nilai.",
        "file" => "Atribut: harus lebih besar dari atau sama dengan: nilai kilobytes.",
        "string" => "Atribut: harus lebih besar dari atau sama dengan: nilai karakter.",
        "array" => "Atribut: Atribut: Item nilai atau lebih."
    ],
    "image" => "Atribut: Atribut harus merupakan gambar.",
    "in" => "Atribut yang dipilih: tidak valid.",
    "in_array" => ": Bidang atribut tidak ada di: Lainnya.",
    "integer" => "Atribut: harus berupa bilangan bulat.",
    "ip" => "Atribut: Atribut harus merupakan alamat IP yang valid.",
    "ipv4" => "Atribut: Atribut harus berupa alamat IPv4 yang valid.",
    "ipv6" => "Atribut: Atribut harus berupa alamat IPv6 yang valid.",
    "json" => "Atribut: harus berupa string JSON yang valid.",
    "lt" => [
        "numeric" => "Atribut: Nilai: Nilai.",
        "file" => "Atribut: Atribut harus kurang dari: nilai kilobytes.",
        "string" => "Atribut: Kurang dari: nilai karakter.",
        "array" => "Atribut: item nilai kurang dari: item nilai."
    ],
    "lte" => [
        "numeric" => "Atribut: harus kurang dari atau sama dengan: nilai.",
        "file" => "Atribut: Atribut harus kurang dari atau sama dengan: nilai kilobytes.",
        "string" => "Atribut: harus kurang dari atau sama dengan: karakter nilai.",
        "array" => "Atribut: Tidak boleh memiliki lebih dari: item nilai."
    ],
    "mac_address" => "Atribut: Atribut harus berupa alamat MAC yang valid.",
    "max" => [
        "numeric" => "Atribut: tidak boleh lebih besar dari: maks.",
        "file" => "Atribut: tidak boleh lebih besar dari: max kilobytes.",
        "string" => "Atribut: Karakter Max tidak lebih besar dari: Max.",
        "array" => "Atribut: Tidak boleh memiliki lebih dari: item maks."
    ],
    "mimes" => "Atribut: Atribut harus merupakan file jenis :: nilai.",
    "mimetypes" => "Atribut: Atribut harus merupakan file jenis :: nilai.",
    "min" => [
        "numeric" => "Atribut: setidaknya harus: min.",
        "file" => "Atribut: setidaknya harus: min kilobytes.",
        "string" => "Atribut: setidaknya: karakter min.",
        "array" => "Atribut: Atribut harus memiliki setidaknya: item min."
    ],
    "multiple_of" => "Atribut: Nilai: nilai.",
    "not_in" => "Atribut yang dipilih: tidak valid.",
    "not_regex" => "Format atribut: tidak valid.",
    "numeric" => "Atribut: Atribut harus menjadi angka.",
    "password" => "Kata sandi salah.",
    "present" => ": Bidang atribut harus ada.",
    "prohibited" => ": Bidang atribut dilarang.",
    "prohibited_if" => "Bidang: Atribut dilarang ketika: Lainnya adalah: nilai.",
    "prohibited_unless" => "Bidang: Atribut dilarang kecuali: Lainnya ada di: Nilai.",
    "prohibits" => ": Larang bidang Atribut: Lainnya dari hadir.",
    "regex" => "Format atribut: tidak valid.",
    "required" => "Bidang Atribut: diperlukan.",
    "required_array_keys" => "Bidang: Atribut harus berisi entri untuk :: Nilai.",
    "required_if" => "Bidang Atribut: Diperlukan saat: Lainnya adalah: Nilai.",
    "required_unless" => "Bidang: Atribut diperlukan kecuali: Lainnya ada di: Nilai.",
    "required_with" => "Bidang Atribut: Diperlukan saat: Nilai ada.",
    "required_with_all" => "Bidang Atribut: Diperlukan saat: Nilai ada.",
    "required_without" => "Bidang: Atribut diperlukan saat: Nilai tidak ada.",
    "required_without_all" => "Bidang: Atribut diperlukan ketika tidak ada: nilai ada.",
    "same" => "Atribut:: Lainnya harus cocok.",
    "size" => [
        "numeric" => "Atribut: Ukuran.",
        "file" => "Atribut: Atribut harus: ukuran kilobytes.",
        "string" => "Atribut: Karakter ukuran.",
        "array" => "Atribut: Atribut harus berisi: item ukuran."
    ],
    "starts_with" => "Atribut: harus dimulai dengan salah satu dari yang berikut :: nilai.",
    "string" => "Atribut: harus berupa string.",
    "timezone" => "Atribut: Atribut harus berupa zona waktu yang valid.",
    "unique" => "Atribut: telah diambil.",
    "uploaded" => "Atribut: Gagal mengunggah.",
    "url" => "Atribut: URL yang valid.",
    "uuid" => "Atribut: harus berupa UUID yang valid.",
    "custom" => [
        "attribute-name" => [
            "rule-name" => "pesan khusus"
        ]
    ],
    "attributes" => []
];
