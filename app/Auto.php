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
            $targetUserList = Auto::select("userId")->where("autoFlg", "=", 1)->get();

            for($i = 0; $i < count($targetUserList); $i++)
            {
                $List[$i] = $targetUserList[$i]["userId"];
            }
            Log::debug($List);
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
    public static function autoFlgSavedZero($loginId)
    {
        try{
            Log::Debug("autoFlgSavedZero 実行");
            // ログインユーザーのレコードがあるか
            $auto = Auto::where("userId", "=", $loginId)->first();

            // なければ新規登録
            if($auto === null){
                $auto = new Auto;
                $auto["userId"] = $loginId;
                $auto["autoFlg"] = "0";
                $auto->save();
                Log::Debug("autoFlgSavedZero 新規登録完了");
            }else{
                // あればそのユーザーのデータを更新
                $auto->autoFlg = "0";
                $auto->save();
                Log::Debug("autoFlgSavedZero 更新完了");
            }
        }catch(\Exception $e){
            Log::error($e->getMessage());
            Log::Debug("error: Auto0のsaveに失敗しました");
        }
    }

    /**
     * 自動フォローを実行しているユーザーの状態を保存 flg=0へ
     */
    public static function autoFlgSavedOne($loginId){
        try{
            Log::Debug("autoFlgSavedOne 実行");
            // ログインユーザーのレコードがあるか
            $auto = Auto::where("userId", "=", $loginId)->first();

            // なければ新規登録
            if($auto === null){
                $auto = new Auto;
                $auto["userId"] = $loginId;
                $auto["autoFlg"] = "1";
                $auto->save();
                Log::Debug("autoFlgSavedOne 新規登録完了");
            }else{
                // あればそのユーザーのデータを更新
                $auto->autoFlg = "1";
                $auto->save();
                Log::Debug("autoFlgSavedOne 更新完了");
            }
        }catch(\Exception $e){
            Log::error($e->getMessage());
            Log::Debug("error: Auto1のsaveに失敗しました");
        }
    }
}
