<?php
	$fp =fopen("/dev/ttyUSB0", "w+");
	if( !$fp) {
		echo "Error";
		die();
	}

	while(true){
		fflush($fp);
		$buffer = "";
		if(($buffer = fgets($fp, 10)) === false){
			echo "PROBLEMI ALLA PORTA SERIALE\n\n";
			exit;
		}
		// Controllo di ciò che è arrivato	
		if(strpos($buffer,"[") === false) continue;
		if(strpos($buffer,"]") === false) continue;
		// Ripulisce i dati
		$buffer_tmp = str_replace ("[" , "" ,$buffer);
		$value = str_replace ("]" , "" ,$buffer_tmp);
		// echo $value . "\n";
		/* Dice il valore
		$format = "espeak -g 4 -v europe/it \"%s gradi\" --stdout | aplay;echo";
		$cmd = sprintf($format,$value);
		system($cmd);
		*/
		// Lo inserisce nel db
		date_default_timezone_set('UTC');
		$data = date("Y/m/d");
		$time = date("H:i");
	        echo $data . " " . $time . " " . $value;
     		try {
	        	// costruisce una stringa con l'host e il nome del database
	     	        $connect_str = "mysql:host=localhost;dbname=temperature_db;";
	                // Crea una connessione con il database aggiungendo il nome utente e la password
	                $db_handle = new PDO($connect_str,'user_temperature_db','password_temperature_db');
	                // Imposta alcune opzioni (uso delle eccezioni)
	                $db_handle->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	                // Imposta la query di inserimento in una stringa aggiungendo i valori arrivati
	                $query = "INSERT INTO temperature_tbl (date, time,temperature) VALUES ('" . $data . "','" . $time . "','" . $value . "')";
	                // Comunica al server db di preparare la query
	                $sth = $db_handle->prepare($query);
	                // Gli dice di eseguirla
	                $result = $sth->execute();
	       }
	      // Se qualcosa non funziona viene eseguito il codice sottostante
	      catch(PDOException $e) {
	      		echo "Errore:" . $e->getMessage();
	      		return;
	      }
	      echo "Dati inseriti!: " . $data . " " . $time . " " . $value . "\n";
	}
	fclose($fp);
?>

