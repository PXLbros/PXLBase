<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $table = 'users';

	public static function register($email, $first_name, $last_name, $password = null)
	{
		$new_user = User::create(
		[
			'email' => $email,
			'password' => bcrypt($password),
			'first_name' => ($first_name),
			'last_name' => trim($last_name)
		]);

		return $new_user;
	}

	public function getName()
	{
		return $this->first_name . ' ' . $this->last_name;
	}
}