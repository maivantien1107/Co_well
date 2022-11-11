<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;


class UsersExport implements FromQuery
{
    use Exportable;
    public function __construct($data)
    {
        $this->user_id = $data->unSelect? explode(',',$data->unSelect):[];
        $this->sort_direction=$data->sort_direction?$data->sort_direction:'desc';
        $this->sort_field=$data->sort_field?$data->sort_field:'id';
        $this->search=$data->search?$data->search:'';
        
    }

    public function query()
    {
        if ($this->search!=''){

            return User::select('users.id', 'users.name', 'email', 'roles.name AS role_name')
                        ->whereRaw('match(email,users.name) against(?)', array($this->search))
                        ->whereNotIn('users.id',$this->user_id)
                        ->join('role_users','users.id','=','user_id')
                        ->join('roles','role_id','=','roles.id')
                        ->orderBy($this->sort_field,$this->sort_direction);
        }
        else {
            return User::select('users.id', 'users.name', 'email', 'roles.name AS role_name')
            ->whereNotIn('users.id',$this->user_id)
            ->join('role_users','users.id','=','user_id')
            ->join('roles','role_id','=','roles.id')
            ->orderBy($this->sort_field,$this->sort_direction);

        }
    }

    
}