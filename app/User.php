<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Tweet;
use App\Coin;
use App\Time;
use App\Week;
use App\Day;
use App\Hour;
use App\Minute;
use App\User;
use GuzzleHttp\Client;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // ======================================================================
    // Google News API接続->取得　処理
    // ======================================================================
    public static function  get_news($keywords, $max_num){  

        set_time_limit(90);
        
        $API_BASE_URL = "https://news.google.com/rss/search?q=";
        //----　キーワードの文字コード変更 + 日本語指定
        $query = urlencode(mb_convert_encoding($keywords,"UTF-8", "auto"));
        $lang = "&hl=ja&gl=JP&ceid=JP:ja";

        //---- APIへのリクエストURL生成 + APIにアクセス
        $api_url = $API_BASE_URL.$query.$lang;
        $contents = file_get_contents($api_url);

        //---- レスポンス結果をsimplexmlElementObject形式で格納
        $xml = simplexml_load_string($contents);
        //---- 最終取得日, 記事の箇所を取り出す
        $lastBuildDate = $xml->channel->lastBuildDate;
        $item = $xml->channel->item;
       
        //----　記事のタイトルとURLを取り出して配列に格納
        for ($i = 0; $i < $max_num; $i++) {
            //----　タイトルの文字コード変換
            $list[$i]['title'] = mb_convert_encoding($item[$i]->title ,"UTF-8", "auto");
            //----　リンクの設定
            $list[$i]['link'] = $item[$i]->link;
            //----　時刻の表示変換
            $temp_date = $item[$i]->pubDate;
            $list[$i]['pubDate'] = date('Y年m月d日', strtotime($temp_date));
            //----　文字列への表示変換
            $list[$i]['source'] = (string)($item[$i]->source);
            //----　時刻の表示変換
            $lastBuildDate = date("Y-m-d H:i:s");
            $list[$i]['lastBuildDate'] = $lastBuildDate;
            
        }
        return $list;
    }





}
