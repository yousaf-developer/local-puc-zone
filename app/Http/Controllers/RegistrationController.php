<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\TempUsers;
use App\Models\States;
use App\Models\ApiSettings;
use App\Models\Settings;
use App\Http\Controllers\PaytmController;

class RegistrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $paytm;

    // Use dependency injection to bring in the PaymentEncode class
    public function __construct(PaytmController $paytm)
    {
        $this->paytm = $paytm;
    }


    public function index(Request $request)
    {
        // return view('user/registration');
    }

    public function register(Request $request)
    {
        $data['states'] = States::where('status', '1')->get();
        $admin_settings = Settings::first();
        $retailer_rate = $admin_settings->retailer_rate;

        do {
            $username_auto = 'PUCZ' . mt_rand(100000, 999999);
            $existing_number = User::where('username', $username_auto)->first();
        } while ($existing_number);

        $data['username_auto'] = $username_auto;
        $data['retailer_rate'] = $retailer_rate;
        $data['settings'] = Settings::first();

        return view('user/registration')->with($data);
    }

    public function registerUser(Request $request)
    {   
        // dd($request->all());
        $validatedData = $request->validate([
            'user_name' => 'required|max:100',
            'username_auto' => 'required|max:100',
            'company_name' => 'required|max:100',
            'user_phone' => 'required|numeric',
            'user_email' => 'required|email|unique:users,email',
            'user_pin' => 'required|max:100',
            'user_state' => 'required',
            'user_city' => 'required',
            'user_area' => 'required',
            'upload_picture' => 'required|image|mimes:jpeg,png,jpg,gif,JPEG,PNG,JPG,GIF|max:400',
            'upload_aadhar' => 'required|max:400|image|mimes:jpeg,png,jpg,JPEG,PNG,JPG|max:400',
        ]);

        // Process form submission if validation passes
        $existing = User::where('user_type', $request->username_auto)->first();

        if (isset($existing->id)) {
            return response()->json(['status' => 401, 'message' => "Username is already exist, try another time!"]);
        }

        // check payment settings status is on/off
        $apisettings = ApiSettings::where('type', 'RAZOR_PAY')->first();
        // if(isset($apisettings->status) && $apisettings->status == 'on'){
            $paymentStatus = 'on';
        // }else{
        //     $paymentStatus = 'off';
        // }

        if($paymentStatus == 'on'){
            $Users = new TempUsers();
        }else{
            $Users = new User();
        }
        
        // Update the settings with the new values
        $Users->type = 'user';
        $Users->name = $request->user_name;
        $Users->username = $request->username_auto;
        $Users->email = $request->user_email;
        $Users->phone_number = $request->user_phone;
        $Users->user_type = 'retailer';
        $Users->company_name = $request->company_name;
        $Users->pin_code = $request->user_pin;
        $Users->state_id = $request->user_state;
        $Users->city_id = $request->user_city;
        $Users->area_id = $request->user_area;
        $Users->status = 'inactive';
        $Users->landmark = $request->user_landmark;

        $req_file = 'upload_picture';
        $path = '/assets/uploads/profile';
        if ($request->hasFile($req_file)) {

            $uploadedFile = $request->file($req_file);

            $savedImage = saveSingleImage($uploadedFile, $path);
            $Users->profile_picture = url('/public').$savedImage;
        }

        $req_file1 = 'upload_aadhar';
        $path1 = '/assets/uploads/aadhar';
        if ($request->hasFile($req_file1)) {

            $uploadedFile = $request->file($req_file1);

            $savedFile = saveSingleImage($uploadedFile, $path1);
            $Users->aadhar = url('/public').$savedFile;
        }

        $Users->status = 'inactive';
        $Users->created_at = date('Y-m-d H:i:s');
        $Users->updated_at = date('Y-m-d H:i:s');

        // Save the changes
        $Users->save();

        // send email code
        // $body = view('emails.registration', $Users);
        // $userEmailsSend[] = $Users->email;
        // // to username, to email, from username, subject, body html
        // $response = sendMail($Users->name, $userEmailsSend, 'PUCZONE', 'Registration', $body);

        do {
            $username_auto = 'PUCZ' . mt_rand(100000, 999999);
            $existing_number = User::where('username', $username_auto)->first();
        } while ($existing_number);

        $data['username_auto'] = $username_auto;
        $data['userData'] = $Users;
        $data['formData'] = $request->all();
        $data['payment_gateway_settings'] = $paymentStatus;

        //Handle Registeration Payment
        return response()->json(['status' => 200, 'message' => "User Created Successfully, Now set password and then login!", 'data' => $data]);
    }









    public function getOtpCodeForget(Request $request)
    {
        $validatedData = $request->validate([
            'registered_email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->registered_email)->first();
        $userId = $user->id;

        $otp = mt_rand(1000, 9999);

        User::where('id', $userId)->update([
            'otp' => $otp,
            'otp_created_at' => date('Y-m-d H:i:s'),
        ]);

        // send email code
        $userDetail = User::where('email', $request->registered_email)->first();
        $body = view('emails.forget_password', $userDetail);
        $userEmailsSend[] = $userDetail->email;
        // to username, to email, from username, subject, body html
        sendMail($userDetail->name, $userEmailsSend, 'PUCZONE', 'Forget Password OTP', $body); // send_to_name, send_to_email, email_from_name, subject, body

        return response()->json(['status' => 200, 'message' => "OTP (One Time Password) is sent to user email adderess. Kindly enter OTP then change password!"]);
    }

    public function verifyOtpCodeForget(Request $request)
    {
        $validatedData = $request->validate([
            'registered_email' => 'required|email|exists:users,email',
            'one_time_password' => 'required',
        ]);

        $user = User::where('email', $request->registered_email)->first();

        if ($user->otp != $request->one_time_password) { // check OTP is valid or not
            return response()->json(['status' => 402, 'message' => "OTP is not valid!"]);
        } else {
            return response()->json(['status' => 200, 'message' => "OTP is verified, please enter new password!"]);
        }
    }

    public function resetForgetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'registered_email' => 'required|email|exists:users,email',
            'one_time_password' => 'required',
            'new_password' => 'required',
        ]);

        $user = User::where('email', $request->registered_email)->first();
        $userId = $user->id;

        if ($user->otp != $request->one_time_password) { // check OTP is valid or not
            return response()->json(['status' => 402, 'message' => "OTP is not valid!"]);
        } else {

            $valid = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/', $request->new_password);

            if (!$valid) {
                return response()->json(['status' => 402, 'message' => "The new password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character."]);
            } else {

                User::where('id', $userId)->update([
                    'otp' => null,
                    'password' => bcrypt($request->new_password),
                    // 'status' => 'active',
                ]);

                return response()->json(['status' => 200, 'message' => "Password reset successfully!"]);
            }
        }
    }
}
