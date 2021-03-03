<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['zalogowany']) || $_SESSION['stanowisko'] != 2) {
    header('Location: logout.php');
    exit();
}
?>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/index.css" type="text/css" />
        <link rel="stylesheet" href="CSS/prezes.css" type="text/css" />
        <link rel="stylesheet" href="CSS/formularz.css" type="text/css" />        
    </head>
    <body>
        <section>
            <nav>
                <a href="prezesSprawdzPracownika.php">Sprawdz dane pracownika</a>
                <a href="prezesPodgladBudowy.php">Edycja budowy</a>
                <a href="prezesGenerujRaport.php">Generuj Raport</a>
                <a href="logout.php">Wyloguj się</a>
            </nav>
        </section>
        <?php
        echo '<h2>Witaj ' . $_SESSION['imie'] . '!</h2>';
        include_once 'funkcjePrezes.php';
        zobaczGeneruj_prezes();
        $akcja = filter_input(INPUT_POST, "submit");
        switch ($akcja) {
            case "Zobacz historię" : echo '<div id="formularz">';
                zobaczHistorie();
                echo '</div>';
                break;
            case "Wygeneruj raport" : echo '<div id="formularz">';
                generujRaport();
                echo '</div>';
                break;
        }
        ?>
    </body>
    <footer>
        Błażewicz Piotr | Bodziak Agnieszka &copy; Lublin 2021
    </footer>
</html>