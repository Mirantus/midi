<?php
    namespace app\Controller;

    use app\Model\ModuleCat;
    use app\Model\ModuleItem;
    use core\Form\Form;
    use core\Request;
    use core\Response;
    use core\Session;
    use lib\Url;

    trait ModuleCatTrait {
        protected $level = 'cats';

        protected function cats() {
            $cats_query_params = $this->paginate();
            $cats_query_params['orderBy'] = 'sort';

            $this->render([
                'vars' => [
                    'flash' => Session::getInstance()->flash('flash'),
                    'items' => ModuleCat::find($cats_query_params),
                    'count_pages' => $this->countPages(ModuleCat::count())
                ]
            ]);
        }

        /**
         * @param integer $cat_id
         */
        public function cat($cat_id) {
            $cat = ModuleCat::findByPK($cat_id);

            if (empty($cat)) {
                $this->notFound();
                return;
            }

            $items_query_params = $this->paginate();
            $items_query_params['where'] = 'cat = :cat';
            $items_query_params['orderBy'] = 'sort';

            $this->render([
                'view' => $this->name . '/items',
                'vars' => [
                    'cat' => $cat,
                    'flash' => Session::getInstance()->flash('flash'),
                    'items' => ModuleItem::find($items_query_params, ['cat' => $cat_id]),
                    'count_pages' => $this->countPages(ModuleItem::count(['where' => 'cat = :cat'], ['cat' => $cat_id])),
                    'title' => $cat['title']
                ]
            ]);
        }

        public function addcat() {
            $form = new Form();
            $form->add('title', ['title' => 'Название рубрики']);
            $form->fill();

            if (Request::isPost()) {
                //validation
                if (empty($form->title->value)) {
                    $form->title->error = 'Введите пожалуйста название рубрики';
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();

                    $id = ModuleCat::insert($formValues);

                    if ($id) {
                        ModuleCat::updateByPK($id, ['sort' => $id]);
                    } else {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                if ($form->isValid()) {
                    $return_url = '/' . $this->alias . '/';
                    $items_count = ModuleCat::count();
                    $return_url = Url::addUrlParam($return_url, 'page', $this->countPages($items_count));
                    $return_url .= '#item' . $id;

                    Session::getInstance()->set('flash', 'Рубрика успешно добавлена');
                    $this->redirect($return_url);
                }
            }

            $this->render([
                'vars' => [
                    'form' => $form,
                    'title' => 'Добавление рубрики'
                ]
            ]);
        }

        /**
         * @param integer $id
         */
        public function editcat($id) {
            $item = ModuleCat::findByPK($id);
            if (empty($item)) {
                $this->notFound();
                return;
            }

            $form = new Form();
            $form->add('title', ['title' => 'Название рубрики', 'value' => $item['title']]);
            $form->fill();

            if (Request::isPost()) {
                //validation
                if (empty($form->title->value)) {
                    $form->title->error = 'Введите пожалуйста название рубрики';
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();

                    if (!ModuleCat::updateByPK($id, $formValues)) {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                if ($form->isValid()) {
                    Session::getInstance()->set('flash', 'Рубрика успешно изменена');
                    $return_url = Request::getParam('return', false, '/');
                    $this->redirect($return_url);
                }
            }

            $this->render([
                'vars' => [
                    'id' => $id,
                    'form' => $form,
                    'title' => 'Редактирование рубрики'
                ]
            ]);
        }

        /**
         * @param integer $id
         */
        public function delcat($id) {
            ModuleCat::deleteByPK($id);
            Response::getInstance()->setAjax('');
        }

        public function reordercat() {
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

            $items = ModuleCat::find($query_params);

            if (!$items) {
                return;
            }

            for ($i = 0; $i < count($ids); $i++) {
                // set asc sorted statuses to ids sorted by user
                ModuleCat::updateByPK($ids[$i], ['sort' => $items[$i]['sort']]);
            }
        }
    }