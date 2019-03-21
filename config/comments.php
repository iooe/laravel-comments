<?php

return [
    // The model which creates the comments aka the User model
    'commenter' => \App\User::class,
    'ui' => 'bootstrap4',
    'purifier' => [
        'HTML_Allowed' => 'p',
    ],
    'policy_prefix' => 'comments',
    'testing' => [
        'seeding' => [
            'commentable' => 'App\User',
            'commenter' => 'App\Post'
        ]
    ],
    'api' => [
        'get' => [
            'comment' => [
                'preprocessor' => null
            ]
        ]
    ]
];