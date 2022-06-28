<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "classified_datainfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "classified_colorsgridcls.php" ?>
<?php include_once "classified_attributesgridcls.php" ?>
<?php include_once "classified_faqsgridcls.php" ?>
<?php include_once "classified_picturesgridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$classified_data_edit = NULL; // Initialize page object first

class cclassified_data_edit extends cclassified_data {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'classified_data';

	// Page object name
	var $PageObjName = 'classified_data_edit';

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

		// Table object (classified_data)
		if (!isset($GLOBALS["classified_data"]) || get_class($GLOBALS["classified_data"]) == "cclassified_data") {
			$GLOBALS["classified_data"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["classified_data"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'classified_data', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("classified_datalist.php"));
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

		// Set up detail page object
		$this->SetupDetailPages();

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

			// Process auto fill for detail table 'classified_colors'
			if (@$_POST["grid"] == "fclassified_colorsgrid") {
				if (!isset($GLOBALS["classified_colors_grid"])) $GLOBALS["classified_colors_grid"] = new cclassified_colors_grid;
				$GLOBALS["classified_colors_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'classified_attributes'
			if (@$_POST["grid"] == "fclassified_attributesgrid") {
				if (!isset($GLOBALS["classified_attributes_grid"])) $GLOBALS["classified_attributes_grid"] = new cclassified_attributes_grid;
				$GLOBALS["classified_attributes_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'classified_faqs'
			if (@$_POST["grid"] == "fclassified_faqsgrid") {
				if (!isset($GLOBALS["classified_faqs_grid"])) $GLOBALS["classified_faqs_grid"] = new cclassified_faqs_grid;
				$GLOBALS["classified_faqs_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'classified_pictures'
			if (@$_POST["grid"] == "fclassified_picturesgrid") {
				if (!isset($GLOBALS["classified_pictures_grid"])) $GLOBALS["classified_pictures_grid"] = new cclassified_pictures_grid;
				$GLOBALS["classified_pictures_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $classified_data;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($classified_data);
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
	var $DetailPages; // Detail pages object

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

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->ID->CurrentValue == "")
			$this->Page_Terminate("classified_datalist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("classified_datalist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "classified_datalist.php")
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

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->car_type->FldIsDetailKey) {
			$this->car_type->setFormValue($objForm->GetValue("x_car_type"));
		}
		if (!$this->car_make_company_id->FldIsDetailKey) {
			$this->car_make_company_id->setFormValue($objForm->GetValue("x_car_make_company_id"));
		}
		if (!$this->car_model_id->FldIsDetailKey) {
			$this->car_model_id->setFormValue($objForm->GetValue("x_car_model_id"));
		}
		if (!$this->price_range->FldIsDetailKey) {
			$this->price_range->setFormValue($objForm->GetValue("x_price_range"));
		}
		if (!$this->milage_km_liter->FldIsDetailKey) {
			$this->milage_km_liter->setFormValue($objForm->GetValue("x_milage_km_liter"));
		}
		if (!$this->transmition->FldIsDetailKey) {
			$this->transmition->setFormValue($objForm->GetValue("x_transmition"));
		}
		if (!$this->fuel_type->FldIsDetailKey) {
			$this->fuel_type->setFormValue($objForm->GetValue("x_fuel_type"));
		}
		if (!$this->engine_capicity->FldIsDetailKey) {
			$this->engine_capicity->setFormValue($objForm->GetValue("x_engine_capicity"));
		}
		if (!$this->detail_text->FldIsDetailKey) {
			$this->detail_text->setFormValue($objForm->GetValue("x_detail_text"));
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
		$this->car_type->CurrentValue = $this->car_type->FormValue;
		$this->car_make_company_id->CurrentValue = $this->car_make_company_id->FormValue;
		$this->car_model_id->CurrentValue = $this->car_model_id->FormValue;
		$this->price_range->CurrentValue = $this->price_range->FormValue;
		$this->milage_km_liter->CurrentValue = $this->milage_km_liter->FormValue;
		$this->transmition->CurrentValue = $this->transmition->FormValue;
		$this->fuel_type->CurrentValue = $this->fuel_type->FormValue;
		$this->engine_capicity->CurrentValue = $this->engine_capicity->FormValue;
		$this->detail_text->CurrentValue = $this->detail_text->FormValue;
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
		$this->car_type->setDbValue($rs->fields('car_type'));
		$this->car_make_company_id->setDbValue($rs->fields('car_make_company_id'));
		$this->car_model_id->setDbValue($rs->fields('car_model_id'));
		$this->price_range->setDbValue($rs->fields('price_range'));
		$this->milage_km_liter->setDbValue($rs->fields('milage_km_liter'));
		$this->transmition->setDbValue($rs->fields('transmition'));
		$this->fuel_type->setDbValue($rs->fields('fuel_type'));
		$this->engine_capicity->setDbValue($rs->fields('engine_capicity'));
		$this->detail_text->setDbValue($rs->fields('detail_text'));
		$this->status->setDbValue($rs->fields('status'));
		$this->ETD->setDbValue($rs->fields('ETD'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->title->DbValue = $row['title'];
		$this->car_type->DbValue = $row['car_type'];
		$this->car_make_company_id->DbValue = $row['car_make_company_id'];
		$this->car_model_id->DbValue = $row['car_model_id'];
		$this->price_range->DbValue = $row['price_range'];
		$this->milage_km_liter->DbValue = $row['milage_km_liter'];
		$this->transmition->DbValue = $row['transmition'];
		$this->fuel_type->DbValue = $row['fuel_type'];
		$this->engine_capicity->DbValue = $row['engine_capicity'];
		$this->detail_text->DbValue = $row['detail_text'];
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
		// title
		// car_type
		// car_make_company_id
		// car_model_id
		// price_range
		// milage_km_liter
		// transmition
		// fuel_type
		// engine_capicity
		// detail_text
		// status
		// ETD

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// car_type
		if (strval($this->car_type->CurrentValue) <> "") {
			$this->car_type->ViewValue = $this->car_type->OptionCaption($this->car_type->CurrentValue);
		} else {
			$this->car_type->ViewValue = NULL;
		}
		$this->car_type->ViewCustomAttributes = "";

		// car_make_company_id
		if (strval($this->car_make_company_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->car_make_company_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->car_make_company_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->car_make_company_id->ViewValue = $this->car_make_company_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->car_make_company_id->ViewValue = $this->car_make_company_id->CurrentValue;
			}
		} else {
			$this->car_make_company_id->ViewValue = NULL;
		}
		$this->car_make_company_id->ViewCustomAttributes = "";

		// car_model_id
		if (strval($this->car_model_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->car_model_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_models`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->car_model_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->car_model_id->ViewValue = $this->car_model_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->car_model_id->ViewValue = $this->car_model_id->CurrentValue;
			}
		} else {
			$this->car_model_id->ViewValue = NULL;
		}
		$this->car_model_id->ViewCustomAttributes = "";

		// price_range
		$this->price_range->ViewValue = $this->price_range->CurrentValue;
		$this->price_range->ViewCustomAttributes = "";

		// milage_km_liter
		$this->milage_km_liter->ViewValue = $this->milage_km_liter->CurrentValue;
		$this->milage_km_liter->ViewCustomAttributes = "";

		// transmition
		if (strval($this->transmition->CurrentValue) <> "") {
			$this->transmition->ViewValue = $this->transmition->OptionCaption($this->transmition->CurrentValue);
		} else {
			$this->transmition->ViewValue = NULL;
		}
		$this->transmition->ViewCustomAttributes = "";

		// fuel_type
		if (strval($this->fuel_type->CurrentValue) <> "") {
			$this->fuel_type->ViewValue = $this->fuel_type->OptionCaption($this->fuel_type->CurrentValue);
		} else {
			$this->fuel_type->ViewValue = NULL;
		}
		$this->fuel_type->ViewCustomAttributes = "";

		// engine_capicity
		$this->engine_capicity->ViewValue = $this->engine_capicity->CurrentValue;
		$this->engine_capicity->ViewCustomAttributes = "";

		// detail_text
		$this->detail_text->ViewValue = $this->detail_text->CurrentValue;
		$this->detail_text->ViewCustomAttributes = "";

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

			// car_type
			$this->car_type->LinkCustomAttributes = "";
			$this->car_type->HrefValue = "";
			$this->car_type->TooltipValue = "";

			// car_make_company_id
			$this->car_make_company_id->LinkCustomAttributes = "";
			$this->car_make_company_id->HrefValue = "";
			$this->car_make_company_id->TooltipValue = "";

			// car_model_id
			$this->car_model_id->LinkCustomAttributes = "";
			$this->car_model_id->HrefValue = "";
			$this->car_model_id->TooltipValue = "";

			// price_range
			$this->price_range->LinkCustomAttributes = "";
			$this->price_range->HrefValue = "";
			$this->price_range->TooltipValue = "";

			// milage_km_liter
			$this->milage_km_liter->LinkCustomAttributes = "";
			$this->milage_km_liter->HrefValue = "";
			$this->milage_km_liter->TooltipValue = "";

			// transmition
			$this->transmition->LinkCustomAttributes = "";
			$this->transmition->HrefValue = "";
			$this->transmition->TooltipValue = "";

			// fuel_type
			$this->fuel_type->LinkCustomAttributes = "";
			$this->fuel_type->HrefValue = "";
			$this->fuel_type->TooltipValue = "";

			// engine_capicity
			$this->engine_capicity->LinkCustomAttributes = "";
			$this->engine_capicity->HrefValue = "";
			$this->engine_capicity->TooltipValue = "";

			// detail_text
			$this->detail_text->LinkCustomAttributes = "";
			if (!ew_Empty($this->detail_text->CurrentValue)) {
				$this->detail_text->HrefValue = ((!empty($this->detail_text->ViewValue)) ? ew_RemoveHtml($this->detail_text->ViewValue) : $this->detail_text->CurrentValue); // Add prefix/suffix
				$this->detail_text->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->detail_text->HrefValue = ew_ConvertFullUrl($this->detail_text->HrefValue);
			} else {
				$this->detail_text->HrefValue = "";
			}
			$this->detail_text->TooltipValue = "";

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

			// car_type
			$this->car_type->EditCustomAttributes = "";
			$this->car_type->EditValue = $this->car_type->Options(FALSE);

			// car_make_company_id
			$this->car_make_company_id->EditAttrs["class"] = "form-control";
			$this->car_make_company_id->EditCustomAttributes = "";
			if (trim(strval($this->car_make_company_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->car_make_company_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_make_companies`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->car_make_company_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->car_make_company_id->EditValue = $arwrk;

			// car_model_id
			$this->car_model_id->EditAttrs["class"] = "form-control";
			$this->car_model_id->EditCustomAttributes = "";
			if (trim(strval($this->car_model_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->car_model_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `make_company_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_models`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->car_model_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->car_model_id->EditValue = $arwrk;

			// price_range
			$this->price_range->EditAttrs["class"] = "form-control";
			$this->price_range->EditCustomAttributes = "";
			$this->price_range->EditValue = ew_HtmlEncode($this->price_range->CurrentValue);
			$this->price_range->PlaceHolder = ew_RemoveHtml($this->price_range->FldCaption());

			// milage_km_liter
			$this->milage_km_liter->EditAttrs["class"] = "form-control";
			$this->milage_km_liter->EditCustomAttributes = "";
			$this->milage_km_liter->EditValue = ew_HtmlEncode($this->milage_km_liter->CurrentValue);
			$this->milage_km_liter->PlaceHolder = ew_RemoveHtml($this->milage_km_liter->FldCaption());

			// transmition
			$this->transmition->EditCustomAttributes = "";
			$this->transmition->EditValue = $this->transmition->Options(FALSE);

			// fuel_type
			$this->fuel_type->EditCustomAttributes = "";
			$this->fuel_type->EditValue = $this->fuel_type->Options(FALSE);

			// engine_capicity
			$this->engine_capicity->EditAttrs["class"] = "form-control";
			$this->engine_capicity->EditCustomAttributes = "";
			$this->engine_capicity->EditValue = ew_HtmlEncode($this->engine_capicity->CurrentValue);
			$this->engine_capicity->PlaceHolder = ew_RemoveHtml($this->engine_capicity->FldCaption());

			// detail_text
			$this->detail_text->EditAttrs["class"] = "form-control";
			$this->detail_text->EditCustomAttributes = "";
			$this->detail_text->EditValue = ew_HtmlEncode($this->detail_text->CurrentValue);
			$this->detail_text->PlaceHolder = ew_RemoveHtml($this->detail_text->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Edit refer script
			// title

			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// car_type
			$this->car_type->LinkCustomAttributes = "";
			$this->car_type->HrefValue = "";

			// car_make_company_id
			$this->car_make_company_id->LinkCustomAttributes = "";
			$this->car_make_company_id->HrefValue = "";

			// car_model_id
			$this->car_model_id->LinkCustomAttributes = "";
			$this->car_model_id->HrefValue = "";

			// price_range
			$this->price_range->LinkCustomAttributes = "";
			$this->price_range->HrefValue = "";

			// milage_km_liter
			$this->milage_km_liter->LinkCustomAttributes = "";
			$this->milage_km_liter->HrefValue = "";

			// transmition
			$this->transmition->LinkCustomAttributes = "";
			$this->transmition->HrefValue = "";

			// fuel_type
			$this->fuel_type->LinkCustomAttributes = "";
			$this->fuel_type->HrefValue = "";

			// engine_capicity
			$this->engine_capicity->LinkCustomAttributes = "";
			$this->engine_capicity->HrefValue = "";

			// detail_text
			$this->detail_text->LinkCustomAttributes = "";
			if (!ew_Empty($this->detail_text->CurrentValue)) {
				$this->detail_text->HrefValue = ((!empty($this->detail_text->EditValue)) ? ew_RemoveHtml($this->detail_text->EditValue) : $this->detail_text->CurrentValue); // Add prefix/suffix
				$this->detail_text->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->detail_text->HrefValue = ew_ConvertFullUrl($this->detail_text->HrefValue);
			} else {
				$this->detail_text->HrefValue = "";
			}

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
		if (!$this->car_make_company_id->FldIsDetailKey && !is_null($this->car_make_company_id->FormValue) && $this->car_make_company_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->car_make_company_id->FldCaption(), $this->car_make_company_id->ReqErrMsg));
		}
		if (!$this->car_model_id->FldIsDetailKey && !is_null($this->car_model_id->FormValue) && $this->car_model_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->car_model_id->FldCaption(), $this->car_model_id->ReqErrMsg));
		}
		if (!$this->price_range->FldIsDetailKey && !is_null($this->price_range->FormValue) && $this->price_range->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->price_range->FldCaption(), $this->price_range->ReqErrMsg));
		}
		if (!$this->milage_km_liter->FldIsDetailKey && !is_null($this->milage_km_liter->FormValue) && $this->milage_km_liter->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->milage_km_liter->FldCaption(), $this->milage_km_liter->ReqErrMsg));
		}
		if ($this->fuel_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fuel_type->FldCaption(), $this->fuel_type->ReqErrMsg));
		}
		if (!$this->engine_capicity->FldIsDetailKey && !is_null($this->engine_capicity->FormValue) && $this->engine_capicity->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->engine_capicity->FldCaption(), $this->engine_capicity->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->engine_capicity->FormValue)) {
			ew_AddMessage($gsFormError, $this->engine_capicity->FldErrMsg());
		}
		if (!$this->detail_text->FldIsDetailKey && !is_null($this->detail_text->FormValue) && $this->detail_text->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->detail_text->FldCaption(), $this->detail_text->ReqErrMsg));
		}
		if ($this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("classified_colors", $DetailTblVar) && $GLOBALS["classified_colors"]->DetailEdit) {
			if (!isset($GLOBALS["classified_colors_grid"])) $GLOBALS["classified_colors_grid"] = new cclassified_colors_grid(); // get detail page object
			$GLOBALS["classified_colors_grid"]->ValidateGridForm();
		}
		if (in_array("classified_attributes", $DetailTblVar) && $GLOBALS["classified_attributes"]->DetailEdit) {
			if (!isset($GLOBALS["classified_attributes_grid"])) $GLOBALS["classified_attributes_grid"] = new cclassified_attributes_grid(); // get detail page object
			$GLOBALS["classified_attributes_grid"]->ValidateGridForm();
		}
		if (in_array("classified_faqs", $DetailTblVar) && $GLOBALS["classified_faqs"]->DetailEdit) {
			if (!isset($GLOBALS["classified_faqs_grid"])) $GLOBALS["classified_faqs_grid"] = new cclassified_faqs_grid(); // get detail page object
			$GLOBALS["classified_faqs_grid"]->ValidateGridForm();
		}
		if (in_array("classified_pictures", $DetailTblVar) && $GLOBALS["classified_pictures"]->DetailEdit) {
			if (!isset($GLOBALS["classified_pictures_grid"])) $GLOBALS["classified_pictures_grid"] = new cclassified_pictures_grid(); // get detail page object
			$GLOBALS["classified_pictures_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// title
			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", $this->title->ReadOnly);

			// car_type
			$this->car_type->SetDbValueDef($rsnew, $this->car_type->CurrentValue, NULL, $this->car_type->ReadOnly);

			// car_make_company_id
			$this->car_make_company_id->SetDbValueDef($rsnew, $this->car_make_company_id->CurrentValue, 0, $this->car_make_company_id->ReadOnly);

			// car_model_id
			$this->car_model_id->SetDbValueDef($rsnew, $this->car_model_id->CurrentValue, 0, $this->car_model_id->ReadOnly);

			// price_range
			$this->price_range->SetDbValueDef($rsnew, $this->price_range->CurrentValue, "", $this->price_range->ReadOnly);

			// milage_km_liter
			$this->milage_km_liter->SetDbValueDef($rsnew, $this->milage_km_liter->CurrentValue, "", $this->milage_km_liter->ReadOnly);

			// transmition
			$this->transmition->SetDbValueDef($rsnew, $this->transmition->CurrentValue, NULL, $this->transmition->ReadOnly);

			// fuel_type
			$this->fuel_type->SetDbValueDef($rsnew, $this->fuel_type->CurrentValue, "", $this->fuel_type->ReadOnly);

			// engine_capicity
			$this->engine_capicity->SetDbValueDef($rsnew, $this->engine_capicity->CurrentValue, 0, $this->engine_capicity->ReadOnly);

			// detail_text
			$this->detail_text->SetDbValueDef($rsnew, $this->detail_text->CurrentValue, "", $this->detail_text->ReadOnly);

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

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("classified_colors", $DetailTblVar) && $GLOBALS["classified_colors"]->DetailEdit) {
						if (!isset($GLOBALS["classified_colors_grid"])) $GLOBALS["classified_colors_grid"] = new cclassified_colors_grid(); // Get detail page object
						$EditRow = $GLOBALS["classified_colors_grid"]->GridUpdate();
					}
				}
				if ($EditRow) {
					if (in_array("classified_attributes", $DetailTblVar) && $GLOBALS["classified_attributes"]->DetailEdit) {
						if (!isset($GLOBALS["classified_attributes_grid"])) $GLOBALS["classified_attributes_grid"] = new cclassified_attributes_grid(); // Get detail page object
						$EditRow = $GLOBALS["classified_attributes_grid"]->GridUpdate();
					}
				}
				if ($EditRow) {
					if (in_array("classified_faqs", $DetailTblVar) && $GLOBALS["classified_faqs"]->DetailEdit) {
						if (!isset($GLOBALS["classified_faqs_grid"])) $GLOBALS["classified_faqs_grid"] = new cclassified_faqs_grid(); // Get detail page object
						$EditRow = $GLOBALS["classified_faqs_grid"]->GridUpdate();
					}
				}
				if ($EditRow) {
					if (in_array("classified_pictures", $DetailTblVar) && $GLOBALS["classified_pictures"]->DetailEdit) {
						if (!isset($GLOBALS["classified_pictures_grid"])) $GLOBALS["classified_pictures_grid"] = new cclassified_pictures_grid(); // Get detail page object
						$EditRow = $GLOBALS["classified_pictures_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
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
		return $EditRow;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("classified_colors", $DetailTblVar)) {
				if (!isset($GLOBALS["classified_colors_grid"]))
					$GLOBALS["classified_colors_grid"] = new cclassified_colors_grid;
				if ($GLOBALS["classified_colors_grid"]->DetailEdit) {
					$GLOBALS["classified_colors_grid"]->CurrentMode = "edit";
					$GLOBALS["classified_colors_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["classified_colors_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["classified_colors_grid"]->setStartRecordNumber(1);
					$GLOBALS["classified_colors_grid"]->classfied_id->FldIsDetailKey = TRUE;
					$GLOBALS["classified_colors_grid"]->classfied_id->CurrentValue = $this->ID->CurrentValue;
					$GLOBALS["classified_colors_grid"]->classfied_id->setSessionValue($GLOBALS["classified_colors_grid"]->classfied_id->CurrentValue);
				}
			}
			if (in_array("classified_attributes", $DetailTblVar)) {
				if (!isset($GLOBALS["classified_attributes_grid"]))
					$GLOBALS["classified_attributes_grid"] = new cclassified_attributes_grid;
				if ($GLOBALS["classified_attributes_grid"]->DetailEdit) {
					$GLOBALS["classified_attributes_grid"]->CurrentMode = "edit";
					$GLOBALS["classified_attributes_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["classified_attributes_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["classified_attributes_grid"]->setStartRecordNumber(1);
					$GLOBALS["classified_attributes_grid"]->classified_id->FldIsDetailKey = TRUE;
					$GLOBALS["classified_attributes_grid"]->classified_id->CurrentValue = $this->ID->CurrentValue;
					$GLOBALS["classified_attributes_grid"]->classified_id->setSessionValue($GLOBALS["classified_attributes_grid"]->classified_id->CurrentValue);
				}
			}
			if (in_array("classified_faqs", $DetailTblVar)) {
				if (!isset($GLOBALS["classified_faqs_grid"]))
					$GLOBALS["classified_faqs_grid"] = new cclassified_faqs_grid;
				if ($GLOBALS["classified_faqs_grid"]->DetailEdit) {
					$GLOBALS["classified_faqs_grid"]->CurrentMode = "edit";
					$GLOBALS["classified_faqs_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["classified_faqs_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["classified_faqs_grid"]->setStartRecordNumber(1);
					$GLOBALS["classified_faqs_grid"]->classified_id->FldIsDetailKey = TRUE;
					$GLOBALS["classified_faqs_grid"]->classified_id->CurrentValue = $this->ID->CurrentValue;
					$GLOBALS["classified_faqs_grid"]->classified_id->setSessionValue($GLOBALS["classified_faqs_grid"]->classified_id->CurrentValue);
				}
			}
			if (in_array("classified_pictures", $DetailTblVar)) {
				if (!isset($GLOBALS["classified_pictures_grid"]))
					$GLOBALS["classified_pictures_grid"] = new cclassified_pictures_grid;
				if ($GLOBALS["classified_pictures_grid"]->DetailEdit) {
					$GLOBALS["classified_pictures_grid"]->CurrentMode = "edit";
					$GLOBALS["classified_pictures_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["classified_pictures_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["classified_pictures_grid"]->setStartRecordNumber(1);
					$GLOBALS["classified_pictures_grid"]->classified_id->FldIsDetailKey = TRUE;
					$GLOBALS["classified_pictures_grid"]->classified_id->CurrentValue = $this->ID->CurrentValue;
					$GLOBALS["classified_pictures_grid"]->classified_id->setSessionValue($GLOBALS["classified_pictures_grid"]->classified_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("classified_datalist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Set up detail pages
	function SetupDetailPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add('classified_colors');
		$pages->Add('classified_attributes');
		$pages->Add('classified_faqs');
		$pages->Add('classified_pictures');
		$this->DetailPages = $pages;
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
if (!isset($classified_data_edit)) $classified_data_edit = new cclassified_data_edit();

// Page init
$classified_data_edit->Page_Init();

// Page main
$classified_data_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$classified_data_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fclassified_dataedit = new ew_Form("fclassified_dataedit", "edit");

// Validate form
fclassified_dataedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_data->title->FldCaption(), $classified_data->title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_car_make_company_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_data->car_make_company_id->FldCaption(), $classified_data->car_make_company_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_car_model_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_data->car_model_id->FldCaption(), $classified_data->car_model_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_price_range");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_data->price_range->FldCaption(), $classified_data->price_range->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_milage_km_liter");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_data->milage_km_liter->FldCaption(), $classified_data->milage_km_liter->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fuel_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_data->fuel_type->FldCaption(), $classified_data->fuel_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_engine_capicity");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_data->engine_capicity->FldCaption(), $classified_data->engine_capicity->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_engine_capicity");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($classified_data->engine_capicity->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_detail_text");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_data->detail_text->FldCaption(), $classified_data->detail_text->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $classified_data->status->FldCaption(), $classified_data->status->ReqErrMsg)) ?>");

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
fclassified_dataedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclassified_dataedit.ValidateRequired = true;
<?php } else { ?>
fclassified_dataedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclassified_dataedit.Lists["x_car_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataedit.Lists["x_car_type"].Options = <?php echo json_encode($classified_data->car_type->Options()) ?>;
fclassified_dataedit.Lists["x_car_make_company_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":["x_car_model_id"],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataedit.Lists["x_car_model_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":["x_car_make_company_id"],"ChildFields":[],"FilterFields":["x_make_company_id"],"Options":[],"Template":""};
fclassified_dataedit.Lists["x_transmition"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataedit.Lists["x_transmition"].Options = <?php echo json_encode($classified_data->transmition->Options()) ?>;
fclassified_dataedit.Lists["x_fuel_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataedit.Lists["x_fuel_type"].Options = <?php echo json_encode($classified_data->fuel_type->Options()) ?>;
fclassified_dataedit.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataedit.Lists["x_status"].Options = <?php echo json_encode($classified_data->status->Options()) ?>;

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
<?php $classified_data_edit->ShowPageHeader(); ?>
<?php
$classified_data_edit->ShowMessage();
?>
<form name="fclassified_dataedit" id="fclassified_dataedit" class="<?php echo $classified_data_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($classified_data_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $classified_data_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="classified_data">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($classified_data->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label id="elh_classified_data_title" for="x_title" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->title->CellAttributes() ?>>
<span id="el_classified_data_title">
<input type="text" data-table="classified_data" data-field="x_title" name="x_title" id="x_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($classified_data->title->getPlaceHolder()) ?>" value="<?php echo $classified_data->title->EditValue ?>"<?php echo $classified_data->title->EditAttributes() ?>>
</span>
<?php echo $classified_data->title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($classified_data->car_type->Visible) { // car_type ?>
	<div id="r_car_type" class="form-group">
		<label id="elh_classified_data_car_type" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->car_type->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->car_type->CellAttributes() ?>>
<span id="el_classified_data_car_type">
<div id="tp_x_car_type" class="ewTemplate"><input type="radio" data-table="classified_data" data-field="x_car_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->car_type->DisplayValueSeparator) ? json_encode($classified_data->car_type->DisplayValueSeparator) : $classified_data->car_type->DisplayValueSeparator) ?>" name="x_car_type" id="x_car_type" value="{value}"<?php echo $classified_data->car_type->EditAttributes() ?>></div>
<div id="dsl_x_car_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $classified_data->car_type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($classified_data->car_type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_car_type" name="x_car_type" id="x_car_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $classified_data->car_type->EditAttributes() ?>><?php echo $classified_data->car_type->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($classified_data->car_type->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_car_type" name="x_car_type" id="x_car_type_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($classified_data->car_type->CurrentValue) ?>" checked<?php echo $classified_data->car_type->EditAttributes() ?>><?php echo $classified_data->car_type->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $classified_data->car_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($classified_data->car_make_company_id->Visible) { // car_make_company_id ?>
	<div id="r_car_make_company_id" class="form-group">
		<label id="elh_classified_data_car_make_company_id" for="x_car_make_company_id" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->car_make_company_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->car_make_company_id->CellAttributes() ?>>
<span id="el_classified_data_car_make_company_id">
<?php $classified_data->car_make_company_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$classified_data->car_make_company_id->EditAttrs["onchange"]; ?>
<select data-table="classified_data" data-field="x_car_make_company_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->car_make_company_id->DisplayValueSeparator) ? json_encode($classified_data->car_make_company_id->DisplayValueSeparator) : $classified_data->car_make_company_id->DisplayValueSeparator) ?>" id="x_car_make_company_id" name="x_car_make_company_id"<?php echo $classified_data->car_make_company_id->EditAttributes() ?>>
<?php
if (is_array($classified_data->car_make_company_id->EditValue)) {
	$arwrk = $classified_data->car_make_company_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($classified_data->car_make_company_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $classified_data->car_make_company_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($classified_data->car_make_company_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($classified_data->car_make_company_id->CurrentValue) ?>" selected><?php echo $classified_data->car_make_company_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
$sWhereWrk = "";
$classified_data->car_make_company_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$classified_data->car_make_company_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$classified_data->Lookup_Selecting($classified_data->car_make_company_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $classified_data->car_make_company_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_car_make_company_id" id="s_x_car_make_company_id" value="<?php echo $classified_data->car_make_company_id->LookupFilterQuery() ?>">
</span>
<?php echo $classified_data->car_make_company_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($classified_data->car_model_id->Visible) { // car_model_id ?>
	<div id="r_car_model_id" class="form-group">
		<label id="elh_classified_data_car_model_id" for="x_car_model_id" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->car_model_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->car_model_id->CellAttributes() ?>>
<span id="el_classified_data_car_model_id">
<select data-table="classified_data" data-field="x_car_model_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->car_model_id->DisplayValueSeparator) ? json_encode($classified_data->car_model_id->DisplayValueSeparator) : $classified_data->car_model_id->DisplayValueSeparator) ?>" id="x_car_model_id" name="x_car_model_id"<?php echo $classified_data->car_model_id->EditAttributes() ?>>
<?php
if (is_array($classified_data->car_model_id->EditValue)) {
	$arwrk = $classified_data->car_model_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($classified_data->car_model_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $classified_data->car_model_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($classified_data->car_model_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($classified_data->car_model_id->CurrentValue) ?>" selected><?php echo $classified_data->car_model_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_models`";
$sWhereWrk = "{filter}";
$classified_data->car_model_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$classified_data->car_model_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$classified_data->car_model_id->LookupFilters += array("f1" => "`make_company_id` IN ({filter_value})", "t1" => "3", "fn1" => "");
$sSqlWrk = "";
$classified_data->Lookup_Selecting($classified_data->car_model_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $classified_data->car_model_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_car_model_id" id="s_x_car_model_id" value="<?php echo $classified_data->car_model_id->LookupFilterQuery() ?>">
</span>
<?php echo $classified_data->car_model_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($classified_data->price_range->Visible) { // price_range ?>
	<div id="r_price_range" class="form-group">
		<label id="elh_classified_data_price_range" for="x_price_range" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->price_range->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->price_range->CellAttributes() ?>>
<span id="el_classified_data_price_range">
<input type="text" data-table="classified_data" data-field="x_price_range" name="x_price_range" id="x_price_range" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($classified_data->price_range->getPlaceHolder()) ?>" value="<?php echo $classified_data->price_range->EditValue ?>"<?php echo $classified_data->price_range->EditAttributes() ?>>
</span>
<?php echo $classified_data->price_range->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($classified_data->milage_km_liter->Visible) { // milage_km_liter ?>
	<div id="r_milage_km_liter" class="form-group">
		<label id="elh_classified_data_milage_km_liter" for="x_milage_km_liter" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->milage_km_liter->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->milage_km_liter->CellAttributes() ?>>
<span id="el_classified_data_milage_km_liter">
<input type="text" data-table="classified_data" data-field="x_milage_km_liter" name="x_milage_km_liter" id="x_milage_km_liter" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($classified_data->milage_km_liter->getPlaceHolder()) ?>" value="<?php echo $classified_data->milage_km_liter->EditValue ?>"<?php echo $classified_data->milage_km_liter->EditAttributes() ?>>
</span>
<?php echo $classified_data->milage_km_liter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($classified_data->transmition->Visible) { // transmition ?>
	<div id="r_transmition" class="form-group">
		<label id="elh_classified_data_transmition" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->transmition->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->transmition->CellAttributes() ?>>
<span id="el_classified_data_transmition">
<div id="tp_x_transmition" class="ewTemplate"><input type="radio" data-table="classified_data" data-field="x_transmition" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->transmition->DisplayValueSeparator) ? json_encode($classified_data->transmition->DisplayValueSeparator) : $classified_data->transmition->DisplayValueSeparator) ?>" name="x_transmition" id="x_transmition" value="{value}"<?php echo $classified_data->transmition->EditAttributes() ?>></div>
<div id="dsl_x_transmition" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $classified_data->transmition->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($classified_data->transmition->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_transmition" name="x_transmition" id="x_transmition_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $classified_data->transmition->EditAttributes() ?>><?php echo $classified_data->transmition->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($classified_data->transmition->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_transmition" name="x_transmition" id="x_transmition_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($classified_data->transmition->CurrentValue) ?>" checked<?php echo $classified_data->transmition->EditAttributes() ?>><?php echo $classified_data->transmition->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $classified_data->transmition->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($classified_data->fuel_type->Visible) { // fuel_type ?>
	<div id="r_fuel_type" class="form-group">
		<label id="elh_classified_data_fuel_type" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->fuel_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->fuel_type->CellAttributes() ?>>
<span id="el_classified_data_fuel_type">
<div id="tp_x_fuel_type" class="ewTemplate"><input type="radio" data-table="classified_data" data-field="x_fuel_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->fuel_type->DisplayValueSeparator) ? json_encode($classified_data->fuel_type->DisplayValueSeparator) : $classified_data->fuel_type->DisplayValueSeparator) ?>" name="x_fuel_type" id="x_fuel_type" value="{value}"<?php echo $classified_data->fuel_type->EditAttributes() ?>></div>
<div id="dsl_x_fuel_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $classified_data->fuel_type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($classified_data->fuel_type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_fuel_type" name="x_fuel_type" id="x_fuel_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $classified_data->fuel_type->EditAttributes() ?>><?php echo $classified_data->fuel_type->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($classified_data->fuel_type->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_fuel_type" name="x_fuel_type" id="x_fuel_type_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($classified_data->fuel_type->CurrentValue) ?>" checked<?php echo $classified_data->fuel_type->EditAttributes() ?>><?php echo $classified_data->fuel_type->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $classified_data->fuel_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($classified_data->engine_capicity->Visible) { // engine_capicity ?>
	<div id="r_engine_capicity" class="form-group">
		<label id="elh_classified_data_engine_capicity" for="x_engine_capicity" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->engine_capicity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->engine_capicity->CellAttributes() ?>>
<span id="el_classified_data_engine_capicity">
<input type="text" data-table="classified_data" data-field="x_engine_capicity" name="x_engine_capicity" id="x_engine_capicity" size="30" placeholder="<?php echo ew_HtmlEncode($classified_data->engine_capicity->getPlaceHolder()) ?>" value="<?php echo $classified_data->engine_capicity->EditValue ?>"<?php echo $classified_data->engine_capicity->EditAttributes() ?>>
</span>
<?php echo $classified_data->engine_capicity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($classified_data->detail_text->Visible) { // detail_text ?>
	<div id="r_detail_text" class="form-group">
		<label id="elh_classified_data_detail_text" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->detail_text->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->detail_text->CellAttributes() ?>>
<span id="el_classified_data_detail_text">
<?php ew_AppendClass($classified_data->detail_text->EditAttrs["class"], "editor"); ?>
<textarea data-table="classified_data" data-field="x_detail_text" name="x_detail_text" id="x_detail_text" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($classified_data->detail_text->getPlaceHolder()) ?>"<?php echo $classified_data->detail_text->EditAttributes() ?>><?php echo $classified_data->detail_text->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fclassified_dataedit", "x_detail_text", 0, 0, <?php echo ($classified_data->detail_text->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $classified_data->detail_text->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($classified_data->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_classified_data_status" class="col-sm-2 control-label ewLabel"><?php echo $classified_data->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $classified_data->status->CellAttributes() ?>>
<span id="el_classified_data_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="classified_data" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->status->DisplayValueSeparator) ? json_encode($classified_data->status->DisplayValueSeparator) : $classified_data->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $classified_data->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $classified_data->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($classified_data->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $classified_data->status->EditAttributes() ?>><?php echo $classified_data->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($classified_data->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($classified_data->status->CurrentValue) ?>" checked<?php echo $classified_data->status->EditAttributes() ?>><?php echo $classified_data->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $classified_data->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="classified_data" data-field="x_ID" name="x_ID" id="x_ID" value="<?php echo ew_HtmlEncode($classified_data->ID->CurrentValue) ?>">
<?php if ($classified_data->getCurrentDetailTable() <> "") { ?>
<?php
	$FirstActiveDetailTable = $classified_data_edit->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages">
<div class="tabbable" id="classified_data_edit_details">
	<ul class="nav<?php echo $classified_data_edit->DetailPages->NavStyle() ?>">
<?php
	if (in_array("classified_colors", explode(",", $classified_data->getCurrentDetailTable())) && $classified_colors->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_colors") {
			$FirstActiveDetailTable = "classified_colors";
		}
?>
		<li<?php echo $classified_data_edit->DetailPages->TabStyle("classified_colors") ?>><a href="#tab_classified_colors" data-toggle="tab"><?php echo $Language->TablePhrase("classified_colors", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("classified_attributes", explode(",", $classified_data->getCurrentDetailTable())) && $classified_attributes->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_attributes") {
			$FirstActiveDetailTable = "classified_attributes";
		}
?>
		<li<?php echo $classified_data_edit->DetailPages->TabStyle("classified_attributes") ?>><a href="#tab_classified_attributes" data-toggle="tab"><?php echo $Language->TablePhrase("classified_attributes", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("classified_faqs", explode(",", $classified_data->getCurrentDetailTable())) && $classified_faqs->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_faqs") {
			$FirstActiveDetailTable = "classified_faqs";
		}
?>
		<li<?php echo $classified_data_edit->DetailPages->TabStyle("classified_faqs") ?>><a href="#tab_classified_faqs" data-toggle="tab"><?php echo $Language->TablePhrase("classified_faqs", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("classified_pictures", explode(",", $classified_data->getCurrentDetailTable())) && $classified_pictures->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_pictures") {
			$FirstActiveDetailTable = "classified_pictures";
		}
?>
		<li<?php echo $classified_data_edit->DetailPages->TabStyle("classified_pictures") ?>><a href="#tab_classified_pictures" data-toggle="tab"><?php echo $Language->TablePhrase("classified_pictures", "TblCaption") ?></a></li>
<?php
	}
?>
	</ul>
	<div class="tab-content">
<?php
	if (in_array("classified_colors", explode(",", $classified_data->getCurrentDetailTable())) && $classified_colors->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_colors") {
			$FirstActiveDetailTable = "classified_colors";
		}
?>
		<div class="tab-pane<?php echo $classified_data_edit->DetailPages->PageStyle("classified_colors") ?>" id="tab_classified_colors">
<?php include_once "classified_colorsgrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("classified_attributes", explode(",", $classified_data->getCurrentDetailTable())) && $classified_attributes->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_attributes") {
			$FirstActiveDetailTable = "classified_attributes";
		}
?>
		<div class="tab-pane<?php echo $classified_data_edit->DetailPages->PageStyle("classified_attributes") ?>" id="tab_classified_attributes">
<?php include_once "classified_attributesgrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("classified_faqs", explode(",", $classified_data->getCurrentDetailTable())) && $classified_faqs->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_faqs") {
			$FirstActiveDetailTable = "classified_faqs";
		}
?>
		<div class="tab-pane<?php echo $classified_data_edit->DetailPages->PageStyle("classified_faqs") ?>" id="tab_classified_faqs">
<?php include_once "classified_faqsgrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("classified_pictures", explode(",", $classified_data->getCurrentDetailTable())) && $classified_pictures->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_pictures") {
			$FirstActiveDetailTable = "classified_pictures";
		}
?>
		<div class="tab-pane<?php echo $classified_data_edit->DetailPages->PageStyle("classified_pictures") ?>" id="tab_classified_pictures">
<?php include_once "classified_picturesgrid.php" ?>
		</div>
<?php } ?>
	</div>
</div>
</div>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $classified_data_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fclassified_dataedit.Init();
</script>
<?php
$classified_data_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$classified_data_edit->Page_Terminate();
?>
