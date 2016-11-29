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

if(isset($_GET["id"])){

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
   * Get the user
   */
  try
  {
    $results = $service->users->get($_GET["id"]);
  }
  catch (Google_IO_Exception $gioe)
  {
    echo "Error in connection: ".$gioe->getMessage();
  }
  catch (Google_Service_Exception $gse)
  {
    echo "User already exists: ".$gse->getMessage();
  }
  print_r($results);
}

?>
<?php
pageFooter(__FILE__);
?>