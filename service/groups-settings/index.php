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

if(isset($_POST["group_id"])){
  
    $group_id = $_POST["group_id"];
  
	$client = getGoogleService();
	$service = new Google_Service_Groupssettings($client);
	
	// Prints the group's settings.
	$optParams = array(
	  'alt' => 'json'
	);
	try {
	  $group = $service->groups->get($group_id, $optParams);
	} catch (Google_Service_Exception $e) {
	  if ($e->getCode() == 404) {
		printf('Group not found: %s', $group_id);
		exit();
	  } else {
		throw $e;
	  }
	}
	$group->setAllowExternalMembers(true);
	$group = $service->groups->update($group_id, $group);
	var_dump($group);
  
  }
  
?>
<form method="POST">
	<input type="text" name="group_id" placeholder="Group ID" required/>
	<button>Enviar</button>
</form>
<?php
pageFooter(__FILE__);
?>
