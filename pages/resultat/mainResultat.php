<main id="resultat" class="container">

    <?php
    if (isset($_POST['recherche'])) {
        $_SESSION['resultat']['recherche'] = $_POST['recherche'];
    }


    $requette = "SELECT * FROM projetMarathon.race";

    if (!empty($_SESSION['resultat']['recherche'])) {
        $requette .= " WHERE race.name_race LIKE :recherche OR race.address_race LIKE :recherche OR race.city_race LIKE :recherche";
        $assos = array(
            'recherche' => "%" . htmlspecialchars($_SESSION['resultat']['recherche']) . "%"
        );
        list($retour, $nmb) = $bd->BDqueryAssos($requette, $assos);
    } else {
        list($retour, $nmb) = $bd->BDquery($requette);
    }
    ?>



    <h2>Résultat</h2>
    <form action="" method="post" class="formRechercher">
        <div class="form-inline">
            <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>
                </div>
                <input type="search" class="form-control form-control-rechercher" name="recherche" id="inputRechercher" placeholder="rechercher une course..." style="width: 292px;" value="<?= htmlspecialchars($_SESSION['resultat']['recherche'] ?? null) ?>">
            </div>

            <button type="submit" class="btn btn-primary mb-2">Rechercher</button>
        </div>
    </form>


    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col" class="coloneCentrer text-center" style="width: 50px;">#</th>
                <th scope="col" class="coloneCentrer">Nom</th>
                <th scope="col" class="coloneCentrer">Ville</th>
                <th scope="col" class="coloneCentrer">Date</th>
                <th scope="col" class="coloneCentrer">Parcours disponible</th>
                <th scope="col" class="coloneCentrer text-center"><i class="fa-solid fa-info"></i></th>
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
                        <td class="coloneCentrer" style="display:flex; justify-content: center; align-items: center;">
                            <a href="?page=resultat&id=<?= $element['id_race'] ?>" class="engrenage btn btn-secondary" style="margin-right: 5px;"><i class="fa-solid fa-info"></i></a>
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