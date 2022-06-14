<main id="resulatDetail" class="container">
    <?php

    if (isset($_POST['recherche'])) {
        $_SESSION['resulatDetail']['recherche'] = $_POST['recherche'];
    }

    $ActualIDRace = htmlspecialchars($_GET['id']);

    // on récupère la race sélectionné
    $assos = array(
        'id' => $ActualIDRace
    );
    list($retourSelectRace, $nmbSelectRace) = $bd->BDqueryAssos("SELECT * FROM projetMarathon.race WHERE race.id_race = :id", $assos);


    //  on affiche la liste des participant de cette course
    $assos = array(
        'id' => $ActualIDRace
    );
    $requette = "SELECT * FROM projetMarathon.run, projetMarathon.runner WHERE run.id_runner = runner.id_runner AND run.id_race = :id AND runner.displayResult_runner = '1'";

    if (!empty($_SESSION['resulatDetail']['recherche'])) {
        $requette .= " AND (runner.fristname_runner LIKE :recherche OR runner.name_runner LIKE :recherche OR runner.id_runner LIKE :recherche)";
        $assos = array(
            'recherche' => "%" . htmlspecialchars($_SESSION['resulatDetail']['recherche']) . "%",
            'id' => $ActualIDRace
        );
    }

    list($retour, $nmb) = $bd->BDqueryAssos($requette, $assos);


    // // recherche et affiche
    // $requette = "SELECT * FROM projetMarathon.runner WHERE runner.id_account = 1";

    // if (isset($_POST['recherche'])) {
    //     $_SESSION['mesInscription']['recherche'] = $_POST['recherche'];
    // }

    // if (!empty($_SESSION['mesInscription']['recherche'])) {
    //     $requette .= " AND (runner.fristname_runner LIKE :recherche OR runner.name_runner LIKE :recherche OR runner.gender_runner LIKE :recherche OR runner.dateBirth_runner LIKE :recherche)";
    //     $assos = array(
    //         'recherche' => "%" . htmlspecialchars($_SESSION['mesInscription']['recherche']) . "%"
    //     );
    //     list($retour, $nmb) = $bd->BDqueryAssos($requette, $assos);
    // } else {
    //     list($retour, $nmb) = $bd->BDquery($requette);
    // }


    ?>

    <div class="" style="position: relative;">
        <h2>Resulat</h2>
        <h3 class="font-italic">Pour la course : <?= $retourSelectRace['0']['name_race'] ?></h3>

        <a href="?page=mainResultat" class="arrowBack"><i class="fa-solid fa-arrow-left-long"></i></a>
    </div>

    <div class="menuAction">
        <form class="formRechercher" method="post">
            <div class="form-inline">
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>
                    </div>
                    <input type="text" class="form-control form-control-rechercher" name="recherche" id="inputRechercher" placeholder="rechercher..." style="width: 292px;" value="<?= htmlspecialchars($_SESSION['resulatDetail']['recherche'] ?? null) ?>">
                </div>

                <button type="submit" class="btn btn-primary mb-2">Rechercher</button>
            </div>
        </form>
    </div>

    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col" class="coloneCentrer text-center" style="width: 80px;">N°</th>
                <th scope="col" class="coloneCentrer" style="width: 300px;">Prénom</th>
                <th scope="col" class="coloneCentrer" style="width: 300px;">Nom</th>
                <th scope="col" class="coloneCentrer text-center" style="width: 300px;">Temps</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($retour)) :
                foreach ($retour as $element) :
            ?>
                    <tr>
                        <th scope="row" class="coloneCentrer text-center"><?= $element['id_runner'] ?></th>
                        <td class="coloneCentrer"><?= $element['fristname_runner'] ?></td>
                        <td class="coloneCentrer"><?= $element['name_runner'] ?></td>
                        <td class="coloneCentrer text-center"><?= $element['time_run'] === null ? 'pas fini' : $element['time_run'] ?></td>
                    </tr>
                <?php
                endforeach;
            else :
                ?>
                <tr>
                    <td colspan="9" class="text-center">Vous n'avez incrit aucune personnes à aucune course</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>

</main>