<?php
date_default_timezone_set('Asia/Jakarta');
header('Access-Control-Allow-Origin: *');
    // variabel koneksi
        $db_name  = 'elisabeth';
        $hostname = 'localhost';
        $username = 'root';
        $password = '';
         
    // koneksi ke database
        $dbh = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
     
    // query untuk menampilkan data
        $sql = 'SELECT id,judul,kategori,tanggal,gambar1,isi FROM berita_terkini ORDER BY tanggal DESC';
        $stmt = $dbh->prepare($sql);
    // execute the query
        $stmt->execute();
     
    // pecah hasilnya ke dalam bentuk array
        $result = $stmt->fetchAll( PDO::FETCH_ASSOC );
     
    // konversi ke JSON
        $json = json_encode( $result );
        echo $json;
?> 