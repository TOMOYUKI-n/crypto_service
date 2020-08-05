<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Auto extends Model
{
    //
    protected $table = 'auto';
    protected $fillable = [
        'userId',
        'autoFlg'
    ];

    /**
     * フォロー対象をリストにする（配列）
     */
    public static function autoUserList()
    {
        try{
            $targetUserList = Auto::select("id")->where("autoFlg", "=", 1)->get();
            for($i = 0; $i < count($targetUserList); $i++)
            {
                $List[$i] = $targetUserList[$i]["id"];
            }
            return $List;
        }catch(\Exception $e){
            Log::error($e->getMessage());
            Log::Debug("error: フォロー対象をリスト化に失敗しました");
            return $List = array("auto" => "Error");
        }

    }
    /**
     * 自動フォローを実行しているユーザーの状態を保存 flg=1
     */
    public static function autoFlgSaved($loginId)
    {
        try{
            Log::Debug("autoFlgSaved 実行");
            $auto = new Auto;
            $auto["userId"] = $loginId;
            $auto["autoFlg"] = "1";
            $auto->save();
        }catch(\Exception $e){
            Log::error($e->getMessage());
            Log::Debug("error: Autoのsaveに失敗しました");
        }
    }

    /**
     * 自動フォローを実行しているユーザーの状態を保存 flg=0へ
     */
    public static function autoFlgDelete($loginId){
        try{
            Log::Debug("autoFlgDelete 実行");
            $auto = Auto::where("userId", "=", $loginId)->first();
            $auto["userId"] = $loginId;
            $auto["autoFlg"] = "0";
            $auto->save();
            return;
        }catch(\Exception $e){
            Log::error($e->getMessage());
            Log::Debug("error: Autoのsaveに失敗しました");
        }
    }
}
