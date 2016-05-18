<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Follow;
use Auth;
use App\Http\Requests;

class FollowController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

    }

    /**
     * 主动关注
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_following() {
        $followings = Follow::where('user_id', Auth::id())->get();

        return view('follow.show_following', [
            'followings' => $followings,
        ]);
    }

    /**
     * 被别人关注
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_followers() {
        $followers = Follow::where('follow_user_id', Auth::id())->get();

        return view('follow.show_followers', [
            'followers' => $followers,
        ]);
    }
}
