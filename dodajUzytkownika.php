<?php
session_start();
if (!isset($_SESSION['zalogowany']) || $_SESSION['stanowisko'] != 1) {
    header('Location: logout.php');
    exit();
}

if (isset($_POST['login'])) {
//Udana walidacja? Załóżmy, że tak!
    $wszystko_OK = true;

//Sprawdź poprawność imienia
    $imie = $_POST['imie'];

//Sprawdzenie długości imienia
    if ((strlen($imie) < 1) || (strlen($imie) > 45)) {
        $wszystko_OK = false;
        $_SESSION['e_imie'] = "Imie musi posiadać od 1 do 45 znaków!";
    }

//Sprawdź poprawność nazwiska
    $nazwisko = $_POST['nazwisko'];

//Sprawdzenie długości nazwiska
    if ((strlen($nazwisko) < 1) || (strlen($nazwisko) > 45)) {
        $wszystko_OK = false;
        $_SESSION['e_nazwisko'] = "Nazwisko musi posiadać od 1 do 45 znaków!";
    }

//Sprawdź poprawność PESELa
    $pesel = $_POST['pesel'];

//Sprawdzenie długości PESELa
    if ((strlen($pesel) < 1) || (strlen($pesel) > 11)) {
        $wszystko_OK = false;
        $_SESSION['e_pesel'] = "PESEL musi posiadać od 1 do 11 znaków!";
    }

//Sprawdź poprawność stawki
    $stawka = $_POST['stawka'];

//Sprawdź poprawność nickname'a
    $login = $_POST['login'];

//Sprawdzenie długości loginu
    if ((strlen($login) < 1) || (strlen($login) > 45)) {
        $wszystko_OK = false;
        $_SESSION['e_login'] = "Login musi posiadać od 1 do 45 znaków!";
    }

    if (ctype_alnum($login) == false) {
        $wszystko_OK = false;
        $_SESSION['e_login'] = "Login może składać się tylko z liter i cyfr (bez polskich znaków)";
    }

//Sprawdzenie poprawności hasła
    $haslo = $_POST['haslo'];

    if ((strlen($haslo) < 8) || (strlen($haslo) > 20)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków!";
    }

    $haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);

//Sprawdź poprawność stanowiska
    $stanowisko_id = $_POST['stanowisko_id'];

//Zapamiętaj wprowadzone dane
    $_SESSION['fr_imie'] = $imie;
    $_SESSION['fr_nazwisko'] = $nazwisko;
    $_SESSION['fr_pesel'] = $pesel;
    $_SESSION['fr_stawka'] = $stawka;
    $_SESSION['fr_login'] = $login;
    $_SESSION['fr_haslo'] = $haslo;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $polaczenie = new mysqli("localhost", "root", "", "inzynierka");
        if ($polaczenie->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {

            //Czy login jest już zarezerwowany?
            $rezultat = $polaczenie->query("SELECT id FROM pracownik WHERE login='$login'");

            if (!$rezultat)
                throw new Exception($polaczenie->error);

            $ile_takich_loginow = $rezultat->num_rows;
            if ($ile_takich_loginow > 0) {
                $wszystko_OK = false;
                $_SESSION['e_login'] = "Istnieje już pracownik o takim loginie! Wybierz inny.";
            }

            if ($wszystko_OK == true) {
                //Wszystkie testy zaliczone, dodajemy pracownika do bazy

                if ($polaczenie->query("INSERT INTO pracownik VALUES (NULL, '$imie', '$nazwisko', '$login', '$haslo_hash', '$pesel', '$stawka', '$stanowisko_id')")) {
                    $_SESSION['udanarejestracja'] = true;
                    header('Location: dodajUzytkownika.php');
                } else {
                    throw new Exception($polaczenie->error);
                }
            }

            $polaczenie->close();
        }
    } catch (Exception $e) {
        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
        echo '<br />Informacja developerska: ' . $e;
    }
}
?>

<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link rel="stylesheet" href="CSS/index.css" type="text/css" />
        <link rel="stylesheet" href="CSS/admin.css" type="text/css" />
        <link rel="stylesheet" href="CSS/formularz.css" type="text/css" />
    </head>

    <body>
        <section>
            <nav>
                <a href="dodajUzytkownika.php">Dodaj użytkownika</a>
                <a href="usunUzytkownika.php">Usuń użytkownika</a>
                <a href="dodajBudowe.php">Dodaj budowe</a>
                <a href="logout.php">Wyloguj się</a>
            </nav>
        </section>
        <?php echo '<h2>Witaj '.$_SESSION['imie'].'!</h2>'; ?>
        <div id="formularz">
            <form method="post">

                <table>
                    <tr> <th>Imię:</th><th> <input type="text" value="<?php
                            if (isset($_SESSION['fr_imie'])) {
                                echo $_SESSION['fr_imie'];
                                unset($_SESSION['fr_imie']);
                            }
                            ?>" name="imie" /></th></tr>

                    <tr> <th>Nazwisko:</th><th> <input type="text" value="<?php
                            if (isset($_SESSION['fr_nazwisko'])) {
                                echo $_SESSION['fr_nazwisko'];
                                unset($_SESSION['fr_nazwisko']);
                            }
                            ?>" name="nazwisko" /></th></tr>

                    <tr> <th>PESEL:</th><th> <input type="number" value="<?php
                            if (isset($_SESSION['fr_pesel'])) {
                                echo $_SESSION['fr_pesel'];
                                unset($_SESSION['fr_pesel']);
                            }
                            ?>" name="pesel" /></th></tr>

                    <tr> <th>Stawka za godzine:</th><th>  <input type="number" step="0.01" value="<?php
                            if (isset($_SESSION['fr_stawka'])) {
                                echo $_SESSION['fr_stawka'];
                                unset($_SESSION['fr_stawka']);
                            }
                            ?>" name="stawka" /></th></tr>

                    <tr> <th>Login:</th><th>  <input type="text" value="<?php
                            if (isset($_SESSION['fr_login'])) {
                                echo $_SESSION['fr_login'];
                                unset($_SESSION['fr_login']);
                            }
                            ?>" name="login" /></th></tr>

                    <?php
                    if (isset($_SESSION['e_login'])) {
                        echo '<div class="error">' . $_SESSION['e_login'] . '</div>';
                        unset($_SESSION['e_login']);
                    }
                    ?>

                    <tr> <th>Hasło:</th><th> <input type="password"  value="<?php
                            if (isset($_SESSION['fr_haslo'])) {
                                echo $_SESSION['fr_haslo'];
                                unset($_SESSION['fr_haslo']);
                            }
                            ?>" name="haslo" /></th></tr>

                    <?php
                    if (isset($_SESSION['e_haslo'])) {
                        echo '<div class="error">' . $_SESSION['e_haslo'] . '</div>';
                        unset($_SESSION['e_haslo']);
                    }
                    ?>				

                    <tr> <th>Stanowisko: </th><th><select class="select" name="stanowisko_id">
                                <option value="1">Admin</option>
                                <option value="2">Prezes</option>
                                <option value="3">Brygadzista</option>
                                <option value="4">Pracownik</option>
                            </select></th></tr>
                </table>
                <input class="button" type="submit" value="Dodaj pracownika" />

            </form>
        </div>
    </body>
    <footer>
        Błażewicz Piotr | Bodziak Agnieszka &copy; Lublin 2021
    </footer>
</html>