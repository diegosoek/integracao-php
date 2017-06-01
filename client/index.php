<?php 
include_once __DIR__ . '/../google-api/vendor/autoload.php';
include_once "../templates/base.php" 
?>

<?php if (!isWebRequest()): ?>
  To view this example, run the following command from the root directory of this repository:

    php -S localhost:8080 -t examples/

  And then browse to "localhost:8080" in your web browser
<?php return ?>
<?php endif ?>

<?= pageHeader("PHP Library Examples"); ?>

<?php if (!getAccessToken()): ?>
<div class="api-key">
  <strong>You have not login token</strong>
  <a href="<?php echo(getGoogleAuthUrl()); ?>">Login with Google</a>
</div>
<?php else: ?>
<ul>
  <li><a href="./youtube-live">Youtube Live</a></li>
</ul>
<?php endif ?>


<?= pageFooter(); ?>
