<?php
    namespace app\Controller;

    use app\Model\ModuleComment;
    use core\Auth;
    use core\Form\Form;
    use core\Request;

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
    }