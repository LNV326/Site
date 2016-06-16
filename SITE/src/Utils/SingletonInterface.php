<?php

namespace Utils;

interface SingletonInterface {
	/**
	 * Return an existing instance of class or create and return a new instance
	 * @return SingletonInterface
	 */
	public static function getInstance();
	/**
	 * Check if class already has an instance
	 * @return boolean
	 */
	public static function hasInstance();
}