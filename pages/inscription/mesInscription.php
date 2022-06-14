<main id="mesInscription" class="container">
    <?php
    if ($_SESSION['connected'] === "0") {
        header("Location: ?page=logIn");
    }

    if (isset($_POST['recherche'])) {
            $_SESSION['mesInscription']['recherche'] = $_POST['recherche'];
    }
    
    
    
    // recherche et affiche
    $requette = "SELECT * FROM projetMarathon.runner WHERE runner.id_account = :id";
    $assos = array(
        'id' => $_SESSION['connected']
    );
    
    
    if (!empty($_SESSION['mesInscription']['recherche'])) {
        $requette .= " AND (runner.fristname_runner LIKE :recherche OR runner.name_runner LIKE :recherche OR runner.gender_runner LIKE :recherche OR runner.dateBirth_runner LIKE :recherche)";
        $assos = array(
            'recherche' => "%" . htmlspecialchars($_SESSION['mesInscription']['recherche']) . "%",
            'id' => $_SESSION['connected']
        );
    }

    list($retour, $nmb) = $bd->BDqueryAssos($requette, $assos);


    // delete le runner voulu
    if (isset($_POST['deleteRunner'])) {
        $requetteDelete = "DELETE FROM projetMarathon.runner WHERE runner.id_runner = :idDeleteRunner";
        $requetteDeleteRelation = "DELETE FROM projetMArathon.run WHERE run.id_runner = :idDeleteRunner";
        $assosDelete = array(
            'idDeleteRunner' => htmlspecialchars($_POST['deleteRunner'])
        );
        list($retour, $nmb) = $bd->BDqueryAssos($requetteDeleteRelation, $assosDelete);
        list($retour, $nmb) = $bd->BDqueryAssos($requetteDelete, $assosDelete);
        header("Location: ?page=mesInscription");
    }

    ?>


    <h2>Mes inscriptions</h2>
    <h3 class="font-italic">La liste des personnes que vous avez inscrit.</h3>

    <div class="menuAction">
        <a href="?page=addInscription" class="btn btn-primary" style="margin-bottom: 0.5rem">Nouveau</a>
        <form class="formRechercher" method="post">
            <div class="form-inline">
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>
                    </div>
                    <input type="text" class="form-control form-control-rechercher" name="recherche" id="inputRechercher" placeholder="rechercher..." style="width: 292px;" value="<?= htmlspecialchars($_SESSION['mesInscription']['recherche'] ?? null) ?>">
                </div>

                <button type="submit" class="btn btn-primary mb-2">Rechercher</button>
            </div>
        </form>
    </div>

    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col" class="coloneCentrer text-center" style="width: 80px;">#</th>
                <th scope="col" class="coloneCentrer" style="width: 300px;">Prénom</th>
                <th scope="col" class="coloneCentrer" style="width: 300px;">Nom</th>
                <th scope="col" class="coloneCentrer" style="width: 300px;">Age</th>
                <th scope="col" class="coloneCentrer text-center"><i class="fas fa-cog"></i></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($retour)) :
                foreach ($retour as $element) :
                    $aujourdhui = date("Y-m-d");
                    $diff = date_diff(date_create($element['dateBirth_runner']), date_create($aujourdhui));
                    $age = $diff->format('%y');
            ?>
                    <tr>
                        <th scope="row" class="coloneCentrer text-center"><?= $element['id_runner'] ?></th>
                        <td class="coloneCentrer"><?= $element['fristname_runner'] ?></td>
                        <td class="coloneCentrer"><?= $element['name_runner'] ?></td>
                        <td class="coloneCentrer"><?= $age ?></td>
                        <td class="coloneCentrer" style="display:flex; justify-content: center; align-items: center;">
                            <a href="?page=updateInscription&id=<?= $element['id_runner'] ?>" class="engrenage btn btn-secondary" style="margin-right: 5px;"><i class="fas fa-cog"></i></a>

                            <form action="" method="post">
                                <input type="hidden" name="deleteRunner" value="<?= $element['id_runner'] ?>">
                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
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