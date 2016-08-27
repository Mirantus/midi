<?php
	/**
	 * App class
	 *
	 * @author Mikhail Miropolskiy <the-ms@ya.ru>
	 * @package Core
	 * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
	 */
	namespace core;

	class App {
		/**
		 * Auth credentials
		 * @var array
		 */
		public $auth;

		/**
		 * Database config
		 * @var array
		 */
		public $dbConfig;

		/**
		 * Current page
		 * @var \core\Page
		 */
		public $page;

		/**
		 * Pages config
		 * @var array
		 */
		public $pages;

		/**
		 * Current page url
		 * @var string
		 */
		public $pageUrl;

		/**
		 * View partials path
		 * @var string
		 */
		public $partialPath;

		/**
		 * Site root path
		 * @var string
		 */
		public $path;

		/**
		 * Site title
		 * @var string
		 */
		public $title;

		/**
		 * Site root url
		 * @var string
		 */
		public $url;

		/**
		 * Site version
		 * @var string
		 */
		public $version;

		/**
		 * Path to webroot
		 * @var string
		 */
		public $webrootPath;

		/**
		 * @var App
		 */
		protected static $_instance;

		//	/**
		//	 * @var string
		//	 */
		//	public $layoutPath;
		//
		//	/**
		//	 * Path to partials for include
		//	 * @var string
		//	 */
		//	public $partialPath;

		//	/**
		//	 * Current module url
		//	 * @var string
		//	 */
		//	public $moduleUrl;
		//
		//	/**
		//	 * Current module path
		//	 * @var string
		//	 */
		//	public $modulePath;
		//
		//	/**
		//	 * Path to data directory of current module
		//	 * @var string
		//	 */
		//	public $moduleDataPath;
		//
		//	/**
		//	 * Path to image directory of current module
		//	 * @var string
		//	 */
		//	public $moduleImagePath;
		//
		//	/**
		//	 * Path to items directory of current module
		//	 * @var string
		//	 */
		//	public $moduleItemsPath;

		//	/**
		//	 * Current page path
		//	 * @var string
		//	 */
		//	public $pagePath;

		//	/**
		//	 * Site owner email
		//	 * @var string
		//	 */
		//	public $owner;
		//
		//	/**
		//	 * Site debug mode
		//	 * @var string
		//	 */
		//	public $debug;
		//

		private function __construct() {
		}

		/**
		 * @return App
		 */
		public static function getInstance() {
			if (null === self::$_instance) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		private function setAutoload() {
			spl_autoload_register(function ($class) {
				require str_replace('\\', '/', $class) . '.php';
			});
			$paths = ['', 'lib'];
			foreach ($paths as $i => $path) {
				$paths[$i] = $this->path . '/' . $path;
			}
			set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $paths));
		}

		private function setConfig() {
			require '../app/config/index.php';
			/** @var array $config */

			// TODO: NEW USERS replace to Users
			$this->auth = $config['auth'];

			$this->title = $config['app']['title'];
			date_default_timezone_set($config['app']['timezone']);
			$this->version = $config['app']['version'];
			$this->pages = $config['pages'];
			$this->dbConfig = $config['database'];
		}

		private function setPage() {
			$request = parse_url($_SERVER['REQUEST_URI']);

			$pageConfig = $this->pages['404'];
			foreach ($this->pages as $alias => $page) {
				$regexp = '/^' . str_replace('/', '\/', $page['route']) . '$/i';
				if (preg_match($regexp, $request['path'], $matches)) {
					if (!empty($page['params'])) {
						for ($i = 0; $i < count($page['params']); $i++) {
							$page['params'][$page['params'][$i]] = $matches[$i + 1];
							unset($page['params'][$i]);
						}
					}
					$pageConfig = $page;
					$pageConfig['alias'] = $alias;
					break;
				}
			}

			$this->page = new Page($pageConfig);
		}

		private function setPaths() {
			// TODO: create class Request
			$this->url = 'http://' . $_SERVER['HTTP_HOST'];
			$this->pageUrl = $this->url . $_SERVER['REQUEST_URI'];
			$this->webrootPath = $_SERVER['DOCUMENT_ROOT'];
			$this->path = dirname($this->webrootPath);
			$this->partialPath = $this->path . '/app/View/Partials';
			//		$this->layoutPath = $this->path . '/layouts';
			//		$this->partialPath = $this->path . '/partials';
		}

		public function run() {
			$this->setConfig();
			$this->setPaths();
			$this->setAutoload();
			$this->setPage();

			if (!isset($_SESSION)) {
				session_start();
			}
			if (!isset($_SESSION['auth'])) {
				$_SESSION['auth'] = [
                    'id' => 0,
                    'login' => ''
                ];
			}

			$controllerClass = '\app\Controller\\' . $this->page->controller;
			$controller = new $controllerClass($this);

			if ($this->page->auth && !$this->isMember()) {
				$this->redirect('/login/?return=/admin/');
			}

			call_user_func_array([$controller, $this->page->action], $this->page->params);
		}

		/**
		 * Get param from request
		 * @param string $param Name of param
		 * @param bool $safe Convert to text or not
		 * @param mixed $fallback Fallback value
		 * @return string|null Param value
		 */
		public function getParam($param, $safe = true, $fallback = null) {
			if (isset($_POST[$param])) {
				$query = $_POST[$param];
			} elseif (isset($_GET[$param])) {
				$query = $_GET[$param];
			} else {
				return $fallback;
			}

			$values = (is_array($query)) ? $query : [$query];

			foreach ($values as $key => $value) {
				$value = trim($value);

				if (get_magic_quotes_gpc()) {
					$value = stripslashes($value);
				}

				if (!empty($safe)) {
					$value = htmlspecialchars(strip_tags($value));
				}

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
			if ($values === null) {
				return null;
			}

			$values = (is_array($values)) ? $values : [$values];

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
		public function addUrlParam($url, $param, $value) {
			// TODO: create class lib/Url
			if (strpos($url, '?')) {
				list($path, $params) = explode('?', $url);
				parse_str($params, $params);
				$params[$param] = $value;
				return $path . '?' . http_build_query($params);
			}

			return $url . '?' . $param . '=' . $value;
		}

		//    /**
		//     * Send simple mail
		//     * @param string $email
		//     * @param string $title
		//     * @param string $message
		//     * @return bool Result of sending mail
		//     */
		//    public function mail($email, $title, $message) {
		//        //$title = iconv("UTF-8", "koi8-r//IGNORE", $title);
		//        //$message = iconv("UTF-8", "koi8-r//IGNORE", $message);
		//
		//        return mail($email, $title, $message, 'From: mailer@' . $_SERVER['SERVER_NAME'] . "\r\n" . 'Content-type: text/plain; charset=utf-8' . "\r\n" . 'X-Mailer: PHP/' . phpversion());
		//    }

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
		 * Create url by page alias and query
		 * @param string $alias
		 * @param array $query
		 * @return string Url
		 */
		public function createUrl($alias, $query = []) {
			// TODO: ROUTES Add support of routes with params
			if (!isset($this->pages[$alias])) {
				return $this->url;
			}
			$routePart = $this->pages[$alias]['route'];
			$queryPart = empty($query) ? '' : '?' . http_build_query($query);
			return $this->url . $routePart . $queryPart;
		}

		/**
		 * Create HTML link
		 * @param string $url
		 * @param string $text
		 * @param bool $isActive Create link or text (if active)
		 * @return string
		 */
		public function createLink($url, $text, $isActive = false) {
			return $isActive ? $text : '<a href="' . $url . '">' . $text . '</a>';
		}

		//	/**
		//	 * Return user rights level for access
		//	 * @return int 0 - disabled/1 - everyone/2 - registered/3 - author/4 - admin/5 - god
		//	 */
		//	public function getUserAccess() {
		//		return $_SESSION['current_user']['access'];
		//	}

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
			if ($_FILES[$name]['error'] > 0) {
				return 'Ошибка загрузки файла';
			}
			if ($_FILES[$name]['size'] > 1048576) {
				return 'Максимальный размер файла 1Мб';
			}
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

        /**
         * Check if curent user is not guest
         * @return bool
         */
        public function isMember() {
            return !empty($_SESSION['auth']['id']);
        }
	}