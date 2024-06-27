<?php
ob_start();
include_once 'includes.php';
include_once 'print_style.php';
require_once('../modules/tcpdf/config/lang/eng.php');
require_once('../modules/tcpdf/tcpdf.php');
include_once  'MYPDF.php';
//	ob_clean();
if(!empty($_GET['id_bus_depart'])){
	
	$critere = " 1 ";
	$id_bus_depart = $_GET['id_bus_depart'];

//	On Compte le Nombre de Passger, Bagages et Colis Embarques dans le BUS
	$criteres_passager = "type_embarquement = 'PASSAGER' AND id_bus_depart = '$id_bus_depart' ";
	$nbre_passager = count_row_in_table('embarquement','id_embarquement',$criteres_passager);
	
	$criteres_bagaes = "type_embarquement = 'BAGAGE' AND id_bus_depart = '$id_bus_depart' ";
	$nbre_bagaes = count_row_in_table('embarquement','id_embarquement',$criteres_bagaes);
	
	$criteres_colis = "type_embarquement = 'COLIS' AND id_bus_depart = '$id_bus_depart' ";
	$nbre_colis = count_row_in_table('embarquement','id_embarquement',$criteres_colis);


//	Recuperation des differents escales d'arrivees des Passagers
	$select_escale_arrivees_depart = "SELECT code_escale_arrivee FROM billet b INNER JOIN embarquement e ON b.unique_billet = e.unique_reference
										WHERE id_bus_depart = '$id_bus_depart' AND type_embarquement = 'PASSAGER' ";
	$result_select_escales_arrivee = $pdo->query("$select_escale_arrivees_depart");
	$array_escale_arrivee = [];
	
	while ($row_escale_arrivee = $result_select_escales_arrivee->fetch()){
		$code_escale_arrivee_passager = $row_escale_arrivee['code_escale_arrivee'];
		if(!in_array($code_escale_arrivee_passager, $array_escale_arrivee)){
			$array_escale_arrivee[] = $code_escale_arrivee_passager;
		}
	}

//	Recuperation des differentes Agences D'arrivées des Envois
	$select_agences_arrivees_depart_bus = "SELECT code_agence_destination FROM package p INNER JOIN colis c ON c.unique_package = p.unique_package
											INNER JOIN embarquement e ON e.unique_reference = c.num_colis WHERE id_bus_depart = '$id_bus_depart' AND  type_embarquement = 'COLIS' ";
	$result_select_agence_arrivees = $pdo->query($select_agences_arrivees_depart_bus);
	$array_agences_arrivees = [];
	
	while ($row_agences_arrivees = $result_select_agence_arrivees->fetch()){
		$code_agences_arrivees_colis = $row_agences_arrivees['code_agence_destination'];
		
		if(!in_array($code_agences_arrivees_colis, $array_agences_arrivees)){
			$array_agences_arrivees[]  = $code_agences_arrivees_colis;
		}
	}

//	Recuperation des Informations du Depart
	$bus_depart_bus = "SELECT * FROM bus_depart b, bus_programme_depart p, axe a, bus c WHERE $critere AND a.id_axe = b.id_axe AND c.numero_bus = p.numero_bus AND p.id_bus_depart = b.id_bus_depart AND p.id_bus_depart = '$id_bus_depart' ";
	$result_bus_depart = $pdo->query($bus_depart_bus);
	// $row_colis_bus_depart = $result_bus_depart->rowCount();
	$row_bus_depart = $result_bus_depart->fetch();
	
	$id_bus_programme_depart  = htmlentities(stripcslashes($row_bus_depart["id_bus_programme_depart"]));
	$id_bus_depart  = htmlentities(stripcslashes($row_bus_depart["id_bus_depart"]));
	$position  = htmlentities(stripcslashes($row_bus_depart["position"]));
	$numero_bus  = htmlentities(stripcslashes($row_bus_depart["numero_bus"]));
	$immatriculation_bus  = htmlentities(stripcslashes($row_bus_depart["immatriculation_bus"]));
	$libelle_bus_depart  = htmlentities(stripcslashes($row_bus_depart["libelle_bus_depart"]));
	$id_chauffeur_titulaire  = htmlentities(stripcslashes($row_bus_depart["id_chauffeur_titulaire"]));
	$frais_chauffeur_titulaire  = htmlentities(stripcslashes($row_bus_depart["frais_chauffeur_titulaire"]));
	$id_chauffeur_secondaire  = htmlentities(stripcslashes($row_bus_depart["id_chauffeur_secondaire"]));
	$libelle_axe  = htmlentities(stripcslashes($row_bus_depart["libelle_axe"]));
	$frais_chauffeur_secondaire  = htmlentities(stripcslashes($row_bus_depart["frais_chauffeur_secondaire"]));
	$heure_depart_bus_depart  = htmlentities(stripcslashes($row_bus_depart["heure_depart_bus_depart"]));
	$date_depart_bus_depart  = htmlentities(stripcslashes($row_bus_depart["date_depart_bus_depart"]));
	$type_bus_depart = html_entity_decode(stripslashes($row_bus_depart["type_bus_depart"]));
	
	
	// RECUPERATION DES CHAUFFEURS
	$select_chauffeur_titulaire = $pdo->QUERY("SELECT * FROM employe WHERE id_employe = '$id_chauffeur_titulaire' ");
	$result_chauffeur_titulaire = $select_chauffeur_titulaire->fetch();
	$nom_prenom_chauffeur_titulaire = $result_chauffeur_titulaire['nom_employe'].' '.$result_chauffeur_titulaire['prenom_employe'];
	
	$select_chauffeur_secondaire = $pdo->QUERY("SELECT * FROM employe WHERE id_employe = '$id_chauffeur_secondaire' ");
	$result_chauffeur_secondaire = $select_chauffeur_secondaire->fetch();
	$nom_prenom_chauffeur_secondaire = $result_chauffeur_secondaire['nom_employe'].' '.$result_chauffeur_secondaire['prenom_employe'];
	
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	$pdf->setDateDepart(dateInFrench($date_depart_bus_depart));
	$pdf->setLibelleAxeDepart($libelle_axe);
	$pdf->setNumBus($numero_bus);
	$pdf->setHeureDepart($heure_depart_bus_depart);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor($nom_employe_user . ' ' .$prenom_employe_user);

// set default header data
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(true);
// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
	$pdf->SetMargins(5, 5, 5);
	$pdf->SetFooterMargin(5);
	$pdf->SetAutoPageBreak(TRUE, 5);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$pdf->setLanguageArray($l);
	$pdf->setFontSubsetting(true);

	$pdf->SetFont('helvetica', '', 11, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
	$pdf->AddPage();
	
	$entete_stm = "<table style='width:100%'>
                     <tr>
                     	<td class='logo' style='width:30%'><img src='../images/logo_stm.jpg' style='width:200px' alt=''></td>
                        <td style='text-align:center; width:70%'>
                        	<h2>STM Ténére</h2>
                            <h5><!-- Siège --></h5>
                        </td>
                     </tr>
                   </table>";
	
	$entete_page_garde = "<br><h1  style='text-align:center; margin-bottom: 25px; text-decoration: underline;'> PROGRAMME VOYAGE N° ".  $id_bus_depart ." </h1> ";
	$entete_page_garde .= "<br>";
	$entete_page_garde .= "<h1 class='text-center' style='font-size: 32px; text-decoration: underline;'> " . $type_bus_depart . "-". $libelle_axe . "</h1>";
	
	$page_garde = "<table class='tableur' style='width:100%; font-size:30px;'  border='1' cellspacing='0' cellpadding='10'>";
	$page_garde .= "<tbody>"
		."<tr>"
		."<td> <strong>Nom du BUS : </strong>". $libelle_bus_depart . "</td>
												  <td><strong>N° du BUS : </strong> " . $numero_bus . "</td>
												  <td><strong> NOMBRE DE BAGAGES : </strong> ". $nbre_bagaes  ." </td>"
		."</tr>"
		."<tr>"
		."<td> <strong>Date de départ  : </strong>". dateInFrench($date_depart_bus_depart) . "</td>
												  <td><strong>Heure de départ : </strong> " . $heure_depart_bus_depart . "</td>
												  <td><strong>NOMBRE DE COLIS : </strong> ". $nbre_colis  ." </td>"
		."</tr>"
		."<tr>"
		."<td> <strong>Chauffeur Principal  : </strong>". $nom_prenom_chauffeur_titulaire . "</td>
												  <td><strong>Chauffeur Secondaire: </strong> " . $nom_prenom_chauffeur_secondaire . "</td>
												  <td><strong>NOMBRE DE PASSAGER : </strong> ". $nbre_passager  ."</td>"
		."</tr>"
		."</tbody>";
	$page_garde .= "</table>";
	$page_garde .= "<br>";
	$page_garde .= "<h1 style='text-align:center'>RECAPUTILATIF DU DEPART</h1>";
	$page_garde .= "<br>";
	$page_garde .= "<table class='data_table_simple' style='width: 50%'>" ;
	$page_garde .= "<thead>"
		."<tr>"
		."<th style='text-align: center' colspan='2'> REPARTITIONS DES PASSAGERS PAR DESTINATION </th>"
		."</tr>"
		."<tr>"
		."<th style='text-align: center'> ESCALE D'ARRIVEE </th>"
		."<th style='text-align: center'> Nbre de PASSAGERS </th>"
		."</tr>"
		."</thead>";
	$page_garde .= "<tbody>";
	if(count($array_escale_arrivee)){
		for($ii=0; $ii < count($array_escale_arrivee); $ii++){
			
			$code_escale_arrivee_pass = $array_escale_arrivee[$ii];
			$sql_count_passagers = "SELECT count(id_billet) as nbr_passager_escale FROM billet b INNER JOIN embarquement e
													ON b.unique_billet = e.unique_reference
													WHERE b.code_escale_arrivee = '$code_escale_arrivee_pass' AND type_embarquement = 'PASSAGER'
													AND id_bus_depart = '$id_bus_depart' ";
			$result_escales = $pdo->query($sql_count_passagers)->fetch();
			
			$page_garde .= "<tr>";
			$page_garde .= "<td>". select_all_from_one_table('escale','code_escale',$code_escale_arrivee_pass)->fetch()['libelle_escale'] ."</td>";
			$page_garde .= "<td>". $result_escales['nbr_passager_escale'] . "</td>";
			$page_garde .= "</tr>";
		}
	}  else {
		$page_garde .= "<tr><td colspan='2' style='text-align: center'> Aucun PASSAGER Embarques dans le BUS </td></tr>";
	}
	
	$page_garde .= "<tr>"
		."<th style='text-align: center' colspan='2'> REPARTITION DES ENVOIS/COLIS PAR DESTINATION </th>"
		."</tr>"
		."<tr>"
		."<th style='text-align: center'>AGENCE DESTINATION </th>"
		."<th style='text-align: center'> Nbre de COLIS </th>"
		."</tr>";
	if(!empty($array_agences_arrivees)){
		for($ii=0; $ii < count($array_agences_arrivees); $ii++){
			$code_agences_arrivee_colis = $array_agences_arrivees[$ii];
			
			$sql_count_colis = "SELECT count(id_colis) as nbr_colis_agence FROM package p
												INNER JOIN colis c ON c.unique_package = p.unique_package
												INNER JOIN embarquement e ON e.unique_reference = c.num_colis
												WHERE id_bus_depart = '$id_bus_depart' AND type_embarquement = 'COLIS'
												AND code_agence_destination = '$code_agences_arrivee_colis' ";
			$result_agences = $pdo->query($sql_count_colis)->fetch();
			
			$page_garde .= "<tr>";
			$page_garde .= "<td>". strtoupper(select_all_from_one_table('agence','code_agence',$code_agences_arrivee_colis)->fetch()['libelle_agence']) ."</td>";
			$page_garde .= "<td>". $result_agences['nbr_colis_agence'] . "</td>";
			$page_garde .= "</tr>";
		}
	} else {
		$page_garde .= "<tr><td colspan='2' style='text-align: center'> Aucun Colis Embarques dans le BUS </td></tr>";
	}
	$page_garde .= "</tbody>";
	$page_garde .= "</table>";
	
	$page_recapitulatif = $entete_stm.$entete_page_garde.$page_garde;
	
	$page_passager_par_destination = "";

//	Debut de Recuperation des Passagers Embarqués Et Leurs Bagages Par destination
	for($ii=0; $ii < count($array_escale_arrivee); $ii++):
		
		$code_escale_arrivee_pass = $array_escale_arrivee[$ii];
		$libelle_esclale_destination = select_all_from_one_table('escale','code_escale',$code_escale_arrivee_pass)->fetch()['libelle_escale'];
		
		$tab_destination_entete = "<br pagebreak='true'> ";
		$tab_destination_entete .= "<h2> LISTES DES PASSAGERS A DESTINATION DE ".strtoupper($libelle_esclale_destination)." : </h2>";
//		On recupere les passagers embarques dans le bus et leurs Bagages
		$sql_select_passagers = "SELECT nom_client,prenom_client, tel_client, b.unique_billet AS unique_billet, libelle_escale
								FROM billet b INNER JOIN client c ON b.code_client = c.code_client
								INNER JOIN embarquement e ON b.unique_billet = e.unique_reference
								INNER JOIN escale es ON b.code_escale_depart = es.code_escale
								WHERE id_bus_depart = '$id_bus_depart' AND code_escale_arrivee = '$code_escale_arrivee_pass' ORDER BY e.created_at DESC ";
		
		$result_select_passagers = $pdo->query($sql_select_passagers);
		
		$table_destination = "<table class='data_table_simple'>";
		$table_destination .= "<thead>"
			."<tr>
											.<th>#</th>
											.<th>N° du Billet</th>
											.<th>Passager</th>
											.<th>Téléphone</th>
											.<th>Depart</th>
											.<th>SACS</th>
											.<th>COLIS</th>
											.<th>VALISE</th>
											.<th>CARTON</th>
											.<th>RESTANT</th>
											.<th>E.A</th>
										</tr>"
			."</thead>";
		$table_destination .= "<tbody>";
		$k = 0;
		while ($row_passagers = $result_select_passagers->fetch()):
			$k++;
			$unique_billet_passager = $row_passagers['unique_billet'];
//			Select Bagages
			$criteres_bagages_total = " unique_billet = '$unique_billet_passager' AND etat_bagages = 'ENREGISTRE' ";
			$nbre_total_bagages_passagers  = count_row_in_table('bagages','id_bagages',$criteres_bagages_total);
			
			$nbr_colis_passager_embarques = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
									WHERE etat_bagages = 'EMBARQUE'
									AND unique_billet = '$unique_billet_passager' AND nature_bagages = 'COLIS' ")->fetch()['nbr'];
			
			$nbr_sacs_voyages_passager_embarques = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
																	WHERE etat_bagages = 'EMBARQUE'
																	AND unique_billet = '$unique_billet_passager' AND nature_bagages = 'SACS' AND id_bus_depart = '$id_bus_depart' ")->fetch()['nbr'];
			$nbr_valises_passager_embarques = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
																	WHERE etat_bagages = 'EMBARQUE'
																	AND unique_billet = '$unique_billet_passager' AND nature_bagages = 'VALISE' AND id_bus_depart = '$id_bus_depart' ")->fetch()['nbr'];
			$nbr_carton_passager_embarques = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
																	WHERE etat_bagages = 'EMBARQUE'
																	AND unique_billet = '$unique_billet_passager' AND nature_bagages = 'CARTON' AND id_bus_depart = '$id_bus_depart' ")->fetch()['nbr'];
			
			$nbr_bagages_embarques_other_bus = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
																		WHERE etat_bagages = 'EMBARQUE'
																		AND unique_billet = '$unique_billet_passager' AND id_bus_depart <> '$id_bus_depart' ")->fetch()['nbr'];
			$nbr_bagages_non_embarques = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
																	WHERE etat_bagages = 'ENREGISTRE'
																	AND unique_billet = '$unique_billet_passager' ")->fetch()['nbr'];
			
			$nbr_bagages_restants = $nbre_total_bagages_passagers - $nbr_carton_passager_embarques - $nbr_sacs_voyages_passager_embarques - $nbr_valises_passager_embarques - $nbr_bagages_embarques_other_bus - $nbr_bagages_non_embarques ;
			
			$table_destination .= "<tr>
										<td>" .$k ."</td>
										<td>". $unique_billet_passager . "</td>
										<td>". $row_passagers["nom_client"]." ". $row_passagers["prenom_client"]."</td>
										<td>". $row_passagers["tel_client"] ."</td>
										<td>". $row_passagers["libelle_escale"] ."</td>
										<td>". $nbr_sacs_voyages_passager_embarques ."</td>
										<td>". $nbr_colis_passager_embarques ."</td>
										<td>". $nbr_valises_passager_embarques ."</td>
										<td>". $nbr_carton_passager_embarques ."</td>
										<td>". $nbr_bagages_restants . "</td>
										<td>". $nbr_bagages_embarques_other_bus ."</td>
									</tr>";
		endwhile;
		$table_destination .= "</tbody>";
		$table_destination .= "</table>";
		
		$page_passager_par_destination .= $tab_destination_entete.$table_destination;
	endfor;
	
	$envois_par_destination = "";
	
	for($ii=0; $ii < count($array_agences_arrivees); $ii++):
		$code_agences_arrivee_colis = $array_agences_arrivees[$ii];
		$libelle_agence_destination = select_all_from_one_table('agence','code_agence',$code_agences_arrivee_colis)->fetch()['libelle_agence'];
		
		$tab_agence_destination_entete = "<br pagebreak='true'/> ";
		$tab_agence_destination_entete .= "<h2> LISTES DES ENVOIS A DESTINATION DE ".strtoupper($libelle_agence_destination)." : </h2>";
		$sql_select_data_colis = "SELECT em.id_bus_depart AS id_bus_depart, c.unique_package AS unique_package, c.nature_colis AS nature_colis, code_client_exp, code_client_dest,
                                    code_agence_destination, serie_package, code_agence_provenance, num_colis FROM embarquement AS em
                                    INNER JOIN bus_programme_depart AS bd ON em.id_bus_depart = bd.id_bus_depart
                                    INNER JOIN colis AS c ON em.unique_reference = c.num_colis
                                    INNER JOIN package AS p ON c.unique_package = p.unique_package
                                    WHERE bd.id_bus_depart = '$id_bus_depart' AND p.code_agence_destination = '$code_agences_arrivee_colis' ORDER BY unique_package ";
		
		$result_select_data_colis = $pdo->QUERY($sql_select_data_colis);
		
								$table_colis_destination = "<table class='data_table_simple'>";
								$table_colis_destination .= "<thead>"
																."<tr>
																	<th>#</th>
																	<th style='text-align:center'>N° du colis</th>
																	<th>Expéditeur</th>
																	<th>Tel Expediteur</th>
																	<th>Destinataire</th>
																	<th>Tel Destinataire</th>
																	<th>Provénance</th>
																	<th>Destination</th>
																	<th>Nature colis</th>
																</tr>";
								$table_colis_destination .= "</thead>";
								$table_colis_destination .= "<tbody>";
										if($nbre_colis == 0){
											$table_colis_destination .= "<tr><td colspan='9' style='text-align: center;'>Aucun colis embarque dans le bus</td></tr>";
										} else {
											$l = 0;
											while ($row_result_colis = $result_select_data_colis->fetch()):
												$l++;
												$code_client_exp = $row_result_colis['code_client_exp'];
												$code_client_dest = $row_result_colis['code_client_dest'];
												
												$code_agence_provenance = $row_result_colis['code_agence_provenance'];
												
												$libelle_agence_provenance = $pdo->query("SELECT libelle_agence FROM agence WHERE code_agence = '$code_agence_provenance' ")->fetch()['libelle_agence'];
												
												$expediteur = $pdo->query("SELECT * from client WHERE code_client = '$code_client_exp' ")->fetch();
												$destinataire = $pdo->query("SELECT * from client WHERE code_client = '$code_client_dest' ")->fetch();
												
												$num_colis =  $row_result_colis['num_colis'] ;
												$num_colis_tab = explode('-',$num_colis);
												$num_colis_codifie =  $num_colis_tab[1].'/'.$num_colis_tab[2];
												$num_colis_complet = $row_result_colis['serie_package']." | ".$num_colis_codifie;
												
												$table_colis_destination .= "<tr>";
													$table_colis_destination .= "<td>". $l ."</td>
																				<td> ". $num_colis_complet ."</td>
																				<td> ". $expediteur['nom_client']."<br>".$expediteur['prenom_client'] ."</td>
																				<td>". $expediteur['tel_client'] ."</td>
																				<td>". $destinataire['nom_client']."<br>".$destinataire['prenom_client'] ."</td>
																				<td>". $destinataire['tel_client'] ."</td>
																				<td>". $libelle_agence_provenance ."</td>
																				<td>". $libelle_agence_destination. "</td>
																				<td>". $row_result_colis['nature_colis']. "</td>";
												$table_colis_destination .= "</tr>";
											endwhile;
										}
		$table_colis_destination .= "</tbody>";
		$table_colis_destination .= "</table>";
		$envois_par_destination = $tab_agence_destination_entete.$table_colis_destination;
	endfor;
	
	$bagages_embarques_hors_passagers = "";
	
	$tab_bagages_embarques_hors_passagers_entete = "<br pagebreak='true'/> ";
	$tab_bagages_embarques_hors_passagers_entete .= "<h2> LISTES DES BAGAGES EMBARQUES SANS LEURS PASSAGERS </h2>";
	$sql_bagages_embarques_hors_passagers = "SELECT ba.unique_billet AS unique_billet
                                FROM embarquement AS em
                                INNER JOIN bagages AS ba ON em.unique_reference = ba.unique_bagages
                                WHERE id_bus_depart = '$id_bus_depart'
                                 	AND type_embarquement = 'BAGAGE'
                                 	AND ba.unique_billet NOT IN (SELECT unique_reference FROM embarquement WHERE id_bus_depart = '$id_bus_depart' AND type_embarquement = 'PASSAGER' ) ORDER BY unique_billet";
	
	$array_unique_billet_bagages_hors_bus = [];
	
	while ($row_billet_bagage_hors_bus = $pdo->query($sql_bagages_embarques_hors_passagers)->fetch() ):
		$unique_billet_bag = $row_billet_bagage_hors_bus['unique_billet'];
		if(!in_array($unique_billet_bag, $array_unique_billet_bagages_hors_bus)){
			$array_unique_billet_bagages_hors_bus[] = $unique_billet_bag;
		}
	endwhile;
	
	$table_bagages_hors_passagers = "<table class='data_table_simple'>";
	$table_bagages_hors_passagers .= "<thead>"
		."<tr>
											.<th>#</th>
											.<th>N° du Billet</th>
											.<th>Passager</th>
											.<th>Téléphone</th>
											.<th>Depart</th>
											.<th>SACS</th>
											.<th>COLIS</th>
											.<th>VALISE</th>
											.<th>CARTON</th>
											.<th>RESTANT</th>
										</tr>"
		."</thead>";
	$table_bagages_hors_passagers .= "<tbody>";
	if(count($array_unique_billet_bagages_hors_bus) == 0){
		$table_bagages_hors_passagers .= "<tr> <td colspan='11' style='text-align: center'>Aucun Bagage Embarqués sans le passager</td></tr>";
	} else {
		for($kk = 0; $kk < count($array_unique_billet_bagages_hors_bus); $kk++){
			
			$unique_billet_bag = $array_unique_billet_bagages_hors_bus[$kk];
			
			$sql_select_bag_passagers = "SELECT nom_client,prenom_client, tel_client, b.unique_billet AS unique_billet,
											ed.libelle_escale AS libelle_escale_depart,
											ea.libelle_escale AS libelle_escale_arrivee
								FROM billet b INNER JOIN client c ON b.code_client = c.code_client
								INNER JOIN escale ed ON b.code_escale_depart = ed.code_escale
								INNER JOIN escale ea ON b.code_escale_arrivee = ea.code_escale
								WHERE unique_billet = '$unique_billet_bag' ORDER BY b.created_at DESC ";
			
			$result_select_bag_passagers = $pdo->query($sql_select_bag_passagers);
			
			$k = 0;
			$row_passagers = $result_select_bag_passagers->fetch();
				$k++;
				$unique_billet_passager = $row_passagers['unique_billet'];
//			Select Bagages
				$criteres_bagages_total = " unique_billet = '$unique_billet_passager' ";
				$nbre_total_bagages_passagers  = count_row_in_table('bagages','id_bagages',$criteres_bagages_total);
				
				$nbr_colis_passager_embarques = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
									WHERE etat_bagages = 'EMBARQUE'
									AND unique_billet = '$unique_billet_passager' AND nature_bagages = 'COLIS' ")->fetch()['nbr'];
				
				$nbr_sacs_voyages_passager_embarques = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
																	WHERE etat_bagages = 'EMBARQUE'
																	AND unique_billet = '$unique_billet_passager' AND nature_bagages = 'SACS' AND id_bus_depart = '$id_bus_depart' ")->fetch()['nbr'];
				$nbr_valises_passager_embarques = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
																	WHERE etat_bagages = 'EMBARQUE'
																	AND unique_billet = '$unique_billet_passager' AND nature_bagages = 'VALISE' AND id_bus_depart = '$id_bus_depart' ")->fetch()['nbr'];
				$nbr_carton_passager_embarques = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
																	WHERE etat_bagages = 'EMBARQUE'
																	AND unique_billet = '$unique_billet_passager' AND nature_bagages = 'CARTON' AND id_bus_depart = '$id_bus_depart' ")->fetch()['nbr'];
				
				$nbr_bagages_embarques_other_bus = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
																		WHERE etat_bagages = 'EMBARQUE'
																		AND unique_billet = '$unique_billet_passager' AND id_bus_depart <> '$id_bus_depart' ")->fetch()['nbr'];
				$nbr_bagages_non_embarques = $pdo->query("SELECT count(id_bagages) AS nbr FROM embarquement e INNER JOIN bagages b ON e.unique_reference = b.unique_bagages
																	WHERE etat_bagages = 'ENREGISTRE'
																	AND unique_billet = '$unique_billet_passager' ")->fetch()['nbr'];
				
				$nbr_bagages_restants = $nbre_total_bagages_passagers - $nbr_carton_passager_embarques - $nbr_sacs_voyages_passager_embarques - $nbr_valises_passager_embarques - $nbr_bagages_embarques_other_bus - $nbr_bagages_non_embarques ;
			
			$table_bagages_hors_passagers .= "<tr>
										<td>" .$k ."</td>
										<td>". $unique_billet_passager . "</td>
										<td>". $row_passagers["nom_client"]." ". $row_passagers["prenom_client"]."</td>
										<td>". $row_passagers["tel_client"] ."</td>
										<td>". $row_passagers["libelle_escale"] ."</td>
										<td>". $nbr_sacs_voyages_passager_embarques ."</td>
										<td>". $nbr_colis_passager_embarques ."</td>
										<td>". $nbr_valises_passager_embarques ."</td>
										<td>". $nbr_carton_passager_embarques ."</td>
										<td>". $nbr_bagages_restants . "</td>
									</tr>";
				
		}
	}
	$table_bagages_hors_passagers .= "</tbody>";
	$table_bagages_hors_passagers .= "</table>";
	$bagages_embarques_hors_passagers .= $tab_bagages_embarques_hors_passagers_entete.$table_bagages_hors_passagers;
	
//	Sous Pieds de page Pour la Signature
	$footer_page = "<br pagebreak='true'/>";
	$footer_page .= "<table class='data_table_simple' style='width:100%; font-size:30px;'>
                                  <tbody>
                                    <tr>
                                      <th width='20%' style='text-align:center; font-weight:bold'>Chef d'agence </th>
                                      <th width='20%' style='text-align:center; font-weight:bold'> Chauffeur Principal </th>
                                      <th width='20%' style='text-align:center; font-weight:bold'>Chauffeur Secondaire </th>
                                      <th width='20%' style='text-align:center; font-weight:bold'>Agent de Voyage</th>
                                    </tr>
                                    <tr>
                                      <td style='height: 5cm'></td>
                                      <td ></td>
                                      <td ></td>
                                      <td ></td>
                                    </tr>
                                  </tbody>
                                </table>";
	
	echo $tab_complete = $page_recapitulatif.$page_passager_par_destination.$envois_par_destination.$bagages_embarques_hors_passagers.$footer_page;
	$out = ob_get_clean();
	//echo $out;

 	$pdf->writeHTML($out, true, false, true, false, '');
// ////// ---------------------------------------------------------
	$pdf->IncludeJS("print();");
	$pdf->Output('depart_bus_'.$id_bus_depart.'.pdf', 'I');
//	sleep(10);
} else die("Veuillez de Selectionner un depart");
