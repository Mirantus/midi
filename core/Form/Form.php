<?php
/**
 * Form class
 *
 * @author Mikhail Miropolskiy <the-ms@ya.ru>
 * @package Core
 * @subpackage Form
 * @copyright (c) 2012. Mikhail Miropolskiy. All Rights Reserved.
 */

namespace core\Form;

class Form {

	/**
	 * Array of form fields
	 * @var Field[]
	 */
	public $fields = array();

	/**
	 * Form error
	 * @var mixed
	 */
	public $error = '';

	/**
	 * Array of predefined error messages
	 * @var array
	 */
	public $errors = array(
        'wrong' => 'Поле заполнено неверно',
		'email' => 'Email введен неверно',
		'url' => 'Адрес сайта введен неверно',
		'icq' => 'Номер ICQ введен неверно',
		'int' => 'Введите только цифры',
		'cat' => 'Рубрика не найдена',
	);

	/**
	 * Add field to form
	 * @param string $name Field name
	 * @param Field $field
	 */
	public function __set($name, $field) {
		$this->fields[$name] = $field;
	}

	/**
	 * Get form field
	 * @param string $name Field name
	 * @return mixed Field or null
	 */
	public function __get($name) {
		if (array_key_exists($name, $this->fields)) {
			return $this->fields[$name];
		}
		return null;
}

	/**
	 * Create new field in form by params
	 * @param string $name Field name
	 * @param array $options Field options
	 * @return Form
	 */
	public function add($name, $options = array()) {
		$this->fields[$name] = new Field($name, $options);
		return $this;
	}

	/**
	 * Fill form from request params
	 */
	public function fill() {
		foreach($this->fields as $field) {
			$field->fill();
		}
	}

	/**
	 * Check if form is valid
	 * @return bool
	 */
	public function isValid() {
		if (!isset($_REQUEST['key']) || $_REQUEST['key'] != '') return false;

		foreach($this->fields as $field) {
			if (!empty($field->error)) {
			    return false;
            }
		}

		return empty($this->error);
	}

	/**
	 * Unserialize form fields to array
	 * @return array like array($name->$value)
	 */
	public function toArray() {
		$fields = array();
		foreach($this->fields as $name => $field) {
			$fields[$name] = $field->value;
		}
		return $fields;
	}

	/**
	 * Unserialize form fields to array
	 * @return array like array('field'->'error text')
	 */
	public function getErrors() {
		$errors = [];

		foreach($this->fields as $name => $field) {
			if (!empty($field->error)) {
			    $errors[$name] = $field->error;
            }
		}

		if (!empty($this->error)) {
            $errors['submit'] = $this->error;
        }

		return $errors;
	}
}