<?php
    namespace app\Controller;

    use core\Controller;
    use core\Form\Form;

    class UsersController extends Controller {
        public function login() {
            $return_url = $this->app->getParam('return', false, '/');

            $form = new Form();
            $form->add('login', array('title' => 'Логин'));
            $form->add('password', array('title' => 'Пароль'));
            $form->fill();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //validation
                if ($form->login->value != $this->app->auth['login']) $form->login->error = 'Введен неправильный логин';
                if ($form->password->value != $this->app->auth['password']) $form->password->error = 'Введен неправильный пароль';

                //process
                if ($form->isValid()) {
                    $_SESSION['auth'] = true;
                    $this->app->redirect($return_url);
                }
            }

            $this->render([
                'vars' => [
                    'form' => $form,
                    'title' => 'Авторизация'
                ]
            ]);
        }

        public function logout() {
            $return_url = $this->app->getParam('return', false, '/');
            $_SESSION['auth'] = false;
            $this->app->redirect($return_url);
        }
    }