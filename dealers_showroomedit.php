<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "dealers_showroominfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$dealers_showroom_edit = NULL; // Initialize page object first

class cdealers_showroom_edit extends cdealers_showroom {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'dealers_showroom';

	// Page object name
	var $PageObjName = 'dealers_showroom_edit';

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

		// Table object (dealers_showroom)
		if (!isset($GLOBALS["dealers_showroom"]) || get_class($GLOBALS["dealers_showroom"]) == "cdealers_showroom") {
			$GLOBALS["dealers_showroom"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dealers_showroom"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'dealers_showroom', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("dealers_showroomlist.php"));
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
		$this->ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $dealers_showroom;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($dealers_showroom);
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
			$this->Page_Terminate("dealers_showroomlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("dealers_showroomlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "dealers_showroomlist.php")
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
		if (!$this->ID->FldIsDetailKey)
			$this->ID->setFormValue($objForm->GetValue("x_ID"));
		if (!$this->customer_id->FldIsDetailKey) {
			$this->customer_id->setFormValue($objForm->GetValue("x_customer_id"));
		}
		if (!$this->showroom_name->FldIsDetailKey) {
			$this->showroom_name->setFormValue($objForm->GetValue("x_showroom_name"));
		}
		if (!$this->showroom_address->FldIsDetailKey) {
			$this->showroom_address->setFormValue($objForm->GetValue("x_showroom_address"));
		}
		if (!$this->city_id->FldIsDetailKey) {
			$this->city_id->setFormValue($objForm->GetValue("x_city_id"));
		}
		if (!$this->owner_name->FldIsDetailKey) {
			$this->owner_name->setFormValue($objForm->GetValue("x_owner_name"));
		}
		if (!$this->contact_1->FldIsDetailKey) {
			$this->contact_1->setFormValue($objForm->GetValue("x_contact_1"));
		}
		if (!$this->contat_2->FldIsDetailKey) {
			$this->contat_2->setFormValue($objForm->GetValue("x_contat_2"));
		}
		if (!$this->showroom_logo_link->FldIsDetailKey) {
			$this->showroom_logo_link->setFormValue($objForm->GetValue("x_showroom_logo_link"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->created_at->FldIsDetailKey) {
			$this->created_at->setFormValue($objForm->GetValue("x_created_at"));
			$this->created_at->CurrentValue = ew_UnFormatDateTime($this->created_at->CurrentValue, 5);
		}
		if (!$this->updated_at->FldIsDetailKey) {
			$this->updated_at->setFormValue($objForm->GetValue("x_updated_at"));
			$this->updated_at->CurrentValue = ew_UnFormatDateTime($this->updated_at->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->ID->CurrentValue = $this->ID->FormValue;
		$this->customer_id->CurrentValue = $this->customer_id->FormValue;
		$this->showroom_name->CurrentValue = $this->showroom_name->FormValue;
		$this->showroom_address->CurrentValue = $this->showroom_address->FormValue;
		$this->city_id->CurrentValue = $this->city_id->FormValue;
		$this->owner_name->CurrentValue = $this->owner_name->FormValue;
		$this->contact_1->CurrentValue = $this->contact_1->FormValue;
		$this->contat_2->CurrentValue = $this->contat_2->FormValue;
		$this->showroom_logo_link->CurrentValue = $this->showroom_logo_link->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->created_at->CurrentValue = $this->created_at->FormValue;
		$this->created_at->CurrentValue = ew_UnFormatDateTime($this->created_at->CurrentValue, 5);
		$this->updated_at->CurrentValue = $this->updated_at->FormValue;
		$this->updated_at->CurrentValue = ew_UnFormatDateTime($this->updated_at->CurrentValue, 5);
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
		$this->customer_id->setDbValue($rs->fields('customer_id'));
		$this->showroom_name->setDbValue($rs->fields('showroom_name'));
		$this->showroom_address->setDbValue($rs->fields('showroom_address'));
		$this->city_id->setDbValue($rs->fields('city_id'));
		$this->owner_name->setDbValue($rs->fields('owner_name'));
		$this->contact_1->setDbValue($rs->fields('contact_1'));
		$this->contat_2->setDbValue($rs->fields('contat_2'));
		$this->showroom_logo_link->setDbValue($rs->fields('showroom_logo_link'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->customer_id->DbValue = $row['customer_id'];
		$this->showroom_name->DbValue = $row['showroom_name'];
		$this->showroom_address->DbValue = $row['showroom_address'];
		$this->city_id->DbValue = $row['city_id'];
		$this->owner_name->DbValue = $row['owner_name'];
		$this->contact_1->DbValue = $row['contact_1'];
		$this->contat_2->DbValue = $row['contat_2'];
		$this->showroom_logo_link->DbValue = $row['showroom_logo_link'];
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
		// customer_id
		// showroom_name
		// showroom_address
		// city_id
		// owner_name
		// contact_1
		// contat_2
		// showroom_logo_link
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ID
		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// customer_id
		$this->customer_id->ViewValue = $this->customer_id->CurrentValue;
		$this->customer_id->ViewCustomAttributes = "";

		// showroom_name
		$this->showroom_name->ViewValue = $this->showroom_name->CurrentValue;
		$this->showroom_name->ViewCustomAttributes = "";

		// showroom_address
		$this->showroom_address->ViewValue = $this->showroom_address->CurrentValue;
		$this->showroom_address->ViewCustomAttributes = "";

		// city_id
		$this->city_id->ViewValue = $this->city_id->CurrentValue;
		$this->city_id->ViewCustomAttributes = "";

		// owner_name
		$this->owner_name->ViewValue = $this->owner_name->CurrentValue;
		$this->owner_name->ViewCustomAttributes = "";

		// contact_1
		$this->contact_1->ViewValue = $this->contact_1->CurrentValue;
		$this->contact_1->ViewCustomAttributes = "";

		// contat_2
		$this->contat_2->ViewValue = $this->contat_2->CurrentValue;
		$this->contat_2->ViewCustomAttributes = "";

		// showroom_logo_link
		$this->showroom_logo_link->ViewValue = $this->showroom_logo_link->CurrentValue;
		$this->showroom_logo_link->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "1";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "0";
		}
		$this->status->ViewCustomAttributes = "";

		// created_at
		$this->created_at->ViewValue = $this->created_at->CurrentValue;
		$this->created_at->ViewValue = ew_FormatDateTime($this->created_at->ViewValue, 5);
		$this->created_at->ViewCustomAttributes = "";

		// updated_at
		$this->updated_at->ViewValue = $this->updated_at->CurrentValue;
		$this->updated_at->ViewValue = ew_FormatDateTime($this->updated_at->ViewValue, 5);
		$this->updated_at->ViewCustomAttributes = "";

			// ID
			$this->ID->LinkCustomAttributes = "";
			$this->ID->HrefValue = "";
			$this->ID->TooltipValue = "";

			// customer_id
			$this->customer_id->LinkCustomAttributes = "";
			$this->customer_id->HrefValue = "";
			$this->customer_id->TooltipValue = "";

			// showroom_name
			$this->showroom_name->LinkCustomAttributes = "";
			$this->showroom_name->HrefValue = "";
			$this->showroom_name->TooltipValue = "";

			// showroom_address
			$this->showroom_address->LinkCustomAttributes = "";
			$this->showroom_address->HrefValue = "";
			$this->showroom_address->TooltipValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";
			$this->city_id->TooltipValue = "";

			// owner_name
			$this->owner_name->LinkCustomAttributes = "";
			$this->owner_name->HrefValue = "";
			$this->owner_name->TooltipValue = "";

			// contact_1
			$this->contact_1->LinkCustomAttributes = "";
			$this->contact_1->HrefValue = "";
			$this->contact_1->TooltipValue = "";

			// contat_2
			$this->contat_2->LinkCustomAttributes = "";
			$this->contat_2->HrefValue = "";
			$this->contat_2->TooltipValue = "";

			// showroom_logo_link
			$this->showroom_logo_link->LinkCustomAttributes = "";
			$this->showroom_logo_link->HrefValue = "";
			$this->showroom_logo_link->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// created_at
			$this->created_at->LinkCustomAttributes = "";
			$this->created_at->HrefValue = "";
			$this->created_at->TooltipValue = "";

			// updated_at
			$this->updated_at->LinkCustomAttributes = "";
			$this->updated_at->HrefValue = "";
			$this->updated_at->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// ID
			$this->ID->EditAttrs["class"] = "form-control";
			$this->ID->EditCustomAttributes = "";
			$this->ID->EditValue = $this->ID->CurrentValue;
			$this->ID->ViewCustomAttributes = "";

			// customer_id
			$this->customer_id->EditAttrs["class"] = "form-control";
			$this->customer_id->EditCustomAttributes = "";
			$this->customer_id->EditValue = ew_HtmlEncode($this->customer_id->CurrentValue);
			$this->customer_id->PlaceHolder = ew_RemoveHtml($this->customer_id->FldCaption());

			// showroom_name
			$this->showroom_name->EditAttrs["class"] = "form-control";
			$this->showroom_name->EditCustomAttributes = "";
			$this->showroom_name->EditValue = ew_HtmlEncode($this->showroom_name->CurrentValue);
			$this->showroom_name->PlaceHolder = ew_RemoveHtml($this->showroom_name->FldCaption());

			// showroom_address
			$this->showroom_address->EditAttrs["class"] = "form-control";
			$this->showroom_address->EditCustomAttributes = "";
			$this->showroom_address->EditValue = ew_HtmlEncode($this->showroom_address->CurrentValue);
			$this->showroom_address->PlaceHolder = ew_RemoveHtml($this->showroom_address->FldCaption());

			// city_id
			$this->city_id->EditAttrs["class"] = "form-control";
			$this->city_id->EditCustomAttributes = "";
			$this->city_id->EditValue = ew_HtmlEncode($this->city_id->CurrentValue);
			$this->city_id->PlaceHolder = ew_RemoveHtml($this->city_id->FldCaption());

			// owner_name
			$this->owner_name->EditAttrs["class"] = "form-control";
			$this->owner_name->EditCustomAttributes = "";
			$this->owner_name->EditValue = ew_HtmlEncode($this->owner_name->CurrentValue);
			$this->owner_name->PlaceHolder = ew_RemoveHtml($this->owner_name->FldCaption());

			// contact_1
			$this->contact_1->EditAttrs["class"] = "form-control";
			$this->contact_1->EditCustomAttributes = "";
			$this->contact_1->EditValue = ew_HtmlEncode($this->contact_1->CurrentValue);
			$this->contact_1->PlaceHolder = ew_RemoveHtml($this->contact_1->FldCaption());

			// contat_2
			$this->contat_2->EditAttrs["class"] = "form-control";
			$this->contat_2->EditCustomAttributes = "";
			$this->contat_2->EditValue = ew_HtmlEncode($this->contat_2->CurrentValue);
			$this->contat_2->PlaceHolder = ew_RemoveHtml($this->contat_2->FldCaption());

			// showroom_logo_link
			$this->showroom_logo_link->EditAttrs["class"] = "form-control";
			$this->showroom_logo_link->EditCustomAttributes = "";
			$this->showroom_logo_link->EditValue = ew_HtmlEncode($this->showroom_logo_link->CurrentValue);
			$this->showroom_logo_link->PlaceHolder = ew_RemoveHtml($this->showroom_logo_link->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// created_at
			$this->created_at->EditAttrs["class"] = "form-control";
			$this->created_at->EditCustomAttributes = "";
			$this->created_at->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created_at->CurrentValue, 5));
			$this->created_at->PlaceHolder = ew_RemoveHtml($this->created_at->FldCaption());

			// updated_at
			$this->updated_at->EditAttrs["class"] = "form-control";
			$this->updated_at->EditCustomAttributes = "";
			$this->updated_at->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->updated_at->CurrentValue, 5));
			$this->updated_at->PlaceHolder = ew_RemoveHtml($this->updated_at->FldCaption());

			// Edit refer script
			// ID

			$this->ID->LinkCustomAttributes = "";
			$this->ID->HrefValue = "";

			// customer_id
			$this->customer_id->LinkCustomAttributes = "";
			$this->customer_id->HrefValue = "";

			// showroom_name
			$this->showroom_name->LinkCustomAttributes = "";
			$this->showroom_name->HrefValue = "";

			// showroom_address
			$this->showroom_address->LinkCustomAttributes = "";
			$this->showroom_address->HrefValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";

			// owner_name
			$this->owner_name->LinkCustomAttributes = "";
			$this->owner_name->HrefValue = "";

			// contact_1
			$this->contact_1->LinkCustomAttributes = "";
			$this->contact_1->HrefValue = "";

			// contat_2
			$this->contat_2->LinkCustomAttributes = "";
			$this->contat_2->HrefValue = "";

			// showroom_logo_link
			$this->showroom_logo_link->LinkCustomAttributes = "";
			$this->showroom_logo_link->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// created_at
			$this->created_at->LinkCustomAttributes = "";
			$this->created_at->HrefValue = "";

			// updated_at
			$this->updated_at->LinkCustomAttributes = "";
			$this->updated_at->HrefValue = "";
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
		if (!$this->customer_id->FldIsDetailKey && !is_null($this->customer_id->FormValue) && $this->customer_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->customer_id->FldCaption(), $this->customer_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->customer_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->customer_id->FldErrMsg());
		}
		if (!$this->showroom_name->FldIsDetailKey && !is_null($this->showroom_name->FormValue) && $this->showroom_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->showroom_name->FldCaption(), $this->showroom_name->ReqErrMsg));
		}
		if (!$this->showroom_address->FldIsDetailKey && !is_null($this->showroom_address->FormValue) && $this->showroom_address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->showroom_address->FldCaption(), $this->showroom_address->ReqErrMsg));
		}
		if (!$this->city_id->FldIsDetailKey && !is_null($this->city_id->FormValue) && $this->city_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->city_id->FldCaption(), $this->city_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->city_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->city_id->FldErrMsg());
		}
		if (!$this->owner_name->FldIsDetailKey && !is_null($this->owner_name->FormValue) && $this->owner_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->owner_name->FldCaption(), $this->owner_name->ReqErrMsg));
		}
		if (!$this->contact_1->FldIsDetailKey && !is_null($this->contact_1->FormValue) && $this->contact_1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->contact_1->FldCaption(), $this->contact_1->ReqErrMsg));
		}
		if ($this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!ew_CheckDate($this->created_at->FormValue)) {
			ew_AddMessage($gsFormError, $this->created_at->FldErrMsg());
		}
		if (!ew_CheckDate($this->updated_at->FormValue)) {
			ew_AddMessage($gsFormError, $this->updated_at->FldErrMsg());
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

			// customer_id
			$this->customer_id->SetDbValueDef($rsnew, $this->customer_id->CurrentValue, 0, $this->customer_id->ReadOnly);

			// showroom_name
			$this->showroom_name->SetDbValueDef($rsnew, $this->showroom_name->CurrentValue, "", $this->showroom_name->ReadOnly);

			// showroom_address
			$this->showroom_address->SetDbValueDef($rsnew, $this->showroom_address->CurrentValue, "", $this->showroom_address->ReadOnly);

			// city_id
			$this->city_id->SetDbValueDef($rsnew, $this->city_id->CurrentValue, 0, $this->city_id->ReadOnly);

			// owner_name
			$this->owner_name->SetDbValueDef($rsnew, $this->owner_name->CurrentValue, "", $this->owner_name->ReadOnly);

			// contact_1
			$this->contact_1->SetDbValueDef($rsnew, $this->contact_1->CurrentValue, "", $this->contact_1->ReadOnly);

			// contat_2
			$this->contat_2->SetDbValueDef($rsnew, $this->contat_2->CurrentValue, NULL, $this->contat_2->ReadOnly);

			// showroom_logo_link
			$this->showroom_logo_link->SetDbValueDef($rsnew, $this->showroom_logo_link->CurrentValue, NULL, $this->showroom_logo_link->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, ((strval($this->status->CurrentValue) == "1") ? "1" : "0"), 0, $this->status->ReadOnly);

			// created_at
			$this->created_at->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created_at->CurrentValue, 5), NULL, $this->created_at->ReadOnly);

			// updated_at
			$this->updated_at->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->updated_at->CurrentValue, 5), NULL, $this->updated_at->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("dealers_showroomlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($dealers_showroom_edit)) $dealers_showroom_edit = new cdealers_showroom_edit();

// Page init
$dealers_showroom_edit->Page_Init();

// Page main
$dealers_showroom_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dealers_showroom_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fdealers_showroomedit = new ew_Form("fdealers_showroomedit", "edit");

// Validate form
fdealers_showroomedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_customer_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dealers_showroom->customer_id->FldCaption(), $dealers_showroom->customer_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customer_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($dealers_showroom->customer_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_showroom_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dealers_showroom->showroom_name->FldCaption(), $dealers_showroom->showroom_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_showroom_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dealers_showroom->showroom_address->FldCaption(), $dealers_showroom->showroom_address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_city_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dealers_showroom->city_id->FldCaption(), $dealers_showroom->city_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_city_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($dealers_showroom->city_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_owner_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dealers_showroom->owner_name->FldCaption(), $dealers_showroom->owner_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_contact_1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dealers_showroom->contact_1->FldCaption(), $dealers_showroom->contact_1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dealers_showroom->status->FldCaption(), $dealers_showroom->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created_at");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($dealers_showroom->created_at->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_updated_at");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($dealers_showroom->updated_at->FldErrMsg()) ?>");

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
fdealers_showroomedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdealers_showroomedit.ValidateRequired = true;
<?php } else { ?>
fdealers_showroomedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdealers_showroomedit.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdealers_showroomedit.Lists["x_status"].Options = <?php echo json_encode($dealers_showroom->status->Options()) ?>;

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
<?php $dealers_showroom_edit->ShowPageHeader(); ?>
<?php
$dealers_showroom_edit->ShowMessage();
?>
<form name="fdealers_showroomedit" id="fdealers_showroomedit" class="<?php echo $dealers_showroom_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dealers_showroom_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dealers_showroom_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dealers_showroom">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($dealers_showroom->ID->Visible) { // ID ?>
	<div id="r_ID" class="form-group">
		<label id="elh_dealers_showroom_ID" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->ID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->ID->CellAttributes() ?>>
<span id="el_dealers_showroom_ID">
<span<?php echo $dealers_showroom->ID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dealers_showroom->ID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="dealers_showroom" data-field="x_ID" name="x_ID" id="x_ID" value="<?php echo ew_HtmlEncode($dealers_showroom->ID->CurrentValue) ?>">
<?php echo $dealers_showroom->ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->customer_id->Visible) { // customer_id ?>
	<div id="r_customer_id" class="form-group">
		<label id="elh_dealers_showroom_customer_id" for="x_customer_id" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->customer_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->customer_id->CellAttributes() ?>>
<span id="el_dealers_showroom_customer_id">
<input type="text" data-table="dealers_showroom" data-field="x_customer_id" name="x_customer_id" id="x_customer_id" size="30" placeholder="<?php echo ew_HtmlEncode($dealers_showroom->customer_id->getPlaceHolder()) ?>" value="<?php echo $dealers_showroom->customer_id->EditValue ?>"<?php echo $dealers_showroom->customer_id->EditAttributes() ?>>
</span>
<?php echo $dealers_showroom->customer_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->showroom_name->Visible) { // showroom_name ?>
	<div id="r_showroom_name" class="form-group">
		<label id="elh_dealers_showroom_showroom_name" for="x_showroom_name" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->showroom_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->showroom_name->CellAttributes() ?>>
<span id="el_dealers_showroom_showroom_name">
<input type="text" data-table="dealers_showroom" data-field="x_showroom_name" name="x_showroom_name" id="x_showroom_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($dealers_showroom->showroom_name->getPlaceHolder()) ?>" value="<?php echo $dealers_showroom->showroom_name->EditValue ?>"<?php echo $dealers_showroom->showroom_name->EditAttributes() ?>>
</span>
<?php echo $dealers_showroom->showroom_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->showroom_address->Visible) { // showroom_address ?>
	<div id="r_showroom_address" class="form-group">
		<label id="elh_dealers_showroom_showroom_address" for="x_showroom_address" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->showroom_address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->showroom_address->CellAttributes() ?>>
<span id="el_dealers_showroom_showroom_address">
<input type="text" data-table="dealers_showroom" data-field="x_showroom_address" name="x_showroom_address" id="x_showroom_address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($dealers_showroom->showroom_address->getPlaceHolder()) ?>" value="<?php echo $dealers_showroom->showroom_address->EditValue ?>"<?php echo $dealers_showroom->showroom_address->EditAttributes() ?>>
</span>
<?php echo $dealers_showroom->showroom_address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->city_id->Visible) { // city_id ?>
	<div id="r_city_id" class="form-group">
		<label id="elh_dealers_showroom_city_id" for="x_city_id" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->city_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->city_id->CellAttributes() ?>>
<span id="el_dealers_showroom_city_id">
<input type="text" data-table="dealers_showroom" data-field="x_city_id" name="x_city_id" id="x_city_id" size="30" placeholder="<?php echo ew_HtmlEncode($dealers_showroom->city_id->getPlaceHolder()) ?>" value="<?php echo $dealers_showroom->city_id->EditValue ?>"<?php echo $dealers_showroom->city_id->EditAttributes() ?>>
</span>
<?php echo $dealers_showroom->city_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->owner_name->Visible) { // owner_name ?>
	<div id="r_owner_name" class="form-group">
		<label id="elh_dealers_showroom_owner_name" for="x_owner_name" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->owner_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->owner_name->CellAttributes() ?>>
<span id="el_dealers_showroom_owner_name">
<input type="text" data-table="dealers_showroom" data-field="x_owner_name" name="x_owner_name" id="x_owner_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($dealers_showroom->owner_name->getPlaceHolder()) ?>" value="<?php echo $dealers_showroom->owner_name->EditValue ?>"<?php echo $dealers_showroom->owner_name->EditAttributes() ?>>
</span>
<?php echo $dealers_showroom->owner_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->contact_1->Visible) { // contact_1 ?>
	<div id="r_contact_1" class="form-group">
		<label id="elh_dealers_showroom_contact_1" for="x_contact_1" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->contact_1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->contact_1->CellAttributes() ?>>
<span id="el_dealers_showroom_contact_1">
<input type="text" data-table="dealers_showroom" data-field="x_contact_1" name="x_contact_1" id="x_contact_1" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($dealers_showroom->contact_1->getPlaceHolder()) ?>" value="<?php echo $dealers_showroom->contact_1->EditValue ?>"<?php echo $dealers_showroom->contact_1->EditAttributes() ?>>
</span>
<?php echo $dealers_showroom->contact_1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->contat_2->Visible) { // contat_2 ?>
	<div id="r_contat_2" class="form-group">
		<label id="elh_dealers_showroom_contat_2" for="x_contat_2" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->contat_2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->contat_2->CellAttributes() ?>>
<span id="el_dealers_showroom_contat_2">
<input type="text" data-table="dealers_showroom" data-field="x_contat_2" name="x_contat_2" id="x_contat_2" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($dealers_showroom->contat_2->getPlaceHolder()) ?>" value="<?php echo $dealers_showroom->contat_2->EditValue ?>"<?php echo $dealers_showroom->contat_2->EditAttributes() ?>>
</span>
<?php echo $dealers_showroom->contat_2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->showroom_logo_link->Visible) { // showroom_logo_link ?>
	<div id="r_showroom_logo_link" class="form-group">
		<label id="elh_dealers_showroom_showroom_logo_link" for="x_showroom_logo_link" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->showroom_logo_link->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->showroom_logo_link->CellAttributes() ?>>
<span id="el_dealers_showroom_showroom_logo_link">
<input type="text" data-table="dealers_showroom" data-field="x_showroom_logo_link" name="x_showroom_logo_link" id="x_showroom_logo_link" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($dealers_showroom->showroom_logo_link->getPlaceHolder()) ?>" value="<?php echo $dealers_showroom->showroom_logo_link->EditValue ?>"<?php echo $dealers_showroom->showroom_logo_link->EditAttributes() ?>>
</span>
<?php echo $dealers_showroom->showroom_logo_link->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_dealers_showroom_status" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->status->CellAttributes() ?>>
<span id="el_dealers_showroom_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="dealers_showroom" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($dealers_showroom->status->DisplayValueSeparator) ? json_encode($dealers_showroom->status->DisplayValueSeparator) : $dealers_showroom->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $dealers_showroom->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $dealers_showroom->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($dealers_showroom->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="dealers_showroom" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $dealers_showroom->status->EditAttributes() ?>><?php echo $dealers_showroom->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($dealers_showroom->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="dealers_showroom" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($dealers_showroom->status->CurrentValue) ?>" checked<?php echo $dealers_showroom->status->EditAttributes() ?>><?php echo $dealers_showroom->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $dealers_showroom->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->created_at->Visible) { // created_at ?>
	<div id="r_created_at" class="form-group">
		<label id="elh_dealers_showroom_created_at" for="x_created_at" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->created_at->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->created_at->CellAttributes() ?>>
<span id="el_dealers_showroom_created_at">
<input type="text" data-table="dealers_showroom" data-field="x_created_at" data-format="5" name="x_created_at" id="x_created_at" placeholder="<?php echo ew_HtmlEncode($dealers_showroom->created_at->getPlaceHolder()) ?>" value="<?php echo $dealers_showroom->created_at->EditValue ?>"<?php echo $dealers_showroom->created_at->EditAttributes() ?>>
</span>
<?php echo $dealers_showroom->created_at->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dealers_showroom->updated_at->Visible) { // updated_at ?>
	<div id="r_updated_at" class="form-group">
		<label id="elh_dealers_showroom_updated_at" for="x_updated_at" class="col-sm-2 control-label ewLabel"><?php echo $dealers_showroom->updated_at->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dealers_showroom->updated_at->CellAttributes() ?>>
<span id="el_dealers_showroom_updated_at">
<input type="text" data-table="dealers_showroom" data-field="x_updated_at" data-format="5" name="x_updated_at" id="x_updated_at" placeholder="<?php echo ew_HtmlEncode($dealers_showroom->updated_at->getPlaceHolder()) ?>" value="<?php echo $dealers_showroom->updated_at->EditValue ?>"<?php echo $dealers_showroom->updated_at->EditAttributes() ?>>
</span>
<?php echo $dealers_showroom->updated_at->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $dealers_showroom_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fdealers_showroomedit.Init();
</script>
<?php
$dealers_showroom_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dealers_showroom_edit->Page_Terminate();
?>
