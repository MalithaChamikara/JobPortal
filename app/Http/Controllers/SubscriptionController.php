<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\isEmployer;
use App\Http\Middleware\notAllowUserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\User;
use App\Mail\PurchaseMail;


class SubscriptionController extends Controller
{
    const WEEKLY_AMOUNT = 20;
    const MONTHLY_AMOUNT = 80;
    const YEARLY_AMOUNT = 200;
    const CURRENCY = 'USD';
    //this will aply for all the functions in this controller
    public function __construct()
    {
        $this->middleware(['auth',isEmployer::class]);
        $this->middleware(['auth',notAllowUserPayment::class])->except(['subscribe']);
    }
    public function subscribe()
    {
        return view('subscription.index');
    }

    public function initiatePayment(Request $request)
    {
        $plans = [
            'weekly'=>[
                'name'=>'weekly',
                'description'=>'weekly payment',
                'amount'=>self::WEEKLY_AMOUNT,
                'currency'=>self::CURRENCY,
                'quantity'=>1,

                
            ],
            'monthly'=>[
                'name'=>'monthly',
                'description'=>'monthly payment',
                'amount'=>self::MONTHLY_AMOUNT,
                'currency'=>self::CURRENCY,
                'quantity'=>1,

                
            ],
            'yearly'=>[
                'name'=>'yearly',
                'description'=>'yearly payment',
                'amount'=>self::YEARLY_AMOUNT,
                'currency'=>self::CURRENCY,
                'quantity'=>1,

                
            ],

        ];

        //initiate payment
        Stripe::setApiKey(config('services.stripe.secret'));//setting the stripe api key
        try{
            $selectPlan = null;
            //check what the user has selected url
            if($request->is('pay/weekly'))
            {
                $selectPlan = $plans['weekly'];
                $billingEnds = now()->addWeek()->startOfDay()->toDateString();
            }else if($request->is('pay/monthly'))
            {
                $selectPlan = $plans['monthly'];
                $billingEnds = now()->addMonth()->startOfDay()->toDateString();
            }else if($request->is('pay/yearly'))
            {
                $selectPlan = $plans['yearly'];
                $billingEnds = now()->addYear()->startOfDay()->toDateString();
            }

            if($selectPlan)
            {
                $successURL = URL::signedRoute('payment.success',[
                    'plan'=>$selectPlan['name'],
                    'billing_ends'=>$billingEnds
                ]);
                //create a price object
                $price = \Stripe\Price::create([
                    'unit_amount' => $selectPlan['amount']*100,
                    'currency' => $selectPlan['currency'],
                    'product_data' => [
                      'name' => $selectPlan['name'],
                     
                    ],
                  ]);
                //create stripe session
                $session= Session::create([
                        'payment_method_types'=>['card'],
                        'line_items'=>[
                            [
                                'price'=>$price->id,
                                'quantity'=>$selectPlan['quantity'],
                                
                            ]
                            ],
                            'mode'=>'payment',

                        'success_url'=>$successURL,
                        'cancel_url'=>route('payment.cancel')
                ]);
                //redirect user to the stripe checkout
                return redirect($session->url);
            }
                



        }catch(\Exception $e)
        {
            return $e;
        }
    }

    public function paymentSuccess(Request $request)
    {
        //update db
        $plan  = $request->plan;
        $billingEnds = $request->billing_ends;

        User::where('id',auth()->user()->id)->update([
            'plan'=>$plan,
            'billing_ends'=>$billingEnds,
            'status'=>'paid'
        ]);
        try{
            Mail::to(auth()->user())->queue(new PurchaseMail($plan,$billingEnds));
        }catch(\Exception $e)
        {
            return response()->json($e);
        }
        

        return redirect()->route('dashboard')->with('success','payment was succesfull');

    }

    public function cancel()
    {
        //redirect
        return redirect()->route('dashboard')->with('error','payment was unsuccesfull');
    }
}
