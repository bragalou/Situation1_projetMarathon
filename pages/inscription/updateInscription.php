<main class="container" style="position: relative;">

    <?php
    if ($_SESSION['connected'] === "0" || !isset($_SESSION)) {
        header("Location: ?page=logIn");
    }

    $ActualIDRunner = htmlspecialchars($_GET['id']);

    // on récupère le runner sélectionné
    $assos = array(
        'id' => $ActualIDRunner
    );
    list($retourSelectRunners, $nmbSelectRunner) = $bd->BDqueryAssos("SELECT * FROM projetMarathon.runner, projetMarathon.run WHERE runner.id_runner = :id AND runner.id_runner = run.id_runner", $assos);

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
        list($retourRace, $nmb) = $bd->BDqueryAssos($requette, $assos);
    } else {
        list($retourRace, $nmb) = $bd->BDquery($requette);
    }


    // Modification de la personne et des courses
    $_SESSION['addInscription']['erreur'] = false;
    if (isset($_POST['submit'])) {
        if (!empty($_POST['firstname']) && !empty($_POST['name']) && !empty($_POST['gender']) && !empty($_POST['date'])) {
            if (!empty($_POST['choixRun'])) {
                $name = htmlspecialchars($_POST['name']);
                $firstname = htmlspecialchars($_POST['firstname']);
                $gender = htmlspecialchars($_POST['gender']);
                $date = htmlspecialchars($_POST['date']);
                $choixRuns = ($_POST['choixRun']);

                // ----- modification du runner
                $requette;
                if (!empty($_POST['publicResult'])) {
                    $requette = "UPDATE projetMarathon.runner SET `fristname_runner` = :firstname, `name_runner` = :name, `gender_runner` = :gender, `dateBirth_runner` = :date, `displayResult_runner` = '1' WHERE `runner`.`id_runner` = :id";
                } else {
                    $requette = "UPDATE projetMarathon.runner SET `fristname_runner` = :firstname, `name_runner` = :name, `gender_runner` = :gender, `dateBirth_runner` = :date, `displayResult_runner` = '0' WHERE `runner`.`id_runner` = :id";
                }
                $assos = array(
                    'id' => $ActualIDRunner,
                    'firstname' => $firstname,
                    'name' => $name,
                    'gender' => $gender,
                    'date' => $date,
                    // 'dateRegister' => date('Y-m-d'),
                    // 'dateRegister' => $retourSelectRunners['0']['dateRegister'],
                );
                list($retour, $nmb) = $bd->BDqueryAssos($requette, $assos);



                // ----- modification du runner à une course
                $assos = array(
                    'id' => $ActualIDRunner
                );
                list($retour, $nmb) = $bd->BDqueryAssos('DELETE FROM projetMarathon.run WHERE run.id_runner = :id', $assos);

                foreach ($choixRuns as $choixRun) {
                    $requette = "INSERT INTO projetMarathon.run (`id_race`, `id_runner`, `time_run`) VALUES (:idRace, :idRunner, NULL);";
                    $assos = array(
                        'idRace' => htmlspecialchars($choixRun),
                        'idRunner' => $ActualIDRunner
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
        <h2>Modifier une inscription</h2>
        <h3 class="font-italic">Inscription de : <?= $retourSelectRunners['0']['fristname_runner'] . ' ' . $retourSelectRunners['0']['name_runner']?></h3>

        <a href="?page=mesInscription" class="arrowBack"><i class="fa-solid fa-arrow-left-long"></i></a>
    </div>

    <form class="" method="POST" style="display: flex; flex-direction: row; justify-content: center; width: 100%;">
        <div class="form-group col-md-4" style="margin-right: 20px; padding-right: 20px; border-right: 1px solid;">
            <div class="form-group ">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" placeholder="Prénom" name="firstname" value="<?= $retourSelectRunners['0']['fristname_runner'] ?>">
            </div>
            <div class="form-group ">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" placeholder="Nom" name="name" value="<?= $retourSelectRunners['0']['name_runner'] ?>">
            </div>
            <div class="form-group  ">
                <label for="inputState">Genre</label>
                <select id="inputState" class="form-control" name="gender">
                    <option>Genre...</option>
                    <option <?= $retourSelectRunners['0']['gender_runner'] === 'masculin' ? 'selected' : '' ?> value="masculin">Masculin</option>
                    <option <?= $retourSelectRunners['0']['gender_runner'] === 'feminin' ? 'selected' : '' ?> value="feminin">Féminin</option>
                </select>
            </div>
            <div class="form-group ">
                <label for="inputCity">Date de naissance</label>
                <input type="date" class="form-control" id="inputCity" name="date" value="<?= $retourSelectRunners['0']['dateBirth_runner'] ?>">
            </div>
            <div class="form-group ">
                <div class="form-check">
                    <input <?= $retourSelectRunners['0']['displayResult_runner'] === '1' ? 'checked' : '' ?> class="form-check-input" type="checkbox" id="publicResult" name="publicResult">
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
                    if (!empty($retourRace)) :
                        foreach ($retourRace as $element) :
                            $assos = array(
                                'idelement' => $element['id_race']
                            );
                            list($parcours, $nmbP) = $bd->BDqueryAssos("SELECT * FROM projetMarathon.track WHERE track.id_race = :idelement", $assos);
                    ?>

                            <tr>
                                <td class="coloneCentrer text-center">
                                    <input <?php
                                            foreach ($retourSelectRunners as $retourSelectRunner) {
                                                if ($retourSelectRunner['id_race'] === $element['id_race']) {
                                                    echo 'checked';
                                                }
                                            }
                                            ?> type="checkbox" name="choixRun[]" value="<?= $element['id_race'] ?>">
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