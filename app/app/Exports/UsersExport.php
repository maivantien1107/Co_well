<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromQuery
{
    use Exportable;
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function query()
    {
        return User::query()->whereIn('id',$this->users);
    }
}