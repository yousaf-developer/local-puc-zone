<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TempUsers;
use App\Models\Transactions;
use App\Models\ApiSettings;
use App\Models\Settings;

use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function show()
    {
        return view('payment');
    }

    /*=========================== Registration Payment =================================*/
    
    function registerPayment(Request $request)
    {
        $user_id = $request->id;

        $sysSettings = Settings::first();
        $settings = ApiSettings::where('type', 'RAZOR_PAY')->first();
      
        $createdUserData = TempUsers::find($user_id);
        $amount = isset($sysSettings->retailer_rate) ? $sysSettings->retailer_rate : 1;
        
        // $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $api = new Api($settings->merchant_id, $settings->merchant_key);

        $order = $api->order->create([
            'receipt' => "123",
            'amount' => $amount * 100, 
            'currency' => 'INR'
        ]);

        $orderId = $order['id'];

        $paramList = array();
        
        $source_id = $user_id != '' ? $user_id.'_'.time() : strval(rand(10000, 99999999));
        $user_id = "CUST" . $createdUserData->id;
        // Required parameters for payment
        $paramList["keyId"] = $settings->merchant_id;//config('services.razorpay.key');
        $paramList["amount"] = $amount * 100;
        $paramList["orderId"] = $orderId;
        $paramList["username"] = $createdUserData->name;
        $paramList["userEmail"] = $createdUserData->email;

        $paramList["source_id"] = $source_id;
        $paramList["source_type"] = 'REGISTRATION';
        $paramList["website"] = "PUCZONE";
        $paramList["CALLBACK_URL"] = route('payment.callbackregister');

        // Session::put('razorpay_order_id', $orderId);

        return view('payment')->with($paramList);
    }

    public function callbackRegister(Request $request)
    {
        $payment_response = $request->all();
        
        $sysSettings = Settings::first();
        $settings = ApiSettings::where('type', 'RAZOR_PAY')->first();
      
        $amount = isset($sysSettings->retailer_rate) ? $sysSettings->retailer_rate : 1;

        $source_id = $payment_response['source_id'];
        $source_type = $payment_response['source_type'];
        $razorpay_payment_id = $payment_response['razorpay_payment_id'];
        $razorpay_order_id = $payment_response['razorpay_order_id'];
        $razorpay_signature = $payment_response['razorpay_signature'];

        $tempUser = explode('_',$source_id );
        $tempUserId = isset($tempUser[0]) ? $tempUser[0] : '' ;

        $api = new Api($settings->merchant_id, $settings->merchant_key);

        try {
            $api->utility->verifyPaymentSignature([
                            'razorpay_order_id' => $razorpay_order_id,
                            'razorpay_payment_id' => $razorpay_payment_id,
                            'razorpay_signature' => $razorpay_signature,
                        ]);
            
            

            $tempUser = TempUsers::where('id', $tempUserId)->first();
            
            $userData = $tempUser->toArray();

            // Remove the id and timestamps from the data
            unset($userData['id'], $userData['created_at'], $userData['updated_at']);
            // $userData['status'] = 'active';
            $user = new User();
            $user->fill($userData);
            $user->save();
            
            TempUsers::where('id', $tempUserId)->delete();

            $transaction = new Transactions;
            $transaction->type = '1'; // 1=>credit, 2=>debit
            $transaction->transaction_type = '1'; // 1=>online, 2=>manual
            $transaction->user_id = $user->id;
            $transaction->bank_id = null;
            $transaction->puc_id = null;
            $transaction->amount = $amount;
            $transaction->transaction_number = $razorpay_payment_id;
            $transaction->transaction_remarks = 'User Register';
            $transaction->status = '3'; //  1=>pending, 2=>rejected , 3=>approved
            $transaction->transaction_status = '3'; //  1=>pending, 2=>rejected , 3=>approved
            $transaction->transaction_checksum = '';
            $transaction->transaction_response = json_encode($payment_response, true);
            $transaction->date = date('Y-m-d');
            $transaction->save();

            // send email code
            $body = view('emails.registration', $user);
            $userEmailsSend[] = $user->email;
            // to username, to email, from username, subject, body html
            $response = sendMail($user->name, $userEmailsSend, 'PUCZONE', 'Registration', $body);

            $encodedId = base64_encode($transaction->id); 
            $url = route('payment_success', ['id' => $encodedId]);
            return redirect($url);

            // Payment was successful
            // return redirect()->route('payment.success');
        } catch (\Exception $e) {

            $url = route('payment_fail');
            return redirect($url);
            // Payment failed
            // return redirect()->route('payment.failure');
        }
    }

    /*=========================== Add Wallet Payment =================================*/
    function addWalletOnline(Request $request)
    {
        $amount = $request->amount;

        if(isset(Auth::user()->id)){
            $settings = ApiSettings::where('type', 'RAZOR_PAY')->first();
        
            $createdUserData = User::find(Auth::user()->id);
            
            $transaction = new Transactions;
            $transaction->type = '1'; // 1=>credit, 2=>debit
            $transaction->transaction_type = '1'; // 1=>online, 2=>manual
            $transaction->user_id = Auth::user()->id;
            $transaction->bank_id = null;
            $transaction->puc_id = null;
            $transaction->amount = $amount;
            $transaction->transaction_number = null;
            $transaction->transaction_remarks = 'Added by user';
            $transaction->status = '1'; //  1=>pending, 2=>rejected , 3=>approved
            $transaction->transaction_status = '1'; //  1=>pending, 2=>rejected , 3=>approved
            $transaction->transaction_checksum = null;
            $transaction->transaction_response = null;
            $transaction->date = date('Y-m-d');
            $transaction->save();

            $api = new Api($settings->merchant_id, $settings->merchant_key);

            $order = $api->order->create([
                'receipt' => "123",
                'amount' => $amount * 100, 
                'currency' => 'INR'
            ]);

            $orderId = $order['id'];

            $paramList = array();
           
            $transaction_id = isset($transaction->id) ? $transaction->id.'_'.time() : strval(rand(10000, 99999999));
            $user_id = "CUST" . $createdUserData->id;
            // Required parameters for payment
            $paramList["keyId"] = $settings->merchant_id;//config('services.razorpay.key');
            $paramList["amount"] = $amount * 100;
            $paramList["orderId"] = $orderId;
            $paramList["username"] = $createdUserData->name;
            $paramList["userEmail"] = $createdUserData->email;

            $paramList["source_id"] = $transaction_id;
            $paramList["source_type"] = 'WALLET';
            $paramList["website"] = "PUCZONE";
            $paramList["CALLBACK_URL"] = route('payment.callbackwallet');

            // Session::put('razorpay_order_id', $orderId);

            return view('payment')->with($paramList);
        }else{
            return redirect('login');
        }
    }

    public function callbackWallet(Request $request)
    {
        $payment_response = $request->all();
        // dd($payment_response);
        
        $settings = ApiSettings::where('type', 'RAZOR_PAY')->first();

        $source_id = $payment_response['source_id'];
        $source_type = $payment_response['source_type'];
        $razorpay_payment_id = $payment_response['razorpay_payment_id'];
        $razorpay_order_id = $payment_response['razorpay_order_id'];
        $razorpay_signature = $payment_response['razorpay_signature'];

        $tempTrxId = explode('_',$source_id );
        $trxId = isset($tempTrxId[0]) ? $tempTrxId[0] : '' ;

        $api = new Api($settings->merchant_id, $settings->merchant_key);

        try {
            $api->utility->verifyPaymentSignature([
                            'razorpay_order_id' => $razorpay_order_id,
                            'razorpay_payment_id' => $razorpay_payment_id,
                            'razorpay_signature' => $razorpay_signature,
                        ]);
            
            

            $transaction = Transactions::where('id', $trxId)->with(['createdByUser'])->first();

            if(isset($transaction->id)){
                $transaction->transaction_number = $razorpay_payment_id;
                $transaction->status = 3;
                $transaction->transaction_status = 3;
                $transaction->transaction_response = json_encode($payment_response, true);
                $transaction->save();

                $user = $transaction->createdByUser;
                $newBalance = $transaction->amount + $user['balance'];
                User::where('id', $user['id'])->update([
                    'balance' => $newBalance,
                ]);
                Auth::login($user);
                $request->session()->put('user', $user);
            }

            $encodedId = base64_encode($transaction->id);
            $url = route('payment_success', ['id' => $encodedId]);
            return redirect($url);

            // Payment was successful
            // return redirect()->route('payment.success');
        } catch (\Exception $e) {

            $transaction = Transactions::where('id', $trxId)->with(['createdByUser'])->first();

            if(isset($transaction->id)){
                $transaction->transaction_number = $razorpay_payment_id;
                $transaction->status = 2;
                $transaction->transaction_status = 2;
                $transaction->transaction_response = json_encode($payment_response, true);
                $transaction->save();

                $user = $transaction->createdByUser;
                Auth::login($user);
                $request->session()->put('user', $user);
            }
            
            $encodedId = base64_encode($transaction->id);
            $url = route('payment_fail', ['id' => $encodedId]);
            return redirect($url);
            // Payment failed
            // return redirect()->route('payment.failure');
        }
    }

    // public function store(Request $request)
    // {
    //     $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

    //     $order = $api->order->create([
    //         'receipt' => '123',
    //         'amount' => $request->input('amount') * 100, 
    //         'currency' => 'INR'
    //     ]);

        

    //     $orderId = $order['id'];

    //     Session::put('razorpay_order_id', $orderId);

    //     return view('payment', compact('orderId'));
    // }

    // public function callback(Request $request)
    // {
    //     $input = $request->all();
    //     dd($input);
    //     $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

    //     try {
    //         $api->utility->verifyPaymentSignature([
    //                         'razorpay_order_id' => $input['razorpay_order_id'],
    //                         'razorpay_payment_id' => $input['razorpay_payment_id'],
    //                         'razorpay_signature' => $input['razorpay_signature'],
    //                     ]);
            
    //                     // Payment was successful
    //         return redirect()->route('payment.success');
    //     } catch (\Exception $e) {
    //         // Payment failed
    //         return redirect()->route('payment.failure');
    //     }
    // }
}
