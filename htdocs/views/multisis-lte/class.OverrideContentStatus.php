<?php
/**
 * Implementation of OverrideContentStatus view
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
 * Class which outputs the html page for OverrideContentStatus view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_View_OverrideContentStatus extends SeedDMS_Bootstrap_Style {

	function js() { /* {{{ */
		header('Content-Type: application/javascript; charset=UTF-8');
?>
function checkForm()
{
	msg = new Array();
	if (document.form1.overrideStatus.value == "") msg.push("<?php printMLText("js_no_override_status");?>");
	if (document.form1.comment.value == "") msg.push("<?php printMLText("js_no_comment");?>");
	if (msg != "") {
  	noty({
  		text: msg.join('<br />'),
  		type: 'error',
      dismissQueue: true,
  		layout: 'topRight',
  		theme: 'defaultTheme',
			_timeout: 1500,
  	});
		return false;
	}
	else
		return true;
}
$(document).ready(function() {
	$('body').on('submit', '#form1', function(ev){
		if(checkForm()) return;
		ev.preventDefault();
	});
});
<?php
	} /* }}} */

	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$folder = $this->params['folder'];
		$document = $this->params['document'];
		$content = $this->params['version'];

		$overallStatus = $content->getStatus();
		$reviewStatus = $content->getReviewStatus();
		$approvalStatus = $content->getApprovalStatus();

		$this->htmlStartPage(getMLText("document_title", array("documentname" => htmlspecialchars($document->getName()))), "skin-blue sidebar-mini");
		$this->containerStart();
		$this->mainHeader();
		$this->mainSideBar();
		$this->contentStart();		

		echo $this->getDefaultFolderPathHTML($folder, true, $document);

		echo "<div class=\"row\">";
		echo "<div class=\"col-md-12\">";

		$this->startBoxPrimary(getMLText("Revocar la reserva de un documento: ingrese fecha de resolución y comentario"));

// Display the Review form.
?>
<form class="form-horizontal" method="post" action="../op/op.OverrideContentStatus.php" id="form1" name="form1">
	<div class="control-group">
	<label class="control-label"><?php echo(printMLText("Fecha de la resolución del IAIP de revocación de la reserva de este documento"));?>:</label>
		<span class="input-append date" style="display: inline;" id="createstartdate" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd" data-date-language="<?php echo str_replace('_', '-', $this->params['session']->getLanguage()); ?>">
          <input class="span4 form-control" size="16" name="fechaRevocacion" type="text" value="<?php 
			  echo date('d-m-Y'); 		
		  ?>">
          <span class="add-on"><i class="icon-calendar"></i></span>
        </span>&nbsp;


		<label class="control-label"><?php echo(printMLText("comment"));?>:</label>
		<div class="controls">
			<textarea class="form-control" name="comment" cols="40" rows="4"></textarea>
		</div>
	</div>
	<!-- <div class="control-group">
		<label class="control-label"><?php echo(printMLText("status")); ?>:</label>
		<div class="controls"> -->
	<?php

			// if ($overallStatus["status"] == S_OBSOLETE) echo "<option value='".S_RELEASED."'>".getOverallStatusText(S_RELEASED)."</option>";
			echo "<input type=\"hidden\" name=\"overrideStatus\" value='".S_OBSOLETE."'></input>";
	?>

<!-- 	</div></div> -->
	<div class="box-footer">
		<input type='hidden' name='documentid' value='<?php echo $document->getID() ?>'/>
		<input type='hidden' name='version' value='<?php echo $content->getVersion() ?>'/>
		<button type='submit' class="btn btn-info" name='overrideContentStatus'><i class="fa fa-save"></i><?php echo(printMLText("update")); ?></button>
	</div>
</form>
<?php
		
		$this->endsBoxPrimary();

		echo "</div>";
		echo "</div>"; 
		echo "</div>"; // Ends row

		$this->contentEnd();
		$this->mainFooter();		
		$this->containerEnd();
		$this->htmlEndPage();
	} /* }}} */
}
?>
