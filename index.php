<?php
// début session
if (!session_id()) {
    session_start();
}

// gestion url pour redirection page
$page = (isset($_GET['page'])) ? htmlspecialchars($_GET['page']) : 'home';

// 
if (!isset($_SESSION['connected'])){
    $_SESSION['connected'] = "0";
}

// initialisation bd
require './database/BD.php';
$bd = new Bd();

// inclure les bandeau d'alerte
require './pages/addon/notication.php';
$notification = new Notification();
?>



<!DOCTYPE html>
<html lang="fr">

<?php require './pages/head.php'; ?>

<body>
    <?php
    require './pages/header.php';

    // if ($page === 'home') {
    //     require './pages/home.php';
    // } elseif ($page  === 'logIn') {
    //     require './pages/inscription/logIn.php';
    // } elseif ($page  === 'logOn') {
    //     require './pages/inscription/logOn.php';
    // } elseif ($page  === 'mesInscription') {
    //     require './pages/inscription/mesInscription.php';
    // } elseif ($page  === 'addInscription') {
    //     require './pages/inscription/addInscription.php';
    // } elseif ($page  === 'updateInscription') {
    //     require './pages/inscription/updateInscription.php.php';
    // } elseif ($page  === 'logOut') {
    //     require './pages/inscription/logOut.php';
    // } elseif ($page === 'resultat') {
    //     require './pages/resultat/resultat.php';
    // }

    switch ($page) {
        case 'home':
            require './pages/home.php';
            break;

        case 'logIn':
            require './pages/inscription/logIn.php';
            break;
        case 'logOn':
            require './pages/inscription/logOn.php';
            break;
        case 'mesInscription':
            require './pages/inscription/mesInscription.php';
            break;
        case 'addInscription':
            require './pages/inscription/addInscription.php';
            break;
        case 'updateInscription':
            require './pages/inscription/updateInscription.php';
            break;
        case 'logOut':
            require './pages/inscription/logOut.php';
            break;

        case 'mainResultat':
            require './pages/resultat/mainResultat.php';
            break;
        case 'resultat':
            require './pages/resultat/resultat.php';
            break;

        default:
            header("Location: ?page=home");
            break;
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(".date").datepicker({
            format: "yyyy/mm/dd",
        });
    </script>

</body>

</html>