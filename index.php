<?php

session_start();

if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true)) {
    header('Location: konto.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="main.css">
    <title>Strona główna</title>
</head>

<body>

    

    <a href="rejestracja.php">Rejestracja - załóż darmowe konto!</a>
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