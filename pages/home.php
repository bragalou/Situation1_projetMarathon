<main id="home" class="container">
    <?php

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
    ?>



    <h2>Liste des courses</h2>

    <!-- ===== rechercher ====== -->
    <form class="formRechercher" method="post">
        <div class="form-inline">
            <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>
                </div>
                <input type="text" class="form-control form-control-rechercher" name="recherche" id="inputRechercher" placeholder="rechercher..." style="width: 292px;" value="<?= htmlspecialchars($_SESSION['home']['recherche'] ?? null) ?>">
            </div>

            <button type="submit" class="btn btn-primary mb-2">Rechercher</button>
        </div>
    </form>


    <!-- ====== tableau liste des courses ====== -->
    <!-- <section id="nextRun"> -->
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col" class="coloneCentrer text-center" style="width: 50px;">#</th>
                <th scope="col" class="coloneCentrer">Nom</th>
                <th scope="col" class="coloneCentrer">Ville</th>
                <th scope="col" class="coloneCentrer">Date</th>
                <th scope="col" class="coloneCentrer">Parcours disponible</th>
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
                        <th scope="row" class="coloneCentrer text-center"><?= $element['id_race'] ?></th>
                        <td class="coloneCentrer"><?= $element['name_race'] ?></td>
                        <td class="coloneCentrer"><?= $element['city_race'] ?></td>
                        <td class="coloneCentrer"><?= $element['date_race'] ?></td>
                        <td class="coloneCentrer">
                            <?php
                            if (!empty($parcours)) :
                                foreach ($parcours as $parcour) : ?>
                                    <?= $parcour['distance_track'] ?>km
                                    <!-- <br> -->
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
</main>