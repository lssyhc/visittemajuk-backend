<?php

declare(strict_types=1);

return [
    'max' => [
        'file' => ':attribute tidak boleh lebih dari :max KB.',
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'mimes' => ':attribute harus berupa file dengan tipe: :values.',
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'url' => ':attribute harus berupa URL yang valid.',
    'attributes' => [
        'username' => 'username',
        'password' => 'kata sandi',
        'image' => 'Gambar utama',
        'gallery.*' => 'URL galeri',
    ],
];
