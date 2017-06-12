## A portable tool for mysqldump

<pre>
<code>

use EasyMySQLDump\EasyMySQLDump;
use EasyMySQLDump\EasySendEmail;

/**
 * 1. Mysqldump
 */
$easyMySQLDump = new EasyMySQLDump();
$easyMySQLDump->setDatabase('127.0.0.1', 3306, 'root', '', 'test')
	->setDumpFile('/web/test.mysqldump.sql')
	->mysqldump();

/**
 * 2. Send email
 */
$easySendEmail = new EasySendEmail();
$easySendEmail->debug = true;
$sendResult = $easySendEmail
	->setSubject('mysqldump')
	->setBody('A mail to backup mysqldump file')
	->setSMTP('smtp.yeah.net', 465, '[username]@yeah.net', '[password]')
	->setSender('[sender address]', '[sender name]')
	->setReceiver('[receiver address]')
	->addAttachment($easyMySQLDump->getDumpFile(), 'mysqldump.sql')
	->send();
if ($sendResult === false) {
	echo $easySendEmail->error;
	exit;
}
</code>
</pre>