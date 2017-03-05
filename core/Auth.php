<?php
    /**
     * Auth class
     *
     * @author Mikhail Miropolskiy <the-ms@ya.ru>
     * @package Core
     * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
     */
    namespace core;

    use core\Session;

    class Auth {
        /**
         * @var Auth
         */
        private static $_instance;

        /**
         * @var Session
         */
        private $session;

        /**
         * @var array
         */
        private $initialData = [
            'id' => 0,
            'email' => '',
            'role' => 'guest',
            'name' => ''
        ];

        private function __construct() {
            $this->session = Session::getInstance();
            if (!$this->session->get('auth')) {
                $this->session->set('auth', $this->initialData);
            }
        }

        /**
         * @return Auth
         */
        public static function getInstance() {
            if (null === self::$_instance) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Return Auth parameter or null
         * @param string $key
         * @return string
         */
        public function get($key) {
            $authData = $this->session->get('auth');
            return isset($authData[$key]) ? $authData[$key] : null;
        }

        /**
         * Set Auth parameters
         * @param array $data
         */
        public function set($data) {
            $authData = [];
            foreach ($data as $key => $value) {
                if (array_key_exists($key, $this->initialData)) {
                    $authData[$key] = $value;
                }
            }
            $this->session->set('auth', $authData);
        }

        /**
         * Reset Auth to guest
         */
        public function reset() {
            $this->set($this->initialData);
        }

        /**
         * Check if current user is admin
         * @return bool
         */
        public function isAdmin() {
            return $this->get('role') == 'admin';
        }

        /**
         * Check if current user is authorized
         * @return bool
         */
        public function isAuth() {
            return $this->isAdmin() || $this->isUser();
        }

        /**
         * Check if user logged
         * @return bool
         */
        public function isUser() {
            return $this->get('role') == 'user';
        }
    }