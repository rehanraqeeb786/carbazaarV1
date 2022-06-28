<?php include_once "adm_usersinfo.php" ?>
<?php

// Create page object
if (!isset($ad_pictures_grid)) $ad_pictures_grid = new cad_pictures_grid();

// Page init
$ad_pictures_grid->Page_Init();

// Page main
$ad_pictures_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ad_pictures_grid->Page_Render();
?>
<?php if ($ad_pictures->Export == "") { ?>
<script type="text/javascript">

// Form object
var fad_picturesgrid = new ew_Form("fad_picturesgrid", "grid");
fad_picturesgrid.FormKeyCountName = '<?php echo $ad_pictures_grid->FormKeyCountName ?>';

// Validate form
fad_picturesgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_ad_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ad_pictures->ad_id->FldCaption(), $ad_pictures->ad_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ad_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ad_pictures->ad_id->FldErrMsg()) ?>");
			felm = this.GetElements("x" + infix + "_picture_link");
			elm = this.GetElements("fn_x" + infix + "_picture_link");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $ad_pictures->picture_link->FldCaption(), $ad_pictures->picture_link->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fad_picturesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "ad_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "picture_link", false)) return false;
	return true;
}

// Form_CustomValidate event
fad_picturesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fad_picturesgrid.ValidateRequired = true;
<?php } else { ?>
fad_picturesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($ad_pictures->CurrentAction == "gridadd") {
	if ($ad_pictures->CurrentMode == "copy") {
		$bSelectLimit = $ad_pictures_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$ad_pictures_grid->TotalRecs = $ad_pictures->SelectRecordCount();
			$ad_pictures_grid->Recordset = $ad_pictures_grid->LoadRecordset($ad_pictures_grid->StartRec-1, $ad_pictures_grid->DisplayRecs);
		} else {
			if ($ad_pictures_grid->Recordset = $ad_pictures_grid->LoadRecordset())
				$ad_pictures_grid->TotalRecs = $ad_pictures_grid->Recordset->RecordCount();
		}
		$ad_pictures_grid->StartRec = 1;
		$ad_pictures_grid->DisplayRecs = $ad_pictures_grid->TotalRecs;
	} else {
		$ad_pictures->CurrentFilter = "0=1";
		$ad_pictures_grid->StartRec = 1;
		$ad_pictures_grid->DisplayRecs = $ad_pictures->GridAddRowCount;
	}
	$ad_pictures_grid->TotalRecs = $ad_pictures_grid->DisplayRecs;
	$ad_pictures_grid->StopRec = $ad_pictures_grid->DisplayRecs;
} else {
	$bSelectLimit = $ad_pictures_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($ad_pictures_grid->TotalRecs <= 0)
			$ad_pictures_grid->TotalRecs = $ad_pictures->SelectRecordCount();
	} else {
		if (!$ad_pictures_grid->Recordset && ($ad_pictures_grid->Recordset = $ad_pictures_grid->LoadRecordset()))
			$ad_pictures_grid->TotalRecs = $ad_pictures_grid->Recordset->RecordCount();
	}
	$ad_pictures_grid->StartRec = 1;
	$ad_pictures_grid->DisplayRecs = $ad_pictures_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$ad_pictures_grid->Recordset = $ad_pictures_grid->LoadRecordset($ad_pictures_grid->StartRec-1, $ad_pictures_grid->DisplayRecs);

	// Set no record found message
	if ($ad_pictures->CurrentAction == "" && $ad_pictures_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$ad_pictures_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($ad_pictures_grid->SearchWhere == "0=101")
			$ad_pictures_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$ad_pictures_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$ad_pictures_grid->RenderOtherOptions();
?>
<?php $ad_pictures_grid->ShowPageHeader(); ?>
<?php
$ad_pictures_grid->ShowMessage();
?>
<?php if ($ad_pictures_grid->TotalRecs > 0 || $ad_pictures->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fad_picturesgrid" class="ewForm form-inline">
<div id="gmp_ad_pictures" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_ad_picturesgrid" class="table ewTable">
<?php echo $ad_pictures->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$ad_pictures_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$ad_pictures_grid->RenderListOptions();

// Render list options (header, left)
$ad_pictures_grid->ListOptions->Render("header", "left");
?>
<?php if ($ad_pictures->ad_id->Visible) { // ad_id ?>
	<?php if ($ad_pictures->SortUrl($ad_pictures->ad_id) == "") { ?>
		<th data-name="ad_id"><div id="elh_ad_pictures_ad_id" class="ad_pictures_ad_id"><div class="ewTableHeaderCaption"><?php echo $ad_pictures->ad_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ad_id"><div><div id="elh_ad_pictures_ad_id" class="ad_pictures_ad_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ad_pictures->ad_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ad_pictures->ad_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ad_pictures->ad_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($ad_pictures->picture_link->Visible) { // picture_link ?>
	<?php if ($ad_pictures->SortUrl($ad_pictures->picture_link) == "") { ?>
		<th data-name="picture_link"><div id="elh_ad_pictures_picture_link" class="ad_pictures_picture_link"><div class="ewTableHeaderCaption"><?php echo $ad_pictures->picture_link->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="picture_link"><div><div id="elh_ad_pictures_picture_link" class="ad_pictures_picture_link">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ad_pictures->picture_link->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ad_pictures->picture_link->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ad_pictures->picture_link->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ad_pictures_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$ad_pictures_grid->StartRec = 1;
$ad_pictures_grid->StopRec = $ad_pictures_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($ad_pictures_grid->FormKeyCountName) && ($ad_pictures->CurrentAction == "gridadd" || $ad_pictures->CurrentAction == "gridedit" || $ad_pictures->CurrentAction == "F")) {
		$ad_pictures_grid->KeyCount = $objForm->GetValue($ad_pictures_grid->FormKeyCountName);
		$ad_pictures_grid->StopRec = $ad_pictures_grid->StartRec + $ad_pictures_grid->KeyCount - 1;
	}
}
$ad_pictures_grid->RecCnt = $ad_pictures_grid->StartRec - 1;
if ($ad_pictures_grid->Recordset && !$ad_pictures_grid->Recordset->EOF) {
	$ad_pictures_grid->Recordset->MoveFirst();
	$bSelectLimit = $ad_pictures_grid->UseSelectLimit;
	if (!$bSelectLimit && $ad_pictures_grid->StartRec > 1)
		$ad_pictures_grid->Recordset->Move($ad_pictures_grid->StartRec - 1);
} elseif (!$ad_pictures->AllowAddDeleteRow && $ad_pictures_grid->StopRec == 0) {
	$ad_pictures_grid->StopRec = $ad_pictures->GridAddRowCount;
}

// Initialize aggregate
$ad_pictures->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ad_pictures->ResetAttrs();
$ad_pictures_grid->RenderRow();
if ($ad_pictures->CurrentAction == "gridadd")
	$ad_pictures_grid->RowIndex = 0;
if ($ad_pictures->CurrentAction == "gridedit")
	$ad_pictures_grid->RowIndex = 0;
while ($ad_pictures_grid->RecCnt < $ad_pictures_grid->StopRec) {
	$ad_pictures_grid->RecCnt++;
	if (intval($ad_pictures_grid->RecCnt) >= intval($ad_pictures_grid->StartRec)) {
		$ad_pictures_grid->RowCnt++;
		if ($ad_pictures->CurrentAction == "gridadd" || $ad_pictures->CurrentAction == "gridedit" || $ad_pictures->CurrentAction == "F") {
			$ad_pictures_grid->RowIndex++;
			$objForm->Index = $ad_pictures_grid->RowIndex;
			if ($objForm->HasValue($ad_pictures_grid->FormActionName))
				$ad_pictures_grid->RowAction = strval($objForm->GetValue($ad_pictures_grid->FormActionName));
			elseif ($ad_pictures->CurrentAction == "gridadd")
				$ad_pictures_grid->RowAction = "insert";
			else
				$ad_pictures_grid->RowAction = "";
		}

		// Set up key count
		$ad_pictures_grid->KeyCount = $ad_pictures_grid->RowIndex;

		// Init row class and style
		$ad_pictures->ResetAttrs();
		$ad_pictures->CssClass = "";
		if ($ad_pictures->CurrentAction == "gridadd") {
			if ($ad_pictures->CurrentMode == "copy") {
				$ad_pictures_grid->LoadRowValues($ad_pictures_grid->Recordset); // Load row values
				$ad_pictures_grid->SetRecordKey($ad_pictures_grid->RowOldKey, $ad_pictures_grid->Recordset); // Set old record key
			} else {
				$ad_pictures_grid->LoadDefaultValues(); // Load default values
				$ad_pictures_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$ad_pictures_grid->LoadRowValues($ad_pictures_grid->Recordset); // Load row values
		}
		$ad_pictures->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($ad_pictures->CurrentAction == "gridadd") // Grid add
			$ad_pictures->RowType = EW_ROWTYPE_ADD; // Render add
		if ($ad_pictures->CurrentAction == "gridadd" && $ad_pictures->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$ad_pictures_grid->RestoreCurrentRowFormValues($ad_pictures_grid->RowIndex); // Restore form values
		if ($ad_pictures->CurrentAction == "gridedit") { // Grid edit
			if ($ad_pictures->EventCancelled) {
				$ad_pictures_grid->RestoreCurrentRowFormValues($ad_pictures_grid->RowIndex); // Restore form values
			}
			if ($ad_pictures_grid->RowAction == "insert")
				$ad_pictures->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$ad_pictures->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($ad_pictures->CurrentAction == "gridedit" && ($ad_pictures->RowType == EW_ROWTYPE_EDIT || $ad_pictures->RowType == EW_ROWTYPE_ADD) && $ad_pictures->EventCancelled) // Update failed
			$ad_pictures_grid->RestoreCurrentRowFormValues($ad_pictures_grid->RowIndex); // Restore form values
		if ($ad_pictures->RowType == EW_ROWTYPE_EDIT) // Edit row
			$ad_pictures_grid->EditRowCnt++;
		if ($ad_pictures->CurrentAction == "F") // Confirm row
			$ad_pictures_grid->RestoreCurrentRowFormValues($ad_pictures_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$ad_pictures->RowAttrs = array_merge($ad_pictures->RowAttrs, array('data-rowindex'=>$ad_pictures_grid->RowCnt, 'id'=>'r' . $ad_pictures_grid->RowCnt . '_ad_pictures', 'data-rowtype'=>$ad_pictures->RowType));

		// Render row
		$ad_pictures_grid->RenderRow();

		// Render list options
		$ad_pictures_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($ad_pictures_grid->RowAction <> "delete" && $ad_pictures_grid->RowAction <> "insertdelete" && !($ad_pictures_grid->RowAction == "insert" && $ad_pictures->CurrentAction == "F" && $ad_pictures_grid->EmptyRow())) {
?>
	<tr<?php echo $ad_pictures->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ad_pictures_grid->ListOptions->Render("body", "left", $ad_pictures_grid->RowCnt);
?>
	<?php if ($ad_pictures->ad_id->Visible) { // ad_id ?>
		<td data-name="ad_id"<?php echo $ad_pictures->ad_id->CellAttributes() ?>>
<?php if ($ad_pictures->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($ad_pictures->ad_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $ad_pictures_grid->RowCnt ?>_ad_pictures_ad_id" class="form-group ad_pictures_ad_id">
<span<?php echo $ad_pictures->ad_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ad_pictures->ad_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" name="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_pictures->ad_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $ad_pictures_grid->RowCnt ?>_ad_pictures_ad_id" class="form-group ad_pictures_ad_id">
<input type="text" data-table="ad_pictures" data-field="x_ad_id" name="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" id="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" size="30" placeholder="<?php echo ew_HtmlEncode($ad_pictures->ad_id->getPlaceHolder()) ?>" value="<?php echo $ad_pictures->ad_id->EditValue ?>"<?php echo $ad_pictures->ad_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="ad_pictures" data-field="x_ad_id" name="o<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" id="o<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_pictures->ad_id->OldValue) ?>">
<?php } ?>
<?php if ($ad_pictures->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($ad_pictures->ad_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $ad_pictures_grid->RowCnt ?>_ad_pictures_ad_id" class="form-group ad_pictures_ad_id">
<span<?php echo $ad_pictures->ad_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ad_pictures->ad_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" name="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_pictures->ad_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $ad_pictures_grid->RowCnt ?>_ad_pictures_ad_id" class="form-group ad_pictures_ad_id">
<input type="text" data-table="ad_pictures" data-field="x_ad_id" name="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" id="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" size="30" placeholder="<?php echo ew_HtmlEncode($ad_pictures->ad_id->getPlaceHolder()) ?>" value="<?php echo $ad_pictures->ad_id->EditValue ?>"<?php echo $ad_pictures->ad_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($ad_pictures->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $ad_pictures_grid->RowCnt ?>_ad_pictures_ad_id" class="ad_pictures_ad_id">
<span<?php echo $ad_pictures->ad_id->ViewAttributes() ?>>
<?php echo $ad_pictures->ad_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="ad_pictures" data-field="x_ad_id" name="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" id="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_pictures->ad_id->FormValue) ?>">
<input type="hidden" data-table="ad_pictures" data-field="x_ad_id" name="o<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" id="o<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_pictures->ad_id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ad_pictures_grid->PageObjName . "_row_" . $ad_pictures_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($ad_pictures->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="ad_pictures" data-field="x_ID" name="x<?php echo $ad_pictures_grid->RowIndex ?>_ID" id="x<?php echo $ad_pictures_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($ad_pictures->ID->CurrentValue) ?>">
<input type="hidden" data-table="ad_pictures" data-field="x_ID" name="o<?php echo $ad_pictures_grid->RowIndex ?>_ID" id="o<?php echo $ad_pictures_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($ad_pictures->ID->OldValue) ?>">
<?php } ?>
<?php if ($ad_pictures->RowType == EW_ROWTYPE_EDIT || $ad_pictures->CurrentMode == "edit") { ?>
<input type="hidden" data-table="ad_pictures" data-field="x_ID" name="x<?php echo $ad_pictures_grid->RowIndex ?>_ID" id="x<?php echo $ad_pictures_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($ad_pictures->ID->CurrentValue) ?>">
<?php } ?>
	<?php if ($ad_pictures->picture_link->Visible) { // picture_link ?>
		<td data-name="picture_link"<?php echo $ad_pictures->picture_link->CellAttributes() ?>>
<?php if ($ad_pictures_grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_ad_pictures_picture_link" class="form-group ad_pictures_picture_link">
<div id="fd_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link">
<span title="<?php echo $ad_pictures->picture_link->FldTitle() ? $ad_pictures->picture_link->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($ad_pictures->picture_link->ReadOnly || $ad_pictures->picture_link->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="ad_pictures" data-field="x_picture_link" name="x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id="x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link"<?php echo $ad_pictures->picture_link->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fn_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $ad_pictures->picture_link->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fa_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="0">
<input type="hidden" name="fs_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fs_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="255">
<input type="hidden" name="fx_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fx_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $ad_pictures->picture_link->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fm_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $ad_pictures->picture_link->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="ad_pictures" data-field="x_picture_link" name="o<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id="o<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo ew_HtmlEncode($ad_pictures->picture_link->OldValue) ?>">
<?php } elseif ($ad_pictures->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $ad_pictures_grid->RowCnt ?>_ad_pictures_picture_link" class="ad_pictures_picture_link">
<span<?php echo $ad_pictures->picture_link->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($ad_pictures->picture_link, $ad_pictures->picture_link->ListViewValue()) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $ad_pictures_grid->RowCnt ?>_ad_pictures_picture_link" class="form-group ad_pictures_picture_link">
<div id="fd_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link">
<span title="<?php echo $ad_pictures->picture_link->FldTitle() ? $ad_pictures->picture_link->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($ad_pictures->picture_link->ReadOnly || $ad_pictures->picture_link->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="ad_pictures" data-field="x_picture_link" name="x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id="x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link"<?php echo $ad_pictures->picture_link->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fn_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $ad_pictures->picture_link->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fa_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fa_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fs_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="255">
<input type="hidden" name="fx_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fx_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $ad_pictures->picture_link->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fm_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $ad_pictures->picture_link->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ad_pictures_grid->ListOptions->Render("body", "right", $ad_pictures_grid->RowCnt);
?>
	</tr>
<?php if ($ad_pictures->RowType == EW_ROWTYPE_ADD || $ad_pictures->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fad_picturesgrid.UpdateOpts(<?php echo $ad_pictures_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($ad_pictures->CurrentAction <> "gridadd" || $ad_pictures->CurrentMode == "copy")
		if (!$ad_pictures_grid->Recordset->EOF) $ad_pictures_grid->Recordset->MoveNext();
}
?>
<?php
	if ($ad_pictures->CurrentMode == "add" || $ad_pictures->CurrentMode == "copy" || $ad_pictures->CurrentMode == "edit") {
		$ad_pictures_grid->RowIndex = '$rowindex$';
		$ad_pictures_grid->LoadDefaultValues();

		// Set row properties
		$ad_pictures->ResetAttrs();
		$ad_pictures->RowAttrs = array_merge($ad_pictures->RowAttrs, array('data-rowindex'=>$ad_pictures_grid->RowIndex, 'id'=>'r0_ad_pictures', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($ad_pictures->RowAttrs["class"], "ewTemplate");
		$ad_pictures->RowType = EW_ROWTYPE_ADD;

		// Render row
		$ad_pictures_grid->RenderRow();

		// Render list options
		$ad_pictures_grid->RenderListOptions();
		$ad_pictures_grid->StartRowCnt = 0;
?>
	<tr<?php echo $ad_pictures->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ad_pictures_grid->ListOptions->Render("body", "left", $ad_pictures_grid->RowIndex);
?>
	<?php if ($ad_pictures->ad_id->Visible) { // ad_id ?>
		<td data-name="ad_id">
<?php if ($ad_pictures->CurrentAction <> "F") { ?>
<?php if ($ad_pictures->ad_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_ad_pictures_ad_id" class="form-group ad_pictures_ad_id">
<span<?php echo $ad_pictures->ad_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ad_pictures->ad_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" name="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_pictures->ad_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_ad_pictures_ad_id" class="form-group ad_pictures_ad_id">
<input type="text" data-table="ad_pictures" data-field="x_ad_id" name="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" id="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" size="30" placeholder="<?php echo ew_HtmlEncode($ad_pictures->ad_id->getPlaceHolder()) ?>" value="<?php echo $ad_pictures->ad_id->EditValue ?>"<?php echo $ad_pictures->ad_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_ad_pictures_ad_id" class="form-group ad_pictures_ad_id">
<span<?php echo $ad_pictures->ad_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ad_pictures->ad_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="ad_pictures" data-field="x_ad_id" name="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" id="x<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_pictures->ad_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="ad_pictures" data-field="x_ad_id" name="o<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" id="o<?php echo $ad_pictures_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_pictures->ad_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ad_pictures->picture_link->Visible) { // picture_link ?>
		<td data-name="picture_link">
<span id="el$rowindex$_ad_pictures_picture_link" class="form-group ad_pictures_picture_link">
<div id="fd_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link">
<span title="<?php echo $ad_pictures->picture_link->FldTitle() ? $ad_pictures->picture_link->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($ad_pictures->picture_link->ReadOnly || $ad_pictures->picture_link->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="ad_pictures" data-field="x_picture_link" name="x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id="x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link"<?php echo $ad_pictures->picture_link->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fn_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $ad_pictures->picture_link->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fa_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="0">
<input type="hidden" name="fs_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fs_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="255">
<input type="hidden" name="fx_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fx_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $ad_pictures->picture_link->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id= "fm_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $ad_pictures->picture_link->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="ad_pictures" data-field="x_picture_link" name="o<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" id="o<?php echo $ad_pictures_grid->RowIndex ?>_picture_link" value="<?php echo ew_HtmlEncode($ad_pictures->picture_link->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ad_pictures_grid->ListOptions->Render("body", "right", $ad_pictures_grid->RowCnt);
?>
<script type="text/javascript">
fad_picturesgrid.UpdateOpts(<?php echo $ad_pictures_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($ad_pictures->CurrentMode == "add" || $ad_pictures->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $ad_pictures_grid->FormKeyCountName ?>" id="<?php echo $ad_pictures_grid->FormKeyCountName ?>" value="<?php echo $ad_pictures_grid->KeyCount ?>">
<?php echo $ad_pictures_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ad_pictures->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $ad_pictures_grid->FormKeyCountName ?>" id="<?php echo $ad_pictures_grid->FormKeyCountName ?>" value="<?php echo $ad_pictures_grid->KeyCount ?>">
<?php echo $ad_pictures_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ad_pictures->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fad_picturesgrid">
</div>
<?php

// Close recordset
if ($ad_pictures_grid->Recordset)
	$ad_pictures_grid->Recordset->Close();
?>
<?php if ($ad_pictures_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($ad_pictures_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($ad_pictures_grid->TotalRecs == 0 && $ad_pictures->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($ad_pictures_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($ad_pictures->Export == "") { ?>
<script type="text/javascript">
fad_picturesgrid.Init();
</script>
<?php } ?>
<?php
$ad_pictures_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$ad_pictures_grid->Page_Terminate();
?>
