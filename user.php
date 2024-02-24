<?php
session_start();

if (isset($_POST['pkk'])) {

    $wszystko_OK = true;

    $pkk = $_POST['pkk'];
    $pkk_pattern = "/^[0-9]+$/";
    if (strlen($pkk) != 20) {
        $wszystko_OK = false;
        $_SESSION['e_pkk'] = "Numer PKK musi składać się z 20 cyfr";
    } elseif (preg_match($pkk_pattern, $pkk) == false) {
        $wszystko_OK = false;
        $_SESSION['e_pkk'] = "Podaj prawidłowy numer PKK";
    } else {
        $_SESSION['c_pkk'] = "Prawidłowy numer PKK";
    }

    $czesc_egz = $_POST['czesc_egz'];
    if ($czesc_egz == "null") {
        $wszystko_OK = false;
        $_SESSION['e_czesc'] = "Wybierz część";
    } else {
        $_SESSION['c_czesc'] = "Część wybrana";
    }

    $kategoria = $_POST['kategoria'];
    if ($kategoria == "null") {
        $wszystko_OK = false;
        $_SESSION['e_kategoria'] = "Wybierz kategorię";
    } else {
        $_SESSION['c_kategoria'] = "Kategoria wybrana";
    }

    $data = $_POST['data'];
    $teraz = date('Y-m-d\TH:i');
    if (!$data) {
        $wszystko_OK = false;
        $_SESSION['e_data'] = "Podaj datę egzaminu";
    } elseif ($data < $teraz) {
        $wszystko_OK = false;
        $_SESSION['e_data'] = "Data egzaminu nie może być w przeszłości";
    } else {
        $_SESSION['c_data'] = "Prawidłowa data";
    }


    $_SESSION['fr_pkk'] = $pkk;
    $_SESSION['fr_czesc'] = $czesc_egz;
    $_SESSION['fr_kategoria'] = $kategoria;
    $_SESSION['fr_data'] = $data;

    require_once "connect.php";

    $sekret = $secret_key;

    $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $sekret . '&response=' . $_POST['g-recaptcha-response']);

    $odpowiedz = json_decode($sprawdz);

    if ($odpowiedz->success == false) {
        $wszystko_OK = false;
        $_SESSION['e_bot'] = "Potwierdź, że nie jesteś botem";
    }
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {

            if ($wszystko_OK == true) {
                if ($polaczenie->query("INSERT INTO egzaminy VALUES(null, '$pkk', '$czesc_egz','$kategoria', '$data', null)")) {
                    $_SESSION['udany_zapis'] = true;
                    header('Location: udany-zapis-na-egzamin-teoretyczny');
                } else {
                    throw new Exception($polaczenie->error);
                }
            }
            $polaczenie->close();
        }
    } catch (Exception $e) {
        echo "<span style='color: red;'>Błąd serwera! Spróbuj ponownie później</span>";
        //echo "<br />Informacja deweloperska: " . $e;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WORD - Zarejestruj się na egzamin teoretyczny</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://www.google.com/recaptcha/api.js"></script>
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
                    Zapisz się na egzamin:
                </h2>
                <label for="pkk">Numer PKK:</label><br />
                <input type="text" name="pkk" value="<?php
                                                        if (isset($_SESSION['fr_pkk']))
                                                            echo $_SESSION['fr_pkk'];
                                                        unset($_SESSION['fr_pkk']);
                                                        ?>">
                <br />
                <?php
                if (isset($_SESSION['e_pkk'])) {
                    echo '<div class="error">' . $_SESSION['e_pkk'] . '</div>';
                    unset($_SESSION['e_pkk']);
                }

                if (isset($_SESSION['c_pkk'])) {
                    echo '<div class="correct">' . $_SESSION['c_pkk'] . '</div>';
                    unset($_SESSION['c_pkk']);
                }
                ?>
                <label for="czesc_egz">Część egzaminu:</label><br />
                <select name="czesc_egz">
                    <option value="null" selected <?php echo isset($_SESSION['fr_czesc']) &&        $_SESSION['fr_czesc'] == 'null' ? 'selected' : ''; ?>> -- Wybierz część -- </option>
                    <option value="Teoretyczna" <?php echo isset($_SESSION['fr_czesc']) && $_SESSION['fr_czesc'] == 'Teoretyczna' ? 'selected' : ''; ?>>Teoretyczna</option>
                    <option value="Praktyczna" <?php echo isset($_SESSION['fr_czesc']) && $_SESSION['fr_czesc'] == 'Praktyczna' ? 'selected' : ''; ?>>Praktyczna</option>
                </select> <br />

                <?php
                if (isset($_SESSION['e_czesc'])) {
                    echo '<div class="error">' . $_SESSION['e_czesc'] . '</div>';
                    unset($_SESSION['e_czesc']);
                }

                if (isset($_SESSION['c_czesc'])) {
                    echo '<div class="correct">' . $_SESSION['c_czesc'] . '</div>';
                    unset($_SESSION['c_czesc']);
                }
                ?>
                <label for="kategoria">Kategoria:</label><br />
                <select name="kategoria">
                    <option value="null" selected <?php
                                                    echo isset($_SESSION['fr_kategoria']) && $_SESSION['fr_kategoria'] == 'null' ? 'selected' : '';
                                                    ?>> -- Wybierz kategorię -- </option>
                    <option value="A" <?php
                                        echo isset($_SESSION['fr_kategoria']) && $_SESSION['fr_kategoria'] == 'A' ? 'selected' : '';
                                        ?>>A</option>
                    <option value="B" <?php
                                        echo isset($_SESSION['fr_kategoria']) && $_SESSION['fr_kategoria'] == 'B' ? 'selected' : '';
                                        ?>>B</option>
                    <option value="C" <?php
                                        echo isset($_SESSION['fr_kategoria']) && $_SESSION['fr_kategoria'] == 'C' ? 'selected' : '';
                                        ?>>C</option>
                    <option value="D" <?php
                                        echo isset($_SESSION['fr_kategoria']) && $_SESSION['fr_kategoria'] == 'D' ? 'selected' : '';
                                        ?>>D</option>
                </select> <br />
                <?php
                if (isset($_SESSION['e_kategoria'])) {
                    echo '<div class="error">' . $_SESSION['e_kategoria'] . '</div>';
                    unset($_SESSION['e_kategoria']);
                }

                if (isset($_SESSION['c_kategoria'])) {
                    echo '<div class="correct">' . $_SESSION['c_kategoria'] . '</div>';
                    unset($_SESSION['c_kategoria']);
                }
                ?>
                <label for="data_teoria">Data egzaminu teoretycznego:</label><br />
                <input type="datetime-local" name="data" value="<?php
                                                                if (isset($_SESSION['fr_data']))
                                                                    echo $_SESSION['fr_data'];
                                                                unset($_SESSION['fr_data']);
                                                                ?>"><br />
                <?php
                if (isset($_SESSION['e_data'])) {
                    echo '<div class="error">' . $_SESSION['e_data'] . '</div>';
                    unset($_SESSION['e_data']);
                }

                if (isset($_SESSION['c_data'])) {
                    echo '<div class="correct">' . $_SESSION['c_data'] . '</div>';
                    unset($_SESSION['c_data']);
                }
                ?><br />
                <div class="g-recaptcha" data-sitekey="6LdwLE8pAAAAANaZWTpzNWy6TsT48FEoRQ2LHjem"></div>
                <br />
                <?php
                if (isset($_SESSION['e_bot'])) {
                    echo '<div class="error">' . $_SESSION['e_bot'] . '</div>';
                    unset($_SESSION['e_bot']);
                }
                ?>
                <input type="submit" value="Zapisz termin">
            </form>
        </section>
    </div>
</body>

</html>