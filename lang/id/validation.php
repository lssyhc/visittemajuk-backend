<?php

declare(strict_types=1);

return [
    'image' => ':attribute harus berupa gambar.',
    'integer' => ':attribute harus berupa angka bulat.',
    'max' => [
        'file' => ':attribute tidak boleh lebih dari :max KB.',
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'min' => [
        'file' => ':attribute harus berukuran minimal :min KB.',
        'string' => ':attribute harus berupa teks minimal :min karakter.',
        'numeric' => ':attribute harus berupa angka minimal :min.',
        'array' => ':attribute harus memiliki minimal :min item.',
    ],
    'mimes' => ':attribute harus berupa file dengan tipe: :values.',
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'uploaded' => ':attribute gagal diunggah.',
    'url' => ':attribute harus berupa URL yang valid.',
    'attributes' => [
        'username' => 'username',
        'password' => 'kata sandi',
        'image' => 'Gambar utama',
        'gallery.*' => 'URL galeri',
    ],
];
