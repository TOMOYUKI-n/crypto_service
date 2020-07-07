<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Log;
use Exception;
use App\User;


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
    
    protected function redirectTo(){
        
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

        } catch(\Exception $e) {
            return redirect('/login')->with('oauth_error', '予期せぬエラーが発生しました');
        }
        //Log::debug('111111111111');
        // ユーザー情報の中からEmailと名前を取得
        if ($email = $providerUser->getEmail()) {

            // ユーザーがDBに存在すればリダイレクト、存在しなければ新規登録
            Auth::login(User::firstOrCreate([
                'email' => $email
            ],
            [
                'name' => $providerUser->getName()
            ]
            ));
            //return redirect($this->redirectTo);
            return redirect('/trend')->with('scc_message', __(('Login!')));
        } else {
            // 存在しない場合は通常のログイン画面へ遷移する
            return redirect('/login')->with('oauth_error', 'メールアドレスが取得できませんでした');
        }
    }
}
