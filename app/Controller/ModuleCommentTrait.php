<?php
    namespace app\Controller;

    use app\Model\ModuleComment;
    use core\Auth;
    use core\Form\Form;
    use core\Request;
    use core\Response;
    use core\Session;

    trait ModuleCommentTrait {
        protected $comments = true;

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
                if (!Auth::getInstance()->isAuth()) {
                    if (!empty($form->email->value) && !$form->email->isEmail()) {
                        $form->email->error = $form->errors['email'];
                    }
                    if (empty($form->name->value)) {
                        $form->name->error = 'Введите пожалуйста ваше имя';
                    }
                }
                if (empty($form->text->value)) {
                    $form->text->error = 'Введите пожалуйста комментарий';
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();
                    $formValues['user'] = Auth::getInstance()->get('id');
                    $formValues['ip'] = $_SERVER['REMOTE_ADDR'];
                    $formValues['date'] = date('Y-m-d');

                    $id = ModuleComment::insert($formValues);

                    if (!$id) {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                if ($form->isValid()) {
                    $this->redirect('/' . $this->alias . '/item/' . $item_id . '/#comment' . $id);
                }
            }

            $this->render([
                'vars' => [
                    'form' => $form,
                    'title' => 'Добавление комментария'
                ]
            ]);
        }

        /**
         * @param integer $id
         */
        public function editcomment($id) {
            $item = ModuleComment::findByPK($id);
            $auth = Auth::getInstance();

            if (empty($item) || (!$auth->isAdmin() && $auth->get('id') != $item['user'])) {
                $this->notFound();
                return;
            }

            $form = new Form();
            $form->add('text', ['title' => 'Текст', 'value' => $item['text']]);
            if ($auth->isAdmin()) {
                $form->add('name', ['title' => 'Имя', 'value' => $item['name']]);
                $form->add('email', ['title' => 'E-mail', 'value' => $item['email']]);
            }
            $form->fill();

            if (Request::isPost()) {
                if (empty($form->text->value)) {
                    $form->text->error = 'Введите пожалуйста комментарий';
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();

                    if (!ModuleComment::updateByPK($id, $formValues)) {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                if ($form->isValid()) {
                    Session::getInstance()->set('flash', 'Комментарий успешно изменен');
                    $return_url = Request::getParam('return', false, '/');
                    $this->redirect($return_url);
                }
            }

            $this->render([
                'vars' => [
                    'form' => $form,
                    'title' => 'Добавление комментария'
                ]
            ]);
        }

        /**
         * @param integer $id
         */
        public function delcomment($id) {
            $item = ModuleComment::findByPK($id);
            $auth = Auth::getInstance();

            if (!empty($item) && ($auth->isAdmin() || $auth->get('id') == $item['user'])) {
                ModuleComment::deleteByPK($id);
            }

            Response::getInstance()->setAjax('');
        }
    }