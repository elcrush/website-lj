<?php	
/*

	MADE WITH ANANG FEBRI PANGESTU
	LOST JAVA INDONESIA

*/
session_start();
require 'ucp_db.class.php';
define("INVITE_FILE","tmp/lastinvite.dat");
define("INVITE_STALE",4500); //seconds
define("BOT_TOKEN","ODYxNDA3NDc1NTQ2MDYyODg5.Gf9k8i.WjVKpcHpizSZvraCJy7m-BJBj4Wb3uhBMY_ShM");
define("CHANNEL_ID","969606358225793065");


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
		if($result[0]['banned'] == 1)
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
		if($result[0]['admin'] >= 1)
		{
			return true;
		}
		else
		{
			return false;
		}
		return false;		
	}
	public function IsAdminHigh($username)
	{
		$username = $this->db->quote($username);
		$result   = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		if($result[0]['admin'] >= 6)
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
					?>
						<script src="js/jquery-3.4.1.min.js"></script>
						<script src="js/sweetalert2.all.min.js"></script>
						<script>
							Swal.fire("Opss","Akun anda telah di banned.","warning");
							e.preventDefault();
						</script>
					<?php
				}
				else
				{
					$_SESSION['logged']   = true;
					$_SESSION['username'] = $username;
					$this->db->query("INSERT INTO `ucp_loginlogs` (`id`, `user_id`, `ip`, `date`, `useragent`, `os`, `reason`) VALUES (NULL, '".$result[0]['reg_id']."', '".$this->getRealIpAddr()."', '".date("Y-m-d H:i:s")."', '".$this->getUserAgent()."', '', 'Login');");
					?>
						<script src="js/jquery-3.4.1.min.js"></script>
						<script src="js/sweetalert2.all.min.js"></script>
						<script>
							Swal.fire("Congratulations","Anda berhasil login. Mohon tunggu sesaat.","success", "");
							e.preventDefault();
						</script>
						<meta http-equiv='refresh' content='2'>
					<?php		
				}
			}
			else
			{
				?>
					<script src="js/jquery-3.4.1.min.js"></script>
					<script src="js/sweetalert2.all.min.js"></script>
					<script>
						Swal.fire("Opss..","Password yang anda masukan salah!","warning");
						e.preventDefault();
					</script>
				<?php
				return true;
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
	public function Register($username, $email, $password, $ppassword, $last_ip, $captcha)
	{
		$username    = $this->db->quote($username);
		$email       = $this->db->quote($email);
		$password    = $this->db->quote($password);
		$ppassword   = $this->db->quote($ppassword);
		$captcha   = $this->db->quote($captcha);

    	
		if(!$this->db->exists("players", "username", $username))
		{
			if(!$this->db->exists("players", "email", $email))
			{
				if(!$this->db->exists("players", "last_ip", $last_ip))
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
					if($_SESSION['captcha_code'] != $captcha)
					{
						?>
						<script src="js/jquery-3.4.1.min.js"></script>
						<script src="js/sweetalert2.all.min.js"></script>
						<script>
							Swal.fire("Opss..","Captcha Salah!","warning");
							e.preventDefault();
						</script>
						<?php
						$kesalahan = true;
					}	
					if(!$kesalahan)
					{
						$panjanghash = 16;
						$salt = base64_encode(random_bytes(ceil(0.75*$panjanghash)));
						$password = $this->Sha256($password, $salt);
						$token= md5(rand(0,1000));
						$verifcode = md5(rand(0,1000));
						$verification_code= rand(11111,99999);
						$verifkey= rand(11111, 999999);

						$result = $this->db->query("INSERT INTO `test`.`players` (`reg_id`, `username`, `password`, `salt`, `email`, `last_ip`,`verifkey`, `verifcode`, `verification_code`) VALUES (NULL, '".$username."', '".$password."', '".$salt."', '".$email."','".$this->getRealIpAddr()."', '".$token."', '".$verifcode."', '".$verification_code."');");
						if($result)
						{
							function invite_discord($options)
							{
								//prevent too many messages
								$f=file_exists(INVITE_FILE);
								if ($f == false) $last=time()-500000;
								else $last=@filemtime(INVITE_FILE);
								$now=time();
								$el=$now - $last;
								if ($el < INVITE_STALE)
								{
									return file_get_contents(INVITE_FILE);
								} // not last invite
								// Replace the URL with your own webhook url
								$url = "https://discordapp.com/api/v6/channels/" . CHANNEL_ID . "/invites";

								$inviteobj=json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_HTTPHEADER,
								array( "Authorization: Bot " . BOT_TOKEN,
										'Content-Type: application/json',
										'Referer: https://discordapp.com/channels/@me'
								));

								curl_setopt_array( $ch, [
								CURLOPT_URL => $url,
								CURLOPT_POST => true,
								CURLOPT_POSTFIELDS => $inviteobj]);


								$response = curl_exec( $ch );
								$works=strpos($response,'{"code":' );
								if ($works === false) return "";
								file_put_contents(INVITE_FILE,$response);
								curl_close( $ch );
								return $response;
							}
							$inviteobj = [
							   /*
							   * How long link should last (0 for forever)
							    */
							    "max_age" => 0,
							    /*
							     * The total users that can use the invite (here I use 1)
							     */
							    "max_uses" => 1,
							   	];
								$r=invite_discord($inviteobj);
								$inviteresp=json_decode($r,true);
								$code=$inviteresp["code"];
								
								$url = 'https://'.$_SERVER['SERVER_NAME'].'/activation.php?t='.$verifcode;                                // Set email format to HTML
							
								$output = '<div class=""><div class="aHl"></div><div id=":oa" tabindex="-1"></div><div id=":nz" class="ii gt" jslog="20277; u014N:xr6bB; 4:W251bGwsbnVsbCxbXV0."><div id=":ny" class="a3s aiL msg-1863698435439421271"><u></u>
									<div style="background:#f9f9f9">
									  <div style="background-color:#f9f9f9"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px" align="left"><div style="color:#737f8d;font-family:Whitney,Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif;font-size:16px;line-height:24px;text-align:left">
            						<h2 style="font-family:Whitney,Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif;font-weight:500;font-size:20px;color:#4f545c;letter-spacing:0.27px">Hey '.$username.',</h2>
									<p>Thanks for registering in Lost Java Indonesia, Please click this button in the bottom for verification, isn not you? dont click this button!.</p>
									<p><strong>IP Address:</strong> '.$this->getRealIpAddr().'<br>
									<strong>Username:</strong> '.$username.'</p>
									<tbody><tr><td style="word-break:break-word;font-size:0px;padding:10px 25px;padding-top:20px" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate" align="center" border="0"><tbody><tr><td style="border:none;border-radius:3px;color:white;padding:15px 19px" align="center" valign="middle" bgcolor="#5865f2"><a href="'.$url.'" data-saferedirecturl="https://www.google.com/url?q='.$url.'&amp;source=gmail&amp;ust=1653048248094000&amp;usg=AOvVaw3RNziSX6ZgkkrmjZhFxOnh" style="text-decoration:none;line-height:100%;background:#5865f2;color:white;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:15px;font-weight:normal;text-transform:none;margin:0px">
						            Verify Account</a></td></tr></tbody>
						          </table></td></tr></center>
								<br><p><strong>Silahkan Join Discord Baru LJRP Untuk Whitelist
								<br>https://discord.gg/'.$code.'</p>						
								<br<table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-break:break-word;font-size:0px;padding:0px" align="center"><div style="color:#99aab5;font-family:Whitney,Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif;font-size:12px;line-height:24px;text-align:center">
								    Sent by Lost Java Indonesia •
								      <a href="https://lostjavaindonesia.com" style="color:#1eb0f4;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://lostjavaindonesia.com&amp;source=gmail&amp;ust=1653048248094000&amp;usg=AOvVaw3RNziSX6ZgkkrmjZhFxOnh">check our website</a>
								      • <a href="https://instagram.com/lostjavaroleplay" style="color:#1eb0f4;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://instagram.com/lostjavaroleplay&amp;source=gmail&amp;ust=1653048248094000&amp;usg=AOvVaw3zBfeIwXrXwj4cWlqfbaQY">@LJI</a>
								    </div></td></tr><tr><td style="word-break:break-word;font-size:0px;padding:0px" align="left"><div style="color:#000000;font-family:Whitney,Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif;font-size:13px;line-height:22px;text-align:left">
								      <img src="https://ci4.googleusercontent.com/proxy/Vkm5xgh99TLXcAG5mFfXsSzT_8E29CCT8jDXmIMOGzbYUE94BX2s1wGXY1WbBJ7KOg4P-_wh67fKi0pevKoN9JQacHin3_P5SulWyAIezui2Ed7ezd730Wg_Y9iSGrCs34M3kZ4f3KpTmbC3RepB0npkU-2K1M9P_jtSNYFwYjCiMpUmwkmZKKX9HtTmRujh5LD8CVzI-Aus9YroMAQ-rXXBLqjp4fs8KKl49FM=s0-d-e1-ft#https://discord.com/api/science/799935142612828162/c57efb4b-12fc-41f4-807c-4d5b5c49db4b.gif?properties=eyJlbWFpbF90eXBlIjogInVzZXJfaXBfYXV0aG9yaXplIn0%3D" width="1" height="1" class="CToWUd">
								    </div></td></tr></tbody></table></div</div>';

								$mail = new PHPMailer();
								$mail->IsSMTP();
								$mail->SMTPDebug = 0;
								$mail->SMTPAuth = true;
								$mail->SMTPSecure = 'tls';
								$mail->Host = 'smtp.gmail.com';
								$mail->Port = 587;                             
								$mail->isHTML(true);
								$mail->Username = 'lostjavaindonesia@gmail.com';                 
								$mail->Password = 'jvcbczwoswjqfaqx';                                
								$mail->setFrom('lostjavaindonesia@gmail.com', "noreply");
								$mail->Subject = 'noreply';
								$mail->Body    = $output;
								$mail->addAddress($email);
								if($mail->send())
								{
									?>
									<script src="js/jquery-3.4.1.min.js"></script>
									<script src="js/sweetalert2.all.min.js"></script>
									<script>
										Swal.fire("Congratulations","Please Verify Your Email!","success");
										e.preventDefault();
									</script>
									<?php
									$timestamp = date("c", strtotime("now"));
									$url = "https://discord.com/api/webhooks/973653537408053339/S_iHjoOq_KwLVlomXCiPyW0qNy7TytaT6BW43IpzPn-RkEFMU-lj0mndJCz0AFBT-eev";
									$headers = [ 'Content-Type: application/json; charset=utf-8' ];
									$POST = [ 'username' => 'Register Log UCP', 'content' => '**__'.$username.'__** Has been registered', "avatar_url" => "https://cdn.discordapp.com/avatars/861407475546062889/01e841fd54eb2df20fc968cb8097ccb1.png?size=512", "embeds" => [[ "title" => "LOG REGISTER UCP", "color" => hexdec( "FFFFFF" ), "timestamp" => $timestamp, "type" => "rich", "url" => "https://ucp.lostjavaindonesia.com", "footer" => ["text" => "Lost Java Indonesia", "icon_url" => "https://ucp.lostjavaindonesia.com/assets/images/lostjava.png?size=375"]]]];

									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, $url);
									curl_setopt($ch, CURLOPT_POST, true);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
									curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
									$response   = curl_exec($ch);
								}
								else if(!$mail->send())
								{
									echo 'Message could not be sent.';
									echo 'Mailer Error: ' . $mail->ErrorInfo;
								}
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
						else
						{
							?>
								<script src="js/jquery-3.4.1.min.js"></script>
								<script src="js/sweetalert2.all.min.js"></script>
								<script>
									Swal.fire("Opss..","Terjadi kesalahan! UCP","warning");
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
							Swal.fire("Opss..","Anda tidak bisa membuat akun lagi!","warning");
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
	public function UpdateProfile($username, $tpassword, $npassword, $ppassword)
	{
		$username       = $this->db->quote($username);
		$tpassword      = $this->db->quote($tpassword);
		$npassword      = $this->db->quote($npassword);
		$ppassword      = $this->db->quote($ppassword);
		
		$getsalt = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		$salt = $getsalt[0]['salt'];		

		$panggil_query = $this->db->select("SELECT * FROM `ucp` WHERE `username` = '$username'");
		if($panggil_query[0]['password'] != $this->Sha256($tpassword, $salt))
		{
			?>
				<div class="alert alert-warning">Password anda salah!</div>
			 <?php
			$kesalahan = true;
		}
		
	
		if(strlen($npassword) < 8)
		{
			?>
				<div class="alert alert-warning">Password baru wajib 8 digit keatas!</div>
			<?php
			$kesalahan = true;
		}
		
		if($npassword != $ppassword)
		{
			?>
				<div class="alert alert-warning">Your password and repassword not match!</div>
			<?php
			$kesalahan = 1;
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
				$this->db->query("INSERT INTO `ucp_loginlogs` (`id`, `user_id`, `ip`, `date`, `useragent`, `os`, `reason`) VALUES (NULL, '".$result['reg_id']."', '".$this->getRealIpAddr()."', '".date("Y-m-d H:i:s")."', '".$this->getUserAgent()."', '', 'Change Password');");
				echo '<div class="alert alert-success">Password has changed!</a></div>';
			}
			else
			{
				?>
				<div class="alert alert-warning">Terjadi Kesalahan!</div>
				<?php
			}
		}
	}
	public function resetpassword($password, $ppassword, $verifcode)
	{
		$password    = $this->db->quote($password);
		$ppassword   = $this->db->quote($ppassword);
		$verifcode=$_GET['t'];
		$getsalt = $this->db->select("SELECT * FROM `players` WHERE `resetpw` = '$verifcode'");
		$salt = $getsalt[0]['salt'];		

		$panggil_query = $this->db->select("SELECT * FROM `players` WHERE `resetpw` = '$verifcode'");
	
		if(strlen($password) < 8)
		{
			echo '<div class="alert alert-warning">Password baru wajib 8 digit keatas!</div>';
			$kesalahan = true;
		}
		
		if($password != $ppassword)
		{
			echo '<div class="alert alert-warning">Your password and repassword not match!</div>';
			$kesalahan = true;
		}
		if(isSet($password))
		{
			$password = $this->Sha256($password, $salt);
		}
		else
		{
			$password = $panggil_query[0]['password'];
		}
		
		if(!$kesalahan)
		{
			$result = $this->db->query("UPDATE `players` SET `password` = '".$password."' WHERE `resetpw` = '".$verifcode."'");
			if($result)
			{
				echo '<div class="alert alert-success">Password berhasil di ubah, silahkan <a href="/">Login</a></div>';
			}
			else
			{
				echo '<div class="alert alert-warning">Terjadi Kesalahan!</div>';
			}
		}
	}
	public function ForgotPassword($email)
	{
		$email       = $this->db->quote($email);
		if($this->db->exists("players", "email", $email))
		{
			if(filter_var($email, !FILTER_VALIDATE_EMAIL))
			{
				echo '<div class="alert alert-warning">is not a valid email address!</div>';
				$kesalahan = true;
			}
			if(!$kesalahan)
			{
				$verifcode = md5(rand(0,1000));
				$result = $this->db->query("UPDATE `players` SET `resetpw` = '".$verifcode."' WHERE `email` = '".$email."'");
				if($result)
				{
					$url = 'https://'.$_SERVER['SERVER_NAME'].'/resetpassword.php?t='.$verifcode;                                // Set email format to HTML
								
					$output = '<div><center>Please click this link for reseting password!<br>
					<br>------------------------
					<br>'.$url.' 
					<br>------------------------
					<br>Regards,
					<br>
					Lost Java Indonesia</div></center>';

					$mail = new PHPMailer();
					$mail->IsSMTP();
					$mail->SMTPDebug = 0;
					$mail->SMTPAuth = true;
					$mail->SMTPSecure = 'tls';
					$mail->Host = 'smtp.gmail.com';
					$mail->Port = 587;                             
					$mail->isHTML(true);
					$mail->Username = 'lostjavaindonesia@gmail.com';                 
					$mail->Password = 'jvcbczwoswjqfaqx';                                
					$mail->setFrom('lostjavaindonesia@gmail.com', "noreply");
					$mail->Subject = 'noreply';
					$mail->Body    = $output;
					$mail->addAddress($email);
					if($mail->send())
					{
						?>
						<script src="js/jquery-3.4.1.min.js"></script>
						<script src="js/sweetalert2.all.min.js"></script>
						<script>
							Swal.fire("Congratulations","Please check your email!","success");
							e.preventDefault();
						</script>
						<?php
						$timestamp = date("c", strtotime("now"));
						$url = "https://discord.com/api/webhooks/973670884780957706/pcLV8akIhFGPgngP67g1B-BJQ2S7DDHsi9lngFw0DnBqpzhv5VCttIVKQRSeP63Rcf1O";
						$headers = [ 'Content-Type: application/json; charset=utf-8' ];
						$POST = [ 'username' => 'Reset Password Log UCP', 'content' => '**__'.$email.'__** Has been reseting password', "avatar_url" => "https://cdn.discordapp.com/avatars/861407475546062889/01e841fd54eb2df20fc968cb8097ccb1.png?size=512", "embeds" => [[ "title" => "LOG RESET PASSWORD UCP", "color" => hexdec( "FFFFFF" ), "timestamp" => $timestamp]]];

						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
						$response   = curl_exec($ch);
					}
					else if(!$mail->send())
					{
						echo 'Message could not be sent.';
						echo 'Mailer Error: ' . $mail->ErrorInfo;
					}
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
			else
			{
				?>
					<script src="js/jquery-3.4.1.min.js"></script>
					<script src="js/sweetalert2.all.min.js"></script>
					<script>
						Swal.fire("Oops","Terjadi Kesalahan!","warning");
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
			   	Swal.fire("Opss..","Email tidak ditemukan!","warning");
			    e.preventDefault();
			</script>
			<?php
		}		
	}
	
/* admin panel */

	public function GetSettingsValue($setting_name)
	{
		$setting_name = $this->db->quote($setting_name);
		$result       = $this->db->select("SELECT * FROM `settings` WHERE `setting_name` = '".$setting_name."'");
		return $result[0]['setting_value'];
	}
	
	public function ChangeUsername($usernamelama, $usernamebaru)
	{
		$usernamelama      = $this->db->quote($usernamelama);
		$usernamebaru      = $this->db->quote($usernamebaru);
		
		$panggil_query = $this->db->select("SELECT * FROM `ucp` WHERE `username` = '$usernamelama'");
		if($panggil_query)
		{
			$result = $this->db->query("UPDATE `ucp` SET `username` = '".$usernamebaru."' WHERE `username` = '".$usernamelama."'");
			if($result)
			{
				?>
			    <script src="js/jquery-3.4.1.min.js"></script>
			    <script src="js/sweetalert2.all.min.js"></script>
			    <script>
			       	Swal.fire("Congratulation","Username Has been Changed!","success");
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
		else
		{
			?>
			    <script src="js/jquery-3.4.1.min.js"></script>
			    <script src="js/sweetalert2.all.min.js"></script>
			    <script>
			       	Swal.fire("Opss..","Tidak ada username seperti itu!","warning");
			        e.preventDefault();
			     </script>
			 <?php
		}
	}
	public function SetDonate($usernamedonate, $totalgold)
	{
		$usernamedonate      = $this->db->quote($usernamedonate);
		$totalgold      = $this->db->quote($totalgold);
		
		$panggil_query = $this->db->select("SELECT * FROM `players` WHERE `username` = '$usernamedonate'");
		if($panggil_query)
		{
			$result = $this->db->query("UPDATE `players` SET `gold` = '".$totalgold."' WHERE `username` = '".$usernamedonate."'");
			if($result)
			{
				?>
			    <script src="js/jquery-3.4.1.min.js"></script>
			    <script src="js/sweetalert2.all.min.js"></script>
			    <script>
			       	Swal.fire("Congratulation","Gold Telah Ditambah!","success");
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
		else
		{
			?>
			    <script src="js/jquery-3.4.1.min.js"></script>
			    <script src="js/sweetalert2.all.min.js"></script>
			    <script>
			       	Swal.fire("Opss..","Tidak ada username seperti itu!","warning");
			        e.preventDefault();
			     </script>
			 <?php
		}
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
				$result = $this->db->query("UPDATE `ucp` SET `isverif` = 1 WHERE `username` = '".$namacharacter."'");
				if($result)
				{
					$this->db->query("INSERT INTO `ucp_veriflogs` (`id`, `user_id`, `admin`, `verif`-) VALUES (NULL, '".$result[0]['reg_id']."', '".$result[0]['username']."', '".$namacharacter."');");
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
			$_SESSION['error_msg'] = "<b>ERROR!</b><br> Silahkan dicek kembali nama character!";
		}
	}
	public function VerifEmail($verification_code)
	{
		$verification_code = $this->db->quote($verification_code);
		$checkverifikasi = $this->db->select("SELECT * FROM `ucp` WHERE `verification_code` = '$verification_code'");
		if($checkverifikasi)
		{
			
			if($checkverifikasi[0]['emailverif'] == 1)
			{
				?>
			        <script src="js/jquery-3.4.1.min.js"></script>
				    <script src="js/sweetalert2.all.min.js"></script>
				    <script>
				            Swal.fire("Opss..","Character sudah diverifikasi!","warning");
			            	e.preventDefault();
			        </script>
			    <?php
			}
			else
			{
				$result = $this->db->query("UPDATE `ucp` SET `emailverif` = 1 WHERE `verification_code` = '".$verification_code."'");
				if($result)
				{
					?>
			        <script src="js/jquery-3.4.1.min.js"></script>
				    <script src="js/sweetalert2.all.min.js"></script>
				    <script>
				            Swal.fire("Congratulations","Character berhasil diverifikasi!","success");
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
				    Swal.fire("Opss..","Silahkan cek OTP anda!","warning");
			        e.preventDefault();
			    </script>
			<?php
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
	public function Message($username)
	{
		$id     = $this->GetIDFromUsername($username);
		$result = $this->db->select("SELECT * FROM `message` WHERE `user_id` = '".$id."'");
		return $result;
	}
	public function Getprofileuser($username, $field)
	{
		$result = $this->db->select("SELECT * FROM `ucp` WHERE `username` = '$username'");
		return $result[0][$field];
	}
	public function GetEmail($username)
	{
		$result = $this->db->select("SELECT * FROM `ucp` WHERE `username` = '$username'");
		return $result[0]['email'];
	}
	public function GetCode($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		return $result[0]['verifkey'];
	}	
	
	public function GetAvatarURL($username)
	{
		$result = $this->db->select("SELECT * FROM `ucp` WHERE `username` = '$username'");
		return $result[0]['avatar'];
	}
	
	public function GetIDFromUsername($username)
	{
		$result = $this->db->select("SELECT * FROM `ucp` WHERE `username` = '$username'");
		return $result[0]['reg_id'];
	}
	public function emailplayer($username)
	{
		$result = $this->db->select("SELECT * FROM `ucp` WHERE `username` = '$username'");
		return $result[0]['email'];
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
		$result = $this->db->select("SELECT * FROM `ucp` WHERE `CharName` = '$username'");

		if($result[0]['gender'] == 1)
		{
			$gender = '<i class="fa fa-male" aria-hidden="true"></i> ( Male )';
		}
		if($result[0]['gender'] == 2)
		{
			$gender = '<i class="fa fa-female" aria-hidden="true"></i> ( Female )';
		}
		return $gender;
	}
	public function GetGender2($username)
	{
		$result = $this->db->select("SELECT * FROM `ucp` WHERE `CharName2` = '$username'");

		if($result[0]['gender'] == 1)
		{
			$gender = '<i class="fa fa-male" aria-hidden="true"></i> ( Male )';
		}
		if($result[0]['gender'] == 2)
		{
			$gender = '<i class="fa fa-female" aria-hidden="true"></i> ( Female )';
		}
		return $gender;
	}
	public function GetGender3($username)
	{
		$result = $this->db->select("SELECT * FROM `ucp` WHERE `CharName3` = '$username'");

		if($result[0]['gender'] == 1)
		{
			$gender = '<i class="fa fa-male" aria-hidden="true"></i> ( Male )';
		}
		if($result[0]['gender'] == 2)
		{
			$gender = '<i class="fa fa-female" aria-hidden="true"></i> ( Female )';
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
		$result = $this->db->select("SELECT * FROM `ucp` WHERE `username` = '$username'");
		
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
	public function Getemailverif($username)
	{
		$result = $this->db->select("SELECT * FROM `players` WHERE `username` = '$username'");
		
		if(strcmp($result[0]['emailverif'],'0')==0)
		{
			$emailverifstatus = 'Your email has not been verified';
		}
		else
		{
			$emailverifstatus = 'Your email has been verified';
		}
		return $emailverifstatus;
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
	public function Getdcuser($discord, $field)
	{
		$result = $this->db->select("SELECT * FROM `ucp` WHERE `discord` = '$discord'");
		return $result[0][$field];
	}
	
	public function Getintuser1($username, $field)
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
		$fullurl = "https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		return $fullurl;
	}
	
	public function Sha256($value, $salt)
	{
		return strtoupper(hash('sha256', $value . $salt));
	}
	public function base_url()
	{
    	return 'http'.(isset($_SERVER['HTTPS'])?'s':'').'://'.$_SERVER['SERVER_NAME'];
	}
}
?>