<?php
//require_once("connexion/connexion_pdo.php");
// if (isset($_POST['envoyer'])) {
    // $login = $_POST['login'];
    // $pass = $_POST['pass'];

// //requete dans la table compte
    // $query = "SELECT id, login, pass, profil
    //                   FROM utilisateur" ;

    // $result = $pdo->query($query);
    // $row = $result->fetch();

    // $test= $row['login'];
    // die();

//  //redirection selon le type de profil
//             if($row['profil'] == 'admin'){
//                  header('location:admin/index.php');    
//             }
//             else if($row['profil'] == 'simple'){
//                  header('location:secretaire/index.php');
//             }
//             else if($row['profil'] == '#'){
//                  header('location:simple/index.php');
//             }

    

//     else{ //else 2
//       //erreur de login/mot de passe
//       header('location:index.php?msg=login et/ou mot de passe incorrect');

//     }// fin else  2

//       # code...
//   }  
  ?>

<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        
        .wi {
  
  height: 200px;
          
            opacity: 0.5;
}

figcaption {
  position: absolute;
  top: 13%;
  left: 7.5%;
  transform: translate(-50%, -50%);
  /* Styles supplémentaires */
}
.image-container {
  position: relative;

}

.loader {
  position: absolute;
  top: 45%;
  left: 5.5%;
  transform: translate(-50%, -50%);
  width: 50px;
  height: 50px;
  border: 4px solid black;
  border-top: 8px solid #3498db;
  border-bottom: 8px solid #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: translate(-50%, -50%) rotate(0deg); }
  100% { transform: translate(-50%, -50%) rotate(360deg); }
}
    </style>
    <title>connexion</title>
</head>
<body bgcolor="black">
 
 <figure class="wi">
  <img src="kp.png" class="w" alt="Norway" height="200px"> 
  <figcaption style="color: black;font-size: 20px;">Bientot</figcaption>
</figure>

<figure class="image-container">
  <img src="kp.png" class="w" alt="Norway" height="200px"> 
  <div class="loader"></div>
</figure>
<!-- <form>
   
                <input type="text" id="login" placeholder="salut">
</form> -->
<!-- <script>
 
var logininput= document.getElementById('login');

alert('logininput');

logininput.onfocus = function () {
    // body...
    if (this.placeholder==='salut') {
        this.placeholder='souleymane';
    }
};

logininput.onblur = function(){

    if (this.placeholder==='souleymane') {
        this.placeholder='salut';
    }
};
</script> -->
</body>
</html>



