<?php

namespace Mint;

class Session
{
	function __construct()
	{
		session_start();
	}

	public function get( $key )
	{
		return $_SESSION[ $key ];
	}

	public function set( $key, $value )
	{
		return $_SESSION[ $key ] = $value;
	}

	public function del( $key )
	{
		unset( $_SESSION[ $key ] );
	}
	
	public function des()
	{
		session_destroy();
	}
}