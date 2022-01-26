<?php

return [
    // The model which creates the comments aka the User model
    'models' => [
        'commenter' => \App\User::class,
        'comment' => \App\Comment::class,
        'votes' => \tizis\laraComments\Entity\CommentVotes::class,
    ],
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
            'commentable' => '\App\Post',
            'commenter' => '\App\User'
        ]
    ],
    /**
     * Only for API
     *
     * @example ['get']['preprocessor']['user'] => App\UseCases\CommentPreprocessor\User::class
     */
    'api' => [
        'get' => [
            'preprocessor' => [
                'user' => null,
                'comment' => null
            ]
        ]
    ]
];