<?php
session_start();

if ((!isset($_SESSION['udany_zapis']))) {
  header('Location: zapisz-sie-na-egzamin-teoretyczny');
  exit();
} else {
  unset($_SESSION['udany_zapis']);
}

// Usuwanie zmiennych pamiętających wartości inputów w user.php
if (isset($_SESSION['fr_pkk'])) {
  unset($_SESSION['fr_pkk']);
}

if (isset($_SESSION['fr_czesc'])) {
  unset($_SESSION['fr_czesc']);
}

if (isset($_SESSION['fr_kategoria'])) {
  unset($_SESSION['fr_kategoria']);
}

if (isset($_SESSION['fr_data'])) {
  unset($_SESSION['fr_data']);
}

// Usuwanie błędów zapisu
if (isset($_SESSION['e_pkk'])) {
  unset($_SESSION['e_pkk']);
}

if (isset($_SESSION['e_czesc'])) {
  unset($_SESSION['e_czesc']);
}

if (isset($_SESSION['e_kategoria'])) {
  unset($_SESSION['e_kategoria']);
}

if (isset($_SESSION['e_data'])) {
  unset($_SESSION['e_data']);
}

// Usuwanie poprawnych wartości zapisu
if (isset($_SESSION['c_pkk'])) {
  unset($_SESSION['c_pkk']);
}

if (isset($_SESSION['c_czesc'])) {
  unset($_SESSION['c_czesc']);
}

if (isset($_SESSION['c_kategoria'])) {
  unset($_SESSION['c_kategoria']);
}

if (isset($_SESSION['c_data'])) {
  unset($_SESSION['c_data']);
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>WORD - Zapis ukończony!</title>
  <meta http-equiv="refresh" content="5,url='zapisz-sie-na-egzamin-teoretyczny'">
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
      </ol>
    </nav>
    <section>
      <form>
        <p>
          Udało się poprawnie zapisać na egazmin teoretyczny! Za <span id="timer">5</span> sek. zostaniesz przeniesiony spowrotem do formularza zapisu. Jeżeli tak się nie stało kliknij w <a href="zapisz-sie-na-egzamin-teoretyczny">ten link</a>
        </p>
      </form>
    </section>
  </div>
</body>
<script src="timer.js"></script>

</html>