<?php
namespace vocbook;

class Book extends BookFlag {
	protected $resource = [];
	protected $parts = [];

	public function __get ($key) {
		if (array_key_exists($key, $this->resource))
			return $this->resource[$key];
		else {
			switch ($key) {
				case 'parts': return $parts;
			}
		}

	}

	public function __construct ($resource) {
		$available_keys = ["driver", "id", "author", "title"];
		foreach ($available_keys as $key) {
			if (empty($resource[$key]))
				throw new \Exception("Not found `{$key}` key");
			$this->resource = $resource;
		}
	}

	public function getFilename () {
		return urlencode($this->author) . " - "
			. urlencode($this->title) . ".txt";
	}

	public function getBookLocalPath () {
		return realpath(Config::get()['data'])
			. DIRECTORY_SEPARATOR . $this->driver . DIRECTORY_SEPARATOR
			. $this->getFilename();
			
	}

	public function getPartsLocalPath () {
		return preg_replace("#.txt$#", ".parts.txt",
			$this->getBookLocalPath(), 1);
	}

	private $is_loaded = false;

	public function load ($force = false) {
		$file = $this->getBookLocalPath();

		if ($force && file_exists($file))
			if (!@unlink($file))
				trigger_error("can't force update book `{$file}`");

		if (!file_exists($file)) {
			if (!($driver = Driver::get($this->driver))) {
				trigger_error("driver `{$this->driver}` not found");
				return FALSE;
			}

			if (false === ($handle = @fopen($file, 'w')))
				return FALSE;

			(new $driver($this->id))->fetch($handle);
			fclose($handle);
		}
		
		return $this->is_loaded = TRUE;
	}

	
	private $last_added_part = 0;

	public function add_part ($text, $number) {
		if (is_int($number) && is_string($text)) {
			$part = new BookPart($text, $number);
		} else if (!($number instanceof BookPart)) {
			throw new \Exception("incorrect type argument(s)");
		} else {
			$part = $text;
		}

		if (++$this->last_added_part != $part->number) {
			trigger_error("expected part_number {$this->last_added_part}, given {$part->number}", E_USER_NOTICE);
			$this->last_added_part = $part->number;
		}

		$this->parts[] = $part;

		return $this;
	}

	public function save_parts () {
		usort($this->parts, function (BookPart $a, BookPart $b) {
			return $a->number - $b->number;
		});

		print_r($this->parts);

		return $this;
	}

	public function split (callable $parser) {
		$file = $this->getBookLocalPath();

		if (false === ($handle = @fopen($file, 'r')))
			throw new \Exception("can't read a book file `{$file}`");
		
		$flags = BookFlag::NONE;
		if (isset($this->type) && $this->type === 'private')
			$flags |= BookFlag::VOC_PRIVATE;

		$result = $parser($handle, [$this, 'add_part'], $flags);
		fclose($handle);

		$this->save_parts();

		return $this;
	}
}
