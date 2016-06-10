<?php

namespace Mint;

class Application
{
	public $vars;
	public $routes;
	public $session;
	
	function __construct()
	{
		$this -> vars = new Vars();
		$this -> routes = new \ArrayObject([]);
		$this -> session = new Session();
	}
	
	public function get( $uri, $callback )
	{
		$route = new Route( 'get', $uri, $callback );
		$this -> routes[] = $route;
	}
	
	public function post( $uri, $callback )
	{
		$route = new Route( 'post', $uri, $callback );
		$this -> routes[] = $route;
	}
	
	public function run()
	{
		$uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
		$method = strtolower( $_SERVER['REQUEST_METHOD'] );
		/** @var $route Route */
		foreach( $this -> routes as $route )
		{
			if( $route -> compare( $method, $uri ) )
			{
				ob_start();
				$arguments = $route -> arguments( $uri );
				call_user_func_array( $route -> callback, $arguments );
			}
		}
	}
}