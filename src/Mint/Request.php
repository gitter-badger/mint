<?php

namespace Mint;

class Request
{
	public $uri;
	public $method;

	public $arguments;

	function __construct()
	{
		$this -> uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
		$this -> method = strtolower( $_SERVER['REQUEST_METHOD'] );
	}
}