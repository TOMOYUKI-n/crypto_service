<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
class TwitterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    protected $defer = true;
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }


    public function register()
    {
        //TwitterOAuthクラスのインスタンスを'twitter'という名前で登録
        //$this->app->singleton('twitter', function () {
        $this->app->bind('twitter', function () {
            //config/twitter.phpの中身を参照し、インスタンスを作成
            $config = config('twitter');
            return new TwitterOAuth($config['api_key'], $config['secret_key'], $config['access_token'], $config['access_token_secret']);
        });
    }
    public function provides()
    {
        return ['twitter'];
    }
}
