<?php

session_start();
//session_destroy();

require_once __DIR__.'/facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php';
require_once __DIR__.'/config.php';

$fb = new Facebook\Facebook([
  'app_id' => APPID, // Replace {app-id} with your app id
  'app_secret' => APPSECRET,
  'default_graph_version' => DEFAULTGRAPHVERSION,
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl($_SERVER[HTTP_REFERER].'fb.php', $permissions);
$logoutUrl = $_SERVER[HTTP_REFERER].'fbl.php';

echo '<a href="'.htmlspecialchars($loginUrl).'">Log in with Facebook!</a>';
echo '<br>';
echo '<a href="'.htmlspecialchars($logoutUrl).'">Log out with Facebook!</a>';
echo '<br>';

$fb->setDefaultAccessToken($_SESSION['fb_access_token']);

try {
    $response = $fb->get('/me');
    $userNode = $response->getGraphUser();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
  echo 'Graph returned an error: '.$e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
  echo 'Facebook SDK returned an error: '.$e->getMessage();
    exit;
}

echo 'Logged in as '.$userNode->getName();

$response = $fb->get('/spottedunitn/posts?limit=5');
$plainOldArray = $response->getDecodedBody();

print_r($plainOldArray);
