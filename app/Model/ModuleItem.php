<?php
    namespace app\Model;

    use core\Model;

    class ModuleItem extends Model {
        /**
         * @var array
         */
        protected static $fields = ['id', 'cat', 'title', 'text', 'price', 'image', 'file', 'name', 'phone', 'url', 'email', 'city', 'address', 'company', 'user', 'ip', 'date', 'access', 'sort'];

        /**
         * @var string
         */
        protected static $table = 'module_item';
    }