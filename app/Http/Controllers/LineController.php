<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Response;
use Auth;
use Config;
use App\Line;

class LineController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $lines = Line::all();

        return view('line.index', [
            'lines' => $lines,
        ]);
    }

    public function set() {
        $this->authorize('userManage', Auth::user());
        return view('line.set');
    }


    public function get() {

        try {
            $statusCode = 200;
            $response = [
                "status" => "",
                "pressure_high" => "",
                "pressure_low" => "",
                "sugar" => "",
            ];

            $lines = Line::all();

            foreach ($lines as $line) {
                switch ($line->name) {
                    case 'high':
                        $response['pressure_high'] = $line->line;
                        break;
                    case 'low':
                        $response['pressure_low'] = $line->line;
                        break;
                    case 'sugar':
                        $response['sugar'] = $line->line;
                        break;
                    default:
                        break;
                }
            }

            $response['status'] = "success";

        } catch (\Exception $e) {
            $response = [
                "error" => $e,
                "status" => "fails"
            ];
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }
    }

    public function set_pressure_high_line($line_num) {
        return $this->set_line($line_num, Config::get('constants.LINE_PRESSURE_HIGH'), Config::get('constants.LINE_MORE_THAN'));
    }

    public function set_pressure_low_line($line_num) {
        return $this->set_line($line_num, Config::get('constants.LINE_PRESSURE_LOW'), Config::get('constants.LINE_LESS_THAN'));
    }

    public function set_sugar_line($line_num) {
        return $this->set_line($line_num, Config::get('constants.LINE_SUGAR'), Config::get('constants.LINE_MORE_THAN'));
    }

    private function set_line($line_num, $name, $type) {

        $this->authorize('userManage', Auth::user());
        try {
            $statusCode = 200;
            $response = [
                "status" => "",
            ];

            $count = Line::where('name', $name)->count();

            if ($count > 0) {

                $line = Line::where('name', $name)->update(['line' => $line_num, 'type' => $type]);
                if ($line) {
                    $response['status'] = "success";
                } else {
                    $response['status'] = "fails";
                }

            } else {

                $line = new Line;
                $line->name = $name;
                $line->line = $line_num;
                $line->type = $type;
                if ($line->save()) {
                    $response['status'] = "success";
                } else {
                    $response['status'] = "fails";
                }
            }

        } catch (\Exception $e) {
            $response = [
                "error" => $e,
                "status" => "fails"
            ];
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }
    }
}
