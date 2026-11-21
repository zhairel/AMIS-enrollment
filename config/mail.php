<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send all email
    | messages unless another mailer is explicitly specified when sending
    | the message. All additional mailers can be configured within the
    | "mailers" array. Examples of each type of mailer are provided.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers that can be used
    | when delivering an email. You may specify which one you're using for
    | your mailers below. You may also add additional mailers if needed.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "resend", "log", "array",
    |            "failover", "roundrobin"
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => 15,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', env('MAIL_USERNAME', 'amisonlinesupport@gmail.com')),
                'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Al Munawwara Islamic School')),
            ],
        ],

        'smtp_backup' => [
            'transport' => 'smtp',
            'host' => env('MAIL_BACKUP_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_BACKUP_PORT', 587),
            'encryption' => env('MAIL_BACKUP_ENCRYPTION', 'tls'),
            'username' => env('MAIL_BACKUP_USERNAME'),
            'password' => env('MAIL_BACKUP_PASSWORD'),
            'timeout' => 15,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
            'from' => [
                'address' => env('MAIL_BACKUP_FROM_ADDRESS', env('MAIL_BACKUP_USERNAME')),
                'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Al Munawwara Islamic School')),
            ],
        ],

        'smtp_backup_2' => [
            'transport' => 'smtp',
            'host' => env('MAIL_BACKUP_2_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_BACKUP_2_PORT', 587),
            'encryption' => env('MAIL_BACKUP_2_ENCRYPTION', 'tls'),
            'username' => env('MAIL_BACKUP_2_USERNAME'),
            'password' => env('MAIL_BACKUP_2_PASSWORD'),
            'timeout' => 15,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
            'from' => [
                'address' => env('MAIL_BACKUP_2_FROM_ADDRESS', env('MAIL_BACKUP_2_USERNAME')),
                'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Al Munawwara Islamic School')),
            ],
        ],

        'smtp_backup_3' => [
            'transport' => 'smtp',
            'host' => env('MAIL_BACKUP_3_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_BACKUP_3_PORT', 587),
            'encryption' => env('MAIL_BACKUP_3_ENCRYPTION', 'tls'),
            'username' => env('MAIL_BACKUP_3_USERNAME'),
            'password' => env('MAIL_BACKUP_3_PASSWORD'),
            'timeout' => 15,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
            'from' => [
                'address' => env('MAIL_BACKUP_3_FROM_ADDRESS', env('MAIL_BACKUP_3_USERNAME')),
                'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Al Munawwara Islamic School')),
            ],
        ],

        'smtp_backup_4' => [
            'transport' => 'smtp',
            'host' => env('MAIL_BACKUP_4_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_BACKUP_4_PORT', 587),
            'encryption' => env('MAIL_BACKUP_4_ENCRYPTION', 'tls'),
            'username' => env('MAIL_BACKUP_4_USERNAME'),
            'password' => env('MAIL_BACKUP_4_PASSWORD'),
            'timeout' => 15,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
            'from' => [
                'address' => env('MAIL_BACKUP_4_FROM_ADDRESS', env('MAIL_BACKUP_4_USERNAME')),
                'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Al Munawwara Islamic School')),
            ],
        ],

        'inquiries' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_INQUIRIES_SCHEME', 'smtps'),
            'host' => env('MAIL_INQUIRIES_HOST', 'mail.amis.edu.ph'),
            'port' => env('MAIL_INQUIRIES_PORT', 465),
            'encryption' => env('MAIL_INQUIRIES_ENCRYPTION', 'ssl'),
            'username' => env('MAIL_INQUIRIES_USERNAME'),
            'password' => env('MAIL_INQUIRIES_PASSWORD'),
            'timeout' => 15,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
            'from' => [
                'address' => env('MAIL_INQUIRIES_FROM_ADDRESS', env('MAIL_INQUIRIES_USERNAME', 'noreply@amis.edu.ph')),
                'name' => env('MAIL_INQUIRIES_FROM_NAME', env('MAIL_FROM_NAME', 'Al Munawwara Islamic School')),
            ],
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
            'retry_after' => 60,
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
            'retry_after' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all emails sent by your application to be sent from
    | the same address. Here you may specify a name and address that is
    | used globally for all emails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Dynamic Gmail Pool (comma or semicolon-separated email:app_password)
    |--------------------------------------------------------------------------
    */
    'gmail_pool' => env('MAIL_GMAIL_POOL', ''),

];
