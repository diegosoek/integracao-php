<?php include_once "../templates/base.php" ?>

<?php if (!isWebRequest()): ?>
  To view this example, run the following command from the root directory of this repository:

    php -S localhost:8080 -t examples/

  And then browse to "localhost:8080" in your web browser
<?php return ?>
<?php endif ?>

<?= pageHeader("PHP Library Examples"); ?>

<?php if (isset($_POST['user_email'])): ?>
<?php setImpersonateUsers($_POST['user_email']) ?>
<span class="warn">
  Usuário setado!
</span>
<?php endif ?>

<?php if (!validateApiKeyService() || !getImpersonateUsers()){ ?>
  <?php if (!validateApiKeyService()){ ?>
    <div class="api-key">
      <strong>Você precisa incluir o JSON com as chaves na pasta google-api</strong>
    </div>
  <?php } ?>
  <?php if (!getImpersonateUsers()){ ?>
    <div class="api-key">
      <strong>Usuário para impersonificar</strong>
      <form method="post">
        Email do usuário:<input type="text" name="user_email" />
        <input type="submit" />
      </form>
      <em>Para manipular os dados precisa ser um superadmin</em>
    </div>
  <?php } ?>
<?php } else { ?>
<ul>
  <li><a href="./drive">Google Drive</a></li>
  <li><a href="./users">Usuários</a></li>
  <li><a href="./youtube-live">Youtube Live</a></li>
  <li><a href="./groups-settings">Groups Settings</a></li>
</ul>
<?php } ?>


<?= pageFooter(); ?>
