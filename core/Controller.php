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
	protected $layout = 'default';

    /**
     * @var string
     */
    protected $name;

	/**
	 * @var string
	 */
	protected $resourcePath;

	/**
	 * @var string
	 */
	protected $viewPath;

	/**
	 * ContactsController constructor.
	 * @param App $app
	 */
	public function __construct($app) {
		$this->app = $app;
        $this->name = str_replace('Controller', '', $this->app->page->controller);
	}

	public function render($view_params = []) {
	    // TODO: create class View
        $title = $this->app->page->title;
		
		$view_vars = isset($view_params['vars']) ? $view_params['vars'] : [];
		extract($view_vars);

        $this->viewPath = isset($view_params['view']) ? $view_params['view'] : $this->name . '/' . $this->app->page->action;
		$this->viewPath = dirname(__FILE__) . '/../app/View/' . $this->viewPath;
		
		$this->resourcePath = isset($view_params['resource']) ? $view_params['resource'] : $this->name;
		$this->resourcePath = $this->app->webrootPath . '/data/' . $this->resourcePath;
		
        $this->layout = isset($view_params['layout']) ? $view_params['layout'] : $this->layout;
		
		require dirname(__FILE__) . '/../app/View/Layout/' . $this->layout . '.php';
	}

	protected function paginate() {
		$currentPage = Request::getParam('page');
		if ($currentPage == '') $currentPage = 1;
		$first = ($currentPage - 1) * $this->pageLimit;

		return ['limit' => $first . ',' . $this->pageLimit];
	}
}