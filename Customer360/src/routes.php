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

// Routes

// QUERY (get, query) documents by billingAddress.city value
$app->get('/api/customer/city/{city}', function($request, $response, $args) {
	$query = CouchbaseN1qlQuery::fromString('SELECT * FROM Customer360 WHERE billingAddress.city = $city ');
	$query->namedParams([
		'city' => $args['city']
	]);
	try {
		$result = $this->bucket->query($query);
	} catch (Exception $e) {
		throw new Exception($e->getMessage());
	}
	return $response->getBody()->write(json_encode($result->rows));
});

// DELETE (delete, remove) documents by document ID
$app->delete('/api/customer/{id}', function($request, $response, $args) {
	$docType = $request->getAttribute('docType');
	$docId = $docType . '::' . $args['id'];
	try {
		$result = $this->bucket->remove($docId);		
	} catch (Exception $e) {
		throw new Exception($e->getMessage());
	}
	return $response->getBody()->write($docId . ' deleted');
});

// CREATE (put, replace/upsert) or UPDATE (put, replace/upsert) documents by document ID
$app->put('/api/customer/{id}', function($request, $response, $args) {
	$docType = $request->getAttribute('docType');
	$jsonBody = $request->getParsedBody();
	$jsonBody['type'] = $docType;
	$docId = $docType . '::' . $args['id'];
	try {
		// $result = $this->bucket->replace($docId, $jsonBody);
		$result = $this->bucket->upsert($docId, $jsonBody);
	} catch (Exception $e) {
		throw new Exception($e->getMessage());
	}
	return $response->getBody()->write($docId . ' upserted/replaced');
});

// READ (get, get[]) a list of document IDs passed on the URI
$app->get('/api/customer[/{params:.*}]', function($request, $response, $args) {
	$docType = $request->getAttribute('docType');
	$docIds = explode('/', $request->getAttribute('params'));
	$docIds = preg_filter('/^/', $docType . '::', $docIds);
	try {
		$document = $this->bucket->get($docIds);
		$result = json_encode($document);
	} catch (Exception $e) {
		throw new Exception($e->getMessage());
	}
	return $response->getBody()->write($result);
});

// CREATE (post, insert) a new document based on username, type prefix, and unique suffix
$app->post('/api/customer', function($request, $response, $args) {
	$docType = $request->getAttribute('docType');
	$jsonBody = $request->getParsedBody();
	$jsonBody['type'] = $docType;
	// $counterDoc = $this->bucket->counter('customer::counter', 1, ['initial' => 1]);
	// $counterVal = $counterDoc->value;
	$counterVal = $this->utilities->getCounterIncrement();
	$docId = $jsonBody['username'] . '-' . $counterVal;
	$docId = $docType . '::' . $docId;
	try {
		$result = $this->bucket->insert($docId, $jsonBody);
	} catch (Exception $e) {
		throw new Exception($e->getMessage());
	}
	return $response->getBody()->write($docId . ' inserted');	
});

// READ (get, get) a document by its document ID
$app->get('/api/customer/{id}', function($request, $response, $args) {
  $docId = $args['id'];
  try {
    $result = $this->bucket->get($docId);
    $result = json_encode($result->value);
  } catch (Exception $e) {
    throw new Exception($e->getMessage());
  }
  return $response->getBody()->write($result);
});

// GET the PHP info page
$app->get('/api/info', function($request, $response, $args){
	return $response->getBody()->write(phpinfo());
});

/*$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/
