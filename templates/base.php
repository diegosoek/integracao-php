<?php
 // Start the session (for storing access tokens and things)
if (!headers_sent()) {
  session_start();
}
/* Ad hoc functions to make the examples marginally prettier.*/
function isWebRequest()
{
  return isset($_SERVER['HTTP_USER_AGENT']);
}

function pageHeader($title)
{
  $ret = "<!doctype html>
  <html>
  <head>
    <title>" . $title . "</title>
    <link href='/styles/style.css' rel='stylesheet' type='text/css' />
  </head>
  <body>\n";
  if ($_SERVER['PHP_SELF'] != "/index.php") {
    $ret .= "<p><a href='index.php'>Back</a></p>";
  }
  $ret .= "<header><h1>" . $title . "</h1></header>";

  return $ret;
}


function pageFooter($file = null)
{
  $ret = "";
  if ($file) {
    $ret .= "<h3>Code:</h3>";
    $ret .= "<pre class='code'>";
    $ret .= htmlspecialchars(file_get_contents($file));
    $ret .= "</pre>";
  }
  $ret .= "</html>";

  return $ret;
}

function missingApiKeyWarning()
{
  $ret = "
    <h3 class='warn'>
      Warning: You need to set a Simple API Access key from the
      <a href='http://developers.google.com/console'>Google API console</a>
    </h3>";

  return $ret;
}

function missingClientSecretsWarning()
{
  $ret = "
    <h3 class='warn'>
      Warning: You need to set Client ID, Client Secret and Redirect URI from the
      <a href='http://developers.google.com/console'>Google API console</a>
    </h3>";

  return $ret;
}

function missingServiceAccountDetailsWarning()
{
  $ret = "
    <h3 class='warn'>
      Warning: You need download your Service Account Credentials JSON from the
      <a href='http://developers.google.com/console'>Google API console</a>.
    </h3>
    <p>
      Once downloaded, move them into the root directory of this repository and
      rename them 'service-account-credentials.json'.
    </p>
    <p>
      In your application, you should set the GOOGLE_APPLICATION_CREDENTIALS environment variable
      as the path to this file, but in the context of this example we will do this for you.
    </p>";

  return $ret;
}

function missingOAuth2CredentialsWarning()
{
  $ret = "
    <h3 class='warn'>
      Warning: You need to set the location of your OAuth2 Client Credentials from the
      <a href='http://developers.google.com/console'>Google API console</a>.
    </h3>
    <p>
      Once downloaded, move them into the root directory of this repository and
      rename them 'oauth-credentials.json'.
    </p>";

  return $ret;
}

function checkServiceAccountCredentialsFile()
{
  // service account creds
  $application_creds = __DIR__ . '/../google-api/service-account-credentials.json';

  return file_exists($application_creds) ? $application_creds : false;
}

function getOAuthCredentialsFile()
{
  // oauth2 creds
  $oauth_creds = __DIR__ . '/../../oauth-credentials.json';

  if (file_exists($oauth_creds)) {
    return $oauth_creds;
  }

  return false;
}

function setClientCredentialsFile($apiKey)
{
  $file = __DIR__ . '/../../.apiKey';
  file_put_contents($file, $apiKey);
}

function getApiKey()
{
  $file = __DIR__ . '/../../.apiKey';
  if (file_exists($file)) {
    return file_get_contents($file);
  }
}

function validateApiKeyService()
{
  $file = __DIR__ . '/../google-api/service-account-credentials.json';
  return file_exists($file);
}

function validateApiKeyClient()
{
  $file = __DIR__ . '/../google-api/client-account-credentials.json';
  return file_exists($file);
}

function setApiKey($apiKey)
{
  $file = __DIR__ . '/../../.apiKey';
  file_put_contents($file, $apiKey);
}

function getImpersonateUsers()
{
  $file = __DIR__ . '/../google-api/.impersonate';
  if (file_exists($file)) {
    return file_get_contents($file);
  }else{
    return false;
  }
}

function setImpersonateUsers($email)
{
  $file = __DIR__ . '/../google-api/.impersonate';
  file_put_contents($file, $email);
}

function getGoogleAuthUrl()
{
  $client = new Google_Client();
  $client->setAuthConfigFile(__DIR__ . '/../google-api/client-account-credentials.json');
  $client->setAccessType("offline");        // offline access
  $client->addScope(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/youtube', 'https://www.googleapis.com/auth/script.external_request', 'https://www.googleapis.com/auth/forms', 'https://www.googleapis.com/auth/spreadsheets']);
  $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/client/callback.php');
  return $client->createAuthUrl();
}

function getGoogleTokenFromCode(){
  $client = new Google_Client();
  $client->setAuthConfigFile(__DIR__ . '/../google-api/client-account-credentials.json');
  $client->addScope(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/youtube', 'https://www.googleapis.com/auth/script.external_request', 'https://www.googleapis.com/auth/forms', 'https://www.googleapis.com/auth/spreadsheets']);
  $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/client/callback.php');
  $jabacule = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  setAccessToken($jabacule);
  header('Location: /client');
}

function getGoogleClient(){
  $client = new Google_Client();
  $client->setAuthConfigFile(__DIR__ . '/../google-api/client-account-credentials.json');
  $client->setAccessToken($_SESSION["access_token"]);
  return $client;
}

function getGoogleService(){
  $client = new Google_Client();
  if ($credentials_file = checkServiceAccountCredentialsFile()) {
    $client->setAuthConfig($credentials_file);
  } elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
    $client->useApplicationDefaultCredentials();
  } else {
    echo missingServiceAccountDetailsWarning();
    return;
  }
  $impersonate_user = getImpersonateUsers();
  $client->setApplicationName("Client_Library_Examples");
  $client->setScopes(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/youtube']);
  $client->setSubject("$impersonate_user");
  return $client;
}

function getAccessToken()
{
  if(isset($_SESSION["access_token"]) && isset($_SESSION["access_token"]["access_token"])){
    return $_SESSION["access_token"];
  }
}

function setAccessToken($access_token)
{
  $_SESSION["access_token"] = $access_token;
}