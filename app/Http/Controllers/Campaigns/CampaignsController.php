<?php
namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use App\Services\CampaignsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CampaignsController extends Controller {
    public function __construct(CampaignsService $campaignsService){
        $this->campaignsService = $campaignsService;
    }

    public function getCamps(Request $request){
        $data = $this->campaignsService->getCamps($request->all());
        return Response::json($data, 200);
    }

    public function getCamp($camp_id){
        $data = $this->campaignsService->getCamp($camp_id);
        return Response::json($data, 200);
    }

    public function putCamp(Request $request){
        $data = $this->campaignsService->putCamp($request->all());
        return Response::json($data, 200);
    }

    public function getCampsNum(){
        $data = $this->campaignsService->getCampsNum();
        return Response::json($data, 200);
    }

    public function getUsers(Request $request){
        $data = $this->campaignsService->getUsers($request->all());
        return Response::json($data, 200);
    }

    public function getUsersNum(){
        $data = $this->campaignsService->getUsersNum();
        return Response::json($data, 200);
    }

    public function getOffer($camp_id, Request $request){
        $data = $this->campaignsService->getOffer($camp_id,$request->all());
        return Response::json($data, 200);
    }

    public function putOffer(Request $request){
        $data = $this->campaignsService->putOffer($request->all());
        return Response::json($data, 200);
    }

    public function getLP($camp_id){
        $data = $this->campaignsService->getLP($camp_id);
        return Response::json($data, 200);
    }

    public function putLP(Request $request){
        $data = $this->campaignsService->putLP($request->all());
        return Response::json($data, 200);
    }

    public function postCampUsers($id, Request $request){
        $all = $request->all();
        $ids = $all['camp_ids'];
        $data = $this->campaignsService->postCampUsers($id, $ids);
        return Response::json($data, 200);
    }

    public function getGroupByOption($camp_id){
        $data = $this->campaignsService->getGroupByOption($camp_id);
        return Response::json($data, 200);
    }

    public function getGroupBy($camp_id, Request $request){
        $data = $this->campaignsService->getGroupBy($camp_id, $request->all());
        return Response::json($data, 200);
    }

    public function getGroups(){
        $data = $this->campaignsService->getGroups();
        return Response::json($data, 200);
    }
}