<?php

namespace App;

use App\Exceptions\Handler;

class FTP {

	protected $config = array(
            'host'   => '177.10.199.54',
            'port'  => 21,
            'username' => 'rastreador',
            'password'   => 'F@ast#2008$',
            'passive'   => false,
        );

	protected $cnx;

	function __construct()
	{
		$this->connect();
		$this->login();
		ftp_pasv($this->cnx, $this->config['passive']);
	}

	public function connect()
	{
		$this->cnx = ftp_connect($this->config['host'], $this->config['port']);		
	}

	public function chdir($dir)
	{
		return ftp_chdir($this->cnx, $dir);
	}

	public function close()
	{
		ftp_close($this->cnx);
	}

	public function delete($file)
	{
		return ftp_delete($this->cnx, $file);
	}

	public function read($file)
	{
		$filename = storage_path('framework/cache').'ftp_'.str_random(10).'.xml';
		$local = fopen($filename, 'w');
		ftp_fget($this->cnx, $local, $file, FTP_ASCII, 0);
		fclose($local);
		return simplexml_load_file($filename);
	}

	public function login()
	{
		return ftp_login($this->cnx, $this->config['username'], $this->config['password']);
	}

	public function dir($name = '.')
	{
		return ftp_nlist($this->cnx, $name);
	}
}

?>