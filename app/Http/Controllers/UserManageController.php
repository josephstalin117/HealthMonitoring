<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Message;
use App\Pressure;
use App\Sugar;
use Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Config;
use DB;
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
    }

    /**
     * Show user list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->authorize('userManage', Auth::user());
        $users = User::where('role', 1)->orderBy('created_at')->paginate(6);
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

        $this->authorize('userManage', Auth::user());

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

        } catch (\Exception $e) {
            $response = [
                "error" => "can't find user",
            ];
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }

    }

    /**
     * 搜索用户
     * @param $nickname
     * @return mixed
     */
    public function search($nickname = "") {

        try {
            $statusCode = 200;
            $response = [
                "users" => [],
                "status" => "",
            ];

            $users = DB::table('users')->join('profiles', 'users.id', '=', 'profiles.user_id')->select('users.*', 'profiles.nickname')->where('users.role', Config::get('constants.ROLE_USER'))->where('profiles.nickname', "LIKE", "%$nickname%")->get();

            foreach ($users as $user) {
                $response['users'][] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'nickname' => $user->nickname,
                ];
            }

            $response['status'] = "success";
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

    public function destroy(Request $request, $id) {

        $this->authorize('userManage', Auth::user());

        try {
            $user = User::findOrFail($id);
            Pressure::where('user_id',$id)->delete();
            Sugar::where('user_id',$id)->delete();
            Follow::where('user_id',$id)->delete();
            Follow::where('follow_user_id',$id)->delete();
            Message::where('user_id',$id)->delete();
            Message::where('to_user_id',$id)->delete();
            $user->profile->delete();
            $user->delete();
            $response = [
                "status" => "success",
            ];

            $request->session()->flash('success', '删除成功');
            return Response::json($response, 200);
        } catch (\Exception $e) {
            return Response::json("{}", 404);
        }

    }

    public function update(Request $request) {

        $this->authorize('userManage', Auth::user());

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

        $this->authorize('userManage', Auth::user());

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
