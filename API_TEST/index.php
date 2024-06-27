<?php 
require_once("function_api.php");

try {

	if (!empty($_GET['demande'])) {
		$url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
		switch ($url[0]) {
			//recuperation de la liste des utilisateurs par profile
			case "users":
				 if(empty($url[1])){
				 	getUsers();
				 } else{

				 	//recuperation de la liste des utilisateurs par profile
				 	getUsersByProfile($url[1]);
				 }
				break;
				//recuperation de l'utilisateur par Id
				case "user": 

				if (!empty($url[1])) {
					getUsersById($url[1]);
				} else {
					throw new Exception("Vous n'avez pas renseigner le numero de user");
					
				}
			break;	

			// Ajout d'un utilisateur 
				case "adduser": 

				if (empty($url[1])) {

					$data = file_get_contents('php://input');
					//$data = jsondecode( file_get_contents('php://input'),true);
      				adduser($data);

				} else {
					throw new Exception("enregistrement echouer");
					
				}
			break;
			
			default: throw new Exception ("la demande n'est pas valide, verifier l'url");
				
		}
	} else {
		throw new Exception("Probleme de recuperation de donnees", 1);
		
	}
	
} catch (Exception $e) {
	$erreur =[
		"message" => $e->getMessage(),
		"code" => $e->getCode()
	];
	print_r($erreur);
	
}


?>
