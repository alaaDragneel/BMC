<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('name');
            $table->string('email');
            $table->string('account_type')->default('gudiAcount');
            $table->string('sns_account_id');
            $table->string('phoneNo');
            $table->string('image');
            $table->string('back_image');
            $table->string('job');
            $table->text('description');
            $table->string('address');
            $table->string('companyStartFrom');
            $table->boolean('userType');
            $table->boolean('premium')->default(0); // free 0, premium 1
            $table->boolean('cPanelInfo')->default(0); // didi't see the info 0, see the info 1
            $table->string('password');
            $table->boolean('verified')->default(0); // 0 == false
            $table->string('token')->nullable();
            $table->rememberToken();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
