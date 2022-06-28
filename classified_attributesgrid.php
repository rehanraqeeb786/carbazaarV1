<?php include_once "adm_usersinfo.php" ?>
<?php

// Create page object
if (!isset($classified_attributes_grid)) $classified_attributes_grid = new cclassified_attributes_grid();

// Page init
$classified_attributes_grid->Page_Init();

// Page main
$classified_attributes_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$classified_attributes_grid->Page_Render();
?>
<?php if ($classified_attributes->Export == "") { ?>
<script type="text/javascript">

// Form object
var fclassified_attributesgrid = new ew_Form("fclassified_attributesgrid", "grid");
fclassified_attributesgrid.FormKeyCountName = '<?php echo $classified_attributes_grid->FormKeyCountName ?>';

// Validate form
fclassified_attributesgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_classified_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_attributes->classified_id->FldCaption(), $classified_attributes->classified_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_classified_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($classified_attributes->classified_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_attribute_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_attributes->attribute_id->FldCaption(), $classified_attributes->attribute_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_attribute_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($classified_attributes->attribute_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_attribute_value");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_attributes->attribute_value->FldCaption(), $classified_attributes->attribute_value->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fclassified_attributesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "classified_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "attribute_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "attribute_value", false)) return false;
	return true;
}

// Form_CustomValidate event
fclassified_attributesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclassified_attributesgrid.ValidateRequired = true;
<?php } else { ?>
fclassified_attributesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclassified_attributesgrid.Lists["x_classified_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_attributesgrid.Lists["x_attribute_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_attribute_title","x_attribute_type","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($classified_attributes->CurrentAction == "gridadd") {
	if ($classified_attributes->CurrentMode == "copy") {
		$bSelectLimit = $classified_attributes_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$classified_attributes_grid->TotalRecs = $classified_attributes->SelectRecordCount();
			$classified_attributes_grid->Recordset = $classified_attributes_grid->LoadRecordset($classified_attributes_grid->StartRec-1, $classified_attributes_grid->DisplayRecs);
		} else {
			if ($classified_attributes_grid->Recordset = $classified_attributes_grid->LoadRecordset())
				$classified_attributes_grid->TotalRecs = $classified_attributes_grid->Recordset->RecordCount();
		}
		$classified_attributes_grid->StartRec = 1;
		$classified_attributes_grid->DisplayRecs = $classified_attributes_grid->TotalRecs;
	} else {
		$classified_attributes->CurrentFilter = "0=1";
		$classified_attributes_grid->StartRec = 1;
		$classified_attributes_grid->DisplayRecs = $classified_attributes->GridAddRowCount;
	}
	$classified_attributes_grid->TotalRecs = $classified_attributes_grid->DisplayRecs;
	$classified_attributes_grid->StopRec = $classified_attributes_grid->DisplayRecs;
} else {
	$bSelectLimit = $classified_attributes_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($classified_attributes_grid->TotalRecs <= 0)
			$classified_attributes_grid->TotalRecs = $classified_attributes->SelectRecordCount();
	} else {
		if (!$classified_attributes_grid->Recordset && ($classified_attributes_grid->Recordset = $classified_attributes_grid->LoadRecordset()))
			$classified_attributes_grid->TotalRecs = $classified_attributes_grid->Recordset->RecordCount();
	}
	$classified_attributes_grid->StartRec = 1;
	$classified_attributes_grid->DisplayRecs = $classified_attributes_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$classified_attributes_grid->Recordset = $classified_attributes_grid->LoadRecordset($classified_attributes_grid->StartRec-1, $classified_attributes_grid->DisplayRecs);

	// Set no record found message
	if ($classified_attributes->CurrentAction == "" && $classified_attributes_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$classified_attributes_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($classified_attributes_grid->SearchWhere == "0=101")
			$classified_attributes_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$classified_attributes_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$classified_attributes_grid->RenderOtherOptions();
?>
<?php $classified_attributes_grid->ShowPageHeader(); ?>
<?php
$classified_attributes_grid->ShowMessage();
?>
<?php if ($classified_attributes_grid->TotalRecs > 0 || $classified_attributes->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fclassified_attributesgrid" class="ewForm form-inline">
<div id="gmp_classified_attributes" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_classified_attributesgrid" class="table ewTable">
<?php echo $classified_attributes->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$classified_attributes_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$classified_attributes_grid->RenderListOptions();

// Render list options (header, left)
$classified_attributes_grid->ListOptions->Render("header", "left");
?>
<?php if ($classified_attributes->classified_id->Visible) { // classified_id ?>
	<?php if ($classified_attributes->SortUrl($classified_attributes->classified_id) == "") { ?>
		<th data-name="classified_id"><div id="elh_classified_attributes_classified_id" class="classified_attributes_classified_id"><div class="ewTableHeaderCaption"><?php echo $classified_attributes->classified_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="classified_id"><div><div id="elh_classified_attributes_classified_id" class="classified_attributes_classified_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_attributes->classified_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_attributes->classified_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_attributes->classified_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_attributes->attribute_id->Visible) { // attribute_id ?>
	<?php if ($classified_attributes->SortUrl($classified_attributes->attribute_id) == "") { ?>
		<th data-name="attribute_id"><div id="elh_classified_attributes_attribute_id" class="classified_attributes_attribute_id"><div class="ewTableHeaderCaption"><?php echo $classified_attributes->attribute_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="attribute_id"><div><div id="elh_classified_attributes_attribute_id" class="classified_attributes_attribute_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_attributes->attribute_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_attributes->attribute_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_attributes->attribute_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_attributes->attribute_value->Visible) { // attribute_value ?>
	<?php if ($classified_attributes->SortUrl($classified_attributes->attribute_value) == "") { ?>
		<th data-name="attribute_value"><div id="elh_classified_attributes_attribute_value" class="classified_attributes_attribute_value"><div class="ewTableHeaderCaption"><?php echo $classified_attributes->attribute_value->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="attribute_value"><div><div id="elh_classified_attributes_attribute_value" class="classified_attributes_attribute_value">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_attributes->attribute_value->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_attributes->attribute_value->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_attributes->attribute_value->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$classified_attributes_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$classified_attributes_grid->StartRec = 1;
$classified_attributes_grid->StopRec = $classified_attributes_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($classified_attributes_grid->FormKeyCountName) && ($classified_attributes->CurrentAction == "gridadd" || $classified_attributes->CurrentAction == "gridedit" || $classified_attributes->CurrentAction == "F")) {
		$classified_attributes_grid->KeyCount = $objForm->GetValue($classified_attributes_grid->FormKeyCountName);
		$classified_attributes_grid->StopRec = $classified_attributes_grid->StartRec + $classified_attributes_grid->KeyCount - 1;
	}
}
$classified_attributes_grid->RecCnt = $classified_attributes_grid->StartRec - 1;
if ($classified_attributes_grid->Recordset && !$classified_attributes_grid->Recordset->EOF) {
	$classified_attributes_grid->Recordset->MoveFirst();
	$bSelectLimit = $classified_attributes_grid->UseSelectLimit;
	if (!$bSelectLimit && $classified_attributes_grid->StartRec > 1)
		$classified_attributes_grid->Recordset->Move($classified_attributes_grid->StartRec - 1);
} elseif (!$classified_attributes->AllowAddDeleteRow && $classified_attributes_grid->StopRec == 0) {
	$classified_attributes_grid->StopRec = $classified_attributes->GridAddRowCount;
}

// Initialize aggregate
$classified_attributes->RowType = EW_ROWTYPE_AGGREGATEINIT;
$classified_attributes->ResetAttrs();
$classified_attributes_grid->RenderRow();
if ($classified_attributes->CurrentAction == "gridadd")
	$classified_attributes_grid->RowIndex = 0;
if ($classified_attributes->CurrentAction == "gridedit")
	$classified_attributes_grid->RowIndex = 0;
while ($classified_attributes_grid->RecCnt < $classified_attributes_grid->StopRec) {
	$classified_attributes_grid->RecCnt++;
	if (intval($classified_attributes_grid->RecCnt) >= intval($classified_attributes_grid->StartRec)) {
		$classified_attributes_grid->RowCnt++;
		if ($classified_attributes->CurrentAction == "gridadd" || $classified_attributes->CurrentAction == "gridedit" || $classified_attributes->CurrentAction == "F") {
			$classified_attributes_grid->RowIndex++;
			$objForm->Index = $classified_attributes_grid->RowIndex;
			if ($objForm->HasValue($classified_attributes_grid->FormActionName))
				$classified_attributes_grid->RowAction = strval($objForm->GetValue($classified_attributes_grid->FormActionName));
			elseif ($classified_attributes->CurrentAction == "gridadd")
				$classified_attributes_grid->RowAction = "insert";
			else
				$classified_attributes_grid->RowAction = "";
		}

		// Set up key count
		$classified_attributes_grid->KeyCount = $classified_attributes_grid->RowIndex;

		// Init row class and style
		$classified_attributes->ResetAttrs();
		$classified_attributes->CssClass = "";
		if ($classified_attributes->CurrentAction == "gridadd") {
			if ($classified_attributes->CurrentMode == "copy") {
				$classified_attributes_grid->LoadRowValues($classified_attributes_grid->Recordset); // Load row values
				$classified_attributes_grid->SetRecordKey($classified_attributes_grid->RowOldKey, $classified_attributes_grid->Recordset); // Set old record key
			} else {
				$classified_attributes_grid->LoadDefaultValues(); // Load default values
				$classified_attributes_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$classified_attributes_grid->LoadRowValues($classified_attributes_grid->Recordset); // Load row values
		}
		$classified_attributes->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($classified_attributes->CurrentAction == "gridadd") // Grid add
			$classified_attributes->RowType = EW_ROWTYPE_ADD; // Render add
		if ($classified_attributes->CurrentAction == "gridadd" && $classified_attributes->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$classified_attributes_grid->RestoreCurrentRowFormValues($classified_attributes_grid->RowIndex); // Restore form values
		if ($classified_attributes->CurrentAction == "gridedit") { // Grid edit
			if ($classified_attributes->EventCancelled) {
				$classified_attributes_grid->RestoreCurrentRowFormValues($classified_attributes_grid->RowIndex); // Restore form values
			}
			if ($classified_attributes_grid->RowAction == "insert")
				$classified_attributes->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$classified_attributes->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($classified_attributes->CurrentAction == "gridedit" && ($classified_attributes->RowType == EW_ROWTYPE_EDIT || $classified_attributes->RowType == EW_ROWTYPE_ADD) && $classified_attributes->EventCancelled) // Update failed
			$classified_attributes_grid->RestoreCurrentRowFormValues($classified_attributes_grid->RowIndex); // Restore form values
		if ($classified_attributes->RowType == EW_ROWTYPE_EDIT) // Edit row
			$classified_attributes_grid->EditRowCnt++;
		if ($classified_attributes->CurrentAction == "F") // Confirm row
			$classified_attributes_grid->RestoreCurrentRowFormValues($classified_attributes_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$classified_attributes->RowAttrs = array_merge($classified_attributes->RowAttrs, array('data-rowindex'=>$classified_attributes_grid->RowCnt, 'id'=>'r' . $classified_attributes_grid->RowCnt . '_classified_attributes', 'data-rowtype'=>$classified_attributes->RowType));

		// Render row
		$classified_attributes_grid->RenderRow();

		// Render list options
		$classified_attributes_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($classified_attributes_grid->RowAction <> "delete" && $classified_attributes_grid->RowAction <> "insertdelete" && !($classified_attributes_grid->RowAction == "insert" && $classified_attributes->CurrentAction == "F" && $classified_attributes_grid->EmptyRow())) {
?>
	<tr<?php echo $classified_attributes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$classified_attributes_grid->ListOptions->Render("body", "left", $classified_attributes_grid->RowCnt);
?>
	<?php if ($classified_attributes->classified_id->Visible) { // classified_id ?>
		<td data-name="classified_id"<?php echo $classified_attributes->classified_id->CellAttributes() ?>>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($classified_attributes->classified_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_classified_id" class="form-group classified_attributes_classified_id">
<span<?php echo $classified_attributes->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_attributes->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" name="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_classified_id" class="form-group classified_attributes_classified_id">
<?php
$wrkonchange = trim(" " . @$classified_attributes->classified_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_attributes->classified_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_attributes_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo $classified_attributes->classified_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_attributes->classified_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_attributes->classified_id->getPlaceHolder()) ?>"<?php echo $classified_attributes->classified_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_classified_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_attributes->classified_id->DisplayValueSeparator) ? json_encode($classified_attributes->classified_id->DisplayValueSeparator) : $classified_attributes->classified_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_attributes->Lookup_Selecting($classified_attributes->classified_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="q_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_attributesgrid.CreateAutoSuggest({"id":"x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id","forceSelect":false});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="classified_attributes" data-field="x_classified_id" name="o<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="o<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->OldValue) ?>">
<?php } ?>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($classified_attributes->classified_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_classified_id" class="form-group classified_attributes_classified_id">
<span<?php echo $classified_attributes->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_attributes->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" name="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_classified_id" class="form-group classified_attributes_classified_id">
<?php
$wrkonchange = trim(" " . @$classified_attributes->classified_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_attributes->classified_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_attributes_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo $classified_attributes->classified_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_attributes->classified_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_attributes->classified_id->getPlaceHolder()) ?>"<?php echo $classified_attributes->classified_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_classified_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_attributes->classified_id->DisplayValueSeparator) ? json_encode($classified_attributes->classified_id->DisplayValueSeparator) : $classified_attributes->classified_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_attributes->Lookup_Selecting($classified_attributes->classified_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="q_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_attributesgrid.CreateAutoSuggest({"id":"x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_classified_id" class="classified_attributes_classified_id">
<span<?php echo $classified_attributes->classified_id->ViewAttributes() ?>>
<?php echo $classified_attributes->classified_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_classified_id" name="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->FormValue) ?>">
<input type="hidden" data-table="classified_attributes" data-field="x_classified_id" name="o<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="o<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $classified_attributes_grid->PageObjName . "_row_" . $classified_attributes_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="classified_attributes" data-field="x_ID" name="x<?php echo $classified_attributes_grid->RowIndex ?>_ID" id="x<?php echo $classified_attributes_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_attributes->ID->CurrentValue) ?>">
<input type="hidden" data-table="classified_attributes" data-field="x_ID" name="o<?php echo $classified_attributes_grid->RowIndex ?>_ID" id="o<?php echo $classified_attributes_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_attributes->ID->OldValue) ?>">
<?php } ?>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_EDIT || $classified_attributes->CurrentMode == "edit") { ?>
<input type="hidden" data-table="classified_attributes" data-field="x_ID" name="x<?php echo $classified_attributes_grid->RowIndex ?>_ID" id="x<?php echo $classified_attributes_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_attributes->ID->CurrentValue) ?>">
<?php } ?>
	<?php if ($classified_attributes->attribute_id->Visible) { // attribute_id ?>
		<td data-name="attribute_id"<?php echo $classified_attributes->attribute_id->CellAttributes() ?>>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_attribute_id" class="form-group classified_attributes_attribute_id">
<?php
$wrkonchange = trim(" " . @$classified_attributes->attribute_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_attributes->attribute_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_attributes_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo $classified_attributes->attribute_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->getPlaceHolder()) ?>"<?php echo $classified_attributes->attribute_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_attributes->attribute_id->DisplayValueSeparator) ? json_encode($classified_attributes->attribute_id->DisplayValueSeparator) : $classified_attributes->attribute_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld` FROM `cfg_classified_attribure`";
$sWhereWrk = "`attribute_title` LIKE '{query_value}%' OR CONCAT(`attribute_title`,'" . ew_ValueSeparator(1, $Page->attribute_id) . "',`attribute_type`) LIKE '{query_value}%'";
$classified_attributes->Lookup_Selecting($classified_attributes->attribute_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="q_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_attributesgrid.CreateAutoSuggest({"id":"x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id","forceSelect":false});
</script>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_id" name="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->OldValue) ?>">
<?php } ?>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_attribute_id" class="form-group classified_attributes_attribute_id">
<?php
$wrkonchange = trim(" " . @$classified_attributes->attribute_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_attributes->attribute_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_attributes_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo $classified_attributes->attribute_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->getPlaceHolder()) ?>"<?php echo $classified_attributes->attribute_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_attributes->attribute_id->DisplayValueSeparator) ? json_encode($classified_attributes->attribute_id->DisplayValueSeparator) : $classified_attributes->attribute_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld` FROM `cfg_classified_attribure`";
$sWhereWrk = "`attribute_title` LIKE '{query_value}%' OR CONCAT(`attribute_title`,'" . ew_ValueSeparator(1, $Page->attribute_id) . "',`attribute_type`) LIKE '{query_value}%'";
$classified_attributes->Lookup_Selecting($classified_attributes->attribute_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="q_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_attributesgrid.CreateAutoSuggest({"id":"x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_attribute_id" class="classified_attributes_attribute_id">
<span<?php echo $classified_attributes->attribute_id->ViewAttributes() ?>>
<?php echo $classified_attributes->attribute_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_id" name="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->FormValue) ?>">
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_id" name="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($classified_attributes->attribute_value->Visible) { // attribute_value ?>
		<td data-name="attribute_value"<?php echo $classified_attributes->attribute_value->CellAttributes() ?>>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_attribute_value" class="form-group classified_attributes_attribute_value">
<input type="text" data-table="classified_attributes" data-field="x_attribute_value" name="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" id="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_value->getPlaceHolder()) ?>" value="<?php echo $classified_attributes->attribute_value->EditValue ?>"<?php echo $classified_attributes->attribute_value->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_value" name="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" id="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_value->OldValue) ?>">
<?php } ?>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_attribute_value" class="form-group classified_attributes_attribute_value">
<input type="text" data-table="classified_attributes" data-field="x_attribute_value" name="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" id="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_value->getPlaceHolder()) ?>" value="<?php echo $classified_attributes->attribute_value->EditValue ?>"<?php echo $classified_attributes->attribute_value->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $classified_attributes_grid->RowCnt ?>_classified_attributes_attribute_value" class="classified_attributes_attribute_value">
<span<?php echo $classified_attributes->attribute_value->ViewAttributes() ?>>
<?php echo $classified_attributes->attribute_value->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_value" name="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" id="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_value->FormValue) ?>">
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_value" name="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" id="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_value->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$classified_attributes_grid->ListOptions->Render("body", "right", $classified_attributes_grid->RowCnt);
?>
	</tr>
<?php if ($classified_attributes->RowType == EW_ROWTYPE_ADD || $classified_attributes->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fclassified_attributesgrid.UpdateOpts(<?php echo $classified_attributes_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($classified_attributes->CurrentAction <> "gridadd" || $classified_attributes->CurrentMode == "copy")
		if (!$classified_attributes_grid->Recordset->EOF) $classified_attributes_grid->Recordset->MoveNext();
}
?>
<?php
	if ($classified_attributes->CurrentMode == "add" || $classified_attributes->CurrentMode == "copy" || $classified_attributes->CurrentMode == "edit") {
		$classified_attributes_grid->RowIndex = '$rowindex$';
		$classified_attributes_grid->LoadDefaultValues();

		// Set row properties
		$classified_attributes->ResetAttrs();
		$classified_attributes->RowAttrs = array_merge($classified_attributes->RowAttrs, array('data-rowindex'=>$classified_attributes_grid->RowIndex, 'id'=>'r0_classified_attributes', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($classified_attributes->RowAttrs["class"], "ewTemplate");
		$classified_attributes->RowType = EW_ROWTYPE_ADD;

		// Render row
		$classified_attributes_grid->RenderRow();

		// Render list options
		$classified_attributes_grid->RenderListOptions();
		$classified_attributes_grid->StartRowCnt = 0;
?>
	<tr<?php echo $classified_attributes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$classified_attributes_grid->ListOptions->Render("body", "left", $classified_attributes_grid->RowIndex);
?>
	<?php if ($classified_attributes->classified_id->Visible) { // classified_id ?>
		<td data-name="classified_id">
<?php if ($classified_attributes->CurrentAction <> "F") { ?>
<?php if ($classified_attributes->classified_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_classified_attributes_classified_id" class="form-group classified_attributes_classified_id">
<span<?php echo $classified_attributes->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_attributes->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" name="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_classified_attributes_classified_id" class="form-group classified_attributes_classified_id">
<?php
$wrkonchange = trim(" " . @$classified_attributes->classified_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_attributes->classified_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_attributes_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo $classified_attributes->classified_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_attributes->classified_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_attributes->classified_id->getPlaceHolder()) ?>"<?php echo $classified_attributes->classified_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_classified_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_attributes->classified_id->DisplayValueSeparator) ? json_encode($classified_attributes->classified_id->DisplayValueSeparator) : $classified_attributes->classified_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_attributes->Lookup_Selecting($classified_attributes->classified_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="q_x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_attributesgrid.CreateAutoSuggest({"id":"x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_classified_attributes_classified_id" class="form-group classified_attributes_classified_id">
<span<?php echo $classified_attributes->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_attributes->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_classified_id" name="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="classified_attributes" data-field="x_classified_id" name="o<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" id="o<?php echo $classified_attributes_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($classified_attributes->attribute_id->Visible) { // attribute_id ?>
		<td data-name="attribute_id">
<?php if ($classified_attributes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_classified_attributes_attribute_id" class="form-group classified_attributes_attribute_id">
<?php
$wrkonchange = trim(" " . @$classified_attributes->attribute_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_attributes->attribute_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_attributes_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="sv_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo $classified_attributes->attribute_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->getPlaceHolder()) ?>"<?php echo $classified_attributes->attribute_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_attributes->attribute_id->DisplayValueSeparator) ? json_encode($classified_attributes->attribute_id->DisplayValueSeparator) : $classified_attributes->attribute_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld` FROM `cfg_classified_attribure`";
$sWhereWrk = "`attribute_title` LIKE '{query_value}%' OR CONCAT(`attribute_title`,'" . ew_ValueSeparator(1, $Page->attribute_id) . "',`attribute_type`) LIKE '{query_value}%'";
$classified_attributes->Lookup_Selecting($classified_attributes->attribute_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="q_x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_attributesgrid.CreateAutoSuggest({"id":"x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_classified_attributes_attribute_id" class="form-group classified_attributes_attribute_id">
<span<?php echo $classified_attributes->attribute_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_attributes->attribute_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_id" name="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_id" name="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" id="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_id" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($classified_attributes->attribute_value->Visible) { // attribute_value ?>
		<td data-name="attribute_value">
<?php if ($classified_attributes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_classified_attributes_attribute_value" class="form-group classified_attributes_attribute_value">
<input type="text" data-table="classified_attributes" data-field="x_attribute_value" name="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" id="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_value->getPlaceHolder()) ?>" value="<?php echo $classified_attributes->attribute_value->EditValue ?>"<?php echo $classified_attributes->attribute_value->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_classified_attributes_attribute_value" class="form-group classified_attributes_attribute_value">
<span<?php echo $classified_attributes->attribute_value->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_attributes->attribute_value->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_value" name="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" id="x<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_value->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_value" name="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" id="o<?php echo $classified_attributes_grid->RowIndex ?>_attribute_value" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_value->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$classified_attributes_grid->ListOptions->Render("body", "right", $classified_attributes_grid->RowCnt);
?>
<script type="text/javascript">
fclassified_attributesgrid.UpdateOpts(<?php echo $classified_attributes_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($classified_attributes->CurrentMode == "add" || $classified_attributes->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $classified_attributes_grid->FormKeyCountName ?>" id="<?php echo $classified_attributes_grid->FormKeyCountName ?>" value="<?php echo $classified_attributes_grid->KeyCount ?>">
<?php echo $classified_attributes_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($classified_attributes->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $classified_attributes_grid->FormKeyCountName ?>" id="<?php echo $classified_attributes_grid->FormKeyCountName ?>" value="<?php echo $classified_attributes_grid->KeyCount ?>">
<?php echo $classified_attributes_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($classified_attributes->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fclassified_attributesgrid">
</div>
<?php

// Close recordset
if ($classified_attributes_grid->Recordset)
	$classified_attributes_grid->Recordset->Close();
?>
<?php if ($classified_attributes_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($classified_attributes_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($classified_attributes_grid->TotalRecs == 0 && $classified_attributes->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($classified_attributes_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($classified_attributes->Export == "") { ?>
<script type="text/javascript">
fclassified_attributesgrid.Init();
</script>
<?php } ?>
<?php
$classified_attributes_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$classified_attributes_grid->Page_Terminate();
?>
