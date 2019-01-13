<?php
namespace vocbook\drivers;

use vocbook\Driver;

class local extends Driver {
	public function fetch ($sw) {
		if ($sr = fopen($this->getURI(), 'r')) {
			while (($buffer = fgets($sr)) !== false)
				fwrite($sw, $buffer);
			fclose($sr);
		}
	}

	protected function getURI () {
		return realpath($this->id);
	}
}
