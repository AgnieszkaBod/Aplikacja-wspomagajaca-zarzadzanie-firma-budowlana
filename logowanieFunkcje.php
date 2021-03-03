<?php

session_start();

if ((!isset($_POST['login'])) || (!isset($_POST['haslo']))) {
    header('Location: index.php');
    exit();
}

$polaczenie = @new mysqli("localhost", "root", "", "inzynierka");

if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} else {
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");

    if ($rezultat = @$polaczenie->query(
                    sprintf("SELECT * FROM pracownik WHERE login='%s'", mysqli_real_escape_string($polaczenie, $login)))) {
        $ilu_userow = $rezultat->num_rows;
        if ($ilu_userow > 0) {
            $wiersz = $rezultat->fetch_assoc();

            if (password_verify($haslo, $wiersz['haslo'])) {
                $_SESSION['zalogowany'] = true;
                $_SESSION['id'] = $wiersz['id'];
                $_SESSION['login'] = $wiersz['login'];
                //$_SESSION['haslo'] = $wiersz['haslo'];
                $_SESSION['stanowisko'] = $wiersz['id_stanowiska'];
                $_SESSION['imie'] = $wiersz['imie'];

                unset($_SESSION['blad']);
                $rezultat->free_result();
                
                if ($_SESSION['stanowisko'] == 1)
                    header('Location: dodajUzytkownika.php');
                else if ($_SESSION['stanowisko'] == 2)
                    header('Location: prezesPodgladBudowy.php');
                else if ($_SESSION['stanowisko'] == 3)
                    header('Location: brygadzistaRaport.php');
                else if ($_SESSION['stanowisko'] == 4)
                    header('Location: pracownikDzien.php');
            } else {
                $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                //$_SESSION['blad'] = password_hash($_POST['haslo'], PASSWORD_DEFAULT);
                header('Location: index.php');
            }
        } else {
            $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
            header('Location: index.php');
        }
    }

    $polaczenie->close();
}
?>

