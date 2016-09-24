<?php
    namespace app\Controller;

    use app\Model\User;
    use core\Controller;
    use core\Form\Form;
    use core\Request;
    use core\Response;

    class UsersController extends Controller {
        public function login() {
            $return_url = Request::getParam('return', false, '/');

            $form = new Form();
            $form->add('email', ['title' => 'E-mail']);
            $form->add('password', ['title' => 'Пароль']);
            $form->fill();

            if (Request::isPost()) {
                //validation
                if (User::find(['where' => 'email = :email'], ['email' => $form->email->value])) {
                    if (!$user = User::auth($form->email->value, $form->password->value)) {
                        $form->password->error = 'Указан неправильный пароль';
                    }
                } else {
                    $form->email->error = 'Указанный email не найден';
                }

                //process
                if ($form->isValid()) {
                    $_SESSION['auth'] = $user;
                    unset($_SESSION['auth']['password']);
                    $this->redirect($return_url);
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