<?php include_once "adm_usersinfo.php" ?>
<?php

// Create page object
if (!isset($classified_pictures_grid)) $classified_pictures_grid = new cclassified_pictures_grid();

// Page init
$classified_pictures_grid->Page_Init();

// Page main
$classified_pictures_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$classified_pictures_grid->Page_Render();
?>
<?php if ($classified_pictures->Export == "") { ?>
<script type="text/javascript">

// Form object
var fclassified_picturesgrid = new ew_Form("fclassified_picturesgrid", "grid");
fclassified_picturesgrid.FormKeyCountName = '<?php echo $classified_pictures_grid->FormKeyCountName ?>';

// Validate form
fclassified_picturesgrid.Validate = function() {
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
			felm = this.GetElements("x" + infix + "_picture_link");
			elm = this.GetElements("fn_x" + infix + "_picture_link");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_pictures->picture_link->FldCaption(), $classified_pictures->picture_link->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_classified_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_pictures->classified_id->FldCaption(), $classified_pictures->classified_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_classified_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($classified_pictures->classified_id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fclassified_picturesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "picture_link", false)) return false;
	if (ew_ValueChanged(fobj, infix, "classified_id", false)) return false;
	return true;
}

// Form_CustomValidate event
fclassified_picturesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclassified_picturesgrid.ValidateRequired = true;
<?php } else { ?>
fclassified_picturesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclassified_picturesgrid.Lists["x_classified_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($classified_pictures->CurrentAction == "gridadd") {
	if ($classified_pictures->CurrentMode == "copy") {
		$bSelectLimit = $classified_pictures_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$classified_pictures_grid->TotalRecs = $classified_pictures->SelectRecordCount();
			$classified_pictures_grid->Recordset = $classified_pictures_grid->LoadRecordset($classified_pictures_grid->StartRec-1, $classified_pictures_grid->DisplayRecs);
		} else {
			if ($classified_pictures_grid->Recordset = $classified_pictures_grid->LoadRecordset())
				$classified_pictures_grid->TotalRecs = $classified_pictures_grid->Recordset->RecordCount();
		}
		$classified_pictures_grid->StartRec = 1;
		$classified_pictures_grid->DisplayRecs = $classified_pictures_grid->TotalRecs;
	} else {
		$classified_pictures->CurrentFilter = "0=1";
		$classified_pictures_grid->StartRec = 1;
		$classified_pictures_grid->DisplayRecs = $classified_pictures->GridAddRowCount;
	}
	$classified_pictures_grid->TotalRecs = $classified_pictures_grid->DisplayRecs;
	$classified_pictures_grid->StopRec = $classified_pictures_grid->DisplayRecs;
} else {
	$bSelectLimit = $classified_pictures_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($classified_pictures_grid->TotalRecs <= 0)
			$classified_pictures_grid->TotalRecs = $classified_pictures->SelectRecordCount();
	} else {
		if (!$classified_pictures_grid->Recordset && ($classified_pictures_grid->Recordset = $classified_pictures_grid->LoadRecordset()))
			$classified_pictures_grid->TotalRecs = $classified_pictures_grid->Recordset->RecordCount();
	}
	$classified_pictures_grid->StartRec = 1;
	$classified_pictures_grid->DisplayRecs = $classified_pictures_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$classified_pictures_grid->Recordset = $classified_pictures_grid->LoadRecordset($classified_pictures_grid->StartRec-1, $classified_pictures_grid->DisplayRecs);

	// Set no record found message
	if ($classified_pictures->CurrentAction == "" && $classified_pictures_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$classified_pictures_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($classified_pictures_grid->SearchWhere == "0=101")
			$classified_pictures_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$classified_pictures_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$classified_pictures_grid->RenderOtherOptions();
?>
<?php $classified_pictures_grid->ShowPageHeader(); ?>
<?php
$classified_pictures_grid->ShowMessage();
?>
<?php if ($classified_pictures_grid->TotalRecs > 0 || $classified_pictures->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fclassified_picturesgrid" class="ewForm form-inline">
<div id="gmp_classified_pictures" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_classified_picturesgrid" class="table ewTable">
<?php echo $classified_pictures->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$classified_pictures_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$classified_pictures_grid->RenderListOptions();

// Render list options (header, left)
$classified_pictures_grid->ListOptions->Render("header", "left");
?>
<?php if ($classified_pictures->picture_link->Visible) { // picture_link ?>
	<?php if ($classified_pictures->SortUrl($classified_pictures->picture_link) == "") { ?>
		<th data-name="picture_link"><div id="elh_classified_pictures_picture_link" class="classified_pictures_picture_link"><div class="ewTableHeaderCaption"><?php echo $classified_pictures->picture_link->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="picture_link"><div><div id="elh_classified_pictures_picture_link" class="classified_pictures_picture_link">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_pictures->picture_link->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_pictures->picture_link->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_pictures->picture_link->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_pictures->classified_id->Visible) { // classified_id ?>
	<?php if ($classified_pictures->SortUrl($classified_pictures->classified_id) == "") { ?>
		<th data-name="classified_id"><div id="elh_classified_pictures_classified_id" class="classified_pictures_classified_id"><div class="ewTableHeaderCaption"><?php echo $classified_pictures->classified_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="classified_id"><div><div id="elh_classified_pictures_classified_id" class="classified_pictures_classified_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_pictures->classified_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_pictures->classified_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_pictures->classified_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$classified_pictures_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$classified_pictures_grid->StartRec = 1;
$classified_pictures_grid->StopRec = $classified_pictures_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($classified_pictures_grid->FormKeyCountName) && ($classified_pictures->CurrentAction == "gridadd" || $classified_pictures->CurrentAction == "gridedit" || $classified_pictures->CurrentAction == "F")) {
		$classified_pictures_grid->KeyCount = $objForm->GetValue($classified_pictures_grid->FormKeyCountName);
		$classified_pictures_grid->StopRec = $classified_pictures_grid->StartRec + $classified_pictures_grid->KeyCount - 1;
	}
}
$classified_pictures_grid->RecCnt = $classified_pictures_grid->StartRec - 1;
if ($classified_pictures_grid->Recordset && !$classified_pictures_grid->Recordset->EOF) {
	$classified_pictures_grid->Recordset->MoveFirst();
	$bSelectLimit = $classified_pictures_grid->UseSelectLimit;
	if (!$bSelectLimit && $classified_pictures_grid->StartRec > 1)
		$classified_pictures_grid->Recordset->Move($classified_pictures_grid->StartRec - 1);
} elseif (!$classified_pictures->AllowAddDeleteRow && $classified_pictures_grid->StopRec == 0) {
	$classified_pictures_grid->StopRec = $classified_pictures->GridAddRowCount;
}

// Initialize aggregate
$classified_pictures->RowType = EW_ROWTYPE_AGGREGATEINIT;
$classified_pictures->ResetAttrs();
$classified_pictures_grid->RenderRow();
if ($classified_pictures->CurrentAction == "gridadd")
	$classified_pictures_grid->RowIndex = 0;
if ($classified_pictures->CurrentAction == "gridedit")
	$classified_pictures_grid->RowIndex = 0;
while ($classified_pictures_grid->RecCnt < $classified_pictures_grid->StopRec) {
	$classified_pictures_grid->RecCnt++;
	if (intval($classified_pictures_grid->RecCnt) >= intval($classified_pictures_grid->StartRec)) {
		$classified_pictures_grid->RowCnt++;
		if ($classified_pictures->CurrentAction == "gridadd" || $classified_pictures->CurrentAction == "gridedit" || $classified_pictures->CurrentAction == "F") {
			$classified_pictures_grid->RowIndex++;
			$objForm->Index = $classified_pictures_grid->RowIndex;
			if ($objForm->HasValue($classified_pictures_grid->FormActionName))
				$classified_pictures_grid->RowAction = strval($objForm->GetValue($classified_pictures_grid->FormActionName));
			elseif ($classified_pictures->CurrentAction == "gridadd")
				$classified_pictures_grid->RowAction = "insert";
			else
				$classified_pictures_grid->RowAction = "";
		}

		// Set up key count
		$classified_pictures_grid->KeyCount = $classified_pictures_grid->RowIndex;

		// Init row class and style
		$classified_pictures->ResetAttrs();
		$classified_pictures->CssClass = "";
		if ($classified_pictures->CurrentAction == "gridadd") {
			if ($classified_pictures->CurrentMode == "copy") {
				$classified_pictures_grid->LoadRowValues($classified_pictures_grid->Recordset); // Load row values
				$classified_pictures_grid->SetRecordKey($classified_pictures_grid->RowOldKey, $classified_pictures_grid->Recordset); // Set old record key
			} else {
				$classified_pictures_grid->LoadDefaultValues(); // Load default values
				$classified_pictures_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$classified_pictures_grid->LoadRowValues($classified_pictures_grid->Recordset); // Load row values
		}
		$classified_pictures->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($classified_pictures->CurrentAction == "gridadd") // Grid add
			$classified_pictures->RowType = EW_ROWTYPE_ADD; // Render add
		if ($classified_pictures->CurrentAction == "gridadd" && $classified_pictures->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$classified_pictures_grid->RestoreCurrentRowFormValues($classified_pictures_grid->RowIndex); // Restore form values
		if ($classified_pictures->CurrentAction == "gridedit") { // Grid edit
			if ($classified_pictures->EventCancelled) {
				$classified_pictures_grid->RestoreCurrentRowFormValues($classified_pictures_grid->RowIndex); // Restore form values
			}
			if ($classified_pictures_grid->RowAction == "insert")
				$classified_pictures->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$classified_pictures->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($classified_pictures->CurrentAction == "gridedit" && ($classified_pictures->RowType == EW_ROWTYPE_EDIT || $classified_pictures->RowType == EW_ROWTYPE_ADD) && $classified_pictures->EventCancelled) // Update failed
			$classified_pictures_grid->RestoreCurrentRowFormValues($classified_pictures_grid->RowIndex); // Restore form values
		if ($classified_pictures->RowType == EW_ROWTYPE_EDIT) // Edit row
			$classified_pictures_grid->EditRowCnt++;
		if ($classified_pictures->CurrentAction == "F") // Confirm row
			$classified_pictures_grid->RestoreCurrentRowFormValues($classified_pictures_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$classified_pictures->RowAttrs = array_merge($classified_pictures->RowAttrs, array('data-rowindex'=>$classified_pictures_grid->RowCnt, 'id'=>'r' . $classified_pictures_grid->RowCnt . '_classified_pictures', 'data-rowtype'=>$classified_pictures->RowType));

		// Render row
		$classified_pictures_grid->RenderRow();

		// Render list options
		$classified_pictures_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($classified_pictures_grid->RowAction <> "delete" && $classified_pictures_grid->RowAction <> "insertdelete" && !($classified_pictures_grid->RowAction == "insert" && $classified_pictures->CurrentAction == "F" && $classified_pictures_grid->EmptyRow())) {
?>
	<tr<?php echo $classified_pictures->RowAttributes() ?>>
<?php

// Render list options (body, left)
$classified_pictures_grid->ListOptions->Render("body", "left", $classified_pictures_grid->RowCnt);
?>
	<?php if ($classified_pictures->picture_link->Visible) { // picture_link ?>
		<td data-name="picture_link"<?php echo $classified_pictures->picture_link->CellAttributes() ?>>
<?php if ($classified_pictures_grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_classified_pictures_picture_link" class="form-group classified_pictures_picture_link">
<div id="fd_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link">
<span title="<?php echo $classified_pictures->picture_link->FldTitle() ? $classified_pictures->picture_link->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($classified_pictures->picture_link->ReadOnly || $classified_pictures->picture_link->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="classified_pictures" data-field="x_picture_link" name="x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id="x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link"<?php echo $classified_pictures->picture_link->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fn_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $classified_pictures->picture_link->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fa_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="0">
<input type="hidden" name="fs_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fs_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="255">
<input type="hidden" name="fx_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fx_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $classified_pictures->picture_link->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fm_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $classified_pictures->picture_link->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="classified_pictures" data-field="x_picture_link" name="o<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id="o<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo ew_HtmlEncode($classified_pictures->picture_link->OldValue) ?>">
<?php } elseif ($classified_pictures->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $classified_pictures_grid->RowCnt ?>_classified_pictures_picture_link" class="classified_pictures_picture_link">
<span<?php echo $classified_pictures->picture_link->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($classified_pictures->picture_link, $classified_pictures->picture_link->ListViewValue()) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $classified_pictures_grid->RowCnt ?>_classified_pictures_picture_link" class="form-group classified_pictures_picture_link">
<div id="fd_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link">
<span title="<?php echo $classified_pictures->picture_link->FldTitle() ? $classified_pictures->picture_link->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($classified_pictures->picture_link->ReadOnly || $classified_pictures->picture_link->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="classified_pictures" data-field="x_picture_link" name="x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id="x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link"<?php echo $classified_pictures->picture_link->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fn_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $classified_pictures->picture_link->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fa_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fa_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fs_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="255">
<input type="hidden" name="fx_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fx_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $classified_pictures->picture_link->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fm_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $classified_pictures->picture_link->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<a id="<?php echo $classified_pictures_grid->PageObjName . "_row_" . $classified_pictures_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($classified_pictures->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="classified_pictures" data-field="x_ID" name="x<?php echo $classified_pictures_grid->RowIndex ?>_ID" id="x<?php echo $classified_pictures_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_pictures->ID->CurrentValue) ?>">
<input type="hidden" data-table="classified_pictures" data-field="x_ID" name="o<?php echo $classified_pictures_grid->RowIndex ?>_ID" id="o<?php echo $classified_pictures_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_pictures->ID->OldValue) ?>">
<?php } ?>
<?php if ($classified_pictures->RowType == EW_ROWTYPE_EDIT || $classified_pictures->CurrentMode == "edit") { ?>
<input type="hidden" data-table="classified_pictures" data-field="x_ID" name="x<?php echo $classified_pictures_grid->RowIndex ?>_ID" id="x<?php echo $classified_pictures_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_pictures->ID->CurrentValue) ?>">
<?php } ?>
	<?php if ($classified_pictures->classified_id->Visible) { // classified_id ?>
		<td data-name="classified_id"<?php echo $classified_pictures->classified_id->CellAttributes() ?>>
<?php if ($classified_pictures->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($classified_pictures->classified_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $classified_pictures_grid->RowCnt ?>_classified_pictures_classified_id" class="form-group classified_pictures_classified_id">
<span<?php echo $classified_pictures->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_pictures->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" name="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $classified_pictures_grid->RowCnt ?>_classified_pictures_classified_id" class="form-group classified_pictures_classified_id">
<?php
$wrkonchange = trim(" " . @$classified_pictures->classified_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_pictures->classified_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_pictures_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="sv_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo $classified_pictures->classified_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_pictures->classified_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_pictures->classified_id->getPlaceHolder()) ?>"<?php echo $classified_pictures->classified_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_pictures" data-field="x_classified_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_pictures->classified_id->DisplayValueSeparator) ? json_encode($classified_pictures->classified_id->DisplayValueSeparator) : $classified_pictures->classified_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_pictures->Lookup_Selecting($classified_pictures->classified_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="q_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_picturesgrid.CreateAutoSuggest({"id":"x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id","forceSelect":false});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="classified_pictures" data-field="x_classified_id" name="o<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="o<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->OldValue) ?>">
<?php } ?>
<?php if ($classified_pictures->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($classified_pictures->classified_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $classified_pictures_grid->RowCnt ?>_classified_pictures_classified_id" class="form-group classified_pictures_classified_id">
<span<?php echo $classified_pictures->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_pictures->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" name="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $classified_pictures_grid->RowCnt ?>_classified_pictures_classified_id" class="form-group classified_pictures_classified_id">
<?php
$wrkonchange = trim(" " . @$classified_pictures->classified_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_pictures->classified_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_pictures_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="sv_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo $classified_pictures->classified_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_pictures->classified_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_pictures->classified_id->getPlaceHolder()) ?>"<?php echo $classified_pictures->classified_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_pictures" data-field="x_classified_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_pictures->classified_id->DisplayValueSeparator) ? json_encode($classified_pictures->classified_id->DisplayValueSeparator) : $classified_pictures->classified_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_pictures->Lookup_Selecting($classified_pictures->classified_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="q_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_picturesgrid.CreateAutoSuggest({"id":"x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($classified_pictures->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $classified_pictures_grid->RowCnt ?>_classified_pictures_classified_id" class="classified_pictures_classified_id">
<span<?php echo $classified_pictures->classified_id->ViewAttributes() ?>>
<?php echo $classified_pictures->classified_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="classified_pictures" data-field="x_classified_id" name="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->FormValue) ?>">
<input type="hidden" data-table="classified_pictures" data-field="x_classified_id" name="o<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="o<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$classified_pictures_grid->ListOptions->Render("body", "right", $classified_pictures_grid->RowCnt);
?>
	</tr>
<?php if ($classified_pictures->RowType == EW_ROWTYPE_ADD || $classified_pictures->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fclassified_picturesgrid.UpdateOpts(<?php echo $classified_pictures_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($classified_pictures->CurrentAction <> "gridadd" || $classified_pictures->CurrentMode == "copy")
		if (!$classified_pictures_grid->Recordset->EOF) $classified_pictures_grid->Recordset->MoveNext();
}
?>
<?php
	if ($classified_pictures->CurrentMode == "add" || $classified_pictures->CurrentMode == "copy" || $classified_pictures->CurrentMode == "edit") {
		$classified_pictures_grid->RowIndex = '$rowindex$';
		$classified_pictures_grid->LoadDefaultValues();

		// Set row properties
		$classified_pictures->ResetAttrs();
		$classified_pictures->RowAttrs = array_merge($classified_pictures->RowAttrs, array('data-rowindex'=>$classified_pictures_grid->RowIndex, 'id'=>'r0_classified_pictures', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($classified_pictures->RowAttrs["class"], "ewTemplate");
		$classified_pictures->RowType = EW_ROWTYPE_ADD;

		// Render row
		$classified_pictures_grid->RenderRow();

		// Render list options
		$classified_pictures_grid->RenderListOptions();
		$classified_pictures_grid->StartRowCnt = 0;
?>
	<tr<?php echo $classified_pictures->RowAttributes() ?>>
<?php

// Render list options (body, left)
$classified_pictures_grid->ListOptions->Render("body", "left", $classified_pictures_grid->RowIndex);
?>
	<?php if ($classified_pictures->picture_link->Visible) { // picture_link ?>
		<td data-name="picture_link">
<span id="el$rowindex$_classified_pictures_picture_link" class="form-group classified_pictures_picture_link">
<div id="fd_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link">
<span title="<?php echo $classified_pictures->picture_link->FldTitle() ? $classified_pictures->picture_link->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($classified_pictures->picture_link->ReadOnly || $classified_pictures->picture_link->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="classified_pictures" data-field="x_picture_link" name="x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id="x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link"<?php echo $classified_pictures->picture_link->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fn_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $classified_pictures->picture_link->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fa_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="0">
<input type="hidden" name="fs_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fs_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="255">
<input type="hidden" name="fx_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fx_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $classified_pictures->picture_link->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id= "fm_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo $classified_pictures->picture_link->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="classified_pictures" data-field="x_picture_link" name="o<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" id="o<?php echo $classified_pictures_grid->RowIndex ?>_picture_link" value="<?php echo ew_HtmlEncode($classified_pictures->picture_link->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($classified_pictures->classified_id->Visible) { // classified_id ?>
		<td data-name="classified_id">
<?php if ($classified_pictures->CurrentAction <> "F") { ?>
<?php if ($classified_pictures->classified_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_classified_pictures_classified_id" class="form-group classified_pictures_classified_id">
<span<?php echo $classified_pictures->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_pictures->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" name="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_classified_pictures_classified_id" class="form-group classified_pictures_classified_id">
<?php
$wrkonchange = trim(" " . @$classified_pictures->classified_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_pictures->classified_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_pictures_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="sv_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo $classified_pictures->classified_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_pictures->classified_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_pictures->classified_id->getPlaceHolder()) ?>"<?php echo $classified_pictures->classified_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_pictures" data-field="x_classified_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_pictures->classified_id->DisplayValueSeparator) ? json_encode($classified_pictures->classified_id->DisplayValueSeparator) : $classified_pictures->classified_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_pictures->Lookup_Selecting($classified_pictures->classified_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="q_x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_picturesgrid.CreateAutoSuggest({"id":"x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_classified_pictures_classified_id" class="form-group classified_pictures_classified_id">
<span<?php echo $classified_pictures->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_pictures->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="classified_pictures" data-field="x_classified_id" name="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="classified_pictures" data-field="x_classified_id" name="o<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" id="o<?php echo $classified_pictures_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_pictures->classified_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$classified_pictures_grid->ListOptions->Render("body", "right", $classified_pictures_grid->RowCnt);
?>
<script type="text/javascript">
fclassified_picturesgrid.UpdateOpts(<?php echo $classified_pictures_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($classified_pictures->CurrentMode == "add" || $classified_pictures->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $classified_pictures_grid->FormKeyCountName ?>" id="<?php echo $classified_pictures_grid->FormKeyCountName ?>" value="<?php echo $classified_pictures_grid->KeyCount ?>">
<?php echo $classified_pictures_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($classified_pictures->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $classified_pictures_grid->FormKeyCountName ?>" id="<?php echo $classified_pictures_grid->FormKeyCountName ?>" value="<?php echo $classified_pictures_grid->KeyCount ?>">
<?php echo $classified_pictures_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($classified_pictures->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fclassified_picturesgrid">
</div>
<?php

// Close recordset
if ($classified_pictures_grid->Recordset)
	$classified_pictures_grid->Recordset->Close();
?>
<?php if ($classified_pictures_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($classified_pictures_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($classified_pictures_grid->TotalRecs == 0 && $classified_pictures->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($classified_pictures_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($classified_pictures->Export == "") { ?>
<script type="text/javascript">
fclassified_picturesgrid.Init();
</script>
<?php } ?>
<?php
$classified_pictures_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$classified_pictures_grid->Page_Terminate();
?>
