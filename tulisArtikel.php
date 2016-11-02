<?php
date_default_timezone_set('Asia/Jakarta');
require 'koneksi.php';
$fields = array(
		"judul"=>true,
		"nopegawai"=>true,
		"isi"=>true
	);
require 'parameter.php';
$judul = $data->judul;
$nopegawai = $data->nopegawai;
$isi = array();
$isi = $data->isi;
var_dump($data);
exit();

?>