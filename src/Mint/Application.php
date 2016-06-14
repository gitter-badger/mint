<?php

namespace Mint;

class Application
{
	public $vars;
	public $route;
	public $session;
	public $request;
	
	function __construct()
	{
		$this -> vars = new Vars();
		$this -> session = new Session();
		$this -> request = new Request();
	}
	
	public function get( $uri, $callback )
	{
		$this -> route( 'get', $uri, $callback );
	}
	
	public function post( $uri, $callback )
	{
		$this -> route( 'post', $uri, $callback );
	}

	private function route( $method, $uri, $callback )
	{
		$route = new Route( $method, $uri, $callback );
		if( $this -> compare( $route, $this -> request ) )
		{
			$this -> route = $route;
			$this -> request -> arguments = $this -> arguments();
		}
	}
	
	public function run()
	{
		ob_start();
		call_user_func( $this -> route -> callback, $this -> request );
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

		return true;
	}

	private function arguments()
	{
		$arguments = [];
		$route_uri = array_filter( explode( '/', $this -> route -> uri ) );
		$request_uri = array_filter( explode( '/', $this -> request -> uri ) );

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