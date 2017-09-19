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
include_once "../../templates/base.php";
include_once __DIR__ . '/../../google-api/vendor/autoload.php';

echo pageHeader("Service Account Access");

/************************************************
  Make an API request authenticated with a service
  account.
 ************************************************/

if(isset($_GET["id"])){

  $client = getGoogleClient();
  $broadcast_id = $_GET["id"];

  $youtube = new Google_Service_YouTube($client);
  
  $broadcastsResponse = $youtube->liveBroadcasts->delete($broadcast_id);
  
}
header('Location: /client/youtube-live/');
?>