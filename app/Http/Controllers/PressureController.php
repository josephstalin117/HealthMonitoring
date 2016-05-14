<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use App\Pressure;
use App\Profile;
use Illuminate\Support\Facades\Auth;
use Config;
use DB;

class PressureController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $pressures = Pressure::where('user_id', Auth::id())->get();

        return view('health.show_pressure', [
            'pressures' => $pressures,
            'user_id' => Auth::id(),
        ]);
    }

    public function show($user_id) {
        //用户授权
//        $this->authorize('userManage', Auth::user());
        try {
            $statusCode = 200;
            $response = [
                "pressures" => []
            ];
            $pressures = Pressure::where('user_id', $user_id)->get();

            foreach ($pressures as $pressure) {

                $response['pressures'][] = [
                    'high' => $pressure->high,
                    'low' => $pressure->low,
                    'time' => $pressure->created_at,
                ];
            }

        } catch (Exception $e) {
            $response = [
                "error" => "bad stauts",
            ];
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }
    }

    public function search($nickname = "") {
        $this->authorize('userManage', Auth::user());
//        $profiles = Profile::where('nickname', 'LIKE', "%$nickname%")->orderBy('created_at', 'asc')->get();
        $users = DB::table('users')->join('profiles', 'users.id', '=', 'profiles.user_id')->select('users.*', 'profiles.nickname')->where('users.role', Config::get('constants.ROLE_USER'))->where('profiles.nickname', "LIKE", "%$nickname%")->get();

        return view('health.search_pressure', [
            'users' => $users,
        ]);
    }

    public function create() {
        return view('health.create_pressure');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'high' => 'required',
            'low' => 'required',
        ]);

        //@todo 添加时间插件
        $pressure = Pressure::create([
            'low' => $request->input('low'),
            'high' => $request->input('high'),
            'user_id' => Auth::user()->id,
        ]);

        $request->session()->flash('success', '新增成功');
        return redirect('/pressures');
    }
}
