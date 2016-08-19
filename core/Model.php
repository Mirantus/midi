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
         * @var array
         */
        protected static $fields = ['id'];
        
        /**
         * @var string
         */
        protected static $primaryKey = 'id';

        /**
         * Delete
         * @param string $where
         */
        public static function delete($where) {
            $query = 'DELETE FROM ' . static::$table . ' WHERE ' . $where;
            static::getDB()->prepare($query)->execute();
        }

        /**
         * Get all records
         * @param array $params
         * @return array
         */
        public static function getAll($params = []) {
            $params['query'] = 'SELECT * FROM ' . static::$table;
            $result = static::getDB()->query(static::buidQuery($params))->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }

        /**
         * Get one record by id
         * @param $id
         * @return array
         */
        public static function getById($id) {
            $params['query'] = 'SELECT * FROM ' . static::$table . ' WHERE id=' . $id;
            $result = static::getDB()->query(static::buidQuery($params))->fetch(PDO::FETCH_ASSOC);

            return $result;
        }

        /**
         * Count all records
         * @param array $params
         * @return int
         */
        public static function countAll($params = []) {
            $params['query'] = 'SELECT COUNT(*) FROM ' . static::$table;
            $result = static::getDB()->query(static::buidQuery($params))->fetchColumn();

            return $result;
        }

//        /**
//         * Get fields list
//         * @return array
//         */
//        public static function getFields() {
//            return static::$fields;
//        }

        /**
         * Insert
         * @param array $data
         * @return int|bool Last insert id or false
         */
        public static function insert($data) {
            $fields = $placeholders = $values = [];

            foreach ($data as $field => $value) {
                if (!in_array($field, static::$fields)) {
                    continue;
                }
                $fields[] = $field;
                $placeholders[] = ':' . $field;
                $values[$field] = $value;
            }

            $query_fields = implode(',', $fields);
            $query_placeholders = implode(',', $placeholders);
            $query = 'INSERT INTO ' . static::$table . ' (' . $query_fields . ') VALUES (' . $query_placeholders . ')';

            $st = static::getDB()->prepare($query);
            $result = $st->execute($values);

            if ($result) {
                return static::getDB()->lastInsertId();
            }

            return false;
        }

        /**
         * Insert
         * @param array $data
         * @param string $where
         * @return int|bool Last insert id or false
         */
        public static function update($data, $where = '') {
            $fields = $values = [];

            foreach ($data as $field => $value) {
                if (!in_array($field, static::$fields)) {
                    continue;
                }
                $fields[] = '`' . $field . '`=:' . $field;
                $values[$field] = $value;
            }

            $query_fields = implode(',', $fields);
            $query_where = empty($where) ? '' : ' WHERE ' . $where;
            $query = 'UPDATE ' . static::$table . ' SET ' . $query_fields . $query_where;

            $st = static::getDB()->prepare($query);
            $st->execute($values);
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

        /**
         * @return PDO
         */
        protected static function getDB() {
            return DB::getInstance(App::getInstance()->dbConfig);
        }
    }