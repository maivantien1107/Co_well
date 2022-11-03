<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\OTPRequest;
use App\Mail\MailOTP;
use App\Models\User;
use App\Models\VerificationLogin;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use Tymon\JWTAuth\Facades\JWTAuth;
class AdminController extends BaseController
{
    public function __construct()
    {
        $this->user = new User();
    }
    
    public function username($username){
        if(is_numeric($username)){
            $field = 'phone';
        } elseif (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'username';
        }

        return $field;

    }
    public function login(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'username' => 'string|required',
                'password' => 'required'
            ]);

            if ($validated->fails()) {
                return $this->failValidator($validated);
            }
            $typeinput=$this->username($request['username']);
        
            if ($typeinput=="phone"){
                $customer = User::where('phone', $request->username)->first();
            }
            elseif ($typeinput=='email'){
                $customer = User::where('email', $request->username)->first();
            }
            else {
                return $this->badRequest('Tài khoản đăng nhập không hợp lệ!');

            }
            if (!$customer->is_verified) {
            return $this->badRequest('Tài khoản chưa kích hoạt');
            }
            if (!Hash::check($request->password, $customer->password, [])) {
                throw new Exception('Sai thông tin đăng nhập!');
            }
            $verificationOTP=VerificationLogin::where('user_id',$customer->id)->latest()->first();
            $now=Carbon::now();
            if($verificationOTP&&$now->isBefore($verificationOTP->expire_at)){
               $this->sendOTP($typeinput,$request->username,$verificationOTP->otp);
            }
            else{
                $otp=rand(100000,999999);        
                VerificationLogin::create([
                 'user_id'=>$customer->id,
                 'otp'=>$otp,
                 'verify'=>$request->password,
                 'expire_at'=>Carbon::now()->addMinutes(5)
                ]);
                 $this->sendOTP($typeinput,$request->username,$otp);
            }

            return $this->withData($customer, 'Chúng tôi đã gửi mã xác nhận đến số điện thoại của bạn!', 200);

        } catch (JWTAuthException $e) {
            return $this->errorInternal('Đăng nhập thất bại');
        }
    }
    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'username'=>'required|max:255|unique:users',
            // 'email'=>'required|email|unique:users',
            // 'phone' => 'required|unique:users,phone|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'password' => 'required|max:255|min:6',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $code=rand(100000, 999999);
        $message="Mã xác thực của bạn là: ".+$code;
        $typeinput=$this->username($request['username']);
        if ($typeinput=='phone'){
            try {
    
                $account_sid = getenv("TWILIO_SID");
                $auth_token = getenv("TWILIO_TOKEN");
                $twilio_number = getenv("TWILIO_FROM");
                $phone=$request['username'];
                $client = new Client($account_sid, $auth_token);
                $client->messages->create(convertPhone($phone), [
                    'from' => $twilio_number, 
                    'body' => $message]);
    
            } catch (Exception $e) {
                return $this->sendError("Số điện thoại không hợp lệ");
            }
            $customer = $this->user->firstOrCreate([
                'name' => $request['name'],
                'phone' => $request['username'],
                'password' => Hash::make($request['password']),
                'username' => $request['username'],
            ]);
            $usercode=VerificationLogin::create([
                'user_id'=>$customer->id,
                'verify'=>$request['password'],
                'expire_at' =>Carbon::now()->addMinutes(10),
                'otp'=>$code,
            ]);
            return $this->withData($customer, 'Chúng tôi đã gửi mã xác nhận đến số điện thoại của bạn!', 201);

        }
        elseif ($typeinput=='email'){
            $customer = $this->user->firstOrCreate([
                'name' => $request['name'],
                'email' => $request['username'],
                'password' => Hash::make($request['password']),
                'username' => $request['username'],
            ]);
            $usercode=VerificationLogin::create([
                'user_id'=>$customer->id,
                'verify'=>$request['password'],
                'expire_at' =>Carbon::now()->addMinutes(10),
                'otp'=>$code,
            ]);
            $this->sendMail($request['username'],$message);
            return $this->withData($customer,'Chúng tôi đã gửi mã xác nhận đến email của bạn',201);

        }
       
        
      
    }
    public function authOTP(OTPRequest $request){
        $user=User::where('username',$request->username)->first();
         $verificationOTP=VerificationLogin::where('user_id',$user->id)->where('otp',$request->otp)->latest()->first();
         $now=Carbon::now();
         if(!$verificationOTP){
              return $this->sendError("Xác thực với OTP không thành công!");
         }else{
             if($now->isAfter($verificationOTP->expire_at)){     
              return $this->sendError("OTP hết hạn!");
             }
         }
    
     
     if($user){
        $verificationOTP->update([
            'expire_at'=>Carbon::now()
        ]);
        $credentials=[
            'password'=>$verificationOTP->verify,
            'username'=>$request->username,
        ];
        $token=JWTAuth::attempt($credentials);
        if($user->is_verified==0){
            if ($user->email==null){
                $user->update([
                    'is_verified'=>1,
                    'phone_verified_at' =>Carbon::now(),
                ]);
            }
            if ($user->phone==null){
                $user->update([
                    'is_verified'=>1,
                    'email_verified_at' =>Carbon::now(),
                ]);
            }
            
            return $this->withSuccessMessage('Kích hoạt tài khoản thành công!');
        }
         return $this->responseWithToken($token);
     }
        return $this->sendError("Xác thực không thành công!");
    
    }

    public function forgetPassword(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'phone' => 'string|required|max:12',
        ]);

        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $customer = $this->user->where('phone', $request['phone'])->where('is_verified', 1)->first() ?? null;
        if ($customer) {
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio_sid = getenv("TWILIO_SID");
            $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
            $twilio = new Client($twilio_sid, $token);
            try {
                $twilio->verify->v2->services($twilio_verify_sid)
                    ->verifications
                    ->create($request['phone'], "sms");
            }
            catch (\Exception $e) {
                return $this->sendError('Số điện thoại không hợp lệ');
            }
        } else {
            return $this->sendError("số điện thoại này chưa được đăng ký");
        }

        return $this->withSuccessMessage("Chúng tôi đã gửi mã xác nhận đến số điện thoại của bạn!");
    }

    public function verifiedPhone(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'code'=>'required|min:6|max:6',
            'phone' => 'required|unique:users,phone|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
        ]);
        // if ($validated->fails()) {
        //     return $this->failValidator($validated);
        // }
        try {
           $user=User::where('phone', $request['phone'])->first();
           $code=UserCode::where('user_id',$user->id)->first();
           if ($code->code==$request['code']){
            $user = tap(User::where('phone', $request['phone']))->update([
                'is_verified' => true,
                'phone_verified_at' => Carbon::now(),
            ]);
            return $this->withSuccessMessage('Mã xác nhận chính xác!');
           }
        }
        catch (Exception $e) {
            return $this->sendError("The code is incorrect or has been used!");
        }
        return $this->sendError('Mã xác nhận không chính xác!');
    }

    public function newPassword (Request $request)
    {
        $validated = Validator::make($request->all(), [
            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'newPassword' => 'required|max:255',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $customer = $this->user->where('phone', $request['phone'])->firstOrFail();
        $customer->password = Hash::make($request['newPassword']);
        $customer->save();

        return $this->withSuccessMessage("Đổi mật khẩu thành công!");
    }

    public function changePassword(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'oldPassword' => 'required|max:255',
            'newPassword' => 'required|max:255',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $customer = Auth()->user();
        if (!password_verify($request['oldPassword'], $customer->password))
        {
            return $this->sendError('Mật khẩu cũ không chính xác!');
        }
        $customerUpdatePassword = $customer->update([
            'password' => Hash::make($request['newPassword']),
        ]);
        if ($customerUpdatePassword) {
            $this->logout();
        }

        return $this->withSuccessMessage('Đổi mật khẩu thành công!');

    }

    public function addMail(Request $request)
    {
        $uniqueEmail = Customer::where('email', $request->email)->whereNotNull('email_verified_at') ->first() ?? null;
        if (!empty($uniqueEmail)) {
            return $this->sendError("Email này đã được sử dụng");
        }
        $checkMailValid = $this->checkValidatedMail($request->email);
        if (!$checkMailValid) {
            return $this->sendError('email không hợp lệ!');
        }
        $customer = Auth::user();
        $customer->update([
            'email' => $request->email,
        ]);
        $token =Str::random(60);
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $customer->email,
            'token' => $token,
        ]);
        $sendMail = $customer->notify(new CustomerAddEmail($token));

        return $this->withSuccessMessage('Chúng tôi đã gửi link xác thực đến email của bạn!');
    }

    public function customerActiveMail($token)
    {
        $customerActiveMail = PasswordReset::where('token', $token)->firstOrFail();
        if (Carbon::parse($customerActiveMail->updated_at)->addMinutes(720)->isPast()) {
            $customerActiveMail->delete();
            return response()->json([
                'message' => 'Token hết hạn.',
            ], 422);
        }

        $customer = Customer::where('email', $customerActiveMail->email)->firstOrFail();
        $customer->update([
            'email_verified_at' => Carbon::now(),
        ]);

        return $this->withSuccessMessage("Thêm email thành công!");
    }

    public function checkValidatedMail($email)
    {
        $sender    = 'namxuanhoapro@gmail.com';
        $validator = new SmtpEmailValidator($email, $sender);
        $results   = $validator->validate();
        return $results[$email];
    }
    public function sendMail($email,$otp){
        $details = [
            'title' => 'Title: Mail xác thực danh tính',
            'body' => 'Body: Mã xác nhận của bạn là'.$otp,
        ];
        // dd($user->email);
        Mail::to($email)->send(new MailOTP($details));
    }
    public function responseWithToken($token){
        $user=auth()->user();
        $data = [
            'personnel_information' => $user,
            'token' => [
                'status_code' => 200,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in'=>60
            ]
        ];
       return $this->withData($data,'Đăng nhập thành công',200);
    }
    public function sendOTP($field,$type,$otp){
        if ($field=='email'){
            $this->sendMail($type,$otp);
        }
        elseif($field=='phone'){
            $message="Mã xác nhận của bạn là:".+$otp;
            try {   
                $account_sid = getenv("TWILIO_SID");
                $auth_token = getenv("TWILIO_TOKEN");
                $twilio_number = getenv("TWILIO_FROM");
                $phone=$type;
                $client = new Client($account_sid, $auth_token);
                $client->messages->create(convertPhone($phone), [
                    'from' => $twilio_number, 
                    'body' => $message]);
            } catch (Exception $e) {
                return $this->sendError("Số điện thoại không hợp lệ");
            }

        }
        else {
            return $this->sendError('Tài khoản không hợp lẹ');
        }
    }
}
