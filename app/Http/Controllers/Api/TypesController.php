<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = DB::table('inquire_types')->get();

        return response()->json($query);
    }
}
