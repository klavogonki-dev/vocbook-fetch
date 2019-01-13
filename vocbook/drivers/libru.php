<?php
namespace vocbook\drivers;

use vocbook\Driver;

class libru extends Driver {
	public function fetch ($sw) {
		$opts = [
			'http' => [
				'method' => "GET",
				'header' => [ ]
			]
		];

		$context = stream_context_create($opts);

		if ($sr = fopen($this->getURI(), 'r', false, $context)) {
			while (($buffer = fgets($sr)) !== false) {
				// $buffer = strip_tags($buffer);
				fwrite($sw, $buffer);
			}
			fclose($sr);
		}
	}

	protected function getURI () {
		return "http://lib.ru/{$this->id}_Ascii.txt";
	}
}
