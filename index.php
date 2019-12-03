<?php

session_start();
if (isset($_POST['email'])) {

    // Udana walidacja
    $wszystko_OK = true;

    
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $ulica = $_POST['ulica'];
    $miasto = $_POST['miasto'];

    // Wyrażenie regularne sprawdza poprawność: imie, nazwisko, ulicca, miasto
    $sprawdz = '/^[A-ZŁŚ]{1}+[a-ząęółśżźćń]+$/';

    // Sprawdź imie
    if (!preg_match($sprawdz, $imie)) {
        $wszystko_OK = false;
        $_SESSION['e_imie'] = "Nieprawidłowe imię";
    }

    // Sprawdź nazwisko
    if (!preg_match($sprawdz, $nazwisko)) {
        $wszystko_OK = false;
        $_SESSION['e_nazwisko'] = "Nieprawidłowe nazwisko";
    
    }
    // Sprawdź ulicę
    if (!preg_match($sprawdz, $ulica)) {
        $wszystko_OK = false;
        $_SESSION['e_ulica'] = "Nieprawidłowa ulica";
    }

    // Sprawdź miasto
    if (!preg_match($sprawdz, $miasto)) {
        $wszystko_OK = false;
        $_SESSION['e_miasto'] = "Nieprawidłowe miasto";
    }

    //Sprawdź numer domu i mieszkania
    $nr_domu = $_POST['nr_domu'];
    $nr_mieszkania = $_POST['nr_mieszkania'];

    //Sprawdzenie długości nr domu
    if (strlen($nr_domu) > 5) {

        $wszystko_OK = false;
        $_SESSION['e_nr_domu'] = "Nr domu nie może mieć więcej niż 5 znaków";
    }

    if (ctype_alnum($nr_domu) == false) {

        $wszystko_OK = false;
        $_SESSION['e_nr_domu'] = "Nr domu może składać się tylko z liter i cyfr (bez polskich znaków)";
    }

    //Sprawdzenie długości nr mieszkania
    if (strlen($nr_mieszkania) > 5) {

        $wszystko_OK = false;
        $_SESSION['e_nr_mieszkania'] = "Nr mieszkania nie może mieć więcej niż 5 znaków";
    }

    if (ctype_alnum($nr_mieszkania) == false) {

        $wszystko_OK = false;
        $_SESSION['e_nr_mieszkania'] = "Nr mieszkania może składać się tylko z liter i cyfr (bez polskich znaków)";
    }

    //Sprawdź login
    $login = $_POST['login'];

    //Sprawdzenie długości logina
    if ((strlen($login) < 3) || (strlen($login) > 20)) {

        $wszystko_OK = false;
        $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków";
    }

    if (ctype_alnum($login) == false) {

        $wszystko_OK = false;
        $_SESSION['e_login'] = "Login może składać się tylko z liter i cyfr (bez polskich znaków)";
    }

     //Sprawdź poprawność hasła
     $haslo1 = $_POST['haslo1'];
     $haslo2 = $_POST['haslo2'];
 
     if ((strlen($haslo1) < 8) || (strlen($haslo1) > 20)) {
         $wszystko_OK = false;
         $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków!";
     }
 
     if ($haslo1 != $haslo2) {
         $wszystko_OK = false;
         $_SESSION['e_haslo'] = "Hasła muszą być identyczne!";
     }
 
     $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

    //Sprawdź poprawność adresu email
    $email = $_POST['email'];
    $emailCheck = filter_var($email, FILTER_SANITIZE_EMAIL);
    if ((filter_var($emailCheck, FILTER_VALIDATE_EMAIL) == false) || ($emailCheck != $email)) {

        $wszystko_OK = false;
        $_SESSION['e_email'] = "Podaj poprawny adres e-mail";
    }


















    if ($wszystko_OK == true) {
        //Walidacja udana
        echo "Udana walidacja!";
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="main.css">
    <title>Formularz</title>
</head>

<body>
    <form method="post">
        Imię: <br> <input type="text" name="imie"> <br>
        <?php
        if (isset($_SESSION['e_imie'])) {
            echo '<div class="error">' . $_SESSION['e_imie'] . '</div>';
            unset($_SESSION['e_imie']);
        }
        ?>

        Nazwisko: <br> <input type="text" name="nazwisko"> <br>

        <?php
        if (isset($_SESSION['e_nazwisko'])) {
            echo '<div class="error">' . $_SESSION['e_nazwisko'] . '</div>';
            unset($_SESSION['e_nazwisko']);
        }
        ?>

        Login: <br> <input type="text" name="login"> <br>

        <?php
        if (isset($_SESSION['e_login'])) {
            echo '<div class="error">' . $_SESSION['e_login'] . '</div>';
            unset($_SESSION['e_login']);
        }
        ?>

        Twoje hasło: <br> <input type="password" name="haslo1"> <br>

        <?php
        if (isset($_SESSION['e_haslo'])) {
            echo '<div class="error">' . $_SESSION['e_haslo'] . '</div>';
            unset($_SESSION['e_haslo']);
        }
        ?>

        Powtórz hasło: <br> <input type="password" name="haslo2"> <br>
        E-mail: <br> <input type="text" name="email"> <br>

        <?php
        if (isset($_SESSION['e_email'])) {
            echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
            unset($_SESSION['e_email']);
        }
        ?>

        Adres: <br> <input type="text" name="ulica" placeholder="ulica">
        <?php
        if (isset($_SESSION['e_ulica'])) {
            echo '<div class="error">' . $_SESSION['e_ulica'] . '</div>';
            unset($_SESSION['e_ulica']);
        }
        ?>
        <br> <input type="text" name="nr_domu" placeholder="nr domu">
        <br> <input type="text" name=nr_mieszkania placeholder="nr mieszkania">
        <br> <input type="text" name="kodpocztowy" placeholder="kod pocztowy">
        <br> <input type="text" name="miasto" placeholder="miasto"> <br>
        <?php
        if (isset($_SESSION['e_miasto'])) {
            echo '<div class="error">' . $_SESSION['e_miasto'] . '</div>';
            unset($_SESSION['e_miasto']);
        }
        ?>
        Wykształcenie: <br>
        <select name="select">
            <option>wyższe</option>
            <option>średnie</option>
            <option>podstawowe</option>
        </select> <br>
        Zainteresowania: <br>
        <label><input type="checkbox" name="żeglarstwo">żeglarstwo</label> <br>
        <label><input type="checkbox" name="kolarstwo">kolarstwo</label> <br>
        <label><input type="checkbox" name="manga">manga</label> <br>
        <label><input type="checkbox" name="komputery">komputery</label> <br>
        <label><input type="checkbox" name="wspinaczka">wspinaczka</label> <br>
        <input type="submit" value="Zarejestruj się">


    </form>
</body>

</html>