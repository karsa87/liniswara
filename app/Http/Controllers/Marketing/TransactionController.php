<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('marketing.transaction.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function detail(Request $request, $id)
    {
        return view('marketing.transaction.detail');
    }
}
