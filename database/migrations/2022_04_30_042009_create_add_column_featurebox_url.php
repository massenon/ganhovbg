<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddColumnFeatureboxUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature_boxes', function (Blueprint $table) 
        {

            $table->longtext('featureboxurl')->nullable()->after('subtitle');
            $table->longtext('url_checkbox')->nullable()->after('featureboxurl');
             
        });

        Schema::table('articles', function (Blueprint $table) {

            $table->dropForeign(['category_id']);
        });
        
        Schema::table('tickets', function (Blueprint $table) {

            $table->dropForeign(['category_id']);
            $table->datetime('closing_ticket')->change();
            $table->datetime('auto_close_ticket')->change();
            $table->datetime('auto_overdue_ticket')->change();
            $table->bigInteger('selfassignuser_id')->unsigned()->nullable()->after('auto_overdue_ticket');
            $table->bigInteger('closedby_user')->unsigned()->nullable()->after('selfassignuser_id');
            $table->longText('toassignuser_id')->change();
            $table->softDeletes();
        });

        Schema::table('comments', function (Blueprint $table) {

            
            $table->softDeletes();
        });

        Schema::table('sendmails', function (Blueprint $table) {

            $table->string('tag')->nullable()->after('mailtext');
            $table->string('selecttagcolor')->nullable()->after('tag');
        });

        Schema::table('permissions', function (Blueprint $table) {

            $table->string('permissionsgroupname')->nullable()->after('guard_name');
        });

        Schema::table('usersettings', function (Blueprint $table) {

            $table->boolean('emailnotifyon')->nullable()->after('ticket_refresh');
        });

        Schema::table('media', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('verify_otps', function (Blueprint $table) {
            
            $table->string('cust_id')->nullable()->change();
            $table->string('type')->nullable()->after('otp');
        });
        Schema::table('groups', function (Blueprint $table) {
            
            $table->boolean('groupstatus')->nullable()->after('groupname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_column_featurebox_url');
    }
}
