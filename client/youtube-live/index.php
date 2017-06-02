<?php
include_once "../../templates/base.php";
include_once __DIR__ . '/../../google-api/vendor/autoload.php';

echo pageHeader("Service Account Access");

$client = getGoogleClient();

$youtube = new Google_Service_YouTube($client);
$results = null;

try {

  $results = $youtube->liveStreams->listLiveStreams('id,snippet', array(
      'mine' => 'true',
  ));

} catch (Google_Service_Exception $e) {
  echo('<p>A service error occurred: <code>' . htmlspecialchars($e->getMessage()) . '</code></p>');
} catch (Google_Exception $e) {
  echo('<p>An client error occurred: <code>' . htmlspecialchars($e->getMessage()) . '</code></p>');
}

echo("<a href='create-stream.php'>Adicionar live stream</a>");
echo("<h3>Live Streams</h3>");
if(isset($results)){
  echo("<ul>");
  foreach ($results['items'] as $streamItem) {
    echo("<li>" . $streamItem['snippet']['title'] . " (" . $streamItem['id'] . ")</li>");
  }
  echo('</ul>');
}

?>
<script src="https://apis.google.com/js/platform.js" async defer>
</script> 
<div class="g-hangout" data-render="createhangout"
     data-initial_apps="[{ app_id : '123456789012', start_data : 'trDBBNIzwJU', 'app_type' : 'ROOM_APP' }]"> 
 </div>
<?php

try {

  $results = $youtube->liveBroadcasts->listLiveBroadcasts('id,snippet,contentDetails', array(
      'mine' => 'true',
  ));

} catch (Google_Service_Exception $e) {
  echo('<p>A service error occurred: <code>' . htmlspecialchars($e->getMessage()) . '</code></p>');
} catch (Google_Exception $e) {
  echo('<p>An client error occurred: <code>' . htmlspecialchars($e->getMessage()) . '</code></p>');
}

echo("<a href='create-broadcast.php'>Adicionar live broadcast</a>");
echo("<h3>Live Broadcasts</h3>");
if(isset($results)){
  echo("<ul>");
  foreach ($results['items'] as $streamItem) {
    echo("<li>" . $streamItem['snippet']['title'] . " (" . $streamItem['id'] . ")</li>");
  }
  echo('</ul>');
}

pageFooter(__FILE__);
