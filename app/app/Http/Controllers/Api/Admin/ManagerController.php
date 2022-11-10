<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use SMTPValidateEmail\Validator as SmtpEmailValidator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ManagerController extends BaseController
{
    public function __construct()
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
        $sort_direction = request('sort_direction','desc');
        if(!in_array($sort_direction,['asc','desc'])){
            $sort_direction = 'desc';
        }
        $sort_field = request('sort_field','id');
        $sort_field="users.".''.$sort_field;
        if (!in_array($sort_field,['users.name','users.email','users.id','users.phone'])){
            $sort_field='roles.name';
        }
        $search=request('search','');
        if ($search){
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
            ->whereRaw('match(email,users.name,phone) against(?)', array($search))
            ->join('role_users','users.id','=','user_id')
            ->join('roles','role_id','=','roles.id')
            ->orderBy($sort_field,$sort_direction)
           ->paginate(10);
        }
        else{
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
            ->orderBy($sort_field,$sort_direction)
           ->paginate(10);
        }
        
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
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'email|required|unique:users,email|max:255',
            'sex' => 'required',
            'role_id' => 'required',
            'phone' => 'required',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        // $checkMailValid = $this->checkValidatedMail($request['email']);
        // if (!$checkMailValid) {
        //     return $this->sendError('This email is not valid!');
        // }
            $user = $this->user->create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['phone']),
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
        // $user = JWTAuth::toUser($request->input('token')); 
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
            'sex' => 'required',
            'role_id' => 'required',
        ];
        
        $validated = Validator::make($request->all(), $validateRequest);
        
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
        $user = $this->user->findOrFail($id);
        if ($this->user->getType($user->id) == 'superadmin' || Auth::user()->id == $id) {
            return $this->unauthorizedResponse();
        }
        $user->delete();

        return $this->withSuccessMessage('User has been deleted!');
    }
    public function getAll(){
        $sort_direction = request('sort_direction','desc');
        if(!in_array($sort_direction,['asc','desc'])){
            $sort_direction = 'desc';
        }
        $sort_field = request('sort_field','id');
        $sort_field="users.".''.$sort_field;
        if (!in_array($sort_field,['users.name','users.email','users.id','users.phone'])){
            $sort_field='users.id';
        }
        $search=request('search','');
        if ($search){
            $users = User::select([
                'users.id',
            ])
            ->whereRaw('match(email,users.name,phone) against(?)', array($search))
            ->orderBy($sort_field,$sort_direction)
           ->get();
        }
        else{
            $users = User::select([
                'users.id',
            ])
            ->orderBy($sort_field,$sort_direction)
           ->get();
        }
        $idArr=[];
        foreach($users as $user){
            $idArr[]=$user->id;

        }
        return $this->withData($idArr, 'Chọn tất cả thành công');
    }
    
    public function checkValidatedMail($email)
    {
        $sender    = 'maivantien1107@gmail.com';
        $validator = new SmtpEmailValidator($email, $sender);
        $results   = $validator->validate();
        return $results[$email];
    }

    public function search(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|max:255'
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $users=User::whereRaw('match(name,email,phone) against(?)', array($request['email']))
                    ->get();
        return $this->withData($users, 'Search done');
    }
    public function export($users) 
    {
        $usersArray = explode(',',$users);
        $results= (new UsersExport($usersArray))->download('users.xlsx');
        if ($results){
            return $this->withSuccessMessage('export thanh cong');
        }
        else {
            return $this->sendError('Khong thanh cong');
        }
    }
}
