<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromQuery
{
    use Exportable;
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function query()
    {
        return User::query()->whereId($this->students);
    }
}