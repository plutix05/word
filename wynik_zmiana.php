<?php
session_start();

if ((!isset($_SESSION['wynik_zmieniony']))) {
    header('Location: wpisz-wynik-egzaminu');
    exit();
} else {
    unset($_SESSION['wynik_zmieniony']);
}

// Usuwanie błędów zapisu
if (isset($_SESSION['e_wynik'])) {
    unset($_SESSION['e_wynik']);
}


// Usuwanie poprawnych wartości zapisu
if (isset($_SESSION['c_wynik'])) {
    unset($_SESSION['c_wynik']);
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WORD - Zapis ukończony!</title>
    <meta http-equiv="refresh" content="5,url='lista-egzaminow'">
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
            <form>
                <p>
                    Udało się poprawnie zapisać na egazmin teoretyczny! Za <span id="timer">5</span> sek. zostaniesz przeniesiony spowrotem do listy egzaminów. Jeżeli tak się nie stało kliknij w <a href="lista-egzaminow">ten link</a>
                </p>
            </form>
        </section>
    </div>
</body>
<script src="timer.js"></script>

</html>