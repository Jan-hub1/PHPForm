<?php

session_start();

if ((!isset($_SESSION['udanarejestracja']))) {
    header('Location: index.php');
    exit();
}
else {
    unset($_SESSION['udanarejestracja']);
}


?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="main.css">
    <title>Udana rejestracja</title>
</head>

<body>

    Dziękujemy za rejestrację w serwisie! Możesz już zalogować się na swoje konto!<br><br>

    <a href="index.php">Załóż nowe konto</a>
    <br><br>

    <form action="zaloguj.php" method="post">

        Login: <br> <input type="text" name="login"> <br>
        Hasło: <br> <input type="password" name="haslo"> <br><br>
        <input type="submit" value="Zaloguj się">

    </form>

    <?php
    if (isset($_SESSION['blad'])) {

        echo $_SESSION['blad'];
    }
    ?>

</body>

</html>