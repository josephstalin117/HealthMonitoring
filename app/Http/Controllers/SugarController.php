<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Sugar;
use App\Line;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Config;

use App\Http\Requests;

class SugarController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * 显示个人的血压
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        $sugars = Sugar::where('user_id', Auth::id())->paginate(6);

        return view('health.show_sugar', [
            'sugars' => $sugars,
            'user_id' => Auth::id(),
        ]);
    }

    public function show($user_id) {
        try {
            $statusCode = 200;
            $response = [
                "sugars" => []
            ];
            $sugars = Sugar::where('user_id', $user_id)->get();

            foreach ($sugars as $sugar) {

                $response['sugars'][] = [
                    'sugar' => $sugar->sugar,
                    'time' => $sugar->created_at,
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
//        $profiles = Profile::where(['nickname', 'LIKE', "%$nickname%"],[])->orderBy('created_at', 'asc')->get();
        $users = DB::table('users')->join('profiles', 'users.id', '=', 'profiles.user_id')->select('users.*', 'profiles.nickname')->where('users.role', Config::get('constants.ROLE_USER'))->where('profiles.nickname', "LIKE", "%$nickname%")->get();

        return view('health.search_sugar', [
            'users' => $users,
        ]);
    }

    public function create() {
        return view('health.create_sugar');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'sugar' => 'required',
        ]);

        $sugar = Sugar::create([
            'sugar' => $request->input('sugar'),
            'user_id' => Auth::user()->id,
        ]);

        $line = $this->getLine();

        if ($sugar->sugar > $line['sugar']) {
            $this->sendWarning(Config::get('constants.LINE_SUGAR'));
        }

        $request->session()->flash('success', '新增成功');
        return redirect('/sugars');
    }

    public function destroy(Request $request, $id) {

        try {
            $sugar = Sugar::findOrFail($id);
            $sugar->delete();
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

        $line = array(
            'high' => $line_pressure_high->name,
            'low' => $line_pressure_low->line,
            'sugar' => $line_sugar->line,
        );

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
            case 'sugar':
                $content = "您的血糖可能偏高,请及时就医";
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
