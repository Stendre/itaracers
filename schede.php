<?php
 
$pilota = $_GET['ricerca_nome'];
 
?>





<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=yes" charset="UTF-8">
<title>schede pilota</title> 


	<!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
		

    <!-- Custom styles for this template -->
    <link href="css/stile.css" rel="stylesheet" type="text/css">
	<link href="css/stileschede.css" rel="stylesheet" type="text/css">
	
	
	<!-- Jquery CSS -->
	<link href="css/jquery-ui.css" rel="stylesheet" type="text/css">
	<!------------------------------------------------------------------------------------------->
	
</head>

<body style ="padding-top: 5rem;">

<!-- BOOTSTRAP MENUUU
    ================================================== -->
    <nav class="navbar navbar-collapse navbar-expand-md navbar-dark fixed-top" id = "navbarra">
      <img class="img-navbar" src="immagini/logo.png" alt="Logo">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="home.html"> Home <span class="sr-only"></span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#"><nobr>Schede Piloti</nobr></a>
          </li>
      
          <li class="nav-item dropdown">
		  
		  
		  
		  
           <!-- <a style = "width: 180px"	class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Classifiche</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item dropdown-toggle" href="http://example.com" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Junior League</a>
				<div class="dropdown-menu" aria-labelledby="dropdown02">
				<a class="dropdown-item" href="#">Super League</a>
				<a class="dropdown-item" href="#">Super League</a>
				</div>
              <a class="dropdown-item" href="#">Super League</a>
            </div>
			-->
			
			
							<a style = "width: 130px"	class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Classifiche</a>
							<ul class="dropdown-menu">
							  <li class="dropdown-submenu">
								<a class="test dropdown-item dropdown-toggle" tabindex="-1" href="#">Junior League<span class="caret"></span></a>
								<ul class="dropdown-menu">
								  <li><a class="dropdown-item" tabindex="-1" href="piloti junior.php">piloti</a></li>
								  <li><a class="dropdown-item" tabindex="-1" href="scuderie junior.php">costruttori</a></li>
								  </li>
								</ul>
							  <li class="dropdown-submenu">
								<a class="test dropdown-item dropdown-toggle" tabindex="-1" href="#">Super League<span class="caret"></span></a>
								<ul class="dropdown-menu">
								  <li><a class="dropdown-item" tabindex="-1" href="piloti super.php">piloti</a></li>
								  <li><a class="dropdown-item" tabindex="-1" href="scuderie super.php">costruttori</a></li>
								  </li>
								</ul>
							  </li>
							</ul>

			
			
          </li>
		  
		  <li class="nav-item">
            <a class="nav-link" href="http://itaracers.forumfree.it/"> Forum </a>
          </li>
        </ul>
        <form action = "login.php" class="form-inline my-2 my-lg-0">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Login</button>
        </form>
      </div>
    </nav>

    <main role="main" class="container" >
	
	
	
	<!-- form  ////////////////////////////////////  -->
	
	<div class="container container-personalizzato"  id="form_schede">
  <h2 id = "intestation_01">Ricerca schede pilota</h2>
  <form class="form-inline" action="schede.php">
      <div class="col-sm-10">
        <input type="ricerca_nome" list="piloti" class="form-control" id="autocomplete" placeholder="Enter name" name="ricerca_nome" onclick="changeText()">
		<button type="submit" class="btn btn-default">Cerca</button>
      </div>
    </form>
</div>
      <!-- fine form ////////////////////////////////////////// -->
	  
	  
	  
	  
	  
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

		
		
		//
		///
		/////////////////////////////////////////
		//	QUERY INIZIALE DI CONTROLLO
		//////////////////////////////////////////
		$sql = "SELECT campionato, nome, scuderia FROM pilota, risultato
				WHERE pilota.ID = risultato.ID_pilota
				AND pilota.nome = '".$pilota."'
				ORDER BY punti desc, nome desc";
		$result = $conn->query($sql);

		
		if ($result->num_rows > 0){
			$iniziale = $result->fetch_assoc();
	
		
		//
		///
		/////////////////////////////////////////
		//	QUERY VITTORIE
		//////////////////////////////////////////
		$sql = "SELECT campionato, nome, scuderia, SUM(risultato.posizione) as vittorie  FROM pilota, risultato
				WHERE pilota.ID = risultato.ID_pilota
				AND pilota.nome = '".$pilota."'
				AND risultato.posizione = 1
				ORDER BY punti desc, nome desc";
		$result = $conn->query($sql);

		
		
		if ($result->num_rows > 0)
			$vittorie = $result->fetch_assoc();	
		
		
			//
			///
			/////////////////////////////////////////
			//  QUERY GARE
			//////////////////////////////////////////
			$sql = "SELECT campionato, nome, scuderia, COUNT(risultato.ID) as gare  FROM pilota, risultato
					WHERE pilota.ID = risultato.ID_pilota
					AND pilota.nome = '".$pilota."'
					ORDER BY nome desc";
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0)
				$gare = $result->fetch_assoc();
		
		
			//
			///
			/////////////////////////////////////////
			//	QUERY POLE POSITION
			//////////////////////////////////////////
			$sql = "SELECT SUM(risultato.poleposition) as pole  FROM pilota, risultato
					WHERE pilota.ID = risultato.ID_pilota
					AND pilota.nome = '".$pilota."'
					AND risultato.poleposition = 1
					ORDER BY punti desc, nome desc";
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0)
				$pole = $result->fetch_assoc();
			
			//
			///
			/////////////////////////////////////////
			//	QUERY GIRI VELOCI
			//////////////////////////////////////////
			$sql = "SELECT campionato, nome, scuderia, SUM(risultato.giro_veloce) as gpv  FROM pilota, risultato
					WHERE pilota.ID = risultato.ID_pilota
					AND pilota.nome = '".$pilota."'
					AND risultato.giro_veloce = 1
					ORDER BY punti desc, nome desc";
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0)
				$gpv = $result->fetch_assoc();
			
			//
			///
			/////////////////////////////////////////
			//	QUERY PODI
			//////////////////////////////////////////
			$sql = "SELECT COUNT(risultato.ID) as podi  FROM pilota, risultato
					WHERE pilota.ID = risultato.ID_pilota
					AND pilota.nome = '".$pilota."'
					AND risultato.posizione <= 3
					ORDER BY punti desc, nome desc";
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0)
				$podi = $result->fetch_assoc();
			
			//
			///
			/////////////////////////////////////////
			//	QUERY TOP 5
			//////////////////////////////////////////
			$sql = "SELECT COUNT(risultato.ID) as topfive  FROM pilota, risultato
					WHERE pilota.ID = risultato.ID_pilota
					AND pilota.nome = '".$pilota."'
					AND risultato.posizione <= 5
					ORDER BY punti desc, nome desc";
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0)
				$topfive = $result->fetch_assoc();
			
			//
			///
			/////////////////////////////////////////
			//	QUERY RITIRI
			//////////////////////////////////////////
			$sql = "SELECT COUNT(risultato.ID) as ritiri  FROM pilota, risultato
					WHERE pilota.ID = risultato.ID_pilota
					AND pilota.nome = '".$pilota."'
					AND risultato.ritiro = 1
					ORDER BY punti desc, nome desc";
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0)
				$ritiri = $result->fetch_assoc();
			
			//
			///
			/////////////////////////////////////////
			//	QUERY SQUALIFICHE
			//////////////////////////////////////////
			$sql = "SELECT COUNT(risultato.ID) as squalifiche  FROM pilota, risultato
					WHERE pilota.ID = risultato.ID_pilota
					AND pilota.nome = '".$pilota."'
					AND risultato.squalifica = 1
					ORDER BY punti desc, nome desc";
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0)
				$squalifiche = $result->fetch_assoc();
			
			
			echo "</br>";
			echo " <div class='container container-personalizzato'><h1>  ".$iniziale["scuderia"]."      ".$iniziale["nome"]."   </h1></div>";
			//////////////////////////////////////////////////////////////TABELLONE CON SCHEDA 
			echo"
			<div class='container' >
					   
				  <table class='table table-striped table-hover'>
					  <tr>
						<td>CATEGORIA</td>
					  </tr>
					  <tr>
						<td>".$iniziale['campionato']." league</td>
					  </tr>
					  <tr>
						<td>GARE</td>
					  </tr>
					  <tr>
						<td>".(0 + $gare['gare'])."</td>
					  </tr>
					  <tr>
						<td>VITTORIE</td>
					  </tr>
					  <tr>
						<td>".(0 + $vittorie['vittorie'])."</td>
					  </tr>
				  </table>
					   
				  <table class='table table-striped table-hover'>
					  <tr>
						<td>PODI</td>
					  </tr>
					  <tr>
						<td>".(0 + $podi['podi'])."</td>
					  </tr>
					  <tr>
						<td>TOP 5</td>
					  </tr>
					  <tr>
						<td>".(0 + $topfive['topfive'])."</td>
					  </tr>
					  <tr>
						<td>POLEPOSITION</td>
					  </tr>
					  <tr>
						<td>".(0 + $pole['pole'])."</td>
					  </tr>
				  </table>
							
				  <table class='table table-striped table-hover'>
					  <tr>
						<td>GIRI VELOCI</td>
					  </tr>
					  <tr>
						<td>".(0 + $gpv['gpv'])."</td>
					  </tr>
					  <tr>
						<td>RITIRI</td>
					  </tr>
					  <tr>
						<td>".(0 + $ritiri['ritiri'])."</td>
					  </tr>
					  <tr>
						<td>SQUALIFICHE</td>
					  </tr>
					  <tr>
						<td>".(0 + $squalifiche['squalifiche'])."</td>
					  </tr>
				  </table>
			</div>
			";
		//////////////////////////////////////////////////////////////TABELLONE CON SCHEDA FINE
			
			
			
		}
		
		else{
			if($pilota != "")
				echo"</br> <div class='container container-personalizzato'><h1> error ".$pilota." non trovato </h1></div>";
		}




	 ?>

	

  

  
    </main><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<!-- Jquery      -->
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	
	<!-- Multiple layer dropdown script -->
	<script src="js/multiple layer dropdown.js"></script>
	
	
	<!-- Autocomplete script per ricerca piloti-->
 <script>
	$(document).ready(function() {
	$("input#autocomplete").autocomplete({
		source: [<?php
						$stringa="";
						$sql = "SELECT nome FROM pilota";
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
	});
  </script>
  <script>
  function changeText() 
	{ 
	
	}
	</script>
	
  </body>
</html>