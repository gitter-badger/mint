<?php

namespace Mint;

class Application
{
	public $vars;
	public $routes;
	public $session;
	public $request;
	
	function __construct()
	{
		$this -> vars = new Vars();
		$this -> routes = new \ArrayObject([]);
		$this -> session = new Session();
		$this -> request = new Request();
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
		/** @var $route Route */
		foreach( $this -> routes as $route )
		{
			if( $this -> compare( $route, $this -> request ) )
			{
				ob_start();
				call_user_func( $route -> callback, $this -> request );
			}
		}
	}

	private function compare( Route $route, Request $request )
	{
		if( strcmp( $route -> method, strtolower( $request -> method ) ) )
		{
			return false;
		}

		$route_uri = array_filter( explode( '/', $route -> uri ) );
		$request_uri = array_filter( explode( '/', $request -> uri ) );
		if( count( $route_uri ) != count( $request_uri ) )
		{
			return false;
		}

		$combine = array_combine( $route_uri, $request_uri );
		foreach( $combine as $key => $value )
		{
			if( strcmp( substr( $key, 0, 1 ), '$' ) && strcmp( $key, $value ) )
			{
				return false;
			}
		}

		$request -> arguments = $this -> arguments( $route, $request );
		return true;
	}

	private function arguments( Route $route, Request $request )
	{
		$arguments = [];
		$route_uri = array_filter( explode( '/', $route -> uri ) );
		$request_uri = array_filter( explode( '/', $request -> uri ) );

		$combine = array_combine( $route_uri, $request_uri );
		foreach( $combine as $key => $value )
		{
			if( !strcmp( substr( $key, 0, 1 ), '$') )
			{
				$arguments['get'][ substr( $key, 1 ) ] = $value;
			}
		}
		
		$arguments['post'] = $_POST;
		return $arguments;
	}
}