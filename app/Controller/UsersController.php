<?php
    namespace app\Controller;

    use app\Model\User;
    use core\Auth;
    use core\Controller;
    use core\Form\Form;
    use core\Request;
    use core\Response;
    use core\Session;
    use lib\Url;

    class UsersController extends Controller {
        public function index() {
            $this->render([
                'vars' => [
                    'items' => User::find($this->paginate()),
                    'count_pages' => $this->countPages(User::count()),
                    'flash' => Session::getInstance()->flash('flash')
                ]
            ]);
        }

        public function add() {
            $form = new Form();
            $form->add('email', ['title' => 'E-mail']);
            $form->add('password', ['title' => 'Пароль']);
            $form->add('role', ['title' => 'Роль']);
            $form->add('name', ['title' => 'Имя']);
            $form->fill();

            if (Request::isPost()) {
                //initialization
                if ($form->password->value != '') {
                    $form->password->value = password_hash($form->password->value, PASSWORD_DEFAULT);
                }

                //validation
                if (!$form->email->isEmail()) {
                    $form->email->error = $form->errors['email'];
                } elseif (User::find(['where' => 'email=:email'], ['email' => $form->email->value])) {
                    $form->email->error = 'Пользователь с таким email уже существует';
                }
                if (empty($form->password->value)) {
                    $form->password->error = 'Введите пожалуйста пароль';
                }
                if (empty($form->name->value)) {
                    $form->name->error = 'Введите пожалуйста имя пользователя';
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();
                    $formValues['date'] = date('Y-m-d');

                    $id = User::insert($formValues);

                    if (!$id) {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                if ($form->isValid()) {
                    $return_url = '/' . $this->alias . '/';
                    $items_count = User::count();
                    $return_url = Url::addUrlParam($return_url, 'page', $this->countPages($items_count));
                    $return_url .= '#item' . $id;

                    Session::getInstance()->set('flash', 'Пользователь успешно добавлен');
                    $this->redirect($return_url);
                }
            }

            $this->render([
                'vars' => [
                    'form' => $form,
                    'roles' => User::getRoles(),
                    'title' => 'Добавление пользователя'
                ]
            ]);
        }

        /**
         * @param integer $id
         */
        public function edit($id) {
            $item = User::findByPK($id);
            if (empty($item)) {
                $this->notFound();
                return;
            }

            $form = new Form();
            $form->add('email', ['title' => 'E-mail', 'value' => $item['email']]);
            $form->add('password', ['title' => 'Пароль', 'value' => $item['password']]);
            $form->add('role', ['title' => 'Роль', 'value' => $item['role']]);
            $form->add('name', ['title' => 'Имя', 'value' => $item['name']]);
            $form->fill();

            if (Request::isPost()) {
                //initialization
                if ($form->password->value != '') {
                    $form->password->value = password_hash($form->password->value, PASSWORD_DEFAULT);
                }

                //validation
                if (!$form->email->isEmail()) {
                    $form->email->error = $form->errors['email'];
                } elseif (User::find(['where' => 'email=:email and id != :id'], ['email' => $form->email->value, 'id' => $id])) {
                    $form->email->error = 'Пользователь с таким email уже существует';
                }
                if (empty($form->password->value)) {
                    unset($form->fields['password']);
                }
                if (empty($form->name->value)) {
                    $form->name->error = 'Введите пожалуйста имя пользователя';
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();

                    if (!User::updateByPK($id, $formValues)) {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                if ($form->isValid()) {
                    Session::getInstance()->set('flash', 'Пользователь успешно изменен');
                    $return_url = Request::getParam('return', false, '/');
                    $this->redirect($return_url);
                }
            }

            $this->render([
                'vars' => [
                    'id' => $id,
                    'form' => $form,
                    'roles' => User::getRoles(),
                    'title' => 'Редактирование пользователя'
                ]
            ]);
        }

        /**
         * @param integer $id
         */
        public function del($id) {
            User::deleteByPK($id);
            Response::getInstance()->setAjax('');
        }

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
                    Auth::getInstance()->set($user);
                    $this->redirect($return_url);
                }
            }

            $this->render([
                'vars' => [
                    'flash' => Session::getInstance()->flash('flash'),
                    'form' => $form,
                    'title' => 'Авторизация'
                ]
            ]);
        }

        public function logout() {
            $return_url = Request::getParam('return', false, '/');
            Auth::getInstance()->reset();
            Response::getInstance()->redirect($return_url);
        }

        public function register() {
            $form = new Form();
            $form->add('email', ['title' => 'E-mail']);
            $form->add('password', ['title' => 'Пароль']);
            $form->add('name', ['title' => 'Имя']);
            $form->fill();

            if (Request::isPost()) {
                //initialization
                if ($form->password->value != '') {
                    $form->password->value = password_hash($form->password->value, PASSWORD_DEFAULT);
                }

                //validation
                if (!$form->email->isEmail()) {
                    $form->email->error = $form->errors['email'];
                } elseif (User::find(['where' => 'email=:email'], ['email' => $form->email->value])) {
                    $form->email->error = 'Пользователь с таким email уже существует';
                }
                if (empty($form->password->value)) {
                    $form->password->error = 'Введите пожалуйста пароль';
                }
                if (empty($form->name->value)) {
                    $form->name->error = 'Введите пожалуйста имя пользователя';
                }

                //process
                if ($form->isValid()) {
                    $formValues = $form->toArray();
                    $formValues['date'] = date('Y-m-d');

                    $id = User::insert($formValues);

                    if (!$id) {
                        $form->error = 'Ошибка сохранения данных';
                    }
                }

                if ($form->isValid()) {
                    Session::getInstance()->set('flash', 'Регистрация успешно завершена. Теперь вы можете войти.');
                    $this->redirect('/login/');
                }
            }

            $this->render([
                'vars' => [
                    'form' => $form,
                    'title' => 'Регистрация'
                ]
            ]);
        }
    }