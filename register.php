<?php 

///// 
//     PAGINA DI REGISTRAZIONE DEI DATI NEL DATABASE DOPO AVERLI INSERITI IN "inset data into database.php"
/////
			
		$servername = "Localhost";
		$username = "sitoclassifiche@localhost";
		$password = "***";
		$dbname = "my_sitoclassifiche";


		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname );
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
			
?>




<?php
// define variables and set to empty values
$nome_gara = "";
$anno = "";
$mezzi = "";
$circuito = "";
$categoria = "";

$punti_si_o_no = $pilota = $scuderia = $pole = $gpv = $rit = $dsq = array(" ");

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	
	$categoria = test_input($_POST["categoria"]);
	$nome_gara = test_input($_POST["nome_gara"]);
	$circuito = test_input($_POST["circuito"]);
	$anno = test_input($_POST["anno"]);
	$mezzi = test_input($_POST["mezzi"]);
	
	
	
	$fine = 0;
	/////////////////////
	///CICLO DI RIEMPIMENTO DEGLI ARRAY
	/////////////////////
	for($i = 1; $i <= 20; $i++)
	{
		array_push($pilota, test_input($_POST["pilota".$i.""]));
		if($pilota[$i] == "")
		{
			$fine = $i - 1;
			break;
		}
		array_push($scuderia, test_input($_POST["scuderia".$i.""]));
		array_push($pole, test_input($_POST["pole".$i.""]));
		array_push($gpv, test_input($_POST["gpv".$i.""]));
		array_push($rit, test_input($_POST["rit".$i.""]));
		array_push($dsq, test_input($_POST["dsq".$i.""]));
		array_push($punti_si_o_no, test_input($_POST["punti".$i.""]));
		
		
	}
	/////////////////////
	////////////////////////////////////////////////////////////////
	/////////////////////
}


///////////////////////////////////////
/////////////////////////////FUNZIONE DI CONTROLLO FUNZIONAMENTO INPUT
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>




<?php

//////
//STAMPA A VIDEO DI PROVA (POI DOVRO FARLO CON LE QUERY PER SALVARE DATI IN DB)
//////

echo "<h2>Your Input:</h2></br>";

echo "nome gara: ".$nome_gara." del ".$anno."</br>";

///////////////////////////////////////////////
//////////////  INSERIMENTO DATI NELLA TABELLA "GARA"
///////////////////////////////////////////////
$sql = "INSERT INTO gara (denominazione, categoria, anno, ID_circuito)
				VALUES
				('".$nome_gara."',
				'".$categoria."',
				'".$anno."',
				(SELECT circuito.ID FROM circuito WHERE circuito.nome = '".$circuito."')
				)"; 
				
			
				//////////////////////////////
				/////////////////////////////////
				///////////////////////////////////  stampa a video se ha successo inserimento tabella gara

		//declare in the order variable
	$result = mysql_query($toinsert);	//order executes
	if ($conn->query($sql) === TRUE) 
	{
			echo "New record created successfully";
			
			
					/////////
					////////
					//////
					/////
					////
					///
					//
					
			
			$punti = 25;
			$assegna_punti = $punti;

		// for di assegnamento valore 0 per giri veloci pole ritiri e dsq se sono null
		// + assengamento punti
		// + query dei risultati

		for($i = 1; $i <= $fine; $i++)
		{
			echo " </br>";
			echo "fine = ".$fine." </br>";
			echo " </br>";
			
			echo " ".$pilota[$i]." ".$scuderia[$i]."   ";
			if ($pole[$i] != 1)
				$pole[$i] = 0;
				
			if ($gpv[$i] != 1)
				$gpv[$i] = 0;
			
			if ($rit[$i] != 1)
				$rit[$i] = 0;
			
			if ($dsq[$i] != 1)
				$dsq[$i] = 0;
			
			echo "</br>";echo "</br>";echo "</br>";
			
			///// scalo dei punti
			if($i == 2)
			{
				$punti -= 7;
			}
			if($i == 3 || $i == 4)
			{
				$punti -= 3;
			}
			if($i >= 5 && $i <= 9)
			{
				$punti -= 2;
			}
			if($i == 10 || $i == 11)
			{
				$punti -= 1;
			}
			///// scalo dei punti
			
			/// controllo dei mezzi punti
			if($mezzi == 1)
			{
				$punti = $punti/2;
			}

			if	($punti_si_o_no[$i] == 'no' && $rit[$i] == 1)
			{
				$assegna_punti = 0;
				echo "".$pilota[$i]."  0";
			}
			else
			{
				$assegna_punti = $punti;
				echo "".$pilota[$i]."  ".$punti."";
			}
			
			

			
			
				//inserting data order (query di inserimento risultati)
			$sql = "INSERT INTO risultato (ID_scuderia, ID_pilota, ID_gara, posizione, punteggio, giro_veloce, poleposition, ritiro, squalifica)
						VALUES
						((SELECT scuderie.ID FROM scuderie WHERE scuderie.nome = '".$scuderia[$i]."'),
						(SELECT pilota.ID FROM pilota WHERE pilota.nome = '".$pilota[$i]."'),
						(SELECT gara.ID FROM gara, circuito WHERE gara.ID_circuito = circuito.ID  AND circuito.nome = '".$circuito."' AND gara.anno = '".$anno."' AND gara.categoria = '".$categoria."'),
						".$i.",
						".$assegna_punti.",
						".$gpv[$i].",
						".$pole[$i].",
						".$rit[$i].",
						".$dsq[$i].")"; 
						
					
						//////////////////////////////
						/////////////////////////////////
						/////////////////////////////////// 
						
						
					//  FINE QUERY E STAMPA A SCHEMO SE ANDATO A BUON FINE 

				//declare in the order variable
			$result = mysql_query($toinsert);	//order executes
			if ($conn->query($sql) === TRUE) 
			{
					echo "New record created successfully";
			} else 
			{
					echo "Error: " . $sql . "<br>" . $conn->error;
			}

			
			/// controllo dei mezzi punti (ripristino)
			if($mezzi == 1)
			{
				$punti = $punti*2;
			}	
			
		} // fine if di inserimento
			
			
			
			
	} 
	else 
	{
		echo "Error: " . $sql . "<br>" . $conn->error;
		echo "</br>";
		echo "<p style = 'font-size: 70px; color: red '>ERRORE DATI INSERITI NON VALIDI<p>";
		
	} // fine else di errore


$conn->close();


?>