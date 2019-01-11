<?php
namespace vocbook;

class Driver {
	public static function get (/* string */ $key) {
		$driver = __NAMESPACE__ . "\\drivers\\" . $key;

		if (class_exists($driver)) {
			$dir = Config::get()['data'] . DIRECTORY_SEPARATOR . $key;

			if (file_exists($dir) && !is_dir($dir))
				if (!@unlink($dir))
					throw new Exception("can't remove file `{$dir}`");
			if (!file_exists($dir))
				if (!mkdir($dir, 0755))
					throw new Exception("can't create directory `{$dir}`");

			return $driver;
		} else {
			return FALSE;
		}
	}

	protected $id;

	public function __construct (/* string */ $id) {
		$this->id = $id;
	}
}
