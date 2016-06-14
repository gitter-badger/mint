<?php

namespace Mint;

class Vars
{
	private $vars;
	
	function __construct()
	{
		$this -> vars = [];
	}

	public function get( $key )
	{
		return $this -> vars[ $key ];
	}

	public function set( $key, $value )
	{
		return $this -> vars[ $key ] = $value;
	}

	public function del( $key )
	{
		unset( $this -> vars[ $key ] );
	}
}