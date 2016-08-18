<?php
    namespace app\Controller;

    use core\Controller;
    use app\Model\ModuleCat;
    use app\Model\ModuleItem;
    use core\Form\Form;
    use lib\Utils;
    use lib\Image;

    class ModuleController extends Controller {
        private $level = 'cats';

        public function add() {
            $cats = [];

            $data_path = $this->app->webrootPath . '/data/' . $this->name . '/items/';

            $form = new Form();
            if ($this->level == 'cats') {
                $cats = ModuleCat::getAll();
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

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

                if ($this->app->isFileUploaded('file')) {
                    $form->file->value = Utils::prepareFileName($_FILES['file']['name']);
                    $form->file->error = $this->app->getFileUploadError('file');
                }
                if ($this->app->isFileUploaded('image')) {
                    $form->image->value = Utils::prepareFileName($_FILES['image']['name']);
                    $form->image->error = $this->app->getFileUploadError('image');

                    if (!$form->file->error && !Image::GetType($_FILES['image']['tmp_name'])) {
                        $form->image->error = 'Изображение должно быть в формате jpg, png или gif';
                    }
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();
                    $formValues['file'] = $form->file->value;
                    $formValues['image'] = $form->image->value;
                    // TODO: save current user
//                    $formValues['user'] = $_SESSION['current_user']['id'];
                    $formValues['ip'] = $_SERVER['REMOTE_ADDR'];
                    $formValues['date'] = date('Y-m-d');

                    $id = ModuleItem::insert($formValues);
                    // TODO: if $id == false, show errors

                    //image
                    if ($id) {
                        // TODO: create File class
                        if ($this->app->isFileUploaded('file')) {
                            @mkdir($data_path . $id);
                            move_uploaded_file($_FILES['file']['tmp_name'], $data_path . $id . '/' . $form->file->value);
                        }
                        if ($this->app->isFileUploaded('image')) {
                            $image_path = $data_path . $id . '/';
                            @mkdir($image_path);
                            move_uploaded_file($_FILES['image']['tmp_name'], $image_path . $form->image->value);
                            Image::CreatePreview($image_path . $form->image->value, $image_path . 'thumb_' . $form->image->value, 100);
                            ModuleItem::update(['image' => $form->image->value], 'id=' . $id);
                        }
                    }

//                    if (!$site->isAjaxRequest()) {
                        $this->app->redirect($this->app->url . '/ok');
//                    }
                }

                // TODO: add ajax support
//                if ($site->isAjaxRequest()) {
//                    $ajaxResponse = [];
//                    if (!$form->isValid()) {
//                        $ajaxResponse['errors'] = $form->getErrors();
//                    }
//                    $site->ajaxResponse($ajaxResponse);
//                }
            }

            $this->render([
                'vars' => [
                    'cats' => $cats,
                    'form' => $form,
                    'title' => 'Добавление модуля'
                ]
            ]);
        }

        public function edit() {
            $id = $this->app->getParamInt('id');
            if (!$id) $this->app->back();

            $item = ModuleItem::getById($id);
            if (empty($item)) $this->app->back();

            $data_path = $this->app->webrootPath . '/data/' . $this->name . '/items/';
            $cats = [];

            $form = new Form();
            if ($this->level == 'cats') {
                $cats = ModuleCat::getAll();
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

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

                if ($this->app->isFileUploaded('file')) {
                    $form->file->value = Utils::prepareFileName($_FILES['file']['name']);
                    $form->file->error = $this->app->getFileUploadError('file');
                }
                if ($this->app->isFileUploaded('image')) {
                    $form->image->value = Utils::prepareFileName($_FILES['image']['name']);
                    $form->image->error = $this->app->getFileUploadError('image');

                    if (!$form->file->error && !Image::GetType($_FILES['image']['tmp_name'])) {
                        $form->image->error = 'Изображение должно быть в формате jpg, png или gif';
                    }
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();
                    $formValues['file'] = $form->file->value;
                    $formValues['image'] = $form->image->value;

                    ModuleItem::update($formValues, 'id=' . $id);
                    // TODO: if false, show errors

                    //image
                    if ($this->app->isFileUploaded('file')) {
                        @mkdir($data_path . $id);
                        move_uploaded_file($_FILES['file']['tmp_name'], $data_path . $id . '/' . $form->file->value);
                    }
                    if ($this->app->isFileUploaded('image')) {
                        $image_path = $data_path . $id . '/';
                        @mkdir($image_path);
                        move_uploaded_file($_FILES['image']['tmp_name'], $image_path . $form->image->value);
                        Image::CreatePreview($image_path . $form->image->value, $image_path . 'thumb_' . $form->image->value, 100);
                        ModuleItem::update(['image' => $form->image->value], 'id=' . $id);
                    }

//                    if (!$site->isAjaxRequest()) {
                    // TODO: redirect to list with message
                    $this->app->redirect($this->app->url . '/ok');
//                    }
                }

                // TODO: add ajax support
//                if ($site->isAjaxRequest()) {
//                    $ajaxResponse = [];
//                    if (!$form->isValid()) {
//                        $ajaxResponse['errors'] = $form->getErrors();
//                    }
//                    $site->ajaxResponse($ajaxResponse);
//                }
            }

            $this->render([
                'vars' => [
                    'id' => $id,
                    'cats' => $cats,
                    'form' => $form,
                    'title' => 'Редактирование модуля'
                ]
            ]);
        }

        public function index() {
            $this->app->page->action = $this->level;
            $this->{$this->level}();
        }

        protected function cats() {
            $this->render([
                'vars' => [
                    'cats' => ModuleCat::getAll($this->paginate()),
                    'count' => ModuleCat::countAll()
                ]
            ]);
        }

        public function cat($cat_id) {
            $cat = ModuleCat::getById($cat_id);

            if (empty($cat)) {
                $this->app->redirect('/404');
            }
            
            $this->render([
                'view' => 'Module/items',
                'vars' => [
                    'cat' => $cat,
                    'items' => ModuleItem::getAll($this->paginate()),
                    'count' => ModuleItem::countAll(),
                    'title' => $cat['title']
                ]
            ]);
        }

        protected function items() {
            $this->render([
                'vars' => [
                    'items' => ModuleItem::getAll($this->paginate()),
                    'count' => ModuleItem::countAll()
                ]
            ]);
        }

        public function item($id) {
            $this->render([
                'vars' => [
                    'item' => ModuleItem::getById($id)
                ]
            ]);
        }
    }