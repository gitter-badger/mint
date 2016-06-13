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
}