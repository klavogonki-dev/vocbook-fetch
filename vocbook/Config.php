<?php
namespace vocbook;

class Config {
	protected static $cfg = null;

	protected static function default_cfg () {
		return [
			"data" => realpath(__DIR__ . "/../data")
		];
	}

	public static function set ($user_cfg) {
		static::check($user_cfg);
		static::$cfg = array_merge(static::get(), $user_cfg);
	}

	private static function check ($cfg) {
		$check_list = ['data'];

		foreach ($check_list as $key) {
			if (isset($cfg[$key]) && $key === 'data' && !is_dir($cfg[$key])) {
				throw new \Exception("books directory is not exists: `{$cfg[$key]}`");
			}
		}
	}

	public static function get () {
		if (!static::$cfg) {
			static::$cfg = static::default_cfg();
		}

		return static::$cfg;
	}
}
