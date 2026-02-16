<?php

return [
    'required' => 'The :attribute field is required.',
    'file' => 'The :attribute must be a file.',
    'string' => 'The :attribute must be a string.',
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max' => [
        'string' => 'The :attribute must not exceed :max characters.',
    ],
    'email' => 'The :attribute must be a valid email address.',

    // File validation
    'file_too_large' => 'File size must not exceed :max KB.',
    'invalid_file_extension' => 'Invalid file format. Allowed: :values.',
    'invalid_file_type' => 'Invalid file type.',
    'file_not_found' => 'File not found.',
    'file_upload_failed' => 'Failed to upload file.',

    // User validation
    'invalid_phone_format' => 'Invalid phone format. Use: +7(777)123-45-67',
    'invalid_username_format' => 'Username can only contain Latin letters, numbers, underscore and @',
    'invalid_iin_format' => 'IIN must consist of 12 digits',
    'birth_date_minimum_18' => 'User must be at least 18 years old',

    'attributes' => [
        'login' => 'login',
        'password' => 'password',
        'email' => 'email',
        'phone' => 'phone',
        'username' => 'username',
        'file' => 'file',
        'birth_date' => 'birth date',
    ],

    // Match Logists validation
    'match_id_required' => 'Select a match.',
    'match_id_exists' => 'Match not found.',
    'logist_id_required' => 'Select a user.',
    'logist_id_exists' => 'User not found.',
    'logist_must_be_active' => 'User must be active.',
    'logist_must_be_logistician' => 'User must have logistician role.',

    'match' => 'Match',
    'logist' => 'Logist',
];
