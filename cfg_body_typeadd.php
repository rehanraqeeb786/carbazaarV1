<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_body_typeinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_body_type_add = NULL; // Initialize page object first

class ccfg_body_type_add extends ccfg_body_type {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_body_type';

	// Page object name
	var $PageObjName = 'cfg_body_type_add';

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

		// Table object (cfg_body_type)
		if (!isset($GLOBALS["cfg_body_type"]) || get_class($GLOBALS["cfg_body_type"]) == "ccfg_body_type") {
			$GLOBALS["cfg_body_type"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_body_type"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cfg_body_type', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_body_typelist.php"));
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
		global $EW_EXPORT, $cfg_body_type;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_body_type);
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
			if (@$_GET["ID"] != "") {
				$this->ID->setQueryStringValue($_GET["ID"]);
				$this->setKey("ID", $this->ID->CurrentValue); // Set up key
			} else {
				$this->setKey("ID", ""); // Clear key
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
					$this->Page_Terminate("cfg_body_typelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "cfg_body_typelist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "cfg_body_typeview.php")
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
		$this->icon_url->Upload->Index = $objForm->Index;
		$this->icon_url->Upload->UploadFile();
		$this->icon_url->CurrentValue = $this->icon_url->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->title->CurrentValue = NULL;
		$this->title->OldValue = $this->title->CurrentValue;
		$this->details->CurrentValue = NULL;
		$this->details->OldValue = $this->details->CurrentValue;
		$this->icon_url->Upload->DbValue = NULL;
		$this->icon_url->OldValue = $this->icon_url->Upload->DbValue;
		$this->icon_url->CurrentValue = NULL; // Clear file related field
		$this->status->CurrentValue = "1";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->details->FldIsDetailKey) {
			$this->details->setFormValue($objForm->GetValue("x_details"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->title->CurrentValue = $this->title->FormValue;
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
		$this->title->setDbValue($rs->fields('title'));
		$this->details->setDbValue($rs->fields('details'));
		$this->icon_url->Upload->DbValue = $rs->fields('icon_url');
		$this->icon_url->CurrentValue = $this->icon_url->Upload->DbValue;
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->title->DbValue = $row['title'];
		$this->details->DbValue = $row['details'];
		$this->icon_url->Upload->DbValue = $row['icon_url'];
		$this->status->DbValue = $row['status'];
		$this->created_at->DbValue = $row['created_at'];
		$this->updated_at->DbValue = $row['updated_at'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("ID")) <> "")
			$this->ID->CurrentValue = $this->getKey("ID"); // ID
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
		// ID
		// title
		// details
		// icon_url
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// details
		$this->details->ViewValue = $this->details->CurrentValue;
		$this->details->ViewCustomAttributes = "";

		// icon_url
		if (!ew_Empty($this->icon_url->Upload->DbValue)) {
			$this->icon_url->ImageAlt = $this->icon_url->FldAlt();
			$this->icon_url->ViewValue = $this->icon_url->Upload->DbValue;
		} else {
			$this->icon_url->ViewValue = "";
		}
		$this->icon_url->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// details
			$this->details->LinkCustomAttributes = "";
			$this->details->HrefValue = "";
			$this->details->TooltipValue = "";

			// icon_url
			$this->icon_url->LinkCustomAttributes = "";
			if (!ew_Empty($this->icon_url->Upload->DbValue)) {
				$this->icon_url->HrefValue = ew_GetFileUploadUrl($this->icon_url, $this->icon_url->Upload->DbValue); // Add prefix/suffix
				$this->icon_url->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->icon_url->HrefValue = ew_ConvertFullUrl($this->icon_url->HrefValue);
			} else {
				$this->icon_url->HrefValue = "";
			}
			$this->icon_url->HrefValue2 = $this->icon_url->UploadPath . $this->icon_url->Upload->DbValue;
			$this->icon_url->TooltipValue = "";
			if ($this->icon_url->UseColorbox) {
				if (ew_Empty($this->icon_url->TooltipValue))
					$this->icon_url->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->icon_url->LinkAttrs["data-rel"] = "cfg_body_type_x_icon_url";
				ew_AppendClass($this->icon_url->LinkAttrs["class"], "ewLightbox");
			}

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// details
			$this->details->EditAttrs["class"] = "form-control";
			$this->details->EditCustomAttributes = "";
			$this->details->EditValue = ew_HtmlEncode($this->details->CurrentValue);
			$this->details->PlaceHolder = ew_RemoveHtml($this->details->FldCaption());

			// icon_url
			$this->icon_url->EditAttrs["class"] = "form-control";
			$this->icon_url->EditCustomAttributes = "";
			if (!ew_Empty($this->icon_url->Upload->DbValue)) {
				$this->icon_url->ImageAlt = $this->icon_url->FldAlt();
				$this->icon_url->EditValue = $this->icon_url->Upload->DbValue;
			} else {
				$this->icon_url->EditValue = "";
			}
			if (!ew_Empty($this->icon_url->CurrentValue))
				$this->icon_url->Upload->FileName = $this->icon_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->icon_url);

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Add refer script
			// title

			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// details
			$this->details->LinkCustomAttributes = "";
			$this->details->HrefValue = "";

			// icon_url
			$this->icon_url->LinkCustomAttributes = "";
			if (!ew_Empty($this->icon_url->Upload->DbValue)) {
				$this->icon_url->HrefValue = ew_GetFileUploadUrl($this->icon_url, $this->icon_url->Upload->DbValue); // Add prefix/suffix
				$this->icon_url->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->icon_url->HrefValue = ew_ConvertFullUrl($this->icon_url->HrefValue);
			} else {
				$this->icon_url->HrefValue = "";
			}
			$this->icon_url->HrefValue2 = $this->icon_url->UploadPath . $this->icon_url->Upload->DbValue;

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
		if (!$this->title->FldIsDetailKey && !is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title->FldCaption(), $this->title->ReqErrMsg));
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

		// title
		$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", FALSE);

		// details
		$this->details->SetDbValueDef($rsnew, $this->details->CurrentValue, NULL, FALSE);

		// icon_url
		if ($this->icon_url->Visible && !$this->icon_url->Upload->KeepFile) {
			$this->icon_url->Upload->DbValue = ""; // No need to delete old file
			if ($this->icon_url->Upload->FileName == "") {
				$rsnew['icon_url'] = NULL;
			} else {
				$rsnew['icon_url'] = $this->icon_url->Upload->FileName;
			}
		}

		// status
		$this->status->SetDbValueDef($rsnew, ((strval($this->status->CurrentValue) == "1") ? "1" : "0"), 0, strval($this->status->CurrentValue) == "");
		if ($this->icon_url->Visible && !$this->icon_url->Upload->KeepFile) {
			if (!ew_Empty($this->icon_url->Upload->Value)) {
				$rsnew['icon_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->icon_url->UploadPath), $rsnew['icon_url']); // Get new file name
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
				$this->ID->setDbValue($conn->Insert_ID());
				$rsnew['ID'] = $this->ID->DbValue;
				if ($this->icon_url->Visible && !$this->icon_url->Upload->KeepFile) {
					if (!ew_Empty($this->icon_url->Upload->Value)) {
						$this->icon_url->Upload->SaveToFile($this->icon_url->UploadPath, $rsnew['icon_url'], TRUE);
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

		// icon_url
		ew_CleanUploadTempPath($this->icon_url, $this->icon_url->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_body_typelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_body_type_add)) $cfg_body_type_add = new ccfg_body_type_add();

// Page init
$cfg_body_type_add->Page_Init();

// Page main
$cfg_body_type_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_body_type_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fcfg_body_typeadd = new ew_Form("fcfg_body_typeadd", "add");

// Validate form
fcfg_body_typeadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_body_type->title->FldCaption(), $cfg_body_type->title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_body_type->status->FldCaption(), $cfg_body_type->status->ReqErrMsg)) ?>");

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
fcfg_body_typeadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_body_typeadd.ValidateRequired = true;
<?php } else { ?>
fcfg_body_typeadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_body_typeadd.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_body_typeadd.Lists["x_status"].Options = <?php echo json_encode($cfg_body_type->status->Options()) ?>;

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
<?php $cfg_body_type_add->ShowPageHeader(); ?>
<?php
$cfg_body_type_add->ShowMessage();
?>
<form name="fcfg_body_typeadd" id="fcfg_body_typeadd" class="<?php echo $cfg_body_type_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_body_type_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_body_type_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_body_type">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($cfg_body_type->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label id="elh_cfg_body_type_title" for="x_title" class="col-sm-2 control-label ewLabel"><?php echo $cfg_body_type->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_body_type->title->CellAttributes() ?>>
<span id="el_cfg_body_type_title">
<input type="text" data-table="cfg_body_type" data-field="x_title" name="x_title" id="x_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_body_type->title->getPlaceHolder()) ?>" value="<?php echo $cfg_body_type->title->EditValue ?>"<?php echo $cfg_body_type->title->EditAttributes() ?>>
</span>
<?php echo $cfg_body_type->title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_body_type->details->Visible) { // details ?>
	<div id="r_details" class="form-group">
		<label id="elh_cfg_body_type_details" for="x_details" class="col-sm-2 control-label ewLabel"><?php echo $cfg_body_type->details->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_body_type->details->CellAttributes() ?>>
<span id="el_cfg_body_type_details">
<textarea data-table="cfg_body_type" data-field="x_details" name="x_details" id="x_details" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($cfg_body_type->details->getPlaceHolder()) ?>"<?php echo $cfg_body_type->details->EditAttributes() ?>><?php echo $cfg_body_type->details->EditValue ?></textarea>
</span>
<?php echo $cfg_body_type->details->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_body_type->icon_url->Visible) { // icon_url ?>
	<div id="r_icon_url" class="form-group">
		<label id="elh_cfg_body_type_icon_url" class="col-sm-2 control-label ewLabel"><?php echo $cfg_body_type->icon_url->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_body_type->icon_url->CellAttributes() ?>>
<span id="el_cfg_body_type_icon_url">
<div id="fd_x_icon_url">
<span title="<?php echo $cfg_body_type->icon_url->FldTitle() ? $cfg_body_type->icon_url->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_body_type->icon_url->ReadOnly || $cfg_body_type->icon_url->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_body_type" data-field="x_icon_url" name="x_icon_url" id="x_icon_url"<?php echo $cfg_body_type->icon_url->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_icon_url" id= "fn_x_icon_url" value="<?php echo $cfg_body_type->icon_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_icon_url" id= "fa_x_icon_url" value="0">
<input type="hidden" name="fs_x_icon_url" id= "fs_x_icon_url" value="255">
<input type="hidden" name="fx_x_icon_url" id= "fx_x_icon_url" value="<?php echo $cfg_body_type->icon_url->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_icon_url" id= "fm_x_icon_url" value="<?php echo $cfg_body_type->icon_url->UploadMaxFileSize ?>">
</div>
<table id="ft_x_icon_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_body_type->icon_url->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_body_type->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_cfg_body_type_status" class="col-sm-2 control-label ewLabel"><?php echo $cfg_body_type->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_body_type->status->CellAttributes() ?>>
<span id="el_cfg_body_type_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="cfg_body_type" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_body_type->status->DisplayValueSeparator) ? json_encode($cfg_body_type->status->DisplayValueSeparator) : $cfg_body_type->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $cfg_body_type->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_body_type->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_body_type->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_body_type" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_body_type->status->EditAttributes() ?>><?php echo $cfg_body_type->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_body_type->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_body_type" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_body_type->status->CurrentValue) ?>" checked<?php echo $cfg_body_type->status->EditAttributes() ?>><?php echo $cfg_body_type->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $cfg_body_type->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cfg_body_type_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcfg_body_typeadd.Init();
</script>
<?php
$cfg_body_type_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_body_type_add->Page_Terminate();
?>
