<?php
	// $users = json_decode(file_get_contents("http://localhost/test/API_TEST/users/"));
	//  print_r($users);

//      $userId = ""; /*Your user id*/
// $password = ""; /*Your password*/
// $postBody = array(); /*Your POST body*/
// $authString = $userId.':'.$password;
// $authStringBytes = utf8_encode($authString);
// $authloginString = base64_encode($authStringBytes);
// $authHeader= "Authorization:Basic ".$authloginString;
// $header = (array("Accept: application/json", "Content-Type: application/json", $authHeader));
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
// curl_setopt($ch, CURLOPT_URL, "https://sandbox.api.visa.com/forexrates/v1/foreignexchangerates");
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postBody));
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_SSLCERT, 'PATH_TO_CERT_FILE');
// curl_setopt($ch, CURLOPT_SSLKEY, 'PATH_TO_PRIVATE_KEY_FILE');
// $results = curl_exec($ch);
// curl_close($ch);
// print_r(json_decode($results));

     $userId = "OM7MCYZ7RVA09IFT4XPF21XlFr6w3C8z6zjZcZWoCNs_VkI6Y"; /*Your user id*/
$password = "X98xuYM4W4E8E082JdDCu03Drk"; /*Your password*/
$postBody = array(); /*Your POST body*/
$authString = $userId.':'.$password;
$authStringBytes = utf8_encode($authString);
$authloginString = base64_encode($authStringBytes);
$authHeader= "Authorization:Basic ".$authloginString;
$header = (array("Accept: application/json", "Content-Type: application/json", $authHeader));
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_URL, "https://sandbox.api.visa.com/dcas/cardservices/v2/cards/589fee31-7449-4b6a-9917-c68ada92de56");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postBody));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSLCERT, 'C:\Users\User\Music\Responsable_2iPay_10_08_2022_Directeur General\securite_visa\cert.pem');
curl_setopt($ch, CURLOPT_SSLKEY, 'C:\Users\User\Music\Responsable_2iPay_10_08_2022_Directeur General\securite_visa\key_5fc9823e-ac04-40e6-add8-e938b2e91812.pem');
$results = curl_exec($ch);
curl_close($ch);
print_r(json_decode($results));


 ?>
<!-- <table>
 <thead>
 	<tr>
 		<th>id</th>
 		<th>ref</th>
 		<th>nom user</th>
 	</tr>
 </thead>
 <tbody>
 	<?php 
 	 		
 		foreach ($users as $value) {?>
 			<tr>
 			<td><?= $value->id_user ?></td>
 			<td><?= $value->reference_compte ?></td>
 			<td><?= $value->nom_complet ?></td>
 		</tr>
 	<?php }
 	?>
 	
 </tbody>
 </table> -->