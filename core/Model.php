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
        public static $primaryKey = 'id';

        /**
         * Query
         * @param array $params
         * @param array $values
         * @return boolean
         */
        public static function query($params = [], $values = []) {
            $query = static::buidQuery($params);
            $st = static::getDB()->prepare($query);
            $result = $st->execute($values);

            return $result;
        }

        /**
         * Find records
         * @param array $params
         * @param array $values
         * @return array
         */
        public static function find($params = [], $values = []) {
            if (empty($params['query'])) {
                $params['query'] = 'SELECT * FROM ' . static::$table;
            }

            $query = static::buidQuery($params);
            $st = static::getDB()->prepare($query);
            $st->execute($values);
            $result = $st->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

        /**
         * Get one record by primary key
         * @param $pk
         * @return array
         */
        public static function findByPK($pk) {
            $params = [
                'where' => static::$primaryKey . ' = :' . static::$primaryKey,
                'limit' => 1
            ];
            $values = [static::$primaryKey => $pk];
            $result = static::find($params, $values);

            if (!empty($result)) {
                return $result[0];
            }

            return $result;
        }

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
         * Update
         * @param array $data
         * @param array $params
         * @return boolean
         */
        public static function update($data, $params = []) {
            $fields = $values = [];

            foreach ($data as $field => $value) {
                if (!in_array($field, static::$fields)) {
                    continue;
                }
                $fields[] = '`' . $field . '`=:' . $field;
                $values[$field] = $value;
            }

            $query_fields = implode(',', $fields);

            if (empty($params['query'])) {
                $params['query'] = 'UPDATE ' . static::$table . ' SET ' . $query_fields;
            }

            $result = static::query($params, $values);

            return $result;
        }

        /**
         * Update by primary key
         * @param integer $pk
         * @param array $data
         * @param array $params
         * @return boolean
         */
        public static function updateByPK($pk, $data, $params = []) {
            if (empty($params['where'])) {
                $params['where'] = static::$primaryKey . ' = :' . static::$primaryKey;
            }

            $data[static::$primaryKey] = $pk;
            $result = static::update($data, $params);

            return $result;
        }

        /**
         * Delete
         * @param array $params
         * @param array $values
         * @return boolean
         */
        public static function delete($params = [], $values = []) {
            if (empty($params['query'])) {
                $params['query'] = 'DELETE FROM ' . static::$table;
            }
            $result = static::query($params, $values);

            return $result;
        }

        /**
         * Delete by primary key
         * @param integer $pk
         * @param array $params
         * @param array $values
         * @return boolean
         */
        public static function deleteByPK($pk, $params = [], $values = []) {
            if (empty($params['where'])) {
                $params['where'] = static::$primaryKey . ' = :' . static::$primaryKey;
            }

            $values[static::$primaryKey] = $pk;
            $result = static::delete($params, $values);

            return $result;
        }

        /**
         * Count all records
         * @param array $params
         * @param array $values
         * @return int
         */
        public static function count($params = [], $values = []) {
            if (empty($params['query'])) {
                $params['query'] = 'SELECT COUNT(*) FROM ' . static::$table;
            }

            $query = static::buidQuery($params);
            $st = static::getDB()->prepare($query);
            $st->execute($values);
            $result = $st->fetchColumn();

            return $result;
        }

        /**
         * @param $params
         * @return string
         */
        protected static function buidQuery($params) {
            $where = isset($params['where']) ? ' WHERE ' . $params['where'] : '';
            $orderBy = isset($params['orderBy']) ? $params['orderBy'] : static::$primaryKey;
            $limit = isset($params['limit']) ? ' LIMIT ' . $params['limit'] : '';
            $query = $params['query'] . $where . ' ORDER BY ' . $orderBy . $limit . ';';

            return $query;
        }

        /**
         * @return PDO
         */
        protected static function getDB() {
            return DB::getInstance(App::getInstance()->dbConfig);
        }
    }