<?php

namespace App\Imports;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Hash;
use Throwable;

class UserImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation
{
    use Importable, SkipsErrors;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
         $user = new User([
            'firstname'     => $row['firstname'],
            'lastname'     => $row['lastname'],
            'name'     => $row['firstname'].' '.$row['lastname'],
            'email'    => $row['email'],
            'empid'    => $row['empid'],
            'password' => Hash::make($row['password']),
            'status' => '1',
            'dashboard' =>  ($row['role'] == 'superadmin') ? 'Admin' : 'Employee',
            'verified' => '1',
            'darkmode' => setting('DARK_MODE')
        ]);


        $user->assignRole($row['role']);

        return $user;
    }

    public function rules(): array
    {
        return  [
            '*.firstname' => ['required','string',],
            '*.lastname' => ['required','string',],
            '*.email' => ['required','string','unique:users,email'],
            '*.password' => ['required'],
            '*.empid' => ['required'],
            '*.role' => ['required'],
        ];


    }
}
