<?php

return [
    'required' => ':attribute өрісі міндетті түрде толтырылуы керек.',
    'string' => ':attribute өрісі жол болуы керек.',
    'min' => [
        'string' => ':attribute өрісі кемінде :min таңбадан тұруы керек.',
    ],
    'max' => [
        'string' => ':attribute өрісі :max таңбадан аспауы керек.',
    ],
    'email' => ':attribute өрісі дұрыс email мекенжайы болуы керек.',

    // File validation
    'file_too_large' => 'Файл өлшемі :max КБ-дан аспауы керек.',
    'invalid_file_extension' => 'Рұқсат етілмеген файл пішімі. Рұқсат етілген: :values.',
    'invalid_file_type' => 'Рұқсат етілмеген файл түрі.',
    'file_not_found' => 'Файл табылмады.',
    'file_upload_failed' => 'Файлды жүктеу мүмкін болмады.',

    // User validation
    'invalid_phone_format' => 'Телефон форматы дұрыс емес. Пайдаланыңыз: +7(777)123-45-67',
    'invalid_username_format' => 'Логин тек латын әріптері, сандар, астыңғы сызық және @ құрайды',
    'invalid_iin_format' => 'ЖСН 12 цифрдан тұруы керек',

    'attributes' => [
        'login' => 'логин',
        'password' => 'құпия сөз',
        'email' => 'email',
        'phone' => 'телефон',
        'username' => 'пайдаланушы аты',
        'file' => 'файл',
    ],
];
