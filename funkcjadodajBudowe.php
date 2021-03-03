<?php

function dodajBudowe() {
    $nazwa = $_POST['nazwa'];
    $miejscowosc = $_POST['miejscowosc'];
    $ulica = $_POST['ulica'];
    $nr_budynku = $_POST['nr_budynku'];
    $kod_pocztowy = $_POST['kod_pocztowy'];
    $polaczenie = new mysqli("localhost", "root", "", "inzynierka");

    if ($polaczenie->query("INSERT INTO budowa (id_budowy, nazwa_budowy, miejscowosc, ulica, numer_budynku, kod_pocztowy, id_statusu_budowy) VALUES (NULL, '$nazwa', '$miejscowosc', '$ulica', '$nr_budynku', '$kod_pocztowy', 1)")) {
        
    } else {

        throw new Exception($polaczenie->error);
    }
}
?>
<?php

function drukujForm() { ?>
    <div id="formularz">
        <form method="post" action="dodajBudowe.php">
            <table>
                <tr> <th>Nazwa:</th><th>  <input type="text" name="nazwa" /></th></tr>
                <tr> <th>Miejscowość:</th><th> <input type="text"  name="miejscowosc" /></th></tr>
                <tr> <th>Ulica:</th><th> <input type="text"  name="ulica" /></th></tr>
                <tr> <th>Numer budynku: </th><th> <input type="text" name="nr_budynku" /></th></tr>
                <tr> <th>Kod pocztowy: </th><th> <input pattern="^[0-9]{2}-[0-9]{3}$" type="text"  name="kod_pocztowy" /></th></tr>
            </table>
            <input class="button" type="submit" value="Dodaj budowe" name="submit" />
        </form>
    </div>
<?php }
?>
