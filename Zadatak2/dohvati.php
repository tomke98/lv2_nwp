<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohvacanje kriptiranih dokumenata</title>
</head>
<body>
    <?php
        //pocetak sesije
        session_start();
        //ako direktorij ne postoji, ispisi poruku o nedostatku datoteka za dekriptiranje
        if (!is_dir("uploads/")) {
            echo "<p>Nema prisutnih datoteka za dekriptiranje.</p>";
            die();
        }
        //provjera je li datoteka tekstualna po njezinoj ekstenziji txt
        $checkIfFileIsText = function ($file) {
            return (pathinfo($file, PATHINFO_EXTENSION) === 'txt');
        };
        //scandir funkcijom izlistavamo datoteke i direktorije unutar putanje uploads/
        //definiranje varijable $files unutar koje izracunavaju razlike izmedu
        //izlistanih datoteka i polja
        $files = array_diff(scandir("uploads/"), array('..', '.'));
        //filtriranje tekstualnih datoteka
        $files = array_filter($files, $checkIfFileIsText);
        //ako je broj datoteka nula, ispisi da ne postoje datoteke za dekripciju
        //ako postoje datoteke, izlistaj ih
        if (count($files) === 0) {
            echo "<p>Ne postoje datoteke za dekripciju</p>";
        } else {
            foreach ($files as $file) {
                //dohvacanje imena datoteke bez .txt nastavka
                $file_name_without_extension = substr($file, 0, strlen($file) - 4);
                //ispis datoteka
                echo "<p> <a href=\"preuzmi.php?file=$file_name_without_extension\">$file_name_without_extension</a></p>";
            }
        }
    ?>
</body>
</html>