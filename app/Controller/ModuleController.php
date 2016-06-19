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

//            $moduleImagePath = $site->webrootPath . '/data/' . $this->module . '/items/';

            $form = new Form();
            if ($this->level == 'cats') {
                $cats = ModuleCat::getAll();
                $form->add('cat', array('title' => 'Рубрика'));
            }
            $form->add('title', array('title' => 'Заголовок'));
            $form->add('text', array('title' => 'Текст'));
            $form->add('price', array('title' => 'Цена'));
            $form->add('image', array('title' => 'Изображение'));
            $form->add('file', array('title' => 'Файл'));
            $form->add('name', array('title' => 'Имя'));
            $form->add('phone', array('title' => 'Телефон'));
            $form->add('url', array('title' => 'Сайт'));
            $form->add('email', array('title' => 'E-mail'));
            $form->add('address', array('title' => 'Адрес'));

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
                if (isset($config['cats']) && !$form->cat->isInt()) {
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
                if ($this->app->isFileUploaded('file') && $form->isValid()) {
                    if (!move_uploaded_file($_FILES['file']['tmp_name'],
                        $this->app->webrootPath . '/data/' . $this->name . '/items/' . $form->file->value)
                    ) {
                        $form->file->error = 'Ошибка загрузки файла';
                    }
                }
                if ($this->app->isFileUploaded('image') && $form->isValid()) {
                    if (!move_uploaded_file($_FILES['image']['tmp_name'],
                        $this->app->webrootPath . '/data/' . $this->name . '/items/' . $form->image->value)
                    ) {
                        $form->image->error = 'Ошибка загрузки файла';
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

                    $fields = $placeholders = $values = [];
                    foreach ($formValues as $field => $value) {
                        // TODO: config to model
//                        if (!isset($config['items'][$field])) {
//                            continue;
//                        }
                        $fields[] = $field;
                        $placeholders[] = ':' . $field;
                        $values[$field] = $value;
                    }
                    // TODO: save model
//                    $st = $site->db->prepare(
//                        'INSERT INTO ' . $site->module . '_items (' . implode(',',
//                            $fields) . ') VALUES (' . implode(',', $placeholders) . ')'
//                    );
//                    $st->execute($values);

                    //image
                    // TODO: save image
//                    $id = $site->db->lastInsertId();
//                    if ($site->isFileUploaded('image')) {
//                        $image = $id . '.' . Image::GetType($moduleImagePath . '/' . $form->image->value);
//                        rename($moduleImagePath . '/' . $form->image->value, $moduleImagePath . '/' . $image);
//                        Image::CreatePreview($moduleImagePath . '/' . $image, $moduleImagePath . '/thumbs/' . $image,
//                            100);
//                        $site->db->exec('UPDATE ' . $site->module . '_items SET image="' . $image . '" WHERE id=' . $id);
//                    }

//                    if (!$site->isAjaxRequest()) {
//                        $site->redirect($site->url . '/ok');
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