<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\User;
use App\Pressure;
use App\Sugar;
use Config;
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

    public function pressure($nickname) {

        $list = array();

        $users = User::where('role', Config::get('constants.ROLE_USER'))->get();
        foreach ($users as $user) {

            $nickname = $user->profile->nickname;
            //@todo 添加年龄
            $max_high = DB::table('pressures')->where('user_id', $user->id)->max('high');
            $min_low = DB::table('pressures')->where('user_id', $user->id)->min('low');
            $avg_high = DB::table('pressures')->where('user_id', $user->id)->avg('high');
            $avg_low = DB::table('pressures')->where('user_id', $user->id)->avg('low');

            array_push($list, array(
                'id' => $user->id,
                'nickname' => $nickname,
                'max_high' => $max_high,
                'min_low' => $min_low,
                'avg_high' => $avg_high,
                'avg_low' => $avg_low,
            ));

        }

        return view('statistics.pressure', [
            'list' => $list,
        ]);
    }

    public function sugar() {

        $list = array();

        $users = User::where('role', Config::get('constants.ROLE_USER'))->get();
        foreach ($users as $user) {

            $nickname = $user->profile->nickname;
            //@todo 添加年龄
            $max_sugar = DB::table('sugars')->where('user_id', $user->id)->max('sugar');
            $min_sugar = DB::table('sugars')->where('user_id', $user->id)->min('sugar');
            $avg_sugar = DB::table('sugars')->where('user_id', $user->id)->avg('sugar');

            array_push($list, array(
                'id' => $user->id,
                'nickname' => $nickname,
                'max_sugar' => $max_sugar,
                'min_sugar' => $min_sugar,
                'avg_sugar' => $avg_sugar,
            ));

        }

        return view('statistics.sugar', [
            'list' => $list,
        ]);
    }

}
