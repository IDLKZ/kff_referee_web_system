<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения.',
    'string' => 'Поле :attribute должно быть строкой.',
    'min' => [
        'string' => 'Поле :attribute должно содержать минимум :min символов.',
    ],
    'max' => [
        'string' => 'Поле :attribute не должно превышать :max символов.',
    ],
    'email' => 'Поле :attribute должно быть корректным email адресом.',

    // File validation
    'file_too_large' => 'Размер файла не должен превышать :max КБ.',
    'invalid_file_extension' => 'Недопустимый формат файла. Разрешены: :values.',
    'invalid_file_type' => 'Недопустимый тип файла.',
    'file_not_found' => 'Файл не найден.',
    'file_upload_failed' => 'Не удалось загрузить файл.',

    // User validation
    'invalid_phone_format' => 'Неверный формат телефона. Используйте формат: +7(777)123-45-67',
    'invalid_username_format' => 'Логин может содержать только латинские буквы, цифры, подчеркивание и @',
    'invalid_iin_format' => 'ИИН должен состоять из 12 цифр',

    'attributes' => [
        'login' => 'логин',
        'password' => 'пароль',
        'email' => 'email',
        'phone' => 'телефон',
        'username' => 'имя пользователя',
        'file' => 'файл',
    ],

    // Match Logists validation
    'match_id_required' => 'Выберите матч.',
    'match_id_exists' => 'Матч не найден.',
    'logist_id_required' => 'Выберите пользователя.',
    'logist_id_exists' => 'Пользователь не найден.',
    'logist_must_be_active' => 'Пользователь должен быть активным.',
    'logist_must_be_logistician' => 'Пользователь должен иметь роль логиста.',

    'match' => 'Матч',
    'logist' => 'Логист',
];
