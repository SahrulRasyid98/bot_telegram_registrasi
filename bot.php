<?php
$token = "1656169465:AAHYBJmhg0g7AFYGj2R3BO7SDXHvZ2yeLg8"; //Ganti dengan Token yang diperoleh dari BotFather
$usernamebot="@HeroLifebot"; //nama bot yang diperoleh dari BotFather
define('BOT_TOKEN', $token); 

define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

$debug = false;

function exec_curl_request($handle)
{
    $response = curl_exec($handle);

    if ($response === false) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        error_log("Curl returned error $errno: $error\n");
        curl_close($handle);

        return false;
    }

    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);

    if ($http_code >= 500) {
        // do not wat to DDOS server if something goes wrong
    sleep(10);

        return false;
    } elseif ($http_code != 200) {
        $response = json_decode($response, true);
        error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
        if ($http_code == 401) {
            throw new Exception('Invalid access token provided');
        }

        return false;
    } else {
        $response = json_decode($response, true);
        if (isset($response['description'])) {
            error_log("Request was successfull: {$response['description']}\n");
        }
        $response = $response['result'];
    }

    return $response;
}

function apiRequest($method, $parameters = null)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");

        return false;
    }

    if (!$parameters) {
        $parameters = [];
    } elseif (!is_array($parameters)) {
        error_log("Parameters must be an array\n");

        return false;
    }

    foreach ($parameters as $key => &$val) {
        // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
        $val = json_encode($val);
    }
    }
    $url = API_URL.$method.'?'.http_build_query($parameters);

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

    return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");

        return false;
    }

    if (!$parameters) {
        $parameters = [];
    } elseif (!is_array($parameters)) {
        error_log("Parameters must be an array\n");

        return false;
    }

    $parameters['method'] = $method;

    $handle = curl_init(API_URL);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
    curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    return exec_curl_request($handle);
}

// jebakan token, klo ga diisi akan mati
if (strlen(BOT_TOKEN) < 20) {
    die(PHP_EOL."-> -> Token BOT API nya mohon diisi dengan benar!\n");
}

function getUpdates($last_id = null)
{
    $params = [];
    if (!empty($last_id)) {
        $params = ['offset' => $last_id + 1, 'limit' => 1];
    }
  //echo print_r($params, true);
  return apiRequest('getUpdates', $params);
}

// matikan ini jika ingin bot berjalan
//die('baca dengan teliti yak!');

// ----------- pantengin mulai ini
function sendMessage($idpesan, $idchat, $pesan)
{
    $data = [
    'chat_id'             => $idchat,
    'text'                => $pesan,
    'parse_mode'          => 'Markdown',
    'reply_to_message_id' => $idpesan,
  ];

    return apiRequest('sendMessage', $data);
}

function processMessage($message)
{
    global $database;
    if ($GLOBALS['debug']) {
        print_r($message);
    }

    if (isset($message['message'])) {
            
        $sumber = $message['message'];
        $idpesan = $sumber['message_id'];
        $idchat = $sumber['chat']['id'];
        
        $username = $sumber["from"]["username"];
        $nama = $sumber['from']['first_name'];
        $iduser = $sumber['from']['id'];

        if (isset($sumber['text'])) {
            $pesan = $sumber['text'];

            if (preg_match("/^\/view_(\d+)$/i", $pesan, $cocok)) {
                $pesan = "/view $cocok[1]";
            }

            if (preg_match("/^\/hapus_(\d+)$/i", $pesan, $cocok)) {
                $pesan = "/hapus $cocok[1]";
            }

     // print_r($pesan);

      $pecah2 = explode(' ', $pesan, 3);
            $katake1 = strtolower($pecah2[0]); //untuk command
            $katake2 = strtolower($pecah2[1]); // kata pertama setelah command
            $katake3 = strtolower($pecah2[2]); // kata kedua setelah command
            
            
      $pecah = explode(' ', $pesan, 2);
            $katapertama = strtolower($pecah[0]); //untuk command
            
        switch ($katapertama) {
        case '/start': 
		case '/start@HeroLifebot':
          $text = "Selamat datang..., `$nama`! Untuk bantuan ketik: /help";
          break;

        case '/help': 
        case '/help@HeroLifebot':
          $text = "Berikut menu yang tersedia:\n\n";
		  $text .= "/start untuk memulai bot\n";
          $text .= "/help info bantuan ini\n";	 	  
          $text .= "/registrasi `username` dan `no hp` untuk registrasi user baru\n";
          $text .= "/password `passwordbaru` untuk ganti password\n";
          $text .= "/username `usernamebaru` untuk ganti username\n";
          $text .= "/email `email baru` untuk mengisi atau mengganti email\n";
          $text .= "/polis `kode polis` untuk mengisi atau mengganti kode polis\n";
          $text .= "/login `username` `password` untuk login\n";
          $text .= "/myakun untuk melihat akun Anda\n"; 
          $text .= "/time info waktu sekarang.";
          break; 
		  
        case '/time': 
		case '/time@HeroLifebot':
          date_default_timezone_set("Asia/Jakarta");
		  $waktusekarang = date('d-m-Y H:i:s');
          $text = "Waktu Sekarang: $waktusekarang\n";
          break;		  
          
		case '/registrasi':
        case '/registrasi@HeroLifebot': 

                    if (isset($pecah2[1])) {
                        $username = $pecah2[1]; //mendapatkan kata kedua setelah command
                        $nohp = $pecah2[2]; //mendapatkan kata ketiga setelah command   
					    $password = rand(111111, 999999); //contoh untuk mendapatkan password awal secara random
				  
					include "koneksi.php";
					
					if(mysqli_num_rows(mysqli_query($mysqli, "select * from registrasi where nohp ='".mysqli_real_escape_string($mysqli, $nohp)."'"))){
							$text = "Anda sudah terdaftar sebelumnya dengan Nomer Hp : $nohp";
					} else
					{ 
						$nama = str_replace("'","",$nama); //salah satu cara menghilangkan tanda petik
						$username = str_replace("'","",$username);
						$password = str_replace("'","",$password);
						
						$simpan="INSERT INTO registrasi SET 
							iduser='$iduser', 
							nama='$nama', 
							nohp = '$nohp',
							username='$username',
							password='$password'";  
									
							mysqli_query($mysqli, $simpan); 		  
							  
							$text = "Selamat $nama ($iduser), registrasi Saudara berhasil, Username: $username, Password: $password\n\n";
							$text .= "Ganti password: /password `passwordbaru`\n";	
							
						//include "sendMessage.php"; 
					}
				} else {
				  $text = 'Username dan Nohp Tidak boleh kosong !';
				  $text .= "\n";
				  $text .= "Format: /registrasi  username `sapasi` nomer hp";
				}
		break;
          
        case '/password':
        case '/password@HeroLifebot':
          if (isset($pecah[1])) {
                $password = $pecah[1];
                include 'koneksi.php';

		        $simpan="UPDATE registrasi SET 
			    password='$password' where iduser='$iduser'";
		        mysqli_query($mysqli, $simpan); 
                
                $text = "$nama ($iduser), password telah berhasil diganti!";
          } else {
              $text = '*ERROR:* _Password pengganti tidak boleh kosong!_';
			  $text .= "\n";
			  $text .= "Format: /password `passwordbaru`";
          }
          break;     
          
        case '/username':
        case '/username@HeroLifebot':
          if (isset($pecah[1])) {
                $username = $pecah[1];
                include 'koneksi.php';

		        $simpan="UPDATE registrasi SET 
			    username='$username' where iduser='$iduser'";
		        mysqli_query($mysqli, $simpan); 
                
                $text = "$nama ($iduser), username telah berhasil diganti!";
          } else {
              $text = '*ERROR:* _Username pengganti tidak boleh kosong!_';
          }
          break;   
          
          case '/email':
            case '/email@HeroLifebot':
              if (isset($pecah[1])) {
                    $email = $pecah[1];
                    include 'koneksi.php';
    
                    $simpan="UPDATE registrasi SET 
                    email='$email' where iduser='$iduser'";
                    mysqli_query($mysqli, $simpan); 
                    
                    $text = "$nama ($iduser), email telah berhasil diinput atau di ganti";
              } else {
                  $text = '*ERROR:* _Username pengganti tidak boleh kosong!_';
              }
              break;     
          
              case '/polis':
                case '/polis@HeroLifebot':
                  if (isset($pecah[1])) {
                        $polis = $pecah[1];
                        include 'koneksi.php';
        
                        $simpan="UPDATE registrasi SET 
                        polis='$polis' where iduser='$iduser'";
                        mysqli_query($mysqli, $simpan); 
                        
                        $text = "$nama ($iduser), Kode polis telah berhasil di tambahkan atau diganti!";
                  } else {
                      $text = '*ERROR:* _Username pengganti tidak boleh kosong!_';
                  }
                  break;     
                  
          
                  case '/login':
                    case '/login@HeroLifebot':
                        if ((isset($pecah2[1])) and (isset($pecah2[2])))
                        {
                            $username = $pecah2[1]; //mendapatkan nohp dari kata kedua
                            $password = $pecah2[2]; //contoh untuk mendapatkan password awal secara random
                            $username = str_replace("'","",$username);
                            $password = str_replace("'","",$password);
                            include "koneksi.php";
                            if(mysqli_num_rows(mysqli_query($mysqli, "select * from `registrasi` where `iduser` = '$iduser' and`username` ='$username' and `password` ='$password'")))
                            {
                                    $text = "Dear $nama, Anda berhasil Login dengan (username : $username) dan (ID : $iduser)\n\n\n" ;
                            } else
                            { 
                                $text = "Maaf, $nama , password atau username yang anda masukan salah atau menggukanan ID Telegram lain!\n\n\n";
                            }
                            $text .= "Jika ingin melalukan login lewat Browser Klik link https://01fd26690a61.ngrok.io/registrasi/login.php?username=$username&password=$password"; 
                      } else {
                          $text = '*ERROR:* _Username dan Password tidak boleh kosong!_';
                          $text .= "\n";
                          $text .= "Format: /login `username` `password`";
                      }
                      break;  

          case '/myakun':
            case '/myakun@HeroLifebot':
                    include 'koneksi.php';
                        
                    $tampil="select * from registrasi WHERE iduser='$iduser'"; 
                    $qryTampil=mysqli_query($mysqli, $tampil); 
                    $data=mysqli_fetch_array($qryTampil);
                    
                    $nama = $data['nama']; 
                    $nohp=$data['nohp'];				
                    $username = $data['username'];
                    $password = $data['password'];
                    $email = $data['email'];
                    $polis = $data['polis'];
    
                    $text = "Akun anda adalah : \nNama: $nama  \nNo. HP: $nohp \nUsername: $username \nPassword: $password\nEmail : $email \nKode Polis : $polis \n\n";
                    $text .= "Login https://01fd26690a61.ngrok.io/registrasi/login.php?username=$username&password=$password"; 
            break;       

        default:
          $text = '_Command tidak dikenali?!_';
		  $text .= "\n";
		  $text .= "Klik /help untuk bantuan";
          break;
      }
        } else {
            $text = 'Silahkan tulis pesan yang akan disampaikan..';
			$text .= "\n";
			$text .= "Format: /pesan `pesan`";
        }

        $hasil = sendMessage($idpesan, $idchat, $text);
        if ($GLOBALS['debug']) {
            // hanya nampak saat metode poll dan debug = true;
      echo 'Pesan yang dikirim: '.$text.PHP_EOL;
            print_r($hasil);
        }
    }
}

// pencetakan versi dan info waktu server, berfungsi jika test hook
echo 'Ver. '.myVERSI.' OK Start!'.PHP_EOL.date('d-m-Y H:i:s').PHP_EOL;

function printUpdates($result)
{
    foreach ($result as $obj) {
        // echo $obj['message']['text'].PHP_EOL;
    processMessage($obj);
        $last_id = $obj['update_id'];
    }

    return $last_id;
}


// AKTIFKAN INI jika menggunakan metode poll
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/*
$last_id = null;
while (true) {
    $result = getUpdates($last_id);
    if (!empty($result)) {
        echo '+';
        $last_id = printUpdates($result);
    } else {
        echo '-';
    }

    sleep(1);
}
*/
// AKTIFKAN INI jika menggunakan metode webhook
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  exit;
} else {
  processMessage($update);
}

?>