<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_classified_attribureinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_classified_attribure_edit = NULL; // Initialize page object first

class ccfg_classified_attribure_edit extends ccfg_classified_attribure {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_classified_attribure';

	// Page object name
	var $PageObjName = 'cfg_classified_attribure_edit';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (cfg_classified_attribure)
		if (!isset($GLOBALS["cfg_classified_attribure"]) || get_class($GLOBALS["cfg_classified_attribure"]) == "ccfg_classified_attribure") {
			$GLOBALS["cfg_classified_attribure"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_classified_attribure"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cfg_classified_attribure', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (adm_users)
		if (!isset($UserTable)) {
			$UserTable = new cadm_users();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("cfg_classified_attriburelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $cfg_classified_attribure;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_classified_attribure);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["ID"] <> "") {
			$this->ID->setQueryStringValue($_GET["ID"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->ID->CurrentValue == "")
			$this->Page_Terminate("cfg_classified_attriburelist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("cfg_classified_attriburelist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "cfg_classified_attriburelist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->parent_attribute_id->FldIsDetailKey) {
			$this->parent_attribute_id->setFormValue($objForm->GetValue("x_parent_attribute_id"));
		}
		if (!$this->attribute_title->FldIsDetailKey) {
			$this->attribute_title->setFormValue($objForm->GetValue("x_attribute_title"));
		}
		if (!$this->attribute_type->FldIsDetailKey) {
			$this->attribute_type->setFormValue($objForm->GetValue("x_attribute_type"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->ID->FldIsDetailKey)
			$this->ID->setFormValue($objForm->GetValue("x_ID"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->ID->CurrentValue = $this->ID->FormValue;
		$this->parent_attribute_id->CurrentValue = $this->parent_attribute_id->FormValue;
		$this->attribute_title->CurrentValue = $this->attribute_title->FormValue;
		$this->attribute_type->CurrentValue = $this->attribute_type->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->ID->setDbValue($rs->fields('ID'));
		$this->parent_attribute_id->setDbValue($rs->fields('parent_attribute_id'));
		$this->attribute_title->setDbValue($rs->fields('attribute_title'));
		$this->attribute_type->setDbValue($rs->fields('attribute_type'));
		$this->status->setDbValue($rs->fields('status'));
		$this->ETD->setDbValue($rs->fields('ETD'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->parent_attribute_id->DbValue = $row['parent_attribute_id'];
		$this->attribute_title->DbValue = $row['attribute_title'];
		$this->attribute_type->DbValue = $row['attribute_type'];
		$this->status->DbValue = $row['status'];
		$this->ETD->DbValue = $row['ETD'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// ID
		// parent_attribute_id
		// attribute_title
		// attribute_type
		// status
		// ETD

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// parent_attribute_id
		$this->parent_attribute_id->ViewValue = $this->parent_attribute_id->CurrentValue;
		if (strval($this->parent_attribute_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->parent_attribute_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_classified_attribure`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->parent_attribute_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->parent_attribute_id->ViewValue = $this->parent_attribute_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->parent_attribute_id->ViewValue = $this->parent_attribute_id->CurrentValue;
			}
		} else {
			$this->parent_attribute_id->ViewValue = NULL;
		}
		$this->parent_attribute_id->ViewCustomAttributes = "";

		// attribute_title
		$this->attribute_title->ViewValue = $this->attribute_title->CurrentValue;
		$this->attribute_title->ViewCustomAttributes = "";

		// attribute_type
		if (strval($this->attribute_type->CurrentValue) <> "") {
			$this->attribute_type->ViewValue = $this->attribute_type->OptionCaption($this->attribute_type->CurrentValue);
		} else {
			$this->attribute_type->ViewValue = NULL;
		}
		$this->attribute_type->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// parent_attribute_id
			$this->parent_attribute_id->LinkCustomAttributes = "";
			$this->parent_attribute_id->HrefValue = "";
			$this->parent_attribute_id->TooltipValue = "";

			// attribute_title
			$this->attribute_title->LinkCustomAttributes = "";
			$this->attribute_title->HrefValue = "";
			$this->attribute_title->TooltipValue = "";

			// attribute_type
			$this->attribute_type->LinkCustomAttributes = "";
			$this->attribute_type->HrefValue = "";
			$this->attribute_type->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// parent_attribute_id
			$this->parent_attribute_id->EditAttrs["class"] = "form-control";
			$this->parent_attribute_id->EditCustomAttributes = "";
			$this->parent_attribute_id->EditValue = ew_HtmlEncode($this->parent_attribute_id->CurrentValue);
			if (strval($this->parent_attribute_id->CurrentValue) <> "") {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->parent_attribute_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_classified_attribure`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->parent_attribute_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->parent_attribute_id->EditValue = $this->parent_attribute_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->parent_attribute_id->EditValue = ew_HtmlEncode($this->parent_attribute_id->CurrentValue);
				}
			} else {
				$this->parent_attribute_id->EditValue = NULL;
			}
			$this->parent_attribute_id->PlaceHolder = ew_RemoveHtml($this->parent_attribute_id->FldCaption());

			// attribute_title
			$this->attribute_title->EditAttrs["class"] = "form-control";
			$this->attribute_title->EditCustomAttributes = "";
			$this->attribute_title->EditValue = ew_HtmlEncode($this->attribute_title->CurrentValue);
			$this->attribute_title->PlaceHolder = ew_RemoveHtml($this->attribute_title->FldCaption());

			// attribute_type
			$this->attribute_type->EditCustomAttributes = "";
			$this->attribute_type->EditValue = $this->attribute_type->Options(FALSE);

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Edit refer script
			// parent_attribute_id

			$this->parent_attribute_id->LinkCustomAttributes = "";
			$this->parent_attribute_id->HrefValue = "";

			// attribute_title
			$this->attribute_title->LinkCustomAttributes = "";
			$this->attribute_title->HrefValue = "";

			// attribute_type
			$this->attribute_type->LinkCustomAttributes = "";
			$this->attribute_type->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->parent_attribute_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->parent_attribute_id->FldErrMsg());
		}
		if (!$this->attribute_title->FldIsDetailKey && !is_null($this->attribute_title->FormValue) && $this->attribute_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->attribute_title->FldCaption(), $this->attribute_title->ReqErrMsg));
		}
		if ($this->attribute_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->attribute_type->FldCaption(), $this->attribute_type->ReqErrMsg));
		}
		if ($this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// parent_attribute_id
			$this->parent_attribute_id->SetDbValueDef($rsnew, $this->parent_attribute_id->CurrentValue, NULL, $this->parent_attribute_id->ReadOnly);

			// attribute_title
			$this->attribute_title->SetDbValueDef($rsnew, $this->attribute_title->CurrentValue, "", $this->attribute_title->ReadOnly);

			// attribute_type
			$this->attribute_type->SetDbValueDef($rsnew, $this->attribute_type->CurrentValue, "", $this->attribute_type->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, ((strval($this->status->CurrentValue) == "1") ? "1" : "0"), 0, $this->status->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_classified_attriburelist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($cfg_classified_attribure_edit)) $cfg_classified_attribure_edit = new ccfg_classified_attribure_edit();

// Page init
$cfg_classified_attribure_edit->Page_Init();

// Page main
$cfg_classified_attribure_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_classified_attribure_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fcfg_classified_attribureedit = new ew_Form("fcfg_classified_attribureedit", "edit");

// Validate form
fcfg_classified_attribureedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_parent_attribute_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_classified_attribure->parent_attribute_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_attribute_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_classified_attribure->attribute_title->FldCaption(), $cfg_classified_attribure->attribute_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_attribute_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_classified_attribure->attribute_type->FldCaption(), $cfg_classified_attribure->attribute_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_classified_attribure->status->FldCaption(), $cfg_classified_attribure->status->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fcfg_classified_attribureedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_classified_attribureedit.ValidateRequired = true;
<?php } else { ?>
fcfg_classified_attribureedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_classified_attribureedit.Lists["x_parent_attribute_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_attribute_title","x_attribute_type","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_classified_attribureedit.Lists["x_attribute_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_classified_attribureedit.Lists["x_attribute_type"].Options = <?php echo json_encode($cfg_classified_attribure->attribute_type->Options()) ?>;
fcfg_classified_attribureedit.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_classified_attribureedit.Lists["x_status"].Options = <?php echo json_encode($cfg_classified_attribure->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $cfg_classified_attribure_edit->ShowPageHeader(); ?>
<?php
$cfg_classified_attribure_edit->ShowMessage();
?>
<form name="fcfg_classified_attribureedit" id="fcfg_classified_attribureedit" class="<?php echo $cfg_classified_attribure_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_classified_attribure_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_classified_attribure_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_classified_attribure">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($cfg_classified_attribure->parent_attribute_id->Visible) { // parent_attribute_id ?>
	<div id="r_parent_attribute_id" class="form-group">
		<label id="elh_cfg_classified_attribure_parent_attribute_id" class="col-sm-2 control-label ewLabel"><?php echo $cfg_classified_attribure->parent_attribute_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_classified_attribure->parent_attribute_id->CellAttributes() ?>>
<span id="el_cfg_classified_attribure_parent_attribute_id">
<?php
$wrkonchange = trim(" " . @$cfg_classified_attribure->parent_attribute_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$cfg_classified_attribure->parent_attribute_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_parent_attribute_id" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_parent_attribute_id" id="sv_x_parent_attribute_id" value="<?php echo $cfg_classified_attribure->parent_attribute_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($cfg_classified_attribure->parent_attribute_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($cfg_classified_attribure->parent_attribute_id->getPlaceHolder()) ?>"<?php echo $cfg_classified_attribure->parent_attribute_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="cfg_classified_attribure" data-field="x_parent_attribute_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_classified_attribure->parent_attribute_id->DisplayValueSeparator) ? json_encode($cfg_classified_attribure->parent_attribute_id->DisplayValueSeparator) : $cfg_classified_attribure->parent_attribute_id->DisplayValueSeparator) ?>" name="x_parent_attribute_id" id="x_parent_attribute_id" value="<?php echo ew_HtmlEncode($cfg_classified_attribure->parent_attribute_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld` FROM `cfg_classified_attribure`";
$sWhereWrk = "`attribute_title` LIKE '{query_value}%' OR CONCAT(`attribute_title`,'" . ew_ValueSeparator(1, $Page->parent_attribute_id) . "',`attribute_type`) LIKE '{query_value}%'";
$cfg_classified_attribure->Lookup_Selecting($cfg_classified_attribure->parent_attribute_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_parent_attribute_id" id="q_x_parent_attribute_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fcfg_classified_attribureedit.CreateAutoSuggest({"id":"x_parent_attribute_id","forceSelect":false});
</script>
</span>
<?php echo $cfg_classified_attribure->parent_attribute_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_classified_attribure->attribute_title->Visible) { // attribute_title ?>
	<div id="r_attribute_title" class="form-group">
		<label id="elh_cfg_classified_attribure_attribute_title" for="x_attribute_title" class="col-sm-2 control-label ewLabel"><?php echo $cfg_classified_attribure->attribute_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_classified_attribure->attribute_title->CellAttributes() ?>>
<span id="el_cfg_classified_attribure_attribute_title">
<input type="text" data-table="cfg_classified_attribure" data-field="x_attribute_title" name="x_attribute_title" id="x_attribute_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_classified_attribure->attribute_title->getPlaceHolder()) ?>" value="<?php echo $cfg_classified_attribure->attribute_title->EditValue ?>"<?php echo $cfg_classified_attribure->attribute_title->EditAttributes() ?>>
</span>
<?php echo $cfg_classified_attribure->attribute_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_classified_attribure->attribute_type->Visible) { // attribute_type ?>
	<div id="r_attribute_type" class="form-group">
		<label id="elh_cfg_classified_attribure_attribute_type" class="col-sm-2 control-label ewLabel"><?php echo $cfg_classified_attribure->attribute_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_classified_attribure->attribute_type->CellAttributes() ?>>
<span id="el_cfg_classified_attribure_attribute_type">
<div id="tp_x_attribute_type" class="ewTemplate"><input type="radio" data-table="cfg_classified_attribure" data-field="x_attribute_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_classified_attribure->attribute_type->DisplayValueSeparator) ? json_encode($cfg_classified_attribure->attribute_type->DisplayValueSeparator) : $cfg_classified_attribure->attribute_type->DisplayValueSeparator) ?>" name="x_attribute_type" id="x_attribute_type" value="{value}"<?php echo $cfg_classified_attribure->attribute_type->EditAttributes() ?>></div>
<div id="dsl_x_attribute_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_classified_attribure->attribute_type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_classified_attribure->attribute_type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_classified_attribure" data-field="x_attribute_type" name="x_attribute_type" id="x_attribute_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_classified_attribure->attribute_type->EditAttributes() ?>><?php echo $cfg_classified_attribure->attribute_type->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_classified_attribure->attribute_type->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_classified_attribure" data-field="x_attribute_type" name="x_attribute_type" id="x_attribute_type_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_classified_attribure->attribute_type->CurrentValue) ?>" checked<?php echo $cfg_classified_attribure->attribute_type->EditAttributes() ?>><?php echo $cfg_classified_attribure->attribute_type->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $cfg_classified_attribure->attribute_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_classified_attribure->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_cfg_classified_attribure_status" class="col-sm-2 control-label ewLabel"><?php echo $cfg_classified_attribure->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_classified_attribure->status->CellAttributes() ?>>
<span id="el_cfg_classified_attribure_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="cfg_classified_attribure" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_classified_attribure->status->DisplayValueSeparator) ? json_encode($cfg_classified_attribure->status->DisplayValueSeparator) : $cfg_classified_attribure->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $cfg_classified_attribure->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_classified_attribure->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_classified_attribure->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_classified_attribure" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_classified_attribure->status->EditAttributes() ?>><?php echo $cfg_classified_attribure->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_classified_attribure->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_classified_attribure" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_classified_attribure->status->CurrentValue) ?>" checked<?php echo $cfg_classified_attribure->status->EditAttributes() ?>><?php echo $cfg_classified_attribure->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $cfg_classified_attribure->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="cfg_classified_attribure" data-field="x_ID" name="x_ID" id="x_ID" value="<?php echo ew_HtmlEncode($cfg_classified_attribure->ID->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cfg_classified_attribure_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcfg_classified_attribureedit.Init();
</script>
<?php
$cfg_classified_attribure_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_classified_attribure_edit->Page_Terminate();
?>
