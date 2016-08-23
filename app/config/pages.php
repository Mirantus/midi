<?php
$config['pages'] = [
    'index' => ['route' => '/', 'title' => 'Главная'],
    '404' => ['route' => '/404/', 'controller' => 'NotFoundController', 'title' => 'Страница не найдена'],
    'admin' => ['route' => '/admin/', 'controller' => 'AdminController', 'auth' => true, 'title' => 'Администрирование'],
    'contacts' => ['route' => '/contacts/', 'controller' => 'ContactsController', 'title' => 'Контакты'],
    'feedback' => ['route' => '/feedback/', 'controller' => 'FeedbackController', 'title' => 'Обратная связь'],
    'gallery' => ['route' => '/gallery/', 'controller' => 'GalleryController', 'title' => 'Фотогалерея'],
    'login' => ['route' => '/login/', 'controller' => 'UsersController', 'action' => 'login', 'title' => 'Авторизация'],
    'logout' => ['route' => '/logout/', 'controller' => 'UsersController', 'action' => 'logout', 'title' => 'Выход'],

    'module' => ['route' => '/module/', 'controller' => 'ModuleController', 'title' => 'Модуль'],
    'module_add' => ['route' => '/module/add/', 'controller' => 'ModuleController', 'action' => 'add', 'auth' => true, 'title' => 'Добавление'],
    'module_addcomment' => ['route' => '/module/addcomment/(\d+)/', 'controller' => 'ModuleController', 'action' => 'addcomment', 'params' => ['item'], 'title' => 'Добавление комментария'],
    'module_cat' => ['route' => '/module/cat/(\d+)/', 'controller' => 'ModuleController', 'action' => 'cat', 'params' => ['id'], 'title' => 'Рубрика'],
    'module_del' => ['route' => '/module/del/(\d+)/', 'controller' => 'ModuleController', 'action' => 'del', 'params' => ['id'], 'auth' => true, 'title' => 'Удаление'],
    'module_edit' => ['route' => '/module/edit/(\d+)/', 'controller' => 'ModuleController', 'action' => 'edit', 'params' => ['id'], 'auth' => true, 'title' => 'Редактирование'],
    'module_item' => ['route' => '/module/item/(\d+)/', 'controller' => 'ModuleController', 'action' => 'item', 'params' => ['id'], 'title' => 'Подробная информация'],
    'module_items' => ['route' => '/module/items/', 'controller' => 'ModuleController', 'action' => 'items', 'title' => 'Модуль'],

    'ok' => ['route' => '/ok/', 'controller' => 'PageController', 'action' => 'ok', 'title' => 'Данные отправлены'],
    'subscribe' => ['route' => '/module/subscribe/', 'controller' => 'SubscribeController', 'title' => ''],
];