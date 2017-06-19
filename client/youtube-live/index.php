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
<script src="https://apis.google.com/js/platform.js" async defer></script> 

<div id="placeholder-div"></div>

<script>
window.___gcfg = {
  lang: 'en-US'
};

(function() {
  var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
  po.src = 'https://apis.google.com/js/platform.js?onload=renderButton';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();

function renderButton(){
  gapi.hangout.render('placeholder-div', {
      'render': 'createhangout',
      'hangout_type'  :'onair',
      'initial_apps': [
        { app_id : '992304351793', start_data : 'bANs4Pq52ak', 'app_type' : 'ROOM_APP' }
      ]
    });
}
</script>
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
    print_r($streamItem);
    echo("<li>" . $streamItem['snippet']['title'] . " (" . $streamItem['id'] . ")</li>");
  }
  echo('</ul>');
}

pageFooter(__FILE__);
