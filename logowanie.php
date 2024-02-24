<?php
session_start();

if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany'] == true)) {
  header('Location: panel-administracyjny');
  exit();
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>WORD - Zaloguj się</title>
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

      <form action="login.php" method="post">
        <label for="login">Login:</label><br>
        <input type="text" name="login"><br>
        <label for="haslo">Hasło:</label><br>
        <input type="password" name="haslo"><br><br>
        <?php
        if (isset($_SESSION['blad'])) {
          echo $_SESSION['blad'];
          unset($_SESSION['blad']);
        }
        ?>
        <input type="submit" value="Zaloguj się">
        <p id="pRejestracja">Nie masz konta?⠀<a href="zarejestruj-sie">Zarejestruj się tutaj!</a></p>
      </form>
    </section>
  </div>
</body>

</html>