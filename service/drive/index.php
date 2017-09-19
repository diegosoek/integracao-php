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

include_once __DIR__ . '/../../google-api/vendor/autoload.php';
include_once "../../templates/base.php";

echo pageHeader("Service Account Access");

/************************************************
  Make an API request authenticated with a service
  account.
 ************************************************/

$client = getGoogleService();
$service = new Google_Service_Drive($client);

/**
 * Create the user
 */
$optParams = array(
  'pageSize' => 10,
  'fields' => 'nextPageToken, files(id, name)'
);
try
{
  $results = $service->files->listFiles($optParams);
}
catch (Google_IO_Exception $gioe)
{
  echo "Error in connection: ".$gioe->getMessage();
}
catch (Google_Service_Exception $gse)
{
  echo "User already exists: ".$gse->getMessage();
}

echo("<a href='create-user.php'>Adicionar usuário</a>");

if(isset($results)){
  foreach ($results as $result){
    echO("<div>" . $result->id . " - " . $result->name . "<a href='/service/get_file.php?id=" . $result->id . "'>Abrir arquivo</a></div>");
  }
}

pageFooter(__FILE__);
