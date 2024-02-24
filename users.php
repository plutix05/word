<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
    header('Location: zaloguj-sie');
    exit();
}

?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WORD - Lista użytkowników</title>
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
                <table id="status">
                    <h1>
                        <p id="statusP">Lista użytkowników</p>
                    </h1>
                    <tr class="trHeader">
                        <th>Login</th>
                        <th>Hasło</th>
                        <th>Stan konta</th>
                        <th>Usuń konto</th>
                    </tr>
                    <?php
                    require_once "connect.php";

                    $pol = new mysqli($host, $db_user, $db_password, $db_name);

                    // Sprawdź, czy udało się połączyć z bazą danych
                    if ($pol->connect_error) {
                        die("Connection failed: " . $pol->connect_error);
                    }

                    $zap = "SELECT * FROM uzytkownicy";
                    $rez = mysqli_query($pol, $zap);

                    // Sprawdź, czy zapytanie zostało wykonane poprawnie
                    if ($rez) {
                        while ($wiersz = $rez->fetch_assoc()) {
                            echo "
                                <tr>
                                    <td>{$wiersz['user']}</td>
                                    <td><a href='zmiana-hasla-admin?id={$wiersz['id_user']}'>Resetuj hasło</a></td>
                                    <td>";
                            if ($wiersz['confirm'] == "Niepotwierdzony") {
                                echo "<a href='zatwierdzanie-konta?id={$wiersz['id_user']}'>Zatwierdź konto</a>";
                            } elseif ($wiersz['confirm'] == "Potwierdzony") {
                                echo "Potwierdzony";
                            } else {
                                echo "<span class='error'>Błąd pobierania danych</span>";
                            }
                            echo "</td>
                                <td><a href='usuwanie-konta?id={$wiersz['id_user']}'>Usuń konto</a></td>
                                </tr>
                            ";
                        }
                    } else {
                        echo "Błąd zapytania: " . mysqli_error($pol);
                    }

                    // Zamknij połączenie poza pętlą while
                    $pol->close();
                    ?>
                </table>
            </div>
        </section>
    </div>
</body>

</html>