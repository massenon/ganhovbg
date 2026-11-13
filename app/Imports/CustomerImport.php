<?php

namespace App\Imports;

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
use App\Models\Customer;
use App\Models\CustomerSetting;

class CustomerImport implements  ToModel, WithHeadingRow,SkipsOnError, WithValidation
{
    use Importable, SkipsErrors;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user =  Customer::create([
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'username' => $row['firstname'] .' '. $row['lastname'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'userType' => 'Customer',
            'timezone' => setting('default_timezone'),
            'status' => '1',
            'verified' => '1',
            'image' => null,
            
        ]);

        $customersetting = new CustomerSetting();
        $customersetting->custs_id = $user->id;
        $customersetting->darkmode = setting('DARK_MODE');
        $customersetting->save();

        return $user;
    }


    public function rules(): array
    {
        return  [
            '*.firstname' => ['required','string',],
            '*.lastname' => ['required','string',],
            '*.email' => ['required','string','unique:customers,email'],
            '*.password' => ['required'],
        ];
  
         
    }
}
