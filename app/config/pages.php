<?php
$config['pages'] = [
    'index' => ['route' => '/', 'title' => 'Главная'],
    '404' => ['route' => '/404/', 'controller' => 'PageController', 'action' => 'notfound', 'title' => 'Страница не найдена'],
    'admin' => ['route' => '/admin/', 'controller' => 'AdminController', 'auth' => 'admin', 'title' => 'Администрирование'],
    'contacts' => ['route' => '/contacts/', 'controller' => 'PageController', 'action' => 'contacts', 'title' => 'Контакты'],
    'feedback' => ['route' => '/feedback/', 'controller' => 'FeedbackController', 'title' => 'Обратная связь'],
    'gallery' => ['route' => '/gallery/', 'controller' => 'GalleryController', 'title' => 'Фотогалерея'],

    'login' => ['route' => '/login/', 'controller' => 'UsersController', 'action' => 'login', 'title' => 'Авторизация'],
    'logout' => ['route' => '/logout/', 'controller' => 'UsersController', 'action' => 'logout', 'title' => 'Выход'],
    'users' => ['route' => '/users/', 'controller' => 'UsersController', 'action' => 'index', 'auth' => 'admin', 'title' => 'Пользователи'],
    'users_add' => ['route' => '/users/add/', 'controller' => 'UsersController', 'action' => 'add', 'auth' => 'admin', 'title' => 'Добавление пользователя'],
    'users_del' => ['route' => '/users/del/(\d+)/', 'controller' => 'UsersController', 'action' => 'del', 'params' => ['id'], 'auth' => 'admin', 'title' => 'Удаление'],
    'users_edit' => ['route' => '/users/edit/(\d+)/', 'controller' => 'UsersController', 'action' => 'edit', 'params' => ['id'], 'auth' => 'admin', 'title' => 'Редактирование'],

    'module' => ['route' => '/module/', 'controller' => 'ModuleController', 'title' => 'Модуль'],
    'module_add' => ['route' => '/module/add/', 'controller' => 'ModuleController', 'action' => 'add', 'auth' => 'admin', 'title' => 'Добавление'],
    'module_addcomment' => ['route' => '/module/addcomment/(\d+)/', 'controller' => 'ModuleController', 'action' => 'addcomment', 'params' => ['item'], 'title' => 'Добавление комментария'],
    'module_cat' => ['route' => '/module/cat/(\d+)/', 'controller' => 'ModuleController', 'action' => 'cat', 'params' => ['id'], 'title' => 'Рубрика'],
    'module_del' => ['route' => '/module/del/(\d+)/', 'controller' => 'ModuleController', 'action' => 'del', 'params' => ['id'], 'auth' => 'admin', 'title' => 'Удаление'],
    'module_edit' => ['route' => '/module/edit/(\d+)/', 'controller' => 'ModuleController', 'action' => 'edit', 'params' => ['id'], 'auth' => 'admin', 'title' => 'Редактирование'],
    'module_item' => ['route' => '/module/item/(\d+)/', 'controller' => 'ModuleController', 'action' => 'item', 'params' => ['id'], 'title' => 'Подробная информация'],
    'module_items' => ['route' => '/module/items/', 'controller' => 'ModuleController', 'action' => 'items', 'title' => 'Модуль'],
    'module_reorder' => ['route' => '/module/reorder/', 'controller' => 'ModuleController', 'action' => 'reorder', 'auth' => 'admin', 'title' => 'Сортировка'],
    'module_rss' => ['route' => '/module/rss/', 'controller' => 'ModuleController', 'action' => 'rss', 'title' => 'RSS'],
];