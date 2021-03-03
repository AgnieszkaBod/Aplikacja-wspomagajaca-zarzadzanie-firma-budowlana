<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['zalogowany']) || $_SESSION['stanowisko'] != 1) {
    header('Location: logout.php');
    exit();
}

function usun() {
    $login = $_POST['login'];

    $polaczenie = new mysqli("localhost", "root", "", "inzynierka");
    $rezultat = @$polaczenie->query("SELECT * FROM pracownik WHERE login='$login'");
    $ilosc = $rezultat->num_rows;

    if ($ilosc > 0) {
        $polaczenie->query("DELETE FROM pracownik WHERE login='$login'");
        echo 'Pomyślnie usunięto pracownika!';
    } else
        echo 'Brak użytkownika o podanym loginie!';
}
?>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <br/>   <br/>
                Login: <input type="text" name="login"/> <br/>
                <input class="button" type="submit" value="Usun pracownika" name="submit" />
            </form>
        </div>
        <?php
        $akcja = filter_input(INPUT_POST, "submit");
        switch ($akcja) {
            case "Usun pracownika" : usun();
        }
        ?>
    </body>
    <footer>
        Błażewicz Piotr | Bodziak Agnieszka &copy; Lublin 2021
    </footer>
</html>