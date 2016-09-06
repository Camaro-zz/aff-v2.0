<?php
namespace App\Services;

use App\Models\Campaigns;
use App\Models\CampaignsClick;
use App\Models\CampaignsLPs;
use App\Models\CampaignsLead;
use App\Models\CampaignsOffers;
use App\Models\CampaignsStat;
use App\Models\CampaignsLogs;
use App\Models\CampaignsUsers;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CampaignsService extends BaseService {
    public function __construct(){
        $user = Auth::user();
        $this->uid = $user->id;
        $this->level = $user->role_level;
        $this->username = $user->username;
    }

    public function getCamp($camp_id){
        $camp = Campaigns::where('camp_id',$camp_id)->select('camp_name','camp_id')->first();
        return $camp;
    }

    /**获取campaigns列表
     * @param $param['keyword']  关键词查询
     * @param $param['offset']     页号
     * @param $param['limit']    查询数目
     * @param $param['date_type']    按时间查看类型  0所有时间，1今天，2昨天，3前7天，4前14天，5这个月，6上个月，7今年，8上年，9自定义
     * @param $param['timezone']    按时区查看
     */
    public function getCamps($param){
        $page = isset($param['page']) ? $param['page'] : 1;
        $limit = isset($param['limit']) ? $param['limit'] : 10;
        $uid = isset($param['uid']) ? $param['uid'] : 0;
        $date_type = isset($param['date_type']) ? intval($param['date_type']) : 0;
        $timezone = isset($param['timezone']) ? intval($param['date_type']) : '';
        $offset = ($page - 1) * $limit;
        //$keywords = isset($param['keywords']) ? trim($param['keywords']) : '';
        $query = Campaigns::select('camp_status','camp_name','camp_cpc','camp_id')
                          ->orderBy('camp_id','DESC')
                          ->where('camp_status',0);

        /*if($keywords){
            $query->where('camp_name', 'like', '%' . $keywords . '%');
        }*/

        if($this->level != 9){//如果当前访问的不是超级管理员
            $this_camp_ids = $this->getCampUsers($this->uid);
            $query->whereIn('mt_campaigns.camp_id', $this_camp_ids);
        }

        $count = $query->count();
        $query->skip($offset);
        $query->take($limit);
        $res = $query->get()->toArray();

        foreach ($res as $k=>$v){
            $camp_ids[] = $v['camp_id'];
            $camps[$v['camp_id']] = $v;
        }
        if($timezone){
            date_default_timezone_set('Etc/GMT'.$timezone);
        }
        $timestamp = $this->getTimestamp($date_type);
        $start_timestamp = $timestamp['start'];
        $end_timestamp = $timestamp['end'];
        $select = 'camp_id, (count(click_id)-sum(click_multi)) as clicks,sum(click_lead) as leads, ROUND(click_cpc, 3) as cpc,IFNULL(round((sum(click_lead)/sum(click_offer))*100,2),0) AS offer_cvr,Sum(click_offer) AS lpclicks,IFNULL(round((sum(click_offer)/count(click_id))*100,2),0) AS ctr,IFNULL(round((sum(click_lead)/count(click_id))*100,2),0) AS cvr,Sum(lp_view) AS lpviews,click_leadvalue';
        $new_query = CampaignsClick::select(DB::raw($select))
            ->whereIn('camp_id',$camp_ids)
            ->whereBetween('click_time',[$start_timestamp,$end_timestamp])
            ->groupBy('camp_id')
            ->orderBy('camp_id','DESC')
            ->get()->toArray();
        foreach ($new_query as $k=>$v){
            $new_query[$k]['cost'] = round($v['cpc'] * $v['clicks'], 2);
            $new_query[$k]['rev'] = round($v['leads'] * $v['click_leadvalue'], 2);
            $new_query[$k]['profit'] = round($new_query[$k]['rev'] - $new_query[$k]['cost'], 2);
            $new_query[$k]['roi'] = $new_query[$k]['cost'] == 0 ? 0 : round($new_query[$k]['profit'] / $new_query[$k]['cost'] * 100, 1);
        }
        foreach ($res as $k=>$v){
            if($new_query){
                foreach ($new_query as $key=>$val){
                    if($v['camp_id'] == $val['camp_id']){
                        $res[$k] = array_merge($v,$val);
                        break;
                    }else{
                        $res[$k]['clicks'] = 0;
                        $res[$k]['leads'] = 0;
                        $res[$k]['cpc'] = 0;
                        $res[$k]['epc'] = 0;
                        $res[$k]['offer_cvr'] = 0;
                        $res[$k]['lpclicks'] = 0;
                        $res[$k]['ctr'] = 0;
                        $res[$k]['cvr'] = 0;
                        $res[$k]['lpviews'] = 0;
                        $res[$k]['cost'] = 0;
                        $res[$k]['rev'] = 0;
                        $res[$k]['profit'] = 0;
                        $res[$k]['roi'] = 0;
                    }
                }
            }else{
                $res[$k]['clicks'] = 0;
                $res[$k]['leads'] = 0;
                $res[$k]['cpc'] = 0;
                $res[$k]['epc'] = 0;
                $res[$k]['offer_cvr'] = 0;
                $res[$k]['lpclicks'] = 0;
                $res[$k]['ctr'] = 0;
                $res[$k]['cvr'] = 0;
                $res[$k]['lpviews'] = 0;
                $res[$k]['cost'] = 0;
                $res[$k]['rev'] = 0;
                $res[$k]['profit'] = 0;
                $res[$k]['roi'] = 0;
            }
        }
        if($uid){
            $camp_ids = $this->getCampUsers($uid);
            if($camp_ids){
                foreach ($res as $k=>$v){
                    if(in_array($v['camp_id'], $camp_ids)){
                        $res[$k]['checked'] = 1;
                    }
                }
            }
            $user = User::where('id',$uid)->select('id','name','username')->first();
            $data['user'] = $user;
        }
        $data['data'] = $res;
        $data['count'] = ceil($count/$limit);
        $data['page'] = $page;
        return $data;
    }

    public function getCampUsers($uid){
        if(!$uid){
            return ['status'=>false,'msg'=>'参数错误'];
        }

        if($this->level != 9 && $uid != $this->uid){//判断权限，只有超级管理员和本人可以查看当前人的camps
            return ['status'=>false,'msg'=>'无权查看'];
        }
        $camps = CampaignsUsers::where(array('uid'=>$uid))->lists('camp_id')->toArray();
        return $camps;
    }

    public function postCampUsers($uid,$camp_ids){
        if($this->level != 9){
            return ['status'=>false,'msg'=>'无权操作'];
        }
        CampaignsUsers::where('uid',$uid)->delete();
        if(!empty($camp_ids)){
            foreach ($camp_ids as $k=>$v){
                $create[$k]['uid'] = $uid;
                $create[$k]['camp_id'] = $v;
            }
            $save = CampaignsUsers::insert($create);
        }else{
            $save = 1;
        }
        if($save){
            return ['status'=>true,'msg'=>'保存成功'];
        }else{
            return ['status'=>false,'msg'=>'保存失败'];
        }
    }

    /**
     * 修改campaigns内容
     * @param $param
     */
    public function putCamp($param){
        $title = $param['camp_name'];
        $id = $param['camp_id'];
        $update = Campaigns::where('camp_id',$id)->update(['camp_name'=>$title]);
        if($update){
            $this->log(1);
            return true;
        }else{
            return false;
        }
    }

    public function getCampsNum(){
        if($this->level != 9){
            $num = CampaignsUsers::where(array('uid'=>$this->uid))->count();
        }else{
            $num = Campaigns::count();
        }
        return ['num' => $num];
    }

    public function getUsers(){
        /*date_default_timezone_set('Etc/GMT-8');
        $carbon = new Carbon();
        $start_timestamp = mktime(0,0,0,1,1,date('Y')-1);
        $end_timestamp   = mktime(23,59,59,12,date('t'),date('Y')-1);
        dd($end_timestamp);*/
        if($this->level != 9){
            return ['status'=>true,'msg'=>'没有权限'];
        }
        $users = User::select('id','name','username','email','avatar')->where('role_level','!=','9')->get();
        $data['data'] = $users;
        return $data;
    }

    public function getUsersNum(){
        if($this->level != 9){
            return ['status'=>true,'msg'=>'没有权限'];
        }
        $num = User::count();
        return ['num' => $num];
    }
    /**获取活动的lp内容
     * @param $camp_id
     * @return mixed
     */
    public function getLP($camp_id){
        if(!$camp_id){
            ['status'=>'false','msg'=>'参数错误'];
        }

        $lps = CampaignsLPs::select('lp_id','lp_name','lp_weight','lp_url')
                    ->where('camp_id',$camp_id)
                    ->get();
        if(!$lps){
            return ['status'=>false,'msg'=>'该活动下没有landing page'];
        }

        foreach ($lps as $k=>$v){
            $lps[$k]['clicks'] = CampaignsClick::where(array('camp_id'=>$camp_id,'lp_id'=>$v['lp_id']))->count();
            $lps[$k]['cvrs'] = CampaignsClick::where(array('camp_id'=>$camp_id,'lp_id'=>$v['lp_id'],'lp_lead'=>1,'lp_lead'=>1))->count();
            $lps[$k]['views'] = CampaignsClick::where(array('camp_id'=>$camp_id,'lp_id'=>$v['lp_id']))->sum('lp_view');
            if($lps[$k]['clicks'] > 0){
                $lps[$k]['cvr_rate'] = round($lps[$k]['cvrs'] / $lps[$k]['clicks'] * 100, 2);
            }else{
                $lps[$k]['cvr_rate'] = 0;
            }
        }

        return $lps;
    }

    /**设置LP占比
     * @param $param['data']    {'id1':'weight1', 'id2':'weight2'}
     */
    public function putLP($param){
        if(empty($param) === TRUE){
            return ['status'=>false,'msg'=>'参数错误'];
        }

        $total_weight = 0;
        foreach ($param as $k=>$v){
            $total_weight+=$v;
        }
        if($total_weight != 100){
            return ['status'=>false,'msg'=>'LP占比相加必须为100'];
        }
        foreach ($param as $k=>$v){
            CampaignsLPs::where(array('lp_id'=>$k))->update(['lp_weight'=>$v]);
        }
        return ['status'=>true];
    }

    /**
     * @param $camp_id
     * @return array|bool
     */
    public function getOffer($camp_id,$param){
        if(!$camp_id){
            return ['status'=>'false','msg'=>'参数错误'];
        }
        $date_type = isset($param['date_type']) ? intval($param['date_type']) : 0;
        $timezone = isset($param['timezone']) ? $param['timezone'] : '';

        $offer = CampaignsOffers::select('offer_id','offer_name','offer_url','offer_weight','offer_payout')
                                ->where('camp_id',$camp_id)
                                ->get();
        if(!$offer){
            return ['status'=>false,'msg'=>'该活动下没有offer'];
        }
        
        $timestamp = $this->getTimestamp($date_type);
        $start_timestamp = $timestamp['start'];
        $end_timestamp = $timestamp['end'];

        if($timezone){
            date_default_timezone_set('Etc/GMT'.$timezone);
        }

        //先计算出选中时区，一天之内的时间戳，比如选择today 选择-4时区，那就是计算-4时区今天之内的时间戳，然后根据时间戳筛选
        foreach ($offer as $k=>$v){
            $offer[$k]['offer_payout'] = round($v['offer_payout'], 2);
            $offer[$k]['clicks'] = CampaignsClick::where(array('camp_id'=>$camp_id,'offer_id'=>$v['offer_id']))
                                                 ->whereBetween('click_time',[$start_timestamp,$end_timestamp])
                                                 ->count();
            $offer[$k]['cvrs'] = CampaignsClick::where(array('camp_id'=>$camp_id,'offer_id'=>$v['offer_id'],'lp_lead'=>1,'click_lead'=>1))
                                               ->whereBetween('click_time',[$start_timestamp,$end_timestamp])
                                               ->count();
            //$offer[$k]['views'] = CampaignsClick::where(array('camp_id'=>$camp_id,'offer_id'=>$v['offer_id']))->sum('lp_view');
            $offer[$k]['offer_all_payout'] = $offer[$k]['cvrs'] * $v['offer_payout'];
            if($offer[$k]['clicks'] > 0){
                $offer[$k]['cvr_rate'] = round($offer[$k]['cvrs'] / $offer[$k]['clicks'] * 100, 2);
            }else{
                $offer[$k]['cvr_rate'] = 0;
            }
        }
        return $offer;
    }


    public function getGroupByOption($camp_id){
        if(!$camp_id){
            return ['status'=>'false','msg'=>'参数错误'];
        }
        $camp = Campaigns::where(array('camp_id'=>$camp_id))->select('camp_token1_name as c1','camp_token2_name as c2','camp_token3_name as c3','camp_token4_name as c4','camp_token5_name as c5','camp_token6_name as c6','camp_token7_name as c7','camp_token8_name as c8','camp_token9_name as c9','camp_token10_name as c10')->first()->toArray();
        $token_names = [];
        foreach ($camp as $k=>$val){
            if(empty($val) === FALSE){
                $token_names['click_'.$k] = $val;
            }
        }
        return $token_names;
    }

    public function getGroupBy($camp_id, $param){
        $token_id = isset($param['token_id']) ? $param['token_id'] : '';
        if(!$camp_id || !$token_id){
            return ['status'=>'false','msg'=>'参数错误'];
        }
        $date_type = isset($param['date_type']) ? intval($param['date_type']) : 0;
        $timezone = isset($param['timezone']) ? $param['timezone'] : '';
        //DB::connection()->enableQueryLog();
        $timestamp = $this->getTimestamp($date_type);
        $start_timestamp = $timestamp['start'];
        $end_timestamp = $timestamp['end'];

        if($timezone){
            date_default_timezone_set('Etc/GMT'.$timezone);
        }
        $select = $token_id.' as token,count(camp_id) as clicks,sum(click_lead) as leads, ROUND(click_cpc, 3) as CPC,IFNULL(round((sum(mt_click.click_lead)/sum(mt_click.click_offer))*100,2),0) AS offer_cvr,Sum(mt_click.click_offer) AS lp_clicks,IFNULL(round((sum(mt_click.click_offer)/count(mt_click.click_id))*100,2),0) AS lp_ctr,IFNULL(round((sum(mt_click.click_lead)/count(mt_click.click_id))*100,2),0) AS lp_cvr,Sum(mt_click.lp_view) AS lp_views';
        $data['data'] = CampaignsClick::select(DB::raw($select))
                                      ->where(array('camp_id'=>$camp_id,'click_multi'=>0))
                                      ->whereBetween('click_time',[$start_timestamp,$end_timestamp])
                                      ->groupBy($token_id)
                                      ->get()->toArray();
        //$data['']
        //$data = collect($data)->->toArray();
        //dd(DB::getQueryLog());
        //dd($data);
        return $data;
        /*$clicks = CampaignsClick::where(array('camp_id'=>14,'click_c3'=>12264790,'lp_view'=>1))->get();
        $sub = [];
        foreach ($clicks as $k=>$v){
            $sub[$v['click_subid']] = $v['click_subid'];
        }
        return count(array_unique($sub));*/
    }


    /**设置offer占比
     * @param $param['data']    {'id1':'weight1', 'id2':'weight2'}
     */
    public function putOffer($param){
        if(empty($param) === TRUE){
            return ['status'=>false,'msg'=>'参数错误'];
        }
        $total_weight = 0;
        foreach ($param as $k=>$v){
            $total_weight+=$v;
        }
        if($total_weight != 100){
            return ['status'=>false,'msg'=>'offer占比相加必须为100'];
        }
        foreach ($param as $k=>$v){
            CampaignsOffers::where(array('offer_id'=>$k))->update(['offer_weight'=>$v]);
        }
        $this->log(2);
        return ['status'=>true];
    }

    /**
     * 记录日志
     */
    public function log($type){
        switch ($type){
            case 1:
                $log['memo'] = '修改campaigns名称';
                break;
            case 2:
                $log['memo'] = '修改offer占比';
                break;
        }
        $log['uid'] = $this->uid;
        $log['username'] = $this->username;
        CampaignsLogs::create($log);
    }

    public function getTimestamp($date_type){

        $carbon = new Carbon();
        switch ($date_type){
            case 1:     //今天
                $start_timestamp = strtotime($carbon->startOfDay());
                $end_timestamp   = strtotime($carbon->endOfDay());
                break;
            case 2:     //昨天
                $start_timestamp = strtotime($carbon->yesterday()->startOfDay());
                $end_timestamp   = strtotime($carbon->yesterday()->endOfDay());
                break;
            case 3:     //本周
                $start_timestamp = mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y'));
                //$end_timestamp   = mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y'));
                $end_timestamp   = time();
                break;
            case 4:     //上周
                $start_timestamp = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
                $end_timestamp   = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
                break;
            case 5:     //本月
                $start_timestamp = strtotime($carbon->startOfMonth());
                //$end_timestamp   = strtotime($carbon->endOfMonth());
                $end_timestamp   = time();
                break;
            case 6:     //上月
                $start_timestamp = mktime(0,0,0,date('m')-1,1,date('Y'));
                $end_timestamp   = mktime(23,59,59,date('m')-1,date('t'),date('Y'));
                break;
            case 7:     //本年
                $start_timestamp = strtotime($carbon->startOfYear());
                //$end_timestamp   = strtotime($carbon->endOfYear());
                $end_timestamp   = time();
                break;
            case 8:     //去年
                $start_timestamp = mktime(0,0,0,1,1,date('Y')-1);
                $end_timestamp   = mktime(23,59,59,12,date('t'),date('Y')-1);
                break;
            default:
                $start_timestamp = 0;
                $end_timestamp   = time();
                break;
        }
        return ['start'=>$start_timestamp,'end'=>$end_timestamp];
    }
}