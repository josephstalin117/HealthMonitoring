<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Follow;
use Auth;
use App\Message;
use App\User;
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
        $followings = Follow::where('user_id', Auth::id())->where('auth', 1)->get();

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

            $check_followed = Follow::where('user_id', Auth::id())->where('follow_user_id', $follow_user_id)->count();
            if ($check_followed != 0) {
                $response['status'] = "followed";
            } else {
                User::findOrFail($follow_user_id);
                $user = User::findOrFail(Auth::id());
                $follow = new Follow;
                $follow->user_id = Auth::id();
                $follow->follow_user_id = $follow_user_id;
                $follow->auth = Config::get('constants.FOLLOW_AUTH_DISAGREE');

                if ($follow->save()) {
                    $message = new Message;
                    $message->user_id = Auth::id();
                    $message->to_user_id = $follow_user_id;
                    $message->follow_id = $follow->id;
                    $message->content = "用户" . $user->profile->nickname . "想要关注您";
                    $message->type = Config::get('constants.FOLLOW_MESSAGE');
                    if ($message->save()) {
                        $response['status'] = "success";
                    }
                }
            }

        } catch (\Exception $e) {
            $response = [
                "error" => "can't find user",
                "status" => "fails",
                "reason" => $e,
            ];
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }
    }

    /**
     * 同意关注
     * @param $follow_id
     * @return mixed
     */
    public function approve_follow($follow_id) {

        try {
            $statusCode = 200;
            $response = [
                "status" => "",
            ];

            $follow = Follow::findOrFail($follow_id);
            $follow->auth = Config::get('constants.FOLLOW_AUTH_AGREE');

            if ($follow->save()) {
                $message = new Message;
                $message->user_id = Auth::id();
                $message->to_user_id = $follow->user_id;
                $message->content = "用户" . Auth::user()->profile->nickname . "同意了您的关注请求";
                $message->type = Config::get('constants.NORMAL_MESSAGE');
                if ($message->save()) {
                    $response['status'] = "success";
                }
            }

        } catch (\Exception $e) {
            $response = [
                "status" => "fails",
                "reason" => $e,
            ];
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }
    }
}
