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

    'attributes' => [
        'login' => 'логин',
        'password' => 'пароль',
        'email' => 'email',
        'phone' => 'телефон',
        'username' => 'имя пользователя',
    ],
];
