<?php

session_start();
if (isset($_POST['email'])) {

    // Udana walidacja
    $wszystko_OK = true;


    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $ulica = $_POST['ulica'];
    $miasto = $_POST['miasto'];

    // Wyrażenie regularne sprawdza poprawność: imie, nazwisko, ulica, miasto
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

    //Sprawdź kod pocztowy
    $kod_pocztowy = $_POST['kod_pocztowy'];

    if (!preg_match('/^[0-9]{2}-?[0-9]{3}$/Du', $kod_pocztowy)) {
        $_SESSION['e_kod_pocztowy'] = "Nieprawidłowy kod pocztowy";
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

    //Sprawdź wykształcenie
    $wyksztalcenie = $_POST['wyksztalcenie'];

    if (($_POST['wyksztalcenie']) == "null") {
        $wszystko_OK = false;
        $_SESSION['e_wyksztalcenie'] = "Wybierz wykształcenie";
    }

    //Sprawdź zainteresowania














    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            //Czy email już istnieje?
            $rezultat = $polaczenie->query("SELECT id FROM pierwsza WHERE email='$email'");

            if (!$rezultat) throw new Exception($polaczenie->error);

            $ile_takich_maili = $rezultat->num_rows;
            if ($ile_takich_maili > 0) {
                $wszystko_OK = false;
                $_SESSION['e_email'] = "Istnieje już konto przypisne do tego adresu e-mail!";
            }

            //Czy nick jest już zarezerwowany?
            $rezultat = $polaczenie->query("SELECT id FROM pierwsza WHERE login='$login'");

            if (!$rezultat) throw new Exception($polaczenie->error);

            $ile_takich_nickow = $rezultat->num_rows;
            if ($ile_takich_nickow > 0) {
                $wszystko_OK = false;
                $_SESSION['e_nick'] = "Istnieje już taki login! Wybierz inny.";
            }

            if ($wszystko_OK == true) {

                //Wszystko OK dodaje użytkownika
                if ($polaczenie->query("INSERT INTO pierwsza VALUES (NULL, '$imie', '$nazwisko', '$login', '$haslo_hash', '$email', '$ulica', '$nr_domu', '$nr_mieszkania', '$kod_pocztowy', '$miasto', '$wyksztalcenie', 'NULL')")) {
                    $_SESSION['udanarejestracja'] = true;
                    header('Location: witamy.php');
                } else {
                    throw new Exception($polaczenie->error);
                }
            }

            $polaczenie->close();
        }
    } catch (Exception $e) {
        echo '<span class="error">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie.</span>';
        echo '<br>Informacja deweloperska: ' . $e;
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
        <?php
        if (isset($_SESSION['e_nr_domu'])) {
            echo '<div class="error">' . $_SESSION['e_nr_domu'] . '</div>';
            unset($_SESSION['e_nr_domu']);
        }
        ?>
        <br> <input type="text" name=nr_mieszkania placeholder="nr mieszkania">
        <?php
        if (isset($_SESSION['e_nr_mieszkania'])) {
            echo '<div class="error">' . $_SESSION['e_nr_mieszkania'] . '</div>';
            unset($_SESSION['e_nr_mieszkania']);
        }
        ?>
        <br> <input type="text" name="kod_pocztowy" placeholder="kod pocztowy">
        <?php
        if (isset($_SESSION['e_kod_pocztowy'])) {
            echo '<div class="error">' . $_SESSION['e_kod_pocztowy'] . '</div>';
            unset($_SESSION['e_kod_pocztowy']);
        }
        ?>
        <br> <input type="text" name="miasto" placeholder="miasto"> <br>
        <?php
        if (isset($_SESSION['e_miasto'])) {
            echo '<div class="error">' . $_SESSION['e_miasto'] . '</div>';
            unset($_SESSION['e_miasto']);
        }
        ?>
        Wykształcenie: <br>
        <select name="wyksztalcenie">
            <option value="null">wybierz</option>
            <option value="wyzsze">wyższe</option>
            <option value="srednie">średnie</option>
            <option value="podstawowe">podstawowe</option>
        </select> <br>
        <?php
        if (isset($_SESSION['e_wyksztalcenie'])) {
            echo '<div class="error">' . $_SESSION['e_wyksztalcenie'] . '</div>';
            unset($_SESSION['e_wyksztalcenie']);
        }
        ?>
        Zainteresowania: <br>
        <label><input type="checkbox" name="zainteresowania[]" value="zeglarstwo">żeglarstwo</label> <br>
        <label><input type="checkbox" name="zainteresowania[]" value="kolarstwo">kolarstwo</label> <br>
        <label><input type="checkbox" name="zainteresowania[]" value="programowanie">programowanie</label> <br>
        <label><input type="checkbox" name="zainteresowania[]" value="komputery">komputery</label> <br>
        <label><input type="checkbox" name="zainteresowania[]" value="wspinaczka">wspinaczka</label> <br>
        <input type="submit" value="Zarejestruj się">


    </form>
</body>

</html>