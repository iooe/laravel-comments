<?php

return [
    // The model which creates the comments aka the User model
    'commenter' => \App\User::class,
    'ui' => 'bootstrap4',
    'purifier' => [
        'HTML_Allowed' => 'p',
    ],
    'testing' => [
        'seeding' => [
            'commentable' => 'App\User',
            'commenter' => 'App\Post'
        ]
    ]
];