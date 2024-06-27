<?php

require 'vendor/autoload.php';
 require_once("routes.php");

 //ajouter un utilisateur
 function adduser($data) {
    $data = json_decode($data->getBody());
           
    $sql = "INSERT INTO user (nom, prenom, tel, id_profil) VALUES (:nom, :prenom, :tel, :id_profil)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nom", $data->nom);
        $stmt->bindParam("prenom", $data->prenom);
        $stmt->bindParam("tel", $data->tel);
        $stmt->bindParam("id_profil", $data->id_profil);
        $stmt->execute();
        $data->id = $db->lastInsertId();
        $db = null;
        echo json_encode($data);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//lister l'ensemble des utilisateurs
function getusers() {
    $sql = "SELECT * FROM user WHERE 1";
    try {
        $pdo = getConnection();
        $stmt = $pdo->query($sql);
        $emp = $stmt->fetchAll(PDO::FETCH_OBJ);
        //$db = null;
       return json_encode($emp);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//recuperation d'un utilisateur par l'identifiant
function getuser($request) {
    $id = 0;;
    $id =  $request->getAttribute('id');
    if(empty($id)) {
                echo '{"error":{"text":"Id is empty"}}';
    }
    try {
        $db = getConnection();
        $sth = $db->prepare("SELECT * FROM user WHERE id=$id");
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $todos = $sth->fetchObject();
        return json_encode($todos);
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//Mettre a jour les informations de  l'utilisateur

function updateuser($req) {
    $data = json_decode($req->getBody());
    $id = $req->getAttribute('id');
    $sql = "UPDATE user SET nom=:nom, prenom=:prenom, tel=:tel, id_profil=:id_profil WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nom", $data->nom);
        $stmt->bindParam("prenom", $data->prenom);
        $stmt->bindParam("tel", $data->tel);
        $stmt->bindParam("id_profil", $data->id_profil);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo json_encode($data);
    } catch(PDOException $e) {
       echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//Suppression de l'utilisateur
function deleteuser($req) {
    $id = $req->getAttribute('id');
    $sql = "DELETE FROM user WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        //$stmt->execute();
        $db = null;
        echo '{"error":{"text":"successfully! deleted Records"}}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


//fonction de connexion a la base de donnees comipay
 function getConnection(){
  return new PDO("mysql:host=localhost;dbname=testapi;charset=utf8","root","");
 }

 
// $app = new \Slim\App;
// $app->get('/', function () {
//   echo 'Welcome to my slim app';
// });
// $app->run();
?>