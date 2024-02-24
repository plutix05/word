<?php
session_start();

if ((!isset($_SESSION['udanazmianahasla']))) {
    header('Location: zmiana-hasla');
    exit();
} else {
    unset($_SESSION['udanazmianahasla']);
}

if (isset($_SESSION['fr_haslo1'])) {
    unset($_SESSION['fr_haslo1']);
}

if (isset($_SESSION['fr_haslo2'])) {
    unset($_SESSION['fr_haslo2']);
}
if (isset($_SESSION['e_haslo1'])) {
    unset($_SESSION['e_haslo1']);
}

if (isset($_SESSION['e_haslo2'])) {
    unset($_SESSION['e_haslo2']);
}

if (isset($_SESSION['c_haslo1'])) {
    unset($_SESSION['c_haslo1']);
}

if (isset($_SESSION['c_haslo2'])) {
    unset($_SESSION['c_haslo2']);
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WORD - Hasło zostało zmienione!</title>
    <meta http-equiv="refresh" content="5,url='lista-uzytkownikow'">
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
            <form>
                <p>
                    Hasło zostało zmienione! Za <span id="timer">5</span> sek. zostaniesz przeniesiony do listy użytkowników. Jeżeli tak się nie stało kliknij w <a href="zaloguj-sie">ten link</a>
                </p>
            </form>
        </section>
    </div>
</body>
<script src="timer.js"></script>

</html>