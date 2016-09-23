<?php
    namespace app\Controller;

    use core\Controller;
    use app\Model\ModuleCat;
    use app\Model\ModuleComment;
    use app\Model\ModuleItem;
    use core\Form\Form;
    use core\Request;
    use core\Response;
    use lib\File;
    use lib\Image;
    use lib\Utils;

    class ModuleController extends Controller {
        private $level = 'cats';
        private $comments = true;

        public function index() {
            $this->app->page->action = $this->level;
            $this->{$this->level}();
        }

        protected function cats() {
            $this->render([
                'vars' => [
                    'cats' => ModuleCat::find($this->paginate()),
                    'count' => ModuleCat::count(),
                    'page_limit' => $this->pageLimit
                ]
            ]);
        }

        /**
         * @param integer $cat_id
         */
        public function cat($cat_id) {
            $cat = ModuleCat::findByPK($cat_id);

            if (empty($cat)) {
                Response::getInstance()->redirect('/404');
            }

            $items_query_params = $this->paginate();
            $items_query_params['where'] = 'cat = :cat';

            $this->render([
                'view' => 'Module/items',
                'vars' => [
                    'cat' => $cat,
                    'items' => ModuleItem::find($items_query_params, ['cat' => $cat_id]),
                    'count' => ModuleItem::count(),
                    'page_limit' => $this->pageLimit,
                    'title' => $cat['title']
                ]
            ]);
        }

        /**
         * @param integer $id
         */
        public function item($id) {
            $item = ModuleItem::findByPK($id);

            if (empty($item)) {
                Response::getInstance()->redirect('/404');
            }

            $vars = [
                'item' => $item,
                'dataPath' => '/data/' . $this->name . '/items/'
            ];

            if ($this->comments) {
                $vars['comments'] = ModuleComment::find(['where' => 'item = :item'], ['item' => $id]);
            }

            $this->render([
                'vars' => $vars
            ]);
        }

        public function add() {
            $cats = [];

            $data_path = $this->app->webrootPath . '/data/' . $this->name . '/items/';

            $form = new Form();
            if ($this->level == 'cats') {
                $cats = ModuleCat::find();
                $form->add('cat', ['title' => 'Рубрика']);
            }
            $form->add('title', ['title' => 'Заголовок']);
            $form->add('text', ['title' => 'Текст']);
            $form->add('price', ['title' => 'Цена']);
            $form->add('image', ['title' => 'Изображение']);
            $form->add('file', ['title' => 'Файл']);
            $form->add('name', ['title' => 'Имя']);
            $form->add('phone', ['title' => 'Телефон']);
            $form->add('url', ['title' => 'Сайт']);
            $form->add('email', ['title' => 'E-mail']);
            $form->add('address', ['title' => 'Адрес']);

            $form->fill();

            if (Request::isPost()) {
                //initialization
                $form->url->value = str_replace('http://', '', $form->url->value);
                if ($form->url->value != '') {
                    $form->url->value = 'http://' . $form->url->value;
                }
                if ($form->phone->value != '') {
                    $form->phone->value = Utils::preparePhones($form->phone->value);
                }

                //validation
                if (!empty($form->email->value) && !$form->email->isEmail()) {
                    $form->email->error = $form->errors['email'];
                }
                if (!empty($form->url->value) && !$form->url->isUrl()) {
                    $form->url->error = $form->errors['url'];
                }
                if (!empty($form->price->value) && !$form->price->isInt()) {
                    $form->price->error = $form->errors['int'];
                }
                if ($this->level == 'cats' && !$form->cat->isInt()) {
                    $form->cat->error = $form->errors['cat'];
                }
                if (empty($form->title->value)) {
                    $form->title->error = 'Введите пожалуйста заголовок';
                }

                if (File::isUploaded('file')) {
                    $form->file->value = Utils::prepareFileName($_FILES['file']['name']);
                    $form->file->error = File::getUploadError('file');
                }
                if (File::isUploaded('image')) {
                    $form->image->value = Utils::prepareFileName($_FILES['image']['name']);
                    $form->image->error = File::getUploadError('image');

                    if (!$form->file->error && !Image::GetType($_FILES['image']['tmp_name'])) {
                        $form->image->error = 'Изображение должно быть в формате jpg, png или gif';
                    }
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();
                    $formValues['file'] = $form->file->value;
                    $formValues['image'] = $form->image->value;
                    $formValues['user'] = $_SESSION['auth']['id'];
                    $formValues['ip'] = $_SERVER['REMOTE_ADDR'];
                    $formValues['date'] = date('Y-m-d');

                    $id = ModuleItem::insert($formValues);

                    //image
                    if ($id) {
                        if (File::isUploaded('file')) {
                            File::moveUploadedFile($_FILES['file']['tmp_name'], $data_path . $id . '/' . $form->file->value);
                        }
                        if (File::isUploaded('image')) {
                            $image_path = $data_path . $id . '/';
                            File::moveUploadedFile($_FILES['image']['tmp_name'], $image_path . $form->image->value);
                            Image::CreatePreview($image_path . $form->image->value,
                                $image_path . 'thumb_' . $form->image->value, 100);
                            ModuleItem::updateByPK($id, ['image' => $form->image->value]);
                        }
                    } else {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                $ajaxResponse = $form->isValid() ? '' : ['errors' => $form->getErrors()];
                Response::getInstance()->setAjax($ajaxResponse);
                return;
            }

            $this->render([
                'vars' => [
                    'cats' => $cats,
                    'form' => $form,
                    'title' => 'Добавление модуля'
                ]
            ]);
        }

        /**
         * @param integer $id
         */
        public function edit($id) {
            $item = ModuleItem::findByPK($id);
            if (empty($item)) {
                Response::getInstance()->redirect('/404/');
            }

            $data_path = $this->app->webrootPath . '/data/' . $this->name . '/items/';
            $cats = [];

            $form = new Form();
            if ($this->level == 'cats') {
                $cats = ModuleCat::find();
                $form->add('cat', ['title' => 'Рубрика', 'value' => $item['cat']]);
            }
            $form->add('title', ['title' => 'Заголовок', 'value' => $item['title']]);
            $form->add('text', ['title' => 'Текст', 'value' => $item['text']]);
            $form->add('price', ['title' => 'Цена', 'value' => $item['price']]);
            $form->add('image', ['title' => 'Изображение', 'value' => $item['image']]);
            $form->add('file', ['title' => 'Файл', 'value' => $item['file']]);
            $form->add('name', ['title' => 'Имя', 'value' => $item['name']]);
            $form->add('phone', ['title' => 'Телефон', 'value' => $item['phone']]);
            $form->add('url', ['title' => 'Сайт', 'value' => $item['url']]);
            $form->add('email', ['title' => 'E-mail', 'value' => $item['email']]);
            $form->add('address', ['title' => 'Адрес', 'value' => $item['address']]);

            $form->fill();

            if (Request::isPost()) {
                //initialization
                $form->url->value = str_replace('http://', '', $form->url->value);
                if ($form->url->value != '') {
                    $form->url->value = 'http://' . $form->url->value;
                }
                if ($form->phone->value != '') {
                    $form->phone->value = Utils::preparePhones($form->phone->value);
                }

                //validation
                if (!empty($form->email->value) && !$form->email->isEmail()) {
                    $form->email->error = $form->errors['email'];
                }
                if (!empty($form->url->value) && !$form->url->isUrl()) {
                    $form->url->error = $form->errors['url'];
                }
                if (!empty($form->price->value) && !$form->price->isInt()) {
                    $form->price->error = $form->errors['int'];
                }
                if ($this->level == 'cats' && !$form->cat->isInt()) {
                    $form->cat->error = $form->errors['cat'];
                }
                if (empty($form->title->value)) {
                    $form->title->error = 'Введите пожалуйста заголовок';
                }

                if (File::isUploaded('file')) {
                    $form->file->value = Utils::prepareFileName($_FILES['file']['name']);
                    $form->file->error = File::getUploadError('file');
                }
                if (File::isUploaded('image')) {
                    $form->image->value = Utils::prepareFileName($_FILES['image']['name']);
                    $form->image->error = File::getUploadError('image');

                    if (!$form->file->error && !Image::GetType($_FILES['image']['tmp_name'])) {
                        $form->image->error = 'Изображение должно быть в формате jpg, png или gif';
                    }
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();
                    $formValues['file'] = $form->file->value;
                    $formValues['image'] = $form->image->value;

                    if (ModuleItem::updateByPK($id, $formValues)) {
                        //image
                        if (File::isUploaded('file')) {
                            File::moveUploadedFile($_FILES['file']['tmp_name'], $data_path . $id . '/' . $form->file->value);
                        }
                        if (File::isUploaded('image')) {
                            $image_path = $data_path . $id . '/';
                            File::moveUploadedFile($_FILES['image']['tmp_name'], $image_path . $form->image->value);
                            Image::CreatePreview($image_path . $form->image->value,
                                $image_path . 'thumb_' . $form->image->value, 100);
                            ModuleItem::updateByPK($id, ['image' => $form->image->value]);
                        }
                    } else {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }
                $ajaxResponse = $form->isValid() ? '' : ['errors' => $form->getErrors()];
                Response::getInstance()->setAjax($ajaxResponse);
                return;
            }

            $this->render([
                'vars' => [
                    'id' => $id,
                    'cats' => $cats,
                    'dataPath' => '/data/' . $this->name . '/items/',
                    'form' => $form,
                    'title' => 'Редактирование модуля'
                ]
            ]);
        }

        /**
         * @param integer $id
         */
        public function del($id) {
            ModuleItem::deleteByPK($id);
            Response::getInstance()->setAjax('');
        }

        /**
         * @param integer $item_id
         */
        public function addcomment($item_id) {
            $form = new Form();
            $form->add('item', ['value' => $item_id]);
            $form->add('text', ['title' => 'Текст']);
            $form->add('name', ['title' => 'Имя']);
            $form->add('email', ['title' => 'E-mail']);
            $form->fill();

            if (Request::isPost()) {
                //validation
                if (!empty($form->email->value) && !$form->email->isEmail()) {
                    $form->email->error = $form->errors['email'];
                }
                if (empty($form->name->value)) {
                    $form->name->error = 'Введите пожалуйста ваше имя';
                }
                if (empty($form->text->value)) {
                    $form->text->error = 'Введите пожалуйста комментарий';
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();
                    $formValues['user'] = $_SESSION['auth']['id'];
                    $formValues['ip'] = $_SERVER['REMOTE_ADDR'];
                    $formValues['date'] = date('Y-m-d');

                    if (!ModuleComment::insert($formValues)) {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                $ajaxResponse = $form->isValid()
                    ? ['result' => 'Спасибо, ваш комментарий принят.']
                    : ['errors' => $form->getErrors()];

                Response::getInstance()->setAjax($ajaxResponse);

                return;
            }

            $this->render([
                'vars' => [
                    'form' => $form,
                    'title' => 'Добавление комментария'
                ]
            ]);
        }

        public function rss() {
            Response::getInstance()->addHeader('Content-type', 'application/rss+xml');
            $this->render([
                'layout' => 'empty',
                'vars' => [
                    'items' => ModuleItem::find([
                        'limit' => 10,
                        'orderBy' => ModuleItem::$primaryKey . ' DESC'
                    ])
                ]
            ]);
        }

        protected function items() {
            $this->render([
                'vars' => [
                    'items' => ModuleItem::find($this->paginate()),
                    'count' => ModuleItem::count(),
                    'page_limit' => $this->pageLimit
                ]
            ]);
        }
    }