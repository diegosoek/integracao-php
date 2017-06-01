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

include_once __DIR__ . '/../google-api/vendor/autoload.php';
include_once "../templates/base.php";

echo pageHeader("Service Account Access");

/************************************************
  Make an API request authenticated with a service
  account.
 ************************************************/

if(isset($_POST["givenName"])){

  $client = new Google_Client();

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

  $impersonate_user = getImpersonateUsers();

  $client->setApplicationName("Client_Library_Examples");
  $client->setScopes(['https://www.googleapis.com/auth/admin.directory.user']);
  $client->setSubject("$impersonate_user");

  $service = new Google_Service_Directory($client);

  /**
   * Create the user
   */
  $nameInstance = new Google_Service_Directory_UserName();
  $nameInstance -> setGivenName($_POST["givenName"]);
  $nameInstance -> setFamilyName($_POST["familyName"]);
  $email = $_POST["primaryEmail"];
  $password = $_POST["password"];
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
}

?>
<form method="POST">
  <input type="text" name="givenName" placeholder="Nome" required/>
  <input type="text" name="familyName" placeholder="Sobrenome" required/>
  <input type="email" name="primaryEmail" placeholder="Email" required/>
  <input type="text" name="password" placeholder="Senha" required/>
  <button>Enviar</button>
</form>
<?php
pageFooter(__FILE__);
?>