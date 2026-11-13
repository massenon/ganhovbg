<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddcolumnExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       

        Schema::table('users', function (Blueprint $table) {
            
            $table->string('departments')->nullable()->after('gender');
            $table->string('dashboard')->nullable()->after('departments');
        });

        Schema::table('tickets', function(Blueprint $table){
            $table->bigInteger('lastreply_mail')->unsigned()->nullable()->after('last_reply');
            $table->dropColumn('toassignuser_id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            
            $table->boolean('notifiable')->default('0')->after('notifiable_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            
            $table->datetime('inactive_date')->nullable()->after('last_login_at');
            $table->datetime('last_logins_at')->nullable()->after('inactive_date');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addcolumn_extra');
    }
}
