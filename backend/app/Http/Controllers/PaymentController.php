<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use ErrorException;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    //
    public function payByStripe(Request $request)
    {
        Stripe::setApiKey('sk_test_51C19VNGin0JfRTbQUGCWTkt3wehRnyqRirpbquWlxISCo4f1l9PraQSLxDPBopGEddgWfnam1PlQzBTkwUCP01xd00ptdFClHL');
 
        try {

            $checkout_session = Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Car Parking',
                        ],
                        'unit_amount' => $request->price * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $request->success_url
            ]);

            return response()->json(['url' => $checkout_session->url]);

        } catch (ErrorException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
