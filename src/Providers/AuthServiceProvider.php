<?php

namespace tizis\laraComments\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use tizis\laraComments\Entity\Comment;
use tizis\laraComments\Policies\CommentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('comments', CommentPolicy::class, [
            'delete' => 'delete',
            'reply' => 'reply',
            'edit' => 'edit'
        ]);
    }
}