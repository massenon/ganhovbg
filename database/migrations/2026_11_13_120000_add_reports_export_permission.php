<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Permission « Reports Export » (export Excel et rapport PDF des alertes), accordée aux rôles qui ont « Ticket Access ». */
class AddReportsExportPermission extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }
        $now = now();
        if (!DB::table('permissions')->where('name', 'Reports Export')->where('guard_name', 'web')->exists()) {
            DB::table('permissions')->insert(['name' => 'Reports Export', 'guard_name' => 'web', 'permissionsgroupname' => 'Reports', 'created_at' => $now, 'updated_at' => $now]);
        }
        $pid = DB::table('permissions')->where('name', 'Reports Export')->value('id');
        $src = DB::table('permissions')->where('name', 'Ticket Access')->value('id');
        if ($pid && $src && Schema::hasTable('role_has_permissions')) {
            foreach (DB::table('role_has_permissions')->where('permission_id', $src)->pluck('role_id') as $roleId) {
                if (!DB::table('role_has_permissions')->where('role_id', $roleId)->where('permission_id', $pid)->exists()) {
                    DB::table('role_has_permissions')->insert(['permission_id' => $pid, 'role_id' => $roleId]);
                }
            }
        }
        $this->resetPermissionCache();
    }

    // Le cache des permissions (spatie) doit être vidé pour que les nouvelles permissions soient prises en compte tout de suite.
    private function resetPermissionCache()
    {
        try { app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions(); } catch (\Throwable $e) {}
    }

    public function down()
    {
        if (Schema::hasTable('permissions')) {
            $id = DB::table('permissions')->where('name', 'Reports Export')->value('id');
            if ($id) {
                if (Schema::hasTable('role_has_permissions')) { DB::table('role_has_permissions')->where('permission_id', $id)->delete(); }
                DB::table('permissions')->where('id', $id)->delete();
            }
        }
    }
}
