<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Permissions « Newsletter Access / Edit » (données, pas schéma), accordées aux mêmes rôles que « Assistant Access ». */
class AddNewsletterPermissions extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }
        $now = now();
        foreach (['Newsletter Access', 'Newsletter Edit'] as $name) {
            if (!DB::table('permissions')->where('name', $name)->where('guard_name', 'web')->exists()) {
                DB::table('permissions')->insert(['name' => $name, 'guard_name' => 'web', 'permissionsgroupname' => 'Newsletter', 'created_at' => $now, 'updated_at' => $now]);
            }
        }
        $perm = DB::table('permissions')->whereIn('name', ['Newsletter Access', 'Newsletter Edit'])->pluck('id', 'name');
        if (Schema::hasTable('role_has_permissions')) {
            foreach (['Newsletter Access' => 'Assistant Access', 'Newsletter Edit' => 'Assistant Edit'] as $new => $like) {
                $src = DB::table('permissions')->where('name', $like)->value('id');
                if (!$src || !isset($perm[$new])) { continue; }
                foreach (DB::table('role_has_permissions')->where('permission_id', $src)->pluck('role_id') as $roleId) {
                    if (!DB::table('role_has_permissions')->where('role_id', $roleId)->where('permission_id', $perm[$new])->exists()) {
                        DB::table('role_has_permissions')->insert(['permission_id' => $perm[$new], 'role_id' => $roleId]);
                    }
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
            $ids = DB::table('permissions')->whereIn('name', ['Newsletter Access', 'Newsletter Edit'])->pluck('id');
            if (Schema::hasTable('role_has_permissions')) { DB::table('role_has_permissions')->whereIn('permission_id', $ids)->delete(); }
            DB::table('permissions')->whereIn('id', $ids)->delete();
        }
    }
}
