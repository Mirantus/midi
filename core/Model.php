<?php
    /**
     * Model class
     *
     * @author Mikhail Miropolskiy <the-ms@ya.ru>
     * @package Core
     * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
     */
    namespace core;

    use \PDO;

    abstract class Model {
        /**
         * @var string
         */
        protected static $table;
        
        /**
         * @var string
         */
        protected static $primaryKey = 'id';
        
        /**
         * @var PDO
         */
        protected static $db;

        /**
         * Get all records
         * @param array $params
         * @return array
         */
        public static function getAll($params = []) {
            $params['query'] = 'SELECT * FROM ' . static::$table;
            $result = self::getDB()->query(self::buidQuery($params))->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }

        /**
         * Get one record by id
         * @param $id
         * @return array
         */
        public static function getById($id) {
            $params['query'] = 'SELECT * FROM ' . static::$table . ' WHERE id=' . $id;
            $result = self::getDB()->query(self::buidQuery($params))->fetch(PDO::FETCH_ASSOC);

            return $result;
        }

        /**
         * Count all records
         * @param array $params
         * @return int
         */
        public static function countAll($params = []) {
            $params['query'] = 'SELECT COUNT(*) FROM ' . static::$table;
            $result = self::getDB()->query(self::buidQuery($params))->fetchColumn();

            return $result;
        }

        protected static function getDB() {
            return DB::getInstance(App::getInstance()->dbConfig);
        }

        /**
         * @param $params
         * @return string
         */
        protected static function buidQuery($params) {
            $orderBy = isset($params['orderBy']) ? $params['orderBy'] : static::$primaryKey;
            $limit = isset($params['limit']) ? 'LIMIT ' . $params['limit'] : '';
            $query = $params['query'] . ' ORDER BY ' . $orderBy . ' ' . $limit . ';';

            return $query;
        }
    }