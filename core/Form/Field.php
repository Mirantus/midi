<?php
/**
 * Field class
 *
 * @author Mikhail Miropolskiy <the-ms@ya.ru>
 * @package Core
 * @subpackage Form
 * @copyright (c) 2012. Mikhail Miropolskiy. All Rights Reserved.
 */

namespace core\Form;

use core\App;

class Field {

	/**
	 * Field name like 'email'
	 * @var string
	 */
	public $name;

	/**
	 * Field value like 'e@mail.com'
	 * @var string
	 */
	public $value = '';

	/**
	 * Field title like 'address'
	 * @var string
	 */
	public $title = '';

	/**
	 * Field error like 'Invalid address' or false if no error
	 * @var mixed
	 */
	public $error = false;

	/**
	 * @param string $name Field name like 'email'
	 * @param array $options Array of field options (title, value)
	 */
	public function __construct($name, $options = array()) {
		$this->name = $name;
		if (isset($options['title'])) {
			$this->title = $options['title'];
		}
		if (isset($options['value'])) {
			$this->value = $options['value'];
		}
	}

	/**
	 * @return string Safe field value for display
	 */
	public function getDisplayValue() {
		return htmlspecialchars($this->value, ENT_QUOTES);
	}

	/**
	 * Fill field from request params
	 */
	public function fill() {
		$value = App::getInstance()->getParam($this->name, false);
		if (!is_null($value)) $this->value = $value;
	}

	/**
	 * Check if field value is email
	 * @return bool
	 */
	public function isEmail() {
		return preg_match('/^[-\w.]+@([a-zа-яё0-9][-A-zа-яё0-9]+\.)+[a-zа-яё]{2,4}$/ui', $this->value);
	}

	/**
	 * Check if field value is url
	 * @return bool
	 */
	public function isUrl() {
		return preg_match("~^(?:(?:https?|ftp|telnet)://(?:[a-zа-яё0-9_-]{1,32}(?::[a-zа-яё0-9_-]{1,32})?@)?)?(?:(?:[a-zа-яё0-9-]{1,128}\.)+(?:ru|su|com|net|org|mil|edu|arpa|gov|biz|info|aero|inc|name|[a-zа-яё]{2})|(?!0)(?:(?!0[^.]|255)[0-9]{1,3}\.){3}(?!0|255)[0-9]{1,3})(?:/[a-zа-яё0-9.,_@%&?+=\~/-]*)?(?:#[^ '\"&]*)?$~iu", $this->value);
	}

	/**
	 * Check if field value is icq
	 * @return bool
	 */
	public function isIcq() {
		 return preg_match('/([1-9])+(?:-?\d){4,}/', $this->value);
	}

	/**
	 * Check if field value is integer
	 * @return bool
	 */
	public function isInt() {
		 return filter_var($this->value, FILTER_VALIDATE_INT);
	}
}