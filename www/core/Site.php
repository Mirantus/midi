<?php
/**
 * Site class
 *
 * @author Mikhail Miropolskiy <the-ms@ya.ru>
 * @package Core
 * @copyright (c) 2012. Mikhail Miropolskiy. All Rights Reserved.
 */
class Site {

	/**
	 * @var Site
	 */
	protected static $_instance;

	/**
	 * @var PDO
	 */
	public $db;

	/**
	 * Site version
	 * @var string
	 */
	public $version;

	/**
	 * Site root url
	 * @var string
	 */
	public $url;

	/**
	 * Site root path
	 * @var string
	 */
	public $path;

	/**
	 * @var string
	 */
	public $layoutPath;

	/**
	 * Path to partials for include
	 * @var string
	 */
	public $partialPath;

	/**
	 * Current page name
	 * @var string
	 */
	public $page = '';

	/**
	 * Current module name
	 * @var string
	 */
	public $module = '';

	/**
	 * Current module url
	 * @var string
	 */
	public $moduleUrl;

	/**
	 * Current module path
	 * @var string
	 */
	public $modulePath;

	/**
	 * Path to data directory of current module
	 * @var string
	 */
	public $moduleDataPath;

	/**
	 * Path to image directory of current module
	 * @var string
	 */
	public $moduleImagePath;

	/**
	 * Path to items directory of current module
	 * @var string
	 */
	public $moduleItemsPath;

	/**
	 * Current page url
	 * @var string
	 */
	public $pageUrl;

	/**
	 * Current page path
	 * @var string
	 */
	public $pagePath;

	/**
	 * Site config
	 * @var array
	 */
	public $config;

	/**
	 * Site title
	 * @var string
	 */
	public $title;

	/**
	 * Site owner email
	 * @var string
	 */
	public $owner;

	private function __construct()	{
		spl_autoload_register(create_function('$class', 'require str_replace("_", "/", $class) . ".php";'));

		$this->url = 'http://' . $_SERVER['HTTP_HOST'];
		$this->pageUrl = $this->url . $_SERVER['REQUEST_URI'];
		$this->path = $_SERVER['DOCUMENT_ROOT'];
		$this->layoutPath = $this->path . '/layouts';
		$this->partialPath = $this->path . '/partials';

		$this->config = parse_ini_file($this->path . '/config.ini', true);
		$this->version = $this->config['general']['version'];
		$this->title = $this->config['general']['title'];
		$this->owner = $this->config['general']['owner'];

		if (isset($this->config['db'])) {
			$db = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? $this->config['debug_db'] : $this->config['db'];
			
			$this->db = new PDO(
				"mysql:host=" . $db['host'] . ';dbname=' . $db['dbname'],
				$db['user'],
				$db['password'],
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
			);
			$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$this->db->setAttribute( PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true );
		}

		$this->setModule();
		$this->setPage();

		if (!isset($_SESSION)) session_start();
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') $_SESSION['current_user'] = array ('id' => -1, 'title' => 'Разработчик', 'access' => '5');
		if (!isset($_SESSION['current_user'])) $_SESSION['current_user'] = array ('id' => 0, 'title' => 'Гость', 'access' => '1');
	}

	/**
	 * @return Site
	 */
	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Set include paths
	 * @param array $paths
	 */
	public function registerPaths($paths) {
		foreach ($paths as $i => $path) $paths[$i] = $this->path . '/' . $path;
		set_include_path(implode(PATH_SEPARATOR, $paths));
	}

	/**
	 * Set current module
	 * @param string $module
	 */
	public function setModule($module = '') {
		$this->module = $module;

		$this->moduleUrl = 'http://' . $_SERVER['HTTP_HOST'];
		if ($this->module != '') {
			$this->moduleUrl .= '/' . $this->module;
		}
		$this->modulePath = $this->path . '/' . $this->module;
		$this->moduleDataPath = $this->modulePath . '/data';
		$this->moduleItemsPath = $this->moduleDataPath . '/items';
		$this->moduleImagePath = $this->moduleItemsPath . '/i';
		$this->pagePath = $this->modulePath . '/' . $this->page;
	}

	/**
	 * Set current page
	 * @param string $page
	 */
	public function setPage($page = '') {
		$this->page = $page;
		$this->pagePath = $this->modulePath . '/' . $this->page;
	}

	/**
	 * For pager: Get first item to show on current page
	 * @param int $itemsPerPage
	 * @return int Index of first item
	 */
	public function getPageItemsFirst($itemsPerPage) {
		$currentPage = $this->getParam('page');
		if ($currentPage == '') $currentPage = 1;
		return ($currentPage - 1) * $itemsPerPage;
	}

	/**
	 * Return pager
	 * @param int $itemsPerPage
	 * @param int $countItems Total items count
	 * @return string Html code of pager
	 */
	public function getPager($itemsPerPage, $countItems) {
		$countPages = ceil($countItems / $itemsPerPage);
		if ($countPages < 2) return '';
		$currentPage = $this->getParam('page');
		if ($currentPage == '') $currentPage = 1;

		$pager = '<ul class="pager">';
		for ($i = 1; $i <= $countPages; $i++ ) {
			if ($i == $currentPage)
				$pager .= '<li class="active">' . $i . '</li> ';
			else
				$pager .= '<li><a href="' . $this->addUrlParam($this->pageUrl, 'page', $i) . '">' . $i . '</a></li> ';
		}
		$pager .= '</ul>';

		return $pager;
	}

	/**
	 * Get param from request
	 * @param string $param Name of param
	 * @param bool $safe Convert to text or not
	 * @return string|null Param value
	 */
	public function getParam($param, $safe = true) {
		if (isset($_POST[$param])) {
			$query = $_POST[$param];
		} elseif (isset($_GET[$param])) {
			$query = $_GET[$param];
		} else {
			return null;
		}

		$values = (is_array($query)) ? $query : array($query);

		foreach ($values as $key => $value) {
			$value = trim($value);

			if (get_magic_quotes_gpc()) {
				$value = stripslashes($value);
			}

			if (!empty($safe)) $value = htmlspecialchars(strip_tags($value));

			$values[$key] = $value;
		}

		return (count($values) > 1 || !isset($values[0])) ? $values : $values[0];
	}

	/**
	 * Get integer param from request
	 * @param string $param Name of param
	 * @return int|null Param value
	 */
	public function getParamInt($param) {
		$values = $this->getParam($param);
		if ($values === null) return null;

		$values = (is_array($values)) ? $values : array($values);

		foreach ($values as $key => $value) {
			$value = intval($value);
			$values[$key] = $value;
		}

		return (count($values) > 1) ? $values : $values[0];
	}

	/**
	 * Add param to url
	 * @param string $url
	 * @param string $param Param name
	 * @param string $value Param value
	 * @return string New url
	 */
	public function addUrlParam($url, $param, $value)	{
		if (strpos($url, '?')) {
			list($path, $params) = explode('?', $url);
			parse_str($params, $params);
			$params[$param] = $value;
			return $path . '?' . http_build_query($params);
		}

		return $url . '?' . $param . '=' . $value;
	}

    /**
     * Send simple mail
     * @param string $email
     * @param string $title
     * @param string $message
     * @return bool Result of sending mail
     */
    public function mail($email, $title, $message) {
        //$title = iconv("UTF-8", "koi8-r//IGNORE", $title);
        //$message = iconv("UTF-8", "koi8-r//IGNORE", $message);

        return mail($email, $title, $message, 'From: mailer@' . $_SERVER['SERVER_NAME'] . "\r\n" . 'Content-type: text/html; charset=utf-8' . "\r\n" . 'X-Mailer: PHP/' . phpversion());
    }

	/**
	 * @param string $url
	 */
	public function redirect($url) {
		header('Location: ' . $url);
		exit;
	}

	public function back() {
		$url = empty($_SERVER['HTTP_REFERER']) ? $this->url : $_SERVER['HTTP_REFERER'];
		$this->redirect($url);
	}

	/**
	 * Send ajax response
	 * @param mixed $data
	 */
	public function ajaxResponse($data) {
		header('Content-type: application/json');
		exit(json_encode($data));
	}

	/**
	 * Create url by module and page name
	 * @param string $module
	 * @param string $page
	 * @return string Url
	 */
	public function createUrl($module, $page) {
		$modulePart = ($module == '') ? '' : '/' . $module;
		$pagePart = ($page == 'index' || $page == '') ? '' : '/' . $page;
		return $this->url . $modulePart . $pagePart;
	}

	/**
	 * Return user rights level for access
	 * @return int 0 - disabled/1 - everyone/2 - registered/3 - author/4 - admin/5 - god
	 */
	public function getUserAccess() {
		return $_SESSION['current_user']['access'];
	}

	/**
	 * Is file posted?
	 * @param string $name File field name
	 * @return bool
	 */
	public function isFileUploaded($name) {
		return isset($_FILES[$name]) && $_FILES[$name]['name'] != '' && $_FILES[$name]['size'] > 0;
	}

	/**
	 * Return error of uploading file
	 * @param string $name
	 * @return string|bool
	 */
	public function getFileUploadError($name) {
		if ($_FILES[$name]['error'] > 0) return 'Ошибка загрузки файла';
		if ($_FILES[$name]['size'] > 1048576) return 'Максимальный размер файла 1Мб';
		return false;
	}

	/**
	 * Check if it is ajax request
	 * @return bool
	 */
	public function isAjaxRequest() {
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
}