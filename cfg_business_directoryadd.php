<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_business_directoryinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_business_directory_add = NULL; // Initialize page object first

class ccfg_business_directory_add extends ccfg_business_directory {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_business_directory';

	// Page object name
	var $PageObjName = 'cfg_business_directory_add';

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

		// Table object (cfg_business_directory)
		if (!isset($GLOBALS["cfg_business_directory"]) || get_class($GLOBALS["cfg_business_directory"]) == "ccfg_business_directory") {
			$GLOBALS["cfg_business_directory"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_business_directory"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cfg_business_directory', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_business_directorylist.php"));
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
		global $EW_EXPORT, $cfg_business_directory;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_business_directory);
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
					$this->Page_Terminate("cfg_business_directorylist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "cfg_business_directorylist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "cfg_business_directoryview.php")
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
		$this->business_logo_link->Upload->Index = $objForm->Index;
		$this->business_logo_link->Upload->UploadFile();
		$this->business_logo_link->CurrentValue = $this->business_logo_link->Upload->FileName;
		$this->image_2->Upload->Index = $objForm->Index;
		$this->image_2->Upload->UploadFile();
		$this->image_2->CurrentValue = $this->image_2->Upload->FileName;
		$this->img_3->Upload->Index = $objForm->Index;
		$this->img_3->Upload->UploadFile();
		$this->img_3->CurrentValue = $this->img_3->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->business_title->CurrentValue = NULL;
		$this->business_title->OldValue = $this->business_title->CurrentValue;
		$this->cat_id->CurrentValue = NULL;
		$this->cat_id->OldValue = $this->cat_id->CurrentValue;
		$this->province_id->CurrentValue = NULL;
		$this->province_id->OldValue = $this->province_id->CurrentValue;
		$this->city_id->CurrentValue = NULL;
		$this->city_id->OldValue = $this->city_id->CurrentValue;
		$this->business_address->CurrentValue = NULL;
		$this->business_address->OldValue = $this->business_address->CurrentValue;
		$this->business_logo_link->Upload->DbValue = NULL;
		$this->business_logo_link->OldValue = $this->business_logo_link->Upload->DbValue;
		$this->business_logo_link->CurrentValue = NULL; // Clear file related field
		$this->image_2->Upload->DbValue = NULL;
		$this->image_2->OldValue = $this->image_2->Upload->DbValue;
		$this->image_2->CurrentValue = NULL; // Clear file related field
		$this->img_3->Upload->DbValue = NULL;
		$this->img_3->OldValue = $this->img_3->Upload->DbValue;
		$this->img_3->CurrentValue = NULL; // Clear file related field
		$this->detail_desc->CurrentValue = NULL;
		$this->detail_desc->OldValue = $this->detail_desc->CurrentValue;
		$this->longitute->CurrentValue = NULL;
		$this->longitute->OldValue = $this->longitute->CurrentValue;
		$this->latitude->CurrentValue = NULL;
		$this->latitude->OldValue = $this->latitude->CurrentValue;
		$this->primary_number->CurrentValue = NULL;
		$this->primary_number->OldValue = $this->primary_number->CurrentValue;
		$this->secondary_number->CurrentValue = NULL;
		$this->secondary_number->OldValue = $this->secondary_number->CurrentValue;
		$this->fb_page->CurrentValue = NULL;
		$this->fb_page->OldValue = $this->fb_page->CurrentValue;
		$this->timings->CurrentValue = NULL;
		$this->timings->OldValue = $this->timings->CurrentValue;
		$this->website->CurrentValue = NULL;
		$this->website->OldValue = $this->website->CurrentValue;
		$this->status->CurrentValue = "1";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->business_title->FldIsDetailKey) {
			$this->business_title->setFormValue($objForm->GetValue("x_business_title"));
		}
		if (!$this->cat_id->FldIsDetailKey) {
			$this->cat_id->setFormValue($objForm->GetValue("x_cat_id"));
		}
		if (!$this->province_id->FldIsDetailKey) {
			$this->province_id->setFormValue($objForm->GetValue("x_province_id"));
		}
		if (!$this->city_id->FldIsDetailKey) {
			$this->city_id->setFormValue($objForm->GetValue("x_city_id"));
		}
		if (!$this->business_address->FldIsDetailKey) {
			$this->business_address->setFormValue($objForm->GetValue("x_business_address"));
		}
		if (!$this->detail_desc->FldIsDetailKey) {
			$this->detail_desc->setFormValue($objForm->GetValue("x_detail_desc"));
		}
		if (!$this->longitute->FldIsDetailKey) {
			$this->longitute->setFormValue($objForm->GetValue("x_longitute"));
		}
		if (!$this->latitude->FldIsDetailKey) {
			$this->latitude->setFormValue($objForm->GetValue("x_latitude"));
		}
		if (!$this->primary_number->FldIsDetailKey) {
			$this->primary_number->setFormValue($objForm->GetValue("x_primary_number"));
		}
		if (!$this->secondary_number->FldIsDetailKey) {
			$this->secondary_number->setFormValue($objForm->GetValue("x_secondary_number"));
		}
		if (!$this->fb_page->FldIsDetailKey) {
			$this->fb_page->setFormValue($objForm->GetValue("x_fb_page"));
		}
		if (!$this->timings->FldIsDetailKey) {
			$this->timings->setFormValue($objForm->GetValue("x_timings"));
		}
		if (!$this->website->FldIsDetailKey) {
			$this->website->setFormValue($objForm->GetValue("x_website"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->business_title->CurrentValue = $this->business_title->FormValue;
		$this->cat_id->CurrentValue = $this->cat_id->FormValue;
		$this->province_id->CurrentValue = $this->province_id->FormValue;
		$this->city_id->CurrentValue = $this->city_id->FormValue;
		$this->business_address->CurrentValue = $this->business_address->FormValue;
		$this->detail_desc->CurrentValue = $this->detail_desc->FormValue;
		$this->longitute->CurrentValue = $this->longitute->FormValue;
		$this->latitude->CurrentValue = $this->latitude->FormValue;
		$this->primary_number->CurrentValue = $this->primary_number->FormValue;
		$this->secondary_number->CurrentValue = $this->secondary_number->FormValue;
		$this->fb_page->CurrentValue = $this->fb_page->FormValue;
		$this->timings->CurrentValue = $this->timings->FormValue;
		$this->website->CurrentValue = $this->website->FormValue;
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
		$this->business_title->setDbValue($rs->fields('business_title'));
		$this->cat_id->setDbValue($rs->fields('cat_id'));
		$this->province_id->setDbValue($rs->fields('province_id'));
		$this->city_id->setDbValue($rs->fields('city_id'));
		$this->business_address->setDbValue($rs->fields('business_address'));
		$this->business_logo_link->Upload->DbValue = $rs->fields('business_logo_link');
		$this->business_logo_link->CurrentValue = $this->business_logo_link->Upload->DbValue;
		$this->image_2->Upload->DbValue = $rs->fields('image_2');
		$this->image_2->CurrentValue = $this->image_2->Upload->DbValue;
		$this->img_3->Upload->DbValue = $rs->fields('img_3');
		$this->img_3->CurrentValue = $this->img_3->Upload->DbValue;
		$this->detail_desc->setDbValue($rs->fields('detail_desc'));
		$this->longitute->setDbValue($rs->fields('longitute'));
		$this->latitude->setDbValue($rs->fields('latitude'));
		$this->primary_number->setDbValue($rs->fields('primary_number'));
		$this->secondary_number->setDbValue($rs->fields('secondary_number'));
		$this->fb_page->setDbValue($rs->fields('fb_page'));
		$this->timings->setDbValue($rs->fields('timings'));
		$this->website->setDbValue($rs->fields('website'));
		$this->status->setDbValue($rs->fields('status'));
		$this->ETD->setDbValue($rs->fields('ETD'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->business_title->DbValue = $row['business_title'];
		$this->cat_id->DbValue = $row['cat_id'];
		$this->province_id->DbValue = $row['province_id'];
		$this->city_id->DbValue = $row['city_id'];
		$this->business_address->DbValue = $row['business_address'];
		$this->business_logo_link->Upload->DbValue = $row['business_logo_link'];
		$this->image_2->Upload->DbValue = $row['image_2'];
		$this->img_3->Upload->DbValue = $row['img_3'];
		$this->detail_desc->DbValue = $row['detail_desc'];
		$this->longitute->DbValue = $row['longitute'];
		$this->latitude->DbValue = $row['latitude'];
		$this->primary_number->DbValue = $row['primary_number'];
		$this->secondary_number->DbValue = $row['secondary_number'];
		$this->fb_page->DbValue = $row['fb_page'];
		$this->timings->DbValue = $row['timings'];
		$this->website->DbValue = $row['website'];
		$this->status->DbValue = $row['status'];
		$this->ETD->DbValue = $row['ETD'];
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
		// business_title
		// cat_id
		// province_id
		// city_id
		// business_address
		// business_logo_link
		// image_2
		// img_3
		// detail_desc
		// longitute
		// latitude
		// primary_number
		// secondary_number
		// fb_page
		// timings
		// website
		// status
		// ETD

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// business_title
		$this->business_title->ViewValue = $this->business_title->CurrentValue;
		$this->business_title->ViewCustomAttributes = "";

		// cat_id
		if (strval($this->cat_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->cat_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_bussiness_listing_category`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->cat_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->cat_id->ViewValue = $this->cat_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->cat_id->ViewValue = $this->cat_id->CurrentValue;
			}
		} else {
			$this->cat_id->ViewValue = NULL;
		}
		$this->cat_id->ViewCustomAttributes = "";

		// province_id
		if (strval($this->province_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->province_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `province_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_province`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->province_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->province_id->ViewValue = $this->province_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->province_id->ViewValue = $this->province_id->CurrentValue;
			}
		} else {
			$this->province_id->ViewValue = NULL;
		}
		$this->province_id->ViewCustomAttributes = "";

		// city_id
		if (strval($this->city_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->city_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
		$sWhereWrk = "";
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

		// business_address
		$this->business_address->ViewValue = $this->business_address->CurrentValue;
		$this->business_address->ViewCustomAttributes = "";

		// business_logo_link
		$this->business_logo_link->UploadPath = 'uploads/business';
		if (!ew_Empty($this->business_logo_link->Upload->DbValue)) {
			$this->business_logo_link->ViewValue = $this->business_logo_link->Upload->DbValue;
		} else {
			$this->business_logo_link->ViewValue = "";
		}
		$this->business_logo_link->ViewCustomAttributes = "";

		// image_2
		$this->image_2->UploadPath = 'uploads/business';
		if (!ew_Empty($this->image_2->Upload->DbValue)) {
			$this->image_2->ViewValue = $this->image_2->Upload->DbValue;
		} else {
			$this->image_2->ViewValue = "";
		}
		$this->image_2->ViewCustomAttributes = "";

		// img_3
		$this->img_3->UploadPath = 'uploads/business';
		if (!ew_Empty($this->img_3->Upload->DbValue)) {
			$this->img_3->ViewValue = $this->img_3->Upload->DbValue;
		} else {
			$this->img_3->ViewValue = "";
		}
		$this->img_3->ViewCustomAttributes = "";

		// detail_desc
		$this->detail_desc->ViewValue = $this->detail_desc->CurrentValue;
		$this->detail_desc->ViewCustomAttributes = "";

		// longitute
		$this->longitute->ViewValue = $this->longitute->CurrentValue;
		$this->longitute->ViewCustomAttributes = "";

		// latitude
		$this->latitude->ViewValue = $this->latitude->CurrentValue;
		$this->latitude->ViewCustomAttributes = "";

		// primary_number
		$this->primary_number->ViewValue = $this->primary_number->CurrentValue;
		$this->primary_number->ViewCustomAttributes = "";

		// secondary_number
		$this->secondary_number->ViewValue = $this->secondary_number->CurrentValue;
		$this->secondary_number->ViewCustomAttributes = "";

		// fb_page
		$this->fb_page->ViewValue = $this->fb_page->CurrentValue;
		$this->fb_page->ViewCustomAttributes = "";

		// timings
		$this->timings->ViewValue = $this->timings->CurrentValue;
		$this->timings->ViewCustomAttributes = "";

		// website
		$this->website->ViewValue = $this->website->CurrentValue;
		$this->website->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// business_title
			$this->business_title->LinkCustomAttributes = "";
			$this->business_title->HrefValue = "";
			$this->business_title->TooltipValue = "";

			// cat_id
			$this->cat_id->LinkCustomAttributes = "";
			$this->cat_id->HrefValue = "";
			$this->cat_id->TooltipValue = "";

			// province_id
			$this->province_id->LinkCustomAttributes = "";
			$this->province_id->HrefValue = "";
			$this->province_id->TooltipValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";
			$this->city_id->TooltipValue = "";

			// business_address
			$this->business_address->LinkCustomAttributes = "";
			$this->business_address->HrefValue = "";
			$this->business_address->TooltipValue = "";

			// business_logo_link
			$this->business_logo_link->LinkCustomAttributes = "";
			$this->business_logo_link->HrefValue = "";
			$this->business_logo_link->HrefValue2 = $this->business_logo_link->UploadPath . $this->business_logo_link->Upload->DbValue;
			$this->business_logo_link->TooltipValue = "";

			// image_2
			$this->image_2->LinkCustomAttributes = "";
			$this->image_2->HrefValue = "";
			$this->image_2->HrefValue2 = $this->image_2->UploadPath . $this->image_2->Upload->DbValue;
			$this->image_2->TooltipValue = "";

			// img_3
			$this->img_3->LinkCustomAttributes = "";
			$this->img_3->HrefValue = "";
			$this->img_3->HrefValue2 = $this->img_3->UploadPath . $this->img_3->Upload->DbValue;
			$this->img_3->TooltipValue = "";

			// detail_desc
			$this->detail_desc->LinkCustomAttributes = "";
			$this->detail_desc->HrefValue = "";
			$this->detail_desc->TooltipValue = "";

			// longitute
			$this->longitute->LinkCustomAttributes = "";
			$this->longitute->HrefValue = "";
			$this->longitute->TooltipValue = "";

			// latitude
			$this->latitude->LinkCustomAttributes = "";
			$this->latitude->HrefValue = "";
			$this->latitude->TooltipValue = "";

			// primary_number
			$this->primary_number->LinkCustomAttributes = "";
			$this->primary_number->HrefValue = "";
			$this->primary_number->TooltipValue = "";

			// secondary_number
			$this->secondary_number->LinkCustomAttributes = "";
			$this->secondary_number->HrefValue = "";
			$this->secondary_number->TooltipValue = "";

			// fb_page
			$this->fb_page->LinkCustomAttributes = "";
			$this->fb_page->HrefValue = "";
			$this->fb_page->TooltipValue = "";

			// timings
			$this->timings->LinkCustomAttributes = "";
			$this->timings->HrefValue = "";
			$this->timings->TooltipValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";
			$this->website->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// business_title
			$this->business_title->EditAttrs["class"] = "form-control";
			$this->business_title->EditCustomAttributes = "";
			$this->business_title->EditValue = ew_HtmlEncode($this->business_title->CurrentValue);
			$this->business_title->PlaceHolder = ew_RemoveHtml($this->business_title->FldCaption());

			// cat_id
			$this->cat_id->EditAttrs["class"] = "form-control";
			$this->cat_id->EditCustomAttributes = "";
			if (trim(strval($this->cat_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->cat_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_bussiness_listing_category`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->cat_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->cat_id->EditValue = $arwrk;

			// province_id
			$this->province_id->EditAttrs["class"] = "form-control";
			$this->province_id->EditCustomAttributes = "";
			if (trim(strval($this->province_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->province_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `province_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_province`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->province_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->province_id->EditValue = $arwrk;

			// city_id
			$this->city_id->EditAttrs["class"] = "form-control";
			$this->city_id->EditCustomAttributes = "";
			if (trim(strval($this->city_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->city_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `province_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_cities`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->city_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->city_id->EditValue = $arwrk;

			// business_address
			$this->business_address->EditAttrs["class"] = "form-control";
			$this->business_address->EditCustomAttributes = "";
			$this->business_address->EditValue = ew_HtmlEncode($this->business_address->CurrentValue);
			$this->business_address->PlaceHolder = ew_RemoveHtml($this->business_address->FldCaption());

			// business_logo_link
			$this->business_logo_link->EditAttrs["class"] = "form-control";
			$this->business_logo_link->EditCustomAttributes = "";
			$this->business_logo_link->UploadPath = 'uploads/business';
			if (!ew_Empty($this->business_logo_link->Upload->DbValue)) {
				$this->business_logo_link->EditValue = $this->business_logo_link->Upload->DbValue;
			} else {
				$this->business_logo_link->EditValue = "";
			}
			if (!ew_Empty($this->business_logo_link->CurrentValue))
				$this->business_logo_link->Upload->FileName = $this->business_logo_link->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->business_logo_link);

			// image_2
			$this->image_2->EditAttrs["class"] = "form-control";
			$this->image_2->EditCustomAttributes = "";
			$this->image_2->UploadPath = 'uploads/business';
			if (!ew_Empty($this->image_2->Upload->DbValue)) {
				$this->image_2->EditValue = $this->image_2->Upload->DbValue;
			} else {
				$this->image_2->EditValue = "";
			}
			if (!ew_Empty($this->image_2->CurrentValue))
				$this->image_2->Upload->FileName = $this->image_2->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->image_2);

			// img_3
			$this->img_3->EditAttrs["class"] = "form-control";
			$this->img_3->EditCustomAttributes = "";
			$this->img_3->UploadPath = 'uploads/business';
			if (!ew_Empty($this->img_3->Upload->DbValue)) {
				$this->img_3->EditValue = $this->img_3->Upload->DbValue;
			} else {
				$this->img_3->EditValue = "";
			}
			if (!ew_Empty($this->img_3->CurrentValue))
				$this->img_3->Upload->FileName = $this->img_3->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->img_3);

			// detail_desc
			$this->detail_desc->EditAttrs["class"] = "form-control";
			$this->detail_desc->EditCustomAttributes = "";
			$this->detail_desc->EditValue = ew_HtmlEncode($this->detail_desc->CurrentValue);
			$this->detail_desc->PlaceHolder = ew_RemoveHtml($this->detail_desc->FldCaption());

			// longitute
			$this->longitute->EditAttrs["class"] = "form-control";
			$this->longitute->EditCustomAttributes = "";
			$this->longitute->EditValue = ew_HtmlEncode($this->longitute->CurrentValue);
			$this->longitute->PlaceHolder = ew_RemoveHtml($this->longitute->FldCaption());

			// latitude
			$this->latitude->EditAttrs["class"] = "form-control";
			$this->latitude->EditCustomAttributes = "";
			$this->latitude->EditValue = ew_HtmlEncode($this->latitude->CurrentValue);
			$this->latitude->PlaceHolder = ew_RemoveHtml($this->latitude->FldCaption());

			// primary_number
			$this->primary_number->EditAttrs["class"] = "form-control";
			$this->primary_number->EditCustomAttributes = "";
			$this->primary_number->EditValue = ew_HtmlEncode($this->primary_number->CurrentValue);
			$this->primary_number->PlaceHolder = ew_RemoveHtml($this->primary_number->FldCaption());

			// secondary_number
			$this->secondary_number->EditAttrs["class"] = "form-control";
			$this->secondary_number->EditCustomAttributes = "";
			$this->secondary_number->EditValue = ew_HtmlEncode($this->secondary_number->CurrentValue);
			$this->secondary_number->PlaceHolder = ew_RemoveHtml($this->secondary_number->FldCaption());

			// fb_page
			$this->fb_page->EditAttrs["class"] = "form-control";
			$this->fb_page->EditCustomAttributes = "";
			$this->fb_page->EditValue = ew_HtmlEncode($this->fb_page->CurrentValue);
			$this->fb_page->PlaceHolder = ew_RemoveHtml($this->fb_page->FldCaption());

			// timings
			$this->timings->EditAttrs["class"] = "form-control";
			$this->timings->EditCustomAttributes = "";
			$this->timings->EditValue = ew_HtmlEncode($this->timings->CurrentValue);
			$this->timings->PlaceHolder = ew_RemoveHtml($this->timings->FldCaption());

			// website
			$this->website->EditAttrs["class"] = "form-control";
			$this->website->EditCustomAttributes = "";
			$this->website->EditValue = ew_HtmlEncode($this->website->CurrentValue);
			$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Add refer script
			// business_title

			$this->business_title->LinkCustomAttributes = "";
			$this->business_title->HrefValue = "";

			// cat_id
			$this->cat_id->LinkCustomAttributes = "";
			$this->cat_id->HrefValue = "";

			// province_id
			$this->province_id->LinkCustomAttributes = "";
			$this->province_id->HrefValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";

			// business_address
			$this->business_address->LinkCustomAttributes = "";
			$this->business_address->HrefValue = "";

			// business_logo_link
			$this->business_logo_link->LinkCustomAttributes = "";
			$this->business_logo_link->HrefValue = "";
			$this->business_logo_link->HrefValue2 = $this->business_logo_link->UploadPath . $this->business_logo_link->Upload->DbValue;

			// image_2
			$this->image_2->LinkCustomAttributes = "";
			$this->image_2->HrefValue = "";
			$this->image_2->HrefValue2 = $this->image_2->UploadPath . $this->image_2->Upload->DbValue;

			// img_3
			$this->img_3->LinkCustomAttributes = "";
			$this->img_3->HrefValue = "";
			$this->img_3->HrefValue2 = $this->img_3->UploadPath . $this->img_3->Upload->DbValue;

			// detail_desc
			$this->detail_desc->LinkCustomAttributes = "";
			$this->detail_desc->HrefValue = "";

			// longitute
			$this->longitute->LinkCustomAttributes = "";
			$this->longitute->HrefValue = "";

			// latitude
			$this->latitude->LinkCustomAttributes = "";
			$this->latitude->HrefValue = "";

			// primary_number
			$this->primary_number->LinkCustomAttributes = "";
			$this->primary_number->HrefValue = "";

			// secondary_number
			$this->secondary_number->LinkCustomAttributes = "";
			$this->secondary_number->HrefValue = "";

			// fb_page
			$this->fb_page->LinkCustomAttributes = "";
			$this->fb_page->HrefValue = "";

			// timings
			$this->timings->LinkCustomAttributes = "";
			$this->timings->HrefValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";

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
		if (!$this->business_title->FldIsDetailKey && !is_null($this->business_title->FormValue) && $this->business_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->business_title->FldCaption(), $this->business_title->ReqErrMsg));
		}
		if (!$this->cat_id->FldIsDetailKey && !is_null($this->cat_id->FormValue) && $this->cat_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cat_id->FldCaption(), $this->cat_id->ReqErrMsg));
		}
		if (!$this->province_id->FldIsDetailKey && !is_null($this->province_id->FormValue) && $this->province_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->province_id->FldCaption(), $this->province_id->ReqErrMsg));
		}
		if (!$this->city_id->FldIsDetailKey && !is_null($this->city_id->FormValue) && $this->city_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->city_id->FldCaption(), $this->city_id->ReqErrMsg));
		}
		if (!$this->business_address->FldIsDetailKey && !is_null($this->business_address->FormValue) && $this->business_address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->business_address->FldCaption(), $this->business_address->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->longitute->FormValue)) {
			ew_AddMessage($gsFormError, $this->longitute->FldErrMsg());
		}
		if (!ew_CheckInteger($this->latitude->FormValue)) {
			ew_AddMessage($gsFormError, $this->latitude->FldErrMsg());
		}
		if (!$this->primary_number->FldIsDetailKey && !is_null($this->primary_number->FormValue) && $this->primary_number->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->primary_number->FldCaption(), $this->primary_number->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->primary_number->FormValue)) {
			ew_AddMessage($gsFormError, $this->primary_number->FldErrMsg());
		}
		if (!ew_CheckInteger($this->secondary_number->FormValue)) {
			ew_AddMessage($gsFormError, $this->secondary_number->FldErrMsg());
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
			$this->business_logo_link->OldUploadPath = 'uploads/business';
			$this->business_logo_link->UploadPath = $this->business_logo_link->OldUploadPath;
			$this->image_2->OldUploadPath = 'uploads/business';
			$this->image_2->UploadPath = $this->image_2->OldUploadPath;
			$this->img_3->OldUploadPath = 'uploads/business';
			$this->img_3->UploadPath = $this->img_3->OldUploadPath;
		}
		$rsnew = array();

		// business_title
		$this->business_title->SetDbValueDef($rsnew, $this->business_title->CurrentValue, "", FALSE);

		// cat_id
		$this->cat_id->SetDbValueDef($rsnew, $this->cat_id->CurrentValue, 0, FALSE);

		// province_id
		$this->province_id->SetDbValueDef($rsnew, $this->province_id->CurrentValue, 0, FALSE);

		// city_id
		$this->city_id->SetDbValueDef($rsnew, $this->city_id->CurrentValue, 0, FALSE);

		// business_address
		$this->business_address->SetDbValueDef($rsnew, $this->business_address->CurrentValue, "", FALSE);

		// business_logo_link
		if ($this->business_logo_link->Visible && !$this->business_logo_link->Upload->KeepFile) {
			$this->business_logo_link->Upload->DbValue = ""; // No need to delete old file
			if ($this->business_logo_link->Upload->FileName == "") {
				$rsnew['business_logo_link'] = NULL;
			} else {
				$rsnew['business_logo_link'] = $this->business_logo_link->Upload->FileName;
			}
		}

		// image_2
		if ($this->image_2->Visible && !$this->image_2->Upload->KeepFile) {
			$this->image_2->Upload->DbValue = ""; // No need to delete old file
			if ($this->image_2->Upload->FileName == "") {
				$rsnew['image_2'] = NULL;
			} else {
				$rsnew['image_2'] = $this->image_2->Upload->FileName;
			}
		}

		// img_3
		if ($this->img_3->Visible && !$this->img_3->Upload->KeepFile) {
			$this->img_3->Upload->DbValue = ""; // No need to delete old file
			if ($this->img_3->Upload->FileName == "") {
				$rsnew['img_3'] = NULL;
			} else {
				$rsnew['img_3'] = $this->img_3->Upload->FileName;
			}
		}

		// detail_desc
		$this->detail_desc->SetDbValueDef($rsnew, $this->detail_desc->CurrentValue, NULL, FALSE);

		// longitute
		$this->longitute->SetDbValueDef($rsnew, $this->longitute->CurrentValue, NULL, FALSE);

		// latitude
		$this->latitude->SetDbValueDef($rsnew, $this->latitude->CurrentValue, NULL, FALSE);

		// primary_number
		$this->primary_number->SetDbValueDef($rsnew, $this->primary_number->CurrentValue, 0, FALSE);

		// secondary_number
		$this->secondary_number->SetDbValueDef($rsnew, $this->secondary_number->CurrentValue, NULL, FALSE);

		// fb_page
		$this->fb_page->SetDbValueDef($rsnew, $this->fb_page->CurrentValue, NULL, FALSE);

		// timings
		$this->timings->SetDbValueDef($rsnew, $this->timings->CurrentValue, NULL, FALSE);

		// website
		$this->website->SetDbValueDef($rsnew, $this->website->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, ((strval($this->status->CurrentValue) == "1") ? "1" : "0"), 0, strval($this->status->CurrentValue) == "");
		if ($this->business_logo_link->Visible && !$this->business_logo_link->Upload->KeepFile) {
			$this->business_logo_link->UploadPath = 'uploads/business';
			if (!ew_Empty($this->business_logo_link->Upload->Value)) {
				$rsnew['business_logo_link'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->business_logo_link->UploadPath), $rsnew['business_logo_link']); // Get new file name
			}
		}
		if ($this->image_2->Visible && !$this->image_2->Upload->KeepFile) {
			$this->image_2->UploadPath = 'uploads/business';
			if (!ew_Empty($this->image_2->Upload->Value)) {
				$rsnew['image_2'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->image_2->UploadPath), $rsnew['image_2']); // Get new file name
			}
		}
		if ($this->img_3->Visible && !$this->img_3->Upload->KeepFile) {
			$this->img_3->UploadPath = 'uploads/business';
			if (!ew_Empty($this->img_3->Upload->Value)) {
				$rsnew['img_3'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->img_3->UploadPath), $rsnew['img_3']); // Get new file name
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
				if ($this->business_logo_link->Visible && !$this->business_logo_link->Upload->KeepFile) {
					if (!ew_Empty($this->business_logo_link->Upload->Value)) {
						$this->business_logo_link->Upload->SaveToFile($this->business_logo_link->UploadPath, $rsnew['business_logo_link'], TRUE);
					}
				}
				if ($this->image_2->Visible && !$this->image_2->Upload->KeepFile) {
					if (!ew_Empty($this->image_2->Upload->Value)) {
						$this->image_2->Upload->SaveToFile($this->image_2->UploadPath, $rsnew['image_2'], TRUE);
					}
				}
				if ($this->img_3->Visible && !$this->img_3->Upload->KeepFile) {
					if (!ew_Empty($this->img_3->Upload->Value)) {
						$this->img_3->Upload->SaveToFile($this->img_3->UploadPath, $rsnew['img_3'], TRUE);
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

		// business_logo_link
		ew_CleanUploadTempPath($this->business_logo_link, $this->business_logo_link->Upload->Index);

		// image_2
		ew_CleanUploadTempPath($this->image_2, $this->image_2->Upload->Index);

		// img_3
		ew_CleanUploadTempPath($this->img_3, $this->img_3->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_business_directorylist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_business_directory_add)) $cfg_business_directory_add = new ccfg_business_directory_add();

// Page init
$cfg_business_directory_add->Page_Init();

// Page main
$cfg_business_directory_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_business_directory_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fcfg_business_directoryadd = new ew_Form("fcfg_business_directoryadd", "add");

// Validate form
fcfg_business_directoryadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_business_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_business_directory->business_title->FldCaption(), $cfg_business_directory->business_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cat_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_business_directory->cat_id->FldCaption(), $cfg_business_directory->cat_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_province_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_business_directory->province_id->FldCaption(), $cfg_business_directory->province_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_city_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_business_directory->city_id->FldCaption(), $cfg_business_directory->city_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_business_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_business_directory->business_address->FldCaption(), $cfg_business_directory->business_address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_longitute");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_business_directory->longitute->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_latitude");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_business_directory->latitude->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_primary_number");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_business_directory->primary_number->FldCaption(), $cfg_business_directory->primary_number->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_primary_number");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_business_directory->primary_number->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_secondary_number");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_business_directory->secondary_number->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cfg_business_directory->status->FldCaption(), $cfg_business_directory->status->ReqErrMsg)) ?>");

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
fcfg_business_directoryadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_business_directoryadd.ValidateRequired = true;
<?php } else { ?>
fcfg_business_directoryadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_business_directoryadd.Lists["x_cat_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_cat_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_business_directoryadd.Lists["x_province_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_province_name","","",""],"ParentFields":[],"ChildFields":["x_city_id"],"FilterFields":[],"Options":[],"Template":""};
fcfg_business_directoryadd.Lists["x_city_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":["x_province_id"],"ChildFields":[],"FilterFields":["x_province_id"],"Options":[],"Template":""};
fcfg_business_directoryadd.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_business_directoryadd.Lists["x_status"].Options = <?php echo json_encode($cfg_business_directory->status->Options()) ?>;

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
<?php $cfg_business_directory_add->ShowPageHeader(); ?>
<?php
$cfg_business_directory_add->ShowMessage();
?>
<form name="fcfg_business_directoryadd" id="fcfg_business_directoryadd" class="<?php echo $cfg_business_directory_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_business_directory_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_business_directory_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_business_directory">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($cfg_business_directory->business_title->Visible) { // business_title ?>
	<div id="r_business_title" class="form-group">
		<label id="elh_cfg_business_directory_business_title" for="x_business_title" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->business_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->business_title->CellAttributes() ?>>
<span id="el_cfg_business_directory_business_title">
<input type="text" data-table="cfg_business_directory" data-field="x_business_title" name="x_business_title" id="x_business_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->business_title->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->business_title->EditValue ?>"<?php echo $cfg_business_directory->business_title->EditAttributes() ?>>
</span>
<?php echo $cfg_business_directory->business_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->cat_id->Visible) { // cat_id ?>
	<div id="r_cat_id" class="form-group">
		<label id="elh_cfg_business_directory_cat_id" for="x_cat_id" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->cat_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->cat_id->CellAttributes() ?>>
<span id="el_cfg_business_directory_cat_id">
<select data-table="cfg_business_directory" data-field="x_cat_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_business_directory->cat_id->DisplayValueSeparator) ? json_encode($cfg_business_directory->cat_id->DisplayValueSeparator) : $cfg_business_directory->cat_id->DisplayValueSeparator) ?>" id="x_cat_id" name="x_cat_id"<?php echo $cfg_business_directory->cat_id->EditAttributes() ?>>
<?php
if (is_array($cfg_business_directory->cat_id->EditValue)) {
	$arwrk = $cfg_business_directory->cat_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($cfg_business_directory->cat_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $cfg_business_directory->cat_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($cfg_business_directory->cat_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($cfg_business_directory->cat_id->CurrentValue) ?>" selected><?php echo $cfg_business_directory->cat_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_bussiness_listing_category`";
$sWhereWrk = "";
$cfg_business_directory->cat_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$cfg_business_directory->cat_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$cfg_business_directory->Lookup_Selecting($cfg_business_directory->cat_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $cfg_business_directory->cat_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_cat_id" id="s_x_cat_id" value="<?php echo $cfg_business_directory->cat_id->LookupFilterQuery() ?>">
</span>
<?php echo $cfg_business_directory->cat_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->province_id->Visible) { // province_id ?>
	<div id="r_province_id" class="form-group">
		<label id="elh_cfg_business_directory_province_id" for="x_province_id" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->province_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->province_id->CellAttributes() ?>>
<span id="el_cfg_business_directory_province_id">
<?php $cfg_business_directory->province_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$cfg_business_directory->province_id->EditAttrs["onchange"]; ?>
<select data-table="cfg_business_directory" data-field="x_province_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_business_directory->province_id->DisplayValueSeparator) ? json_encode($cfg_business_directory->province_id->DisplayValueSeparator) : $cfg_business_directory->province_id->DisplayValueSeparator) ?>" id="x_province_id" name="x_province_id"<?php echo $cfg_business_directory->province_id->EditAttributes() ?>>
<?php
if (is_array($cfg_business_directory->province_id->EditValue)) {
	$arwrk = $cfg_business_directory->province_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($cfg_business_directory->province_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $cfg_business_directory->province_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($cfg_business_directory->province_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($cfg_business_directory->province_id->CurrentValue) ?>" selected><?php echo $cfg_business_directory->province_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `province_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_province`";
$sWhereWrk = "";
$cfg_business_directory->province_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$cfg_business_directory->province_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$cfg_business_directory->Lookup_Selecting($cfg_business_directory->province_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $cfg_business_directory->province_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_province_id" id="s_x_province_id" value="<?php echo $cfg_business_directory->province_id->LookupFilterQuery() ?>">
</span>
<?php echo $cfg_business_directory->province_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->city_id->Visible) { // city_id ?>
	<div id="r_city_id" class="form-group">
		<label id="elh_cfg_business_directory_city_id" for="x_city_id" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->city_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->city_id->CellAttributes() ?>>
<span id="el_cfg_business_directory_city_id">
<select data-table="cfg_business_directory" data-field="x_city_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_business_directory->city_id->DisplayValueSeparator) ? json_encode($cfg_business_directory->city_id->DisplayValueSeparator) : $cfg_business_directory->city_id->DisplayValueSeparator) ?>" id="x_city_id" name="x_city_id"<?php echo $cfg_business_directory->city_id->EditAttributes() ?>>
<?php
if (is_array($cfg_business_directory->city_id->EditValue)) {
	$arwrk = $cfg_business_directory->city_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($cfg_business_directory->city_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $cfg_business_directory->city_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($cfg_business_directory->city_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($cfg_business_directory->city_id->CurrentValue) ?>" selected><?php echo $cfg_business_directory->city_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
$sWhereWrk = "{filter}";
$cfg_business_directory->city_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$cfg_business_directory->city_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$cfg_business_directory->city_id->LookupFilters += array("f1" => "`province_id` IN ({filter_value})", "t1" => "3", "fn1" => "");
$sSqlWrk = "";
$cfg_business_directory->Lookup_Selecting($cfg_business_directory->city_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $cfg_business_directory->city_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_city_id" id="s_x_city_id" value="<?php echo $cfg_business_directory->city_id->LookupFilterQuery() ?>">
</span>
<?php echo $cfg_business_directory->city_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->business_address->Visible) { // business_address ?>
	<div id="r_business_address" class="form-group">
		<label id="elh_cfg_business_directory_business_address" for="x_business_address" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->business_address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->business_address->CellAttributes() ?>>
<span id="el_cfg_business_directory_business_address">
<input type="text" data-table="cfg_business_directory" data-field="x_business_address" name="x_business_address" id="x_business_address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->business_address->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->business_address->EditValue ?>"<?php echo $cfg_business_directory->business_address->EditAttributes() ?>>
</span>
<?php echo $cfg_business_directory->business_address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->business_logo_link->Visible) { // business_logo_link ?>
	<div id="r_business_logo_link" class="form-group">
		<label id="elh_cfg_business_directory_business_logo_link" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->business_logo_link->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->business_logo_link->CellAttributes() ?>>
<span id="el_cfg_business_directory_business_logo_link">
<div id="fd_x_business_logo_link">
<span title="<?php echo $cfg_business_directory->business_logo_link->FldTitle() ? $cfg_business_directory->business_logo_link->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_business_directory->business_logo_link->ReadOnly || $cfg_business_directory->business_logo_link->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_business_directory" data-field="x_business_logo_link" name="x_business_logo_link" id="x_business_logo_link"<?php echo $cfg_business_directory->business_logo_link->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_business_logo_link" id= "fn_x_business_logo_link" value="<?php echo $cfg_business_directory->business_logo_link->Upload->FileName ?>">
<input type="hidden" name="fa_x_business_logo_link" id= "fa_x_business_logo_link" value="0">
<input type="hidden" name="fs_x_business_logo_link" id= "fs_x_business_logo_link" value="255">
<input type="hidden" name="fx_x_business_logo_link" id= "fx_x_business_logo_link" value="<?php echo $cfg_business_directory->business_logo_link->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_business_logo_link" id= "fm_x_business_logo_link" value="<?php echo $cfg_business_directory->business_logo_link->UploadMaxFileSize ?>">
</div>
<table id="ft_x_business_logo_link" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_business_directory->business_logo_link->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->image_2->Visible) { // image_2 ?>
	<div id="r_image_2" class="form-group">
		<label id="elh_cfg_business_directory_image_2" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->image_2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->image_2->CellAttributes() ?>>
<span id="el_cfg_business_directory_image_2">
<div id="fd_x_image_2">
<span title="<?php echo $cfg_business_directory->image_2->FldTitle() ? $cfg_business_directory->image_2->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_business_directory->image_2->ReadOnly || $cfg_business_directory->image_2->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_business_directory" data-field="x_image_2" name="x_image_2" id="x_image_2"<?php echo $cfg_business_directory->image_2->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_image_2" id= "fn_x_image_2" value="<?php echo $cfg_business_directory->image_2->Upload->FileName ?>">
<input type="hidden" name="fa_x_image_2" id= "fa_x_image_2" value="0">
<input type="hidden" name="fs_x_image_2" id= "fs_x_image_2" value="255">
<input type="hidden" name="fx_x_image_2" id= "fx_x_image_2" value="<?php echo $cfg_business_directory->image_2->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image_2" id= "fm_x_image_2" value="<?php echo $cfg_business_directory->image_2->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image_2" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_business_directory->image_2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->img_3->Visible) { // img_3 ?>
	<div id="r_img_3" class="form-group">
		<label id="elh_cfg_business_directory_img_3" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->img_3->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->img_3->CellAttributes() ?>>
<span id="el_cfg_business_directory_img_3">
<div id="fd_x_img_3">
<span title="<?php echo $cfg_business_directory->img_3->FldTitle() ? $cfg_business_directory->img_3->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($cfg_business_directory->img_3->ReadOnly || $cfg_business_directory->img_3->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="cfg_business_directory" data-field="x_img_3" name="x_img_3" id="x_img_3"<?php echo $cfg_business_directory->img_3->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_img_3" id= "fn_x_img_3" value="<?php echo $cfg_business_directory->img_3->Upload->FileName ?>">
<input type="hidden" name="fa_x_img_3" id= "fa_x_img_3" value="0">
<input type="hidden" name="fs_x_img_3" id= "fs_x_img_3" value="255">
<input type="hidden" name="fx_x_img_3" id= "fx_x_img_3" value="<?php echo $cfg_business_directory->img_3->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_img_3" id= "fm_x_img_3" value="<?php echo $cfg_business_directory->img_3->UploadMaxFileSize ?>">
</div>
<table id="ft_x_img_3" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $cfg_business_directory->img_3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->detail_desc->Visible) { // detail_desc ?>
	<div id="r_detail_desc" class="form-group">
		<label id="elh_cfg_business_directory_detail_desc" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->detail_desc->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->detail_desc->CellAttributes() ?>>
<span id="el_cfg_business_directory_detail_desc">
<?php ew_AppendClass($cfg_business_directory->detail_desc->EditAttrs["class"], "editor"); ?>
<textarea data-table="cfg_business_directory" data-field="x_detail_desc" name="x_detail_desc" id="x_detail_desc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->detail_desc->getPlaceHolder()) ?>"<?php echo $cfg_business_directory->detail_desc->EditAttributes() ?>><?php echo $cfg_business_directory->detail_desc->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fcfg_business_directoryadd", "x_detail_desc", 35, 4, <?php echo ($cfg_business_directory->detail_desc->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $cfg_business_directory->detail_desc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->longitute->Visible) { // longitute ?>
	<div id="r_longitute" class="form-group">
		<label id="elh_cfg_business_directory_longitute" for="x_longitute" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->longitute->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->longitute->CellAttributes() ?>>
<span id="el_cfg_business_directory_longitute">
<input type="text" data-table="cfg_business_directory" data-field="x_longitute" name="x_longitute" id="x_longitute" size="30" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->longitute->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->longitute->EditValue ?>"<?php echo $cfg_business_directory->longitute->EditAttributes() ?>>
</span>
<?php echo $cfg_business_directory->longitute->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->latitude->Visible) { // latitude ?>
	<div id="r_latitude" class="form-group">
		<label id="elh_cfg_business_directory_latitude" for="x_latitude" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->latitude->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->latitude->CellAttributes() ?>>
<span id="el_cfg_business_directory_latitude">
<input type="text" data-table="cfg_business_directory" data-field="x_latitude" name="x_latitude" id="x_latitude" size="30" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->latitude->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->latitude->EditValue ?>"<?php echo $cfg_business_directory->latitude->EditAttributes() ?>>
</span>
<?php echo $cfg_business_directory->latitude->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->primary_number->Visible) { // primary_number ?>
	<div id="r_primary_number" class="form-group">
		<label id="elh_cfg_business_directory_primary_number" for="x_primary_number" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->primary_number->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->primary_number->CellAttributes() ?>>
<span id="el_cfg_business_directory_primary_number">
<input type="text" data-table="cfg_business_directory" data-field="x_primary_number" name="x_primary_number" id="x_primary_number" size="30" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->primary_number->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->primary_number->EditValue ?>"<?php echo $cfg_business_directory->primary_number->EditAttributes() ?>>
</span>
<?php echo $cfg_business_directory->primary_number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->secondary_number->Visible) { // secondary_number ?>
	<div id="r_secondary_number" class="form-group">
		<label id="elh_cfg_business_directory_secondary_number" for="x_secondary_number" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->secondary_number->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->secondary_number->CellAttributes() ?>>
<span id="el_cfg_business_directory_secondary_number">
<input type="text" data-table="cfg_business_directory" data-field="x_secondary_number" name="x_secondary_number" id="x_secondary_number" size="30" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->secondary_number->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->secondary_number->EditValue ?>"<?php echo $cfg_business_directory->secondary_number->EditAttributes() ?>>
</span>
<?php echo $cfg_business_directory->secondary_number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->fb_page->Visible) { // fb_page ?>
	<div id="r_fb_page" class="form-group">
		<label id="elh_cfg_business_directory_fb_page" for="x_fb_page" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->fb_page->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->fb_page->CellAttributes() ?>>
<span id="el_cfg_business_directory_fb_page">
<input type="text" data-table="cfg_business_directory" data-field="x_fb_page" name="x_fb_page" id="x_fb_page" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->fb_page->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->fb_page->EditValue ?>"<?php echo $cfg_business_directory->fb_page->EditAttributes() ?>>
</span>
<?php echo $cfg_business_directory->fb_page->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->timings->Visible) { // timings ?>
	<div id="r_timings" class="form-group">
		<label id="elh_cfg_business_directory_timings" for="x_timings" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->timings->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->timings->CellAttributes() ?>>
<span id="el_cfg_business_directory_timings">
<input type="text" data-table="cfg_business_directory" data-field="x_timings" name="x_timings" id="x_timings" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->timings->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->timings->EditValue ?>"<?php echo $cfg_business_directory->timings->EditAttributes() ?>>
</span>
<?php echo $cfg_business_directory->timings->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->website->Visible) { // website ?>
	<div id="r_website" class="form-group">
		<label id="elh_cfg_business_directory_website" for="x_website" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->website->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->website->CellAttributes() ?>>
<span id="el_cfg_business_directory_website">
<input type="text" data-table="cfg_business_directory" data-field="x_website" name="x_website" id="x_website" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->website->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->website->EditValue ?>"<?php echo $cfg_business_directory->website->EditAttributes() ?>>
</span>
<?php echo $cfg_business_directory->website->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_cfg_business_directory_status" class="col-sm-2 control-label ewLabel"><?php echo $cfg_business_directory->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cfg_business_directory->status->CellAttributes() ?>>
<span id="el_cfg_business_directory_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="cfg_business_directory" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_business_directory->status->DisplayValueSeparator) ? json_encode($cfg_business_directory->status->DisplayValueSeparator) : $cfg_business_directory->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $cfg_business_directory->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_business_directory->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_business_directory->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_business_directory" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_business_directory->status->EditAttributes() ?>><?php echo $cfg_business_directory->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_business_directory->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_business_directory" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_business_directory->status->CurrentValue) ?>" checked<?php echo $cfg_business_directory->status->EditAttributes() ?>><?php echo $cfg_business_directory->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $cfg_business_directory->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cfg_business_directory_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcfg_business_directoryadd.Init();
</script>
<?php
$cfg_business_directory_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_business_directory_add->Page_Terminate();
?>
