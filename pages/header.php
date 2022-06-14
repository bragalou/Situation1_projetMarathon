<header>

    <?php  //var_dump($_SESSION['connected']); 
    ?>
    <nav>
        <ul>
            <li>
                <a href="?page=home" class="btnNavigation <?= $page === 'home' ? 'actif' : '' ?>"></i>
                    <p>Présentation</p>
                </a>
            </li>

            <!-- <li>
                <a href="?page=courses" class="btnNavigation <?= $page === 'courses' ? 'actif' : '' ?>"></i>
                    <p>Les courses</p>
                </a>

                <ul id="nav2">
                    <li>
                        <a href="?page=courseMarathon" class="btnNavigation <?= $page === 'home' ? 'actif' : '' ?>"></i>
                            <p>Marathon</p>
                        </a>
                        <a href="?page=courseSemi" class="btnNavigation <?= $page === 'home' ? 'actif' : '' ?>"></i>
                            <p>Semi-Marathon</p>
                        </a>
                        <a href="?page=courseRelais" class="btnNavigation <?= $page === 'home' ? 'actif' : '' ?>"></i>
                            <p>Relais</p>
                        </a>
                    </li>
                </ul>
            </li> -->

            <li>
                <a href="?page=mesInscription" class="btnNavigation <?= $page === 'inscription' || $page === 'mesInscription' || $page === 'addInscription' || $page === 'updateInscription' || $page === 'logIn' || $page === 'logOn' ? 'actif' : '' ?>"></i>
                    <p>Mes Inscription</p>
                </a>
            </li>

            <li>
                <a href="?page=mainResultat" class="btnNavigation <?= $page === 'resultat' || $page === 'mainResultat' ? 'actif' : '' ?>"></i>
                    <p>Résultat</p>
                </a>
            </li>
        </ul>
    </nav>
    <?php if ($_SESSION['connected'] !== "0") :  ?>
        <a id="btnLogOut" href="?page=logOut"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
    <?php endif ?>
</header>

<!-- <i class="fas fa-home"> -->