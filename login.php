<?php include('config.php'); 

// per verificare una connessione precendente non andata a buon fine

$errore = $_GET['login'];

if ($errore == 'false')
	echo "ERRORE: user e password non corrispondono riprovare";

?>
<!DOCTYPE html>
<html>
<head>

	<meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=yes"  charset="UTF-8">
 
    <title>Collegati per amministrare il sito - <?php echo $sito_internet ?></title>
 
    <!--Pannello di gestione creato da Mel Riccardo-->
    <link href="css/admin.css" rel="stylesheet" type="text/css" />
 
</head>
<body>
 
    <form id="login" action="verifica.php" method="post">
        <fieldset id="inputs">
            <input id="username" name="username" type="text" placeholder="Username" autofocus required>
            <input id="password" name="password" type="password" placeholder="Password" required>
        </fieldset>
        <fieldset id="actions">
            <input type="submit" id="submit" value="Collegati">
            <a href="../index.php" id="back">Ritorna al sito</a>
        </fieldset>
    </form>
 
</body>
</html>