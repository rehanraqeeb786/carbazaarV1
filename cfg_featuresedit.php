<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_featuresinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_features_edit = NULL; // Initialize page object first

class ccfg_features_edit extends ccfg_features {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_features';

	// Page object name
	var $PageObjName = 'cfg_features_edit';

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

		// Table object (cfg_features)
		if (!isset($GLOBALS["cfg_features"]) || get_class($GLOBALS["cfg_features"]) == "ccfg_features") {
			$GLOBALS["cfg_features"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_features"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cfg_features', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_featureslist.php"));
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
		global $EW_EXPORT, $cfg_features;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_features);
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
			$this->Page_Terminate("cfg_featureslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("cfg_featureslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "cfg_featureslist.php")
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
		$this->feature_icon_link->Upload->Index = $objForm->Index;
		$this->feature_icon_link->Upload->UploadFile();
		$this->feature_icon_link->CurrentValue = $this->feature_icon_link->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->feature_name->FldIsDetailKey) {
			$this->feature_name->setFormValue($objForm->GetValue("x_feature_name"));
		}
		if (!$this->details->FldIsDetailKey) {
			$this->details->setFormValue($objForm->GetValue("x_details"));
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
		$this->feature_name->CurrentValue = $this->feature_name->FormValue;
		$this->details->CurrentValue = $this->details->FormValue;
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
		$this->feature_name->setDbValue($rs->fields('feature_name'));
		$this->feature_icon_link->Upload->DbValue = $rs->fields('feature_icon_link');
		$this->feature_icon_link->CurrentValue = $this->feature_icon_link->Upload->DbValue;
		$this->details->setDbValue($rs->fields('details'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->feature_name->DbValue = $row['feature_name'];
		$this->feature_icon_link->Upload->DbValue = $row['feature_icon_link'];
		$this->details->DbValue = $row['details'];
		$this->status->DbValue = $row['status'];
		$this->created_at->DbValue = $row['created_at'];
		$this->updated_at->DbValue = $row['updated_at'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// ID
		// feature_name
		// feature_icon_link
		// details
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// feature_name
		$this->feature_name->ViewValue = $this->feature_name->CurrentValue;
		$this->feature_name->ViewCustomAttributes = "";

		// feature_icon_link
		if (!ew_Empty($this->feature_icon_link->Upload->DbValue)) {
			$this->feature_icon_link->ViewValue = $this->feature_icon_link->Upload->DbValue;
		} else {
			$this->feature_icon_link->ViewValue = "";
		}
		$this->feature_icon_link->ViewCustomAttributes = "";

		// details
		$this->details->ViewValue = $this->details->CurrentValue;
		$this->details->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// feature_name
			$this->feature_name->LinkCustomAttributes = "";
			$this->feature_name->HrefValue = "";
			$this->feature_name->TooltipValue = "";

			// feature_icon_link
			$this->feature_icon_link->LinkCustomAttributes = "";
			$this->feature_icon_link->HrefValue = "";
			$this->feature_icon_link->HrefValue2 = $this->feature_icon_link->UploadPath . $this->feature_icon_link->Upload->DbValue;
			$this->feature_icon_link->TooltipValue = "";

			// details
			$this->details->LinkCustomAttributes = "";
			$this->details->HrefValue = "";
			$this->details->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// feature_name
			$this->feature_name->EditAttrs["class"] = "form-control";
			$this->feature_name->EditCustomAttributes = "";
			$this->feature_name->EditValue = ew_HtmlEncode($this->feature_name->CurrentValue);
			$this->feature_name->PlaceHolder = ew_RemoveHtml($this->feature_name->FldCaption());

			// feature_icon_link
			$this->feature_icon_link->EditAttrs["class"] = "form-control";
			$this->feature_icon_link->EditCustomAttributes = "";
			if (!ew_Empty($this->feature_icon_link->Upload->DbValue)) {
				$this->feature_icon_link->EditValue = $this->feature_icon_link->Upload->DbValue;
			} else {
				$this->feature_icon_link->EditValue = "";
			}
			if (!ew_Empty($this->feature_icon_link->CurrentValue))
				$this->feature_icon_link->Upload->FileName = $this->feature_icon_link->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->feature_icon_link);

			// details
			$this->details->EditAttrs["class"] = "form-control";
			$this->details->EditCustomAttributes = "";
			$this->details->EditValue = ew_HtmlEncode($this->details->CurrentValue);
			$this->details->PlaceHolder = ew_RemoveHtml($this->details->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Edit refer script
			// feature_name

			$this->feature_name->LinkCustomAttributes = "";
			$this->feature_name->HrefValue = "";

			// feature_icon_link
			$this->feature_icon_link->LinkCustomAttributes = "";
			$this->feature_icon_link->HrefValue = "";
			$this->feature_icon_link->HrefValue2 = $this->feature_icon_link->UploadPath . $this->feature_icon_link->Upload->DbValue;

			// details
			$this->details->LinkCustomAttributes = "";
			$this->details->HrefValue = "";

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
		if (!$this->feature_name->FldIsDetailKey && !is_null($this->feature_name->FormValue) && $this->feature_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->feature_name->FldCaption(), $this->feature_name->ReqErrMsg));
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

			// feature_name
			$this->feature_name->SetDbValueDef($rsnew, $this->feature_name->CurrentValue, "", $this->feature_name->ReadOnly);

			// feature_icon_link
			if ($this->feature_icon_link->Visible && !$this->feature_icon_link->ReadOnly && !$this->feature_icon_link->Upload->KeepFile) {
				$this->feature_icon_link->Upload->DbValue = $rsold['feature_icon_link']; // Get original value
				if ($this->feature_icon_link->Upload->FileName == "") {
					$rsnew['feature_icon_link'] = NULL;
				} else {
					$rsnew['feature_icon_link'] = $this->feature_icon_link->Upload->FileName;
				}
			}

			// details
			$this->details->SetDbValueDef($rsnew, $this->details->CurrentValue, NULL, $this->details->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, ((strval($this->status->CurrentValue) == "1") ? "1" : "0"), 0, $this->status->ReadOnly);
			if ($this->feature_icon_link->Visible && !$this->feature_icon_link->Upload->KeepFile) {
				if (!ew_Empty($this->feature_icon_link->Upload->Value)) {
					$rsnew['feature_icon_link'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->feature_icon_link->UploadPath), $rsnew['feature_icon_link']); // Get new file name
				}
			}

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
					if ($this->feature_icon_link->Visible && !$this->feature_icon_link->Upload->KeepFile) {
						if (!ew_Empty($this->feature_icon_link->Upload->Value)) {
							$this->feature_icon_link->Upload->SaveToFile($this->feature_icon_link->UploadPath, $rsnew['feature_icon_link'], TRUE);
						}
					}
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

		// feature_icon_link
		ew_CleanUploadTempPath($this->feature_icon_link, $this->feature_icon_link->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_featureslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_features_edit)) $cfg_features_edit = new ccfg_features_edit();

// Page init
$cfg_features_edit->Page_Init();

// Page main
$cfg_features_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_features_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fcfg_featuresedit = new ew_Form("fcfg_featuresedit", "edit");

// Validate form
fcfg_featuresedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_feature_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_features->feature_name->FldCaption(), $cfg_features->feature_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_features->status->FldCaption(), $cfg_features->status->ReqErrMsg)) ?>");

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
fcfg_featuresedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_featuresedit.ValidateRequired = true;
<?php } else { ?>
fcfg_featuresedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_featuresedit.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_featuresedit.Lists["x_status"].Options = <?php echo json_encode($cfg_features->status->Options()) ?>;

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
<?php $cfg_features_edit->ShowPageHeader(); ?>
<?php
$cfg_features_edit->ShowMessage();
?>
<form name="fcfg_featuresedit" id="fcfg_featuresedit" class="<?php echo $cfg_features_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_features_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_features_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_features">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($cfg_features->feature_name->Visible) { // feature_name ?>
	<div id="r_feature_name" class="form-group">
		<label id="elh_cfg_features_feature_name" for="x_feature_name" class="col-sm-2 control-label ewLabel"><?php echo $cfg_features->feature_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_features->feature_name->CellAttributes() ?>>
<span id="el_cfg_features_feature_name">
<input type="text" data-table="cfg_features" data-field="x_feature_name" name="x_feature_name" id="x_feature_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_features->feature_name->getPlaceHolder()) ?>" value="<?php echo $cfg_features->feature_name->EditValue ?>"<?php echo $cfg_features->feature_name->EditAttributes() ?>>
</span>
<?php echo $cfg_features->feature_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_features->feature_icon_link->Visible) { // feature_icon_link ?>
	<div id="r_feature_icon_link" class="form-group">
		<label id="elh_cfg_features_feature_icon_link" class="col-sm-2 control-label ewLabel"><?php echo $cfg_features->feature_icon_link->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_features->feature_icon_link->CellAttributes() ?>>
<span id="el_cfg_features_feature_icon_link">
<div id="fd_x_feature_icon_link">
<span title="<?php echo $cfg_features->feature_icon_link->FldTitle() ? $cfg_features->feature_icon_link->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_features->feature_icon_link->ReadOnly || $cfg_features->feature_icon_link->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_features" data-field="x_feature_icon_link" name="x_feature_icon_link" id="x_feature_icon_link"<?php echo $cfg_features->feature_icon_link->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_feature_icon_link" id= "fn_x_feature_icon_link" value="<?php echo $cfg_features->feature_icon_link->Upload->FileName ?>">
<?php if (@$_POST["fa_x_feature_icon_link"] == "0") { ?>
<input type="hidden" name="fa_x_feature_icon_link" id= "fa_x_feature_icon_link" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_feature_icon_link" id= "fa_x_feature_icon_link" value="1">
<?php } ?>
<input type="hidden" name="fs_x_feature_icon_link" id= "fs_x_feature_icon_link" value="255">
<input type="hidden" name="fx_x_feature_icon_link" id= "fx_x_feature_icon_link" value="<?php echo $cfg_features->feature_icon_link->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_feature_icon_link" id= "fm_x_feature_icon_link" value="<?php echo $cfg_features->feature_icon_link->UploadMaxFileSize ?>">
</div>
<table id="ft_x_feature_icon_link" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_features->feature_icon_link->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_features->details->Visible) { // details ?>
	<div id="r_details" class="form-group">
		<label id="elh_cfg_features_details" for="x_details" class="col-sm-2 control-label ewLabel"><?php echo $cfg_features->details->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_features->details->CellAttributes() ?>>
<span id="el_cfg_features_details">
<textarea data-table="cfg_features" data-field="x_details" name="x_details" id="x_details" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($cfg_features->details->getPlaceHolder()) ?>"<?php echo $cfg_features->details->EditAttributes() ?>><?php echo $cfg_features->details->EditValue ?></textarea>
</span>
<?php echo $cfg_features->details->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_features->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_cfg_features_status" class="col-sm-2 control-label ewLabel"><?php echo $cfg_features->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_features->status->CellAttributes() ?>>
<span id="el_cfg_features_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="cfg_features" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_features->status->DisplayValueSeparator) ? json_encode($cfg_features->status->DisplayValueSeparator) : $cfg_features->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $cfg_features->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_features->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_features->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_features" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_features->status->EditAttributes() ?>><?php echo $cfg_features->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_features->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_features" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_features->status->CurrentValue) ?>" checked<?php echo $cfg_features->status->EditAttributes() ?>><?php echo $cfg_features->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $cfg_features->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="cfg_features" data-field="x_ID" name="x_ID" id="x_ID" value="<?php echo ew_HtmlEncode($cfg_features->ID->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cfg_features_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcfg_featuresedit.Init();
</script>
<?php
$cfg_features_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_features_edit->Page_Terminate();
?>
