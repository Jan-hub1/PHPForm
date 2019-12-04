<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
    header('Location: index.php');
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
    <title>Twoje konto</title>
</head>
<body>

<?php

echo "<p>Witaj ".$_SESSION['imie'].' '.$_SESSION['nazwisko'].'! [<a href="logout.php">Wyloguj się</a>]';
echo "<p><b>E-Mail</b>: ".$_SESSION['email'];
echo " | <b>Ulica</b>: ".$_SESSION['ulica'];
echo " ".$_SESSION['nr_domu'];
echo "/".$_SESSION['nr_mieszkania'];
echo " | <b>Kod pocztowy i miasto</b>: ".$_SESSION['kod_pocztowy'];
echo " ".$_SESSION['miasto'];
echo " | <b>Wykształcenie</b>: ".$_SESSION['wyksztalcenie'];
echo " | <b>Zainteresowania</b>: ".$_SESSION['zainteresowania'];



?>
    
</body>
</html>