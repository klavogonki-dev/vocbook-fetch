<?php
namespace vocbook;

class BookPart {
	protected $number = NULL;
	protected $text = NULL;

	public function __get (string $key) {
		switch ($key) {
			case 'text': return $this->text;
			case 'number': return $this->number;
			case 'length': return mb_strlen($this->text);
		}
	}

	public function __construct ($text, $number) {
		if (!is_int($number) || $number <= 0)
			throw new \Exception("number must be an positive integer, `"
				. gettype($number)."` given, value: {$number}");
		if (!is_string($text))
			throw new \Exception("text must be a string, `"
				. gettype($text) . "` given");

		$this->number = $number;
		$this->text = $text;
	}

}

