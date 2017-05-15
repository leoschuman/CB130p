<?php
/**
*
*		Customer360 Lab Application.
*
*		CB130p - Introduction to NoSQL Application Development (PHP)
*		Free online course teaching learners how to expose CRUD and N1QL Query operations via REST API.
*		https://training.couchbase.com/online
*
*		Code relies on:
*			Slim Framework (https://www.slimframework.com)
*			Composer (https://getcomposer.org)
*			Couchbase PHP SDK (https://github.com/couchbase/php-couchbase)
*
*/

// Site-wide utility methods

namespace App\Utils;
class Utilities
{
	private $settings;
	private $bucket;
	public function __construct($settings, $bucket)
	{
		$this->settings = $settings;
		$this->bucket = $bucket;
	}
	public function getCounterIncrement($docType = 'default')
	{
		$counterKey = $docType . '::counter';
		$counterDoc = $this->bucket->counter($counterKey, 1, ['initial' => 1]);
		return $counterDoc->value;
	}
}