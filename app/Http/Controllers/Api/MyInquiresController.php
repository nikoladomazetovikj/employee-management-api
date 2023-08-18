<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyInquiresController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $myInquires = DB::table('inquire_details')->where('user_id', $request->user()->id);

        return response()->json($myInquires->get());
    }
}
