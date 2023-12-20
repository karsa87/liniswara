<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function transaction(Request $request)
    {
        return view('marketing.payment.transaction');
    }

    /**
     * Display a listing of the resource.
     */
    public function agent(Request $request)
    {
        return view('marketing.payment.list_agent');
    }

    /**
     * Display a listing of the resource.
     */
    public function detail_agent(Request $request, $id)
    {
        return view('marketing.payment.detail_agent');
    }
}
