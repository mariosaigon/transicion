<?php
/**
 * Implementation of EditDocument view
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
 * Class which outputs the html page for EditDocument view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_View_EditDocument extends SeedDMS_Bootstrap_Style {

	function js() { /* {{{ */
		$strictformcheck = $this->params['strictformcheck'];
		header('Content-Type: application/javascript');

?>

function checkForm()
{
	msg = new Array();
	if ($("#name").val() == "") msg.push("<?php printMLText("js_no_name");?>");
<?php
	if ($strictformcheck) {
	?>
	if ($("#comment").val() == "") msg.push("<?php printMLText("js_no_comment");?>");
	if ($("#keywords").val() == "") msg.push("<?php printMLText("js_no_keywords");?>");
<?php
	}
?>
	if (msg != "")
	{
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

$(document).ready( function() {
/*
	$('body').on('submit', '#form1', function(ev){
		if(checkForm()) return;
		ev.preventDefault();
	});
*/
	$("#form1").validate({
		invalidHandler: function(e, validator) {
			noty({
				text:  (validator.numberOfInvalids() == 1) ? "<?php printMLText("js_form_error");?>".replace('#', validator.numberOfInvalids()) : "<?php printMLText("js_form_errors");?>".replace('#', validator.numberOfInvalids()),
				type: 'error',
				dismissQueue: true,
				layout: 'topRight',
				theme: 'defaultTheme',
				timeout: 1500,
			});
		},
		messages: {
			name: "<?php printMLText("js_no_name");?>",
			comment: "<?php printMLText("js_no_comment");?>",
			keywords: "<?php printMLText("js_no_keywords");?>"
		}
	});
});
<?php
		$this->printKeywordChooserJs('form1');
		
	} /* }}} */

	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$folder = $this->params['folder'];
		$document = $this->params['document'];
		$attrdefs = $this->params['attrdefs'];
		$strictformcheck = $this->params['strictformcheck'];
		$orderby = $this->params['orderby'];
		$stringActas="Actas de inexistencia";
		$stringFechaEmision="Fecha de emisión del acta de inexistencia";
		$this->htmlAddHeader('<script type="text/javascript" src="../styles/'.$this->theme.'/validate/jquery.validate.js"></script>'."\n", 'js');

		$this->htmlStartPage(getMLText("document_title", array("documentname" => htmlspecialchars($document->getName()))), "skin-blue layout-top-nav");

		$this->containerStart();
		$this->mainHeader();
		//$this->mainSideBar();
		$this->contentStart();		

		echo $this->getDefaultFolderPathHTML($folder, true, $document);
		$esActa=FALSE;

		//// Document content ////
		echo "<div class=\"row\">";
		echo "<div class=\"col-md-12\">";

		$this->startBoxPrimary(getMLText("edit_document_props"));

		if($document->expires())
			$expdate = date('Y-m-d', $document->getExpires());
		else
			$expdate = '';
?>
<div class="table-responsive">
<form action="../op/op.EditDocument.php" name="form1" id="form1" method="post">
	<input type="hidden" name="documentid" value="<?php echo $document->getID() ?>">
	<table class="table-condensed">
		<tr>
			<td class=""><?php printMLText("name");?>:</td>
			<td><input class="form-control" type="text" name="name" id="name" value="<?php print htmlspecialchars($document->getName());?>" size="60" required></td>
		</tr>
		<tr>
			<td valign="top" class=""><?php printMLText("comment");?>:</td>
			<td><textarea class="form-control" name="comment" id="comment" rows="4" cols="80"<?php echo $strictformcheck ? ' required' : ''; ?>><?php print htmlspecialchars($document->getComment());?></textarea></td>
		</tr>
		<!--	<tr>
		<td valign="top" class=""><?php //printMLText("keywords");?>:</td>
			<td class="standardText"> -->

		<!--	</td>
		</tr> -->
		<tr>
			<td><?php printMLText("categories")?>:</td>
			<td>
        <select class="chzn-select form-control" name="categories[]" multiple="multiple" data-placeholder="<?php printMLText('select_category'); ?>" data-no_results_text="<?php printMLText('unknown_document_category'); ?>">
<?php
			$categories = $dms->getDocumentCategories();
			foreach($categories as $category) {
				echo "<option value=\"".$category->getID()."\"";
				if(in_array($category, $document->getCategories()))
					echo " selected";
				echo ">".$category->getName()."</option>";	
			}
?>
				</select>
      </td>
		</tr>
<?php
			//$categories = $dms->getDocumentCategories();			
			$categoActas=$dms->getDocumentCategoryByName($stringActas);
			$nombreCategoActas=$categoActas->getName();
		
			//modificado por Mario
				// echo "<option value=\"".$category->getID()."\"";
				// if(in_array($category, $document->getCategories()))
				// 	echo " selected";
				// echo ">".$category->getName()."</option>";
				//$nombreCatego=$category->getName();
				//echo "Nombre catego: ".$nombreCategoActas;
			$categorias=$document->getCategories();
			foreach ($categorias as $cat) 
			{
				if(strcmp($cat->getName(),$stringActas)==0)
				{
					$esActa=TRUE;
					//echo "es acta puesto a TRIE";
				}	
			}
			
?>
<?php
		if(!$esActa)
		{

			echo "<tr>";
			echo "<td class=\"float-left\">";
			printMLText("expires");
			echo":</td>";
			echo"<td>";


      echo "<span class=\"input-append date span12\" id=\"expirationdate\" data-date=\"$expdate\" data-date-format=\"yyyy-mm-dd\" data-date-language=\"".str_replace('_', '-', $this->params['session']->getLanguage())."\" data-checkbox=\"#expires\">";
         echo "<input class=\"span3 form-control\" size=\"16\" name=\"expdate\" type=\"text\" value=\"$expdate\">";

         echo "<span class=\"add-on\"><i class=\"icon-calendar\"></i></span>";
        echo "</span><br />";
        
	echo "<input type=\"hidden\" id=\"expires\" name=\"expires\" value=\"true\">";
			echo "</td>";
		echo "</tr>";
}
else
{
	//echo "es acta";
	 echo "<input type=\"hidden\" id=\"expires\" name=\"expires\" value=\"false\">";
}

?>
<?php
		if($attrdefs) 
		{
			if($esActa)
			{
				foreach($attrdefs as $attrdef) 
				{//inicio foreach
					if(strcmp($attrdef->getName(), $stringFechaEmision)==0)
							{
						echo "<tr>";
					echo"<td>".htmlspecialchars($attrdef->getName()).":</td>";
					echo "<td>";
					$this->printAttributeEditField($attrdef, $document->getAttribute($attrdef));
					echo "</td>";
							echo "</tr>";
							}			
				}//fin foreach

			}//fin de si es acta
			else //si no es acta
			{
				foreach($attrdefs as $attrdef) 
				{//inicio foreach
					if(strcmp($attrdef->getName(), $stringFechaEmision)!=0)
							{
						echo "<tr>";
					echo"<td>".htmlspecialchars($attrdef->getName()).":</td>";
					echo "<td>";
					$this->printAttributeEditField($attrdef, $document->getAttribute($attrdef));
					echo "</td>";
							echo "</tr>";
							}			
				}//fin foreach
			} //fin del else si no es acta
		}
?>
		<tr>
			<td></td>
			<td><button type="submit" class="btn btn-info"><i class="fa fa-save"></i> <?php printMLText("save")?></button></td>
		</tr>
	</table>
</form>
</div>
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
