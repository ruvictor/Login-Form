<?php
require_once(realpath(dirname(__FILE__) . '/../../config.php'));
class User{
	function Dashboard(){
		echo 'Hi!';
	}
	function generateCode($length){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;
		while (strlen($code) < $length){ 
			$code .= $chars[mt_rand(0,$clen)];
		}
		return $code;
	}
	function LoginForm(){
		return '
			<div class="form_block">
				<div id="title">
					Dashboard
				</div>
				<div class="body">
				<div class="login_response"></div>
					<form id="logform" action="" method="POST" >
						<input type="text" name="email" placeholder="Email" />
						<input type="password" name="pass" placeholder="Password" />
						<input type="submit" value="Log in" />
					</form>
				</div>
			</div>
		';
	}
	function CheckData($email, $pass){
		$db = new Connect;
		if(isset($email) && isset($pass)){
			$email=trim(stripslashes(htmlspecialchars($email)));
			$pass=trim(stripslashes(htmlspecialchars(md5(md5($pass)))));
			if(empty($email) or empty($pass)){
				echo "<div class=\"error\"><p><strong>ERROR:</strong> All fields are required!</p></div>";
			}else{
				$user = $db->prepare("SELECT * FROM users WHERE email = :email AND password = :pass LIMIT 1");
				$user->execute(array(
						'email' => $email,
						'pass'  => $pass
						));
				$info = $user->fetch(PDO::FETCH_ASSOC);
				if ($user->rowCount() == 0){
					echo "<div class=\"error\"><p><strong>ERROR:</strong> Login failed!</p></div>";
				}else{
					$hash = md5($this->generateCode(10));
					$upd = $db->prepare("UPDATE users SET session=:hash WHERE id=:ex_user");
					$upd->execute(array(
							'hash' 	=> $hash,
							'ex_user' 	=> $info['id']
					));
					setcookie("id", $info['id'], time()+60*60*24*30, "/", NULL);
					setcookie("sess", $hash, time()+60*60*24*30, "/", NULL);
					echo("<script>location.href = '/index.php';</script>");
				}
			}
		}
	}
	function Is_Login(){
		$db = new Connect;
		if(isset($_COOKIE['id']) and isset($_COOKIE['sess']))
		{
			$id = intval($_COOKIE['id']);
			$userdata = $db->prepare("SELECT id, session FROM users WHERE id =:id_user LIMIT 1");
			$userdata -> execute(array(
					'id_user' => $id
			));
			$userdataa = $userdata->fetch(PDO::FETCH_ASSOC);
			if(($userdataa['session'] != $_COOKIE['sess'])
			or ($userdataa['id'] != intval($_COOKIE['id'])))
				{
					setcookie('id', '', time() - 60*24*30*12, "/", NULL);
					setcookie('sess', '', time() - 60*24*30*12, "/", NULL);
					return FALSE;
				}else{
					return TRUE;
				}
		}else{
			return FALSE;
		}
	}
	
}
?>