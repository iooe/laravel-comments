<?php

namespace BeyondCode\Comments\Tests;

use tizis\laraComments\Providers\ServiceProvider;
use tizis\laraComments\Providers\AuthServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp():void
    {
        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'sqlite']);
        $this->setUpDatabase();
        $this->createUser();
    }

    protected function setUpDatabase():void
    {
        include_once __DIR__ . '/../database/migrations/2018_06_30_113500_create_comments_table.php';
        (new \CreateCommentsTable())->up();
        $this->app['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });
    }

    protected function createUser():void
    {
        User::forceCreate([
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => 'test'
        ]);
    }

    protected function getPackageProviders($app):array
    {
        return [
            ServiceProvider::class,
            AuthServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app):void
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('app.key', 'base64:6Cu/ozj4gPtIjmXjr8EdVnGFNsdRqZfHfVjQkmTlg4Y=');
    }
}