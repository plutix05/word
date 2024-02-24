<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
    header('Location: zaloguj-sie');
    exit();
}

if (isset($_POST['wynik'])) {
    $id = $_GET['id'];

    $wszystko_OK = true;

    $wynik = $_POST['wynik'];
    if ($wynik == "null") {
        $wszystko_OK = false;
        $_SESSION['e_wynik'] = "Nie wybrano wyniku!";
    } else {
        $_SESSION['c_wynik'] = "Wynik wybrany";
    }

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

        $pol = new mysqli($host, $db_user, $db_password, $db_name);

        if ($pol->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {

            if ($wszystko_OK == true) {
                $sql = "UPDATE egzaminy SET wynik = '$wynik' where id_egz = $id";
                if ($pol->query($sql)) {
                    $_SESSION['wynik_zmieniony'] = true;
                    header('Location: wynik-zostal-zmieniony');
                } else {
                    throw new Exception($pol->error);
                }
            }
        }
    } catch (Exception $e) {
        echo '<span style="color:red;">Błąd serwera! Spróbuj ponownie później!</span>';
        echo '<br />Informacja developerska: ' . $e;
    }
}

?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WORD - Wpisz wynik egzaminu</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="container">
        <header>Wojewódzki Ośrodek Ruchu Drogowego</header>
        <nav>
            <ol class="navol">
                <li><a href="panel-administracyjny">Konto</a></li>
                <li><a href="lista-egzaminow">Egzaminy</a></li>
                <li><a href="lista-uzytkownikow">Użytkownicy</a></li>
                <li><a href="logout.php">Wyloguj się</a></li>
            </ol>
        </nav>
        <section>
            <div class="nonForm">
                <h1>
                    Wybierz wynik egzaminu
                </h1>
                <form method="post">
                    <select name="wynik">
                        <option value="null" selected> -- Wybierz wynik -- </option>
                        <option value="Pozytywny">Pozytywny</option>
                        <option value="Negatywny">Negatywny</option>
                    </select>
                    <?php
                    if (isset($_SESSION['e_wynik'])) {
                        echo '<div class="error">' . $_SESSION['e_wynik'] . '</div>';
                        unset($_SESSION['e_wynik']);
                    }

                    if (isset($_SESSION['c_wynik'])) {
                        echo '<div class="correct">' . $_SESSION['c_wynik'] . '</div>';
                        unset($_SESSION['c_wynik']);
                    }
                    ?>
                    <input type="submit" value="Prześlij wynik">
                </form>
            </div>
        </section>
    </div>
</body>

</html>