<?php

return [
    "title" => "Penginstal NativeCode",
    "next" => "Langkah selanjutnya",
    "back" => "Sebelumnya",
    "finish" => "Memasang",
    "forms" => [
        "errorTitle" => "Kesalahan berikut terjadi:"
    ],
    "welcome" => [
        "templateTitle" => "Selamat datang",
        "title" => "Penginstal NativeCode",
        "message" => "Wisaya Instalasi dan Pengaturan Mudah.",
        "next" => "Periksa persyaratan"
    ],
    "requirements" => [
        "templateTitle" => "Langkah 1 | ",
        "title" => "Persyaratan server",
        "next" => "Periksa izin"
    ],
    "permissions" => [
        "templateTitle" => "Langkah 2 | ",
        "title" => "Izin",
        "next" => "Konfigurasikan lingkungan"
    ],
    "environment" => [
        "menu" => [
            "templateTitle" => "Langkah 3 | ",
            "title" => "Pengaturan Lingkungan",
            "desc" => "Pilih bagaimana Anda ingin mengkonfigurasi aplikasi <code>.env</code> mengajukan.",
            "wizard-button" => "Formulir Pengaturan Wizard",
            "classic-button" => "Editor Teks Klasik"
        ],
        "wizard" => [
            "templateTitle" => "Langkah 3 | ",
            "title" => "Dipandu <code>.env</code> Wizard",
            "tabs" => [
                "environment" => "Lingkungan",
                "database" => "Database",
                "application" => "Aplikasi"
            ],
            "form" => [
                "name_required" => "Diperlukan nama lingkungan.",
                "app_name_label" => "Nama Aplikasi",
                "app_name_placeholder" => "Nama Aplikasi",
                "app_environment_label" => "Lingkungan aplikasi",
                "app_environment_label_local" => "Lokal",
                "app_environment_label_developement" => "Perkembangan",
                "app_environment_label_qa" => "Qa",
                "app_environment_label_production" => "Produksi",
                "app_debug_label" => "Aplikasi Debug",
                "app_debug_label_true" => "BENAR",
                "app_debug_label_false" => "PALSU",
                "app_log_level_label" => "Level log aplikasi",
                "app_log_level_label_debug" => "debug",
                "app_log_level_label_info" => "info",
                "app_log_level_label_notice" => "melihat",
                "app_log_level_label_warning" => "peringatan",
                "app_log_level_label_error" => "kesalahan",
                "app_log_level_label_critical" => "kritis",
                "app_log_level_label_alert" => "peringatan",
                "app_log_level_label_emergency" => "keadaan darurat",
                "app_url_label" => "URL aplikasi",
                "app_url_placeholder" => "URL aplikasi",
                "db_connection_failed" => "Tidak dapat terhubung ke database.",
                "db_connection_label" => "Koneksi basis data",
                "db_connection_label_mysql" => "mysql",
                "db_connection_label_sqlite" => "sqlite",
                "db_connection_label_pgsql" => "pgsql",
                "db_connection_label_sqlsrv" => "sqlsrv",
                "db_host_label" => "Host Basis Data",
                "db_host_placeholder" => "Host Basis Data",
                "db_port_label" => "Port database",
                "db_port_placeholder" => "Port database",
                "db_name_label" => "Nama Basis Data",
                "db_name_placeholder" => "Nama Basis Data",
                "db_username_label" => "Nama Pengguna Basis Data",
                "db_username_placeholder" => "Nama Pengguna Basis Data",
                "db_password_label" => "Kata sandi basis data",
                "db_password_placeholder" => "Kata sandi basis data",
                "app_tabs" => [
                    "more_info" => "Info lebih lanjut",
                    "broadcasting_title" => "Penyiaran, caching, sesi, & antrian",
                    "broadcasting_label" => "SIRKCASI SIMPAN",
                    "broadcasting_placeholder" => "SIRKCASI SIMPAN",
                    "cache_label" => "Driver cache",
                    "cache_placeholder" => "Driver cache",
                    "session_label" => "Pengemudi sesi",
                    "session_placeholder" => "Pengemudi sesi",
                    "queue_label" => "Pengemudi antrian",
                    "queue_placeholder" => "Pengemudi antrian",
                    "redis_label" => "Pengemudi redis",
                    "redis_host" => "Host Redis",
                    "redis_password" => "Kata Sandi Redis",
                    "redis_port" => "Port Redis",
                    "mail_label" => "Surat",
                    "mail_driver_label" => "Sopir surat",
                    "mail_driver_placeholder" => "Sopir surat",
                    "mail_host_label" => "Host surat",
                    "mail_host_placeholder" => "Host surat",
                    "mail_port_label" => "Port Mail",
                    "mail_port_placeholder" => "Port Mail",
                    "mail_username_label" => "Mail Username",
                    "mail_username_placeholder" => "Mail Username",
                    "mail_password_label" => "Kata Sandi Surat",
                    "mail_password_placeholder" => "Kata Sandi Surat",
                    "mail_encryption_label" => "Enkripsi surat",
                    "mail_encryption_placeholder" => "Enkripsi surat",
                    "pusher_label" => "Pusher",
                    "pusher_app_id_label" => "ID Aplikasi Pusher",
                    "pusher_app_id_palceholder" => "ID Aplikasi Pusher",
                    "pusher_app_key_label" => "Kunci Aplikasi Pusher",
                    "pusher_app_key_palceholder" => "Kunci Aplikasi Pusher",
                    "pusher_app_secret_label" => "Rahasia Aplikasi Pusher",
                    "pusher_app_secret_palceholder" => "Rahasia Aplikasi Pusher"
                ],
                "buttons" => [
                    "setup_database" => "Pengaturan Basis Data",
                    "setup_application" => "Aplikasi Pengaturan",
                    "install" => "Memasang"
                ],
                "app_environment_label_other" => "Lainnya",
                "app_environment_placeholder_other" => "Masukkan lingkungan Anda ..."
            ]
        ],
        "classic" => [
            "templateTitle" => "Langkah 3 | ",
            "title" => "Editor Lingkungan Klasik",
            "save" => "Simpan .env",
            "back" => "Gunakan Form Wizard",
            "install" => "Simpan dan instal"
        ],
        "success" => "Pengaturan file .env Anda telah disimpan.",
        "errors" => "Tidak dapat menyimpan file .env, silakan buat secara manual."
    ],
    "install" => "Memasang",
    "installed" => [
        "success_log_message" => "Installer nativecode berhasil diinstal di "
    ],
    "final" => [
        "title" => "Instalasi selesai",
        "templateTitle" => "Instalasi selesai",
        "finished" => "Aplikasi telah berhasil diinstal.",
        "migration" => "Output Migrasi & Konsol Bibit:",
        "console" => "Output Konsol Aplikasi:",
        "log" => "Entri Log Instalasi:",
        "env" => "File .env final:",
        "exit" => "Klik di sini untuk keluar"
    ],
    "updater" => [
        "title" => "NativeCode Updater",
        "welcome" => [
            "title" => "Selamat datang di pembaruan",
            "message" => "Selamat datang di Wizard Pembaruan."
        ],
        "overview" => [
            "title" => "Ringkasan",
            "message" => "Ada 1 pembaruan. | Ada: pembaruan nomor.",
            "install_updates" => "Instal Pembaruan"
        ],
        "final" => [
            "title" => "Selesai",
            "finished" => "Basis data aplikasi telah berhasil diperbarui.",
            "exit" => "Klik di sini untuk keluar"
        ],
        "log" => [
            "success_message" => "Installer nativeCode berhasil diperbarui "
        ]
    ]
];
