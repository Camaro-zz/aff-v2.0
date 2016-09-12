<?php
namespace App\Services;


use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Validator;

class SettingService extends BaseService {
    public function __construct(){
        $user = Auth::user();
        if($user){
            $this->uid = $user->id;
            $this->level = $user->role_level;
            $this->username = $user->username;
        }else{
            $this->level = 0;
        }
    }

    public function getSetting(){
        if($this->level != 9){
            return ['status'=>false,'msg'=>'无权操作'];
        }
        $setting = Setting::first();
        if(!$setting){
            $setting = new Setting();
            $setting->fill([
                'open_register' => 0,
            ]);
            $setting->save();
        }
        return ['status'=>true,'data'=>$setting];
    }

    public function updateSetting($params){
        if($this->level != 9){
            return ['status'=>false,'msg'=>'无权操作'];
        }
        $open_register = isset($params['open_register']) ? intval($params['open_register']) : 0;
        $setting = Setting::first();
        if(!$setting){
            $setting = new Setting();
        }
        $setting->fill([
            'open_register' => $open_register,
        ]);
        $setting->save();

        return $setting;
    }
}