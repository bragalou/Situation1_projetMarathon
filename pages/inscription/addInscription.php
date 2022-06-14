<main class="container" style="position: relative;">

    <?php
    if ($_SESSION['connected'] === "0" || !isset($_SESSION)) {
        header("Location: ?page=logIn");
    }


    // Afficher la lsite des courses
    $requette = "SELECT * FROM projetMarathon.race";
    // $requette = "SELECT DISTINCT race.id_race, race.name_race, race.date_race, race.address_race, race.postalCod_race, race.city_race FROM projetMarathon.race, projetMArathon.track";

    if (isset($_POST['recherche'])) {
        $_SESSION['home']['recherche'] = $_POST['recherche'];
    }

    if (!empty($_SESSION['home']['recherche'])) {
        $requette .= " WHERE race.name_race LIKE :recherche OR race.address_race LIKE :recherche OR race.city_race LIKE :recherche";
        $assos = array(
            'recherche' => "%" . htmlspecialchars($_SESSION['home']['recherche']) . "%"
        );
        list($retour, $nmb) = $bd->BDqueryAssos($requette, $assos);
    } else {
        list($retour, $nmb) = $bd->BDquery($requette);
    }


    // Ajouter une personnes et inscription à une course
    $_SESSION['addInscription']['erreur'] = false;
    if (isset($_POST['submit'])) {
        if (!empty($_POST['firstname']) && !empty($_POST['name']) && !empty($_POST['gender']) && !empty($_POST['date'])) {
            if (!empty($_POST['choixRun'])) {
                $name = htmlspecialchars($_POST['name']);
                $firstname = htmlspecialchars($_POST['firstname']);
                $gender = htmlspecialchars($_POST['gender']);
                $date = htmlspecialchars($_POST['date']);
                $choixRuns = ($_POST['choixRun']);

                // ----- Ajout du runner
                $requette;
                if (!empty($_POST['publicResult'])) {
                    $requette = "INSERT INTO projetMarathon.runner (`id_runner`, `fristname_runner`, `name_runner`, `gender_runner`, `dateBirth_runner`, `dateRegistration_runner`, `displayResult_runner`, `id_account`) VALUES (NULL, :firstname, :name, :gender, :date, :dateRegister, '1', :idAccount)";
                } else {
                    $requette = "INSERT INTO projetMarathon.runner (`id_runner`, `fristname_runner`, `name_runner`, `gender_runner`, `dateBirth_runner`, `dateRegistration_runner`, `displayResult_runner`, `id_account`) VALUES (NULL, :firstname, :name, :gender, :date, :dateRegister, '0', :idAccount)";
                }
                $assos = array(
                    'firstname' => $firstname,
                    'name' => $name,
                    'gender' => $gender,
                    'date' => $date,
                    'dateRegister' => date('Y-m-d'),
                    'idAccount' => $_SESSION['connected']
                );
                list($retour, $nmb) = $bd->BDqueryAssos($requette, $assos);


                // ------ Récupérer le dernier runner
                $requette = "SELECT * FROM projetMarathon.runner WHERE runner.id_account = :idAccount ORDER BY runner.id_runner DESC LIMIT 1";
                $assos = array(
                    'idAccount' => $_SESSION['connected']
                );
                list($retourRunnerAdd, $nmb) = $bd->BDqueryAssos($requette, $assos);


                // ----- Ajout du runner à une course
                foreach ($choixRuns as $choixRun) {                    
                    $requette = "INSERT INTO projetMarathon.run (`id_race`, `id_runner`, `time_run`) VALUES (:idRace, :idRunner, NULL);";
                    $assos = array(
                        'idRace' => htmlspecialchars($choixRun),
                        'idRunner' => $retourRunnerAdd[0]['id_runner']
                    );
                    list($retour, $nmb) = $bd->BDqueryAssos($requette, $assos);
                }


                header("Location: ?page=mesInscription");
            } else {
                $notification->notificationRouge("Veuillez sélectionner une course.");
            }
        } else {
            $notification->notificationRouge("Veuillez remplir tous les champs.");
        }
    }


    ?>

    <div class="" style="position: relative;">
        <h2>Inscrire une nouvelle personne</h2>
        <a href="?page=mesInscription" class="arrowBack"><i class="fa-solid fa-arrow-left-long"></i></a>
    </div>

    <form class="" method="POST" style="display: flex; flex-direction: row; justify-content: center; width: 100%;">
        <div class="form-group col-md-4" style="margin-right: 20px; padding-right: 20px; border-right: 1px solid;">
            <div class="form-group ">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" placeholder="Prénom" name="firstname">
            </div>
            <div class="form-group ">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" placeholder="Nom" name="name">
            </div>
            <div class="form-group  ">
                <label for="inputState">Genre</label>
                <select id="inputState" class="form-control" name="gender">
                    <option selected>Genre...</option>
                    <option value="masculin">Masculin</option>
                    <option value="feminin">Féminin</option>
                </select>
            </div>
            <div class="form-group ">
                <label for="inputCity">Date de naissance</label>
                <input type="date" class="form-control" id="inputCity" name="date">
            </div>
            <div class="form-group ">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="publicResult" name="publicResult">
                    <label class="form-check-label" for="publicResult">résultat publique</label>
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">Ajouter</button>
        </div>

        <div class="form-group col-md-8">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="coloneCentrer"></th>
                        <th scope="col" class="coloneCentrer text-center" style="width: 50px;">#</th>
                        <th scope="col" class="coloneCentrer">Nom</th>
                        <th scope="col" class="coloneCentrer">Ville</th>
                        <th scope="col" class="coloneCentrer">Date</th>
                        <th scope="col" class="coloneCentrer">Parcours</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($retour)) :
                        foreach ($retour as $element) :
                            $assos = array(
                                'idelement' => $element['id_race']
                            );
                            list($parcours, $nmbP) = $bd->BDqueryAssos("SELECT * FROM projetMarathon.track WHERE track.id_race = :idelement", $assos);
                    ?>

                            <tr>
                                <td class="coloneCentrer text-center">
                                        <input type="checkbox" name="choixRun[]" value="<?= $element['id_race'] ?>">
                                </td>
                                <th scope="row" class="coloneCentrer text-center"><?= $element['id_race'] ?></th>
                                <td class="coloneCentrer"><?= $element['name_race'] ?></td>
                                <td class="coloneCentrer"><?= $element['city_race'] ?></td>
                                <td class="coloneCentrer"><?= $element['date_race'] ?></td>
                                <td class="coloneCentrer">
                                    <?php
                                    if (!empty($parcours)) :
                                        foreach ($parcours as $parcour) : ?>
                                            <?= $parcour['distance_track'] ?>km
                                            <br>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p style="color: red;">Aucune</p>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    else :
                        ?>
                        <tr>
                            <td colspan="9" class="text-center">Aucune donnée enregistré</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

    </form>

</main>