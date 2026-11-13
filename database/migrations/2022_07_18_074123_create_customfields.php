<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomfields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customfields', function (Blueprint $table) {

            $table->id();
            $table->string('fieldnames');
            $table->string('fieldtypes');
            $table->string('fieldoptions')->nullable();
            $table->string('displaytypes')->nullable();
            $table->boolean('fieldrequired')->default(0);
            $table->boolean('fieldprivacy')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customfields');
    }
}
