<?php 

	$username=$_SESSION['username'];
	
?>
<head>
	<link rel="shortcut icon" href="favicon.png"> 
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
<?php 
	include "koneksi.php";
	$q = "SELECT * from registrasi where username='$username'"; 	

	$result = mysqli_query($mysqli, $q); //execute the query $q	   
	$data = mysqli_fetch_array($result); 
?>
<div align="center">
	<h3>PROFIL USER</h3>  
	<font color="green"><?php echo $username; ?></font>
	<?php unset($_SESSION['sukses']); ?>
	 
	<table  width="500px" border="1" align="center" cellpadding="5" cellspacing="0"> 
		<tr bgcolor="#E6F7E9"> 
			<td width="150px" style="padding: 10px;" >Nama</td>
			<td style="padding: 10px;">
				<?php echo $data['nama']; ?>
			</td>   
		</tr> 
				<tr bgcolor="#E6F7E9"> 
			<td width="150px" style="padding: 10px;" >Username</td>
			<td style="padding: 10px;">
				<?php echo $data['username']; ?>
			</td>   
		</tr>
		<tr bgcolor="#E6F7E9"> 
			<td width="150px" style="padding: 10px;" >Password</td>
			<td style="padding: 10px;">
				<?php echo $data['password']; ?>
			</td>   
		</tr>
		<tr bgcolor="#E6F7E9"> 
			<td width="150px" style="padding: 10px;" >No. HP</td>
			<td style="padding: 10px;"> 
				<?php echo $data['nohp']; ?>
			</td>   
		</tr>
		<tr bgcolor="#E6F7E9"> 
			<td width="150px" style="padding: 10px;" >Email</td>
			<td style="padding: 10px;"> 
				<?php echo $data['email']; ?>
			</td>   
		</tr>	
		<tr bgcolor="#E6F7E9"> 
			<td width="150px" style="padding: 10px;" >Kode Polis</td>
			<td style="padding: 10px;"> 
				<?php echo $data['polis']; ?>
			</td>   
		</tr>	
	</table>
	<div align="center" style="margin-top: 25px;">
		<a href="edit.php?username=<?php echo $username; ?>" target="_self"><button class="btn btn-success">Edit</button></a>&nbsp;<a href="out.php" target="_self"><button class="btn btn-primary">Logout</button></a>
	</div>
</div>

<div align="center" style="margin-top: 25px;">
	Copyright &copy; Rasyid
</div>
<br/>
</body>