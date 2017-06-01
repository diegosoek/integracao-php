<?php
include_once "../../templates/base.php";
include_once __DIR__ . '/../../google-api/vendor/autoload.php';

echo pageHeader("Service Account Access");

$client = getGoogleClient();

$service = new Google_Service_YouTube($client);
$results = null;

  try {
    // Execute an API request that lists the streams owned by the user who
    // authorized the request.
    $results = $service->liveStreams->listLiveStreams('id,snippet', array(
        'mine' => 'true',
    ));


  } catch (Google_Service_Exception $e) {
    echo('<p>A service error occurred: <code>' . htmlspecialchars($e->getMessage()) . '</code></p>');
  } catch (Google_Exception $e) {
    echo('<p>An client error occurred: <code>' . htmlspecialchars($e->getMessage()) . '</code></p>');
  }

echo("<a href='create-live.php'>Adicionar live</a>");
echo("<h3>Live Streams</h3>");
if(isset($results)){
  echo("<ul>");
  foreach ($results['items'] as $streamItem) {
    echo("<li>" . $streamItem['snippet']['title'] . " (" . $streamItem['id'] . ")</li>");
  }
  echo('</ul>');
}

pageFooter(__FILE__);
