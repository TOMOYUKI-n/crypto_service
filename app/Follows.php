<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Follows extends Model
{
    //
    protected $table = 'follows';
    protected $fillable = [
        'userId',
        'accountId'
    ];


    /**
     * フォロー状態を保存
     */
    public static function GeneratefollowsList($userId,$loginId)
    {
        try{
            $follows = new Follows;
            $follows["userId"] = $loginId;
            $follows["accountId"] = $userId;
            $follows->save();
        }catch(\Exception $e){
            Log::error($e->getMessage());
            Log::Debug("error: Autoのsaveに失敗しました");
        }
    }
}
