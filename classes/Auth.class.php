<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once'classes/AuthDB.class.php';
require_once('PHPMailer/PHPMailerAutoload.php');

class Auth {
	private $_siteKey;
	private $_db;

	public function __construct()
	{
		$this->_siteKey = 'SLLLLSDIE*#&Slks*(Lsdf***,asdf';
	}

	public function randomString($length = 50)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string = '';

		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}

		return $string;
	}

	public function generateRandomString($length)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string = '';

		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}

		return $string;
	}

	protected function hashData($data)
	{
		return hash_hmac('sha512', $data, $this->_siteKey);
	}

	public function isAdmin()
	{
		//$selection being the array of the row returned from the database.
		if($selection['is_admin'] == 1) {
			return true;
		}

		return false;
	}

	public function createSAEUser($email, $password2, $name, $ctrlno, $provcode)
	{
		$this->_db = new AuthDB();

		//Generate users salt
		$user_salt = $this->randomString();

		//Salt and Hash the password
		$password = $user_salt . $password2;
		$password = $this->hashData($password);

		//is verify default 0
		$verification = 1;

		//is active default 0
		$active = 1;

		//user type default 1 for SEIS only
		$admin = 2;

		//status default 0 for first login change password
		$status = 0;


		//Create verification code
		$code = $this->randomString();

		//Commit values to database here.
		$created = $this->_db->createSAEUser($email, $password, $user_salt, $verification, $active, $admin, $code, $name, $status, $ctrlno, $provcode);

		$this->_db = null;

		if($created != false){
			//send verification
			// $this->sendVerification($email, $code, $pw, $name);

			//send password
			$this->sendSAEPassword($email, $password2, $name);

			return true;
		}

		return false;
	}

	private function sendSAEPassword($email, $password, $name) {


		//if code = null, then retrieve code from db
		// if ($code == null) {
		// 	$db = new AuthDB();
		// 	$code = $db->retrieveCode($email);
		// 	if (!$code) {
		// 		return false;
		// 	}
		// }

		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Username = EMAIL_USER;
        $mail->Password = EMAIL_PASS;
		$mail->setFrom("cicportal@creditinfo.gov.ph", "CIC No Reply");
		$mail->addAddress($email, $name);
		$mail->addBCC("access-internal@creditinfo.gov.ph");
		$mail->addBCC("karl.guevarra@creditinfo.gov.ph", "Karl Jorden Guevarra");

		$mail->Subject = "[CIC] SAE Portal - Account Creation";

		$message = '
		<div id="all">
		<div style="display:block;width:100%;max-width:650px;margin:0 auto" >
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr style="background-color: #f5f5f5;">
					<td style="border-top: 1px solid rgb(202,201,200);border-left: 1px solid rgb(202,201,200);border-right: 1px solid rgb(202,201,200); border-radius: 15px 50px 10px 10px;"><br></td>
				</tr>
				<tr style="background-color:#f5f5f5">
					<td align="center" style="border-left:1px solid rgb(202,201,200);border-right:1px solid rgb(202,201,200);padding-bottom: 15px" class="m_-3480498238929770810gmail-m_3586475887212474448null-pad-logo"><a href="http://www.creditinfo.gov.ph/" style="display:block;margin-bottom:10px" target="_blank"><img class="m_-3480498238929770810gmail-m_3586475887212474448head_1_logo-833 CToWUd" src="https://www.creditinfo.gov.ph/img/CICLogo.png" width="100%" style="background-color: #f5f5f5" alt="" style="max-width:645px;display:block"></a></td>
				</tr>
				<tr>
					<td>
					<div>
						<table bgcolor="#ffffff" width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid rgb(202,201,200);border-right:1px solid rgb(202,201,200);background-color: #f5f5f5" >
							<tbody>
								<tr height="20px" rowspan="1" colspan="3">
								<td style="max-width: 645px; word-wrap: break-word;font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:black;line-height:1.25;min-width:300px;padding:10px 30px 15px;" id="editTR">
								<p>Hi <b>'.$name.'</b>,<br/></p>
											
									<p align="justify">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We are pleased to inform you that you now have access to the SAE Portal. This portal is used by the Special Accessing Entity for uploading and validating SE-SAE certifications, as well as for merging and transferring credits from SAEs to SEs.
									</p>

									<p align="justify">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The login credentials are intended solely for the use and safekeeping of the individual to whom it was sent. All liability that will emanate from the disclosure, misuse, and/or mishandling of this information, directly and/or indirectly by the individual, will fall on the individual that authorized or allowed such disclosure, misuse and or mishandling of information, directly or indirectly.
									</p>

									<p>
									You may access the portal through this link <a href="https://www.creditinfo.gov.ph/saeportal" target="_blank">https://www.creditinfo.gov.ph/saeportal</a>
									</p>

									<p align="justify">
										Your credentials are as follows:
										<br>
										<table style="font-size: 14px">
											<tr>
												<td><b>Username:</b></td>
												<td><i>'.$email.'</i></td>
											</tr>
											<tr>
												<td><b>Password:</b></td>
												<td><i>'.$password.'</i></td>
											</tr>
										</table>
									</p>

									<p align="justify">Should you have any questions or clarifications, you may send an e-mail to <a href="mailto:cichelpdesk@creditinfo.gov.ph">cichelpdesk@creditinfo.gov.ph</a></p>
									<br>
									<p>Thank you.</p>
								
								</td>
								</tr>
							</tbody>
						</table>
					</div>
					</td>
				</tr>
				<tr>
					<table width="100%" bgcolor="#cac9c8" style="background-color:#cac9c8" border="0" cellpadding="0" cellspacing="0" align="center">
						<tbody>
							<tr>
								<td width="50%" valign="top" dir="ltr" class="m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448full m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448mobile-pad" style="padding:0px 30px">
									<table align="left" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
										<tbody>
											<tr>
												<td valign="top" class="m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448mobile-padding" style="padding-right:10px;padding-left:0px">
													<p style="margin:25px 0px 15px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:16px;color:rgb(20,72,133);text-align:left;line-height:16px;font-weight:bold">Follow Us:</p>
													<p style="margin:3px 0px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;color:rgb(130,128,128);text-align:left;font-weight:normal"><a href="https://facebook.com/creditinfo.gov.ph"><img src="www.creditinfo.gov.ph/img/fb.png" width="24" style="display:inline-block" class="m_-8036216849319893849m_-6971811544154621716CToWUd m_-8036216849319893849CToWUd CToWUd"><span style="vertical-align:top;display:inline-block;line-height:24px;margin-left:5px;color:rgb(187,115,36)"><span>/creditinfo.gov.ph</span></span></a></p>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td width="50%" valign="top" dir="ltr" style="padding:0px 30px">
									<table align="left" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
										<tbody>
											<tr>
												<td valign="top" class="m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448mobile-padding" style="padding-right:10px;padding-left:0px">
													<p style="margin:25px 0px 15px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:16px;color:rgb(20,72,133);text-align:left;line-height:16px;font-weight:bold">Visit us:</p>
													<p style="margin:3px 0px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;color:rgb(130,128,128);text-align:left;font-weight:normal"><a href="http://www.creditinfo.gov.ph/"><img src="www.creditinfo.gov.ph/img/web.png" width="24" style="display:inline-block" class="m_-8036216849319893849m_-6971811544154621716CToWUd m_-8036216849319893849CToWUd CToWUd"><span style="vertical-align:top;display:inline-block;line-height:24px;margin-left:5px;color:rgb(187,115,36)"><span>http://www.creditinfo.gov.ph</span></span></a></p>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table width="100%" cellpadding="0" style="border-radius:  0px 0px 50px 50px ; background-color:#cac9c8" cellspacing="0" >
						<tbody>
							<tr>
								&nbsp;
							</tr>
						</tbody>
					</table>
				</tr>
				<tr style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:10px;color:#666666;line-height:18px;padding-bottom:10px">
					<footer><small>© '.date('Y').' Credit Information Corporation. <a href="https://www.google.com/maps/place/Credit+Information+Corporation/@14.5560001,121.0148883,17z/data=!3m1!4b1!4m5!3m4!1s0x3397c90e63386907:0x2132dc0efdf6cf6e!8m2!3d14.5560001!4d121.017077"  target="_blank">  6th Floor, Exchange Corner Building 107 V.A. Rufino Street corner Esteban Street Legaspi Village,1229, Makati City. </a></small></footer>
				</tr>
			</table>
			<tr height="16px"></tr>
		</div>
		</div>';


		$mail->msgHTML($message);

		//send the message, check for errors
		if (!$mail->send()) {
			$errmsg .= "Mailer Error: " . $mail->ErrorInfo;
		} else {
			$msg .= "Message sent!";
		}
	}


	public function createUser($email, $password, $name, $access, $is_admin = 0)
	{
		$this->_db = new AuthDB();

		//Generate users salt
		$user_salt = $this->randomString();
			
		//Salt and Hash the password
		$npassword = $user_salt . $password;
		$npassword = $this->hashData($npassword);

		//Create verification code
		$code = $this->randomString();

		//Commit values to database here.
		$created = $this->_db->createUser($email, $npassword, $user_salt, $code, $name, $access);

		$this->_db = null;

		if($created != false){
			//send verification
			$this->sendVerification($email, $password, $name, $access);
			return true;
		}
			
		return false;
	}

	public function login($email, $password)
	{
		$this->_db = new AuthDB();

		//Select users row from database base on $email
		$selection = $this->_db->getUserInfo($email);

		//Salt and hash password for checking
		$password = $selection[0]['user_salt'] . $password;
		$password = $this->hashData($password);

		//Check email and password hash match database row
		if ($password == $selection[0]['password']) $match = true;
		else $match = false;

		//Convert to boolean
		$is_active = (boolean) $selection[0]['is_active'];
		$verified = (boolean) $selection[0]['is_verified'];

		if($match == true) {
			if($is_active == true) {
				if($verified == true) {
					//Email/Password combination exists, set sessions
					//First, generate a random string.
					$random = $this->randomString();
					//Build the token
					$token = $_SERVER['HTTP_USER_AGENT'] . $random;
					$token = $this->hashData($token);

					//Setup sessions vars
					if (!isset($_SESSION)) {
						session_start();
					}
					$_SESSION['token'] = $token;
					$_SESSION['user_id'] = $selection[0]['pkUserId'];
					$_SESSION['name'] = $selection[0]['fld_name'];
					$_SESSION['image'] = $selection[0]['fld_image'];
					$_SESSION['email'] = $email;
					$_SESSION['office'] = $selection[0]['fld_office'];
					$_SESSION['usertype'] = $selection[0]['is_admin'];

					//Delete old logged_in_member records for user
					$this->_db->removePriorLogins($selection[0]['pkUserId']);

					//Insert new logged_in_member record for user
					$inserted = $this->_db->markUserLoggedIn($selection[0]['pkUserId'], session_id(), $token);

					//Logged in
					if($inserted != false) {
						return 0;
					}

					return 3;
				}
				else {
					//Not verified
					return 1;
				}
			}
			else {
				//Not active
				return 2;
			}
		}

		//No match, reject
		return 4;
	}

	public function checkSession() {
		if (isset($_SESSION['user_id'])) {
			//session available, continue
			//get db routines
			$this->_db = new AuthDB();

			//Select the row
			$selection = $this->_db->checkSession($_SESSION['user_id']);

			if($selection) {
				if(session_id() == $selection['session_id'] && $_SESSION['token'] == $selection['token']) {
					//Id and token match, refresh the session for the next request
					$this->refreshSession();
					return true;
				}
				//TODO: Possibly remove session since exists ?
			}
		}
		return false;
	}

	private function refreshSession()
	{
		$db = new AuthDB();

		//Regenerate id
		session_regenerate_id();

		//Regenerate token
		$random = $this->randomString();
		//Build the token
		$token = $_SERVER['HTTP_USER_AGENT'] . $random;
		$token = $this->hashData($token);

		//Store in session
		$_SESSION['token'] = $token;
		
		if ($db->updateSession($_SESSION['user_id'], session_id(), $token)) {
			return true;
		}

		return false;
	}

	public function logout() {
		$db = new AuthDB();

		//destroy session
		session_destroy();

		//delete the row based on user_id
		$db->logoutUser($_SESSION['user_id']);
	}

	public function sendVerification($email, $password, $name, $access) {
		$acc = array("System Administrator", "Submitting / Accessing Entity", "Special Accessing Entity", "IT", "BDC", "Legal", "Marketing", "Board/CEO/SVP", "Head Operator", "Operator", "Billing", "Security (Head)", "Security (User)", "NOC (Head)", "NOC (User)", "Head Data Submission", "Compliance", "Application", "Press Release", "Test User" , "Dispute");

		//if code = null, then retrieve code from db
#		if ($code == null) {
#			$db = new AuthDB();
#			$code = $db->retrieveCode($email);
#			if (!$code) {
#				return false;
#			}
#		}

		## EMAIL SCRIPT FOR THE SUBMITTING CONTACT PERSON
		$mail = new PHPMailer(true);
		$mail->CharSet = 'utf-8';
		$mail->isSMTP();
		$mail->SMTPDebug  = 1;
		$mail->Host       = "smtp.gmail.com";
		$mail->Port       = "587";
		$mail->SMTPSecure = "tls";
		$mail->SMTPAuth   = true;
		$mail->Username = EMAIL_USER;
		$mail->Password = EMAIL_PASS;
		$mail->addReplyTo("cicportal@creditinfo.gov.ph", "CIC Portal Team");
		$mail->setFrom("cicportal@creditinfo.gov.ph", "CIC Portal Team");
		$mail->addAddress($email, $email);
		$mail->Subject  = "[CIC Portal] Account Creation";
		$body = "Hi ".$name.",<br/><br/><b><u>THIS IS A SYSTEM GENERATED EMAIL. PLEASE DO NOT REPLY</u></b><br/><br/>Welcome to the CIC Portal. "
			."You have ".$acc[$access]." access to this portal.  The login credentials are intended solely for the use "
			."and safekeeping of the individual to whom it was sent. All liability that will "
			."emanate from the disclosure, misuse, and/or mishandling of this information, directly "
			."and/or indirectly by the individual, will fall on the individual that authorized or allowed such disclosure, misuse and or mishandling of information, directly or indirectly.<br/><br/>"
			."<b>Please use this password :</b> <u>".$password."</u><br/><br/>Once logged in, please change your password immediately.<br/><br/>"
			."<b>Host link : </b><u>https://www.creditinfo.gov.ph/mycic</u><br/><br/><br/>"
#			."<b>Kindly acknowledge upon receipt of this email.</b><br/>(Please do not reply with your <b>provider code, username or password</b> included in the thread.)<br/><br/><br/>"
			."Thank you very much,<br/><br/><b>CIC Application Development Team</b>";
		$mail->WordWrap = 78;
		$mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images
		if($mail->send()){
			return true;
		}
		return false;
	}



/*
		//set email subject
		$subject = '';

		//set email body
		$message = 'This is to verify your new account has a valid email address';
		$message .= '<br /><br />Your verificatoin code is <b>'. $code .'</b>';
		$message .= '<br /><br />You can click <a href="http://' . SITE_HTTP . '/verify.php?email=' . $email . '&code=' . urlencode($code) . '">here</a> to verify automatically';
		$message .= '<br />or visit <a href="http://'. SITE_HTTP . '/verify.php">http://' . SITE_HTTP . '/verify.php</a>';
		$message .= '<br /><br />Thank you for your coorperation';

		//set email headers
		$headers = 'From: ' . FROM_EMAIL . "\r\n" .
		    'Reply-To: ' . FROM_EMAIL . "\r\n" .
			'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();


		//send email
		if (mail($email, $subject, $message, $headers)) {
			return true;
		}

		return false;
	}
*/
	public function checkVerification($email, $code) {
		$db = new AuthDB();

		if ($db->checkVerification($email, $code) > 0) {
			return true;
		}

		return false;
	}

	// public function forgotPassword($email) {
	// 	$this->_db = new AuthDB();

	// 	//Generate users salt
	// 	$user_salt = $this->randomString();

	// 	//Salt and Hash the password
	// 	$password2 = $this->randomString(8);
	// 	$password = $user_salt . $password2;
	// 	$password = $this->hashData($password);

	// 	//Commit values to database here.
	// 	$created = $this->_db->newPassword($email, $password, $user_salt);

	// 	$this->_db = null;

	// 	if($created > 0) {
	// 		//send new pw via email
	// 		// echo $password2;
	// 		$this->sendNewPassword($email, $password2);
	// 		return true;
	// 	}

	// 	return false;
	// }


	public function forgotPassword($email) {
		$this->_db = new AuthDB();

		$selection = $this->_db->getUserInfo($email);

		if ($selection[0]['pkUserId']) {

			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$string = '';

			for ($p = 0; $p < 12; $p++) {
				$string .= $characters[mt_rand(0, strlen($characters) - 1)];
			}

			$user = $selection[0]['pkUserId'];
			$code = $string;
			if(!empty($email)) {
				//send new pw via email
				// echo $password2;
				$this->sendResetPasswordInstruction($email, $user, $code);
				return true;
			}
		}

		return false;
	}

	public function sendResetPasswordInstruction($email, $id, $code) {
		//set email subject
		## EMAIL SCRIPT FOR THE SUBMITTING CONTACT PERSON

		$mail = new PHPMailer(true);
		$mail->CharSet = 'utf-8';
		$mail->isSMTP();
		$mail->SMTPDebug  = 0;
		$mail->Host       = "smtp.gmail.com";
		//$mail->Host       = gethostbyname('ssl://smtp.gmail.com');;
		$mail->Port       = "587";
		//$mail->Port       = "465";
		$mail->SMTPSecure = "tls";
		$mail->SMTPAuth   = true;  
		$mail->Username = EMAIL_USER;
		$mail->Password = EMAIL_PASS;
		$mail->addReplyTo("cicportal@creditinfo.gov.ph", "CIC Portal Team");
		$mail->setFrom("cicportal@creditinfo.gov.ph", "CIC Portal Team");
		$mail->addAddress($email, $email);
		
		$mail->Subject  = "CIC Portal (MyCIC) Reset Password";
		$body = "<b>Hello!</b><br/><br/><p>We've received a request to reset the password for the CIC Portal account associated with ".$email."</p><p>No changes have been made to your account yet.</p><p>You can reset your password by clicking the link below:</p>"
			."<br /><a href='https://www.creditinfo.gov.ph/mycic/new-password.php?email=".$email."&code=".$code.'.'.$id."'>https://www.creditinfo.gov.ph/mycic/new-password.php?email=".$email."&code=".$code.'.'.$id."</a><br /><br /><p>If you did not request a new password, please let us know immediately.</p><br/><br/>From, <br>CIC Portal Team";
		$mail->WordWrap = 78;
		$mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images

		if($mail->send()){
			return true;
		}
		return false;
	}

	public function sendNewPassword($email, $password) {
		//set email subject
		## EMAIL SCRIPT FOR THE SUBMITTING CONTACT PERSON
		$mail = new PHPMailer(true);
		$mail->CharSet = 'utf-8';
		$mail->isSMTP();
		$mail->SMTPDebug  = 0;
		$mail->Host       = "smtp.gmail.com";
		//$mail->Host       = gethostbyname('ssl://smtp.gmail.com');;
		$mail->Port       = "587";
		//$mail->Port       = "465";
		$mail->SMTPSecure = "tls";
		$mail->SMTPAuth   = true;  
		$mail->Username = EMAIL_USER;
		$mail->Password = EMAIL_PASS;
		$mail->addReplyTo("cicportal@creditinfo.gov.ph", "CIC Portal Team");
		$mail->setFrom("cicportal@creditinfo.gov.ph", "CIC Portal Team");
		$mail->addAddress($email, $email);
		
		$mail->Subject  = "Password Change";
		$body = "<b><u>THIS IS A SYSTEM GENERATED EMAIL. PLEASE DO NOT REPLY<u/></b><br/><br/>Please find your new password below. Once logged in, please change your password<br/><br/>"
			."<br /><br />Your new password is <b>". $password ."</b><br/><br/>From,<br/><br/>CIC Portal Team";
		$mail->WordWrap = 78;
		$mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images

		if($mail->send()){
			return true;
		}
		return false;
	}

	//Old changePassword function
	// public function changePassword($userId, $email, $currentPassword, $newPassword) {
	// 	$this->_db = new AuthDB();

	// 	//Select users row from database base on $email
	// 	$selection = $this->_db->getUserInfo($email);

	// 	//Salt and hash password for checking
	// 	$currentPassword = $selection[0]['user_salt'] . $currentPassword;
	// 	$currentPassword = $this->hashData($currentPassword);

	// 	//Check email and password hash match database row
	// 	if ($currentPassword == $selection[0]['password']) $match = true;
	// 	else $match = false;


	// 	//Convert to boolean
	// 	$is_active = (boolean) $selection[0]['is_active'];
	// 	$verified = (boolean) $selection[0]['is_verified'];

	// 	if($match == true) {
	// 		if($is_active == true) {
	// 			if($verified == true) {
	// 				$salt = $this->randomString();
	// 				$newPassword = $salt . $newPassword;
	// 				$newPassword = $this->hashData($newPassword);
	// 				$cp = $this->_db->updatePassword($userId, $newPassword, $salt);
	// 				if ($cp > 0) {
	// 					return true;
	// 				}
	// 			}
	// 		}
	// 	}

	// 	return false;
	// }

	//Latest changePassword function
	public function changePassword($userId, $email, $newPassword) {
		$this->_db = new AuthDB();

		//Select users row from database base on $email
		$selection = $this->_db->getUserInfo($email);

		//Convert to boolean
		$is_active = (boolean) $selection[0]['is_active'];
		$verified = (boolean) $selection[0]['is_verified'];
		

		if($is_active == true) {
			if($verified == true) {
				$salt = $this->randomString();
				$newPassword = $salt . $newPassword;
				$newPassword = $this->hashData($newPassword);
				$cp = $this->_db->updatePassword($userId, $newPassword, $salt);
				if ($cp > 0) {
					return true;
				}
			}
		}

		return false;
	}

	public function checkPassword($admin, $email, $password){
		$this->_db = new AuthDB();

		//Select users row from database base on $email
		$selection = $this->_db->getUserInfo($email);

		//Salt and hash password for checking
		$password = $selection[0]['user_salt'] . $password;
		$password = $this->hashData($password);

		//Check email and password hash match database row
		if ($password == $selection[0]['password']) $match = true;
		else $match = false;

		if($match == true) {
			if ($admin == 0 || $admin == 4) {
				return 1;
			} else {
				return 2;
			}
		} else {
			return 0;
		}
	}
}