<?php
/**
 * View class
 *
 * @author Mikhail Miropolskiy <the-ms@ya.ru>
 * @package Core
 * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
 */
namespace core;

use core\Auth;

class View {
    /**
     * @var App
     */
    protected $app;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var boolean
     */
    protected $isOwner = false;

    /**
     * @var string
     */
    protected $layout = 'default';

    /**
     * @var string Path to view files
     */
    protected $path;

    /**
     * @var string Path to partial view files
     */
    protected $partialPath;

    /**
     * @var string Html title
     */
    protected $title;

    /**
     * @var array View vars
     */
    protected $vars = [];

    /**
     * View constructor.
     * @param string $path Path to view files
     * @param array $params
     */
    public function __construct($path, $params = []) {
        $this->app = App::getInstance();
        $this->auth = Auth::getInstance();
        if ($this->auth->isAdmin()) {
            $this->isOwner = true;
        } elseif (isset($params['isOwner'])) {
            $this->isOwner = $params['isOwner'];
        }
        $this->path = $path;
        $this->partialPath = $this->app->path . '/app/View/Partials';
        $this->layout = isset($params['layout']) ? $params['layout'] : $this->layout;
        $this->title = isset($params['title']) ? $params['title'] : '';
        $this->vars = isset($params['vars']) ? $params['vars'] : $this->vars;
    }

    /**
     * Return html output
     * @return string
     */
    public function render() {
        extract($this->vars);

        ob_start();
            require dirname(__FILE__) . '/../app/View/Layout/' . $this->layout . '.php';
        $html = ob_get_clean();

        return $html;
    }
}