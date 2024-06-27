<?php 

//fonction de recuperation de la liste des utilisateurs
 function getUsers(){
   $pdo = getConnexion();

   $req="SELECT * FROM user WHERE 1";

   $stmt = $pdo->prepare($req);
   $stmt->execute();
   $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
   $stmt->closeCursor();
   sendJSON($users);
 }


//fonction d'ajout d'utilisateur
 function adduser($data){
  $pdo = getConnexion();
  $data = json_decode($data, true);
//var_dump($data);
  $sql="INSERT INTO user(nom,prenom,tel,id_profil) values (?,?,?,?)";
      $stmt=$pdo->prepare($sql);
      $stmt->execute(array($data['nom'], $data['prenom'], $data['tel'],$data['id_profil']));

      if($stmt->rowCount()==1)
    {
      $response=array(
        'status' => 1,
        'status_message' =>'user ajoute avec succes.'
      );
    }
    else
    {
      $response=array(
        'status' => 0,
        'status_message' =>'ERREUR!.'
      );
    }
    sendJSON($response);

 }

//fonction de recuperation de la liste des utilisateurs par profile
 function getUsersByProfile($profile){
 	$pdo = getConnexion();

 	 $req="SELECT * FROM user u, profil p WHERE u.id_profil=p.id_profil and libelle_profil='$profile'";

   $stmt = $pdo->prepare($req);
   $stmt->execute();
   $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
   $stmt->closeCursor();
   sendJSON($users);
 }

//fonction de recuperation de l'utilisateur par son Id
 function getUsersById($id){
 	$pdo = getConnexion();

 	 $req="SELECT * FROM user WHERE id=$id";

   $stmt = $pdo->prepare($req);
   $stmt->execute();
   $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
   $stmt->closeCursor();
   sendJSON($users);
 }

//fonction de connexion a la base de donnees de test
 function getConnexion(){
 	return new PDO("mysql:host=localhost;dbname=testapi;charset=utf8","root","");
 }

//fonction de recuperation des informations a encode au format JSON
 function sendJSON($infos){
 	header("Access-Control-Allow-Origin: *");
 	header("Content-Type: application/json");
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With');
 	echo json_encode($infos,JSON_UNESCAPED_UNICODE);
 }
?>