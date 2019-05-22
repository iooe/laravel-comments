<?php

return [
    // The model which creates the comments aka the User model
    'commenter' => \App\User::class,
    'ui' => 'bootstrap4',
    'purifier' => [
        'HTML_Allowed' => 'p',
    ],
    'route' => [
        'root' => 'api',
        'group' => 'comments'
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
            'preprocessor' => [
                'user' => null,
                'comment' => null
            ]
        ]
    ]
];