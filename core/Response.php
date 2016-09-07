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
        private $status = '200 OK';

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
         * @param string $name
         * @param string $value
         */
        public function addHeader($name, $value){
            $this->headers[$name] = $value;
        }

        /**
         * Add content to response
         * @param string $content
         */
        public function setContent($content){
            $this->content = $content;
        }

        /**
         * Set ajax response
         * @param mixed $content
         */
        public function setAjax($content) {
            $this->addHeader('Content-type', 'application/json');
            $this->setContent(json_encode($content));
        }

        /**
         * Set response status
         * @param string $status
         */
        public function setStatus($status) {
            $this->status = $status;
        }

        /**
         * Output http response
         */
        public function send() {
            foreach ($this->headers as $name => $value) {
                header($name . ': ' . $value);
            }
            header($_SERVER['SERVER_PROTOCOL'] . ' ' . $this->status);
            exit($this->content);
        }

        /**
         * Redirect to url
         * @param string $url
         */
        public function redirect($url) {
            $this->addHeader('Location', $url);
            $this->setStatus('301 Moved Permanently');
            $this->send();
        }
    }