<?php

// Verify these credentials match the Security Account created in Lab 1
$bucketName = "Customer360";
$password = "password";

$cluster = new CouchbaseCluster("couchbase://127.0.0.1");
$bucket = $cluster->openBucket($bucketName, $password);

// Create or update the customer::aaa123 document
echo "Storing customer::aaa123 : ";
$result = $bucket->upsert("customer::aaa123", array(
    "email" => "aalto123@test.com",
    "cart" => array("XY123", "AB234")
  ));
var_dump($result);

echo("<hr>");

// Read and display the customer::aaa123 document
echo "Retrieving customer::aaa123 : ";
$result = $bucket->get("customer::aaa123");
var_dump($result->value);