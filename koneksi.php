<?php
date_default_timezone_set('Asia/Jakarta');
	 ini_set('display_errors', 1);
      if (version_compare(PHP_VERSION, '5.3', '>=')){
          error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);}
           else{error_reporting(E_ALL);}

	$servername = "localhost";
	$username = "root";
	$password = "";
	$db = "elisabeth";
	$conn = new mysqli($servername, $username, $password, $db);

	if ($conn ->connect_error) {
		$pesan = array("status" => "gagal",
				  "pesan" => "tidak dapat terhubung ke server"
			);
		echo json_encode($pesan);
	}

	function push_notif($input){
		$servername = "localhost";
		$username = "root";
		$password = "";
		$db = "elisabeth";
		$conn = new mysqli($servername, $username, $password, $db);
		$stmt = $conn->prepare("INSERT INTO notif (keterangan, link, user_id, waktu, to)".$input);
		if (!$stmt->execute()) {
			$pesan = array(
					"status"=>"gagal",
					"pesan"=>"terjadi kesalahan pada notifikasi."
				);
			echo json_encode($pesan);
			exit();
		};
	};
?>