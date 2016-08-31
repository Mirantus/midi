<?php
    /**
     * Response class
     *
     * @author Mikhail Miropolskiy <the-ms@ya.ru>
     * @package Core
     * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
     */
    namespace core;

    class Response {
        protected static $_instance;
        private $headers = [];
        private $content = '';

        private function __construct() {
        }

        /**
         * @return Response
         */
        public static function getInstance() {
            if (null === self::$_instance) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Add http header to response
         * @param $name
         * @param $value
         */
        public function addHeader($name, $value){
            $this->headers[$name] = $value;
        }

        /**
         * Output http response
         */
        public function send() {
            foreach ($this->headers as $name => $value) {
                header($name . ': ' . $value, false);
            }
            exit($this->content);
        }

        /**
         * Redirect to url
         * @param string $url
         */
        public function redirect($url) {
            $this->addHeader('Location', $url);
            $this->send();
        }
    }