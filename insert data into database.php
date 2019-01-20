
<?php

// protezione pagina

session_start();
//se non c'è la sessione registrata
if (!(isset($_SESSION['autorizzato']))) {
  echo "<h1>Area riservata, accesso negato.</h1>";
  echo "Per effettuare il login clicca <a href='login.php'><font color='blue'>qui</font></a>";
  die;
}
 
//Altrimenti Prelevo il codice identificatico dell'utente loggato
session_start();
$cod = $_SESSION['cod']; //id cod recuperato nel file di verifica

if ($cod >= 3)
{
	echo "<h1>Area riservata, accesso negato.</h1>";
	die;
}	
?>



<?php 
			
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


<html>
<head>

<meta http-equiv="content-type" content="text/html; charset=UTF-8" />


	<style>
		body{
			padding: 8px;
		}
		th{
			padding: 10px;
		}
	
	</style>

<!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
	

    <!-- Custom styles for this template -->
	<link href="css/stile_gestione.css" rel="stylesheet">
 
	
	<!-- Jquery CSS -->
	<link href="css/jquery-ui.css" rel="stylesheet" type="text/css">
	<!------------------------------------------------------------------------------------------->




<script>
//costruisco l'array con i nomi dei piloti
			var arr = [];
</script>
<?php
///////////////////////////////////         	QUERY PER I NOMI DEI PILOTI
		$sql = "SELECT pilota.nome, scuderie.nome as scuderia FROM pilota, scuderie
				WHERE pilota.id_scuderia = scuderie.ID
				ORDER BY nome";
		$result = $conn->query($sql);

		if ($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo "<script>
						arr.push({nome: '".$row[nome]."', scuderia: '".$row[scuderia]."'});
					</script>
				";
			}

		}		
//////////////////////////////////////////////////
?>

</head>

<body>

<div style = "margin-bottom: 25px; margin-top: 5px;">
	<a class = "pannelli active" href ="#">PANNELLO GESTIONE GARE</a>
	<a class = "pannelli" href ="http://sitoclassifiche.altervista.org/patente_a_punti.php">PANNELLO GESTIONE PUNTI PATENTE</a>
</div>
	
	
<form action="register.php" method="post">
		<p>categoria</p>
		<select style = "float: left" type="text" name="categoria">
			<option>Super League</option>
			<option>Junior League</option>
		</select>
        <input style = "float: left" type="text" name="nome_gara" placeholder="Denominzaione gara" />
		<input style = "width: 190px; float: left" type="text" list="piloti" id="autocomplete" placeholder="circuito (dai suggeriti)" name="circuito">
        <input type="text" name="anno" id="autocomplete2" placeholder="Anno della gara" />
		<input onclick = "imposta_anno_corr()" type="checkbox" id = "annocorr" name="annocorr" value="1"> anno corrente<br>
		<input type="checkbox" name="mezzi" value="1"> mezzi punti?<br>
		
		
		
		
		<table style = "margin-top: 15px" class = "table-bordered">
			<tr>
				<th>pos</th>
				<th>pilota</th>
				<th>scuderia</th>
				<th>pole</th>
				<th>gpv</th>
				<th>rit</th>
				<th>DSQ</th>
			</tr>
			
			
		<?php
		
		//uso lo script per la tabella che ha 20 righe
		
		for($i = 1; $i <= 20; $i++){
		
		echo "<tr>
				<td>".$i."</td>  
				<td>
					  <select onclick='changeText(this,".$i.")' class='form-control' name='pilota".$i."' id='sel".$i."'>
							<script>
								//////////////////////////////////////////////////////////script fondamentale per prendere i dati nell'array di nomi del db
							document.write('<option></option>');
								arr.forEach(function(arr)
								{
									document.write('<option>'+arr.nome+'</option>');
								});
								//////////////////////////////////////////////////////////////script fondamentale per prendere i dati nell'array di nomi del db
							</script>
					  </select>
					
				</td>
				<td><input type='text' id='scuderia".$i."' name='scuderia".$i."' placeholder='scuderia' /><br></td>
				<td><input type='checkbox' name='pole".$i."' value='1'></td>
				<td><input type='checkbox' name='gpv".$i."' value='1'></td>
				<td><input onchange='punti(this,".$i.")' type='checkbox' id='rit".$i."' name='rit".$i."' value='1'></td>
				<td><input type='checkbox' name='dsq".$i."' value='1'></td>
				<td id='riga_punti".$i."' style = 'display: none;'> punti? <select name='punti".$i."'><option>yes</option><option>no</option> </select></td>
			</tr>";
		}
		?>
			
		</table>
 
        <input type="submit" value="invio" />
    </form>


	
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	
<!--   script per autocomplete  -->
 <script>
	$(document).ready(function() {
	$("input#autocomplete").autocomplete({
		source: [<?php
						header('Content-type: text/html;charset=utf-8');
						mysql_query("SET CHARACTER SET 'utf8'");  
						
						$stringa="";
						$sql = "SELECT nome FROM circuito";
						$result = $conn->query($sql);

						if ($result->num_rows > 0){
							while($row = $result->fetch_assoc()) {
								$stringa.="\"".$row['nome']."\", ";
							}
						}
						$stringa=substr($stringa,0,-2);
						echo $stringa
			?>],
		minLength:0
	});
	
	$("input#autocomplete2").autocomplete({
		source: ["2015/2016", "2016/2017", "2017/2018", "2018/2019", "2019/2020", "2020/2021", "2021/2022", "2022/2023", "2023/2024"],
		minLength:0
	});
	
	
	});
  </script>
  
  
  <script>
  // funzione che imposta le scudeie se annocrooente è checked
	function changeText(obj, num) 
	{ 
		if(document.getElementById("annocorr").checked == true)
		{
			
			var x = obj.value;
			
			for (var i = 0; i <= arr.length; i++)
			{
				if(arr[i].nome == obj.value)
				{
					document.getElementById("scuderia"+num).value = arr[i].scuderia;
				}
			}
		}
		
	}
	
	function imposta_anno_corr(){
		if(document.getElementById("annocorr").checked == true)
		{
		// impostazione dell anno corrente
			document.getElementById("autocomplete2").value = "2017/2018";
			////////////////
		}
	}
	
</script>


<script>
// funzione controllo ritiri prima del decimo posto

function punti(obj, num)
{
	if(document.getElementById("rit"+num).checked)
	{
		if(num <=10)
		{
			document.getElementById("riga_punti"+num).style.display = 'block';
		}
	}
	else
	{
		document.getElementById("riga_punti"+num).style.display = 'none';
	}
}


</script>



  

</body>

</html>