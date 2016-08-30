<?php
    /**
     * Request class
     *
     * @author Mikhail Miropolskiy <the-ms@ya.ru>
     * @package Core
     * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
     */
    namespace core;

    class Request {
        /**
         * Returns request method
         * @return string
         */
        public static function getMethod() {
            return $_SERVER['REQUEST_METHOD'];
        }

        /**
         * Return true if request method is Post
         * @return bool
         */
        public static function isPost() {
            return static::getMethod() == 'POST';
        }

        /**
         * Get param from request
         * @param string $param Name of param
         * @param bool $safe Convert to text or not
         * @param mixed $fallback Fallback value
         * @return string|null Param value
         */
        public static function getParam($param, $safe = true, $fallback = null) {
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
        public static function getParamInt($param) {
            $values = static::getParam($param);
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
         * Get url path
         * @return string Url path
         */
        public static function getPath() {
            $request = parse_url($_SERVER['REQUEST_URI']);
            return $request['path'];
        }

        /**
         * Check if it is ajax request
         * @return bool
         */
        public static function isAjax() {
            return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        }
    }