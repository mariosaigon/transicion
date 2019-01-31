<?php
/**
 * Implementation of PasswordSend view
 *
 * @category   DMS
 * @package    SeedDMS
 * @license    GPL 2
 * @version    @version@
 * @author     Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */

/**
 * Include parent class
 */
require_once("class.Bootstrap.php");

/**
 * Class which outputs the html page for PasswordSend view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_View_PasswordSend extends SeedDMS_Bootstrap_Style {

	function show() { /* {{{ */
		$referrer = $this->params['referrer'];

		$this->htmlStartPage(getMLText("password_send"));
		
		$this->startLoginContent();
?>

<?php $this->contentContainerStart(); ?>
<?php printMLText('password_send_text'); ?>
<?php $this->contentContainerEnd(); ?>
<p class="align-center"><a type="button" class="btn btn-info" href="../out/out.Login.php"><?php echo getMLText("login"); ?></a></p>
<?php
		$this->endLoginContent();
	} /* }}} */
}
?>
