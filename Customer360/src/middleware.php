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
*
*/

// Application middleware

// Pre-process all API requests to embed URI-specific type in request body
$app->add(function($request, $response, $next) {
	$uriArray = explode('/', $request->getUri()->getPath());
		if ($uriArray[1] == 'api') {
			$docType = $uriArray[2];
			$request = $request->withAttribute('docType', $docType);
		} else {
			throw new Exception('URI fails to match pattern: /api/[docType][/docId]');
		}
		$response = $next($request, $response);
		return $response;
});

