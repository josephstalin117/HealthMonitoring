<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Follow;
use Auth;
use App\Http\Requests;
use Response;
use Illuminate\Support\Facades\Config;

class FollowController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

    }

    /**
     * 显示主动关注对象
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_following() {
        $followings = Follow::where('user_id', Auth::id())->get();

        return view('follow.show_following', [
            'followings' => $followings,
        ]);
    }

    /**
     * 显示被别人关注的对象
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_followers() {
        $followers = Follow::where('follow_user_id', Auth::id())->get();

        return view('follow.show_followers', [
            'followers' => $followers,
        ]);
    }

    /**
     * 关注某人
     * @param $follow_user_id
     * @return mixed
     */
    public function follow($follow_user_id) {

        try {
            $statusCode = 200;
            $response = [
                "status" => "",
            ];

            $follow = new Follow;
            $follow->user_id = Auth::id();
            $follow->follow_user_id = $follow_user_id;

            $follow->auth = Config::get('constants.FOLLOW_AUTH_DISAGREE');
            if ($follow->save()) {
                $response['status'] = "success";
            }

        } catch (\Exception $e) {
            $response = [
                "error" => "can't find user",
                "status" => "fails",
            ];
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }
    }
}
