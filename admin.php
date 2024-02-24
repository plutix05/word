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
    <title>WORD - <?php
                    echo "Witaj " . $_SESSION['user'] . "!";
                    ?></title>
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
                <h1 id="witaj">
                    <?php
                    echo "Witaj " . $_SESSION['user'] . "!";
                    ?>
                </h1>
                <table id="dane">
                    <caption>
                        Dane konta:
                    </caption>
                    <tr>
                        <td>Login</td>
                        <td><?php echo $_SESSION['user'] ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php echo $_SESSION['email'] ?></td>
                    </tr>
                </table> <br />
                <a href="logout.php">[ Wyloguj się ]</a>
            </div>
        </section>
    </div>
</body>

</html>