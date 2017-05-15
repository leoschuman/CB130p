<?php
/**
*
*   Customer360 Lab Application.
*
*   CB130p - Introduction to NoSQL Application Development (PHP)
*   Free online course teaching learners how to expose CRUD and N1QL Query operations via REST API.
*   https://training.couchbase.com/online
*
*   Code relies on:
*     Slim Framework (https://www.slimframework.com)
*     Composer (https://getcomposer.org)
*     Couchbase PHP SDK (https://github.com/couchbase/php-couchbase)
*
*/

// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Assemble Bucket reference for route injection
$container['bucket'] = function ($c) {
  $settings = $c->get('settings')['couchbase'];
  $cluster = new CouchbaseCluster($settings['bootstrapUri']);
  $bucket = $cluster->openBucket($settings['bucketName'], $settings['password']);
  return $bucket;
};

// Assemble Utilities references for route injection
$container['utilities'] = function ($c) {
    $settings = $c->get('settings');
    $bucket = $c['bucket'];
    $utilities = new App\Utils\Utilities($settings, $bucket);
    return $utilities;
};