<?php

namespace App\Http\Controllers\Gateway\stripe_v3;

use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;


class ProcessController extends Controller
{

    /*
     * Stripe V3 Gateway
     */
    public static function process($deposit)
    {
        $StripeJSAcc = json_decode($deposit->gateway_currency()->gateway_parameter);
        $alias = $deposit->gateway->alias;
        $general =  GeneralSetting::first();
        \Stripe\Stripe::setApiKey("$StripeJSAcc->secret_key");

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => $general->sitename,
                'description' => 'Deposit  with Stripe',
                'images' => [asset('assets/images/logoIcon/logo.png')],
                'amount' => $deposit->final_amo * 100,
                'currency' => "$deposit->method_currency",
                'quantity' => 1,
            ]],
            'cancel_url' => route(gatewayRedirectUrl()),
            'success_url' => route(gatewayRedirectUrl()),
        ]);

        $send['view'] = 'user.payment.stripe_v3';
        $send['session'] = $session;
        $send['StripeJSAcc'] = $StripeJSAcc;

        return json_encode($send);
    }

    /*
     * Stripe V3 js ipn
     */
    public function ipn(Request $request)
    {
        // $StripeJSAcc = GatewayCurrency::where('gateway_alias','stripe_v3')->latest()->first();
        $StripeJSAcc = GatewayCurrency::where('gateway_alias','stripe')->latest()->first();
        $gateway_parameter = json_decode($StripeJSAcc->gateway_parameter);

        \Stripe\Stripe::setApiKey($gateway_parameter->secret_key);

        // You can find your endpoint's secret in your webhook settings
        // $endpoint_secret = $gateway_parameter->end_point; // main
        // old endpoint secret
        //$endpoint_secret = 'whsec_IBLcsXGj2D1bNgM1hxcSOSRSPcCXRKS7';
        // live endpoint secret under uberlyst account created 15 sep, 2021
        $endpoint_secret = 'whsec_lwE5UYcGNwfY0ZSYmrAovC2WcULN2hWw';
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];


        $event = null;
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the checkout.session.completed event
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $data = Deposit::where('btc_wallet',  $session->id)->orderBy('id', 'DESC')->first();

            if($data->status==0){
                PaymentController::userDataUpdate($data->trx);
            }
        }
        
        if ($event->type == 'invoice.paid') {
            $session = $event->data->object;
            $user_email = $session->customer_email ?? false;
            if($user_email){
                $user = DB::table('users')->where('email', $user_email)->first();
                
                if(!$user){
                    http_response_code(200);
                    return;
                }
                
                $subscription = DB::table('subscriptions')->where('user_id',  $user->id)->latest()->first();
                
                $type = $subscription->type;
                
                if($type == 'per_month'){
                    
                    $this->adTransaction($user, 9.99, 'Payment for one month subscription');
                    
                    DB::table('subscriptions')->insert([
                        'user_id' => $user->id,
                        'ad_id' => null,
                        'type' => 'per_month',
                        'expire_at' => now()->addDays(30),
                        'created_at' => now(),
                    ]);
                }
                
                if($type == 'per_six_month'){
                    
                    $this->adTransaction($user, 49.99, 'Payment for six months subscription');
                    
                    DB::table('subscriptions')->insert([
                        'user_id' => $user->id,
                        'ad_id' => null,
                        'type' => 'per_six_month',
                        'expire_at' => now()->addDays(180),
                        'created_at' => now(),
                    ]);
                }
                
                if($type == 'per_year'){
                    
                    $this->adTransaction($user, 99.99, 'Payment for one year subscription');
                    
                    DB::table('subscriptions')->insert([
                        'user_id' => $user->id,
                        'ad_id' => null,
                        'type' => 'per_year',
                        'expire_at' => now()->addDays(360),
                        'created_at' => now(),
                    ]);
                }
            }
        }
        http_response_code(200);
    }
    
    public function subscribe(){
        $subscription = session('subscription');
        //[$type, $user_id, $random]
        
        $type = $subscription[0] ?? null;
        $user_id = $subscription[1] ?? null;
        $random = $subscription[2] ?? null;
        
        if(request()->get('status') != 'success'){
            $notify[]=['error','You can try deposit also!'];
            return redirect()->route('user.deposit')->withNotify($notify);
        }
        
        if($random != request()->get('data')){
            $notify[]=['error','No cheating!'];
            return redirect()->route('home')->withNotify($notify);
        }
        
        $user = DB::table('users')->where('id', $user_id)->first();
        
        if($type == 'per_month'){
            $this->adTransaction($user, 9.99, 'Payment for one month subscription');
            
            DB::table('subscriptions')->insert([
                'user_id' => $user->id,
                'ad_id' => null,
                'type' => 'per_month',
                'expire_at' => now()->addDays(30),
                'created_at' => now(),
            ]);
        }
        
        if($type == 'per_six_month'){
            $this->adTransaction($user, 49.99, 'Payment for six months subscription');
            
            DB::table('subscriptions')->insert([
                'user_id' => $user->id,
                'ad_id' => null,
                'type' => 'per_six_month',
                'expire_at' => now()->addDays(180),
                'created_at' => now(),
            ]);
        }
        
        if($type == 'per_year'){
            $this->adTransaction($user, 99.99, 'Payment for one year subscription');
            
            DB::table('subscriptions')->insert([
                'user_id' => $user->id,
                'ad_id' => null,
                'type' => 'per_year',
                'expire_at' => now()->addDays(360),
                'created_at' => now(),
            ]);
        }
        
        $notify[] = ['success', 'Subscription Successfull'];
        return redirect()->route('user.subscription-plan')->withNotify($notify);
    }
    
    public function adTransaction($user, $amount, $details) {
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $amount;
        $transaction->post_balance = getAmount($user->balance);
        $transaction->charge = getAmount(0);
        $transaction->trx_type = '-';
        $transaction->details = $details;
        $transaction->trx = getTrx();
        $transaction->save();
    }
}














