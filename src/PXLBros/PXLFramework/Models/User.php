<?php namespace PXLBros\PXLFramework\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
	use Authenticatable;

	protected $table = 'users';

	protected $fillable = ['email', 'first_name', 'last_name', 'password'];

	public static function register($email, $first_name, $last_name, $password = null)
	{
		if ( $password === null )
		{
			$password = str_random(8);
		}

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