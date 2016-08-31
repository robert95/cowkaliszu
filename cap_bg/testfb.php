<?php
require_once '/fb/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '1660825800824875',
  'app_secret' => '10ef45d00ba67f754f7bfd2c0291bf9d',
  'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes']; // optional
$loginUrl = $helper->getLoginUrl('http://co.wkaliszu.pl/testfb.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
 
 ?>