<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "customersinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$customers_add = NULL; // Initialize page object first

class ccustomers_add extends ccustomers {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'customers';

	// Page object name
	var $PageObjName = 'customers_add';

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

		// Table object (customers)
		if (!isset($GLOBALS["customers"]) || get_class($GLOBALS["customers"]) == "ccustomers") {
			$GLOBALS["customers"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["customers"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'customers', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("customerslist.php"));
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
		global $EW_EXPORT, $customers;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($customers);
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
					$this->Page_Terminate("customerslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "customerslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "customersview.php")
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
		$this->profile_pic->Upload->Index = $objForm->Index;
		$this->profile_pic->Upload->UploadFile();
		$this->profile_pic->CurrentValue = $this->profile_pic->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->email_id->CurrentValue = NULL;
		$this->email_id->OldValue = $this->email_id->CurrentValue;
		$this->password->CurrentValue = NULL;
		$this->password->OldValue = $this->password->CurrentValue;
		$this->customer_name->CurrentValue = NULL;
		$this->customer_name->OldValue = $this->customer_name->CurrentValue;
		$this->profile_pic->Upload->DbValue = NULL;
		$this->profile_pic->OldValue = $this->profile_pic->Upload->DbValue;
		$this->profile_pic->CurrentValue = NULL; // Clear file related field
		$this->dob->CurrentValue = NULL;
		$this->dob->OldValue = $this->dob->CurrentValue;
		$this->city_id->CurrentValue = NULL;
		$this->city_id->OldValue = $this->city_id->CurrentValue;
		$this->customer_type->CurrentValue = "public";
		$this->mobile_num->CurrentValue = NULL;
		$this->mobile_num->OldValue = $this->mobile_num->CurrentValue;
		$this->status->CurrentValue = "1";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->email_id->FldIsDetailKey) {
			$this->email_id->setFormValue($objForm->GetValue("x_email_id"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->customer_name->FldIsDetailKey) {
			$this->customer_name->setFormValue($objForm->GetValue("x_customer_name"));
		}
		if (!$this->dob->FldIsDetailKey) {
			$this->dob->setFormValue($objForm->GetValue("x_dob"));
			$this->dob->CurrentValue = ew_UnFormatDateTime($this->dob->CurrentValue, 7);
		}
		if (!$this->city_id->FldIsDetailKey) {
			$this->city_id->setFormValue($objForm->GetValue("x_city_id"));
		}
		if (!$this->customer_type->FldIsDetailKey) {
			$this->customer_type->setFormValue($objForm->GetValue("x_customer_type"));
		}
		if (!$this->mobile_num->FldIsDetailKey) {
			$this->mobile_num->setFormValue($objForm->GetValue("x_mobile_num"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->email_id->CurrentValue = $this->email_id->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->customer_name->CurrentValue = $this->customer_name->FormValue;
		$this->dob->CurrentValue = $this->dob->FormValue;
		$this->dob->CurrentValue = ew_UnFormatDateTime($this->dob->CurrentValue, 7);
		$this->city_id->CurrentValue = $this->city_id->FormValue;
		$this->customer_type->CurrentValue = $this->customer_type->FormValue;
		$this->mobile_num->CurrentValue = $this->mobile_num->FormValue;
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
		$this->email_id->setDbValue($rs->fields('email_id'));
		$this->password->setDbValue($rs->fields('password'));
		$this->customer_name->setDbValue($rs->fields('customer_name'));
		$this->profile_pic->Upload->DbValue = $rs->fields('profile_pic');
		$this->profile_pic->CurrentValue = $this->profile_pic->Upload->DbValue;
		$this->dob->setDbValue($rs->fields('dob'));
		$this->city_id->setDbValue($rs->fields('city_id'));
		$this->customer_type->setDbValue($rs->fields('customer_type'));
		$this->mobile_num->setDbValue($rs->fields('mobile_num'));
		$this->status->setDbValue($rs->fields('status'));
		$this->createdAt->setDbValue($rs->fields('createdAt'));
		$this->updatedAt->setDbValue($rs->fields('updatedAt'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->email_id->DbValue = $row['email_id'];
		$this->password->DbValue = $row['password'];
		$this->customer_name->DbValue = $row['customer_name'];
		$this->profile_pic->Upload->DbValue = $row['profile_pic'];
		$this->dob->DbValue = $row['dob'];
		$this->city_id->DbValue = $row['city_id'];
		$this->customer_type->DbValue = $row['customer_type'];
		$this->mobile_num->DbValue = $row['mobile_num'];
		$this->status->DbValue = $row['status'];
		$this->createdAt->DbValue = $row['createdAt'];
		$this->updatedAt->DbValue = $row['updatedAt'];
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
		// email_id
		// password
		// customer_name
		// profile_pic
		// dob
		// city_id
		// customer_type
		// mobile_num
		// status
		// createdAt
		// updatedAt

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// email_id
		$this->email_id->ViewValue = $this->email_id->CurrentValue;
		$this->email_id->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// customer_name
		$this->customer_name->ViewValue = $this->customer_name->CurrentValue;
		$this->customer_name->ViewCustomAttributes = "";

		// profile_pic
		if (!ew_Empty($this->profile_pic->Upload->DbValue)) {
			$this->profile_pic->ImageAlt = $this->profile_pic->FldAlt();
			$this->profile_pic->ViewValue = $this->profile_pic->Upload->DbValue;
		} else {
			$this->profile_pic->ViewValue = "";
		}
		$this->profile_pic->ViewCustomAttributes = "";

		// dob
		$this->dob->ViewValue = $this->dob->CurrentValue;
		$this->dob->ViewValue = ew_FormatDateTime($this->dob->ViewValue, 7);
		$this->dob->ViewCustomAttributes = "";

		// city_id
		if (strval($this->city_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->city_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->city_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->city_id->ViewValue = $this->city_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->city_id->ViewValue = $this->city_id->CurrentValue;
			}
		} else {
			$this->city_id->ViewValue = NULL;
		}
		$this->city_id->ViewCustomAttributes = "";

		// customer_type
		if (strval($this->customer_type->CurrentValue) <> "") {
			$this->customer_type->ViewValue = $this->customer_type->OptionCaption($this->customer_type->CurrentValue);
		} else {
			$this->customer_type->ViewValue = NULL;
		}
		$this->customer_type->ViewCustomAttributes = "";

		// mobile_num
		$this->mobile_num->ViewValue = $this->mobile_num->CurrentValue;
		$this->mobile_num->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// email_id
			$this->email_id->LinkCustomAttributes = "";
			$this->email_id->HrefValue = "";
			$this->email_id->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// customer_name
			$this->customer_name->LinkCustomAttributes = "";
			$this->customer_name->HrefValue = "";
			$this->customer_name->TooltipValue = "";

			// profile_pic
			$this->profile_pic->LinkCustomAttributes = "";
			if (!ew_Empty($this->profile_pic->Upload->DbValue)) {
				$this->profile_pic->HrefValue = ew_GetFileUploadUrl($this->profile_pic, $this->profile_pic->Upload->DbValue); // Add prefix/suffix
				$this->profile_pic->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->profile_pic->HrefValue = ew_ConvertFullUrl($this->profile_pic->HrefValue);
			} else {
				$this->profile_pic->HrefValue = "";
			}
			$this->profile_pic->HrefValue2 = $this->profile_pic->UploadPath . $this->profile_pic->Upload->DbValue;
			$this->profile_pic->TooltipValue = "";
			if ($this->profile_pic->UseColorbox) {
				if (ew_Empty($this->profile_pic->TooltipValue))
					$this->profile_pic->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->profile_pic->LinkAttrs["data-rel"] = "customers_x_profile_pic";
				ew_AppendClass($this->profile_pic->LinkAttrs["class"], "ewLightbox");
			}

			// dob
			$this->dob->LinkCustomAttributes = "";
			$this->dob->HrefValue = "";
			$this->dob->TooltipValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";
			$this->city_id->TooltipValue = "";

			// customer_type
			$this->customer_type->LinkCustomAttributes = "";
			$this->customer_type->HrefValue = "";
			$this->customer_type->TooltipValue = "";

			// mobile_num
			$this->mobile_num->LinkCustomAttributes = "";
			$this->mobile_num->HrefValue = "";
			$this->mobile_num->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// email_id
			$this->email_id->EditAttrs["class"] = "form-control";
			$this->email_id->EditCustomAttributes = "";
			$this->email_id->EditValue = ew_HtmlEncode($this->email_id->CurrentValue);
			$this->email_id->PlaceHolder = ew_RemoveHtml($this->email_id->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// customer_name
			$this->customer_name->EditAttrs["class"] = "form-control";
			$this->customer_name->EditCustomAttributes = "";
			$this->customer_name->EditValue = ew_HtmlEncode($this->customer_name->CurrentValue);
			$this->customer_name->PlaceHolder = ew_RemoveHtml($this->customer_name->FldCaption());

			// profile_pic
			$this->profile_pic->EditAttrs["class"] = "form-control";
			$this->profile_pic->EditCustomAttributes = "";
			if (!ew_Empty($this->profile_pic->Upload->DbValue)) {
				$this->profile_pic->ImageAlt = $this->profile_pic->FldAlt();
				$this->profile_pic->EditValue = $this->profile_pic->Upload->DbValue;
			} else {
				$this->profile_pic->EditValue = "";
			}
			if (!ew_Empty($this->profile_pic->CurrentValue))
				$this->profile_pic->Upload->FileName = $this->profile_pic->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->profile_pic);

			// dob
			$this->dob->EditAttrs["class"] = "form-control";
			$this->dob->EditCustomAttributes = "";
			$this->dob->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dob->CurrentValue, 7));
			$this->dob->PlaceHolder = ew_RemoveHtml($this->dob->FldCaption());

			// city_id
			$this->city_id->EditAttrs["class"] = "form-control";
			$this->city_id->EditCustomAttributes = "";
			if (trim(strval($this->city_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->city_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_cities`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->city_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->city_id->EditValue = $arwrk;

			// customer_type
			$this->customer_type->EditCustomAttributes = "";
			$this->customer_type->EditValue = $this->customer_type->Options(FALSE);

			// mobile_num
			$this->mobile_num->EditAttrs["class"] = "form-control";
			$this->mobile_num->EditCustomAttributes = "";
			$this->mobile_num->EditValue = ew_HtmlEncode($this->mobile_num->CurrentValue);
			$this->mobile_num->PlaceHolder = ew_RemoveHtml($this->mobile_num->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Add refer script
			// email_id

			$this->email_id->LinkCustomAttributes = "";
			$this->email_id->HrefValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";

			// customer_name
			$this->customer_name->LinkCustomAttributes = "";
			$this->customer_name->HrefValue = "";

			// profile_pic
			$this->profile_pic->LinkCustomAttributes = "";
			if (!ew_Empty($this->profile_pic->Upload->DbValue)) {
				$this->profile_pic->HrefValue = ew_GetFileUploadUrl($this->profile_pic, $this->profile_pic->Upload->DbValue); // Add prefix/suffix
				$this->profile_pic->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->profile_pic->HrefValue = ew_ConvertFullUrl($this->profile_pic->HrefValue);
			} else {
				$this->profile_pic->HrefValue = "";
			}
			$this->profile_pic->HrefValue2 = $this->profile_pic->UploadPath . $this->profile_pic->Upload->DbValue;

			// dob
			$this->dob->LinkCustomAttributes = "";
			$this->dob->HrefValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";

			// customer_type
			$this->customer_type->LinkCustomAttributes = "";
			$this->customer_type->HrefValue = "";

			// mobile_num
			$this->mobile_num->LinkCustomAttributes = "";
			$this->mobile_num->HrefValue = "";

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
		if (!$this->email_id->FldIsDetailKey && !is_null($this->email_id->FormValue) && $this->email_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->email_id->FldCaption(), $this->email_id->ReqErrMsg));
		}
		if (!$this->password->FldIsDetailKey && !is_null($this->password->FormValue) && $this->password->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->password->FldCaption(), $this->password->ReqErrMsg));
		}
		if (!$this->dob->FldIsDetailKey && !is_null($this->dob->FormValue) && $this->dob->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dob->FldCaption(), $this->dob->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->dob->FormValue)) {
			ew_AddMessage($gsFormError, $this->dob->FldErrMsg());
		}
		if ($this->customer_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->customer_type->FldCaption(), $this->customer_type->ReqErrMsg));
		}
		if (!$this->mobile_num->FldIsDetailKey && !is_null($this->mobile_num->FormValue) && $this->mobile_num->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mobile_num->FldCaption(), $this->mobile_num->ReqErrMsg));
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

		// email_id
		$this->email_id->SetDbValueDef($rsnew, $this->email_id->CurrentValue, "", FALSE);

		// password
		$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, "", FALSE);

		// customer_name
		$this->customer_name->SetDbValueDef($rsnew, $this->customer_name->CurrentValue, NULL, FALSE);

		// profile_pic
		if ($this->profile_pic->Visible && !$this->profile_pic->Upload->KeepFile) {
			$this->profile_pic->Upload->DbValue = ""; // No need to delete old file
			if ($this->profile_pic->Upload->FileName == "") {
				$rsnew['profile_pic'] = NULL;
			} else {
				$rsnew['profile_pic'] = $this->profile_pic->Upload->FileName;
			}
		}

		// dob
		$this->dob->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dob->CurrentValue, 7), NULL, FALSE);

		// city_id
		$this->city_id->SetDbValueDef($rsnew, $this->city_id->CurrentValue, NULL, FALSE);

		// customer_type
		$this->customer_type->SetDbValueDef($rsnew, $this->customer_type->CurrentValue, "", strval($this->customer_type->CurrentValue) == "");

		// mobile_num
		$this->mobile_num->SetDbValueDef($rsnew, $this->mobile_num->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, ((strval($this->status->CurrentValue) == "1") ? "1" : "0"), 0, strval($this->status->CurrentValue) == "");
		if ($this->profile_pic->Visible && !$this->profile_pic->Upload->KeepFile) {
			if (!ew_Empty($this->profile_pic->Upload->Value)) {
				$rsnew['profile_pic'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->profile_pic->UploadPath), $rsnew['profile_pic']); // Get new file name
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
				if ($this->profile_pic->Visible && !$this->profile_pic->Upload->KeepFile) {
					if (!ew_Empty($this->profile_pic->Upload->Value)) {
						$this->profile_pic->Upload->SaveToFile($this->profile_pic->UploadPath, $rsnew['profile_pic'], TRUE);
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

		// profile_pic
		ew_CleanUploadTempPath($this->profile_pic, $this->profile_pic->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("customerslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($customers_add)) $customers_add = new ccustomers_add();

// Page init
$customers_add->Page_Init();

// Page main
$customers_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$customers_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fcustomersadd = new ew_Form("fcustomersadd", "add");

// Validate form
fcustomersadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_email_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $customers->email_id->FldCaption(), $customers->email_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $customers->password->FldCaption(), $customers->password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dob");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $customers->dob->FldCaption(), $customers->dob->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dob");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($customers->dob->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_customer_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $customers->customer_type->FldCaption(), $customers->customer_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mobile_num");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $customers->mobile_num->FldCaption(), $customers->mobile_num->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $customers->status->FldCaption(), $customers->status->ReqErrMsg)) ?>");

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
fcustomersadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcustomersadd.ValidateRequired = true;
<?php } else { ?>
fcustomersadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcustomersadd.Lists["x_city_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcustomersadd.Lists["x_customer_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcustomersadd.Lists["x_customer_type"].Options = <?php echo json_encode($customers->customer_type->Options()) ?>;
fcustomersadd.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcustomersadd.Lists["x_status"].Options = <?php echo json_encode($customers->status->Options()) ?>;

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
<?php $customers_add->ShowPageHeader(); ?>
<?php
$customers_add->ShowMessage();
?>
<form name="fcustomersadd" id="fcustomersadd" class="<?php echo $customers_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($customers_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $customers_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="customers">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($customers->email_id->Visible) { // email_id ?>
	<div id="r_email_id" class="form-group">
		<label id="elh_customers_email_id" for="x_email_id" class="col-sm-2 control-label ewLabel"><?php echo $customers->email_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $customers->email_id->CellAttributes() ?>>
<span id="el_customers_email_id">
<input type="text" data-table="customers" data-field="x_email_id" name="x_email_id" id="x_email_id" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($customers->email_id->getPlaceHolder()) ?>" value="<?php echo $customers->email_id->EditValue ?>"<?php echo $customers->email_id->EditAttributes() ?>>
</span>
<?php echo $customers->email_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customers->password->Visible) { // password ?>
	<div id="r_password" class="form-group">
		<label id="elh_customers_password" for="x_password" class="col-sm-2 control-label ewLabel"><?php echo $customers->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $customers->password->CellAttributes() ?>>
<span id="el_customers_password">
<input type="text" data-table="customers" data-field="x_password" name="x_password" id="x_password" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($customers->password->getPlaceHolder()) ?>" value="<?php echo $customers->password->EditValue ?>"<?php echo $customers->password->EditAttributes() ?>>
</span>
<?php echo $customers->password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customers->customer_name->Visible) { // customer_name ?>
	<div id="r_customer_name" class="form-group">
		<label id="elh_customers_customer_name" for="x_customer_name" class="col-sm-2 control-label ewLabel"><?php echo $customers->customer_name->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customers->customer_name->CellAttributes() ?>>
<span id="el_customers_customer_name">
<input type="text" data-table="customers" data-field="x_customer_name" name="x_customer_name" id="x_customer_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($customers->customer_name->getPlaceHolder()) ?>" value="<?php echo $customers->customer_name->EditValue ?>"<?php echo $customers->customer_name->EditAttributes() ?>>
</span>
<?php echo $customers->customer_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customers->profile_pic->Visible) { // profile_pic ?>
	<div id="r_profile_pic" class="form-group">
		<label id="elh_customers_profile_pic" class="col-sm-2 control-label ewLabel"><?php echo $customers->profile_pic->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customers->profile_pic->CellAttributes() ?>>
<span id="el_customers_profile_pic">
<div id="fd_x_profile_pic">
<span title="<?php echo $customers->profile_pic->FldTitle() ? $customers->profile_pic->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($customers->profile_pic->ReadOnly || $customers->profile_pic->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="customers" data-field="x_profile_pic" name="x_profile_pic" id="x_profile_pic"<?php echo $customers->profile_pic->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_profile_pic" id= "fn_x_profile_pic" value="<?php echo $customers->profile_pic->Upload->FileName ?>">
<input type="hidden" name="fa_x_profile_pic" id= "fa_x_profile_pic" value="0">
<input type="hidden" name="fs_x_profile_pic" id= "fs_x_profile_pic" value="255">
<input type="hidden" name="fx_x_profile_pic" id= "fx_x_profile_pic" value="<?php echo $customers->profile_pic->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_profile_pic" id= "fm_x_profile_pic" value="<?php echo $customers->profile_pic->UploadMaxFileSize ?>">
</div>
<table id="ft_x_profile_pic" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $customers->profile_pic->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customers->dob->Visible) { // dob ?>
	<div id="r_dob" class="form-group">
		<label id="elh_customers_dob" for="x_dob" class="col-sm-2 control-label ewLabel"><?php echo $customers->dob->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $customers->dob->CellAttributes() ?>>
<span id="el_customers_dob">
<input type="text" data-table="customers" data-field="x_dob" data-format="7" name="x_dob" id="x_dob" placeholder="<?php echo ew_HtmlEncode($customers->dob->getPlaceHolder()) ?>" value="<?php echo $customers->dob->EditValue ?>"<?php echo $customers->dob->EditAttributes() ?>>
<?php if (!$customers->dob->ReadOnly && !$customers->dob->Disabled && !isset($customers->dob->EditAttrs["readonly"]) && !isset($customers->dob->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcustomersadd", "x_dob", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $customers->dob->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customers->city_id->Visible) { // city_id ?>
	<div id="r_city_id" class="form-group">
		<label id="elh_customers_city_id" for="x_city_id" class="col-sm-2 control-label ewLabel"><?php echo $customers->city_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customers->city_id->CellAttributes() ?>>
<span id="el_customers_city_id">
<select data-table="customers" data-field="x_city_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($customers->city_id->DisplayValueSeparator) ? json_encode($customers->city_id->DisplayValueSeparator) : $customers->city_id->DisplayValueSeparator) ?>" id="x_city_id" name="x_city_id"<?php echo $customers->city_id->EditAttributes() ?>>
<?php
if (is_array($customers->city_id->EditValue)) {
	$arwrk = $customers->city_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($customers->city_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $customers->city_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($customers->city_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($customers->city_id->CurrentValue) ?>" selected><?php echo $customers->city_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$customers->city_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$customers->city_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$customers->Lookup_Selecting($customers->city_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $customers->city_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_city_id" id="s_x_city_id" value="<?php echo $customers->city_id->LookupFilterQuery() ?>">
</span>
<?php echo $customers->city_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customers->customer_type->Visible) { // customer_type ?>
	<div id="r_customer_type" class="form-group">
		<label id="elh_customers_customer_type" class="col-sm-2 control-label ewLabel"><?php echo $customers->customer_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $customers->customer_type->CellAttributes() ?>>
<span id="el_customers_customer_type">
<div id="tp_x_customer_type" class="ewTemplate"><input type="radio" data-table="customers" data-field="x_customer_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($customers->customer_type->DisplayValueSeparator) ? json_encode($customers->customer_type->DisplayValueSeparator) : $customers->customer_type->DisplayValueSeparator) ?>" name="x_customer_type" id="x_customer_type" value="{value}"<?php echo $customers->customer_type->EditAttributes() ?>></div>
<div id="dsl_x_customer_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $customers->customer_type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($customers->customer_type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="customers" data-field="x_customer_type" name="x_customer_type" id="x_customer_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $customers->customer_type->EditAttributes() ?>><?php echo $customers->customer_type->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($customers->customer_type->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="customers" data-field="x_customer_type" name="x_customer_type" id="x_customer_type_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($customers->customer_type->CurrentValue) ?>" checked<?php echo $customers->customer_type->EditAttributes() ?>><?php echo $customers->customer_type->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $customers->customer_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customers->mobile_num->Visible) { // mobile_num ?>
	<div id="r_mobile_num" class="form-group">
		<label id="elh_customers_mobile_num" for="x_mobile_num" class="col-sm-2 control-label ewLabel"><?php echo $customers->mobile_num->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $customers->mobile_num->CellAttributes() ?>>
<span id="el_customers_mobile_num">
<input type="text" data-table="customers" data-field="x_mobile_num" name="x_mobile_num" id="x_mobile_num" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($customers->mobile_num->getPlaceHolder()) ?>" value="<?php echo $customers->mobile_num->EditValue ?>"<?php echo $customers->mobile_num->EditAttributes() ?>>
</span>
<?php echo $customers->mobile_num->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customers->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_customers_status" class="col-sm-2 control-label ewLabel"><?php echo $customers->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $customers->status->CellAttributes() ?>>
<span id="el_customers_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="customers" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($customers->status->DisplayValueSeparator) ? json_encode($customers->status->DisplayValueSeparator) : $customers->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $customers->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $customers->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($customers->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="customers" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $customers->status->EditAttributes() ?>><?php echo $customers->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($customers->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="customers" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($customers->status->CurrentValue) ?>" checked<?php echo $customers->status->EditAttributes() ?>><?php echo $customers->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $customers->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $customers_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcustomersadd.Init();
</script>
<?php
$customers_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$customers_add->Page_Terminate();
?>
