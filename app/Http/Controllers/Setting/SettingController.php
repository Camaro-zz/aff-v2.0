<?php

namespace App\Http\Controllers\Setting;

use App\Services\SettingService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class SettingController extends Controller
{
    public function __construct(SettingService $settingService){
        $this->settingService = $settingService;
    }

    public function getSetting(){
        $data = $this->settingService->getSetting();
        return Response::json($data, 200);
    }

    public function updateSetting(Request $request){
        $data = $this->settingService->updateSetting($request->all());
        return Response::json($data, 200);
    }
}
