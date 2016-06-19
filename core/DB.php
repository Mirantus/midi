<?php
    /**
     * Database class
     *
     * @author Mikhail Miropolskiy <the-ms@ya.ru>
     * @package Core
     * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
     */
    namespace core;

    use \PDO;

    class DB {

        /**
         * @param array $config
         * @var DB
         */
        protected static $_instance;

        private function __construct($config) {
            self::$_instance = new PDO(
                "mysql:host=" . $config['host'] . ';dbname=' . $config['dbname'],
                $config['user'],
                $config['password'],
                [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
            );
            self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$_instance->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }

        /**
         * @param array $config
         * @return PDO
         */
        public static function getInstance($config) {
            if (null === self::$_instance) {
                new self($config);
            }
            return self::$_instance;
        }
    }