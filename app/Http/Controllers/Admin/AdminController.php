<?php

namespace App\Http\Controllers\Admin;

use App\Services\AdminService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{

    public function __construct(AdminService $adminService){
        $this->adminService = $adminService;
    }

    public function postAddUsers(Request $request){
        $data = $this->adminService->postAddUser($request->all());
        return Response::json($data, 200);
    }

    public function postDelUser($uid){
        $data = $this->adminService->postDelUser($uid);
        return Response::json($data, 200);
    }
}
