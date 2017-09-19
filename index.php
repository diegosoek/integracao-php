<?php include_once "templates/base.php" ?>

<?php if (!isWebRequest()): ?>
  To view this example, run the following command from the root directory of this repository:

    php -S localhost:8080 -t examples/

  And then browse to "localhost:8080" in your web browser
<?php return ?>
<?php endif ?>

<?= pageHeader("PHP Library Examples"); ?>

<ul>
  <li><a href="/service">Using service account</a></li>
  <li><a href="/client">Using client account</a></li>
</ul>

<?= pageFooter(); ?>
