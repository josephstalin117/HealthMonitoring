<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Sugar;
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

        $sugars = Sugar::where('user_id', Auth::id())->get();

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

        $request->session()->flash('success', '新增成功');
        return redirect('/sugars');
    }
}
