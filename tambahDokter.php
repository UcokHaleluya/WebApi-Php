<?php
require "koneksi.php";
//validasi parameter
$fields = array(
			"email" => true,
			"password" => true,
			"role" => "D",
			"nama_depan" => true,
			"nama_belakang" => true,
			"spesialis" => true,
			"pendidikan" => true,
			"gambar" => false,
			"keterangan" => false,
			"senin" => false,
			"selasa" => false,
			"rabu" => false,
			"kamis" => false,
			"jumat" => false,
			"sabtu" => false,
	);

?>