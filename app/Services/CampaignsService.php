<?php
namespace App\Services;

use App\Models\Campaigns;
use App\Models\CampaignsClick;
use App\Models\CampaignsLPs;
use App\Models\CampaignsLead;
use App\Models\CampaignsOffers;
use App\Models\CampaignsStat;

class CampaignsService extends BaseService {
    public function __construct(){
    }

    /**获取campaigns列表
     * @param $param['keyword']  关键词查询
     * @param $param['offset']     页号
     * @param $param['limit']    查询数目
     */
    public function getCamps($param){
        $offset = isset($param['offset']) ? $param['offset'] : 0;
        $limit = isset($param['limit']) ? $param['limit'] : 10;
        $keywords = isset($param['keywords']) ? trim($param['keywords']) : '';
        $query = Campaigns::leftJoin('mt_temp_stats as stats', 'stats.camp_id','=','mt_campaigns.camp_id')
                          ->select('mt_campaigns.camp_status','mt_campaigns.camp_name','mt_campaigns.camp_cpc','stats.*');

        if($keywords){
            $query->where('camp_name', 'like', '%' . $keywords . '%');
        }

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
        return $res;
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
            $lps[$k]['cvr_rate'] = $lps[$k]['cvrs'] / $lps[$k]['clicks'] * 100;
        }

        return $lps;
    }

    /**设置LP占比
     * @param $param['data']    {'id1':'weight1', 'id2':'weight2'}
     * @param $param['camp_id'] 活动id
     */
    public function putLP($param){
        $data = isset($param['data']) ? json_decode($param['data']) : '';
        $camp_id = isset($param['camp_id']) ? intval($param['camp_id']) : '';
        if($data == 0 || !$camp_id){
            return ['status'=>'false','msg'=>'参数错误'];
        }

        $total_weight = 0;
        foreach ($data as $k=>$v){
            $total_weight+=$v;
        }
        if($total_weight != 100){
            return ['status'=>'false','msg'=>'LP占比相加必须为100'];
        }
        foreach ($data as $k=>$v){
            CampaignsLPs::where(array('lp_id'=>$k,'camp_id'=>$camp_id))->update(['lp_weight'=>$v]);
        }
        return true;
    }

    /**
     * @param $camp_id
     * @return array|bool
     */
    public function getOffer($camp_id){
        if(!$camp_id){
            return ['status'=>'false','msg'=>'参数错误'];
        }

        $offer = CampaignsOffers::select('offer_id','offer_name','lp_weight','offer_url','offer_weight')
            ->where('camp_id',$camp_id)
            ->get();
        if(!$offer){
            return ['status'=>false,'msg'=>'该活动下没有offer'];
        }

        foreach ($offer as $k=>$v){
            $offer[$k]['clicks'] = CampaignsClick::where(array('camp_id'=>$camp_id,'offer_id'=>$v['offer_id']))->count();
            $offer[$k]['cvrs'] = CampaignsClick::where(array('camp_id'=>$camp_id,'offer_id'=>$v['offer_id'],'lp_lead'=>1,'click_lead'=>1))->count();
            //$offer[$k]['views'] = CampaignsClick::where(array('camp_id'=>$camp_id,'offer_id'=>$v['offer_id']))->sum('lp_view');
            $offer[$k]['cvr_rate'] = $offer[$k]['cvrs'] / $offer[$k]['clicks'] * 100;
        }

        return $offer;
    }

    /**设置LP占比
     * @param $param['data']    {'id1':'weight1', 'id2':'weight2'}
     * @param $param['camp_id'] 活动id
     */
    public function putOffer($param){
        $data = isset($param['data']) ? json_decode($param['data']) : '';
        $camp_id = isset($param['camp_id']) ? intval($param['camp_id']) : '';
        if($data == 0 || !$camp_id){
            return ['status'=>'false','msg'=>'参数错误'];
        }

        $total_weight = 0;
        foreach ($data as $k=>$v){
            $total_weight+=$v;
        }
        if($total_weight != 100){
            return ['status'=>'false','msg'=>'offer占比相加必须为100'];
        }
        foreach ($data as $k=>$v){
            CampaignsOffers::where(array('offer_id'=>$k,'camp_id'=>$camp_id))->update(['lp_weight'=>$v]);
        }
        return true;
    }
}