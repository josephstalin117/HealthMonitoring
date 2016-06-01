<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;

class ProfileController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $profile = $request->user()->profile()->first();

        return view('profile.index', [
            'profile' => $profile,
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {

        $profile = $request->user()->profile()->first();

        $profile->nickname = $request->input('nickname');
        $profile->telephone = $request->input('telephone');
        $profile->address = $request->input('address');
        $birth=$request->input('birth');
        $profile->birth = $birth;

        if ($request->hasFile('photo')) {
            $imageName = Auth::id() . time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $destinationPath = base_path() . '/public/images/';
            $request->file('photo')->move($destinationPath, $imageName);
            $profile->avatar = '/images/' . $imageName;
        }

        $profile->save();
        $request->session()->flash('success', '更新成功');

        return redirect('/home');
    }
}
