<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_modelsinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_models_edit = NULL; // Initialize page object first

class ccfg_models_edit extends ccfg_models {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_models';

	// Page object name
	var $PageObjName = 'cfg_models_edit';

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

		// Table object (cfg_models)
		if (!isset($GLOBALS["cfg_models"]) || get_class($GLOBALS["cfg_models"]) == "ccfg_models") {
			$GLOBALS["cfg_models"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_models"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cfg_models', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_modelslist.php"));
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
		global $EW_EXPORT, $cfg_models;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_models);
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
			$this->Page_Terminate("cfg_modelslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("cfg_modelslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "cfg_modelslist.php")
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
		$this->main_pic_link->Upload->Index = $objForm->Index;
		$this->main_pic_link->Upload->UploadFile();
		$this->main_pic_link->CurrentValue = $this->main_pic_link->Upload->FileName;
		$this->pic_2->Upload->Index = $objForm->Index;
		$this->pic_2->Upload->UploadFile();
		$this->pic_2->CurrentValue = $this->pic_2->Upload->FileName;
		$this->pic_3->Upload->Index = $objForm->Index;
		$this->pic_3->Upload->UploadFile();
		$this->pic_3->CurrentValue = $this->pic_3->Upload->FileName;
		$this->pic_4->Upload->Index = $objForm->Index;
		$this->pic_4->Upload->UploadFile();
		$this->pic_4->CurrentValue = $this->pic_4->Upload->FileName;
		$this->pic_5->Upload->Index = $objForm->Index;
		$this->pic_5->Upload->UploadFile();
		$this->pic_5->CurrentValue = $this->pic_5->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->make_company_id->FldIsDetailKey) {
			$this->make_company_id->setFormValue($objForm->GetValue("x_make_company_id"));
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
		$this->title->CurrentValue = $this->title->FormValue;
		$this->make_company_id->CurrentValue = $this->make_company_id->FormValue;
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
		$this->make_company_id->setDbValue($rs->fields('make_company_id'));
		$this->main_pic_link->Upload->DbValue = $rs->fields('main_pic_link');
		$this->main_pic_link->CurrentValue = $this->main_pic_link->Upload->DbValue;
		$this->pic_2->Upload->DbValue = $rs->fields('pic_2');
		$this->pic_2->CurrentValue = $this->pic_2->Upload->DbValue;
		$this->pic_3->Upload->DbValue = $rs->fields('pic_3');
		$this->pic_3->CurrentValue = $this->pic_3->Upload->DbValue;
		$this->pic_4->Upload->DbValue = $rs->fields('pic_4');
		$this->pic_4->CurrentValue = $this->pic_4->Upload->DbValue;
		$this->pic_5->Upload->DbValue = $rs->fields('pic_5');
		$this->pic_5->CurrentValue = $this->pic_5->Upload->DbValue;
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
		$this->title->DbValue = $row['title'];
		$this->make_company_id->DbValue = $row['make_company_id'];
		$this->main_pic_link->Upload->DbValue = $row['main_pic_link'];
		$this->pic_2->Upload->DbValue = $row['pic_2'];
		$this->pic_3->Upload->DbValue = $row['pic_3'];
		$this->pic_4->Upload->DbValue = $row['pic_4'];
		$this->pic_5->Upload->DbValue = $row['pic_5'];
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
		// title
		// make_company_id
		// main_pic_link
		// pic_2
		// pic_3
		// pic_4
		// pic_5
		// details
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// make_company_id
		if (strval($this->make_company_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->make_company_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->make_company_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->make_company_id->ViewValue = $this->make_company_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->make_company_id->ViewValue = $this->make_company_id->CurrentValue;
			}
		} else {
			$this->make_company_id->ViewValue = NULL;
		}
		$this->make_company_id->ViewCustomAttributes = "";

		// main_pic_link
		if (!ew_Empty($this->main_pic_link->Upload->DbValue)) {
			$this->main_pic_link->ViewValue = $this->main_pic_link->Upload->DbValue;
		} else {
			$this->main_pic_link->ViewValue = "";
		}
		$this->main_pic_link->ViewCustomAttributes = "";

		// pic_2
		if (!ew_Empty($this->pic_2->Upload->DbValue)) {
			$this->pic_2->ViewValue = $this->pic_2->Upload->DbValue;
		} else {
			$this->pic_2->ViewValue = "";
		}
		$this->pic_2->ViewCustomAttributes = "";

		// pic_3
		if (!ew_Empty($this->pic_3->Upload->DbValue)) {
			$this->pic_3->ViewValue = $this->pic_3->Upload->DbValue;
		} else {
			$this->pic_3->ViewValue = "";
		}
		$this->pic_3->ViewCustomAttributes = "";

		// pic_4
		if (!ew_Empty($this->pic_4->Upload->DbValue)) {
			$this->pic_4->ViewValue = $this->pic_4->Upload->DbValue;
		} else {
			$this->pic_4->ViewValue = "";
		}
		$this->pic_4->ViewCustomAttributes = "";

		// pic_5
		if (!ew_Empty($this->pic_5->Upload->DbValue)) {
			$this->pic_5->ViewValue = $this->pic_5->Upload->DbValue;
		} else {
			$this->pic_5->ViewValue = "";
		}
		$this->pic_5->ViewCustomAttributes = "";

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

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// make_company_id
			$this->make_company_id->LinkCustomAttributes = "";
			$this->make_company_id->HrefValue = "";
			$this->make_company_id->TooltipValue = "";

			// main_pic_link
			$this->main_pic_link->LinkCustomAttributes = "";
			$this->main_pic_link->HrefValue = "";
			$this->main_pic_link->HrefValue2 = $this->main_pic_link->UploadPath . $this->main_pic_link->Upload->DbValue;
			$this->main_pic_link->TooltipValue = "";

			// pic_2
			$this->pic_2->LinkCustomAttributes = "";
			$this->pic_2->HrefValue = "";
			$this->pic_2->HrefValue2 = $this->pic_2->UploadPath . $this->pic_2->Upload->DbValue;
			$this->pic_2->TooltipValue = "";

			// pic_3
			$this->pic_3->LinkCustomAttributes = "";
			$this->pic_3->HrefValue = "";
			$this->pic_3->HrefValue2 = $this->pic_3->UploadPath . $this->pic_3->Upload->DbValue;
			$this->pic_3->TooltipValue = "";

			// pic_4
			$this->pic_4->LinkCustomAttributes = "";
			$this->pic_4->HrefValue = "";
			$this->pic_4->HrefValue2 = $this->pic_4->UploadPath . $this->pic_4->Upload->DbValue;
			$this->pic_4->TooltipValue = "";

			// pic_5
			$this->pic_5->LinkCustomAttributes = "";
			$this->pic_5->HrefValue = "";
			$this->pic_5->HrefValue2 = $this->pic_5->UploadPath . $this->pic_5->Upload->DbValue;
			$this->pic_5->TooltipValue = "";

			// details
			$this->details->LinkCustomAttributes = "";
			$this->details->HrefValue = "";
			$this->details->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// make_company_id
			$this->make_company_id->EditAttrs["class"] = "form-control";
			$this->make_company_id->EditCustomAttributes = "";
			if (trim(strval($this->make_company_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->make_company_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_make_companies`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->make_company_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->make_company_id->EditValue = $arwrk;

			// main_pic_link
			$this->main_pic_link->EditAttrs["class"] = "form-control";
			$this->main_pic_link->EditCustomAttributes = "";
			if (!ew_Empty($this->main_pic_link->Upload->DbValue)) {
				$this->main_pic_link->EditValue = $this->main_pic_link->Upload->DbValue;
			} else {
				$this->main_pic_link->EditValue = "";
			}
			if (!ew_Empty($this->main_pic_link->CurrentValue))
				$this->main_pic_link->Upload->FileName = $this->main_pic_link->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->main_pic_link);

			// pic_2
			$this->pic_2->EditAttrs["class"] = "form-control";
			$this->pic_2->EditCustomAttributes = "";
			if (!ew_Empty($this->pic_2->Upload->DbValue)) {
				$this->pic_2->EditValue = $this->pic_2->Upload->DbValue;
			} else {
				$this->pic_2->EditValue = "";
			}
			if (!ew_Empty($this->pic_2->CurrentValue))
				$this->pic_2->Upload->FileName = $this->pic_2->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->pic_2);

			// pic_3
			$this->pic_3->EditAttrs["class"] = "form-control";
			$this->pic_3->EditCustomAttributes = "";
			if (!ew_Empty($this->pic_3->Upload->DbValue)) {
				$this->pic_3->EditValue = $this->pic_3->Upload->DbValue;
			} else {
				$this->pic_3->EditValue = "";
			}
			if (!ew_Empty($this->pic_3->CurrentValue))
				$this->pic_3->Upload->FileName = $this->pic_3->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->pic_3);

			// pic_4
			$this->pic_4->EditAttrs["class"] = "form-control";
			$this->pic_4->EditCustomAttributes = "";
			if (!ew_Empty($this->pic_4->Upload->DbValue)) {
				$this->pic_4->EditValue = $this->pic_4->Upload->DbValue;
			} else {
				$this->pic_4->EditValue = "";
			}
			if (!ew_Empty($this->pic_4->CurrentValue))
				$this->pic_4->Upload->FileName = $this->pic_4->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->pic_4);

			// pic_5
			$this->pic_5->EditAttrs["class"] = "form-control";
			$this->pic_5->EditCustomAttributes = "";
			if (!ew_Empty($this->pic_5->Upload->DbValue)) {
				$this->pic_5->EditValue = $this->pic_5->Upload->DbValue;
			} else {
				$this->pic_5->EditValue = "";
			}
			if (!ew_Empty($this->pic_5->CurrentValue))
				$this->pic_5->Upload->FileName = $this->pic_5->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->pic_5);

			// details
			$this->details->EditAttrs["class"] = "form-control";
			$this->details->EditCustomAttributes = "";
			$this->details->EditValue = ew_HtmlEncode($this->details->CurrentValue);
			$this->details->PlaceHolder = ew_RemoveHtml($this->details->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Edit refer script
			// title

			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// make_company_id
			$this->make_company_id->LinkCustomAttributes = "";
			$this->make_company_id->HrefValue = "";

			// main_pic_link
			$this->main_pic_link->LinkCustomAttributes = "";
			$this->main_pic_link->HrefValue = "";
			$this->main_pic_link->HrefValue2 = $this->main_pic_link->UploadPath . $this->main_pic_link->Upload->DbValue;

			// pic_2
			$this->pic_2->LinkCustomAttributes = "";
			$this->pic_2->HrefValue = "";
			$this->pic_2->HrefValue2 = $this->pic_2->UploadPath . $this->pic_2->Upload->DbValue;

			// pic_3
			$this->pic_3->LinkCustomAttributes = "";
			$this->pic_3->HrefValue = "";
			$this->pic_3->HrefValue2 = $this->pic_3->UploadPath . $this->pic_3->Upload->DbValue;

			// pic_4
			$this->pic_4->LinkCustomAttributes = "";
			$this->pic_4->HrefValue = "";
			$this->pic_4->HrefValue2 = $this->pic_4->UploadPath . $this->pic_4->Upload->DbValue;

			// pic_5
			$this->pic_5->LinkCustomAttributes = "";
			$this->pic_5->HrefValue = "";
			$this->pic_5->HrefValue2 = $this->pic_5->UploadPath . $this->pic_5->Upload->DbValue;

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
		if (!$this->title->FldIsDetailKey && !is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title->FldCaption(), $this->title->ReqErrMsg));
		}
		if (!$this->make_company_id->FldIsDetailKey && !is_null($this->make_company_id->FormValue) && $this->make_company_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->make_company_id->FldCaption(), $this->make_company_id->ReqErrMsg));
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

			// title
			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, NULL, $this->title->ReadOnly);

			// make_company_id
			$this->make_company_id->SetDbValueDef($rsnew, $this->make_company_id->CurrentValue, 0, $this->make_company_id->ReadOnly);

			// main_pic_link
			if ($this->main_pic_link->Visible && !$this->main_pic_link->ReadOnly && !$this->main_pic_link->Upload->KeepFile) {
				$this->main_pic_link->Upload->DbValue = $rsold['main_pic_link']; // Get original value
				if ($this->main_pic_link->Upload->FileName == "") {
					$rsnew['main_pic_link'] = NULL;
				} else {
					$rsnew['main_pic_link'] = $this->main_pic_link->Upload->FileName;
				}
			}

			// pic_2
			if ($this->pic_2->Visible && !$this->pic_2->ReadOnly && !$this->pic_2->Upload->KeepFile) {
				$this->pic_2->Upload->DbValue = $rsold['pic_2']; // Get original value
				if ($this->pic_2->Upload->FileName == "") {
					$rsnew['pic_2'] = NULL;
				} else {
					$rsnew['pic_2'] = $this->pic_2->Upload->FileName;
				}
			}

			// pic_3
			if ($this->pic_3->Visible && !$this->pic_3->ReadOnly && !$this->pic_3->Upload->KeepFile) {
				$this->pic_3->Upload->DbValue = $rsold['pic_3']; // Get original value
				if ($this->pic_3->Upload->FileName == "") {
					$rsnew['pic_3'] = NULL;
				} else {
					$rsnew['pic_3'] = $this->pic_3->Upload->FileName;
				}
			}

			// pic_4
			if ($this->pic_4->Visible && !$this->pic_4->ReadOnly && !$this->pic_4->Upload->KeepFile) {
				$this->pic_4->Upload->DbValue = $rsold['pic_4']; // Get original value
				if ($this->pic_4->Upload->FileName == "") {
					$rsnew['pic_4'] = NULL;
				} else {
					$rsnew['pic_4'] = $this->pic_4->Upload->FileName;
				}
			}

			// pic_5
			if ($this->pic_5->Visible && !$this->pic_5->ReadOnly && !$this->pic_5->Upload->KeepFile) {
				$this->pic_5->Upload->DbValue = $rsold['pic_5']; // Get original value
				if ($this->pic_5->Upload->FileName == "") {
					$rsnew['pic_5'] = NULL;
				} else {
					$rsnew['pic_5'] = $this->pic_5->Upload->FileName;
				}
			}

			// details
			$this->details->SetDbValueDef($rsnew, $this->details->CurrentValue, NULL, $this->details->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, ((strval($this->status->CurrentValue) == "1") ? "1" : "0"), 0, $this->status->ReadOnly);
			if ($this->main_pic_link->Visible && !$this->main_pic_link->Upload->KeepFile) {
				if (!ew_Empty($this->main_pic_link->Upload->Value)) {
					$rsnew['main_pic_link'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->main_pic_link->UploadPath), $rsnew['main_pic_link']); // Get new file name
				}
			}
			if ($this->pic_2->Visible && !$this->pic_2->Upload->KeepFile) {
				if (!ew_Empty($this->pic_2->Upload->Value)) {
					$rsnew['pic_2'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->pic_2->UploadPath), $rsnew['pic_2']); // Get new file name
				}
			}
			if ($this->pic_3->Visible && !$this->pic_3->Upload->KeepFile) {
				if (!ew_Empty($this->pic_3->Upload->Value)) {
					$rsnew['pic_3'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->pic_3->UploadPath), $rsnew['pic_3']); // Get new file name
				}
			}
			if ($this->pic_4->Visible && !$this->pic_4->Upload->KeepFile) {
				if (!ew_Empty($this->pic_4->Upload->Value)) {
					$rsnew['pic_4'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->pic_4->UploadPath), $rsnew['pic_4']); // Get new file name
				}
			}
			if ($this->pic_5->Visible && !$this->pic_5->Upload->KeepFile) {
				if (!ew_Empty($this->pic_5->Upload->Value)) {
					$rsnew['pic_5'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->pic_5->UploadPath), $rsnew['pic_5']); // Get new file name
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
					if ($this->main_pic_link->Visible && !$this->main_pic_link->Upload->KeepFile) {
						if (!ew_Empty($this->main_pic_link->Upload->Value)) {
							$this->main_pic_link->Upload->SaveToFile($this->main_pic_link->UploadPath, $rsnew['main_pic_link'], TRUE);
						}
					}
					if ($this->pic_2->Visible && !$this->pic_2->Upload->KeepFile) {
						if (!ew_Empty($this->pic_2->Upload->Value)) {
							$this->pic_2->Upload->SaveToFile($this->pic_2->UploadPath, $rsnew['pic_2'], TRUE);
						}
					}
					if ($this->pic_3->Visible && !$this->pic_3->Upload->KeepFile) {
						if (!ew_Empty($this->pic_3->Upload->Value)) {
							$this->pic_3->Upload->SaveToFile($this->pic_3->UploadPath, $rsnew['pic_3'], TRUE);
						}
					}
					if ($this->pic_4->Visible && !$this->pic_4->Upload->KeepFile) {
						if (!ew_Empty($this->pic_4->Upload->Value)) {
							$this->pic_4->Upload->SaveToFile($this->pic_4->UploadPath, $rsnew['pic_4'], TRUE);
						}
					}
					if ($this->pic_5->Visible && !$this->pic_5->Upload->KeepFile) {
						if (!ew_Empty($this->pic_5->Upload->Value)) {
							$this->pic_5->Upload->SaveToFile($this->pic_5->UploadPath, $rsnew['pic_5'], TRUE);
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

		// main_pic_link
		ew_CleanUploadTempPath($this->main_pic_link, $this->main_pic_link->Upload->Index);

		// pic_2
		ew_CleanUploadTempPath($this->pic_2, $this->pic_2->Upload->Index);

		// pic_3
		ew_CleanUploadTempPath($this->pic_3, $this->pic_3->Upload->Index);

		// pic_4
		ew_CleanUploadTempPath($this->pic_4, $this->pic_4->Upload->Index);

		// pic_5
		ew_CleanUploadTempPath($this->pic_5, $this->pic_5->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_modelslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_models_edit)) $cfg_models_edit = new ccfg_models_edit();

// Page init
$cfg_models_edit->Page_Init();

// Page main
$cfg_models_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_models_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fcfg_modelsedit = new ew_Form("fcfg_modelsedit", "edit");

// Validate form
fcfg_modelsedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_models->title->FldCaption(), $cfg_models->title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_make_company_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_models->make_company_id->FldCaption(), $cfg_models->make_company_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_models->status->FldCaption(), $cfg_models->status->ReqErrMsg)) ?>");

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
fcfg_modelsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_modelsedit.ValidateRequired = true;
<?php } else { ?>
fcfg_modelsedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_modelsedit.Lists["x_make_company_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_modelsedit.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_modelsedit.Lists["x_status"].Options = <?php echo json_encode($cfg_models->status->Options()) ?>;

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
<?php $cfg_models_edit->ShowPageHeader(); ?>
<?php
$cfg_models_edit->ShowMessage();
?>
<form name="fcfg_modelsedit" id="fcfg_modelsedit" class="<?php echo $cfg_models_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_models_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_models_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_models">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($cfg_models->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label id="elh_cfg_models_title" for="x_title" class="col-sm-2 control-label ewLabel"><?php echo $cfg_models->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_models->title->CellAttributes() ?>>
<span id="el_cfg_models_title">
<input type="text" data-table="cfg_models" data-field="x_title" name="x_title" id="x_title" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($cfg_models->title->getPlaceHolder()) ?>" value="<?php echo $cfg_models->title->EditValue ?>"<?php echo $cfg_models->title->EditAttributes() ?>>
</span>
<?php echo $cfg_models->title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->make_company_id->Visible) { // make_company_id ?>
	<div id="r_make_company_id" class="form-group">
		<label id="elh_cfg_models_make_company_id" for="x_make_company_id" class="col-sm-2 control-label ewLabel"><?php echo $cfg_models->make_company_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_models->make_company_id->CellAttributes() ?>>
<span id="el_cfg_models_make_company_id">
<select data-table="cfg_models" data-field="x_make_company_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_models->make_company_id->DisplayValueSeparator) ? json_encode($cfg_models->make_company_id->DisplayValueSeparator) : $cfg_models->make_company_id->DisplayValueSeparator) ?>" id="x_make_company_id" name="x_make_company_id"<?php echo $cfg_models->make_company_id->EditAttributes() ?>>
<?php
if (is_array($cfg_models->make_company_id->EditValue)) {
	$arwrk = $cfg_models->make_company_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($cfg_models->make_company_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $cfg_models->make_company_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($cfg_models->make_company_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($cfg_models->make_company_id->CurrentValue) ?>" selected><?php echo $cfg_models->make_company_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$cfg_models->make_company_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$cfg_models->make_company_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$cfg_models->Lookup_Selecting($cfg_models->make_company_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $cfg_models->make_company_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_make_company_id" id="s_x_make_company_id" value="<?php echo $cfg_models->make_company_id->LookupFilterQuery() ?>">
</span>
<?php echo $cfg_models->make_company_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->main_pic_link->Visible) { // main_pic_link ?>
	<div id="r_main_pic_link" class="form-group">
		<label id="elh_cfg_models_main_pic_link" class="col-sm-2 control-label ewLabel"><?php echo $cfg_models->main_pic_link->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_models->main_pic_link->CellAttributes() ?>>
<span id="el_cfg_models_main_pic_link">
<div id="fd_x_main_pic_link">
<span title="<?php echo $cfg_models->main_pic_link->FldTitle() ? $cfg_models->main_pic_link->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_models->main_pic_link->ReadOnly || $cfg_models->main_pic_link->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_models" data-field="x_main_pic_link" name="x_main_pic_link" id="x_main_pic_link"<?php echo $cfg_models->main_pic_link->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_main_pic_link" id= "fn_x_main_pic_link" value="<?php echo $cfg_models->main_pic_link->Upload->FileName ?>">
<?php if (@$_POST["fa_x_main_pic_link"] == "0") { ?>
<input type="hidden" name="fa_x_main_pic_link" id= "fa_x_main_pic_link" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_main_pic_link" id= "fa_x_main_pic_link" value="1">
<?php } ?>
<input type="hidden" name="fs_x_main_pic_link" id= "fs_x_main_pic_link" value="255">
<input type="hidden" name="fx_x_main_pic_link" id= "fx_x_main_pic_link" value="<?php echo $cfg_models->main_pic_link->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_main_pic_link" id= "fm_x_main_pic_link" value="<?php echo $cfg_models->main_pic_link->UploadMaxFileSize ?>">
</div>
<table id="ft_x_main_pic_link" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_models->main_pic_link->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->pic_2->Visible) { // pic_2 ?>
	<div id="r_pic_2" class="form-group">
		<label id="elh_cfg_models_pic_2" class="col-sm-2 control-label ewLabel"><?php echo $cfg_models->pic_2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_models->pic_2->CellAttributes() ?>>
<span id="el_cfg_models_pic_2">
<div id="fd_x_pic_2">
<span title="<?php echo $cfg_models->pic_2->FldTitle() ? $cfg_models->pic_2->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_models->pic_2->ReadOnly || $cfg_models->pic_2->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_models" data-field="x_pic_2" name="x_pic_2" id="x_pic_2"<?php echo $cfg_models->pic_2->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_pic_2" id= "fn_x_pic_2" value="<?php echo $cfg_models->pic_2->Upload->FileName ?>">
<?php if (@$_POST["fa_x_pic_2"] == "0") { ?>
<input type="hidden" name="fa_x_pic_2" id= "fa_x_pic_2" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_pic_2" id= "fa_x_pic_2" value="1">
<?php } ?>
<input type="hidden" name="fs_x_pic_2" id= "fs_x_pic_2" value="255">
<input type="hidden" name="fx_x_pic_2" id= "fx_x_pic_2" value="<?php echo $cfg_models->pic_2->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_pic_2" id= "fm_x_pic_2" value="<?php echo $cfg_models->pic_2->UploadMaxFileSize ?>">
</div>
<table id="ft_x_pic_2" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_models->pic_2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->pic_3->Visible) { // pic_3 ?>
	<div id="r_pic_3" class="form-group">
		<label id="elh_cfg_models_pic_3" class="col-sm-2 control-label ewLabel"><?php echo $cfg_models->pic_3->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_models->pic_3->CellAttributes() ?>>
<span id="el_cfg_models_pic_3">
<div id="fd_x_pic_3">
<span title="<?php echo $cfg_models->pic_3->FldTitle() ? $cfg_models->pic_3->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_models->pic_3->ReadOnly || $cfg_models->pic_3->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_models" data-field="x_pic_3" name="x_pic_3" id="x_pic_3"<?php echo $cfg_models->pic_3->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_pic_3" id= "fn_x_pic_3" value="<?php echo $cfg_models->pic_3->Upload->FileName ?>">
<?php if (@$_POST["fa_x_pic_3"] == "0") { ?>
<input type="hidden" name="fa_x_pic_3" id= "fa_x_pic_3" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_pic_3" id= "fa_x_pic_3" value="1">
<?php } ?>
<input type="hidden" name="fs_x_pic_3" id= "fs_x_pic_3" value="255">
<input type="hidden" name="fx_x_pic_3" id= "fx_x_pic_3" value="<?php echo $cfg_models->pic_3->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_pic_3" id= "fm_x_pic_3" value="<?php echo $cfg_models->pic_3->UploadMaxFileSize ?>">
</div>
<table id="ft_x_pic_3" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_models->pic_3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->pic_4->Visible) { // pic_4 ?>
	<div id="r_pic_4" class="form-group">
		<label id="elh_cfg_models_pic_4" class="col-sm-2 control-label ewLabel"><?php echo $cfg_models->pic_4->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_models->pic_4->CellAttributes() ?>>
<span id="el_cfg_models_pic_4">
<div id="fd_x_pic_4">
<span title="<?php echo $cfg_models->pic_4->FldTitle() ? $cfg_models->pic_4->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_models->pic_4->ReadOnly || $cfg_models->pic_4->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_models" data-field="x_pic_4" name="x_pic_4" id="x_pic_4"<?php echo $cfg_models->pic_4->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_pic_4" id= "fn_x_pic_4" value="<?php echo $cfg_models->pic_4->Upload->FileName ?>">
<?php if (@$_POST["fa_x_pic_4"] == "0") { ?>
<input type="hidden" name="fa_x_pic_4" id= "fa_x_pic_4" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_pic_4" id= "fa_x_pic_4" value="1">
<?php } ?>
<input type="hidden" name="fs_x_pic_4" id= "fs_x_pic_4" value="255">
<input type="hidden" name="fx_x_pic_4" id= "fx_x_pic_4" value="<?php echo $cfg_models->pic_4->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_pic_4" id= "fm_x_pic_4" value="<?php echo $cfg_models->pic_4->UploadMaxFileSize ?>">
</div>
<table id="ft_x_pic_4" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_models->pic_4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->pic_5->Visible) { // pic_5 ?>
	<div id="r_pic_5" class="form-group">
		<label id="elh_cfg_models_pic_5" class="col-sm-2 control-label ewLabel"><?php echo $cfg_models->pic_5->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_models->pic_5->CellAttributes() ?>>
<span id="el_cfg_models_pic_5">
<div id="fd_x_pic_5">
<span title="<?php echo $cfg_models->pic_5->FldTitle() ? $cfg_models->pic_5->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_models->pic_5->ReadOnly || $cfg_models->pic_5->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_models" data-field="x_pic_5" name="x_pic_5" id="x_pic_5"<?php echo $cfg_models->pic_5->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_pic_5" id= "fn_x_pic_5" value="<?php echo $cfg_models->pic_5->Upload->FileName ?>">
<?php if (@$_POST["fa_x_pic_5"] == "0") { ?>
<input type="hidden" name="fa_x_pic_5" id= "fa_x_pic_5" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_pic_5" id= "fa_x_pic_5" value="1">
<?php } ?>
<input type="hidden" name="fs_x_pic_5" id= "fs_x_pic_5" value="255">
<input type="hidden" name="fx_x_pic_5" id= "fx_x_pic_5" value="<?php echo $cfg_models->pic_5->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_pic_5" id= "fm_x_pic_5" value="<?php echo $cfg_models->pic_5->UploadMaxFileSize ?>">
</div>
<table id="ft_x_pic_5" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_models->pic_5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->details->Visible) { // details ?>
	<div id="r_details" class="form-group">
		<label id="elh_cfg_models_details" class="col-sm-2 control-label ewLabel"><?php echo $cfg_models->details->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_models->details->CellAttributes() ?>>
<span id="el_cfg_models_details">
<?php ew_AppendClass($cfg_models->details->EditAttrs["class"], "editor"); ?>
<textarea data-table="cfg_models" data-field="x_details" name="x_details" id="x_details" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($cfg_models->details->getPlaceHolder()) ?>"<?php echo $cfg_models->details->EditAttributes() ?>><?php echo $cfg_models->details->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fcfg_modelsedit", "x_details", 35, 4, <?php echo ($cfg_models->details->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $cfg_models->details->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_cfg_models_status" class="col-sm-2 control-label ewLabel"><?php echo $cfg_models->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_models->status->CellAttributes() ?>>
<span id="el_cfg_models_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="cfg_models" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_models->status->DisplayValueSeparator) ? json_encode($cfg_models->status->DisplayValueSeparator) : $cfg_models->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $cfg_models->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_models->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_models->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_models" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_models->status->EditAttributes() ?>><?php echo $cfg_models->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_models->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_models" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_models->status->CurrentValue) ?>" checked<?php echo $cfg_models->status->EditAttributes() ?>><?php echo $cfg_models->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $cfg_models->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="cfg_models" data-field="x_ID" name="x_ID" id="x_ID" value="<?php echo ew_HtmlEncode($cfg_models->ID->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cfg_models_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcfg_modelsedit.Init();
</script>
<?php
$cfg_models_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_models_edit->Page_Terminate();
?>
