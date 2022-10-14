<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Cartalyst\Support\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ManagerController extends BaseController
{
    public function __construct(Request $request)
    {
        $this->user = new User();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $users = User::select([
            'users.id',
            'users.name',
            'email',
            'phone',
            'sex',
            'users.permissions',
            'roles.name AS role_name',
            'role_id',
            'last_login',
            'phone_verified_at',
            'users.created_at',
            'users.updated_at',
            'phone_verified_at',
        ])
        ->join('role_users','users.id','=','user_id')
        ->join('roles','role_id','=','roles.id')
        ->orderBy('users.id','desc')
        ->get();
        
        // $users=$this->user->all();
        return $this->withData($users, 'List User');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
