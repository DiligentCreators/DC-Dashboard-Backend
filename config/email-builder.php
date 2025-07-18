<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Upload Settings
    |--------------------------------------------------------------------------
    |
    | These options control the validation for image uploads. You can define
    | the allowed file extensions and the maximum file size (in kilobytes).
    | The default max size is 2048 KB, which equals 2 MB.
    |
    */
    'image' => [
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif'],
        'max_size' => 2048,
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Emails
    |--------------------------------------------------------------------------
    |
    | This option determines whether emails should be queued or sent
    | immediately. When set to true, all emails will be queued and processed
    | by a queue worker. When false, emails will be sent instantly.
    |
    */
    'queue_emails' => false,

    /*
    |--------------------------------------------------------------------------
    | Log Email Info
    |--------------------------------------------------------------------------
    |
    | Enable this option to log info-level messages when emails are sent or
    | queued successfully. Useful for debugging or monitoring email flow.
    |
    */
    'log_info' => false,
];
