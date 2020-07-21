<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //
    protected $table = 'account';
    protected $fillable = [
        'id_str',
    ];

    public static function autoFollow($userid)
    {
        //$targetId = '1252388152221655049';//accountD
        //$userid = '1284754391442968578';//もんぶらん

        try {
            $twitter_api = \Twitter::post("friendships/create", [
                'user_id' => $userid,
            ]);
            var_dump($twitter_api);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::Debug("== error ==");
        }
    }
}
