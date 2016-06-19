<?php
	/**
	 * Page class
	 *
	 * @author Mikhail Miropolskiy <the-ms@ya.ru>
	 * @package Core
	 * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
	 */
	namespace core;

	class Page {

        /**
         * Action name
         * @var string
         */
        public $action = 'index';

        /**
         * Page name
         * @var string
         */
        public $alias;

        /**
         * Is page require authorization
         * @var string
         */
        public $auth = false;

        /**
         * Controller name
         * @var string
         */
        public $controller = 'MainController';

        /**
         * Query params
         * @var array
         */
        public $params = [];

        /**
         * Route regexp
         * @var string
         */
        public $route;

        /**
         * Page title
         * @var string
         */
        public $title = '';

        public function __construct($config) {
            foreach ($config as $param => $value) {
                $this->{$param} = $value;
            }
        }

	}