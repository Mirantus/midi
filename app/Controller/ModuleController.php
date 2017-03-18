<?php
    namespace app\Controller;

    use core\Controller;
    use app\Model\ModuleCat;
    use app\Model\ModuleComment;
    use app\Model\ModuleItem;
    use core\Auth;
    use core\Form\Form;
    use core\Request;
    use core\Response;
    use core\Session;
    use lib\File;
    use lib\Image;
    use lib\Utils;
    use lib\Url;

    class ModuleController extends Controller {
        use ModuleCatTrait;
        use ModuleCommentTrait;

        public function index() {
            $level = $this->getLevel();
            $this->app->page->action = $level;
            $this->{$level}();
        }

        /**
         * @param integer $id
         */
        public function item($id) {
            $item = ModuleItem::findByPK($id);

            if (empty($item)) {
                $this->notFound();
                return;
            }

            $vars = [
                'item' => $item,
                'dataPath' => '/data/' . $this->name . '/items/'
            ];

            if (isset($this->comments)) {
                $vars['comments'] = ModuleComment::find(
                    [
                        'query' => 'select module_comment.*, user.name as user_name from module_comment left join user on module_comment.user = user.id',
                        'where' => 'module_comment.item = :id'
                    ],
                    ['id' => $id]
                );
            }

            $this->render([
                'vars' => $vars
            ]);
        }

        public function add() {
            $cats = [];

            $data_path = $this->app->webrootPath . '/data/' . $this->name . '/items/';

            $form = new Form();
            if ($this->getLevel() == 'cats') {
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
                if ($this->getLevel() == 'cats' && !$form->cat->isInt()) {
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
                    $formValues['user'] = Auth::getInstance()->get('id');
                    $formValues['ip'] = $_SERVER['REMOTE_ADDR'];
                    $formValues['date'] = date('Y-m-d');

                    $id = ModuleItem::insert($formValues);

                    if ($id) {
                        ModuleItem::updateByPK($id, ['sort' => $id]);

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

                if ($form->isValid()) {
                    if ($this->getLevel() == 'cats') {
                        $return_url = '/' . $this->alias . '/cat/' . $form->cat->value;
                        $items_count = ModuleItem::count(['where' => 'cat = :cat'], ['cat' => $form->cat->value]);
                    } else {
                        $return_url = '/' . $this->alias . '/';
                        $items_count = ModuleItem::count();
                    }
                    $return_url = Url::addUrlParam($return_url, 'page', $this->countPages($items_count));
                    $return_url .= '#item' . $id;

                    Session::getInstance()->set('flash', 'Запись успешно добавлена');
                    $this->redirect($return_url);
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

        /**
         * @param integer $id
         */
        public function edit($id) {
            $item = ModuleItem::findByPK($id);
            if (empty($item)) {
                $this->notFound();
                return;
            }

            $data_path = $this->app->webrootPath . '/data/' . $this->name . '/items/';
            $cats = [];

            $form = new Form();
            if ($this->getLevel() == 'cats') {
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
                if ($this->getLevel() == 'cats' && !$form->cat->isInt()) {
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

                if ($form->isValid()) {
                    Session::getInstance()->set('flash', 'Запись успешно изменена');
                    $return_url = Request::getParam('return', false, '/');
                    $this->redirect($return_url);
                }
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

        public function reorder() {
            if (!Request::isPost()) {
                return;
            }

            $data = Request::getParam('data', false, '[]');
            $ids = json_decode($data);

            if (!count($ids)) {
                return;
            }

            $query_params = [
                'where' => 'id IN (' . implode(',', $ids) . ')',
                'orderBy' => 'sort'
            ];

            $items = ModuleItem::find($query_params);

            if (!$items) {
                return;
            }

            for ($i = 0; $i < count($ids); $i++) {
                // set asc sorted statuses to ids sorted by user
                ModuleItem::updateByPK($ids[$i], ['sort' => $items[$i]['sort']]);
            }
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
            $items_query_params = $this->paginate();
            $items_query_params['orderBy'] = 'sort';

            $this->render([
                'vars' => [
                    'items' => ModuleItem::find($items_query_params),
                    'count_pages' => $this->countPages(ModuleItem::count()),
                    'flash' => Session::getInstance()->flash('flash')
                ]
            ]);
        }
    }