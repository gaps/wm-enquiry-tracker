<?php

use Illuminate\Database\Migrations\Migration;

class Init extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//create table users
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password', 64);
            $table->timestamps();
        });

        //create table roles
        Schema::create('roles', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        //create table branches
        Schema::create('branches', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        //create table enquiries
        Schema::create('enquiries', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('mobile', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->date('enquiryDate');
            $table->string('program');
            $table->string('type');
            $table->integer('user_id')->unsigned();
            $table->integer('branch_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->timestamps();
        });

        //create table enquiry status
        Schema::create('enquiryStatus', function ($table) {
            $table->increments('id');
            $table->date('followupDate')->nullable();
            $table->date('joiningDate')->nullable();
            $table->string('status');
            $table->string('remarks', 4000)->nullable();
            $table->integer('enquiry_id')->unsigned();
            $table->foreign('enquiry_id')->references('id')->on('enquiries');
            $table->timestamps();
        });

        Schema::create('user_role', function ($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('branch_user', function ($table) {
            $table->increments('id');
            $table->integer('branch_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('branch_id')->references('id')->on('branches');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('enquiries', function ($table) {
            $table->dropForeign('enquiries_user_id_foreign');
            $table->dropForeign('enquiries_branch_id_foreign');
        });

        Schema::table('user_role', function ($table) {
            $table->dropForeign('user_role_user_id_foreign');
            $table->dropForeign('user_role_role_id_foreign');
        });

        Schema::table('branch_user', function ($table) {
            $table->dropForeign('branch_user_user_id_foreign');
            $table->dropForeign('branch_user_branch_id_foreign');
        });

        Schema::table('enquiryStatus', function ($table) {
            $table->dropForeign('enquiryStatus_enquiry_id_foreign');
        });

        //drop users
        Schema::drop('users');
        Schema::drop('branches');
        Schema::drop('roles');
        Schema::drop('enquiries');
        Schema::drop('enquiryStatus');
        Schema::drop('user_role');
        Schema::drop('branch_user');
	}

}