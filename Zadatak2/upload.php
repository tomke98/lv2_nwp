<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploadanje datoteke</title>
</head>
<body>
    <?php
        //pocetak sesije
        session_start();
        //naziv originalne datoteke
        $file_name = $_FILES['file']['name'];
        //lokacija gdje ce se spremati kriptirani dokumenti
        $location = "uploads/" . $file_name;
        //dohvacanje informacija o putanji datoteke, odnosno o njezinoj ekstenziji
        $file_extension = pathinfo($location, PATHINFO_EXTENSION);
        //dozvoljene ekstenzije datoteke za upload
        $extensions = array("jpg", "jpeg", "png", "pdf");
        //provjera je li ekstenzija datoteke prisutna u dozvoljenim ekstenzijama
        //buduci da je case-sensitive, ekstenziju datoteke stavljamo u mala slova
        if (!in_array(strtolower($file_extension), $extensions)) {
            echo "<p>Pogresan format datoteke: $file_extension.</p>";
            die();
        }
        //dohvacanje sadrzaja privremene datoteke za eknripciju
        $data_content = file_get_contents($_FILES['file']['tmp_name']);
        //kljuc za enkripciju
        $encryption_key = md5('encription key');
        //odabir cipher metode
        $cipher = "AES-128-CTR";
        //inicijalizacijski vektor s ispravnom duzinom
        $iv_length = openssl_cipher_iv_length($cipher);
        $options = 0;
        //non-NULL inicijalizacijski vektor za enkripciju
        //random duzine 16 byte
        $encryption_iv = random_bytes($iv_length);
        //kriptiraj podatke sa openssl
        $data_encrypt = openssl_encrypt($data_content, $cipher, $encryption_key, $options, $encryption_iv);
        //spremi podatke
        $_SESSION['podaci'] = base64_encode($data_encrypt);
        $_SESSION['iv'] = $encryption_iv;
        //ako direktorij ne postoji, stvori ga
        if (!is_dir("uploads/")) {
            //directory=uploads
            //permission=0777
            //recursive=true
            if (!mkdir("uploads/", 0777, true)) {
                die("<p>Nije moguce kreirati direktorij $dir.</p>");
            }
        }
        //file name on server
        $file_name_server = "uploads/$file_name.txt";
        //pisanje kriptiranog dokumenta na server
        file_put_contents($file_name_server, $_SESSION['podaci']);
        //ispis o uspjesnosti uplodanja datoteke
        echo "Datoteka je uploadana uspjesno!";
    ?>
    <br />
     <!--pozivanje datoteke dohvati.php s metodom post-->
     <form action="dohvati.php" method="post">
      <!--dohvacanje kriptiranog dokumenta-->
      <input type="submit" name="submit" value="Fetch" />
    </form>
</body>
</html>
