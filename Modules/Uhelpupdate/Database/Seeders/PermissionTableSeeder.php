<?php

namespace Modules\Uhelpupdate\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         // create permissions
		$this->createPermissions();

        // assign roles and permissions
		$this->assignPermissions();
        // $this->call("OthersTableSeeder");
    }

    public function createPermissions(){

        //  Canned Response
        Permission::create(['id' => 100, 'name' => 'Canned Response Access'  , 'guard_name' => 'web',]);
        Permission::create(['id' => 101, 'name' => 'Canned Response Create'  , 'guard_name' => 'web',]);
        Permission::create(['id' => 102, 'name' => 'Canned Response Edit'  , 'guard_name' => 'web',]);
        Permission::create(['id' => 103, 'name' => 'Canned Response Delete'  , 'guard_name' => 'web',]);

        // Envato
        Permission::create(['id' => 104, 'name' => 'Envato Access'  , 'guard_name' => 'web',]);
        Permission::create(['id' => 105, 'name' => 'Envato API Token Access'  , 'guard_name' => 'web',]);
        Permission::create(['id' => 106, 'name' => 'Envato License Details Access'  , 'guard_name' => 'web',]);

        // App Info
        Permission::create(['id' => 107, 'name' => 'App Info Access'  , 'guard_name' => 'web',]);
        Permission::create(['id' => 108, 'name' => 'App Purchase Code Access'  , 'guard_name' => 'web',]);
    }

    public function assignPermissions()
	{

        $role = Role::where('name', 'Superadmin')->first();
        $permissions = Permission::get();
        foreach ( $permissions as $code ) {
			$role->givePermissionTo($code);
		};
    }
}
