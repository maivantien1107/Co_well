<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use SMTPValidateEmail\Validator as SmtpEmailValidator;
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
        if (!Gate::allows('isSuperAdmin')) {
            return $this->unauthorizedResponse();
        }
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'email|required|unique:users,email|max:255',
            'password' => 'required|max:255|min:6',
            'type' => 'required',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $checkMailValid = $this->checkValidatedMail($request['email']);
        if (!$checkMailValid) {
            return $this->sendError('This email is not valid!');
        }
            $user = $this->user->create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'sex' => $request['sex'],
                'phone'=>$request['phone']
            ]);
            $user->roles()->attach($request['role_id']);

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
        $user = User::select([
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
        ->where('users.id',$id)
        ->first();
        return $this->withData($user, 'User Detail');
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
        $user = User::find($id);
        $validateRequest = [
            'name' => 'required|max:255',
            'role_id' => 'required',
            'sex'=>'required',
            'phone'=>'',
            'email' => 'email|required|max:255',

        ];
        
        $validated = Validator::make($request->all(), $validateRequest);
        // if (Sentinel::user()->getType($user->id) == 2 && Auth::user()->id != $id) {
        //     return $this->unauthorizedResponse();
        // }
        
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
       
            $user->name = $request['name'];
            $user->sex=$request['sex'];
            $user->phone=$request['phone'];
            $user->email=$request['email'];
            $user->save();
            $user->roles()->sync([$request['role_id']]);
        return $this->withData($user, 'User has been updated!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('isSuperAdmin')) {
            return $this->unauthorizedResponse();
        }
        $user = $this->user->findOrFail($id);
        $user->delete();

        return $this->withSuccessMessage('User has been deleted!');
    }

    
    public function checkValidatedMail($email)
    {
        $sender    = 'maivantien1107@gmail.com';
        $validator = new SmtpEmailValidator($email, $sender);
        $results   = $validator->validate();
        return $results[$email];
    }
}
