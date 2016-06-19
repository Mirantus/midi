<?php
    namespace app\Controller;

    use core\Controller;

    class NotFoundController extends Controller {
        public function index() {
            $this->render();
        }
    }