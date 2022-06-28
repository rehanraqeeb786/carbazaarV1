<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_advertisement_spacesinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_advertisement_spaces_edit = NULL; // Initialize page object first

class ccfg_advertisement_spaces_edit extends ccfg_advertisement_spaces {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_advertisement_spaces';

	// Page object name
	var $PageObjName = 'cfg_advertisement_spaces_edit';

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

		// Table object (cfg_advertisement_spaces)
		if (!isset($GLOBALS["cfg_advertisement_spaces"]) || get_class($GLOBALS["cfg_advertisement_spaces"]) == "ccfg_advertisement_spaces") {
			$GLOBALS["cfg_advertisement_spaces"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_advertisement_spaces"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cfg_advertisement_spaces', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_advertisement_spaceslist.php"));
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
		global $EW_EXPORT, $cfg_advertisement_spaces;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_advertisement_spaces);
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
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
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
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("cfg_advertisement_spaceslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("cfg_advertisement_spaceslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "cfg_advertisement_spaceslist.php")
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
		$this->ad_image->Upload->Index = $objForm->Index;
		$this->ad_image->Upload->UploadFile();
		$this->ad_image->CurrentValue = $this->ad_image->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->ad_space_name->FldIsDetailKey) {
			$this->ad_space_name->setFormValue($objForm->GetValue("x_ad_space_name"));
		}
		if (!$this->ad_type->FldIsDetailKey) {
			$this->ad_type->setFormValue($objForm->GetValue("x_ad_type"));
		}
		if (!$this->ad_placement->FldIsDetailKey) {
			$this->ad_placement->setFormValue($objForm->GetValue("x_ad_placement"));
		}
		if (!$this->ad_script->FldIsDetailKey) {
			$this->ad_script->setFormValue($objForm->GetValue("x_ad_script"));
		}
		if (!$this->link_for_image->FldIsDetailKey) {
			$this->link_for_image->setFormValue($objForm->GetValue("x_link_for_image"));
		}
		if (!$this->ad_from->FldIsDetailKey) {
			$this->ad_from->setFormValue($objForm->GetValue("x_ad_from"));
			$this->ad_from->CurrentValue = ew_UnFormatDateTime($this->ad_from->CurrentValue, 5);
		}
		if (!$this->ad_till->FldIsDetailKey) {
			$this->ad_till->setFormValue($objForm->GetValue("x_ad_till"));
			$this->ad_till->CurrentValue = ew_UnFormatDateTime($this->ad_till->CurrentValue, 5);
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->ad_space_name->CurrentValue = $this->ad_space_name->FormValue;
		$this->ad_type->CurrentValue = $this->ad_type->FormValue;
		$this->ad_placement->CurrentValue = $this->ad_placement->FormValue;
		$this->ad_script->CurrentValue = $this->ad_script->FormValue;
		$this->link_for_image->CurrentValue = $this->link_for_image->FormValue;
		$this->ad_from->CurrentValue = $this->ad_from->FormValue;
		$this->ad_from->CurrentValue = ew_UnFormatDateTime($this->ad_from->CurrentValue, 5);
		$this->ad_till->CurrentValue = $this->ad_till->FormValue;
		$this->ad_till->CurrentValue = ew_UnFormatDateTime($this->ad_till->CurrentValue, 5);
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
		$this->ad_space_name->setDbValue($rs->fields('ad_space_name'));
		$this->ad_type->setDbValue($rs->fields('ad_type'));
		$this->ad_placement->setDbValue($rs->fields('ad_placement'));
		$this->ad_script->setDbValue($rs->fields('ad_script'));
		$this->ad_image->Upload->DbValue = $rs->fields('ad_image');
		$this->ad_image->CurrentValue = $this->ad_image->Upload->DbValue;
		$this->link_for_image->setDbValue($rs->fields('link_for_image'));
		$this->ad_from->setDbValue($rs->fields('ad_from'));
		$this->ad_till->setDbValue($rs->fields('ad_till'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->ad_space_name->DbValue = $row['ad_space_name'];
		$this->ad_type->DbValue = $row['ad_type'];
		$this->ad_placement->DbValue = $row['ad_placement'];
		$this->ad_script->DbValue = $row['ad_script'];
		$this->ad_image->Upload->DbValue = $row['ad_image'];
		$this->link_for_image->DbValue = $row['link_for_image'];
		$this->ad_from->DbValue = $row['ad_from'];
		$this->ad_till->DbValue = $row['ad_till'];
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
		// id
		// ad_space_name
		// ad_type
		// ad_placement
		// ad_script
		// ad_image
		// link_for_image
		// ad_from
		// ad_till
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ad_space_name
		$this->ad_space_name->ViewValue = $this->ad_space_name->CurrentValue;
		$this->ad_space_name->ViewCustomAttributes = "";

		// ad_type
		if (strval($this->ad_type->CurrentValue) <> "") {
			$this->ad_type->ViewValue = $this->ad_type->OptionCaption($this->ad_type->CurrentValue);
		} else {
			$this->ad_type->ViewValue = NULL;
		}
		$this->ad_type->ViewCustomAttributes = "";

		// ad_placement
		if (strval($this->ad_placement->CurrentValue) <> "") {
			$this->ad_placement->ViewValue = $this->ad_placement->OptionCaption($this->ad_placement->CurrentValue);
		} else {
			$this->ad_placement->ViewValue = NULL;
		}
		$this->ad_placement->ViewCustomAttributes = "";

		// ad_script
		$this->ad_script->ViewValue = $this->ad_script->CurrentValue;
		$this->ad_script->ViewCustomAttributes = "";

		// ad_image
		if (!ew_Empty($this->ad_image->Upload->DbValue)) {
			$this->ad_image->ImageAlt = $this->ad_image->FldAlt();
			$this->ad_image->ViewValue = $this->ad_image->Upload->DbValue;
		} else {
			$this->ad_image->ViewValue = "";
		}
		$this->ad_image->ViewCustomAttributes = "";

		// link_for_image
		$this->link_for_image->ViewValue = $this->link_for_image->CurrentValue;
		$this->link_for_image->ViewCustomAttributes = "";

		// ad_from
		$this->ad_from->ViewValue = $this->ad_from->CurrentValue;
		$this->ad_from->ViewValue = ew_FormatDateTime($this->ad_from->ViewValue, 5);
		$this->ad_from->ViewCustomAttributes = "";

		// ad_till
		$this->ad_till->ViewValue = $this->ad_till->CurrentValue;
		$this->ad_till->ViewValue = ew_FormatDateTime($this->ad_till->ViewValue, 5);
		$this->ad_till->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// ad_space_name
			$this->ad_space_name->LinkCustomAttributes = "";
			$this->ad_space_name->HrefValue = "";
			$this->ad_space_name->TooltipValue = "";

			// ad_type
			$this->ad_type->LinkCustomAttributes = "";
			$this->ad_type->HrefValue = "";
			$this->ad_type->TooltipValue = "";

			// ad_placement
			$this->ad_placement->LinkCustomAttributes = "";
			$this->ad_placement->HrefValue = "";
			$this->ad_placement->TooltipValue = "";

			// ad_script
			$this->ad_script->LinkCustomAttributes = "";
			$this->ad_script->HrefValue = "";
			$this->ad_script->TooltipValue = "";

			// ad_image
			$this->ad_image->LinkCustomAttributes = "";
			if (!ew_Empty($this->ad_image->Upload->DbValue)) {
				$this->ad_image->HrefValue = ew_GetFileUploadUrl($this->ad_image, $this->ad_image->Upload->DbValue); // Add prefix/suffix
				$this->ad_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->ad_image->HrefValue = ew_ConvertFullUrl($this->ad_image->HrefValue);
			} else {
				$this->ad_image->HrefValue = "";
			}
			$this->ad_image->HrefValue2 = $this->ad_image->UploadPath . $this->ad_image->Upload->DbValue;
			$this->ad_image->TooltipValue = "";
			if ($this->ad_image->UseColorbox) {
				if (ew_Empty($this->ad_image->TooltipValue))
					$this->ad_image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->ad_image->LinkAttrs["data-rel"] = "cfg_advertisement_spaces_x_ad_image";
				ew_AppendClass($this->ad_image->LinkAttrs["class"], "ewLightbox");
			}

			// link_for_image
			$this->link_for_image->LinkCustomAttributes = "";
			$this->link_for_image->HrefValue = "";
			$this->link_for_image->TooltipValue = "";

			// ad_from
			$this->ad_from->LinkCustomAttributes = "";
			$this->ad_from->HrefValue = "";
			$this->ad_from->TooltipValue = "";

			// ad_till
			$this->ad_till->LinkCustomAttributes = "";
			$this->ad_till->HrefValue = "";
			$this->ad_till->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// ad_space_name
			$this->ad_space_name->EditAttrs["class"] = "form-control";
			$this->ad_space_name->EditCustomAttributes = "";
			$this->ad_space_name->EditValue = ew_HtmlEncode($this->ad_space_name->CurrentValue);
			$this->ad_space_name->PlaceHolder = ew_RemoveHtml($this->ad_space_name->FldCaption());

			// ad_type
			$this->ad_type->EditCustomAttributes = "";
			$this->ad_type->EditValue = $this->ad_type->Options(FALSE);

			// ad_placement
			$this->ad_placement->EditCustomAttributes = "";
			$this->ad_placement->EditValue = $this->ad_placement->Options(FALSE);

			// ad_script
			$this->ad_script->EditAttrs["class"] = "form-control";
			$this->ad_script->EditCustomAttributes = "";
			$this->ad_script->EditValue = ew_HtmlEncode($this->ad_script->CurrentValue);
			$this->ad_script->PlaceHolder = ew_RemoveHtml($this->ad_script->FldCaption());

			// ad_image
			$this->ad_image->EditAttrs["class"] = "form-control";
			$this->ad_image->EditCustomAttributes = "";
			if (!ew_Empty($this->ad_image->Upload->DbValue)) {
				$this->ad_image->ImageAlt = $this->ad_image->FldAlt();
				$this->ad_image->EditValue = $this->ad_image->Upload->DbValue;
			} else {
				$this->ad_image->EditValue = "";
			}
			if (!ew_Empty($this->ad_image->CurrentValue))
				$this->ad_image->Upload->FileName = $this->ad_image->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->ad_image);

			// link_for_image
			$this->link_for_image->EditAttrs["class"] = "form-control";
			$this->link_for_image->EditCustomAttributes = "";
			$this->link_for_image->EditValue = ew_HtmlEncode($this->link_for_image->CurrentValue);
			$this->link_for_image->PlaceHolder = ew_RemoveHtml($this->link_for_image->FldCaption());

			// ad_from
			$this->ad_from->EditAttrs["class"] = "form-control";
			$this->ad_from->EditCustomAttributes = "";
			$this->ad_from->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->ad_from->CurrentValue, 5));
			$this->ad_from->PlaceHolder = ew_RemoveHtml($this->ad_from->FldCaption());

			// ad_till
			$this->ad_till->EditAttrs["class"] = "form-control";
			$this->ad_till->EditCustomAttributes = "";
			$this->ad_till->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->ad_till->CurrentValue, 5));
			$this->ad_till->PlaceHolder = ew_RemoveHtml($this->ad_till->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Edit refer script
			// ad_space_name

			$this->ad_space_name->LinkCustomAttributes = "";
			$this->ad_space_name->HrefValue = "";

			// ad_type
			$this->ad_type->LinkCustomAttributes = "";
			$this->ad_type->HrefValue = "";

			// ad_placement
			$this->ad_placement->LinkCustomAttributes = "";
			$this->ad_placement->HrefValue = "";

			// ad_script
			$this->ad_script->LinkCustomAttributes = "";
			$this->ad_script->HrefValue = "";

			// ad_image
			$this->ad_image->LinkCustomAttributes = "";
			if (!ew_Empty($this->ad_image->Upload->DbValue)) {
				$this->ad_image->HrefValue = ew_GetFileUploadUrl($this->ad_image, $this->ad_image->Upload->DbValue); // Add prefix/suffix
				$this->ad_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->ad_image->HrefValue = ew_ConvertFullUrl($this->ad_image->HrefValue);
			} else {
				$this->ad_image->HrefValue = "";
			}
			$this->ad_image->HrefValue2 = $this->ad_image->UploadPath . $this->ad_image->Upload->DbValue;

			// link_for_image
			$this->link_for_image->LinkCustomAttributes = "";
			$this->link_for_image->HrefValue = "";

			// ad_from
			$this->ad_from->LinkCustomAttributes = "";
			$this->ad_from->HrefValue = "";

			// ad_till
			$this->ad_till->LinkCustomAttributes = "";
			$this->ad_till->HrefValue = "";

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
		if (!$this->ad_space_name->FldIsDetailKey && !is_null($this->ad_space_name->FormValue) && $this->ad_space_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ad_space_name->FldCaption(), $this->ad_space_name->ReqErrMsg));
		}
		if ($this->ad_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ad_type->FldCaption(), $this->ad_type->ReqErrMsg));
		}
		if ($this->ad_placement->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ad_placement->FldCaption(), $this->ad_placement->ReqErrMsg));
		}
		if (!$this->link_for_image->FldIsDetailKey && !is_null($this->link_for_image->FormValue) && $this->link_for_image->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->link_for_image->FldCaption(), $this->link_for_image->ReqErrMsg));
		}
		if (!$this->ad_from->FldIsDetailKey && !is_null($this->ad_from->FormValue) && $this->ad_from->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ad_from->FldCaption(), $this->ad_from->ReqErrMsg));
		}
		if (!ew_CheckDate($this->ad_from->FormValue)) {
			ew_AddMessage($gsFormError, $this->ad_from->FldErrMsg());
		}
		if (!$this->ad_till->FldIsDetailKey && !is_null($this->ad_till->FormValue) && $this->ad_till->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ad_till->FldCaption(), $this->ad_till->ReqErrMsg));
		}
		if (!ew_CheckDate($this->ad_till->FormValue)) {
			ew_AddMessage($gsFormError, $this->ad_till->FldErrMsg());
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

			// ad_space_name
			$this->ad_space_name->SetDbValueDef($rsnew, $this->ad_space_name->CurrentValue, "", $this->ad_space_name->ReadOnly);

			// ad_type
			$this->ad_type->SetDbValueDef($rsnew, $this->ad_type->CurrentValue, "", $this->ad_type->ReadOnly);

			// ad_placement
			$this->ad_placement->SetDbValueDef($rsnew, $this->ad_placement->CurrentValue, "", $this->ad_placement->ReadOnly);

			// ad_script
			$this->ad_script->SetDbValueDef($rsnew, $this->ad_script->CurrentValue, NULL, $this->ad_script->ReadOnly);

			// ad_image
			if ($this->ad_image->Visible && !$this->ad_image->ReadOnly && !$this->ad_image->Upload->KeepFile) {
				$this->ad_image->Upload->DbValue = $rsold['ad_image']; // Get original value
				if ($this->ad_image->Upload->FileName == "") {
					$rsnew['ad_image'] = NULL;
				} else {
					$rsnew['ad_image'] = $this->ad_image->Upload->FileName;
				}
			}

			// link_for_image
			$this->link_for_image->SetDbValueDef($rsnew, $this->link_for_image->CurrentValue, "", $this->link_for_image->ReadOnly);

			// ad_from
			$this->ad_from->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->ad_from->CurrentValue, 5), ew_CurrentDate(), $this->ad_from->ReadOnly);

			// ad_till
			$this->ad_till->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->ad_till->CurrentValue, 5), ew_CurrentDate(), $this->ad_till->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, ((strval($this->status->CurrentValue) == "1") ? "1" : "0"), 0, $this->status->ReadOnly);
			if ($this->ad_image->Visible && !$this->ad_image->Upload->KeepFile) {
				if (!ew_Empty($this->ad_image->Upload->Value)) {
					$rsnew['ad_image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->ad_image->UploadPath), $rsnew['ad_image']); // Get new file name
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
					if ($this->ad_image->Visible && !$this->ad_image->Upload->KeepFile) {
						if (!ew_Empty($this->ad_image->Upload->Value)) {
							$this->ad_image->Upload->SaveToFile($this->ad_image->UploadPath, $rsnew['ad_image'], TRUE);
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

		// ad_image
		ew_CleanUploadTempPath($this->ad_image, $this->ad_image->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_advertisement_spaceslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_advertisement_spaces_edit)) $cfg_advertisement_spaces_edit = new ccfg_advertisement_spaces_edit();

// Page init
$cfg_advertisement_spaces_edit->Page_Init();

// Page main
$cfg_advertisement_spaces_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_advertisement_spaces_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fcfg_advertisement_spacesedit = new ew_Form("fcfg_advertisement_spacesedit", "edit");

// Validate form
fcfg_advertisement_spacesedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ad_space_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_advertisement_spaces->ad_space_name->FldCaption(), $cfg_advertisement_spaces->ad_space_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ad_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_advertisement_spaces->ad_type->FldCaption(), $cfg_advertisement_spaces->ad_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ad_placement");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_advertisement_spaces->ad_placement->FldCaption(), $cfg_advertisement_spaces->ad_placement->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_link_for_image");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_advertisement_spaces->link_for_image->FldCaption(), $cfg_advertisement_spaces->link_for_image->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ad_from");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_advertisement_spaces->ad_from->FldCaption(), $cfg_advertisement_spaces->ad_from->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ad_from");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_advertisement_spaces->ad_from->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ad_till");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_advertisement_spaces->ad_till->FldCaption(), $cfg_advertisement_spaces->ad_till->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ad_till");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_advertisement_spaces->ad_till->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_advertisement_spaces->status->FldCaption(), $cfg_advertisement_spaces->status->ReqErrMsg)) ?>");

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
fcfg_advertisement_spacesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_advertisement_spacesedit.ValidateRequired = true;
<?php } else { ?>
fcfg_advertisement_spacesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_advertisement_spacesedit.Lists["x_ad_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_advertisement_spacesedit.Lists["x_ad_type"].Options = <?php echo json_encode($cfg_advertisement_spaces->ad_type->Options()) ?>;
fcfg_advertisement_spacesedit.Lists["x_ad_placement"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_advertisement_spacesedit.Lists["x_ad_placement"].Options = <?php echo json_encode($cfg_advertisement_spaces->ad_placement->Options()) ?>;
fcfg_advertisement_spacesedit.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_advertisement_spacesedit.Lists["x_status"].Options = <?php echo json_encode($cfg_advertisement_spaces->status->Options()) ?>;

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
<?php $cfg_advertisement_spaces_edit->ShowPageHeader(); ?>
<?php
$cfg_advertisement_spaces_edit->ShowMessage();
?>
<form name="fcfg_advertisement_spacesedit" id="fcfg_advertisement_spacesedit" class="<?php echo $cfg_advertisement_spaces_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_advertisement_spaces_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_advertisement_spaces_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_advertisement_spaces">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($cfg_advertisement_spaces->ad_space_name->Visible) { // ad_space_name ?>
	<div id="r_ad_space_name" class="form-group">
		<label id="elh_cfg_advertisement_spaces_ad_space_name" for="x_ad_space_name" class="col-sm-2 control-label ewLabel"><?php echo $cfg_advertisement_spaces->ad_space_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_advertisement_spaces->ad_space_name->CellAttributes() ?>>
<span id="el_cfg_advertisement_spaces_ad_space_name">
<input type="text" data-table="cfg_advertisement_spaces" data-field="x_ad_space_name" name="x_ad_space_name" id="x_ad_space_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_space_name->getPlaceHolder()) ?>" value="<?php echo $cfg_advertisement_spaces->ad_space_name->EditValue ?>"<?php echo $cfg_advertisement_spaces->ad_space_name->EditAttributes() ?>>
</span>
<?php echo $cfg_advertisement_spaces->ad_space_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_type->Visible) { // ad_type ?>
	<div id="r_ad_type" class="form-group">
		<label id="elh_cfg_advertisement_spaces_ad_type" class="col-sm-2 control-label ewLabel"><?php echo $cfg_advertisement_spaces->ad_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_advertisement_spaces->ad_type->CellAttributes() ?>>
<span id="el_cfg_advertisement_spaces_ad_type">
<div id="tp_x_ad_type" class="ewTemplate"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_advertisement_spaces->ad_type->DisplayValueSeparator) ? json_encode($cfg_advertisement_spaces->ad_type->DisplayValueSeparator) : $cfg_advertisement_spaces->ad_type->DisplayValueSeparator) ?>" name="x_ad_type" id="x_ad_type" value="{value}"<?php echo $cfg_advertisement_spaces->ad_type->EditAttributes() ?>></div>
<div id="dsl_x_ad_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_advertisement_spaces->ad_type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_advertisement_spaces->ad_type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_type" name="x_ad_type" id="x_ad_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_advertisement_spaces->ad_type->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->ad_type->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_advertisement_spaces->ad_type->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_type" name="x_ad_type" id="x_ad_type_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_type->CurrentValue) ?>" checked<?php echo $cfg_advertisement_spaces->ad_type->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->ad_type->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $cfg_advertisement_spaces->ad_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_placement->Visible) { // ad_placement ?>
	<div id="r_ad_placement" class="form-group">
		<label id="elh_cfg_advertisement_spaces_ad_placement" class="col-sm-2 control-label ewLabel"><?php echo $cfg_advertisement_spaces->ad_placement->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_advertisement_spaces->ad_placement->CellAttributes() ?>>
<span id="el_cfg_advertisement_spaces_ad_placement">
<div id="tp_x_ad_placement" class="ewTemplate"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_placement" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_advertisement_spaces->ad_placement->DisplayValueSeparator) ? json_encode($cfg_advertisement_spaces->ad_placement->DisplayValueSeparator) : $cfg_advertisement_spaces->ad_placement->DisplayValueSeparator) ?>" name="x_ad_placement" id="x_ad_placement" value="{value}"<?php echo $cfg_advertisement_spaces->ad_placement->EditAttributes() ?>></div>
<div id="dsl_x_ad_placement" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_advertisement_spaces->ad_placement->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_advertisement_spaces->ad_placement->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_placement" name="x_ad_placement" id="x_ad_placement_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_advertisement_spaces->ad_placement->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->ad_placement->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_advertisement_spaces->ad_placement->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_placement" name="x_ad_placement" id="x_ad_placement_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_placement->CurrentValue) ?>" checked<?php echo $cfg_advertisement_spaces->ad_placement->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->ad_placement->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $cfg_advertisement_spaces->ad_placement->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_script->Visible) { // ad_script ?>
	<div id="r_ad_script" class="form-group">
		<label id="elh_cfg_advertisement_spaces_ad_script" for="x_ad_script" class="col-sm-2 control-label ewLabel"><?php echo $cfg_advertisement_spaces->ad_script->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_advertisement_spaces->ad_script->CellAttributes() ?>>
<span id="el_cfg_advertisement_spaces_ad_script">
<textarea data-table="cfg_advertisement_spaces" data-field="x_ad_script" name="x_ad_script" id="x_ad_script" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_script->getPlaceHolder()) ?>"<?php echo $cfg_advertisement_spaces->ad_script->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->ad_script->EditValue ?></textarea>
</span>
<?php echo $cfg_advertisement_spaces->ad_script->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_image->Visible) { // ad_image ?>
	<div id="r_ad_image" class="form-group">
		<label id="elh_cfg_advertisement_spaces_ad_image" class="col-sm-2 control-label ewLabel"><?php echo $cfg_advertisement_spaces->ad_image->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_advertisement_spaces->ad_image->CellAttributes() ?>>
<span id="el_cfg_advertisement_spaces_ad_image">
<div id="fd_x_ad_image">
<span title="<?php echo $cfg_advertisement_spaces->ad_image->FldTitle() ? $cfg_advertisement_spaces->ad_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_advertisement_spaces->ad_image->ReadOnly || $cfg_advertisement_spaces->ad_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_advertisement_spaces" data-field="x_ad_image" name="x_ad_image" id="x_ad_image"<?php echo $cfg_advertisement_spaces->ad_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_ad_image" id= "fn_x_ad_image" value="<?php echo $cfg_advertisement_spaces->ad_image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_ad_image"] == "0") { ?>
<input type="hidden" name="fa_x_ad_image" id= "fa_x_ad_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_ad_image" id= "fa_x_ad_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_ad_image" id= "fs_x_ad_image" value="255">
<input type="hidden" name="fx_x_ad_image" id= "fx_x_ad_image" value="<?php echo $cfg_advertisement_spaces->ad_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_ad_image" id= "fm_x_ad_image" value="<?php echo $cfg_advertisement_spaces->ad_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_ad_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_advertisement_spaces->ad_image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->link_for_image->Visible) { // link_for_image ?>
	<div id="r_link_for_image" class="form-group">
		<label id="elh_cfg_advertisement_spaces_link_for_image" for="x_link_for_image" class="col-sm-2 control-label ewLabel"><?php echo $cfg_advertisement_spaces->link_for_image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_advertisement_spaces->link_for_image->CellAttributes() ?>>
<span id="el_cfg_advertisement_spaces_link_for_image">
<input type="text" data-table="cfg_advertisement_spaces" data-field="x_link_for_image" name="x_link_for_image" id="x_link_for_image" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->link_for_image->getPlaceHolder()) ?>" value="<?php echo $cfg_advertisement_spaces->link_for_image->EditValue ?>"<?php echo $cfg_advertisement_spaces->link_for_image->EditAttributes() ?>>
</span>
<?php echo $cfg_advertisement_spaces->link_for_image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_from->Visible) { // ad_from ?>
	<div id="r_ad_from" class="form-group">
		<label id="elh_cfg_advertisement_spaces_ad_from" for="x_ad_from" class="col-sm-2 control-label ewLabel"><?php echo $cfg_advertisement_spaces->ad_from->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_advertisement_spaces->ad_from->CellAttributes() ?>>
<span id="el_cfg_advertisement_spaces_ad_from">
<input type="text" data-table="cfg_advertisement_spaces" data-field="x_ad_from" data-format="5" name="x_ad_from" id="x_ad_from" placeholder="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_from->getPlaceHolder()) ?>" value="<?php echo $cfg_advertisement_spaces->ad_from->EditValue ?>"<?php echo $cfg_advertisement_spaces->ad_from->EditAttributes() ?>>
<?php if (!$cfg_advertisement_spaces->ad_from->ReadOnly && !$cfg_advertisement_spaces->ad_from->Disabled && !isset($cfg_advertisement_spaces->ad_from->EditAttrs["readonly"]) && !isset($cfg_advertisement_spaces->ad_from->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcfg_advertisement_spacesedit", "x_ad_from", "%Y/%m/%d");
</script>
<?php } ?>
</span>
<?php echo $cfg_advertisement_spaces->ad_from->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_till->Visible) { // ad_till ?>
	<div id="r_ad_till" class="form-group">
		<label id="elh_cfg_advertisement_spaces_ad_till" for="x_ad_till" class="col-sm-2 control-label ewLabel"><?php echo $cfg_advertisement_spaces->ad_till->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_advertisement_spaces->ad_till->CellAttributes() ?>>
<span id="el_cfg_advertisement_spaces_ad_till">
<input type="text" data-table="cfg_advertisement_spaces" data-field="x_ad_till" data-format="5" name="x_ad_till" id="x_ad_till" placeholder="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_till->getPlaceHolder()) ?>" value="<?php echo $cfg_advertisement_spaces->ad_till->EditValue ?>"<?php echo $cfg_advertisement_spaces->ad_till->EditAttributes() ?>>
<?php if (!$cfg_advertisement_spaces->ad_till->ReadOnly && !$cfg_advertisement_spaces->ad_till->Disabled && !isset($cfg_advertisement_spaces->ad_till->EditAttrs["readonly"]) && !isset($cfg_advertisement_spaces->ad_till->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcfg_advertisement_spacesedit", "x_ad_till", "%Y/%m/%d");
</script>
<?php } ?>
</span>
<?php echo $cfg_advertisement_spaces->ad_till->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_cfg_advertisement_spaces_status" class="col-sm-2 control-label ewLabel"><?php echo $cfg_advertisement_spaces->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_advertisement_spaces->status->CellAttributes() ?>>
<span id="el_cfg_advertisement_spaces_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_advertisement_spaces->status->DisplayValueSeparator) ? json_encode($cfg_advertisement_spaces->status->DisplayValueSeparator) : $cfg_advertisement_spaces->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $cfg_advertisement_spaces->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_advertisement_spaces->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_advertisement_spaces->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_advertisement_spaces->status->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_advertisement_spaces->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->status->CurrentValue) ?>" checked<?php echo $cfg_advertisement_spaces->status->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $cfg_advertisement_spaces->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="cfg_advertisement_spaces" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->id->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cfg_advertisement_spaces_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcfg_advertisement_spacesedit.Init();
</script>
<?php
$cfg_advertisement_spaces_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_advertisement_spaces_edit->Page_Terminate();
?>
