<main class="container">

    <?php
    if($_SESSION['connected'] === "0"){
        header("Location: ?page=home");
    }

    $_SESSION['connected'] = "0";
    header("Location: ?page=logIn");
    ?>



</main>