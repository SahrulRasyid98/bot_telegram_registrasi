<?php
	session_start(); 	
	include 'koneksi.php'; 		
	$username=$_GET['username'];
	$password=$_GET['password'];
	$_SESSION['username']=$username;

	$query=mysqli_query($mysqli, "select * from registrasi where username='$username' and password='$password'");	 
	$resultQuery=mysqli_num_rows($query);	
	
	if($resultQuery==TRUE){ 
		header("location:index.php");    
	}else
	{  
		echo '<font color="red">LOGIN FAILED! <br/>Username or Password was wrong!</font>';
	}
?>