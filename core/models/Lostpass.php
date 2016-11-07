<?php
namespace Ocrend\Core\Models;
use Ocrend\Core\Kernel\Models;
use Ocrend\Core\Kernel\OCREND;
use Ocrend\Core\Kernel\Helper;
# Seguridad
defined('INDEX_DIR') OR exit('Ocrend software says .i.');

//------------------------------------------------

final class Lostpass extends Models implements OCREND {

	public function __construct() {
		parent::__construct();
	}

	final public function RepairPass(array $data) : array {

		$mail = $this->db->scape($data['email']);
		$user = $this->db->select('id,user','users',"email='$mail'",'LIMIT 1');

		if(false == $user) {
			$success = 0;
			$message = 'El <b>email</b> introducido no existe.';
		} else {
			$id = $user[0]['id'];
			$u = uniqid();
			$keypass = time();

			$HTML = 'Hola <b>'. $user[0]['user'] .'</b>, has solicitado recuperar tu contraseña perdida, si no has realizado esta acción no necesitas hacer nada.
					<br />
					<br />
					Para cambiar tu contraseña has <a href="'. URL .'lostpass/cambiar/'.$keypass.'" target="_blank">clic aquí</a>.';

			Helper::load('emails');
			$dest[$mail] = $user[0]['user'];
			$email = Emails::send_mail($dest,Emails::plantilla($HTML),'Recuperar contraseña perdida');
			if(true === $email) {
				$e = array(
					'keypass' => $keypass,
					'keypass_tmp' => $u
				);
				$this->db->update('users',$e,"id='$id'",'LIMIT 1');
				$success = 1;
				$message = 'Hemos enviado un email a <b>' . $mail . '</b> para recuperar su contraseña.';
			} else {
				$success = 0;
				$message = $email;
			}

		}

		return array('success' => $success, 'message' => $message);
	}

	final public function UpdatePass() {
		$u = $this->db->select('id,keypass_tmp','users',"keypass='$this->id' AND keypass <> '0'",'LIMIT 1');

		if(false != $u) {

			Helper::load('strings');

			$id = $u[0]['id'];
			$pass = $u[0]['keypass_tmp'];
			$hash = Strings::hash($pass);

			$e = array(
				'keypass' => 0,
				'keypass_tmp' => '',
				'pass' => $hash
			);
			$this->db->update('users',$e,"keypass='$this->id' AND keypass <> '0' AND id='$id'");

			return $pass;
		}

		return false;
	}

	public function __destruct() {
		parent::__destruct();
	}
}

?>
