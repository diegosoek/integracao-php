<?php include_once "../templates/base.php" ?>

<?php if (!isWebRequest()): ?>
  To view this example, run the following command from the root directory of this repository:

    php -S localhost:8080 -t examples/

  And then browse to "localhost:8080" in your web browser
<?php return ?>
<?php endif ?>

<?= pageHeader("PHP Library Examples"); ?>

<?php if (isset($_POST['api_key'])): ?>
<?php setApiKey($_POST['api_key']) ?>
<span class="warn">
  API Key set!
</span>
<?php endif ?>

<?php if (!getApiKey()): ?>
<div class="api-key">
  <strong>You have not entered your API key</strong>
  <form method="post">
    API Key:<input type="text" name="api_key" />
    <input type="submit" />
  </form>
  <em>This can be found in the <a href="http://developers.google.com/console" target="_blank">Google API Console</em>
</div>
<?php endif ?>

<ul>
  <li><a href="./youtube-live">Youtube Live</a></li>
</ul>

<?= pageFooter(); ?>
