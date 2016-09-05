<?php
    namespace app\Controller;

    use core\Controller;
    use core\Form\Form;
    use core\Request;
    use core\Response;

    class UsersController extends Controller {
        public function login() {
            $return_url = Request::getParam('return', false, '/');

            $form = new Form();
            $form->add('login', array('title' => 'Логин'));
            $form->add('password', array('title' => 'Пароль'));
            $form->fill();

            if (Request::isPost()) {
                //validation
                if ($form->login->value != $this->app->auth['login']) $form->login->error = 'Введен неправильный логин';
                if ($form->password->value != $this->app->auth['password']) $form->password->error = 'Введен неправильный пароль';

                //process
                if ($form->isValid()) {
                    // TODO NEW USERS change to Users
                    $_SESSION['auth'] = $this->app->auth;
                    Response::getInstance()->redirect($return_url);
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
            $return_url = Request::getParam('return', false, '/');
            $_SESSION['auth'] = false;
            Response::getInstance()->redirect($return_url);
        }
    }