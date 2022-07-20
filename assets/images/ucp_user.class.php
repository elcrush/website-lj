<?php	
/*

	MADE WITH ANANG FEBRI PANGESTU
	LOST JAVA INDONESIA

*/
include '../PHPMailer/PHPMailerAutoload.php'; 
session_start();
require 'ucp_db.class.php';



class User
{
    public function __construct()
    {
        $this->db = new Db();
	}
	
/* OSNOVNO */	
	public function IsLogged()
	{
		if(isset($_SESSION['logged']))
		{
			if($_SESSION['logged'] == true)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		return false;
	}
	
	public function IsBanned($username)
	{
		$username = $this->db->quote($username);
		$result   = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		if($result[0]['ban'] == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
		return false;
	}
	
	public function BannedReason($username)
	{
		$username = $this->db->quote($username);
		$result   = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		return $result[0]['ban_reason'];
	}
	
	public function IsAdmin($username)
	{
		$username = $this->db->quote($username);
		$result   = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		if($result[0]['admin'] >= 3)
		{
			return true;
		}
		else
		{
			return false;
		}
		return false;		
	}
	
	public function Login($username, $password)
	{
		$username = $this->db->quote($username);
		$password = $this->db->quote($password);
		if($_COOKIE['percobaan_login'] <= 2)
		{
			if($this->db->exists("players", "username", $username))
			{
				$getsalt = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");

				$salt = $getsalt[0]['salt'];

				$password = $this->Sha256($password, $salt);

				$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
				if($result[0]['password'] == $password)
				{
					if($this->IsBanned($username))
					{
						$_SESSION['error_msg'] = "Anda di ban oleh admin dengan alasan".$this->BannedReason($username)."";
					}
					else
					{	
						$_SESSION['logged']   = true;
						$_SESSION['username'] = $username;
						$this->db->query("UPDATE `players` SET `last_ip` = '".$this->getRealIpAddr()."', `ip` = '".$this->getRealIpAddr()."', `last_login` = '".date("Y-m-d H:i:s")."', `session_id` = '".session_id()."' WHERE `username` = '".$username."';");
						$this->db->query("INSERT INTO `ucp_loginlogs` (`id`, `user_id`, `ip`, `date`, `useragent`, `os`) VALUES (NULL, '".$result[0]['reg_id']."', '".$this->getRealIpAddr()."', '".date("Y-m-d H:i:s")."', '".$this->getUserAgent()."', '');");
						?>
							<script src="js/jquery-3.4.1.min.js"></script>
							<script src="js/sweetalert2.all.min.js"></script>
							<script>
								Swal.fire("Congratulations","Anda berhasil login. Mohon tunggu sesaat.","success");
								e.preventDefault();
							</script>
							<meta http-equiv='refresh' content='5; url="/panel.php'>";
						<?php
						
					}
				}
				else
				{
					setcookie("percobaan_login", $_COOKIE['percobaan_login']+1, time() + (60 * 10)); // 60 * 10 = 10 menit
					?>
						<script src="js/jquery-3.4.1.min.js"></script>
						<script src="js/sweetalert2.all.min.js"></script>
						<script>
							Swal.fire("Opss..","Kata sandi yang anda masukan salah!","warning");
							e.preventDefault();
						</script>
					<?php
				}
			}
			else
			{
				?>
					<script src="js/jquery-3.4.1.min.js"></script>
					<script src="js/sweetalert2.all.min.js"></script>
					<script>
						Swal.fire("Opss..","Character atau password yang anda masukan salah!","warning");
						e.preventDefault();
					</script>
				<?php
				return true;
			}
		}
		else
		{
			$_SESSION['error_msg'] = "anda tercatat salah memasuki password 3 kali, jadi anda dilarang login selama 10 menit";
		}
		return false;
	}
	
	public function Register($username, $email, $password, $ppassword, $gender, $verification_code, $verifcode)
	{
		$username    = $this->db->quote($username);
		$email       = $this->db->quote($email);
		$password    = $this->db->quote($password);
		$ppassword   = $this->db->quote($ppassword);
		$gender 	 = $this->db->quote($gender);
		$verification_code 	 = $this->db->quote($verification_code);
		$verifcode 	 = $this->db->quote($verifcode);

    	
		if(!$this->db->exists("players", "username", $username))
		{
			if(!$this->db->exists("players", "email", $email))
			{				
				if(strlen($password) < 8)
				{
				?>
					<script src="js/jquery-3.4.1.min.js"></script>
					<script src="js/sweetalert2.all.min.js"></script>
					<script>
						Swal.fire("Opss..","Password harus 8 karakter lebih!","warning");
						e.preventDefault();
					</script>
				<?php
				}			
				if($password != $ppassword)
				{
				?>
					<script src="js/jquery-3.4.1.min.js"></script>
					<script src="js/sweetalert2.all.min.js"></script>
					<script>
						Swal.fire("Opss..","Your Password and Repassword Not Match!","warning");
						e.preventDefault();
					</script>
				<?php
				}				
				if(!$kesalahan)
				{
					$panjanghash = 16;
					$salt = base64_encode(random_bytes(ceil(0.75*$panjanghash)));
					$password = $this->Sha256($password, $salt);
					$token= md5(rand(0,1000));
					$verifcode = md5(rand(10000, 999999));
					$verification_code= rand(10000,99999);

					$result = $this->db->query("INSERT INTO `server_5675_ljrpdb`.`players` (`reg_id`, `username`, `password`, `salt`, `email`, `reg_date`, `last_login`, `last_ip`, `session_id`, `gender`, `verifkey`, `verifcode`, `verification_code`) VALUES (NULL, '".$username."', '".$password."', '".$salt."', '".$email."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', '".$this->getRealIpAddr()."', '".session_id()."', '".$gender."', '".$token."', '".$verifcode."', '".$verification_code."');");
					if($result)
					{
						?>
							<script src="js/jquery-3.4.1.min.js"></script>
							<script src="js/sweetalert2.all.min.js"></script>
							<script>
								Swal.fire("Congratulations","Your registration has been successful. please verify your account.","success");
								e.preventDefault();
							</script>
						<?php
					}
					else
					{
						?>
							<script src="js/jquery-3.4.1.min.js"></script>
							<script src="js/sweetalert2.all.min.js"></script>
							<script>
								Swal.fire("Opss..","Terjadi kesalahan!","warning");
								e.preventDefault();
							</script>
						<?php
					}
				}
			}
			else
			{
				?>
			        <script src="js/jquery-3.4.1.min.js"></script>
			        <script src="js/sweetalert2.all.min.js"></script>
			        <script>
			            Swal.fire("Opss..","Email Already Taken. Please Try Another One.!","warning");
			            e.preventDefault();
			        </script>
			    <?php
			}
		}
		else
		{
			?>
			    <script src="js/jquery-3.4.1.min.js"></script>
			    <script src="js/sweetalert2.all.min.js"></script>
			    <script>
			       	Swal.fire("Opss..","Username Already Taken. Please Try Another One.!","warning");
			        e.preventDefault();
			     </script>
			 <?php
		}
	}
	public function UpdateProfile($username, $tpassword, $npassword)
	{
		$username       = $this->db->quote($username);
		$tpassword      = $this->db->quote($tpassword);
		$npassword      = $this->db->quote($npassword);
		
		$getsalt = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		$salt = $getsalt[0]['salt'];		

		$panggil_query = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		if($panggil_query[0]['password'] != $this->Sha256($tpassword, $salt))
		{
			$_SESSION['error_msg'] = "Password yang anda masukan salah!";
			$kesalahan = true;
		}
		
	
		if(strlen($npassword) < 8)
		{
			$_SESSION['error_msg'] = "password baru wajib 8 digit keatas";
			$kesalahan = true;
		}
		
	
		if(isSet($npassword))
		{
			$npassword = $this->Sha256($npassword, $salt);
		}
		else
		{
			$npassword = $panggil_query[0]['password'];
		}
		
		if(!$kesalahan)
		{
			$result = $this->db->query("UPDATE `players` SET `password` = '".$npassword."' WHERE `username` = '".$username."'");
			if($result)
			{
				$_SESSION['success_msg'] = "you has a changed the password!";
			}
			else
			{
				$_SESSION['error_msg'] = "<b>Has Been!</b><br>ERROR.";
			}
		}
	}
	
/* admin panel */

	public function GetSettingsValue($setting_name)
	{
		$setting_name = $this->db->quote($setting_name);
		$result       = $this->db->select("SELECT * FROM `settings` WHERE `setting_name` = '".$setting_name."'");
		return $result[0]['setting_value'];
	}
	
	
	public function VerifCharacter($namacharacter)
	{
		$namacharacter = $this->db->quote($namacharacter);

		$checkverif = $this->db->select("SELECT * FROM `players` WHERE `username` = '$namacharacter'");
		if($checkverif)
		{
			
			if($checkverif[0]['isverif'] == 1)
			{
				$_SESSION['error_msg'] = "<b> ERROR!</b><br> Character ini sudah diverifikasi!";
			}
			else
			{
				$result = $this->db->query("UPDATE `players` SET `isverif` = 1 WHERE `username` = '".$namacharacter."'");
				if($result)
				{
					$_SESSION['success_msg'] = "Character ".$namacharacter." berhasil di verifikasi";
				}
				else
				{
					$_SESSION['error_msg'] = "<b>Terjadi </b><br> Kesalahan.";
				}
			}
		}
		else
		{
			$_SESSION['error_msg'] = "<b>ERROR!</b><br> Silahkan dicek kembali kode anda!";
		}
	}
	
	
	public function GetAllplayers()
	{
		$result = $this->db->select("SELECT * FROM `players`");
		return $result;
	}	

	public function CreateLog($log_by, $log_action, $log_text)
	{
		$log_action = $this->db->quote($log_action);
		$log_text   = $this->db->quote($log_text);
		$result     = $this->db->query("INSERT INTO `logs` (`id`, `action`, `text`, `by`, `date`) VALUES ('', '".$log_action."', '".$log_text."', '".$log_by."', '".date("Y-m-d H:i:s")."')");
	}

	public function GetAllAnnouncement()
	{
		$result = $this->db->select("SELECT * FROM `announcements` WHERE `visible` = 1 LIMIT 5");
		return $result;
	}
	
	public function LastLoginLog($username)
	{
		$id     = $this->GetIDFromUsername($username);
		$result = $this->db->select("SELECT * FROM `ucp_loginlogs` WHERE `user_id` = '".$id."'");
		return $result;
	}
	
	public function GetEmail($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		return $result[0]['email'];
	}
	public function GetCode($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		return $result[0]['verifkey'];
	}	
	
	public function GetAvatarURL($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		return $result[0]['avatar'];
	}
	
	public function GetIDFromUsername($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		return $result[0]['reg_id'];
	}

	public function getRealIpAddr()
	{
		if(!empty($_SERVER['HTTP_CF_CONNECTING_IP']))
		{
			$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		} elseif (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
		
	public function getUserAgent()
	{
		return $_SERVER["HTTP_USER_AGENT"];
	}
	
	public function getUserOS()
	{
		$useragent = $_SERVER["HTTP_USER_AGENT"];
		$user_load = $this->url("http://www.useragentstring.com/?uas=".urlencode($useragent)."&getJSON=all", 5);
		$user_load = json_decode($user_load);
		$os = $user_load->os_type." (".$user_load->os_name.")";
		return $os;
	}


	public function GetGender($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");

		if($result[0]['gender'] == 1)
		{
			$gender = '<i class="fa fa-male" aria-hidden="true"></i> Male';
		}
		if($result[0]['gender'] == 2)
		{
			$gender = '<i class="fa fa-female" aria-hidden="true"></i> Female';
		}
		return $gender;
	}
	public function Getumur($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		
		if(strcmp($result[0]['age'],'n/a')==0)
		{
			$umur = 'Not set ingame';
		}
		else
		{
			$birthDate = $result[0]['age'];
			$birthDate = explode("/", $birthDate);
			  //get age from date or birthdate
			$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));

			$umur = ucfirst($age) . ' Years (Date ' . ucfirst($result[0]['age']) . ')';
		}
		return $umur;
	}
	public function Getverif($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		
		if(strcmp($result[0]['isverif'],'0')==0)
		{
			$verif = 'Your character has not been verified';
		}
		else
		{
			$verif = 'Your character has been verified';
		}
		return $verif;
	}
	public function Getcs($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		
		if(strcmp($result[0]['cschar'],'0')==0)
		{
			$csstatus = 'Your Character Story has not been approved';
		}
		else
		{
			$csstatus = 'Your Character Story has been approved';
		}
		return $csstatus;
	}



	public function Getptime($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		$hour = $result[0]['hours'];
		$minute = $result[0]['minutes'];
		$second = $result[0]['seconds'];

		$ptime = ucfirst($hour). ' hours, ' . ucfirst($minute) . ' minutes, ' . ucfirst($second) . ' seconds';
	
		return $ptime;
	}



	public function Getintuser($username, $field)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		return $result[0][$field];
	}
	
	public function dolarformat($username, $field)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		
		$filed = $result[0][$field];

		$result = "$".number_format($filed, 0);
		return $result;
	}

	public function url($url, $timeout)
	{
		//$timeout = 10;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	} 
	
	public function GetFullURL()
	{
		$fullurl = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		return $fullurl;
	}
	
	public function Sha256($value, $salt)
	{
		return strtoupper(hash('sha256', $value . $salt));
	}
}
?>