<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use App\Pressure;
use App\Profile;
use Illuminate\Support\Facades\Auth;
use Config;
use App\Line;
use App\Message;
use DB;

class PressureController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $pressures = Pressure::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(6);

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

        } catch (\Exception $e) {
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

        $pressure = Pressure::create([
            'low' => $request->input('low'),
            'high' => $request->input('high'),
            'user_id' => Auth::user()->id,
        ]);

        $line = $this->getLine();

        if ($line) {
            if ($pressure->high > $line['high']) {

                $this->sendWarning(Config::get('constants.LINE_PRESSURE_HIGH'));
            }

            if ($pressure->low < $line['low']) {
                $this->sendWarning(Config::get('constants.LINE_PRESSURE_LOW'));
            }

        }

        $request->session()->flash('success', '新增成功');
        return redirect('/pressures');
    }

    public function destroy(Request $request, $id) {

        try {
            $pressure = Pressure::findOrFail($id);
            $pressure->delete();
            $response = [
                "status" => "success",
            ];

            $request->session()->flash('success', '删除成功');
            return Response::json($response, 200);
        } catch (\Exception $e) {
            return Response::json("{}", 404);
        }
    }

    private function getLine() {
        $line_pressure_high = Line::where('name', Config::get('constants.LINE_PRESSURE_HIGH'))->first();
        $line_pressure_low = Line::where('name', Config::get('constants.LINE_PRESSURE_LOW'))->first();
        $line_sugar = Line::where('name', Config::get('constants.LINE_SUGAR'))->first();

        if ($line_pressure_high && $line_pressure_low && $line_sugar) {

            $line = array(
                'high' => $line_pressure_high->line,
                'low' => $line_pressure_low->line,
                'sugar' => $line_sugar->line,
            );
        } else {
            return false;
        }

        return $line;
    }

    private function sendWarning($type) {

        $message = new Message;
        $message->user_id = Config::get('constants.USER_ADMIN');
        $message->to_user_id = Auth::id();

        switch ($type) {
            case 'high':
                $content = "您的血压可能偏高,请及时询问医生";
                break;
            case 'low':
                $content = "您的血压可能偏低,请及时询问医生";
                break;
            default:
                break;
        }

        $message->content = "用户" . Auth::user()->profile->nickname . $content;
        $message->type = Config::get('constants.NORMAL_MESSAGE');
        if ($message->save()) {
            return true;
        } else {
            return false;
        }
    }


}
