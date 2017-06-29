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

if(isset($_POST["title"])){

  $client = getGoogleService();

  $youtube = new Google_Service_YouTube($client);
  /**
   * Create the user
   */
  // Create an object for the liveBroadcast resource's snippet. Specify values
  // for the snippet's title, scheduled start time, and scheduled end time.
  $broadcastSnippet = new Google_Service_YouTube_LiveBroadcastSnippet();
  $broadcastSnippet->setTitle($_POST["title"]);
  $broadcastSnippet->setScheduledStartTime('2017-07-01T00:00:00-03:00');
  $broadcastSnippet->setScheduledEndTime('2017-07-01T00:00:00-03:00');

  // Create an object for the liveBroadcast resource's status, and set the
  // broadcast's status to "private".
  $status = new Google_Service_YouTube_LiveBroadcastStatus();
  $status->setPrivacyStatus($_POST["privacy"]);

  // Create the API request that inserts the liveBroadcast resource.
  $broadcastInsert = new Google_Service_YouTube_LiveBroadcast();
  $broadcastInsert->setSnippet($broadcastSnippet);
  $broadcastInsert->setStatus($status);
  $broadcastInsert->setKind('youtube#liveBroadcast');

  // Execute the request and return an object that contains information
  // about the new broadcast.
  $broadcastsResponse = $youtube->liveBroadcasts->insert('snippet,status', $broadcastInsert, array());

  // Create an object for the liveStream resource's snippet. Specify a value
  // for the snippet's title.
  $streamSnippet = new Google_Service_YouTube_LiveStreamSnippet();
  $streamSnippet->setTitle($_POST["title"]);

  // Create an object for content distribution network details for the live
  // stream and specify the stream's format and ingestion type.
  $cdn = new Google_Service_YouTube_CdnSettings();
  $cdn->setFormat("360p");
  $cdn->setIngestionType('rtmp');

  // Create the API request that inserts the liveStream resource.
  $streamInsert = new Google_Service_YouTube_LiveStream();
  $streamInsert->setSnippet($streamSnippet);
  $streamInsert->setCdn($cdn);
  $streamInsert->setKind('youtube#liveStream');

  // Execute the request and return an object that contains information
  // about the new stream.
  $streamsResponse = $youtube->liveStreams->insert('snippet,cdn', $streamInsert, array());
  var_dump($bindBroadcastResponse);
  // Bind the broadcast to the live stream.
  $bindBroadcastResponse = $youtube->liveBroadcasts->bind(
      $broadcastsResponse['id'],'id,contentDetails',
      array(
          'streamId' => $streamsResponse['id'],
      ));

  header('Location: /service/youtube-live/');
}

?>
<form method="POST">
  <input type="text" name="title" placeholder="Titulo" required/>
  <select name="privacy" placeholder="Privacidade">
    <option value="public">Público</option>
    <option value="public">Não listado (not work)</option>
    <option value="private">Privado</option>
  </select>
  <button>Enviar</button>
</form>
<?php
pageFooter(__FILE__);
?>