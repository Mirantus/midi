<?php
    namespace app\Model;

    use core\Model;

    class ModuleComment extends Model {
        /**
         * @var array
         */
        protected static $fields = ['id', 'item', 'text', 'name', 'email', 'user', 'ip', 'date', 'access'];

        /**
         * @var string
         */
        protected static $table = 'module_comment';
    }