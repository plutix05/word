<?php
session_start();

$wszystko_OK = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status_pkk'])) {
    $status_pkk = $_POST['status_pkk'];
    $pkk_pattern = "/^[0-9]+$/";

    if (strlen($status_pkk) != 20) {
        $wszystko_OK = false;
        $_SESSION['e_s_pkk'] = "Numer PKK musi składać się z 20 cyfr";
    } elseif (preg_match($pkk_pattern, $status_pkk) == false) {
        $wszystko_OK = false;
        $_SESSION['e_s_pkk'] = "Nieprawidłowy numer PKK";
    } else {
        $_SESSION['status_pkk'] = $status_pkk;
        $_SESSION['st_wszystko_OK'] = $wszystko_OK;
    }

    $_SESSION['fr_status_pkk'] = $status_pkk;
} else {
    $wszystko_OK = false;
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WORD - Sprawdź status egzaminu</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="container">
        <header>Wojewódzki Ośrodek Ruchu Drogowego</header>
        <nav>
            <ol class="navol">
                <li><a href="zapisz-sie-na-egzamin-teoretyczny">Strona główna</a></li>
                <li><a href="status-egzaminu">Status</a></li>
                <li><a href="zaloguj-sie">Zaloguj się</a></li>
                <li><a href="zarejestruj-sie">Zarejestruj się</a></li>
                <li><a href="regulamin-serwisu">Regulamin</a></li>
            </ol>
        </nav>
        <section>
            <form method="post">
                <h2>
                    Sprawdź status swoich egzaminu:
                </h2>
                <label for="status_pkk">
                    Numer PKK
                </label><br />
                <input type="text" name="status_pkk" value="<?php
                                                            if (isset($_POST['status_pkk']))
                                                                echo $_SESSION['fr_status_pkk'];
                                                            unset($_SESSION['fr_status_pkk']);
                                                            ?>" /><br />
                <?php
                if (isset($_SESSION['e_s_pkk'])) {
                    echo "<div class='error'>" . $_SESSION['e_s_pkk'] . "</div>";
                    unset($_SESSION['e_s_pkk']);
                }
                ?>
                <input type="submit">
            </form>
            <table id="status">
                <?php
                require_once 'connect.php';
                mysqli_report(MYSQLI_REPORT_STRICT);

                try {
                    $pol = new mysqli($host, $db_user, $db_password, $db_name);
                    if ($wszystko_OK) {
                        if ($pol->connect_errno != 0) {
                            throw new Exception(mysqli_connect_errno());
                        } else {

                            if ($rezultat = $pol->query(
                                sprintf(
                                    "SELECT * FROM egzaminy WHERE nr_pkk='%s'",
                                    mysqli_real_escape_string($pol, $_SESSION['status_pkk'])
                                )
                            )) {
                                $ile_pkk = $rezultat->num_rows;
                                if ($ile_pkk > 0) {
                                    if ($wszystko_OK) {
                                        echo "
                                        <tr>
                                            <td>Numer PKK</td>
                                            <td>Część egzaminu</td>
                                            <td>Data</td>
                                            <td>Kategoria</td>
                                            <td>Wynik</td>
                                        </tr>";
                                    }
                                    while ($wiersz = $rezultat->fetch_assoc()) {
                                        echo "
                                            <tr>
                                                <td>$wiersz[nr_pkk]</td>
                                                <td>$wiersz[czesc_egz]</td>
                                                <td>$wiersz[data]</td>
                                                <td>$wiersz[kategoria]</td>
                                                <td>$wiersz[wynik]</td>
                                            </tr>";
                                    }
                                    unset($_SESSION['status_pkk']);
                                    unset($_SESSION['fr_status_pkk']);
                                    unset($_SESSION['blad']);
                                    $rezultat->free_result();
                                } else {
                                    $_SESSION['blad'] = 'Nie ma numeru PKK w bazie danych';
                                    echo "<span class='bladSerwera'>{$_SESSION['blad']}</span>";
                                }
                            } else {
                                throw new Exception($pol->error);
                            }

                            $pol->close();
                        }
                    }
                } catch (Exception $e) {
                    echo "<span style='color: red;'>Błąd serwera! Spróbuj ponownie później</span>";
                    //echo "<br />Informacja deweloperska: " . $e;
                }
                ?>
            </table>
        </section>
    </div>
</body>

</html>