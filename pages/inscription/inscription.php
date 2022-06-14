<main class="container">

    <?php echo
    $_SESSION['erreur']['login'] = false;

    if (isset($_POST['submit'])) {

        if (isset($_POST['identifiant']) && isset($_POST['password'])) {
            $identifiant = htmlspecialchars($_POST['identifiant']);
            $password = htmlspecialchars($_POST['password']);

            $assos = array(
                'identifiant' => $identifiant
            );
            list($retour, $nmb) = $bd->BDqueryAssos("SELECT * FROM projetmarathon.account WHERE login_account = :identifiant", $assos);

            if ($nmb === 1) {
                if (password_verify($password, $retour['0']['password_account'])) {
                    header("Location: ?page=home");
                    $_SESSION['erreur']['login'] = false;
                } else {
                    $_SESSION['erreur']['login'] = "Identifiant ou mot de passe incorrecte.";
                }
            } else {
                $_SESSION['erreur']['login'] = "Identifiant ou mot de passe incorrecte.";
            }
        } else {
            $_SESSION['erreur']['login'] = "Obligatoire.";
        }
    }
    ?>


    <h2>inscription</h2>
    
    <div class="formualireDauthentification">
        <p>Veuillez-vous connecter <br> à votre compte.</p>

        <form action="" method="POST">
            <div>
                <label for="identifiant">Identifiant :</label>
                <input class="form-control <?= $_SESSION['erreur']['login'] !== false ? 'is-invalid' : '' ?>" type="text" id="identifiant" name="identifiant" placeholder="" required>
                <?php if ($_SESSION['erreur']['login'] !== false) : ?>
                    <div class="invalid-feedback" style="padding-top: 0;">
                        <?= $_SESSION['erreur']['login'] ?>
                    </div>
                <?php endif ?>
            </div>

            <div>
                <label for="password">Mot de passe :</label>
                <input class="form-control <?= $_SESSION['erreur']['login'] !== false ? 'is-invalid' : '' ?>" type="password" id="password" name="password" required>
                <?php if ($_SESSION['erreur']['login'] !== false) : ?>
                    <div class="invalid-feedback" style="padding-top: 0;">
                        <?= $_SESSION['erreur']['login'] ?>
                    </div>
                <?php endif ?>

            </div>

            <div class="envoie">
                <input class="envoieForm" type="submit" name="submit" value="Connection">
            </div>
        </form>
    </div>

</main>