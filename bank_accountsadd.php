<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "bank_accountsinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$bank_accounts_add = NULL; // Initialize page object first

class cbank_accounts_add extends cbank_accounts {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'bank_accounts';

	// Page object name
	var $PageObjName = 'bank_accounts_add';

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

		// Table object (bank_accounts)
		if (!isset($GLOBALS["bank_accounts"]) || get_class($GLOBALS["bank_accounts"]) == "cbank_accounts") {
			$GLOBALS["bank_accounts"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bank_accounts"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bank_accounts', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("bank_accountslist.php"));
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
		global $EW_EXPORT, $bank_accounts;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($bank_accounts);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("bank_accountslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "bank_accountslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "bank_accountsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->logo->Upload->Index = $objForm->Index;
		$this->logo->Upload->UploadFile();
		$this->logo->CurrentValue = $this->logo->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->bank_name->CurrentValue = NULL;
		$this->bank_name->OldValue = $this->bank_name->CurrentValue;
		$this->logo->Upload->DbValue = NULL;
		$this->logo->OldValue = $this->logo->Upload->DbValue;
		$this->logo->CurrentValue = NULL; // Clear file related field
		$this->bank_account_title->CurrentValue = NULL;
		$this->bank_account_title->OldValue = $this->bank_account_title->CurrentValue;
		$this->bank_account->CurrentValue = NULL;
		$this->bank_account->OldValue = $this->bank_account->CurrentValue;
		$this->branch_code->CurrentValue = NULL;
		$this->branch_code->OldValue = $this->branch_code->CurrentValue;
		$this->status->CurrentValue = "1";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->bank_name->FldIsDetailKey) {
			$this->bank_name->setFormValue($objForm->GetValue("x_bank_name"));
		}
		if (!$this->bank_account_title->FldIsDetailKey) {
			$this->bank_account_title->setFormValue($objForm->GetValue("x_bank_account_title"));
		}
		if (!$this->bank_account->FldIsDetailKey) {
			$this->bank_account->setFormValue($objForm->GetValue("x_bank_account"));
		}
		if (!$this->branch_code->FldIsDetailKey) {
			$this->branch_code->setFormValue($objForm->GetValue("x_branch_code"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->bank_name->CurrentValue = $this->bank_name->FormValue;
		$this->bank_account_title->CurrentValue = $this->bank_account_title->FormValue;
		$this->bank_account->CurrentValue = $this->bank_account->FormValue;
		$this->branch_code->CurrentValue = $this->branch_code->FormValue;
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
		$this->id->setDbValue($rs->fields('id'));
		$this->bank_name->setDbValue($rs->fields('bank_name'));
		$this->logo->Upload->DbValue = $rs->fields('logo');
		$this->logo->CurrentValue = $this->logo->Upload->DbValue;
		$this->bank_account_title->setDbValue($rs->fields('bank_account_title'));
		$this->bank_account->setDbValue($rs->fields('bank_account'));
		$this->branch_code->setDbValue($rs->fields('branch_code'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->bank_name->DbValue = $row['bank_name'];
		$this->logo->Upload->DbValue = $row['logo'];
		$this->bank_account_title->DbValue = $row['bank_account_title'];
		$this->bank_account->DbValue = $row['bank_account'];
		$this->branch_code->DbValue = $row['branch_code'];
		$this->status->DbValue = $row['status'];
		$this->created_at->DbValue = $row['created_at'];
		$this->updated_at->DbValue = $row['updated_at'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// bank_name
		// logo
		// bank_account_title
		// bank_account
		// branch_code
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// bank_name
		$this->bank_name->ViewValue = $this->bank_name->CurrentValue;
		$this->bank_name->ViewCustomAttributes = "";

		// logo
		if (!ew_Empty($this->logo->Upload->DbValue)) {
			$this->logo->ViewValue = $this->logo->Upload->DbValue;
		} else {
			$this->logo->ViewValue = "";
		}
		$this->logo->ViewCustomAttributes = "";

		// bank_account_title
		$this->bank_account_title->ViewValue = $this->bank_account_title->CurrentValue;
		$this->bank_account_title->ViewCustomAttributes = "";

		// bank_account
		$this->bank_account->ViewValue = $this->bank_account->CurrentValue;
		$this->bank_account->ViewCustomAttributes = "";

		// branch_code
		$this->branch_code->ViewValue = $this->branch_code->CurrentValue;
		$this->branch_code->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Acrive";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// bank_name
			$this->bank_name->LinkCustomAttributes = "";
			$this->bank_name->HrefValue = "";
			$this->bank_name->TooltipValue = "";

			// logo
			$this->logo->LinkCustomAttributes = "";
			$this->logo->HrefValue = "";
			$this->logo->HrefValue2 = $this->logo->UploadPath . $this->logo->Upload->DbValue;
			$this->logo->TooltipValue = "";

			// bank_account_title
			$this->bank_account_title->LinkCustomAttributes = "";
			$this->bank_account_title->HrefValue = "";
			$this->bank_account_title->TooltipValue = "";

			// bank_account
			$this->bank_account->LinkCustomAttributes = "";
			$this->bank_account->HrefValue = "";
			$this->bank_account->TooltipValue = "";

			// branch_code
			$this->branch_code->LinkCustomAttributes = "";
			$this->branch_code->HrefValue = "";
			$this->branch_code->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// bank_name
			$this->bank_name->EditAttrs["class"] = "form-control";
			$this->bank_name->EditCustomAttributes = "";
			$this->bank_name->EditValue = ew_HtmlEncode($this->bank_name->CurrentValue);
			$this->bank_name->PlaceHolder = ew_RemoveHtml($this->bank_name->FldCaption());

			// logo
			$this->logo->EditAttrs["class"] = "form-control";
			$this->logo->EditCustomAttributes = "";
			if (!ew_Empty($this->logo->Upload->DbValue)) {
				$this->logo->EditValue = $this->logo->Upload->DbValue;
			} else {
				$this->logo->EditValue = "";
			}
			if (!ew_Empty($this->logo->CurrentValue))
				$this->logo->Upload->FileName = $this->logo->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->logo);

			// bank_account_title
			$this->bank_account_title->EditAttrs["class"] = "form-control";
			$this->bank_account_title->EditCustomAttributes = "";
			$this->bank_account_title->EditValue = ew_HtmlEncode($this->bank_account_title->CurrentValue);
			$this->bank_account_title->PlaceHolder = ew_RemoveHtml($this->bank_account_title->FldCaption());

			// bank_account
			$this->bank_account->EditAttrs["class"] = "form-control";
			$this->bank_account->EditCustomAttributes = "";
			$this->bank_account->EditValue = ew_HtmlEncode($this->bank_account->CurrentValue);
			$this->bank_account->PlaceHolder = ew_RemoveHtml($this->bank_account->FldCaption());

			// branch_code
			$this->branch_code->EditAttrs["class"] = "form-control";
			$this->branch_code->EditCustomAttributes = "";
			$this->branch_code->EditValue = ew_HtmlEncode($this->branch_code->CurrentValue);
			$this->branch_code->PlaceHolder = ew_RemoveHtml($this->branch_code->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Add refer script
			// bank_name

			$this->bank_name->LinkCustomAttributes = "";
			$this->bank_name->HrefValue = "";

			// logo
			$this->logo->LinkCustomAttributes = "";
			$this->logo->HrefValue = "";
			$this->logo->HrefValue2 = $this->logo->UploadPath . $this->logo->Upload->DbValue;

			// bank_account_title
			$this->bank_account_title->LinkCustomAttributes = "";
			$this->bank_account_title->HrefValue = "";

			// bank_account
			$this->bank_account->LinkCustomAttributes = "";
			$this->bank_account->HrefValue = "";

			// branch_code
			$this->branch_code->LinkCustomAttributes = "";
			$this->branch_code->HrefValue = "";

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
		if (!$this->bank_name->FldIsDetailKey && !is_null($this->bank_name->FormValue) && $this->bank_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bank_name->FldCaption(), $this->bank_name->ReqErrMsg));
		}
		if (!$this->bank_account_title->FldIsDetailKey && !is_null($this->bank_account_title->FormValue) && $this->bank_account_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bank_account_title->FldCaption(), $this->bank_account_title->ReqErrMsg));
		}
		if (!$this->bank_account->FldIsDetailKey && !is_null($this->bank_account->FormValue) && $this->bank_account->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bank_account->FldCaption(), $this->bank_account->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// bank_name
		$this->bank_name->SetDbValueDef($rsnew, $this->bank_name->CurrentValue, "", FALSE);

		// logo
		if ($this->logo->Visible && !$this->logo->Upload->KeepFile) {
			$this->logo->Upload->DbValue = ""; // No need to delete old file
			if ($this->logo->Upload->FileName == "") {
				$rsnew['logo'] = NULL;
			} else {
				$rsnew['logo'] = $this->logo->Upload->FileName;
			}
		}

		// bank_account_title
		$this->bank_account_title->SetDbValueDef($rsnew, $this->bank_account_title->CurrentValue, "", FALSE);

		// bank_account
		$this->bank_account->SetDbValueDef($rsnew, $this->bank_account->CurrentValue, "", FALSE);

		// branch_code
		$this->branch_code->SetDbValueDef($rsnew, $this->branch_code->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, ((strval($this->status->CurrentValue) == "1") ? "1" : "0"), 0, strval($this->status->CurrentValue) == "");
		if ($this->logo->Visible && !$this->logo->Upload->KeepFile) {
			if (!ew_Empty($this->logo->Upload->Value)) {
				$rsnew['logo'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->logo->UploadPath), $rsnew['logo']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->id->setDbValue($conn->Insert_ID());
				$rsnew['id'] = $this->id->DbValue;
				if ($this->logo->Visible && !$this->logo->Upload->KeepFile) {
					if (!ew_Empty($this->logo->Upload->Value)) {
						$this->logo->Upload->SaveToFile($this->logo->UploadPath, $rsnew['logo'], TRUE);
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
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// logo
		ew_CleanUploadTempPath($this->logo, $this->logo->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("bank_accountslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($bank_accounts_add)) $bank_accounts_add = new cbank_accounts_add();

// Page init
$bank_accounts_add->Page_Init();

// Page main
$bank_accounts_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bank_accounts_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fbank_accountsadd = new ew_Form("fbank_accountsadd", "add");

// Validate form
fbank_accountsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_bank_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $bank_accounts->bank_name->FldCaption(), $bank_accounts->bank_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bank_account_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $bank_accounts->bank_account_title->FldCaption(), $bank_accounts->bank_account_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bank_account");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $bank_accounts->bank_account->FldCaption(), $bank_accounts->bank_account->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $bank_accounts->status->FldCaption(), $bank_accounts->status->ReqErrMsg)) ?>");

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
fbank_accountsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbank_accountsadd.ValidateRequired = true;
<?php } else { ?>
fbank_accountsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fbank_accountsadd.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fbank_accountsadd.Lists["x_status"].Options = <?php echo json_encode($bank_accounts->status->Options()) ?>;

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
<?php $bank_accounts_add->ShowPageHeader(); ?>
<?php
$bank_accounts_add->ShowMessage();
?>
<form name="fbank_accountsadd" id="fbank_accountsadd" class="<?php echo $bank_accounts_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($bank_accounts_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $bank_accounts_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="bank_accounts">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($bank_accounts->bank_name->Visible) { // bank_name ?>
	<div id="r_bank_name" class="form-group">
		<label id="elh_bank_accounts_bank_name" for="x_bank_name" class="col-sm-2 control-label ewLabel"><?php echo $bank_accounts->bank_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $bank_accounts->bank_name->CellAttributes() ?>>
<span id="el_bank_accounts_bank_name">
<input type="text" data-table="bank_accounts" data-field="x_bank_name" name="x_bank_name" id="x_bank_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($bank_accounts->bank_name->getPlaceHolder()) ?>" value="<?php echo $bank_accounts->bank_name->EditValue ?>"<?php echo $bank_accounts->bank_name->EditAttributes() ?>>
</span>
<?php echo $bank_accounts->bank_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bank_accounts->logo->Visible) { // logo ?>
	<div id="r_logo" class="form-group">
		<label id="elh_bank_accounts_logo" class="col-sm-2 control-label ewLabel"><?php echo $bank_accounts->logo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bank_accounts->logo->CellAttributes() ?>>
<span id="el_bank_accounts_logo">
<div id="fd_x_logo">
<span title="<?php echo $bank_accounts->logo->FldTitle() ? $bank_accounts->logo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($bank_accounts->logo->ReadOnly || $bank_accounts->logo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="bank_accounts" data-field="x_logo" name="x_logo" id="x_logo"<?php echo $bank_accounts->logo->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_logo" id= "fn_x_logo" value="<?php echo $bank_accounts->logo->Upload->FileName ?>">
<input type="hidden" name="fa_x_logo" id= "fa_x_logo" value="0">
<input type="hidden" name="fs_x_logo" id= "fs_x_logo" value="255">
<input type="hidden" name="fx_x_logo" id= "fx_x_logo" value="<?php echo $bank_accounts->logo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_logo" id= "fm_x_logo" value="<?php echo $bank_accounts->logo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_logo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $bank_accounts->logo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bank_accounts->bank_account_title->Visible) { // bank_account_title ?>
	<div id="r_bank_account_title" class="form-group">
		<label id="elh_bank_accounts_bank_account_title" for="x_bank_account_title" class="col-sm-2 control-label ewLabel"><?php echo $bank_accounts->bank_account_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $bank_accounts->bank_account_title->CellAttributes() ?>>
<span id="el_bank_accounts_bank_account_title">
<input type="text" data-table="bank_accounts" data-field="x_bank_account_title" name="x_bank_account_title" id="x_bank_account_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($bank_accounts->bank_account_title->getPlaceHolder()) ?>" value="<?php echo $bank_accounts->bank_account_title->EditValue ?>"<?php echo $bank_accounts->bank_account_title->EditAttributes() ?>>
</span>
<?php echo $bank_accounts->bank_account_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bank_accounts->bank_account->Visible) { // bank_account ?>
	<div id="r_bank_account" class="form-group">
		<label id="elh_bank_accounts_bank_account" for="x_bank_account" class="col-sm-2 control-label ewLabel"><?php echo $bank_accounts->bank_account->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $bank_accounts->bank_account->CellAttributes() ?>>
<span id="el_bank_accounts_bank_account">
<input type="text" data-table="bank_accounts" data-field="x_bank_account" name="x_bank_account" id="x_bank_account" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($bank_accounts->bank_account->getPlaceHolder()) ?>" value="<?php echo $bank_accounts->bank_account->EditValue ?>"<?php echo $bank_accounts->bank_account->EditAttributes() ?>>
</span>
<?php echo $bank_accounts->bank_account->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bank_accounts->branch_code->Visible) { // branch_code ?>
	<div id="r_branch_code" class="form-group">
		<label id="elh_bank_accounts_branch_code" for="x_branch_code" class="col-sm-2 control-label ewLabel"><?php echo $bank_accounts->branch_code->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bank_accounts->branch_code->CellAttributes() ?>>
<span id="el_bank_accounts_branch_code">
<input type="text" data-table="bank_accounts" data-field="x_branch_code" name="x_branch_code" id="x_branch_code" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($bank_accounts->branch_code->getPlaceHolder()) ?>" value="<?php echo $bank_accounts->branch_code->EditValue ?>"<?php echo $bank_accounts->branch_code->EditAttributes() ?>>
</span>
<?php echo $bank_accounts->branch_code->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bank_accounts->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_bank_accounts_status" class="col-sm-2 control-label ewLabel"><?php echo $bank_accounts->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $bank_accounts->status->CellAttributes() ?>>
<span id="el_bank_accounts_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="bank_accounts" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($bank_accounts->status->DisplayValueSeparator) ? json_encode($bank_accounts->status->DisplayValueSeparator) : $bank_accounts->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $bank_accounts->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $bank_accounts->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($bank_accounts->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="bank_accounts" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $bank_accounts->status->EditAttributes() ?>><?php echo $bank_accounts->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($bank_accounts->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="bank_accounts" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($bank_accounts->status->CurrentValue) ?>" checked<?php echo $bank_accounts->status->EditAttributes() ?>><?php echo $bank_accounts->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $bank_accounts->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $bank_accounts_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fbank_accountsadd.Init();
</script>
<?php
$bank_accounts_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bank_accounts_add->Page_Terminate();
?>
