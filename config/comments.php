<?php

return [
    // The model which creates the comments aka the User model
    'models' => [
        /**
         * Commenter model
         */
        'commenter' => \App\User::class,
        /**
         * Comment model
         */
        'comment' => \App\Comment::class
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
    ],
    
/**
     * Rewrite the original model name with an arbitrary name.
     * (This can be useful if you want to hide the original path of the model.)
     *
     * `'\Path\To\Real\Model' => 'FakeModelName',`
     * @example '\App\Post' => 'SuperPostModel',
     */
    'rewriteModel' => [],

    /**
     * Rewrite the original model name with an arbitrary name.
     * (This can be useful if you want to hide the original path of the model.)
     *
     * `'\Path\To\Real\Model' => 'myVirtualIdAttribute',`
     * @example '\App\Post' => 'myId',
     * and in your Post class added method for virtual attribute:
     * @example `public function getMyIdAttribute(){ return $this->id;}`
     */
    'rewriteIdAttribute' => [],


    /**
     * Rewrite the standard (`findOrFail`) search method for records in the commented model.
     * (This can be useful if you want to hide the original id.)
     *
     * `'Path\To\Model' => 'methodName',`
     * @example '\App\Post' =>  'myMethod' ,
     * and in your Post class added method for finding record:
     * @example `public static function myFindMethod($id){ return self::findOrFail($id);}`
     *
     * As a result, the method will be called: App\Post::myFindMethod($request->commentable_id);
     */
    'rewriteFindMethod' => [],
    
    
];
