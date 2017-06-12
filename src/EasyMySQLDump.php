<?php

namespace EasyMySQLDump;

use PHPMailer;

class EasyMySQLDump
{
	/**
	 * Error message
	 * @var string $error
	 */
	public $error;

	/**
	 * MySQL database configure
	 */
	private $_host;
	private $_port;
	private $_user;
	private $_password;
	private $_db;

	/**
	 * MySQL dumpfile path
	 * @var string $_dumpfile
	 */
	private $_dumpfile;

	public function __construct()
	{
	}

	/**
	 * Set database configure
	 * @param string $host
	 * @param integer $port
	 * @param string $user
	 * @param string $password
	 * @param string $db
	 * @return $this
	 */
	public function setDatabase($host, $port, $user, $password, $db)
	{
		$this->_host = $host;
		$this->_port = $port;
		$this->_user = $user;
		$this->_password = $password;
		$this->_db = $db;
		return $this;
	}

	/**
	 * Set dumpfile path
	 * @param string $path
	 * @return $this
	 */
	public function setDumpFile($path)
	{
		$this->_dumpfile = $path;
		return $this;
	}

	/**
	 * MySQL dump
	 * @return bool
	 */
	public function mysqldump()
	{
		$command = "mysqldump -h{$this->_host} -P{$this->_port} -u{$this->_user} ";
		if (!empty($this->_password)) {
			$command .= "-p{$this->_password} ";
		}
		$command .= "{$this->_db} > {$this->_dumpfile}";
		$output = [];
		$result = exec($command, $output);
		if (empty($result)) {
			chmod($this->_dumpfile, 0644);
			return true;
		}

		$this->error = $result;
		return false;
	}

	public function getDumpFile()
	{
		return $this->_dumpfile;
	}
}