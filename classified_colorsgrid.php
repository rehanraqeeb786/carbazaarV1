<?php include_once "adm_usersinfo.php" ?>
<?php

// Create page object
if (!isset($classified_colors_grid)) $classified_colors_grid = new cclassified_colors_grid();

// Page init
$classified_colors_grid->Page_Init();

// Page main
$classified_colors_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$classified_colors_grid->Page_Render();
?>
<?php if ($classified_colors->Export == "") { ?>
<script type="text/javascript">

// Form object
var fclassified_colorsgrid = new ew_Form("fclassified_colorsgrid", "grid");
fclassified_colorsgrid.FormKeyCountName = '<?php echo $classified_colors_grid->FormKeyCountName ?>';

// Validate form
fclassified_colorsgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_color_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_colors->color_id->FldCaption(), $classified_colors->color_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_color_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($classified_colors->color_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_classfied_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_colors->classfied_id->FldCaption(), $classified_colors->classfied_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_classfied_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($classified_colors->classfied_id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fclassified_colorsgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "color_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "classfied_id", false)) return false;
	return true;
}

// Form_CustomValidate event
fclassified_colorsgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclassified_colorsgrid.ValidateRequired = true;
<?php } else { ?>
fclassified_colorsgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclassified_colorsgrid.Lists["x_color_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_colorsgrid.Lists["x_classfied_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($classified_colors->CurrentAction == "gridadd") {
	if ($classified_colors->CurrentMode == "copy") {
		$bSelectLimit = $classified_colors_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$classified_colors_grid->TotalRecs = $classified_colors->SelectRecordCount();
			$classified_colors_grid->Recordset = $classified_colors_grid->LoadRecordset($classified_colors_grid->StartRec-1, $classified_colors_grid->DisplayRecs);
		} else {
			if ($classified_colors_grid->Recordset = $classified_colors_grid->LoadRecordset())
				$classified_colors_grid->TotalRecs = $classified_colors_grid->Recordset->RecordCount();
		}
		$classified_colors_grid->StartRec = 1;
		$classified_colors_grid->DisplayRecs = $classified_colors_grid->TotalRecs;
	} else {
		$classified_colors->CurrentFilter = "0=1";
		$classified_colors_grid->StartRec = 1;
		$classified_colors_grid->DisplayRecs = $classified_colors->GridAddRowCount;
	}
	$classified_colors_grid->TotalRecs = $classified_colors_grid->DisplayRecs;
	$classified_colors_grid->StopRec = $classified_colors_grid->DisplayRecs;
} else {
	$bSelectLimit = $classified_colors_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($classified_colors_grid->TotalRecs <= 0)
			$classified_colors_grid->TotalRecs = $classified_colors->SelectRecordCount();
	} else {
		if (!$classified_colors_grid->Recordset && ($classified_colors_grid->Recordset = $classified_colors_grid->LoadRecordset()))
			$classified_colors_grid->TotalRecs = $classified_colors_grid->Recordset->RecordCount();
	}
	$classified_colors_grid->StartRec = 1;
	$classified_colors_grid->DisplayRecs = $classified_colors_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$classified_colors_grid->Recordset = $classified_colors_grid->LoadRecordset($classified_colors_grid->StartRec-1, $classified_colors_grid->DisplayRecs);

	// Set no record found message
	if ($classified_colors->CurrentAction == "" && $classified_colors_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$classified_colors_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($classified_colors_grid->SearchWhere == "0=101")
			$classified_colors_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$classified_colors_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$classified_colors_grid->RenderOtherOptions();
?>
<?php $classified_colors_grid->ShowPageHeader(); ?>
<?php
$classified_colors_grid->ShowMessage();
?>
<?php if ($classified_colors_grid->TotalRecs > 0 || $classified_colors->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fclassified_colorsgrid" class="ewForm form-inline">
<div id="gmp_classified_colors" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_classified_colorsgrid" class="table ewTable">
<?php echo $classified_colors->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$classified_colors_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$classified_colors_grid->RenderListOptions();

// Render list options (header, left)
$classified_colors_grid->ListOptions->Render("header", "left");
?>
<?php if ($classified_colors->color_id->Visible) { // color_id ?>
	<?php if ($classified_colors->SortUrl($classified_colors->color_id) == "") { ?>
		<th data-name="color_id"><div id="elh_classified_colors_color_id" class="classified_colors_color_id"><div class="ewTableHeaderCaption"><?php echo $classified_colors->color_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="color_id"><div><div id="elh_classified_colors_color_id" class="classified_colors_color_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_colors->color_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_colors->color_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_colors->color_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_colors->classfied_id->Visible) { // classfied_id ?>
	<?php if ($classified_colors->SortUrl($classified_colors->classfied_id) == "") { ?>
		<th data-name="classfied_id"><div id="elh_classified_colors_classfied_id" class="classified_colors_classfied_id"><div class="ewTableHeaderCaption"><?php echo $classified_colors->classfied_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="classfied_id"><div><div id="elh_classified_colors_classfied_id" class="classified_colors_classfied_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_colors->classfied_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_colors->classfied_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_colors->classfied_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$classified_colors_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$classified_colors_grid->StartRec = 1;
$classified_colors_grid->StopRec = $classified_colors_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($classified_colors_grid->FormKeyCountName) && ($classified_colors->CurrentAction == "gridadd" || $classified_colors->CurrentAction == "gridedit" || $classified_colors->CurrentAction == "F")) {
		$classified_colors_grid->KeyCount = $objForm->GetValue($classified_colors_grid->FormKeyCountName);
		$classified_colors_grid->StopRec = $classified_colors_grid->StartRec + $classified_colors_grid->KeyCount - 1;
	}
}
$classified_colors_grid->RecCnt = $classified_colors_grid->StartRec - 1;
if ($classified_colors_grid->Recordset && !$classified_colors_grid->Recordset->EOF) {
	$classified_colors_grid->Recordset->MoveFirst();
	$bSelectLimit = $classified_colors_grid->UseSelectLimit;
	if (!$bSelectLimit && $classified_colors_grid->StartRec > 1)
		$classified_colors_grid->Recordset->Move($classified_colors_grid->StartRec - 1);
} elseif (!$classified_colors->AllowAddDeleteRow && $classified_colors_grid->StopRec == 0) {
	$classified_colors_grid->StopRec = $classified_colors->GridAddRowCount;
}

// Initialize aggregate
$classified_colors->RowType = EW_ROWTYPE_AGGREGATEINIT;
$classified_colors->ResetAttrs();
$classified_colors_grid->RenderRow();
if ($classified_colors->CurrentAction == "gridadd")
	$classified_colors_grid->RowIndex = 0;
if ($classified_colors->CurrentAction == "gridedit")
	$classified_colors_grid->RowIndex = 0;
while ($classified_colors_grid->RecCnt < $classified_colors_grid->StopRec) {
	$classified_colors_grid->RecCnt++;
	if (intval($classified_colors_grid->RecCnt) >= intval($classified_colors_grid->StartRec)) {
		$classified_colors_grid->RowCnt++;
		if ($classified_colors->CurrentAction == "gridadd" || $classified_colors->CurrentAction == "gridedit" || $classified_colors->CurrentAction == "F") {
			$classified_colors_grid->RowIndex++;
			$objForm->Index = $classified_colors_grid->RowIndex;
			if ($objForm->HasValue($classified_colors_grid->FormActionName))
				$classified_colors_grid->RowAction = strval($objForm->GetValue($classified_colors_grid->FormActionName));
			elseif ($classified_colors->CurrentAction == "gridadd")
				$classified_colors_grid->RowAction = "insert";
			else
				$classified_colors_grid->RowAction = "";
		}

		// Set up key count
		$classified_colors_grid->KeyCount = $classified_colors_grid->RowIndex;

		// Init row class and style
		$classified_colors->ResetAttrs();
		$classified_colors->CssClass = "";
		if ($classified_colors->CurrentAction == "gridadd") {
			if ($classified_colors->CurrentMode == "copy") {
				$classified_colors_grid->LoadRowValues($classified_colors_grid->Recordset); // Load row values
				$classified_colors_grid->SetRecordKey($classified_colors_grid->RowOldKey, $classified_colors_grid->Recordset); // Set old record key
			} else {
				$classified_colors_grid->LoadDefaultValues(); // Load default values
				$classified_colors_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$classified_colors_grid->LoadRowValues($classified_colors_grid->Recordset); // Load row values
		}
		$classified_colors->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($classified_colors->CurrentAction == "gridadd") // Grid add
			$classified_colors->RowType = EW_ROWTYPE_ADD; // Render add
		if ($classified_colors->CurrentAction == "gridadd" && $classified_colors->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$classified_colors_grid->RestoreCurrentRowFormValues($classified_colors_grid->RowIndex); // Restore form values
		if ($classified_colors->CurrentAction == "gridedit") { // Grid edit
			if ($classified_colors->EventCancelled) {
				$classified_colors_grid->RestoreCurrentRowFormValues($classified_colors_grid->RowIndex); // Restore form values
			}
			if ($classified_colors_grid->RowAction == "insert")
				$classified_colors->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$classified_colors->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($classified_colors->CurrentAction == "gridedit" && ($classified_colors->RowType == EW_ROWTYPE_EDIT || $classified_colors->RowType == EW_ROWTYPE_ADD) && $classified_colors->EventCancelled) // Update failed
			$classified_colors_grid->RestoreCurrentRowFormValues($classified_colors_grid->RowIndex); // Restore form values
		if ($classified_colors->RowType == EW_ROWTYPE_EDIT) // Edit row
			$classified_colors_grid->EditRowCnt++;
		if ($classified_colors->CurrentAction == "F") // Confirm row
			$classified_colors_grid->RestoreCurrentRowFormValues($classified_colors_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$classified_colors->RowAttrs = array_merge($classified_colors->RowAttrs, array('data-rowindex'=>$classified_colors_grid->RowCnt, 'id'=>'r' . $classified_colors_grid->RowCnt . '_classified_colors', 'data-rowtype'=>$classified_colors->RowType));

		// Render row
		$classified_colors_grid->RenderRow();

		// Render list options
		$classified_colors_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($classified_colors_grid->RowAction <> "delete" && $classified_colors_grid->RowAction <> "insertdelete" && !($classified_colors_grid->RowAction == "insert" && $classified_colors->CurrentAction == "F" && $classified_colors_grid->EmptyRow())) {
?>
	<tr<?php echo $classified_colors->RowAttributes() ?>>
<?php

// Render list options (body, left)
$classified_colors_grid->ListOptions->Render("body", "left", $classified_colors_grid->RowCnt);
?>
	<?php if ($classified_colors->color_id->Visible) { // color_id ?>
		<td data-name="color_id"<?php echo $classified_colors->color_id->CellAttributes() ?>>
<?php if ($classified_colors->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $classified_colors_grid->RowCnt ?>_classified_colors_color_id" class="form-group classified_colors_color_id">
<?php
$wrkonchange = trim(" " . @$classified_colors->color_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_colors->color_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_colors_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="sv_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo $classified_colors->color_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_colors->color_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_colors->color_id->getPlaceHolder()) ?>"<?php echo $classified_colors->color_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_color_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_colors->color_id->DisplayValueSeparator) ? json_encode($classified_colors->color_id->DisplayValueSeparator) : $classified_colors->color_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo ew_HtmlEncode($classified_colors->color_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `cfg_body_colors`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_colors->Lookup_Selecting($classified_colors->color_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="q_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_colorsgrid.CreateAutoSuggest({"id":"x<?php echo $classified_colors_grid->RowIndex ?>_color_id","forceSelect":false});
</script>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_color_id" name="o<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="o<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo ew_HtmlEncode($classified_colors->color_id->OldValue) ?>">
<?php } ?>
<?php if ($classified_colors->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $classified_colors_grid->RowCnt ?>_classified_colors_color_id" class="form-group classified_colors_color_id">
<?php
$wrkonchange = trim(" " . @$classified_colors->color_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_colors->color_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_colors_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="sv_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo $classified_colors->color_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_colors->color_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_colors->color_id->getPlaceHolder()) ?>"<?php echo $classified_colors->color_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_color_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_colors->color_id->DisplayValueSeparator) ? json_encode($classified_colors->color_id->DisplayValueSeparator) : $classified_colors->color_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo ew_HtmlEncode($classified_colors->color_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `cfg_body_colors`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_colors->Lookup_Selecting($classified_colors->color_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="q_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_colorsgrid.CreateAutoSuggest({"id":"x<?php echo $classified_colors_grid->RowIndex ?>_color_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php if ($classified_colors->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $classified_colors_grid->RowCnt ?>_classified_colors_color_id" class="classified_colors_color_id">
<span<?php echo $classified_colors->color_id->ViewAttributes() ?>>
<?php echo $classified_colors->color_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_color_id" name="x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo ew_HtmlEncode($classified_colors->color_id->FormValue) ?>">
<input type="hidden" data-table="classified_colors" data-field="x_color_id" name="o<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="o<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo ew_HtmlEncode($classified_colors->color_id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $classified_colors_grid->PageObjName . "_row_" . $classified_colors_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($classified_colors->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="classified_colors" data-field="x_ID" name="x<?php echo $classified_colors_grid->RowIndex ?>_ID" id="x<?php echo $classified_colors_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_colors->ID->CurrentValue) ?>">
<input type="hidden" data-table="classified_colors" data-field="x_ID" name="o<?php echo $classified_colors_grid->RowIndex ?>_ID" id="o<?php echo $classified_colors_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_colors->ID->OldValue) ?>">
<?php } ?>
<?php if ($classified_colors->RowType == EW_ROWTYPE_EDIT || $classified_colors->CurrentMode == "edit") { ?>
<input type="hidden" data-table="classified_colors" data-field="x_ID" name="x<?php echo $classified_colors_grid->RowIndex ?>_ID" id="x<?php echo $classified_colors_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_colors->ID->CurrentValue) ?>">
<?php } ?>
	<?php if ($classified_colors->classfied_id->Visible) { // classfied_id ?>
		<td data-name="classfied_id"<?php echo $classified_colors->classfied_id->CellAttributes() ?>>
<?php if ($classified_colors->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($classified_colors->classfied_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $classified_colors_grid->RowCnt ?>_classified_colors_classfied_id" class="form-group classified_colors_classfied_id">
<span<?php echo $classified_colors->classfied_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_colors->classfied_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" name="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $classified_colors_grid->RowCnt ?>_classified_colors_classfied_id" class="form-group classified_colors_classfied_id">
<?php
$wrkonchange = trim(" " . @$classified_colors->classfied_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_colors->classfied_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_colors_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="sv_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo $classified_colors->classfied_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_colors->classfied_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_colors->classfied_id->getPlaceHolder()) ?>"<?php echo $classified_colors->classfied_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_classfied_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_colors->classfied_id->DisplayValueSeparator) ? json_encode($classified_colors->classfied_id->DisplayValueSeparator) : $classified_colors->classfied_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_colors->Lookup_Selecting($classified_colors->classfied_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="q_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_colorsgrid.CreateAutoSuggest({"id":"x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id","forceSelect":false});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="classified_colors" data-field="x_classfied_id" name="o<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="o<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->OldValue) ?>">
<?php } ?>
<?php if ($classified_colors->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($classified_colors->classfied_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $classified_colors_grid->RowCnt ?>_classified_colors_classfied_id" class="form-group classified_colors_classfied_id">
<span<?php echo $classified_colors->classfied_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_colors->classfied_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" name="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $classified_colors_grid->RowCnt ?>_classified_colors_classfied_id" class="form-group classified_colors_classfied_id">
<?php
$wrkonchange = trim(" " . @$classified_colors->classfied_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_colors->classfied_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_colors_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="sv_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo $classified_colors->classfied_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_colors->classfied_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_colors->classfied_id->getPlaceHolder()) ?>"<?php echo $classified_colors->classfied_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_classfied_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_colors->classfied_id->DisplayValueSeparator) ? json_encode($classified_colors->classfied_id->DisplayValueSeparator) : $classified_colors->classfied_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_colors->Lookup_Selecting($classified_colors->classfied_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="q_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_colorsgrid.CreateAutoSuggest({"id":"x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($classified_colors->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $classified_colors_grid->RowCnt ?>_classified_colors_classfied_id" class="classified_colors_classfied_id">
<span<?php echo $classified_colors->classfied_id->ViewAttributes() ?>>
<?php echo $classified_colors->classfied_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_classfied_id" name="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->FormValue) ?>">
<input type="hidden" data-table="classified_colors" data-field="x_classfied_id" name="o<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="o<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$classified_colors_grid->ListOptions->Render("body", "right", $classified_colors_grid->RowCnt);
?>
	</tr>
<?php if ($classified_colors->RowType == EW_ROWTYPE_ADD || $classified_colors->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fclassified_colorsgrid.UpdateOpts(<?php echo $classified_colors_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($classified_colors->CurrentAction <> "gridadd" || $classified_colors->CurrentMode == "copy")
		if (!$classified_colors_grid->Recordset->EOF) $classified_colors_grid->Recordset->MoveNext();
}
?>
<?php
	if ($classified_colors->CurrentMode == "add" || $classified_colors->CurrentMode == "copy" || $classified_colors->CurrentMode == "edit") {
		$classified_colors_grid->RowIndex = '$rowindex$';
		$classified_colors_grid->LoadDefaultValues();

		// Set row properties
		$classified_colors->ResetAttrs();
		$classified_colors->RowAttrs = array_merge($classified_colors->RowAttrs, array('data-rowindex'=>$classified_colors_grid->RowIndex, 'id'=>'r0_classified_colors', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($classified_colors->RowAttrs["class"], "ewTemplate");
		$classified_colors->RowType = EW_ROWTYPE_ADD;

		// Render row
		$classified_colors_grid->RenderRow();

		// Render list options
		$classified_colors_grid->RenderListOptions();
		$classified_colors_grid->StartRowCnt = 0;
?>
	<tr<?php echo $classified_colors->RowAttributes() ?>>
<?php

// Render list options (body, left)
$classified_colors_grid->ListOptions->Render("body", "left", $classified_colors_grid->RowIndex);
?>
	<?php if ($classified_colors->color_id->Visible) { // color_id ?>
		<td data-name="color_id">
<?php if ($classified_colors->CurrentAction <> "F") { ?>
<span id="el$rowindex$_classified_colors_color_id" class="form-group classified_colors_color_id">
<?php
$wrkonchange = trim(" " . @$classified_colors->color_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_colors->color_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_colors_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="sv_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo $classified_colors->color_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_colors->color_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_colors->color_id->getPlaceHolder()) ?>"<?php echo $classified_colors->color_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_color_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_colors->color_id->DisplayValueSeparator) ? json_encode($classified_colors->color_id->DisplayValueSeparator) : $classified_colors->color_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo ew_HtmlEncode($classified_colors->color_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `cfg_body_colors`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_colors->Lookup_Selecting($classified_colors->color_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="q_x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_colorsgrid.CreateAutoSuggest({"id":"x<?php echo $classified_colors_grid->RowIndex ?>_color_id","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_classified_colors_color_id" class="form-group classified_colors_color_id">
<span<?php echo $classified_colors->color_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_colors->color_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_color_id" name="x<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="x<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo ew_HtmlEncode($classified_colors->color_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="classified_colors" data-field="x_color_id" name="o<?php echo $classified_colors_grid->RowIndex ?>_color_id" id="o<?php echo $classified_colors_grid->RowIndex ?>_color_id" value="<?php echo ew_HtmlEncode($classified_colors->color_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($classified_colors->classfied_id->Visible) { // classfied_id ?>
		<td data-name="classfied_id">
<?php if ($classified_colors->CurrentAction <> "F") { ?>
<?php if ($classified_colors->classfied_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_classified_colors_classfied_id" class="form-group classified_colors_classfied_id">
<span<?php echo $classified_colors->classfied_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_colors->classfied_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" name="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_classified_colors_classfied_id" class="form-group classified_colors_classfied_id">
<?php
$wrkonchange = trim(" " . @$classified_colors->classfied_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_colors->classfied_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_colors_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="sv_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo $classified_colors->classfied_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_colors->classfied_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_colors->classfied_id->getPlaceHolder()) ?>"<?php echo $classified_colors->classfied_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_classfied_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_colors->classfied_id->DisplayValueSeparator) ? json_encode($classified_colors->classfied_id->DisplayValueSeparator) : $classified_colors->classfied_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_colors->Lookup_Selecting($classified_colors->classfied_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="q_x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_colorsgrid.CreateAutoSuggest({"id":"x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_classified_colors_classfied_id" class="form-group classified_colors_classfied_id">
<span<?php echo $classified_colors->classfied_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_colors->classfied_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="classified_colors" data-field="x_classfied_id" name="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="x<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="classified_colors" data-field="x_classfied_id" name="o<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" id="o<?php echo $classified_colors_grid->RowIndex ?>_classfied_id" value="<?php echo ew_HtmlEncode($classified_colors->classfied_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$classified_colors_grid->ListOptions->Render("body", "right", $classified_colors_grid->RowCnt);
?>
<script type="text/javascript">
fclassified_colorsgrid.UpdateOpts(<?php echo $classified_colors_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($classified_colors->CurrentMode == "add" || $classified_colors->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $classified_colors_grid->FormKeyCountName ?>" id="<?php echo $classified_colors_grid->FormKeyCountName ?>" value="<?php echo $classified_colors_grid->KeyCount ?>">
<?php echo $classified_colors_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($classified_colors->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $classified_colors_grid->FormKeyCountName ?>" id="<?php echo $classified_colors_grid->FormKeyCountName ?>" value="<?php echo $classified_colors_grid->KeyCount ?>">
<?php echo $classified_colors_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($classified_colors->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fclassified_colorsgrid">
</div>
<?php

// Close recordset
if ($classified_colors_grid->Recordset)
	$classified_colors_grid->Recordset->Close();
?>
<?php if ($classified_colors_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($classified_colors_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($classified_colors_grid->TotalRecs == 0 && $classified_colors->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($classified_colors_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($classified_colors->Export == "") { ?>
<script type="text/javascript">
fclassified_colorsgrid.Init();
</script>
<?php } ?>
<?php
$classified_colors_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$classified_colors_grid->Page_Terminate();
?>
