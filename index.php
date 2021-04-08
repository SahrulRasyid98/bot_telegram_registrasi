<?php
	session_start();
	$username=$_SESSION['username'];
	
	if (empty($username)) {
		echo '<script>alert("Harus akses login memaluli bot Telegram Hanwha!"); </script>';
		echo "<script>window.location.href = 'out.php';</script>";
	} else 
	{
?>
<head>
	<link rel="shortcut icon" href="logo.jpg"> 
	<title>Registrasi</title>
	<style>
		table
		{
			border-collapse:collapse;
			box-shadow: 5px 5px 10px #9b9b8c;
		}
			table,th
		{
			border: 0px solid white;
			border-bottom: 0px solid white;
			font-size: 12px;	
			border-collapse:collapse;		
		}
			td
		{		
			border-bottom: 0px dashed white;
			border-right: 1px solid white;
			border-left: 1px solid white;
			border-collapse:collapse;
		}
	</style>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
	<div align="center">
		<h3>CONTOH REGISTRASI BERBASIS TELEGRAM</h3>
	</div>

	<?php 
		include "profil.php"; 
		} 
	?>
</body>