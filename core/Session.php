<?php
    /**
     * Session class
     *
     * @author Mikhail Miropolskiy <the-ms@ya.ru>
     * @package Core
     * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
     */
    namespace core;

    class Session {
        /**
         * @var Session
         */
        private static $_instance;

        private function __construct() {
            if (!isset($_SESSION)) {
                session_start();
            }
        }

        /**
         * @return Session
         */
        public static function getInstance() {
            if (null === self::$_instance) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Return session value or null
         * @param string $key
         * @return mixed
         */
        public function get($key) {
            return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        }

        /**
         * Set session value
         * @param string $key
         * @param $value
         */
        public function set($key, $value) {
            $_SESSION[$key] = $value;
        }
    }