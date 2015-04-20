<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('first_name', 40);
			$table->string('last_name', 40);
			$table->char('password', 60);
			$table->rememberToken();
			$table->softDeletes();
			$table->nullableTimestamps();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}