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

    public function postSaveUser($params){

        if($this->level != 9){
            return ['status'=>false,'msg'=>'没有权限'];
        }

        $uid = isset($params['id']) ? intval($params['id']) : '';
        $data['username'] = isset($params['username']) ? $params['username'] : '';
        $data['name'] = isset($params['name']) ? $params['name'] : '';
        $data['password'] = isset($params['password']) ? $params['password'] : '';
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
        if(!$data['email']){
            return ['status'=>false,'msg'=>'请填写邮箱'];
        }

        $is_exist = User::where(array('username'=>$data['username']))
            ->orWhere(array('email'=>$data['email']))
            ->first();
        if($is_exist){
            if($uid != $is_exist['id']){
                return ['status'=>false,'msg'=>'帐号或邮箱已存在'];
            }
        }

        $validator = $this->validator($data,$uid);
        if($validator->errors()->first()){
            return ['status'=>false,'msg'=>$validator->errors()->first()];
        }

        if($uid){
            $user = User::where('id',$uid)->first();
        }else{
            $user = new User();
        }
        $user->fill([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
        ]);
        $user->password = bcrypt($data['password']);

        $user->save();

        return $user;
    }

    protected function validator(array $data,$uid='')
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$uid,
            'username' => 'required|max:50|unique:users,username,'.$uid,
            'password' => 'required|min:6',
        ]);
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
            return ['status'=>true,'msg'=>'删除成功'];
        }else{
            return ['status'=>false,'msg'=>'删除失败'];
        }
    }

    public function getUserInfo($uid){
        if($this->level != 9){
            return ['status'=>false,'msg'=>'没有权限'];
        }
        if(!$uid){
            return ['status'=>false,'msg'=>'参数错误'];
        }
        $user = User::where('id',$uid)->select('id','name','username','email')->first();
        if(!$user){
            return ['status'=>false,'msg'=>'用户不存在'];
        }
        $user = $user->toArray();
        $user['password'] = '';
        return ['status'=>true,'data'=>$user];
    }

}