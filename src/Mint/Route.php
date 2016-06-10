<?php

namespace Mint;

class Route
{
	public $uri;
	public $method;
	public $callback;

	function __construct( $method, $uri, $callback )
	{
		$this -> uri = dirname( $_SERVER['PHP_SELF'] ) . $uri;
		$this -> method = strtolower( $method );
		$this -> callback = $callback;
	}

	function compare( $method, $uri )
	{
		if( strcmp( $this -> method, strtolower( $method ) ) )
		{
			return false;
		}

		$route_uri_this = array_filter( explode( '/', $this -> uri ) );
		$route_uri_comp = array_filter( explode( '/', $uri ) );
		if( count( $route_uri_this ) != count( $route_uri_comp ) )
		{
			return false;
		}

		$route_uri_combine = array_combine( $route_uri_this, $route_uri_comp );
		foreach( $route_uri_combine as $key => $value )
		{
			if( strcmp( substr( $key, 0, 1 ), '$' ) && strcmp( $key, $value ) )
			{
				return false;
			}
		}

		return true;
	}

	function arguments( $uri )
	{
		$arguments = [];
		$uri_this = array_filter( explode( '/', $this -> uri ) );
		$uri_comp = array_filter( explode( '/', $uri ) );

		$uri_combine = array_combine( $uri_this, $uri_comp );
		foreach( $uri_combine as $key => $value )
		{
			if( !strcmp( substr( $key, 0, 1 ), '$') )
			{
				$arguments[ substr( $key, 1 ) ] = $value;
			}
		}

		return $arguments;
	}
}