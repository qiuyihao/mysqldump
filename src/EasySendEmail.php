<?php

namespace EasyMySQLDump;

use PHPMailer;
use phpmailerException;

/**
 * Class EasySendEmail
 * @package EasyMySQLDump
 */
class EasySendEmail
{
	/**
	 * Error message
	 * @var string $error
	 */
	public $error;

	/**
	 * Debug mode
	 * @var bool
	 */
	public $debug = false;

	/**
	 * SMTP configure
	 */
	private $_host;
	private $_port;
	private $_username;
	private $_password;

	/**
	 * Sender
	 */
	private $_sendAddress;
	private $_sendName;

	/**
	 * Replier
	 */
	private $_replyAddress;
	private $_replyName;

	/**
	 * To
	 * @var array|string $_toAddress
	 */
	private $_toAddress;

	/**
	 * Email subject and body
	 */
	private $_subject;
	private $_body;
	private $_attachments;

	/**
	 * @param string $host
	 * @param integer $port
	 * @param string $username
	 * @param string $password
	 * @return $this
	 */
	public function setSMTP($host, $port, $username, $password)
	{
		$this->_host = $host;
		$this->_port = $port;
		$this->_username = $username;
		$this->_password = $password;
		return $this;
	}

	/**
	 * Set sender
	 * @param string $address
	 * @param string $name
	 * @return $this
	 */
	public function setSender($address, $name)
	{
		$this->_sendAddress = $address;
		$this->_sendName = $name;
		return $this;
	}

	/**
	 * @param string $address
	 * @param string $name
	 * @return $this
	 */
	public function setReplier($address, $name)
	{
		$this->_replyAddress = $address;
		$this->_replyName = $name;
		return $this;
	}

	/**
	 * Set receiver
	 * @param string $address
	 * @return $this
	 */
	public function setReceiver($address)
	{
		$this->_toAddress = $address;
		return $this;
	}

	/**
	 * Set subject
	 * @param string $subject
	 * @return $this
	 */
	public function setSubject($subject)
	{
		$this->_subject = $subject;
		return $this;
	}

	/**
	 * Set body
	 * @param string $body
	 * @return $this
	 */
	public function setBody($body)
	{
		$this->_body = $body;
		return $this;
	}

	/**
	 * Add attachment
	 * @param string $path
	 * @param string $name
	 * @return $this
	 */
	public function addAttachment($path, $name)
	{
		$this->_attachments[] = [
			'path' => $path,
			'name' => $name,
		];
		return $this;
	}

	/**
	 * Send email
	 * @return bool
	 */
	public function send()
	{
		if (empty($this->_replyAddress) || empty($this->_replyName)) {
			$this->_replyAddress = $this->_sendAddress;
			$this->_replyName = $this->_sendName;
		}

		try {
			$phpMailer = new PHPMailer();
			$phpMailer->CharSet = 'UTF-8';
			$phpMailer->isSMTP();
			$phpMailer->SMTPDebug = $this->debug;
			$phpMailer->SMTPAuth = true;
			$phpMailer->SMTPSecure = "ssl";
			$phpMailer->Host = $this->_host;
			$phpMailer->Port = $this->_port;
			$phpMailer->Username = $this->_username;
			$phpMailer->Password = $this->_password;
			$phpMailer->setFrom($this->_sendAddress, $this->_sendName);
			$phpMailer->addReplyTo($this->_replyAddress, $this->_replyName);
			if (is_array($this->_toAddress)) {
				foreach ($this->_toAddress as $toAddress) {
					$phpMailer->addAddress($toAddress);
				}
			} else {
				$phpMailer->addAddress($this->_toAddress);
			}
			$phpMailer->Subject = $this->_subject;
			$phpMailer->isHTML(true);
			$phpMailer->Body = $this->_body;
			if (!empty($this->_attachments) && is_array($this->_attachments)) {
				foreach ($this->_attachments as $attachment) {
					$phpMailer->addAttachment($attachment['path'], $attachment['name']);
				}
			}
			if (!$phpMailer->Send()) {
				return false;
			}
			return true;
		}
		catch(phpmailerException $e) {
			$this->error = $e->getMessage();
			return false;
		}
	}
}