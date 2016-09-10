<?php
    namespace app\Controller;

    use core\Controller;
    use core\Form\Form;
    use core\Request;
    use core\Response;
    use lib\File;
    use lib\Image;
    use lib\Utils;

    class FeedbackController extends Controller {
        public function index() {
            $form = new Form();
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

                    if (File::isUploaded('file')) {
                        //$_FILES['file']['tmp_name']
                    }
                    if (File::isUploaded('image')) {
                        //$_FILES['image']['tmp_name']
                    }

                    $message = '';
                    foreach ($form->fields as $field) {
                        $message .= $field->title . ': ' . $field->value . "\r\n";
                    }
                    $this->app->mail($this->app->owner, $_SERVER['HTTP_HOST'] . ' - Обратная связь', $message);
                }

                $ajaxResponse = $form->isValid() ? '' : ['errors' => $form->getErrors()];
                Response::getInstance()->setAjax($ajaxResponse);
                return;
            }

            $this->render([
                'vars' => [
                    'form' => $form,
                    'title' => 'Обратная связь'
                ]
            ]);
        }
    }