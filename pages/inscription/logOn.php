<main class="container" style="display : flex; flex-direction: column; align-items: center;">

  <?php
  $_SESSION['logon']['erreur'] = false;

  if (isset($_POST['submit'])) {
    if (!empty($_POST['identifiant']) && !empty($_POST['password']) && !empty($_POST['rePassword'])) {
      $identifiant = htmlspecialchars($_POST['identifiant']);
      $password = htmlspecialchars($_POST['password']);
      $reRassword = htmlspecialchars($_POST['rePassword']);


      if ($password != $reRassword) {
        $notification->notificationRouge("les mots de passe ne sont pas identique.");
      } else {
        $assos = array(
          'identifiant' => $identifiant
        );
        list($retour, $nmb) = $bd->BDqueryAssos("SELECT * FROM projetmarathon.account WHERE login_account = :identifiant", $assos);

        if ($nmb === 1) {
          $notification->notificationRouge("l'identifiant existe déjà.");
        } else {

          $passwordHAsh = password_hash($password, PASSWORD_BCRYPT);

          $requetteInsert = "INSERT INTO projetMarathon.account (id_account, login_account, password_account) VALUES (NULL, :identifiant, :password);";
          $assosInsert = array(
            'identifiant' => $identifiant,
            'password' => $passwordHAsh
          );
          list($retour, $nmb) = $bd->BDqueryAssos($requetteInsert, $assosInsert);

          // redirection ici
          header("Location: ?page=logIn");
        }
      }
    } else {
      $notification->notificationRouge("Veuillez remplir tous les champs.");
    }
  }

  ?>

  <h2>Inscription</h2>

  <form method="post" class="col-lg-4" style=" display: flex; flex-direction: column;">
    <div class="form-group">
      <label for="identifiant">Identifiant : </label>
      <input type="text" class="form-control" name="identifiant" id="identifiant" placeholder="identifiant">
      <?php if ($_SESSION['logon']['erreur'] !== false) : ?>
        <div class="invalid-feedback">
          <?= $_SESSION['logon']['erreur'] ?>
        </div>
      <?php endif ?>
    </div>
    <div class="form-group">
      <label for="password">Mot de passe :</label>
      <input type="password" class="form-control" name="password" id="password" placeholder="Password">
      <?php if ($_SESSION['logon']['erreur'] !== false) : ?>
        <div class="invalid-feedback">
          <?= $_SESSION['logon']['erreur'] ?>
        </div>
      <?php endif ?>
    </div>
    <div class="form-group">
      <label for="rePassword">Confirmer le mot de passe :</label>
      <input type="password" class="form-control" name="rePassword" id="rePassword" placeholder="Password">
      <?php if ($_SESSION['logon']['erreur'] !== false) : ?>
        <div class="invalid-feedback">
          <?= $_SESSION['logon']['erreur'] ?>
        </div>
      <?php endif ?>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">S'inscrire</button>
    <a href="?page=logIn" class="btn btn-outline-secondary" style="margin-top: 20px;">Se connecter</a>
  </form>

</main>