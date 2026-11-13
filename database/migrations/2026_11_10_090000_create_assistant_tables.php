<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Assistant GANXÔ : base de connaissances, réglages et journal anonymisé.
 * Le journal ne contient JAMAIS le texte du visiteur, ni IP, ni identifiant utilisateur.
 */
class CreateAssistantTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('assistant_kb')) {
            Schema::create('assistant_kb', function (Blueprint $table) {
                $table->id();
                $table->string('question', 255);
                $table->text('answer');
                $table->text('keywords')->nullable();
                $table->string('cta_label', 80)->nullable();
                $table->string('cta_url', 255)->nullable();
                $table->boolean('active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('assistant_settings')) {
            Schema::create('assistant_settings', function (Blueprint $table) {
                $table->string('key', 80)->primary();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('assistant_logs')) {
            Schema::create('assistant_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('kb_id')->nullable()->index();
                $table->boolean('escalated')->default(false);
                $table->boolean('matched')->default(false);
                $table->timestamp('created_at')->nullable()->index();
                $table->foreign('kb_id')->references('id')->on('assistant_kb')->nullOnDelete();
            });
        }

        // Permissions du panneau d'administration (spatie/laravel-permission) : données, pas schéma.
        if (Schema::hasTable('permissions')) {
            $now = now();
            foreach (['Assistant Access', 'Assistant Edit'] as $name) {
                if (!DB::table('permissions')->where('name', $name)->where('guard_name', 'web')->exists()) {
                    DB::table('permissions')->insert([
                        'name' => $name, 'guard_name' => 'web', 'permissionsgroupname' => 'Assistant',
                        'created_at' => $now, 'updated_at' => $now,
                    ]);
                }
            }
            // Mêmes rôles que « Feature Box Access » (réglages de la page d'accueil).
            $permIds = DB::table('permissions')->whereIn('name', ['Assistant Access', 'Assistant Edit'])->pluck('id');
            $src = DB::table('permissions')->where('name', 'Feature Box Access')->value('id');
            if ($src && Schema::hasTable('role_has_permissions')) {
                $roles = DB::table('role_has_permissions')->where('permission_id', $src)->pluck('role_id');
                foreach ($roles as $roleId) {
                    foreach ($permIds as $pid) {
                        $exists = DB::table('role_has_permissions')->where('role_id', $roleId)->where('permission_id', $pid)->exists();
                        if (!$exists) {
                            DB::table('role_has_permissions')->insert(['permission_id' => $pid, 'role_id' => $roleId]);
                        }
                    }
                }
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('assistant_logs');
        Schema::dropIfExists('assistant_settings');
        Schema::dropIfExists('assistant_kb');
        if (Schema::hasTable('permissions')) {
            $ids = DB::table('permissions')->whereIn('name', ['Assistant Access', 'Assistant Edit'])->pluck('id');
            DB::table('role_has_permissions')->whereIn('permission_id', $ids)->delete();
            DB::table('permissions')->whereIn('id', $ids)->delete();
        }
    }
}
