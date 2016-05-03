<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use App\Pressure;
use Illuminate\Support\Facades\Auth;

class PressureController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $pressures = Pressure::where('user_id', Auth::id())->get();

        return view('health.show_pressure', [
            'pressures' => $pressures,
        ]);
    }

    public function show($id) {
        //用户授权
        $this->authorize('userManage', Auth::user());
        try {
            $statusCode = 200;
            $response = [
                "pressures" => []
            ];
            $pressures = Pressure::where('user_id', Auth::id()->get());

            foreach ($pressures as $pressure) {

                $response['pressures'][] = [
                    'high' => $pressure->high,
                    'low' => $pressures->low,
                    'time' => $pressure->category,
                ];
            }

        } catch (Exception $e) {
            $response = [
                "error" => "bad stauts"
            ];
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }

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
