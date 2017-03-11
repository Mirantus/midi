<?php
    namespace app\Model;

    use core\Model;

    class User extends Model {
        /**
         * @var array
         */
        protected static $fields = ['id', 'email', 'password', 'role', 'name', 'restore_code', 'ip', 'date'];

        /**
         * @var string
         */
        protected static $table = 'user';

        /**
         * Returns user by email and password
         * @param string $email
         * @param string $password
         * @return array|bool
         */
        public static function auth($email, $password) {
            $user = static::find(['where' => 'email = :email'], ['email' => $email]);

            if ($user && password_verify($password , $user[0]['password'])) {
                return $user[0];
            }

            return false;
        }

        public static function getRoles() {
            return [
                'user' => 'Пользователь',
                'admin' => 'Администратор'
            ];
        }
    }