<?php
    namespace app\Controller;

    use core\Controller;
    use app\Model\ModuleCat;
    use app\Model\ModuleItem;

    class ModuleController extends Controller {
        private $level = 'cats';

        public function add() {
            $this->render([
                'vars' => [
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