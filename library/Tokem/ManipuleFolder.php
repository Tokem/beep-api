<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ManipuleFolder
 *
 * @author Rodolfo Almeida
 */
class Tokem_ManipuleFolder {


	public function createFolderUser($idUser){

		$folder = "user_id_".$idUser;
		$profile = "profile_id_".$idUser;
		$event = "event_id_".$idUser;

		//PASTA PRINCIPAL
		$pasta = @mkdir("../public/upload/users/$folder", 0777);	
		chmod("../public/upload/users/$folder", 0777);

		//PERFIL
		$pasta = @mkdir("../public/upload/users/$folder/profile", 0777);	
		chmod("../public/upload/users/$folder/profile", 0777);

		//EVENTO
		$pasta = @mkdir("../public/upload/users/$folder/events", 0777);	
		chmod("../public/upload/users/$folder/events", 0777);

		//QRCODE
		$pasta = @mkdir("../public/upload/users/$folder/qrcode", 0777);	
		chmod("../public/upload/users/$folder/qrcode", 0777);



	}


	public function delete($idUser){

	}


}
