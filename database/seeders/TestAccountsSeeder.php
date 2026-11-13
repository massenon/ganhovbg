<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerSetting;
use App\Models\User;
use App\Models\usersettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * Comptes de TEST (local / recette uniquement), idempotent :
 *   php artisan db:seed --class=TestAccountsSeeder
 * Administrateur : admin.test@ganho.local   / Ganxo#Admin2026   (connexion : /login)
 * Utilisateur    : user.test@ganho.local    / Ganxo#User2026    (connexion : /espace-client/connexion)
 * Refuse de s'executer en production (APP_ENV=production) sauf GANHO_ALLOW_TEST_ACCOUNTS=1.
 */
class TestAccountsSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment('production') && !env('GANHO_ALLOW_TEST_ACCOUNTS')) {
            $this->command?->error('Comptes de test refuses en production.');
            return;
        }

        $admin = User::updateOrCreate(['email' => 'admin.test@ganho.local'], [
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'name' => 'Admin Test',
            'status' => '1',
            'verified' => 1,
            'password' => Hash::make('Ganxo#Admin2026'),
            'timezone' => 'UTC',
        ]);
        $role = Role::where('name', 'superadmin')->first();
        if ($role && !$admin->hasRole('superadmin')) {
            $admin->assignRole($role);
        }
        if (!usersettings::where('users_id', $admin->id)->exists()) {
            $s = new usersettings();
            $s->users_id = $admin->id;
            $s->save();
        }

        $user = Customer::updateOrCreate(['email' => 'user.test@ganho.local'], [
            'firstname' => 'Utilisateur',
            'lastname' => 'Test',
            'username' => 'utilisateur.test',
            'userType' => 'Customer',
            'status' => '1',
            'verified' => 1,
            'password' => Hash::make('Ganxo#User2026'),
            'timezone' => 'UTC',
        ]);
        if (!CustomerSetting::where('custs_id', $user->id)->exists()) {
            CustomerSetting::create(['custs_id' => $user->id]);
        }

        $this->command?->info('Admin : admin.test@ganho.local / Ganxo#Admin2026');
        $this->command?->info('User  : user.test@ganho.local / Ganxo#User2026');
    }
}
