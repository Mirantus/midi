<?php
    namespace app\Controller;

    use core\Controller;
    use app\Model\ModuleCat;
    use app\Model\ModuleComment;
    use app\Model\ModuleItem;
    use core\Form\Form;
    use lib\Utils;
    use lib\Image;

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
                    'count' => ModuleCat::count()
                ]
            ]);
        }

        public function cat($cat_id) {
            $cat = ModuleCat::findByPK($cat_id);

            if (empty($cat)) {
                $this->app->redirect('/404');
            }

            $items_query_params = $this->paginate();
            $items_query_params['where'] = 'cat = :cat';

            $this->render([
                'view' => 'Module/items',
                'vars' => [
                    'cat' => $cat,
                    'items' => ModuleItem::find($items_query_params, ['cat' => $cat_id]),
                    'count' => ModuleItem::count(),
                    'title' => $cat['title']
                ]
            ]);
        }

        public function item($id) {
            $vars = [
                'item' => ModuleItem::findByPK($id)
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
                    $formValues['user'] = $_SESSION['auth']['id'];
                    $formValues['ip'] = $_SERVER['REMOTE_ADDR'];
                    $formValues['date'] = date('Y-m-d');

                    $id = ModuleItem::insert($formValues);

                    //image
                    if ($id) {
                        // TODO: create File class
                        if ($this->app->isFileUploaded('file')) {
                            @mkdir($data_path . $id);
                            move_uploaded_file($_FILES['file']['tmp_name'],
                                $data_path . $id . '/' . $form->file->value);
                        }
                        if ($this->app->isFileUploaded('image')) {
                            $image_path = $data_path . $id . '/';
                            @mkdir($image_path);
                            move_uploaded_file($_FILES['image']['tmp_name'], $image_path . $form->image->value);
                            Image::CreatePreview($image_path . $form->image->value,
                                $image_path . 'thumb_' . $form->image->value, 100);
                            ModuleItem::updateByPK($id, ['image' => $form->image->value]);
                        }

                        if (!$this->app->isAjaxRequest()) {
                            // TODO: return to list
                            $this->app->redirect($this->app->url . '/ok');
                        }
                    } else {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                if ($this->app->isAjaxRequest()) {
                    $ajaxResponse = [];
                    if (!$form->isValid()) {
                        $ajaxResponse['errors'] = $form->getErrors();
                    }
                    $this->app->ajaxResponse($ajaxResponse);
                }
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
            if (!$id) {
                $this->app->back();
            }

            $item = ModuleItem::findByPK($id);
            if (empty($item)) {
                $this->app->back();
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

                    if (ModuleItem::updateByPK($id, $formValues)) {
                        //image
                        if ($this->app->isFileUploaded('file')) {
                            @mkdir($data_path . $id);
                            move_uploaded_file($_FILES['file']['tmp_name'], $data_path . $id . '/' . $form->file->value);
                        }
                        if ($this->app->isFileUploaded('image')) {
                            $image_path = $data_path . $id . '/';
                            @mkdir($image_path);
                            move_uploaded_file($_FILES['image']['tmp_name'], $image_path . $form->image->value);
                            Image::CreatePreview($image_path . $form->image->value,
                                $image_path . 'thumb_' . $form->image->value, 100);
                            ModuleItem::updateByPK($id, ['image' => $form->image->value]);
                        }

                        if (!$this->app->isAjaxRequest()) {
                            // TODO: return to list
                            $this->app->redirect($this->app->url . '/ok');
                        }
                    } else {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                if ($this->app->isAjaxRequest()) {
                    $ajaxResponse = [];
                    if (!$form->isValid()) {
                        $ajaxResponse['errors'] = $form->getErrors();
                    }
                    $this->app->ajaxResponse($ajaxResponse);
                }
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

        public function del() {
            $id = $this->app->getParamInt('id');
            if (!$id) {
                $this->app->back();
            }

            ModuleItem::deleteByPK($id);

            if ($this->app->isAjaxRequest()) {
                $this->app->ajaxResponse('');
            } else {
                $this->app->back();
            }
        }

        public function addcomment() {
            $item = $this->app->getParamInt('item');
            if (!$item) {
                $this->app->back();
            }

            $form = new Form();
            $form->add('item', ['value' => $item]);
            $form->add('text', ['title' => 'Текст']);
            $form->add('name', ['title' => 'Имя', 'value' => $item['name']]);
            $form->add('email', ['title' => 'E-mail', 'value' => $item['email']]);
            $form->fill();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

                    if (ModuleComment::insert($formValues)) {
                        if (!$this->app->isAjaxRequest()) {
                            // TODO: return to list
                            $this->app->redirect($this->app->url . '/ok');
                        }
                    } else {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                if ($this->app->isAjaxRequest()) {
                    $ajaxResponse = [];
                    if (!$form->isValid()) {
                        $ajaxResponse['errors'] = $form->getErrors();
                    } else {
                        $ajaxResponse['result'] = 'Спасибо, ваш комментарий принят.';
                    }
                    $this->app->ajaxResponse($ajaxResponse);
                }
            }

            $this->render([
                'vars' => [
                    'form' => $form,
                    'title' => 'Добавление комментария'
                ]
            ]);
        }

        protected function items() {
            $this->render([
                'vars' => [
                    'items' => ModuleItem::find($this->paginate()),
                    'count' => ModuleItem::count()
                ]
            ]);
        }
    }