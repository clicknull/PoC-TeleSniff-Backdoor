<?php
/*
    HijackGram 1.0
    Core (PHP)
*/

Class Core
{
	public $config;
	public $bdd;

	public function __construct()
	{
		$this->load_config();
		$this->render('index');
	}

	private function load_config()
	{
		if(file_exists('config.json'))
			$this->config = json_decode(file_get_contents('config.json'));
		else
			return False;
		$this->bdd = new PDO('mysql:host='.$this->config->database->_host.";dbname=".$this->config->database->_dbname,$this->config->database->_username,$this->config->database->_password);
	}

	private function render($view)
	{
		if(file_exists("template/".$view.".template.php") 
			&& isset($_SESSION['login']) && $_SESSION['login'] != "")
			require_once("template/".$view.".template.php");
		else
			require_once("template/login.template.php");
	}
}