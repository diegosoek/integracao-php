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

if(isset($_POST["script_id"])){

  $script_id = $_POST["script_id"];
  $function = $_POST["function"];

  $client = getGoogleClient();

  $service = new Google_Service_Script($client);

  $request = new Google_Service_Script_ExecutionRequest();
  $request->setFunction($function);
  //$request->setDevMode(true);
  if(isset($parameters)){
      $request->setParameters($parameters);
  }
  $response = null;

  try {
      $response = $service->scripts->run($script_id, $request);
      var_dump($response);
  } catch ( \Exception $e){
      var_dump($e);
  }

}

?>
<form method="POST">
  <input type="text" name="script_id" placeholder="Script ID" required/>
  <input type="text" name="function" placeholder="Function" required/>
  <button>Enviar</button>
</form>
<?php
pageFooter(__FILE__);
?>