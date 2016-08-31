<?php

include_once 'fb/src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '1660825800824875',
  'app_secret' => '10ef45d00ba67f754f7bfd2c0291bf9d',
  'default_graph_version' => 'v2.5',
]);


$expires = "123123123";
// Sets the default fallback access token so we don't have to pass it to each request
$fb->setDefaultAccessToken($expires);

try {
  $response = $fb->get('/me');
  $userNode = $response->getGraphUser();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

echo 'Logged in as ' . $userNode->getName();
 
 ?>