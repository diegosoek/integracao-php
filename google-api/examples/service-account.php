<?php
/*
 * Copyright 2013 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

include_once __DIR__ . '/../vendor/autoload.php';
include_once "templates/base.php";

echo pageHeader("Service Account Access");

/************************************************
  Make an API request authenticated with a service
  account.
 ************************************************/

$client = new Google_Client();

/************************************************
  ATTENTION: Fill in these values, or make sure you
  have set the GOOGLE_APPLICATION_CREDENTIALS
  environment variable. You can get these credentials
  by creating a new Service Account in the
  API console. Be sure to store the key file
  somewhere you can get to it - though in real
  operations you'd want to make sure it wasn't
  accessible from the webserver!
  Make sure the Books API is enabled on this
  account as well, or the call will fail.
 ************************************************/

if ($credentials_file = checkServiceAccountCredentialsFile()) {
  // set the location manually
  $client->setAuthConfig($credentials_file);
} elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
  // use the application default credentials
  $client->useApplicationDefaultCredentials();
} else {
  echo missingServiceAccountDetailsWarning();
  return;
}

$client->setApplicationName("Client_Library_Examples");
$client->setScopes(['https://www.googleapis.com/auth/admin.directory.user',
    'https://www.googleapis.com/auth/admin.directory.group']);
$client->setSubject("diego@gedu.demo.mestra.org");

$service = new Google_Service_Directory($client);

/**
 * Create the user
 */
$nameInstance = new Google_Service_Directory_UserName();
$nameInstance -> setGivenName('John');
$nameInstance -> setFamilyName('Doe');
$email = 'paulofaia@gedu.demo.mestra.org';
$password = '123123123123';
$userInstance = new Google_Service_Directory_User();
$userInstance -> setName($nameInstance);
$userInstance -> setHashFunction("MD5");
$userInstance -> setPrimaryEmail($email);
$userInstance -> setPassword(hash("md5", $password));
try
{
  $createUserResult = $service->users->insert($userInstance);
  var_dump($createUserResult);
}
catch (Google_IO_Exception $gioe)
{
  echo "Error in connection: ".$gioe->getMessage();
}
catch (Google_Service_Exception $gse)
{
  echo "User already exists: ".$gse->getMessage();
}
/**
 * If you want it, add the user to a group
 */
$memberInstance = new Google_Service_Directory_Member();
$memberInstance->setEmail($email);
$memberInstance->setRole('MEMBER');
$memberInstance->setType('USER');
try
{
  $insertMembersResult = $service->members->insert('grupoteste@gedu.demo.mestra.org', $memberInstance);
}
catch (Google_IO_Exception $gioe)
{
  echo "Error in connection: ".$gioe->getMessage();
}

pageFooter(__FILE__);
