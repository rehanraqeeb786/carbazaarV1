<?php include_once "adm_usersinfo.php" ?>
<?php

// Create page object
if (!isset($classified_faqs_grid)) $classified_faqs_grid = new cclassified_faqs_grid();

// Page init
$classified_faqs_grid->Page_Init();

// Page main
$classified_faqs_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$classified_faqs_grid->Page_Render();
?>
<?php if ($classified_faqs->Export == "") { ?>
<script type="text/javascript">

// Form object
var fclassified_faqsgrid = new ew_Form("fclassified_faqsgrid", "grid");
fclassified_faqsgrid.FormKeyCountName = '<?php echo $classified_faqs_grid->FormKeyCountName ?>';

// Validate form
fclassified_faqsgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_faqs->classified_id->FldCaption(), $classified_faqs->classified_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_classified_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($classified_faqs->classified_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_question_text");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_faqs->question_text->FldCaption(), $classified_faqs->question_text->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_answer_text");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_faqs->answer_text->FldCaption(), $classified_faqs->answer_text->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fclassified_faqsgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "classified_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "question_text", false)) return false;
	if (ew_ValueChanged(fobj, infix, "answer_text", false)) return false;
	return true;
}

// Form_CustomValidate event
fclassified_faqsgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclassified_faqsgrid.ValidateRequired = true;
<?php } else { ?>
fclassified_faqsgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclassified_faqsgrid.Lists["x_classified_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($classified_faqs->CurrentAction == "gridadd") {
	if ($classified_faqs->CurrentMode == "copy") {
		$bSelectLimit = $classified_faqs_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$classified_faqs_grid->TotalRecs = $classified_faqs->SelectRecordCount();
			$classified_faqs_grid->Recordset = $classified_faqs_grid->LoadRecordset($classified_faqs_grid->StartRec-1, $classified_faqs_grid->DisplayRecs);
		} else {
			if ($classified_faqs_grid->Recordset = $classified_faqs_grid->LoadRecordset())
				$classified_faqs_grid->TotalRecs = $classified_faqs_grid->Recordset->RecordCount();
		}
		$classified_faqs_grid->StartRec = 1;
		$classified_faqs_grid->DisplayRecs = $classified_faqs_grid->TotalRecs;
	} else {
		$classified_faqs->CurrentFilter = "0=1";
		$classified_faqs_grid->StartRec = 1;
		$classified_faqs_grid->DisplayRecs = $classified_faqs->GridAddRowCount;
	}
	$classified_faqs_grid->TotalRecs = $classified_faqs_grid->DisplayRecs;
	$classified_faqs_grid->StopRec = $classified_faqs_grid->DisplayRecs;
} else {
	$bSelectLimit = $classified_faqs_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($classified_faqs_grid->TotalRecs <= 0)
			$classified_faqs_grid->TotalRecs = $classified_faqs->SelectRecordCount();
	} else {
		if (!$classified_faqs_grid->Recordset && ($classified_faqs_grid->Recordset = $classified_faqs_grid->LoadRecordset()))
			$classified_faqs_grid->TotalRecs = $classified_faqs_grid->Recordset->RecordCount();
	}
	$classified_faqs_grid->StartRec = 1;
	$classified_faqs_grid->DisplayRecs = $classified_faqs_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$classified_faqs_grid->Recordset = $classified_faqs_grid->LoadRecordset($classified_faqs_grid->StartRec-1, $classified_faqs_grid->DisplayRecs);

	// Set no record found message
	if ($classified_faqs->CurrentAction == "" && $classified_faqs_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$classified_faqs_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($classified_faqs_grid->SearchWhere == "0=101")
			$classified_faqs_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$classified_faqs_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$classified_faqs_grid->RenderOtherOptions();
?>
<?php $classified_faqs_grid->ShowPageHeader(); ?>
<?php
$classified_faqs_grid->ShowMessage();
?>
<?php if ($classified_faqs_grid->TotalRecs > 0 || $classified_faqs->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fclassified_faqsgrid" class="ewForm form-inline">
<div id="gmp_classified_faqs" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_classified_faqsgrid" class="table ewTable">
<?php echo $classified_faqs->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$classified_faqs_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$classified_faqs_grid->RenderListOptions();

// Render list options (header, left)
$classified_faqs_grid->ListOptions->Render("header", "left");
?>
<?php if ($classified_faqs->classified_id->Visible) { // classified_id ?>
	<?php if ($classified_faqs->SortUrl($classified_faqs->classified_id) == "") { ?>
		<th data-name="classified_id"><div id="elh_classified_faqs_classified_id" class="classified_faqs_classified_id"><div class="ewTableHeaderCaption"><?php echo $classified_faqs->classified_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="classified_id"><div><div id="elh_classified_faqs_classified_id" class="classified_faqs_classified_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_faqs->classified_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_faqs->classified_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_faqs->classified_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_faqs->question_text->Visible) { // question_text ?>
	<?php if ($classified_faqs->SortUrl($classified_faqs->question_text) == "") { ?>
		<th data-name="question_text"><div id="elh_classified_faqs_question_text" class="classified_faqs_question_text"><div class="ewTableHeaderCaption"><?php echo $classified_faqs->question_text->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="question_text"><div><div id="elh_classified_faqs_question_text" class="classified_faqs_question_text">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_faqs->question_text->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_faqs->question_text->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_faqs->question_text->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_faqs->answer_text->Visible) { // answer_text ?>
	<?php if ($classified_faqs->SortUrl($classified_faqs->answer_text) == "") { ?>
		<th data-name="answer_text"><div id="elh_classified_faqs_answer_text" class="classified_faqs_answer_text"><div class="ewTableHeaderCaption"><?php echo $classified_faqs->answer_text->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="answer_text"><div><div id="elh_classified_faqs_answer_text" class="classified_faqs_answer_text">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_faqs->answer_text->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_faqs->answer_text->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_faqs->answer_text->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$classified_faqs_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$classified_faqs_grid->StartRec = 1;
$classified_faqs_grid->StopRec = $classified_faqs_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($classified_faqs_grid->FormKeyCountName) && ($classified_faqs->CurrentAction == "gridadd" || $classified_faqs->CurrentAction == "gridedit" || $classified_faqs->CurrentAction == "F")) {
		$classified_faqs_grid->KeyCount = $objForm->GetValue($classified_faqs_grid->FormKeyCountName);
		$classified_faqs_grid->StopRec = $classified_faqs_grid->StartRec + $classified_faqs_grid->KeyCount - 1;
	}
}
$classified_faqs_grid->RecCnt = $classified_faqs_grid->StartRec - 1;
if ($classified_faqs_grid->Recordset && !$classified_faqs_grid->Recordset->EOF) {
	$classified_faqs_grid->Recordset->MoveFirst();
	$bSelectLimit = $classified_faqs_grid->UseSelectLimit;
	if (!$bSelectLimit && $classified_faqs_grid->StartRec > 1)
		$classified_faqs_grid->Recordset->Move($classified_faqs_grid->StartRec - 1);
} elseif (!$classified_faqs->AllowAddDeleteRow && $classified_faqs_grid->StopRec == 0) {
	$classified_faqs_grid->StopRec = $classified_faqs->GridAddRowCount;
}

// Initialize aggregate
$classified_faqs->RowType = EW_ROWTYPE_AGGREGATEINIT;
$classified_faqs->ResetAttrs();
$classified_faqs_grid->RenderRow();
if ($classified_faqs->CurrentAction == "gridadd")
	$classified_faqs_grid->RowIndex = 0;
if ($classified_faqs->CurrentAction == "gridedit")
	$classified_faqs_grid->RowIndex = 0;
while ($classified_faqs_grid->RecCnt < $classified_faqs_grid->StopRec) {
	$classified_faqs_grid->RecCnt++;
	if (intval($classified_faqs_grid->RecCnt) >= intval($classified_faqs_grid->StartRec)) {
		$classified_faqs_grid->RowCnt++;
		if ($classified_faqs->CurrentAction == "gridadd" || $classified_faqs->CurrentAction == "gridedit" || $classified_faqs->CurrentAction == "F") {
			$classified_faqs_grid->RowIndex++;
			$objForm->Index = $classified_faqs_grid->RowIndex;
			if ($objForm->HasValue($classified_faqs_grid->FormActionName))
				$classified_faqs_grid->RowAction = strval($objForm->GetValue($classified_faqs_grid->FormActionName));
			elseif ($classified_faqs->CurrentAction == "gridadd")
				$classified_faqs_grid->RowAction = "insert";
			else
				$classified_faqs_grid->RowAction = "";
		}

		// Set up key count
		$classified_faqs_grid->KeyCount = $classified_faqs_grid->RowIndex;

		// Init row class and style
		$classified_faqs->ResetAttrs();
		$classified_faqs->CssClass = "";
		if ($classified_faqs->CurrentAction == "gridadd") {
			if ($classified_faqs->CurrentMode == "copy") {
				$classified_faqs_grid->LoadRowValues($classified_faqs_grid->Recordset); // Load row values
				$classified_faqs_grid->SetRecordKey($classified_faqs_grid->RowOldKey, $classified_faqs_grid->Recordset); // Set old record key
			} else {
				$classified_faqs_grid->LoadDefaultValues(); // Load default values
				$classified_faqs_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$classified_faqs_grid->LoadRowValues($classified_faqs_grid->Recordset); // Load row values
		}
		$classified_faqs->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($classified_faqs->CurrentAction == "gridadd") // Grid add
			$classified_faqs->RowType = EW_ROWTYPE_ADD; // Render add
		if ($classified_faqs->CurrentAction == "gridadd" && $classified_faqs->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$classified_faqs_grid->RestoreCurrentRowFormValues($classified_faqs_grid->RowIndex); // Restore form values
		if ($classified_faqs->CurrentAction == "gridedit") { // Grid edit
			if ($classified_faqs->EventCancelled) {
				$classified_faqs_grid->RestoreCurrentRowFormValues($classified_faqs_grid->RowIndex); // Restore form values
			}
			if ($classified_faqs_grid->RowAction == "insert")
				$classified_faqs->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$classified_faqs->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($classified_faqs->CurrentAction == "gridedit" && ($classified_faqs->RowType == EW_ROWTYPE_EDIT || $classified_faqs->RowType == EW_ROWTYPE_ADD) && $classified_faqs->EventCancelled) // Update failed
			$classified_faqs_grid->RestoreCurrentRowFormValues($classified_faqs_grid->RowIndex); // Restore form values
		if ($classified_faqs->RowType == EW_ROWTYPE_EDIT) // Edit row
			$classified_faqs_grid->EditRowCnt++;
		if ($classified_faqs->CurrentAction == "F") // Confirm row
			$classified_faqs_grid->RestoreCurrentRowFormValues($classified_faqs_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$classified_faqs->RowAttrs = array_merge($classified_faqs->RowAttrs, array('data-rowindex'=>$classified_faqs_grid->RowCnt, 'id'=>'r' . $classified_faqs_grid->RowCnt . '_classified_faqs', 'data-rowtype'=>$classified_faqs->RowType));

		// Render row
		$classified_faqs_grid->RenderRow();

		// Render list options
		$classified_faqs_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($classified_faqs_grid->RowAction <> "delete" && $classified_faqs_grid->RowAction <> "insertdelete" && !($classified_faqs_grid->RowAction == "insert" && $classified_faqs->CurrentAction == "F" && $classified_faqs_grid->EmptyRow())) {
?>
	<tr<?php echo $classified_faqs->RowAttributes() ?>>
<?php

// Render list options (body, left)
$classified_faqs_grid->ListOptions->Render("body", "left", $classified_faqs_grid->RowCnt);
?>
	<?php if ($classified_faqs->classified_id->Visible) { // classified_id ?>
		<td data-name="classified_id"<?php echo $classified_faqs->classified_id->CellAttributes() ?>>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($classified_faqs->classified_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_classified_id" class="form-group classified_faqs_classified_id">
<span<?php echo $classified_faqs->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_faqs->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" name="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_classified_id" class="form-group classified_faqs_classified_id">
<?php
$wrkonchange = trim(" " . @$classified_faqs->classified_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_faqs->classified_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_faqs_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="sv_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo $classified_faqs->classified_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_faqs->classified_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_faqs->classified_id->getPlaceHolder()) ?>"<?php echo $classified_faqs->classified_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_classified_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_faqs->classified_id->DisplayValueSeparator) ? json_encode($classified_faqs->classified_id->DisplayValueSeparator) : $classified_faqs->classified_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_faqs->Lookup_Selecting($classified_faqs->classified_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="q_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_faqsgrid.CreateAutoSuggest({"id":"x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id","forceSelect":false});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="classified_faqs" data-field="x_classified_id" name="o<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="o<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->OldValue) ?>">
<?php } ?>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($classified_faqs->classified_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_classified_id" class="form-group classified_faqs_classified_id">
<span<?php echo $classified_faqs->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_faqs->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" name="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_classified_id" class="form-group classified_faqs_classified_id">
<?php
$wrkonchange = trim(" " . @$classified_faqs->classified_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_faqs->classified_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_faqs_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="sv_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo $classified_faqs->classified_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_faqs->classified_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_faqs->classified_id->getPlaceHolder()) ?>"<?php echo $classified_faqs->classified_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_classified_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_faqs->classified_id->DisplayValueSeparator) ? json_encode($classified_faqs->classified_id->DisplayValueSeparator) : $classified_faqs->classified_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_faqs->Lookup_Selecting($classified_faqs->classified_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="q_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_faqsgrid.CreateAutoSuggest({"id":"x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_classified_id" class="classified_faqs_classified_id">
<span<?php echo $classified_faqs->classified_id->ViewAttributes() ?>>
<?php echo $classified_faqs->classified_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_classified_id" name="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->FormValue) ?>">
<input type="hidden" data-table="classified_faqs" data-field="x_classified_id" name="o<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="o<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $classified_faqs_grid->PageObjName . "_row_" . $classified_faqs_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="classified_faqs" data-field="x_ID" name="x<?php echo $classified_faqs_grid->RowIndex ?>_ID" id="x<?php echo $classified_faqs_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_faqs->ID->CurrentValue) ?>">
<input type="hidden" data-table="classified_faqs" data-field="x_ID" name="o<?php echo $classified_faqs_grid->RowIndex ?>_ID" id="o<?php echo $classified_faqs_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_faqs->ID->OldValue) ?>">
<?php } ?>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_EDIT || $classified_faqs->CurrentMode == "edit") { ?>
<input type="hidden" data-table="classified_faqs" data-field="x_ID" name="x<?php echo $classified_faqs_grid->RowIndex ?>_ID" id="x<?php echo $classified_faqs_grid->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($classified_faqs->ID->CurrentValue) ?>">
<?php } ?>
	<?php if ($classified_faqs->question_text->Visible) { // question_text ?>
		<td data-name="question_text"<?php echo $classified_faqs->question_text->CellAttributes() ?>>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_question_text" class="form-group classified_faqs_question_text">
<textarea data-table="classified_faqs" data-field="x_question_text" name="x<?php echo $classified_faqs_grid->RowIndex ?>_question_text" id="x<?php echo $classified_faqs_grid->RowIndex ?>_question_text" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($classified_faqs->question_text->getPlaceHolder()) ?>"<?php echo $classified_faqs->question_text->EditAttributes() ?>><?php echo $classified_faqs->question_text->EditValue ?></textarea>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_question_text" name="o<?php echo $classified_faqs_grid->RowIndex ?>_question_text" id="o<?php echo $classified_faqs_grid->RowIndex ?>_question_text" value="<?php echo ew_HtmlEncode($classified_faqs->question_text->OldValue) ?>">
<?php } ?>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_question_text" class="form-group classified_faqs_question_text">
<textarea data-table="classified_faqs" data-field="x_question_text" name="x<?php echo $classified_faqs_grid->RowIndex ?>_question_text" id="x<?php echo $classified_faqs_grid->RowIndex ?>_question_text" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($classified_faqs->question_text->getPlaceHolder()) ?>"<?php echo $classified_faqs->question_text->EditAttributes() ?>><?php echo $classified_faqs->question_text->EditValue ?></textarea>
</span>
<?php } ?>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_question_text" class="classified_faqs_question_text">
<span<?php echo $classified_faqs->question_text->ViewAttributes() ?>>
<?php echo $classified_faqs->question_text->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_question_text" name="x<?php echo $classified_faqs_grid->RowIndex ?>_question_text" id="x<?php echo $classified_faqs_grid->RowIndex ?>_question_text" value="<?php echo ew_HtmlEncode($classified_faqs->question_text->FormValue) ?>">
<input type="hidden" data-table="classified_faqs" data-field="x_question_text" name="o<?php echo $classified_faqs_grid->RowIndex ?>_question_text" id="o<?php echo $classified_faqs_grid->RowIndex ?>_question_text" value="<?php echo ew_HtmlEncode($classified_faqs->question_text->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($classified_faqs->answer_text->Visible) { // answer_text ?>
		<td data-name="answer_text"<?php echo $classified_faqs->answer_text->CellAttributes() ?>>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_answer_text" class="form-group classified_faqs_answer_text">
<textarea data-table="classified_faqs" data-field="x_answer_text" name="x<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" id="x<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($classified_faqs->answer_text->getPlaceHolder()) ?>"<?php echo $classified_faqs->answer_text->EditAttributes() ?>><?php echo $classified_faqs->answer_text->EditValue ?></textarea>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_answer_text" name="o<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" id="o<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" value="<?php echo ew_HtmlEncode($classified_faqs->answer_text->OldValue) ?>">
<?php } ?>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_answer_text" class="form-group classified_faqs_answer_text">
<textarea data-table="classified_faqs" data-field="x_answer_text" name="x<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" id="x<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($classified_faqs->answer_text->getPlaceHolder()) ?>"<?php echo $classified_faqs->answer_text->EditAttributes() ?>><?php echo $classified_faqs->answer_text->EditValue ?></textarea>
</span>
<?php } ?>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $classified_faqs_grid->RowCnt ?>_classified_faqs_answer_text" class="classified_faqs_answer_text">
<span<?php echo $classified_faqs->answer_text->ViewAttributes() ?>>
<?php echo $classified_faqs->answer_text->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_answer_text" name="x<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" id="x<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" value="<?php echo ew_HtmlEncode($classified_faqs->answer_text->FormValue) ?>">
<input type="hidden" data-table="classified_faqs" data-field="x_answer_text" name="o<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" id="o<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" value="<?php echo ew_HtmlEncode($classified_faqs->answer_text->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$classified_faqs_grid->ListOptions->Render("body", "right", $classified_faqs_grid->RowCnt);
?>
	</tr>
<?php if ($classified_faqs->RowType == EW_ROWTYPE_ADD || $classified_faqs->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fclassified_faqsgrid.UpdateOpts(<?php echo $classified_faqs_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($classified_faqs->CurrentAction <> "gridadd" || $classified_faqs->CurrentMode == "copy")
		if (!$classified_faqs_grid->Recordset->EOF) $classified_faqs_grid->Recordset->MoveNext();
}
?>
<?php
	if ($classified_faqs->CurrentMode == "add" || $classified_faqs->CurrentMode == "copy" || $classified_faqs->CurrentMode == "edit") {
		$classified_faqs_grid->RowIndex = '$rowindex$';
		$classified_faqs_grid->LoadDefaultValues();

		// Set row properties
		$classified_faqs->ResetAttrs();
		$classified_faqs->RowAttrs = array_merge($classified_faqs->RowAttrs, array('data-rowindex'=>$classified_faqs_grid->RowIndex, 'id'=>'r0_classified_faqs', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($classified_faqs->RowAttrs["class"], "ewTemplate");
		$classified_faqs->RowType = EW_ROWTYPE_ADD;

		// Render row
		$classified_faqs_grid->RenderRow();

		// Render list options
		$classified_faqs_grid->RenderListOptions();
		$classified_faqs_grid->StartRowCnt = 0;
?>
	<tr<?php echo $classified_faqs->RowAttributes() ?>>
<?php

// Render list options (body, left)
$classified_faqs_grid->ListOptions->Render("body", "left", $classified_faqs_grid->RowIndex);
?>
	<?php if ($classified_faqs->classified_id->Visible) { // classified_id ?>
		<td data-name="classified_id">
<?php if ($classified_faqs->CurrentAction <> "F") { ?>
<?php if ($classified_faqs->classified_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_classified_faqs_classified_id" class="form-group classified_faqs_classified_id">
<span<?php echo $classified_faqs->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_faqs->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" name="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_classified_faqs_classified_id" class="form-group classified_faqs_classified_id">
<?php
$wrkonchange = trim(" " . @$classified_faqs->classified_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_faqs->classified_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" style="white-space: nowrap; z-index: <?php echo (9000 - $classified_faqs_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="sv_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo $classified_faqs->classified_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_faqs->classified_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_faqs->classified_id->getPlaceHolder()) ?>"<?php echo $classified_faqs->classified_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_classified_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_faqs->classified_id->DisplayValueSeparator) ? json_encode($classified_faqs->classified_id->DisplayValueSeparator) : $classified_faqs->classified_id->DisplayValueSeparator) ?>" name="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_faqs->Lookup_Selecting($classified_faqs->classified_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="q_x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_faqsgrid.CreateAutoSuggest({"id":"x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_classified_faqs_classified_id" class="form-group classified_faqs_classified_id">
<span<?php echo $classified_faqs->classified_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_faqs->classified_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_classified_id" name="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="x<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="classified_faqs" data-field="x_classified_id" name="o<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" id="o<?php echo $classified_faqs_grid->RowIndex ?>_classified_id" value="<?php echo ew_HtmlEncode($classified_faqs->classified_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($classified_faqs->question_text->Visible) { // question_text ?>
		<td data-name="question_text">
<?php if ($classified_faqs->CurrentAction <> "F") { ?>
<span id="el$rowindex$_classified_faqs_question_text" class="form-group classified_faqs_question_text">
<textarea data-table="classified_faqs" data-field="x_question_text" name="x<?php echo $classified_faqs_grid->RowIndex ?>_question_text" id="x<?php echo $classified_faqs_grid->RowIndex ?>_question_text" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($classified_faqs->question_text->getPlaceHolder()) ?>"<?php echo $classified_faqs->question_text->EditAttributes() ?>><?php echo $classified_faqs->question_text->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el$rowindex$_classified_faqs_question_text" class="form-group classified_faqs_question_text">
<span<?php echo $classified_faqs->question_text->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_faqs->question_text->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_question_text" name="x<?php echo $classified_faqs_grid->RowIndex ?>_question_text" id="x<?php echo $classified_faqs_grid->RowIndex ?>_question_text" value="<?php echo ew_HtmlEncode($classified_faqs->question_text->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="classified_faqs" data-field="x_question_text" name="o<?php echo $classified_faqs_grid->RowIndex ?>_question_text" id="o<?php echo $classified_faqs_grid->RowIndex ?>_question_text" value="<?php echo ew_HtmlEncode($classified_faqs->question_text->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($classified_faqs->answer_text->Visible) { // answer_text ?>
		<td data-name="answer_text">
<?php if ($classified_faqs->CurrentAction <> "F") { ?>
<span id="el$rowindex$_classified_faqs_answer_text" class="form-group classified_faqs_answer_text">
<textarea data-table="classified_faqs" data-field="x_answer_text" name="x<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" id="x<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($classified_faqs->answer_text->getPlaceHolder()) ?>"<?php echo $classified_faqs->answer_text->EditAttributes() ?>><?php echo $classified_faqs->answer_text->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el$rowindex$_classified_faqs_answer_text" class="form-group classified_faqs_answer_text">
<span<?php echo $classified_faqs->answer_text->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $classified_faqs->answer_text->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="classified_faqs" data-field="x_answer_text" name="x<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" id="x<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" value="<?php echo ew_HtmlEncode($classified_faqs->answer_text->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="classified_faqs" data-field="x_answer_text" name="o<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" id="o<?php echo $classified_faqs_grid->RowIndex ?>_answer_text" value="<?php echo ew_HtmlEncode($classified_faqs->answer_text->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$classified_faqs_grid->ListOptions->Render("body", "right", $classified_faqs_grid->RowCnt);
?>
<script type="text/javascript">
fclassified_faqsgrid.UpdateOpts(<?php echo $classified_faqs_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($classified_faqs->CurrentMode == "add" || $classified_faqs->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $classified_faqs_grid->FormKeyCountName ?>" id="<?php echo $classified_faqs_grid->FormKeyCountName ?>" value="<?php echo $classified_faqs_grid->KeyCount ?>">
<?php echo $classified_faqs_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($classified_faqs->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $classified_faqs_grid->FormKeyCountName ?>" id="<?php echo $classified_faqs_grid->FormKeyCountName ?>" value="<?php echo $classified_faqs_grid->KeyCount ?>">
<?php echo $classified_faqs_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($classified_faqs->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fclassified_faqsgrid">
</div>
<?php

// Close recordset
if ($classified_faqs_grid->Recordset)
	$classified_faqs_grid->Recordset->Close();
?>
<?php if ($classified_faqs_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($classified_faqs_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($classified_faqs_grid->TotalRecs == 0 && $classified_faqs->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($classified_faqs_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($classified_faqs->Export == "") { ?>
<script type="text/javascript">
fclassified_faqsgrid.Init();
</script>
<?php } ?>
<?php
$classified_faqs_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$classified_faqs_grid->Page_Terminate();
?>
