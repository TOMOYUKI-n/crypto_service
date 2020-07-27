<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    private $redirectTo;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/trend';

    protected function redirectTo()
    {

        session()->flash('flash_message', __('Login!'));
        return '/trend';

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * OAuth認証先にリダイレクト
     *
     * @param str $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
        //Log::debug('providerの中身'.$provider);
    }

    /**
     * OAuth認証の結果受け取り
     *
     * @param str $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        //twitterからユーザー情報を取得する
        try {
            $providerUser = \Socialite::with($provider)->user();
            //追記　アクセストークン取得
            $token = $providerUser->token;
            $tokenSecret = $providerUser->tokenSecret;

        } catch (\Exception $e) {
            return redirect('/login')->with('error_message', '予期せぬエラーが発生しました');
        }

        // ユーザー情報の中からEmailと名前を取得
        if ($email = $providerUser->getEmail()) {
            Auth::login(User::firstOrCreate(
                [
                    'email' => $email,
                ],
                [
                    'twitter_id' => $providerUser->id,
                    'name' => $providerUser->getName(),
                    'twitter_name' => $providerUser->getName(),
                    'twitter_avatar_original' => $providerUser->avatar_original,
                    'twitter_oauth_token' => $token,
                    'twitter_oauth_token_secret' => $tokenSecret,
                    'email' => $email,
                ]
            ));
            return redirect('/trend')->with('flash_message', 'ログインしました');
        } else {
            // 存在しない場合は通常のログイン画面へ遷移する
            // Log::Debug($providerUser);
            return redirect('/misslogin')->with('error_message', 'メールアドレスを取得できませんでした');
        }
    }
}
