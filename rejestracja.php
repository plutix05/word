<?php

session_start();

if (isset($_POST['email'])) {

  $wszystko_OK = true;

  $nick = $_POST['nick'];
  $nick_pattern = "/^[^0-9]\w+$/";

  if ((strlen($nick) < 3) || (strlen($nick) > 20)) {
    $wszystko_OK = false;
    $_SESSION['e_nick'] = "Nick musi składać się od 3 do 20 znaków";
  } else {
    $_SESSION['c_nick'] = "Nick jest poprawny";
  }

  if (preg_match($nick_pattern, $nick) == false) {
    $wszystko_OK = false;
    $_SESSION['e_nick'] = "Nick może składać się z liter, cyfr i znaku podkreślenia (bez polskich znaków)";
  } else {
    $_SESSION['c_nick'] = "Nick jest poprawny";
  }

  $email = $_POST['email'];
  $email_pattern = '/^[a-zA-Z0-9!#\$%&\'\*\+\-\/=\?\^_`{|}~]+(\.[a-zA-Z0-9!#\$%&\'\*\+\-\/=\?\^_`{|}~]+)*@[a-z0-9]([a-z0-9-]*[a-z0-9]\.)+[a-z]+$/';

  if (preg_match($email_pattern, $email) == false) {
    $wszystko_OK = false;
    $_SESSION['e_email'] = "Podaj poprawny adres e-mail";
  } else {
    $_SESSION['c_email'] = "Email jest poprawny";
  }


  $haslo1 = $_POST['haslo1'];
  $haslo2 = $_POST['haslo2'];
  $pat_mal = '/^(?=.*[a-z]).+$/';
  $pat_duz = '/^(?=.*[A-Z]).+$/';
  $pat_cyf = '/^(?=.*\d).+$/';
  $pat_zna = '/^^(?=.*[\W_]).+$/';
  $pat_spa = '/^(?!.*\s).+$/';

  if ((strlen($haslo1) < 8)) {
    $wszystko_OK = false;
    $_SESSION['e_haslo1'] = "Hasło musi posiadać minimalnie 8 znaków";
  } elseif ((strlen($haslo1) > 20)) {
    $wszystko_OK = false;
    $_SESSION['e_haslo1'] = "Hasło musi posiadać maksymalnie 20 znaków";
  } elseif (!preg_match($pat_mal, $haslo1)) {
    $wszystko_OK = false;
    $_SESSION['e_haslo1'] = "Hasło musi posiadać przynajmniej 1 małą literę";
  } elseif (!preg_match($pat_duz, $haslo1)) {
    $wszystko_OK = false;
    $_SESSION['e_haslo1'] = "Hasło musi posiadać przynajmniej 1 wielką literę";
  } elseif (!preg_match($pat_cyf, $haslo1)) {
    $wszystko_OK = false;
    $_SESSION['e_haslo1'] = "Hasło musi posiadać przynajmniej 1 cyfrę";
  } elseif (!preg_match($pat_zna, $haslo1)) {
    $wszystko_OK = false;
    $_SESSION['e_haslo1'] = "Hasło musi posiadać przynajmniej 1 dowolny znak specjalny";
  } elseif (!preg_match($pat_spa, $haslo1)) {
    $wszystko_OK = false;
    $_SESSION['e_haslo1'] = "Hasło nie może zawierać spacji";
  } else {
    $_SESSION['c_haslo1'] = "Hasło jest poprawne";
  }

  if ($haslo1 != $haslo2) {
    $wszystko_OK = false;
    $_SESSION['e_haslo2'] = "Hasła nie są identyczne";
  } else {
    $_SESSION['c_haslo2'] = "Hasła są identyczne";
  }

  $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

  if (!isset($_POST['regulamin'])) {
    $wszystko_OK = false;
    $_SESSION['e_regulamin'] = "Zaakceptuj regulamin";
  } else {
    $_SESSION['c_haslo2'] = "Zaakceptowano regulamin";
  }

  require_once "connect.php";

  $sekret = $secret_key;

  $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $sekret . '&response=' . $_POST['g-recaptcha-response']);

  $odpowiedz = json_decode($sprawdz);

  if ($odpowiedz->success == false) {
    $wszystko_OK = false;
    $_SESSION['e_bot'] = "Potwierdź, że nie jesteś botem";
  } else {
    $_SESSION['e_bot'] = "Potwierdź, że nie jesteś botem";
  }

  $_SESSION['fr_nick'] = $nick;
  $_SESSION['fr_email'] = $email;
  $_SESSION['fr_haslo1'] = $haslo1;
  $_SESSION['fr_haslo2'] = $haslo2;
  if (isset($_POST['regulamin'])) {
    $_SESSION['fr_regulamin'] = true;
  }

  mysqli_report(MYSQLI_REPORT_STRICT);

  try {

    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
    if ($polaczenie->connect_errno != 0) {
      throw new Exception(mysqli_connect_errno());
    } else {

      $rezultat  = $polaczenie->query("select id_user from uzytkownicy where email='$email'");

      if (!$rezultat) {
        throw new Exception($polaczenie->error);
      }

      $ile_takich_maili = $rezultat->num_rows;

      if ($ile_takich_maili > 0) {
        $wszystko_OK = false;
        $_SESSION['e_email'] = "Istnieje konto przypisane do podanego emaila";
      }

      $rezultat  = $polaczenie->query("select id_user from uzytkownicy where user='$nick'");

      if (!$rezultat) {
        throw new Exception($polaczenie->error);
      }

      $ile_takich_nickow = $rezultat->num_rows;

      if ($ile_takich_nickow > 0) {
        $wszystko_OK = false;
        $_SESSION['e_nick'] = "Istnieje konto przypisane do podanego nicku. Wybierz inny.";
      }

      if ($wszystko_OK == true) {
        if ($polaczenie->query("insert into uzytkownicy values (null, '$nick', '$email', '$haslo_hash','Niepotwierdzony')")) {
          $_SESSION['udanarejestracja'] = true;
          header('Location: rejestracja-zakonczona-pomyslnie');
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
  <title>WORD - Zarejestruj się</title>
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
        <label for="nick">Login: </label><br />
        <input type="text" value="<?php
                                  if (isset($_SESSION['fr_nick']))
                                    echo $_SESSION['fr_nick'];
                                  unset($_SESSION['fr_nick']);
                                  ?>" name="nick" /> <br />
        <?php
        if (isset($_SESSION['e_nick'])) {
          echo '<div class="error">' . $_SESSION['e_nick'] . '</div>';
          unset($_SESSION['e_nick']);
        } elseif (isset($_SESSION['c_nick'])) {
          echo '<div class="correct">' . $_SESSION['c_nick'] . '</div>';
          unset($_SESSION['c_nick']);
        }
        ?>
        <label for="email">E-mail: </label><br />
        <input type="text" value="<?php
                                  if (isset($_SESSION['fr_email']))
                                    echo $_SESSION['fr_email'];
                                  unset($_SESSION['fr_email']);
                                  ?>" name="email" /> <br />
        <?php
        if (isset($_SESSION['e_email'])) {
          echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
          unset($_SESSION['e_email']);
        } elseif (isset($_SESSION['c_email'])) {
          echo '<div class="correct">' . $_SESSION['c_email'] . '</div>';
          unset($_SESSION['c_email']);
        }
        ?>
        <label for="haslo1">Hasło: </label><br />
        <input type="password" value="<?php
                                      if (isset($_SESSION['fr_haslo1']))
                                        echo $_SESSION['fr_haslo1'];
                                      unset($_SESSION['fr_haslo1']);
                                      ?>" name="haslo1" /> <br />
        <?php
        if (isset($_SESSION['e_haslo1'])) {
          echo '<div class="error">' . $_SESSION['e_haslo1'] . '</div>';
          unset($_SESSION['e_haslo1']);
        } elseif (isset($_SESSION['c_haslo1'])) {
          echo '<div class="correct">' . $_SESSION['c_haslo1'] . '</div>';
          unset($_SESSION['c_haslo1']);
        }
        ?>
        <label for="haslo2">Powtórz hasło:</label><br />
        <input type="password" value="<?php
                                      if (isset($_SESSION['fr_haslo2']))
                                        echo $_SESSION['fr_haslo2'];
                                      unset($_SESSION['fr_haslo2']);
                                      ?>" name="haslo2" /> <br />
        <?php
        if (isset($_SESSION['e_haslo2'])) {
          echo '<div class="error">' . $_SESSION['e_haslo2'] . '</div>';
          unset($_SESSION['e_haslo2']);
        } elseif (isset($_SESSION['c_haslo2'])) {
          echo '<div class="correct">' . $_SESSION['c_haslo2'] . '</div>';
          unset($_SESSION['c_haslo2']);
        }
        ?>
        <input type="checkbox" name="regulamin" <?php
                                                if (isset($_SESSION['fr_regulamin'])) {
                                                  echo "checked";
                                                  unset($_SESSION['fr_regulamin']);
                                                }
                                                ?> /><label for="regulamin">Akceptuję <a href="regulamin-serwisu">regulamin</a></label>
        <?php
        if (isset($_SESSION['e_regulamin'])) {
          echo '<div class="error">' . $_SESSION['e_regulamin'] . '</div>';
          unset($_SESSION['e_regulamin']);
        } elseif (isset($_SESSION['c_regulamin'])) {
          echo '<div class="correct">' . $_SESSION['c_regulamin'] . '</div>';
          unset($_SESSION['c_regulamin']);
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
        <input type="submit" value="Zarejestruj się" />
      </form>
    </section>
  </div>
</body>

</html>