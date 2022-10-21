<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
class AdminController extends BaseController
{
    public function index()
    {
        $users = $this->user->all();
        return $this->withData($users, 'List User');
    }

    public function login(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'email' => 'email|required',
                'password' => 'required'
            ]);

            if ($validated->fails()) {
                return $this->failValidator($validated);
            }

            $credentials = $request->only('email','password');

            if (!$user=Sentinel::authenticate($credentials)) {
                if (!findRoleById())
                return $this->badRequest('Wrong login information!');
            }

            // $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Wrong login information!');
            }

            $tokenResult = JWTAuth::attempt($credentials);
            $datas = [
                'personnel_information' => $user,
                'token' => [
                    'status_code' => 200,
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer'
                ]
            ];
            return $this->withData($datas, 'Logged in successfully!');
        } catch (JWTAuthException $e) {
            return $this->errorInternal('Login failed');
        }
    }
    public function logout()
    {
        auth()->logout();
        return $this->withSuccessMessage('Log out!');
    }

    public function store(Request $request)
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'email|required|unique:users,email|max:255',
            'password' => 'required|max:255|min:6',
            'type' => 'required',
            'avatar' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $checkMailValid = $this->checkValidatedMail($request['email']);
        if (!$checkMailValid) {
            return $this->sendError('This email is not valid!');
        }
        if ($request->hasFile('avatar')) {
            $feature_image_name= $request['avatar']->getClientOriginalName();
            $path = $request->file('avatar')->storeAs('public/photos/personnel', $feature_image_name);
            $linkAvatar = url('/') . Storage::url($path);
            $user = $this->user->create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'type' => $request['type'],
                'avatar' => $linkAvatar,
            ]);
        }

        return $this->withData($user, 'Create user successfully!', 201);
    }

    public function show($id)
    {
        $user = $this->user->findOrFail($id);
        return $this->withData($user, 'User Detail');
    }


    public function changePasswordProfile(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'passwordOld' => 'required|max:255|min:6',
            'passwordNew' => 'required|max:255|min:6',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $user = auth()->user();
        if (!password_verify($request['passwordOld'], $user->password))
        {
            return $this->sendError('Wrong old password!');
        }
        $user->update([
            'password' => Hash::make($request['passwordNew']),
        ]);

        return $this->withData($user, 'Password has been updated!');
    }

    public function changePassword(Request $request, $userId)
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $validated = Validator::make($request->all(), [
            'password' => 'required|max:255|min:6',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $user = $this->user->findOrFail($userId);
        if ($user->type == 1 || Auth::user()->id == $userId) {
            return $this->unauthorizedResponse();
        }
        $user->update([
            'password' => Hash::make($request['password']),
        ]);

        return $this->withData($user, 'Password has been updated!');
    }

    public function sendMail(Request $request)
    {
        $checkMailValid = $this->checkValidatedMail($request->email);
        if (!$checkMailValid) {
            return $this->sendError('This email is not valid!');
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
            'token' => Str::random(60),
        ]);
        if ($passwordReset) {
            $sendMail = $user->notify(new ResetPasswordRequest($passwordReset->token));
        }

        return $this->withSuccessMessage('We have e-mailed your password reset link!');
    }

    public function resetPassword(Request $request, $token)
    {
        $passwordReset = PasswordReset::where('token', $token)->firstOrFail();
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => 'This password reset token is invalid.',
            ], 422);
        }
        $user = User::where('email', $passwordReset->email)->firstOrFail();
        $validated = Validator::make($request->all(), [
            'password' => 'required|max:255|min:6',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $user->update([
            'password' => Hash::make($request['password'])
        ]);
        //$passwordReset->delete();

        return $this->withData($user, 'Password reset successful!');
    }

    public function checkValidatedMail($email)
    {
        $sender    = 'namxuanhoapro@gmail.com';
        $validator = new SmtpEmailValidator($email, $sender);
        $results   = $validator->validate();
        return $results[$email];
    }

    public function readReportDriver($id)
    {
        $driverReport = $this->reportDriver->findOrFail($id);
        $driverReport->update([
            "status" => ReportDriver::STATUS_READ,
        ]);

        return $this->withSuccessMessage("Đã đọc phản hồi");
    }



}
