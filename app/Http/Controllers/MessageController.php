<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Follow;
use Auth;
use App\Message;
use Response;
use Illuminate\Support\Facades\Config;

use App\Http\Requests;

class MessageController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function show_sends() {
        $messages = Message::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('message.show_sends', [
            'messages' => $messages,
        ]);
    }

    public function show_receives() {
        $messages = Message::where('to_user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('message.show_receives', [
            'messages' => $messages,
        ]);
    }

    public function create() {
        return view('message.create');
    }

    public function send($to_user_id, $content = "", $type = 0) {
        try {
            $statusCode = 200;
            $response = [
                "status" => "",
            ];

            if (User::findOrFail($to_user_id)) {
                $message = new Message;
                $message->user_id = Auth::id();
                $message->to_user_id = $to_user_id;
                $message->content = $content;
                $message->type = Config::get('constants.NORMAL_MESSAGE');
                if ($message->save()) {
                    $response['status'] = "success";
                } else {
                    $response['status'] = "fails";
                }
            }

        } catch (\Exception $e) {
            $response = [
                "error" => $e,
                "status" => "fails",
            ];
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }
    }

    public function destroy($id) {

        try {
            $message = Message::findOrFail($id);
            $message->delete();
            $response = [
                "status" => "success",
            ];

            return Response::json($response, 200);
        } catch (\Exception $e) {
            return Response::json("{}", 404);
        }
    }
}
