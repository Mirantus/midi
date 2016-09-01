<?php
/**
 * Controller class
 *
 * @author Mikhail Miropolskiy <the-ms@ya.ru>
 * @package Core
 * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
 */
namespace core;

abstract class Controller {
    /**
     * @var integer
     */
    public $pageLimit = 20;
    
	/**
	 * @var App
	 */
	protected $app;

    /**
     * @var string
     */
    protected $name;

	/**
	 * ContactsController constructor.
	 * @param App $app
	 */
	public function __construct($app) {
		$this->app = $app;
        $this->name = str_replace('Controller', '', $this->app->page->controller);
	}

	public function render($view_params = []) {
        $view_path = isset($view_params['view']) ? $view_params['view'] : $this->name . '/' . $this->app->page->action;
        $view_path = dirname(__FILE__) . '/../app/View/' . $view_path;

        $view_params['title'] =  isset($view_params['title']) ? $view_params['title'] : $this->app->page->title;

	    $view = new View($view_path, $view_params);
        $content = $view->render();

        Response::getInstance()->setContent($content);
	}

	protected function paginate() {
		$currentPage = Request::getParam('page');
		if ($currentPage == '') $currentPage = 1;
		$first = ($currentPage - 1) * $this->pageLimit;

		return ['limit' => $first . ',' . $this->pageLimit];
	}
}