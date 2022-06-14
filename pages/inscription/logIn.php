<main class="container" style="display : flex; flex-direction: column; align-items: center;">

    <?php
    $_SESSION['login']['erreur'] = false;

    if (isset($_POST['submit'])) {
        if (isset($_POST['identifiant']) && isset($_POST['password'])) {
            $identifiant = htmlspecialchars($_POST['identifiant']);
            $password = htmlspecialchars($_POST['password']);

            $assos = array(
                'identifiant' => $identifiant
            );
            // var_dump($_POST);
            list($retour, $nmb) = $bd->BDqueryAssos("SELECT * FROM projetMarathon.account WHERE login_account = :identifiant", $assos);
            if ($nmb === 1) {
                if (password_verify($password, $retour['0']['password_account'])) {
                    $_SESSION['connected'] = $retour[0]['id_account'];
                    $_SESSION['login']['erreur'] = false;
                    header("Location: ?page=mesInscription");
                } else {
                    $_SESSION['login']['erreur'] = "Identifiant ou mot de passe incorrect.";
                    // echo "connection fail";
                }
            } else {
                $_SESSION['login']['erreur'] = "Identifiant ou mot de passe incorrect.";
            }
        } else {
            $_SESSION['login']['erreur'] = "Obligatoire.";
        }
    }

    ?>

    <h2>Connection</h2>
    <h4 class="h3 mb-3 font-weight-normal" style="font-size: 18px;">Veuillez vous connecter</h4>

    <form method="post" class="col-lg-4" style=" display: flex; flex-direction: column;">
        <div class="form-group">
            <!-- <label for="identifiant">Identifiant : </label> -->
            <input type="text" class="form-control <?= $_SESSION['login']['erreur'] !== false ? 'is-invalid' : '' ?> " name="identifiant" id="identifiant" placeholder="Enter email">
            <?php if ($_SESSION['login']['erreur'] !== false) : ?>
                <div class="invalid-feedback">
                    <?= $_SESSION['login']['erreur'] ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <!-- <label for="password">Mot de passe :</label> -->
            <input type="password" class="form-control <?= $_SESSION['login']['erreur'] !== false ? 'is-invalid' : '' ?>" name="password" id="password" placeholder="Password">
            <?php if ($_SESSION['login']['erreur'] !== false) : ?>
                <div class="invalid-feedback">
                    <?= $_SESSION['login']['erreur'] ?>
                </div>
            <?php endif ?>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Se connecter</button>
        <a href="?page=logOn" class="btn btn-outline-secondary" style="margin-top: 20px;">S'inscrire</a>
    </form>

</main>