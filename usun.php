<?php
session_start();

if (!isset($_SESSION['zalogowany'])) {
    header('Location: zaloguj-sie');
    exit();
}

require_once "connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);
error_reporting(E_ALL ^ E_WARNING); // Removes warnings

try {
    $pol = new mysqli($host, $db_user, $db_password, $db_name);

    if ($pol->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    }

    $id = $_GET['id'];

    $sql1 = "SELECT id_user, user, confirm from uzytkownicy where id_user=$id and confirm='Niepotwierdzony'";
    $rez1 = $pol->query($sql1);

    if (!$rez1) {
        throw new Exception($pol->error);
    }

    $sql2 = "DELETE FROM uzytkownicy where id_user=$id";
    $rez2 = $pol->query($sql2);

    if (!$rez2) {
        $_SESSION['message'] = "Błąd usuwania, spróbuj ponownie później";
        throw new Exception($pol->error);
    } else {
        $_SESSION['message'] = "Konto zostało usunięte";
    }
} catch (Exception $e) {
    echo "Błąd:" . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WORD - Usuwanie konta</title>
    <link rel="stylesheet" href="style.css" />
    <meta http-equiv="refresh" content="5,url='lista-uzytkownikow'">
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
            <div class="nonForm">
                <p>
                    <?php if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    } ?>
                </p>
                <p>
                    Za <span id="timer">5</span> sek. zostaniesz przeniesiony do listy użytkowników. Jeśli tak się nie stanie kliknij <a href="lista-uzytkownikow">ten link</a>.
                </p>
            </div>
        </section>
    </div>
</body>
<script src="timer.js"></script>

</html>