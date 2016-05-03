<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Config;

use App\Http\Requests;
use App\User;
use App\Profile;
use Mockery\CountValidator\Exception;

class UserManageController extends Controller {
    /**
     * Create a new controller instance.
     *
     */
    public function __construct() {

        $this->middleware('auth');
        $this->authorize('userManage', Auth::user());
    }

    /**
     * Show user list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::where('role', 1)->orderBy('created_at')->get();
        return view('manage.users', [
            'users' => $users,
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        try {
            $user = User::find($id);
            $statusCode = 200;
            $response = ["user" => [
                'id' => (int)$id,
                'name' => $user->name,
                'nickname' => $user->profile->nickname,
                'avatar' => $user->profile->avatar,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'telephone' => $user->profile->telephone,
                'address' => $user->profile->address,
            ]];

        } catch (Exception $e) {
            $response = [
                "error" => "can't find user",
            ];
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }

    }

    public function search($nickname) {

        $statusCode = 200;
        $response = [
            "users" => []
        ];
        $profiles = Profile::where('nickname', 'LIKE', "%$nickname%")->get();

        foreach ($profiles as $profile) {
            $response['users'][] = ["user" => [
                'id' => $profile->user->id,
                'name' => $profile->user->name,
                'nickname' => $profile->nickname,
                'avatar' => $profile->avatar,
                'email' => $profile->user->email,
                'role' => $profile->user->role,
                'created_at' => $profile->user->created_at,
                'telephone' => $profile->telephone,
                'address' => $profile->user->address,
            ]];
        }


        return Response::json($response, $statusCode);
    }

    public function destroy(Request $request, $id) {
        try {
            $user = User::findOrFail($id);
            $user->profile->delete();
            $user->delete();
            $response = [
                "status" => "success",
            ];

            $request->session()->flash('success', '删除成功');
            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json("{}", 404);
        }

    }

    public function update(Request $request) {

        $this->validate($request, [
            'telephone' => 'required',
            'nickname' => 'required',
            'address' => 'required',
            'email' => 'required',
        ]);

        $user = User::findOrFail($request->input('id'));
        $user->profile->telephone = $request->input('telephone');
        $user->profile->nickname = $request->input('nickname');
        $user->email = $request->input('email');
        $user->profile->address = $request->input('address');

        $user->save();
        $user->profile->save();
        $request->session()->flash('success', '更新成功');

        return redirect('/usermanage');
    }

    public function delete($id) {

        $user = User::findOrFail($id);
        $user->profile->delete();
        $user->delete();


        return redirect('/usermanage');
    }

    public function create(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'nickname' => 'required',
            'email' => 'required',
            'password' => 'required',
            'telephone' => 'required',
            'address' => 'required',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => Config::get('constants.ROLE_USER'),
        ]);

        $user->profile()->save(new Profile());
        $user->profile->nickname = $request->input('nickname');
        $user->profile->telephone = $request->input('telephone');
        $user->profile->address = $request->input('address');

        $user->profile->save();

        $request->session()->flash('success', '新增成功');
        return redirect('/usermanage');
    }

}
