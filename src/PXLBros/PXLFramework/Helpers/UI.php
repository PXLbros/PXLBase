<?php namespace PXLBros\PXLFramework\Helpers;

class UI
{
	const NOTIFICATION_SESSION_NAME = 'pxl_notification';

	const NOTIFICATION_TYPE_DEFAULT = 1;
	const NOTIFICATION_TYPE_SUCCESS = 2;
	const NOTIFICATION_TYPE_INFO = 3;
	const NOTIFICATION_TYPE_WARNING = 4;
	const NOTIFICATION_TYPE_ERROR = 5;

	private $notification =
	[
		'pre_loaded' => NULL,
		'user_loaded' => NULL
	];

	public function setNotification($text, $type = self::NOTIFICATION_TYPE_DEFAULT)
	{
		$this->notification['user_loaded'] =
		[
			'text' => $text,
			'type' => $type
		];

		$this->saveNotification();
	}

	public function showNotification($text)
	{
		$this->setNotification($text, self::NOTIFICATION_TYPE_DEFAULT);
	}

	public function showSuccess($text)
	{
		$this->setNotification($text, self::NOTIFICATION_TYPE_SUCCESS);
	}

	public function showInfo($text)
	{
		$this->setNotification($text, self::NOTIFICATION_TYPE_INFO);
	}

	public function showWarning($text)
	{
		$this->setNotification($text, self::NOTIFICATION_TYPE_WARNING);
	}

	public function showError($text)
	{
		$this->setNotification($text, self::NOTIFICATION_TYPE_ERROR);
	}

	public function deleteNotification()
	{
		\Session::forget(self::NOTIFICATION_SESSION_NAME);
	}

	private function saveNotification()
	{
		\Session::set(self::NOTIFICATION_SESSION_NAME, $this->notification['user_loaded']);
	}

	public function getNotification()
	{
		return \Session::get(self::NOTIFICATION_SESSION_NAME);
	}

	public function haveNotification()
	{
		return \Session::has(self::NOTIFICATION_SESSION_NAME);
	}

	public function output()
	{
		$this->deleteSession();

		return json_encode($this->notification['pre_loaded']);
	}
}