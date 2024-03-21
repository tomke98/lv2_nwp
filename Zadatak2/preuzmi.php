<?php
    //pocetak sesije
    session_start();
    //dohvacanje pomocu GET
    $file = $_GET['file'];
    //dekripcija podataka
    //isti kljuc kao i za enkripciju
    //stvori kljuc za dekripciju
    $decryption_key = md5('encription key');
    //odabir cipher metode AES
    $cipher = "AES-128-CTR";
    $options = 0;
    //dohvacanje IV i kriptiranih podataka
    $decryption_iv = $_SESSION['iv'];
    //dohvacanje sadrzaja uploadane datoteke (enkriptirano)
    $data_encrypt_content = file_get_contents("uploads/$file.txt");
    $data_decrypt_content = base64_decode($data_encrypt_content);
    //dekriptiranje podataka
    $data = openssl_decrypt($data_decrypt_content, $cipher, $decryption_key, $options, $decryption_iv);
    //putanja gdje ce se zapisivati podaci
    $file = "uploads/$file";
    //zapisivanje podataka u navedenu putanju
    file_put_contents($file, $data);
    //brisanje cache memorije datoteke
    clearstatcache();
    //provjera je li postoji datoteka
    //hederi forsiraju preuzimanje datoteke
    if(file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        //brisanje sadrzaja izlaza buffera
        ob_clean();
        //funkcija flush() zahtjeva od servera slanje trenutnog izlaza sa buffera pregledniku
        flush();
        //citanje datoteke i pisanje je na izlazni buffer
        readfile($file, true);
        //brisanje datoteke
        unlink($file);
        die();
    }
?>