<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\User;
use App\Pressure;
use App\Sugar;
use Config;
use Carbon\Carbon;
use DB;
use App\Http\Requests;

class StatisticsController extends Controller {
    //

    public function index() {

        try {

            $statusCode = 200;
            $response = [
                "users" => []
            ];

            $users = User::where('role', Config::get('constants.ROLE_USER'))->get();
            foreach ($users as $user) {

                $nickname = $user->profile->nickname;
                //@todo 添加年龄
                $max_high = DB::table('pressures')->where('user_id', $user->id)->max('high');
                $min_low = DB::table('pressures')->where('user_id', $user->id)->min('low');
                $max_sugar = DB::table('sugars')->where('user_id', $user->id)->max('sugar');
                $min_sugar = DB::table('sugars')->where('user_id', $user->id)->min('sugar');

                $avg_high = DB::table('pressures')->where('user_id', $user->id)->avg('high');
                $avg_low = DB::table('pressures')->where('user_id', $user->id)->avg('low');
                $avg_sugar = DB::table('sugars')->where('user_id', $user->id)->avg('sugar');

                $response['users'][] = [
                    'user_id' => $user->id,
                    'nickname' => $nickname,
                    'max_high' => $max_high,
                    'min_low' => $min_low,
                    'max_sugar' => $max_sugar,
                    'min_sugar' => $min_sugar,
                    'avg_high' => $avg_high,
                    'avg_low' => $avg_low,
                    'avg_sugar' => $avg_sugar,
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

    public function pressure(Request $request) {

        $list = array();
        $keyword = $request->input("keyword");

        $range = $this->getBirthRange($request->input('range'));

        $users = DB::table('users')->join('profiles', 'users.id', '=', 'profiles.user_id')->select('users.*', 'profiles.nickname', 'profiles.birth')->where('users.role', Config::get('constants.ROLE_USER'))->whereBetween(DB::raw("TIMESTAMPDIFF(YEAR,profiles.birth,CURDATE())"), [$range['min'], $range['max']])->where('profiles.nickname', "LIKE", "%$keyword%")->get();

        foreach ($users as $user) {

            $nickname = $user->nickname;
            $age = $this->getUserAge($user->birth);
            $max_high = DB::table('pressures')->where('user_id', $user->id)->max('high');
            $min_low = DB::table('pressures')->where('user_id', $user->id)->min('low');
            $avg_high = DB::table('pressures')->where('user_id', $user->id)->avg('high');
            $avg_low = DB::table('pressures')->where('user_id', $user->id)->avg('low');

            array_push($list, array(
                'id' => $user->id,
                'nickname' => $nickname,
                'age' => $age,
                'max_high' => $max_high,
                'min_low' => $min_low,
                'avg_high' => $avg_high,
                'avg_low' => $avg_low,
            ));

        }

        return view('statistics.pressure', [
            'list' => $list,
            'keyword' => $keyword,
            'range' => $request->input('range'),
        ]);
    }

    public function sugar(Request $request) {

        $list = array();
        $keyword = $request->input('keyword');

        $range = $this->getBirthRange($request->input('range'));

        $users = DB::table('users')->join('profiles', 'users.id', '=', 'profiles.user_id')->select('users.*', 'profiles.nickname', 'profiles.birth')->where('users.role', Config::get('constants.ROLE_USER'))->whereBetween(DB::raw("TIMESTAMPDIFF(YEAR,profiles.birth,CURDATE())"), [$range['min'], $range['max']])->where('profiles.nickname', "LIKE", "%$keyword%")->get();

        foreach ($users as $user) {

            $nickname = $user->nickname;
            $age = $this->getUserAge($user->birth);
            $max_sugar = DB::table('sugars')->where('user_id', $user->id)->max('sugar');
            $min_sugar = DB::table('sugars')->where('user_id', $user->id)->min('sugar');
            $avg_sugar = DB::table('sugars')->where('user_id', $user->id)->avg('sugar');

            array_push($list, array(
                'id' => $user->id,
                'nickname' => $nickname,
                'age' => $age,
                'max_sugar' => $max_sugar,
                'min_sugar' => $min_sugar,
                'avg_sugar' => $avg_sugar,
            ));

        }

        return view('statistics.sugar', [
            'list' => $list,
            'keyword' => $keyword,
            'range' => $request->input('range')
        ]);
    }

    private function getUserAge($birth) {
        $dateNow = Carbon::now();
        return ($dateNow->diffInYears(Carbon::parse($birth)));
    }

    private function getBirthRange($time) {
        $range = array('min' => '0', 'max' => '200');
        switch ($time) {
            case 'all':
                $range['min'] = 0;
                $range['max'] = 200;
                break;
            case '30':
                $range['min'] = 0;
                $range['max'] = 30;
                break;
            case '40':
                $range['min'] = 30;
                $range['max'] = 40;
                break;
            case '50':
                $range['min'] = 40;
                $range['max'] = 50;
                break;
            case '60':
                $range['min'] = 50;
                $range['max'] = 60;
                break;
            case '60+':
                $range['min'] = 60;
                $range['max'] = 100;
                break;
            default:
                break;
        }
        return $range;
    }

}
