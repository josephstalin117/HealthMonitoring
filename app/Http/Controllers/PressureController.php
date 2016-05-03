<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Pressure;

class PressureController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function create(Request $request) {
        $this->validate($request, [
            'high' => 'required',
            'low' => 'required',
        ]);

        $pressure = Pressure::create([
            'low' => $request->input('low'),
            'high' => $request->input('high'),
            'user_id' => Auth::user()->id,
        ]);

        $request->session()->flash('success', '新增成功');
        return redirect('/usermanage');
    }
}
