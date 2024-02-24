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
                <h1>
                    Lista egzaminów
                </h1>
                <table id="status">
                    <tr class="trHeader">
                        <th>Numer PKK</th>
                        <th>Część egzaminu</th>
                        <th>Data egzaminu</th>
                        <th>Kategoria</th>
                        <th>Wynik</th>
                    </tr>
                    <?php
                    require_once "connect.php";

                    $pol = new mysqli($host, $db_user, $db_password, $db_name);

                    // Sprawdź, czy udało się połączyć z bazą danych
                    if ($pol->connect_error) {
                        die("Connection failed: " . $pol->connect_error);
                    }

                    $zap = "SELECT * FROM egzaminy";
                    $rez = mysqli_query($pol, $zap);

                    // Sprawdź, czy zapytanie zostało wykonane poprawnie
                    if ($rez) {
                        while ($wiersz = $rez->fetch_assoc()) {
                            echo "
                            <tr>
                                <td>$wiersz[nr_pkk]</td>
                                <td>$wiersz[czesc_egz]</td>
                                <td>$wiersz[data]</td>
                                <td>$wiersz[kategoria]</td>
                                <td>";
                            if ($wiersz['wynik'] == null) {
                                echo "<a href='wpisz-wynik?id={$wiersz['id_egz']}'>Wpisz wynik</a>";
                            } else {
                                echo $wiersz['wynik'];
                            }
                            echo "</td>
                                            </tr>";
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