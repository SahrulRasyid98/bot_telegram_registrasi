<?php
	session_start();
	
	include "koneksi.php";
	$username=$_SESSION['username'];
	
	$nama=$_POST['nama'];
	$nohp=$_POST['nohp'];
	$email=$_POST['email'];
	$polis=$_POST['polis'];
	$password=$_POST['password'];
	$sukses = "Data berhasil disimpan!";
	$_SESSION['sukses']= $sukses;
	$pesan = $nama.' berhasil mengedit data!';
	
	$simpan="UPDATE registrasi SET 
		nama ='$nama',
		nohp='$nohp',
		email='$email',
		polis='$polis',
		password='$password' where username='$username'";
		
		mysqli_query($mysqli, $simpan); 
		
		if ($simpan) {
			include "sendMessage.php"; //mengirim notifikasi ke id telegram tertentu
			echo "<script>window.location.href = 'index.php';</script>";
		} else
		{
			echo 'Data profil gagal diupdate!';
			echo '<br/>';
			echo '<a href="edit.php">Kembali</a>';
		}	
?>		