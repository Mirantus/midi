<?php
    /**
     * App class
     *
     * @author Mikhail Miropolskiy <the-ms@ya.ru>
     * @package Core
     * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
     */
    namespace core;

    use core\Auth;

    class App {
        /**
         * Database config
         * @var array
         */
        public $dbConfig;

        /**
         * Site owner email
         * @var string
         */
        public $owner;

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
            $this->title = $config['app']['title'];
            date_default_timezone_set($config['app']['timezone']);
            $this->version = $config['app']['version'];
            $this->pages = $config['pages'];
            $this->dbConfig = $config['database'];
            $this->owner = $config['app']['owner'];
        }

        private function setPage() {
            $pageConfig = $this->pages['404'];
            $pageConfig['alias'] = '404';

            foreach ($this->pages as $alias => $page) {
                $regexp = '/^' . str_replace('/', '\/', $page['route']) . '$/i';
                if (preg_match($regexp, Request::getPath(), $matches)) {
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

            if ($pageConfig['alias'] == '404') {
                Response::getInstance()->setStatus('404 Not Found');
            }

            $this->page = new Page($pageConfig);
        }

        private function setPaths() {
            $this->url = 'http://' . $_SERVER['HTTP_HOST'];
            $this->pageUrl = $this->url . $_SERVER['REQUEST_URI'];
            $this->webrootPath = $_SERVER['DOCUMENT_ROOT'];
            $this->path = dirname($this->webrootPath);
        }

        public function run() {
            $this->setConfig();
            $this->setPaths();
            $this->setAutoload();
            $this->setPage();
            $this->checkAccess();

            $controllerClass = '\app\Controller\\' . $this->page->controller;
            $controller = new $controllerClass($this);

            call_user_func_array([$controller, $this->page->action], $this->page->params);

            Response::getInstance()->send();
        }

        /**
         * Send simple mail
         * @param string $email
         * @param string $title
         * @param string $message
         * @param bool $is_html
         * @return bool Result of sending mail
         */
        public function mail($email, $title, $message, $is_html = false) {
//          $title = iconv("UTF-8", "koi8-r//IGNORE", $title);
//          $message = iconv("UTF-8", "koi8-r//IGNORE", $message);
            $contentType = $is_html ? 'text/html' : 'text/plain';

            return mail($email, $title, $message, 'From: mailer@' . $_SERVER['SERVER_NAME'] . "\r\n" . 'Content-type: ' . $contentType . '; charset=utf-8' . "\r\n" . 'X-Mailer: PHP/' . phpversion());
        }

        /**
         * Create url by page alias and query
         * @param string $alias
         * @param array $params Values for page route params
         * @param array $query
         * @return string Url
         */
        public function createUrl($alias, $params = [], $query = []) {
            if (!isset($this->pages[$alias])) {
                return $this->url;
            }

            $routePart = preg_replace_callback('/\([^\)]*\)/', function () use (&$params) {
                return array_shift($params);
            }, $this->pages[$alias]['route']);

            $queryPart = empty($query) ? '' : '?' . http_build_query($query);
            return $this->url . $routePart . $queryPart;
        }

        private function checkAccess() {
            $auth = Auth::getInstance();
            $response = Response::getInstance();

            if ($this->page->auth == 'admin' && !$auth->isAdmin()) {
                $response->redirect('/login/?return=/admin/');
            }

            if ($this->page->auth == 'user' && !$auth->isAuth()) {
                $response->redirect('/login/?return=' . $_SERVER['REQUEST_URI']);
            }
        }
    }