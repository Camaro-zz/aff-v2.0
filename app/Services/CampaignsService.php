<?php
namespace App\Services;

use App\Models\Campaigns;
use App\Models\CampaignsClick;
use App\Models\CampaignsLPs;
use App\Models\CampaignsLead;
use App\Models\CampaignsOffers;
use App\Models\CampaignsStat;
use App\Models\CampaignsLogs;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CampaignsService extends BaseService {
    public function __construct(){
    }

    public function getCamp($camp_id){
        $camp = Campaigns::where('camp_id',$camp_id)->select('camp_name','camp_id')->first();
        return $camp;
    }

    /**获取campaigns列表
     * @param $param['keyword']  关键词查询
     * @param $param['offset']     页号
     * @param $param['limit']    查询数目
     */
    public function getCamps($param){
        $page = isset($param['page']) ? $param['page'] : 1;
        $limit = isset($param['limit']) ? $param['limit'] : 10;
        $offset = ($page - 1) * $limit;
        $keywords = isset($param['keywords']) ? trim($param['keywords']) : '';
        $query = Campaigns::leftJoin('mt_temp_stats as stats', 'stats.camp_id','=','mt_campaigns.camp_id')
                          ->select('mt_campaigns.camp_status','mt_campaigns.camp_name','mt_campaigns.camp_cpc','stats.*','mt_campaigns.camp_id')
                          ->orderBy('mt_campaigns.camp_id','DESC')
                          ->orderBy('mt_campaigns.camp_status','DESC');

        if($keywords){
            $query->where('camp_name', 'like', '%' . $keywords . '%');
        }
        $count = $query->count();
        $query->skip($offset);
        $query->take($limit);
        $res = $query->get();
        /*if($res){
            foreach ($res as $k=>$v){
                $res[$k]['lps'] = '';
                $lps = CampaignsLPs::where('camp_id',$v['camp_id'])->select('lp_id','lp_name','lp_url','lp_weight')->get();
                if($lps){
                    $res[$k]['lps'] =  $lps->toArray();
                    foreach ($lps as $key=>$val){
                        $res[$k]['lps'][$key]['leads'] = CampaignsLead::where('')
                    }
            }
                $offers = CampaignsOffers::where('camp_id',$v['camp_id'])->select('offer_id','offer_name','offer_url','offer_payout','offer_weight')->get();
                if($offers){
                    $res[$k]['offers'] =  $offers->toArray();
                }
            }
        }*/
        //dd($res);
        $data['data'] = $res;
        $data['count'] = ceil($count/$limit);
        $data['page'] = $page;
        return $data;
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
        $num = Campaigns::count();
        return ['num' => $num];
    }

    public function getUsers(){
        $users = User::select('name','username','email','avatar')->get();
        $data['data'] = $users;
        return $data;
    }

    public function getUsersNum(){
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
    public function getOffer($camp_id){
        if(!$camp_id){
            return ['status'=>'false','msg'=>'参数错误'];
        }

        $offer = CampaignsOffers::select('offer_id','offer_name','offer_url','offer_weight')
            ->where('camp_id',$camp_id)
            ->get();
        if(!$offer){
            return ['status'=>false,'msg'=>'该活动下没有offer'];
        }

        foreach ($offer as $k=>$v){
            $offer[$k]['clicks'] = CampaignsClick::where(array('camp_id'=>$camp_id,'offer_id'=>$v['offer_id']))->count();
            $offer[$k]['cvrs'] = CampaignsClick::where(array('camp_id'=>$camp_id,'offer_id'=>$v['offer_id'],'lp_lead'=>1,'click_lead'=>1))->count();
            //$offer[$k]['views'] = CampaignsClick::where(array('camp_id'=>$camp_id,'offer_id'=>$v['offer_id']))->sum('lp_view');
            if($offer[$k]['clicks'] > 0){
                $offer[$k]['cvr_rate'] = $offer[$k]['cvrs'] / $offer[$k]['clicks'] * 100;
            }else{
                $offer[$k]['cvr_rate'] = 0;
            }
        }

        return $offer;
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
        $user = Auth::user()->toArray();
        $log['uid'] = $user['id'];
        $log['username'] = $user['username'];
        CampaignsLogs::create($log);
    }
}