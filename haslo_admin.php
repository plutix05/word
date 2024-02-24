<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
    header('Location: zaloguj-sie');
    exit();
}

if (isset($_POST['haslo1'])) {
    $id = $_GET['id'];
    $wszystko_OK = true;

    $haslo1 = $_POST['haslo1'];
    $haslo2 = $_POST['haslo2'];
    $pat_mal = '/^(?=.*[a-z]).+$/';
    $pat_duz = '/^(?=.*[A-Z]).+$/';
    $pat_cyf = '/^(?=.*\d).+$/';
    $pat_zna = '/^^(?=.*[\W_]).+$/';
    $pat_spa = '/^(?!.*\s).+$/';

    if ((strlen($haslo1) < 8)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo1'] = "Hasło musi posiadać minimalnie 8 znaków";
    } elseif ((strlen($haslo1) > 20)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo1'] = "Hasło musi posiadać maksymalnie 20 znaków";
    } elseif (!preg_match($pat_mal, $haslo1)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo1'] = "Hasło musi posiadać przynajmniej 1 małą literę";
    } elseif (!preg_match($pat_duz, $haslo1)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo1'] = "Hasło musi posiadać przynajmniej 1 wielką literę";
    } elseif (!preg_match($pat_cyf, $haslo1)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo1'] = "Hasło musi posiadać przynajmniej 1 cyfrę";
    } elseif (!preg_match($pat_zna, $haslo1)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo1'] = "Hasło musi posiadać przynajmniej 1 dowolny znak specjalny";
    } elseif (!preg_match($pat_spa, $haslo1)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo1'] = "Hasło nie może zawierać spacji";
    } else {
        $_SESSION['c_haslo1'] = "Hasło jest poprawne";
    }

    if ($haslo1 != $haslo2) {
        $wszystko_OK = false;
        $_SESSION['e_haslo2'] = "Hasła nie są identyczne";
    } else {
        $_SESSION['c_haslo2'] = "Hasła są identyczne";
    }

    $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $pol = new mysqli($host, $db_user, $db_password, $db_name);

        if ($pol->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {

            if ($wszystko_OK == true) {
                if ($pol->query("UPDATE uzytkownicy SET pass = '$haslo_hash' where id_user = $id")) {
                    $_SESSION['udanazmianahasla'] = true;
                    header('Location: haslo-zmienione');
                } else {
                    throw new Exception($polaczenie->error);
                }
            }

            $pol->close();
        }
    } catch (Exception $e) {
        echo "Błąd serwera" . $e;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WORD - Zatwierdzono administratora</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="container">
        <header>Wojewódzki Ośrodek Ruchu Drogowego</header>
        <nav>
            <ol class="navol">
                <li><a href="panel-administracyjny">Konto</a></li>
                <li><a href="wpisz-wynik-egzaminu">Egzaminy</a></li>
                <li><a href="lista-uzytkownikow">Użytkownicy</a></li>
                <li><a href="logout.php">Wyloguj się</a></li>
            </ol>
        </nav>
        <section>

            <form method="post">
                <h1>Zmiana hasła:</h1>
                <label for="haslo1">Hasło: </label><br />
                <input type="password" value="<?php
                                                if (isset($_SESSION['fr_haslo1']))
                                                    echo $_SESSION['fr_haslo1'];
                                                unset($_SESSION['fr_haslo1']);
                                                ?>" name="haslo1" /> <br />
                <?php
                if (isset($_SESSION['e_haslo1'])) {
                    echo '<div class="error">' . $_SESSION['e_haslo1'] . '</div>';
                    unset($_SESSION['e_haslo1']);
                } elseif (isset($_SESSION['c_haslo1'])) {
                    echo '<div class="correct">' . $_SESSION['c_haslo1'] . '</div>';
                    unset($_SESSION['c_haslo1']);
                }
                ?>
                <label for="haslo2">Powtórz hasło:</label><br />
                <input type="password" value="<?php
                                                if (isset($_SESSION['fr_haslo2']))
                                                    echo $_SESSION['fr_haslo2'];
                                                unset($_SESSION['fr_haslo2']);
                                                ?>" name="haslo2" /> <br />
                <?php
                if (isset($_SESSION['e_haslo2'])) {
                    echo '<div class="error">' . $_SESSION['e_haslo2'] . '</div>';
                    unset($_SESSION['e_haslo2']);
                } elseif (isset($_SESSION['c_haslo2'])) {
                    echo '<div class="correct">' . $_SESSION['c_haslo2'] . '</div>';
                    unset($_SESSION['c_haslo2']);
                } ?>
                <input type="submit" value="Zmień hasło">

            </form>
        </section>
    </div>
</body>
<script src="timer.js"></script>

</html>