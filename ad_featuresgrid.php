<?php include_once "adm_usersinfo.php" ?>
<?php

// Create page object
if (!isset($ad_features_grid)) $ad_features_grid = new cad_features_grid();

// Page init
$ad_features_grid->Page_Init();

// Page main
$ad_features_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ad_features_grid->Page_Render();
?>
<?php if ($ad_features->Export == "") { ?>
<script type="text/javascript">

// Form object
var fad_featuresgrid = new ew_Form("fad_featuresgrid", "grid");
fad_featuresgrid.FormKeyCountName = '<?php echo $ad_features_grid->FormKeyCountName ?>';

// Validate form
fad_featuresgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ad_features->ad_id->FldCaption(), $ad_features->ad_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ad_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ad_features->ad_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_feature_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ad_features->feature_id->FldCaption(), $ad_features->feature_id->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fad_featuresgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "ad_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "feature_id", false)) return false;
	return true;
}

// Form_CustomValidate event
fad_featuresgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fad_featuresgrid.ValidateRequired = true;
<?php } else { ?>
fad_featuresgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fad_featuresgrid.Lists["x_ad_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_ad_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fad_featuresgrid.Lists["x_feature_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_feature_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($ad_features->CurrentAction == "gridadd") {
	if ($ad_features->CurrentMode == "copy") {
		$bSelectLimit = $ad_features_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$ad_features_grid->TotalRecs = $ad_features->SelectRecordCount();
			$ad_features_grid->Recordset = $ad_features_grid->LoadRecordset($ad_features_grid->StartRec-1, $ad_features_grid->DisplayRecs);
		} else {
			if ($ad_features_grid->Recordset = $ad_features_grid->LoadRecordset())
				$ad_features_grid->TotalRecs = $ad_features_grid->Recordset->RecordCount();
		}
		$ad_features_grid->StartRec = 1;
		$ad_features_grid->DisplayRecs = $ad_features_grid->TotalRecs;
	} else {
		$ad_features->CurrentFilter = "0=1";
		$ad_features_grid->StartRec = 1;
		$ad_features_grid->DisplayRecs = $ad_features->GridAddRowCount;
	}
	$ad_features_grid->TotalRecs = $ad_features_grid->DisplayRecs;
	$ad_features_grid->StopRec = $ad_features_grid->DisplayRecs;
} else {
	$bSelectLimit = $ad_features_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($ad_features_grid->TotalRecs <= 0)
			$ad_features_grid->TotalRecs = $ad_features->SelectRecordCount();
	} else {
		if (!$ad_features_grid->Recordset && ($ad_features_grid->Recordset = $ad_features_grid->LoadRecordset()))
			$ad_features_grid->TotalRecs = $ad_features_grid->Recordset->RecordCount();
	}
	$ad_features_grid->StartRec = 1;
	$ad_features_grid->DisplayRecs = $ad_features_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$ad_features_grid->Recordset = $ad_features_grid->LoadRecordset($ad_features_grid->StartRec-1, $ad_features_grid->DisplayRecs);

	// Set no record found message
	if ($ad_features->CurrentAction == "" && $ad_features_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$ad_features_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($ad_features_grid->SearchWhere == "0=101")
			$ad_features_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$ad_features_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$ad_features_grid->RenderOtherOptions();
?>
<?php $ad_features_grid->ShowPageHeader(); ?>
<?php
$ad_features_grid->ShowMessage();
?>
<?php if ($ad_features_grid->TotalRecs > 0 || $ad_features->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fad_featuresgrid" class="ewForm form-inline">
<div id="gmp_ad_features" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_ad_featuresgrid" class="table ewTable">
<?php echo $ad_features->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$ad_features_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$ad_features_grid->RenderListOptions();

// Render list options (header, left)
$ad_features_grid->ListOptions->Render("header", "left");
?>
<?php if ($ad_features->ad_id->Visible) { // ad_id ?>
	<?php if ($ad_features->SortUrl($ad_features->ad_id) == "") { ?>
		<th data-name="ad_id"><div id="elh_ad_features_ad_id" class="ad_features_ad_id"><div class="ewTableHeaderCaption"><?php echo $ad_features->ad_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ad_id"><div><div id="elh_ad_features_ad_id" class="ad_features_ad_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ad_features->ad_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ad_features->ad_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ad_features->ad_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($ad_features->feature_id->Visible) { // feature_id ?>
	<?php if ($ad_features->SortUrl($ad_features->feature_id) == "") { ?>
		<th data-name="feature_id"><div id="elh_ad_features_feature_id" class="ad_features_feature_id"><div class="ewTableHeaderCaption"><?php echo $ad_features->feature_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="feature_id"><div><div id="elh_ad_features_feature_id" class="ad_features_feature_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ad_features->feature_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ad_features->feature_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ad_features->feature_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ad_features_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$ad_features_grid->StartRec = 1;
$ad_features_grid->StopRec = $ad_features_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($ad_features_grid->FormKeyCountName) && ($ad_features->CurrentAction == "gridadd" || $ad_features->CurrentAction == "gridedit" || $ad_features->CurrentAction == "F")) {
		$ad_features_grid->KeyCount = $objForm->GetValue($ad_features_grid->FormKeyCountName);
		$ad_features_grid->StopRec = $ad_features_grid->StartRec + $ad_features_grid->KeyCount - 1;
	}
}
$ad_features_grid->RecCnt = $ad_features_grid->StartRec - 1;
if ($ad_features_grid->Recordset && !$ad_features_grid->Recordset->EOF) {
	$ad_features_grid->Recordset->MoveFirst();
	$bSelectLimit = $ad_features_grid->UseSelectLimit;
	if (!$bSelectLimit && $ad_features_grid->StartRec > 1)
		$ad_features_grid->Recordset->Move($ad_features_grid->StartRec - 1);
} elseif (!$ad_features->AllowAddDeleteRow && $ad_features_grid->StopRec == 0) {
	$ad_features_grid->StopRec = $ad_features->GridAddRowCount;
}

// Initialize aggregate
$ad_features->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ad_features->ResetAttrs();
$ad_features_grid->RenderRow();
if ($ad_features->CurrentAction == "gridadd")
	$ad_features_grid->RowIndex = 0;
if ($ad_features->CurrentAction == "gridedit")
	$ad_features_grid->RowIndex = 0;
while ($ad_features_grid->RecCnt < $ad_features_grid->StopRec) {
	$ad_features_grid->RecCnt++;
	if (intval($ad_features_grid->RecCnt) >= intval($ad_features_grid->StartRec)) {
		$ad_features_grid->RowCnt++;
		if ($ad_features->CurrentAction == "gridadd" || $ad_features->CurrentAction == "gridedit" || $ad_features->CurrentAction == "F") {
			$ad_features_grid->RowIndex++;
			$objForm->Index = $ad_features_grid->RowIndex;
			if ($objForm->HasValue($ad_features_grid->FormActionName))
				$ad_features_grid->RowAction = strval($objForm->GetValue($ad_features_grid->FormActionName));
			elseif ($ad_features->CurrentAction == "gridadd")
				$ad_features_grid->RowAction = "insert";
			else
				$ad_features_grid->RowAction = "";
		}

		// Set up key count
		$ad_features_grid->KeyCount = $ad_features_grid->RowIndex;

		// Init row class and style
		$ad_features->ResetAttrs();
		$ad_features->CssClass = "";
		if ($ad_features->CurrentAction == "gridadd") {
			if ($ad_features->CurrentMode == "copy") {
				$ad_features_grid->LoadRowValues($ad_features_grid->Recordset); // Load row values
				$ad_features_grid->SetRecordKey($ad_features_grid->RowOldKey, $ad_features_grid->Recordset); // Set old record key
			} else {
				$ad_features_grid->LoadDefaultValues(); // Load default values
				$ad_features_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$ad_features_grid->LoadRowValues($ad_features_grid->Recordset); // Load row values
		}
		$ad_features->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($ad_features->CurrentAction == "gridadd") // Grid add
			$ad_features->RowType = EW_ROWTYPE_ADD; // Render add
		if ($ad_features->CurrentAction == "gridadd" && $ad_features->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$ad_features_grid->RestoreCurrentRowFormValues($ad_features_grid->RowIndex); // Restore form values
		if ($ad_features->CurrentAction == "gridedit") { // Grid edit
			if ($ad_features->EventCancelled) {
				$ad_features_grid->RestoreCurrentRowFormValues($ad_features_grid->RowIndex); // Restore form values
			}
			if ($ad_features_grid->RowAction == "insert")
				$ad_features->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$ad_features->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($ad_features->CurrentAction == "gridedit" && ($ad_features->RowType == EW_ROWTYPE_EDIT || $ad_features->RowType == EW_ROWTYPE_ADD) && $ad_features->EventCancelled) // Update failed
			$ad_features_grid->RestoreCurrentRowFormValues($ad_features_grid->RowIndex); // Restore form values
		if ($ad_features->RowType == EW_ROWTYPE_EDIT) // Edit row
			$ad_features_grid->EditRowCnt++;
		if ($ad_features->CurrentAction == "F") // Confirm row
			$ad_features_grid->RestoreCurrentRowFormValues($ad_features_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$ad_features->RowAttrs = array_merge($ad_features->RowAttrs, array('data-rowindex'=>$ad_features_grid->RowCnt, 'id'=>'r' . $ad_features_grid->RowCnt . '_ad_features', 'data-rowtype'=>$ad_features->RowType));

		// Render row
		$ad_features_grid->RenderRow();

		// Render list options
		$ad_features_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($ad_features_grid->RowAction <> "delete" && $ad_features_grid->RowAction <> "insertdelete" && !($ad_features_grid->RowAction == "insert" && $ad_features->CurrentAction == "F" && $ad_features_grid->EmptyRow())) {
?>
	<tr<?php echo $ad_features->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ad_features_grid->ListOptions->Render("body", "left", $ad_features_grid->RowCnt);
?>
	<?php if ($ad_features->ad_id->Visible) { // ad_id ?>
		<td data-name="ad_id"<?php echo $ad_features->ad_id->CellAttributes() ?>>
<?php if ($ad_features->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($ad_features->ad_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $ad_features_grid->RowCnt ?>_ad_features_ad_id" class="form-group ad_features_ad_id">
<span<?php echo $ad_features->ad_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ad_features->ad_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" name="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $ad_features_grid->RowCnt ?>_ad_features_ad_id" class="form-group ad_features_ad_id">
<?php
$wrkonchange = trim(" " . @$ad_features->ad_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$ad_features->ad_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" style="white-space: nowrap; z-index: <?php echo (9000 - $ad_features_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="sv_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo $ad_features->ad_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($ad_features->ad_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($ad_features->ad_id->getPlaceHolder()) ?>"<?php echo $ad_features->ad_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="ad_features" data-field="x_ad_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($ad_features->ad_id->DisplayValueSeparator) ? json_encode($ad_features->ad_id->DisplayValueSeparator) : $ad_features->ad_id->DisplayValueSeparator) ?>" name="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `ad_title` AS `DispFld` FROM `car_ads`";
$sWhereWrk = "`ad_title` LIKE '{query_value}%'";
$ad_features->Lookup_Selecting($ad_features->ad_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="q_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fad_featuresgrid.CreateAutoSuggest({"id":"x<?php echo $ad_features_grid->RowIndex ?>_ad_id","forceSelect":false});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="ad_features" data-field="x_ad_id" name="o<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="o<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->OldValue) ?>">
<?php } ?>
<?php if ($ad_features->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($ad_features->ad_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $ad_features_grid->RowCnt ?>_ad_features_ad_id" class="form-group ad_features_ad_id">
<span<?php echo $ad_features->ad_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ad_features->ad_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" name="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $ad_features_grid->RowCnt ?>_ad_features_ad_id" class="form-group ad_features_ad_id">
<?php
$wrkonchange = trim(" " . @$ad_features->ad_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$ad_features->ad_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" style="white-space: nowrap; z-index: <?php echo (9000 - $ad_features_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="sv_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo $ad_features->ad_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($ad_features->ad_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($ad_features->ad_id->getPlaceHolder()) ?>"<?php echo $ad_features->ad_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="ad_features" data-field="x_ad_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($ad_features->ad_id->DisplayValueSeparator) ? json_encode($ad_features->ad_id->DisplayValueSeparator) : $ad_features->ad_id->DisplayValueSeparator) ?>" name="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `ad_title` AS `DispFld` FROM `car_ads`";
$sWhereWrk = "`ad_title` LIKE '{query_value}%'";
$ad_features->Lookup_Selecting($ad_features->ad_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="q_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fad_featuresgrid.CreateAutoSuggest({"id":"x<?php echo $ad_features_grid->RowIndex ?>_ad_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($ad_features->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $ad_features_grid->RowCnt ?>_ad_features_ad_id" class="ad_features_ad_id">
<span<?php echo $ad_features->ad_id->ViewAttributes() ?>>
<?php echo $ad_features->ad_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="ad_features" data-field="x_ad_id" name="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->FormValue) ?>">
<input type="hidden" data-table="ad_features" data-field="x_ad_id" name="o<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="o<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ad_features_grid->PageObjName . "_row_" . $ad_features_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($ad_features->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="ad_features" data-field="x_ID" name="x<?php echo $ad_features_grid->RowIndex ?>_ID" id="x<?php echo $ad_features_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($ad_features->ID->CurrentValue) ?>">
<input type="hidden" data-table="ad_features" data-field="x_ID" name="o<?php echo $ad_features_grid->RowIndex ?>_ID" id="o<?php echo $ad_features_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($ad_features->ID->OldValue) ?>">
<?php } ?>
<?php if ($ad_features->RowType == EW_ROWTYPE_EDIT || $ad_features->CurrentMode == "edit") { ?>
<input type="hidden" data-table="ad_features" data-field="x_ID" name="x<?php echo $ad_features_grid->RowIndex ?>_ID" id="x<?php echo $ad_features_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($ad_features->ID->CurrentValue) ?>">
<?php } ?>
	<?php if ($ad_features->feature_id->Visible) { // feature_id ?>
		<td data-name="feature_id"<?php echo $ad_features->feature_id->CellAttributes() ?>>
<?php if ($ad_features->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ad_features_grid->RowCnt ?>_ad_features_feature_id" class="form-group ad_features_feature_id">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $ad_features->feature_id->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
$arwrk = $ad_features->feature_id->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ad_features->feature_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "") {
			$emptywrk = FALSE;
?>
<input type="radio" data-table="ad_features" data-field="x_feature_id" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ad_features->feature_id->EditAttributes() ?>><?php echo $ad_features->feature_id->DisplayValue($arwrk[$rowcntwrk]) ?>
<?php
		}
	}
	if ($emptywrk && strval($ad_features->feature_id->CurrentValue) <> "") {
?>
<input type="radio" data-table="ad_features" data-field="x_feature_id" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($ad_features->feature_id->CurrentValue) ?>" checked<?php echo $ad_features->feature_id->EditAttributes() ?>><?php echo $ad_features->feature_id->CurrentValue ?>
<?php
    }
}
if (@$emptywrk) $ad_features->feature_id->OldValue = "";
?>
		</div>
	</div>
	<div id="tp_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" class="ewTemplate"><input type="radio" data-table="ad_features" data-field="x_feature_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($ad_features->feature_id->DisplayValueSeparator) ? json_encode($ad_features->feature_id->DisplayValueSeparator) : $ad_features->feature_id->DisplayValueSeparator) ?>" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="{value}"<?php echo $ad_features->feature_id->EditAttributes() ?>></div>
</div>
<?php
$sSqlWrk = "SELECT `ID`, `feature_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_features`";
$sWhereWrk = "";
$ad_features->feature_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$ad_features->feature_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$ad_features->Lookup_Selecting($ad_features->feature_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $ad_features->feature_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="s_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="<?php echo $ad_features->feature_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="ad_features" data-field="x_feature_id" name="o<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="o<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="<?php echo ew_HtmlEncode($ad_features->feature_id->OldValue) ?>">
<?php } ?>
<?php if ($ad_features->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ad_features_grid->RowCnt ?>_ad_features_feature_id" class="form-group ad_features_feature_id">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $ad_features->feature_id->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
$arwrk = $ad_features->feature_id->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ad_features->feature_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "") {
			$emptywrk = FALSE;
?>
<input type="radio" data-table="ad_features" data-field="x_feature_id" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ad_features->feature_id->EditAttributes() ?>><?php echo $ad_features->feature_id->DisplayValue($arwrk[$rowcntwrk]) ?>
<?php
		}
	}
	if ($emptywrk && strval($ad_features->feature_id->CurrentValue) <> "") {
?>
<input type="radio" data-table="ad_features" data-field="x_feature_id" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($ad_features->feature_id->CurrentValue) ?>" checked<?php echo $ad_features->feature_id->EditAttributes() ?>><?php echo $ad_features->feature_id->CurrentValue ?>
<?php
    }
}
if (@$emptywrk) $ad_features->feature_id->OldValue = "";
?>
		</div>
	</div>
	<div id="tp_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" class="ewTemplate"><input type="radio" data-table="ad_features" data-field="x_feature_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($ad_features->feature_id->DisplayValueSeparator) ? json_encode($ad_features->feature_id->DisplayValueSeparator) : $ad_features->feature_id->DisplayValueSeparator) ?>" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="{value}"<?php echo $ad_features->feature_id->EditAttributes() ?>></div>
</div>
<?php
$sSqlWrk = "SELECT `ID`, `feature_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_features`";
$sWhereWrk = "";
$ad_features->feature_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$ad_features->feature_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$ad_features->Lookup_Selecting($ad_features->feature_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $ad_features->feature_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="s_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="<?php echo $ad_features->feature_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($ad_features->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $ad_features_grid->RowCnt ?>_ad_features_feature_id" class="ad_features_feature_id">
<span<?php echo $ad_features->feature_id->ViewAttributes() ?>>
<?php echo $ad_features->feature_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="ad_features" data-field="x_feature_id" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="<?php echo ew_HtmlEncode($ad_features->feature_id->FormValue) ?>">
<input type="hidden" data-table="ad_features" data-field="x_feature_id" name="o<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="o<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="<?php echo ew_HtmlEncode($ad_features->feature_id->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ad_features_grid->ListOptions->Render("body", "right", $ad_features_grid->RowCnt);
?>
	</tr>
<?php if ($ad_features->RowType == EW_ROWTYPE_ADD || $ad_features->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fad_featuresgrid.UpdateOpts(<?php echo $ad_features_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($ad_features->CurrentAction <> "gridadd" || $ad_features->CurrentMode == "copy")
		if (!$ad_features_grid->Recordset->EOF) $ad_features_grid->Recordset->MoveNext();
}
?>
<?php
	if ($ad_features->CurrentMode == "add" || $ad_features->CurrentMode == "copy" || $ad_features->CurrentMode == "edit") {
		$ad_features_grid->RowIndex = '$rowindex$';
		$ad_features_grid->LoadDefaultValues();

		// Set row properties
		$ad_features->ResetAttrs();
		$ad_features->RowAttrs = array_merge($ad_features->RowAttrs, array('data-rowindex'=>$ad_features_grid->RowIndex, 'id'=>'r0_ad_features', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($ad_features->RowAttrs["class"], "ewTemplate");
		$ad_features->RowType = EW_ROWTYPE_ADD;

		// Render row
		$ad_features_grid->RenderRow();

		// Render list options
		$ad_features_grid->RenderListOptions();
		$ad_features_grid->StartRowCnt = 0;
?>
	<tr<?php echo $ad_features->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ad_features_grid->ListOptions->Render("body", "left", $ad_features_grid->RowIndex);
?>
	<?php if ($ad_features->ad_id->Visible) { // ad_id ?>
		<td data-name="ad_id">
<?php if ($ad_features->CurrentAction <> "F") { ?>
<?php if ($ad_features->ad_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_ad_features_ad_id" class="form-group ad_features_ad_id">
<span<?php echo $ad_features->ad_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ad_features->ad_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" name="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_ad_features_ad_id" class="form-group ad_features_ad_id">
<?php
$wrkonchange = trim(" " . @$ad_features->ad_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$ad_features->ad_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" style="white-space: nowrap; z-index: <?php echo (9000 - $ad_features_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="sv_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo $ad_features->ad_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($ad_features->ad_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($ad_features->ad_id->getPlaceHolder()) ?>"<?php echo $ad_features->ad_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="ad_features" data-field="x_ad_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($ad_features->ad_id->DisplayValueSeparator) ? json_encode($ad_features->ad_id->DisplayValueSeparator) : $ad_features->ad_id->DisplayValueSeparator) ?>" name="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `ad_title` AS `DispFld` FROM `car_ads`";
$sWhereWrk = "`ad_title` LIKE '{query_value}%'";
$ad_features->Lookup_Selecting($ad_features->ad_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="q_x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fad_featuresgrid.CreateAutoSuggest({"id":"x<?php echo $ad_features_grid->RowIndex ?>_ad_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_ad_features_ad_id" class="form-group ad_features_ad_id">
<span<?php echo $ad_features->ad_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ad_features->ad_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="ad_features" data-field="x_ad_id" name="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="x<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="ad_features" data-field="x_ad_id" name="o<?php echo $ad_features_grid->RowIndex ?>_ad_id" id="o<?php echo $ad_features_grid->RowIndex ?>_ad_id" value="<?php echo ew_HtmlEncode($ad_features->ad_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ad_features->feature_id->Visible) { // feature_id ?>
		<td data-name="feature_id">
<?php if ($ad_features->CurrentAction <> "F") { ?>
<span id="el$rowindex$_ad_features_feature_id" class="form-group ad_features_feature_id">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $ad_features->feature_id->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
$arwrk = $ad_features->feature_id->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ad_features->feature_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "") {
			$emptywrk = FALSE;
?>
<input type="radio" data-table="ad_features" data-field="x_feature_id" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ad_features->feature_id->EditAttributes() ?>><?php echo $ad_features->feature_id->DisplayValue($arwrk[$rowcntwrk]) ?>
<?php
		}
	}
	if ($emptywrk && strval($ad_features->feature_id->CurrentValue) <> "") {
?>
<input type="radio" data-table="ad_features" data-field="x_feature_id" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($ad_features->feature_id->CurrentValue) ?>" checked<?php echo $ad_features->feature_id->EditAttributes() ?>><?php echo $ad_features->feature_id->CurrentValue ?>
<?php
    }
}
if (@$emptywrk) $ad_features->feature_id->OldValue = "";
?>
		</div>
	</div>
	<div id="tp_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" class="ewTemplate"><input type="radio" data-table="ad_features" data-field="x_feature_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($ad_features->feature_id->DisplayValueSeparator) ? json_encode($ad_features->feature_id->DisplayValueSeparator) : $ad_features->feature_id->DisplayValueSeparator) ?>" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="{value}"<?php echo $ad_features->feature_id->EditAttributes() ?>></div>
</div>
<?php
$sSqlWrk = "SELECT `ID`, `feature_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_features`";
$sWhereWrk = "";
$ad_features->feature_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$ad_features->feature_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$ad_features->Lookup_Selecting($ad_features->feature_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $ad_features->feature_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="s_x<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="<?php echo $ad_features->feature_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_ad_features_feature_id" class="form-group ad_features_feature_id">
<span<?php echo $ad_features->feature_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ad_features->feature_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="ad_features" data-field="x_feature_id" name="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="x<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="<?php echo ew_HtmlEncode($ad_features->feature_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="ad_features" data-field="x_feature_id" name="o<?php echo $ad_features_grid->RowIndex ?>_feature_id" id="o<?php echo $ad_features_grid->RowIndex ?>_feature_id" value="<?php echo ew_HtmlEncode($ad_features->feature_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ad_features_grid->ListOptions->Render("body", "right", $ad_features_grid->RowCnt);
?>
<script type="text/javascript">
fad_featuresgrid.UpdateOpts(<?php echo $ad_features_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($ad_features->CurrentMode == "add" || $ad_features->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $ad_features_grid->FormKeyCountName ?>" id="<?php echo $ad_features_grid->FormKeyCountName ?>" value="<?php echo $ad_features_grid->KeyCount ?>">
<?php echo $ad_features_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ad_features->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $ad_features_grid->FormKeyCountName ?>" id="<?php echo $ad_features_grid->FormKeyCountName ?>" value="<?php echo $ad_features_grid->KeyCount ?>">
<?php echo $ad_features_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ad_features->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fad_featuresgrid">
</div>
<?php

// Close recordset
if ($ad_features_grid->Recordset)
	$ad_features_grid->Recordset->Close();
?>
<?php if ($ad_features_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($ad_features_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($ad_features_grid->TotalRecs == 0 && $ad_features->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($ad_features_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($ad_features->Export == "") { ?>
<script type="text/javascript">
fad_featuresgrid.Init();
</script>
<?php } ?>
<?php
$ad_features_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$ad_features_grid->Page_Terminate();
?>
