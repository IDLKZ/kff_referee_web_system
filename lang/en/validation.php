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

    'attributes' => [
        'login' => 'login',
        'password' => 'password',
        'email' => 'email',
        'phone' => 'phone',
        'username' => 'username',
        'file' => 'file',
    ],
];
