<?php

return [
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute must be a string.',
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max' => [
        'string' => 'The :attribute must not exceed :max characters.',
    ],
    'email' => 'The :attribute must be a valid email address.',

    'attributes' => [
        'login' => 'login',
        'password' => 'password',
        'email' => 'email',
        'phone' => 'phone',
        'username' => 'username',
    ],
];
