<?php
namespace App\Services;


use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;

class AdminService extends BaseService {
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

    public function postAddUser($params){

        if($this->level != 9){
            return ['status'=>false,'msg'=>'没有权限'];
        }

        $data['username'] = isset($params['username']) ? $params['username'] : '';
        $data['name'] = isset($params['name']) ? $params['name'] : '';
        $data['password'] = isset($params['username']) ? $params['username'] : '';
        $data['email'] = isset($params['email']) ? $params['email'] : '';
        if(!$data['username']){
            return ['status'=>false,'msg'=>'请填写用户名'];
        }
        if(!$data['name']){
            return ['status'=>false,'msg'=>'请填写姓名'];
        }
        if(!$data['password']){
            return ['status'=>false,'msg'=>'请填写密码'];
        }
        $is_exist = User::where(array('username'=>$data['username']))->first();
        if($is_exist){
            return ['status'=>false,'msg'=>'帐号已存在'];
        }
        $user = new User();
        $user->fill([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
        ]);
        $user->password = bcrypt($data['password']);

        $user->save();

        return $user;
    }


    public function postDelUser($uid){
        if($this->level != 9){
            return ['status'=>false,'msg'=>'没有权限'];
        }
        if(!$uid){
            return ['status'=>false,'msg'=>'参数错误'];
        }
        $del = User::where(array('id'=>$uid))->delete();
        if($del){
            return ['status'=>true,'msg'=>'删除失败'];
        }else{
            return ['status'=>false,'msg'=>'删除失败'];
        }
    }

}