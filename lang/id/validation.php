<?php

declare(strict_types=1);

return [
    'max' => [
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
    ],
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
