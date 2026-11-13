<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('employeesreplying')->nullable()->after('subject');
            $table->string('usernameverify')->nullable()->after('employeesreplying');
            $table->string('emailticketfile')->nullable()->after('usernameverify');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->string('logintype')->nullable()->after('email');
            $table->string('voilated')->nullable()->after('userType');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->string('primary_color')->nullable()->after('enddate');
            $table->string('secondary_color')->nullable()->after('primary_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
        });
    }
}
