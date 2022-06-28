<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "car_adsinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "ad_featuresgridcls.php" ?>
<?php include_once "ad_picturesgridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$car_ads_add = NULL; // Initialize page object first

class ccar_ads_add extends ccar_ads {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'car_ads';

	// Page object name
	var $PageObjName = 'car_ads_add';

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

		// Table object (car_ads)
		if (!isset($GLOBALS["car_ads"]) || get_class($GLOBALS["car_ads"]) == "ccar_ads") {
			$GLOBALS["car_ads"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["car_ads"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'car_ads', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("car_adslist.php"));
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

			// Process auto fill for detail table 'ad_features'
			if (@$_POST["grid"] == "fad_featuresgrid") {
				if (!isset($GLOBALS["ad_features_grid"])) $GLOBALS["ad_features_grid"] = new cad_features_grid;
				$GLOBALS["ad_features_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'ad_pictures'
			if (@$_POST["grid"] == "fad_picturesgrid") {
				if (!isset($GLOBALS["ad_pictures_grid"])) $GLOBALS["ad_pictures_grid"] = new cad_pictures_grid;
				$GLOBALS["ad_pictures_grid"]->Page_Init();
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
		global $EW_EXPORT, $car_ads;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($car_ads);
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
	var $DetailPages; // Detail pages object

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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("car_adslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "car_adslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "car_adsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
	}

	// Load default values
	function LoadDefaultValues() {
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
		$this->year_id->CurrentValue = NULL;
		$this->year_id->OldValue = $this->year_id->CurrentValue;
		$this->registered_in->CurrentValue = NULL;
		$this->registered_in->OldValue = $this->registered_in->CurrentValue;
		$this->city_id->CurrentValue = NULL;
		$this->city_id->OldValue = $this->city_id->CurrentValue;
		$this->make_id->CurrentValue = NULL;
		$this->make_id->OldValue = $this->make_id->CurrentValue;
		$this->model_id->CurrentValue = NULL;
		$this->model_id->OldValue = $this->model_id->CurrentValue;
		$this->version_id->CurrentValue = NULL;
		$this->version_id->OldValue = $this->version_id->CurrentValue;
		$this->milage->CurrentValue = NULL;
		$this->milage->OldValue = $this->milage->CurrentValue;
		$this->color_id->CurrentValue = NULL;
		$this->color_id->OldValue = $this->color_id->CurrentValue;
		$this->demand_price->CurrentValue = NULL;
		$this->demand_price->OldValue = $this->demand_price->CurrentValue;
		$this->details->CurrentValue = NULL;
		$this->details->OldValue = $this->details->CurrentValue;
		$this->engine_type_id->CurrentValue = NULL;
		$this->engine_type_id->OldValue = $this->engine_type_id->CurrentValue;
		$this->engine_capicity->CurrentValue = NULL;
		$this->engine_capicity->OldValue = $this->engine_capicity->CurrentValue;
		$this->transmition->CurrentValue = NULL;
		$this->transmition->OldValue = $this->transmition->CurrentValue;
		$this->assembly->CurrentValue = NULL;
		$this->assembly->OldValue = $this->assembly->CurrentValue;
		$this->mobile_number->CurrentValue = NULL;
		$this->mobile_number->OldValue = $this->mobile_number->CurrentValue;
		$this->secondary_number->CurrentValue = NULL;
		$this->secondary_number->OldValue = $this->secondary_number->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->address->CurrentValue = NULL;
		$this->address->OldValue = $this->address->CurrentValue;
		$this->allow_whatsapp->CurrentValue = "0";
		$this->status->CurrentValue = "Pending";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->year_id->FldIsDetailKey) {
			$this->year_id->setFormValue($objForm->GetValue("x_year_id"));
		}
		if (!$this->registered_in->FldIsDetailKey) {
			$this->registered_in->setFormValue($objForm->GetValue("x_registered_in"));
		}
		if (!$this->city_id->FldIsDetailKey) {
			$this->city_id->setFormValue($objForm->GetValue("x_city_id"));
		}
		if (!$this->make_id->FldIsDetailKey) {
			$this->make_id->setFormValue($objForm->GetValue("x_make_id"));
		}
		if (!$this->model_id->FldIsDetailKey) {
			$this->model_id->setFormValue($objForm->GetValue("x_model_id"));
		}
		if (!$this->version_id->FldIsDetailKey) {
			$this->version_id->setFormValue($objForm->GetValue("x_version_id"));
		}
		if (!$this->milage->FldIsDetailKey) {
			$this->milage->setFormValue($objForm->GetValue("x_milage"));
		}
		if (!$this->color_id->FldIsDetailKey) {
			$this->color_id->setFormValue($objForm->GetValue("x_color_id"));
		}
		if (!$this->demand_price->FldIsDetailKey) {
			$this->demand_price->setFormValue($objForm->GetValue("x_demand_price"));
		}
		if (!$this->details->FldIsDetailKey) {
			$this->details->setFormValue($objForm->GetValue("x_details"));
		}
		if (!$this->engine_type_id->FldIsDetailKey) {
			$this->engine_type_id->setFormValue($objForm->GetValue("x_engine_type_id"));
		}
		if (!$this->engine_capicity->FldIsDetailKey) {
			$this->engine_capicity->setFormValue($objForm->GetValue("x_engine_capicity"));
		}
		if (!$this->transmition->FldIsDetailKey) {
			$this->transmition->setFormValue($objForm->GetValue("x_transmition"));
		}
		if (!$this->assembly->FldIsDetailKey) {
			$this->assembly->setFormValue($objForm->GetValue("x_assembly"));
		}
		if (!$this->mobile_number->FldIsDetailKey) {
			$this->mobile_number->setFormValue($objForm->GetValue("x_mobile_number"));
		}
		if (!$this->secondary_number->FldIsDetailKey) {
			$this->secondary_number->setFormValue($objForm->GetValue("x_secondary_number"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->allow_whatsapp->FldIsDetailKey) {
			$this->allow_whatsapp->setFormValue($objForm->GetValue("x_allow_whatsapp"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->year_id->CurrentValue = $this->year_id->FormValue;
		$this->registered_in->CurrentValue = $this->registered_in->FormValue;
		$this->city_id->CurrentValue = $this->city_id->FormValue;
		$this->make_id->CurrentValue = $this->make_id->FormValue;
		$this->model_id->CurrentValue = $this->model_id->FormValue;
		$this->version_id->CurrentValue = $this->version_id->FormValue;
		$this->milage->CurrentValue = $this->milage->FormValue;
		$this->color_id->CurrentValue = $this->color_id->FormValue;
		$this->demand_price->CurrentValue = $this->demand_price->FormValue;
		$this->details->CurrentValue = $this->details->FormValue;
		$this->engine_type_id->CurrentValue = $this->engine_type_id->FormValue;
		$this->engine_capicity->CurrentValue = $this->engine_capicity->FormValue;
		$this->transmition->CurrentValue = $this->transmition->FormValue;
		$this->assembly->CurrentValue = $this->assembly->FormValue;
		$this->mobile_number->CurrentValue = $this->mobile_number->FormValue;
		$this->secondary_number->CurrentValue = $this->secondary_number->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->allow_whatsapp->CurrentValue = $this->allow_whatsapp->FormValue;
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
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->ad_title->setDbValue($rs->fields('ad_title'));
		$this->year_id->setDbValue($rs->fields('year_id'));
		$this->registered_in->setDbValue($rs->fields('registered_in'));
		$this->city_id->setDbValue($rs->fields('city_id'));
		$this->make_id->setDbValue($rs->fields('make_id'));
		$this->model_id->setDbValue($rs->fields('model_id'));
		$this->version_id->setDbValue($rs->fields('version_id'));
		$this->milage->setDbValue($rs->fields('milage'));
		$this->color_id->setDbValue($rs->fields('color_id'));
		$this->demand_price->setDbValue($rs->fields('demand_price'));
		$this->details->setDbValue($rs->fields('details'));
		$this->engine_type_id->setDbValue($rs->fields('engine_type_id'));
		$this->engine_capicity->setDbValue($rs->fields('engine_capicity'));
		$this->transmition->setDbValue($rs->fields('transmition'));
		$this->assembly->setDbValue($rs->fields('assembly'));
		$this->mobile_number->setDbValue($rs->fields('mobile_number'));
		$this->secondary_number->setDbValue($rs->fields('secondary_number'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->name->setDbValue($rs->fields('name'));
		$this->address->setDbValue($rs->fields('address'));
		$this->allow_whatsapp->setDbValue($rs->fields('allow_whatsapp'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->user_id->DbValue = $row['user_id'];
		$this->ad_title->DbValue = $row['ad_title'];
		$this->year_id->DbValue = $row['year_id'];
		$this->registered_in->DbValue = $row['registered_in'];
		$this->city_id->DbValue = $row['city_id'];
		$this->make_id->DbValue = $row['make_id'];
		$this->model_id->DbValue = $row['model_id'];
		$this->version_id->DbValue = $row['version_id'];
		$this->milage->DbValue = $row['milage'];
		$this->color_id->DbValue = $row['color_id'];
		$this->demand_price->DbValue = $row['demand_price'];
		$this->details->DbValue = $row['details'];
		$this->engine_type_id->DbValue = $row['engine_type_id'];
		$this->engine_capicity->DbValue = $row['engine_capicity'];
		$this->transmition->DbValue = $row['transmition'];
		$this->assembly->DbValue = $row['assembly'];
		$this->mobile_number->DbValue = $row['mobile_number'];
		$this->secondary_number->DbValue = $row['secondary_number'];
		$this->_email->DbValue = $row['email'];
		$this->name->DbValue = $row['name'];
		$this->address->DbValue = $row['address'];
		$this->allow_whatsapp->DbValue = $row['allow_whatsapp'];
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
		// user_id
		// ad_title
		// year_id
		// registered_in
		// city_id
		// make_id
		// model_id
		// version_id
		// milage
		// color_id
		// demand_price
		// details
		// engine_type_id
		// engine_capicity
		// transmition
		// assembly
		// mobile_number
		// secondary_number
		// email
		// name
		// address
		// allow_whatsapp
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// user_id
		$this->user_id->ViewValue = $this->user_id->CurrentValue;
		if (strval($this->user_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->user_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, `email` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->user_id->ViewValue = $this->user_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->user_id->ViewValue = $this->user_id->CurrentValue;
			}
		} else {
			$this->user_id->ViewValue = NULL;
		}
		$this->user_id->ViewCustomAttributes = "";

		// year_id
		if (strval($this->year_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->year_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `year` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_years`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->year_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->year_id->ViewValue = $this->year_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->year_id->ViewValue = $this->year_id->CurrentValue;
			}
		} else {
			$this->year_id->ViewValue = NULL;
		}
		$this->year_id->ViewCustomAttributes = "";

		// registered_in
		if (strval($this->registered_in->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->registered_in->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->registered_in, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->registered_in->ViewValue = $this->registered_in->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->registered_in->ViewValue = $this->registered_in->CurrentValue;
			}
		} else {
			$this->registered_in->ViewValue = NULL;
		}
		$this->registered_in->ViewCustomAttributes = "";

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

		// make_id
		if (strval($this->make_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->make_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->make_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->make_id->ViewValue = $this->make_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->make_id->ViewValue = $this->make_id->CurrentValue;
			}
		} else {
			$this->make_id->ViewValue = NULL;
		}
		$this->make_id->ViewCustomAttributes = "";

		// model_id
		if (strval($this->model_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->model_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_models`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->model_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->model_id->ViewValue = $this->model_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->model_id->ViewValue = $this->model_id->CurrentValue;
			}
		} else {
			$this->model_id->ViewValue = NULL;
		}
		$this->model_id->ViewCustomAttributes = "";

		// version_id
		if (strval($this->version_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->version_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_car_versions`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->version_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->version_id->ViewValue = $this->version_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->version_id->ViewValue = $this->version_id->CurrentValue;
			}
		} else {
			$this->version_id->ViewValue = NULL;
		}
		$this->version_id->ViewCustomAttributes = "";

		// milage
		$this->milage->ViewValue = $this->milage->CurrentValue;
		$this->milage->ViewCustomAttributes = "";

		// color_id
		if (strval($this->color_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->color_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_body_colors`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->color_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->color_id->ViewValue = $this->color_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->color_id->ViewValue = $this->color_id->CurrentValue;
			}
		} else {
			$this->color_id->ViewValue = NULL;
		}
		$this->color_id->ViewCustomAttributes = "";

		// demand_price
		$this->demand_price->ViewValue = $this->demand_price->CurrentValue;
		$this->demand_price->ViewCustomAttributes = "";

		// details
		$this->details->ViewValue = $this->details->CurrentValue;
		$this->details->ViewCustomAttributes = "";

		// engine_type_id
		if (strval($this->engine_type_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->engine_type_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_engine_types`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->engine_type_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->engine_type_id->ViewValue = $this->engine_type_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->engine_type_id->ViewValue = $this->engine_type_id->CurrentValue;
			}
		} else {
			$this->engine_type_id->ViewValue = NULL;
		}
		$this->engine_type_id->ViewCustomAttributes = "";

		// engine_capicity
		$this->engine_capicity->ViewValue = $this->engine_capicity->CurrentValue;
		$this->engine_capicity->ViewCustomAttributes = "";

		// transmition
		if (strval($this->transmition->CurrentValue) <> "") {
			$this->transmition->ViewValue = $this->transmition->OptionCaption($this->transmition->CurrentValue);
		} else {
			$this->transmition->ViewValue = NULL;
		}
		$this->transmition->ViewCustomAttributes = "";

		// assembly
		if (strval($this->assembly->CurrentValue) <> "") {
			$this->assembly->ViewValue = $this->assembly->OptionCaption($this->assembly->CurrentValue);
		} else {
			$this->assembly->ViewValue = NULL;
		}
		$this->assembly->ViewCustomAttributes = "";

		// mobile_number
		$this->mobile_number->ViewValue = $this->mobile_number->CurrentValue;
		$this->mobile_number->ViewCustomAttributes = "";

		// secondary_number
		$this->secondary_number->ViewValue = $this->secondary_number->CurrentValue;
		$this->secondary_number->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// allow_whatsapp
		if (ew_ConvertToBool($this->allow_whatsapp->CurrentValue)) {
			$this->allow_whatsapp->ViewValue = $this->allow_whatsapp->FldTagCaption(1) <> "" ? $this->allow_whatsapp->FldTagCaption(1) : "Yes";
		} else {
			$this->allow_whatsapp->ViewValue = $this->allow_whatsapp->FldTagCaption(2) <> "" ? $this->allow_whatsapp->FldTagCaption(2) : "No";
		}
		$this->allow_whatsapp->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$this->status->ViewValue = $this->status->OptionCaption($this->status->CurrentValue);
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// year_id
			$this->year_id->LinkCustomAttributes = "";
			$this->year_id->HrefValue = "";
			$this->year_id->TooltipValue = "";

			// registered_in
			$this->registered_in->LinkCustomAttributes = "";
			$this->registered_in->HrefValue = "";
			$this->registered_in->TooltipValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";
			$this->city_id->TooltipValue = "";

			// make_id
			$this->make_id->LinkCustomAttributes = "";
			$this->make_id->HrefValue = "";
			$this->make_id->TooltipValue = "";

			// model_id
			$this->model_id->LinkCustomAttributes = "";
			$this->model_id->HrefValue = "";
			$this->model_id->TooltipValue = "";

			// version_id
			$this->version_id->LinkCustomAttributes = "";
			$this->version_id->HrefValue = "";
			$this->version_id->TooltipValue = "";

			// milage
			$this->milage->LinkCustomAttributes = "";
			$this->milage->HrefValue = "";
			$this->milage->TooltipValue = "";

			// color_id
			$this->color_id->LinkCustomAttributes = "";
			$this->color_id->HrefValue = "";
			$this->color_id->TooltipValue = "";

			// demand_price
			$this->demand_price->LinkCustomAttributes = "";
			$this->demand_price->HrefValue = "";
			$this->demand_price->TooltipValue = "";

			// details
			$this->details->LinkCustomAttributes = "";
			$this->details->HrefValue = "";
			$this->details->TooltipValue = "";

			// engine_type_id
			$this->engine_type_id->LinkCustomAttributes = "";
			$this->engine_type_id->HrefValue = "";
			$this->engine_type_id->TooltipValue = "";

			// engine_capicity
			$this->engine_capicity->LinkCustomAttributes = "";
			$this->engine_capicity->HrefValue = "";
			$this->engine_capicity->TooltipValue = "";

			// transmition
			$this->transmition->LinkCustomAttributes = "";
			$this->transmition->HrefValue = "";
			$this->transmition->TooltipValue = "";

			// assembly
			$this->assembly->LinkCustomAttributes = "";
			$this->assembly->HrefValue = "";
			$this->assembly->TooltipValue = "";

			// mobile_number
			$this->mobile_number->LinkCustomAttributes = "";
			$this->mobile_number->HrefValue = "";
			$this->mobile_number->TooltipValue = "";

			// secondary_number
			$this->secondary_number->LinkCustomAttributes = "";
			$this->secondary_number->HrefValue = "";
			$this->secondary_number->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// allow_whatsapp
			$this->allow_whatsapp->LinkCustomAttributes = "";
			$this->allow_whatsapp->HrefValue = "";
			$this->allow_whatsapp->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// user_id
			$this->user_id->EditAttrs["class"] = "form-control";
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);
			if (strval($this->user_id->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->user_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, `email` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->user_id->EditValue = $this->user_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);
				}
			} else {
				$this->user_id->EditValue = NULL;
			}
			$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

			// year_id
			$this->year_id->EditCustomAttributes = "";
			if (trim(strval($this->year_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->year_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `year` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_years`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->year_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->year_id->ViewValue = $this->year_id->DisplayValue($arwrk);
			} else {
				$this->year_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->year_id->EditValue = $arwrk;

			// registered_in
			$this->registered_in->EditAttrs["class"] = "form-control";
			$this->registered_in->EditCustomAttributes = "";
			if (trim(strval($this->registered_in->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->registered_in->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_cities`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->registered_in, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->registered_in->EditValue = $arwrk;

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

			// make_id
			$this->make_id->EditAttrs["class"] = "form-control";
			$this->make_id->EditCustomAttributes = "";
			if (trim(strval($this->make_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->make_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_make_companies`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->make_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->make_id->EditValue = $arwrk;

			// model_id
			$this->model_id->EditAttrs["class"] = "form-control";
			$this->model_id->EditCustomAttributes = "";
			if (trim(strval($this->model_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->model_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_models`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->model_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->model_id->EditValue = $arwrk;

			// version_id
			$this->version_id->EditAttrs["class"] = "form-control";
			$this->version_id->EditCustomAttributes = "";
			if (trim(strval($this->version_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->version_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_car_versions`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->version_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->version_id->EditValue = $arwrk;

			// milage
			$this->milage->EditAttrs["class"] = "form-control";
			$this->milage->EditCustomAttributes = "";
			$this->milage->EditValue = ew_HtmlEncode($this->milage->CurrentValue);
			$this->milage->PlaceHolder = ew_RemoveHtml($this->milage->FldCaption());

			// color_id
			$this->color_id->EditAttrs["class"] = "form-control";
			$this->color_id->EditCustomAttributes = "";
			if (trim(strval($this->color_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->color_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_body_colors`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->color_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->color_id->EditValue = $arwrk;

			// demand_price
			$this->demand_price->EditAttrs["class"] = "form-control";
			$this->demand_price->EditCustomAttributes = "";
			$this->demand_price->EditValue = ew_HtmlEncode($this->demand_price->CurrentValue);
			$this->demand_price->PlaceHolder = ew_RemoveHtml($this->demand_price->FldCaption());

			// details
			$this->details->EditAttrs["class"] = "form-control";
			$this->details->EditCustomAttributes = "";
			$this->details->EditValue = ew_HtmlEncode($this->details->CurrentValue);
			$this->details->PlaceHolder = ew_RemoveHtml($this->details->FldCaption());

			// engine_type_id
			$this->engine_type_id->EditAttrs["class"] = "form-control";
			$this->engine_type_id->EditCustomAttributes = "";
			if (trim(strval($this->engine_type_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->engine_type_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_engine_types`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->engine_type_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->engine_type_id->EditValue = $arwrk;

			// engine_capicity
			$this->engine_capicity->EditAttrs["class"] = "form-control";
			$this->engine_capicity->EditCustomAttributes = "";
			$this->engine_capicity->EditValue = ew_HtmlEncode($this->engine_capicity->CurrentValue);
			$this->engine_capicity->PlaceHolder = ew_RemoveHtml($this->engine_capicity->FldCaption());

			// transmition
			$this->transmition->EditCustomAttributes = "";
			$this->transmition->EditValue = $this->transmition->Options(FALSE);

			// assembly
			$this->assembly->EditCustomAttributes = "";
			$this->assembly->EditValue = $this->assembly->Options(FALSE);

			// mobile_number
			$this->mobile_number->EditAttrs["class"] = "form-control";
			$this->mobile_number->EditCustomAttributes = "";
			$this->mobile_number->EditValue = ew_HtmlEncode($this->mobile_number->CurrentValue);
			$this->mobile_number->PlaceHolder = ew_RemoveHtml($this->mobile_number->FldCaption());

			// secondary_number
			$this->secondary_number->EditAttrs["class"] = "form-control";
			$this->secondary_number->EditCustomAttributes = "";
			$this->secondary_number->EditValue = ew_HtmlEncode($this->secondary_number->CurrentValue);
			$this->secondary_number->PlaceHolder = ew_RemoveHtml($this->secondary_number->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// allow_whatsapp
			$this->allow_whatsapp->EditCustomAttributes = "";
			$this->allow_whatsapp->EditValue = $this->allow_whatsapp->Options(FALSE);

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Add refer script
			// user_id

			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";

			// year_id
			$this->year_id->LinkCustomAttributes = "";
			$this->year_id->HrefValue = "";

			// registered_in
			$this->registered_in->LinkCustomAttributes = "";
			$this->registered_in->HrefValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";

			// make_id
			$this->make_id->LinkCustomAttributes = "";
			$this->make_id->HrefValue = "";

			// model_id
			$this->model_id->LinkCustomAttributes = "";
			$this->model_id->HrefValue = "";

			// version_id
			$this->version_id->LinkCustomAttributes = "";
			$this->version_id->HrefValue = "";

			// milage
			$this->milage->LinkCustomAttributes = "";
			$this->milage->HrefValue = "";

			// color_id
			$this->color_id->LinkCustomAttributes = "";
			$this->color_id->HrefValue = "";

			// demand_price
			$this->demand_price->LinkCustomAttributes = "";
			$this->demand_price->HrefValue = "";

			// details
			$this->details->LinkCustomAttributes = "";
			$this->details->HrefValue = "";

			// engine_type_id
			$this->engine_type_id->LinkCustomAttributes = "";
			$this->engine_type_id->HrefValue = "";

			// engine_capicity
			$this->engine_capicity->LinkCustomAttributes = "";
			$this->engine_capicity->HrefValue = "";

			// transmition
			$this->transmition->LinkCustomAttributes = "";
			$this->transmition->HrefValue = "";

			// assembly
			$this->assembly->LinkCustomAttributes = "";
			$this->assembly->HrefValue = "";

			// mobile_number
			$this->mobile_number->LinkCustomAttributes = "";
			$this->mobile_number->HrefValue = "";

			// secondary_number
			$this->secondary_number->LinkCustomAttributes = "";
			$this->secondary_number->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";

			// allow_whatsapp
			$this->allow_whatsapp->LinkCustomAttributes = "";
			$this->allow_whatsapp->HrefValue = "";

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
		if (!$this->user_id->FldIsDetailKey && !is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user_id->FldCaption(), $this->user_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
		}
		if (!$this->year_id->FldIsDetailKey && !is_null($this->year_id->FormValue) && $this->year_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->year_id->FldCaption(), $this->year_id->ReqErrMsg));
		}
		if (!$this->registered_in->FldIsDetailKey && !is_null($this->registered_in->FormValue) && $this->registered_in->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->registered_in->FldCaption(), $this->registered_in->ReqErrMsg));
		}
		if (!$this->city_id->FldIsDetailKey && !is_null($this->city_id->FormValue) && $this->city_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->city_id->FldCaption(), $this->city_id->ReqErrMsg));
		}
		if (!$this->make_id->FldIsDetailKey && !is_null($this->make_id->FormValue) && $this->make_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->make_id->FldCaption(), $this->make_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->milage->FormValue)) {
			ew_AddMessage($gsFormError, $this->milage->FldErrMsg());
		}
		if (!$this->color_id->FldIsDetailKey && !is_null($this->color_id->FormValue) && $this->color_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->color_id->FldCaption(), $this->color_id->ReqErrMsg));
		}
		if (!$this->demand_price->FldIsDetailKey && !is_null($this->demand_price->FormValue) && $this->demand_price->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->demand_price->FldCaption(), $this->demand_price->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->demand_price->FormValue)) {
			ew_AddMessage($gsFormError, $this->demand_price->FldErrMsg());
		}
		if (!$this->details->FldIsDetailKey && !is_null($this->details->FormValue) && $this->details->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->details->FldCaption(), $this->details->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->engine_capicity->FormValue)) {
			ew_AddMessage($gsFormError, $this->engine_capicity->FldErrMsg());
		}
		if (!$this->mobile_number->FldIsDetailKey && !is_null($this->mobile_number->FormValue) && $this->mobile_number->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mobile_number->FldCaption(), $this->mobile_number->ReqErrMsg));
		}
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if ($this->allow_whatsapp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->allow_whatsapp->FldCaption(), $this->allow_whatsapp->ReqErrMsg));
		}
		if ($this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("ad_features", $DetailTblVar) && $GLOBALS["ad_features"]->DetailAdd) {
			if (!isset($GLOBALS["ad_features_grid"])) $GLOBALS["ad_features_grid"] = new cad_features_grid(); // get detail page object
			$GLOBALS["ad_features_grid"]->ValidateGridForm();
		}
		if (in_array("ad_pictures", $DetailTblVar) && $GLOBALS["ad_pictures"]->DetailAdd) {
			if (!isset($GLOBALS["ad_pictures_grid"])) $GLOBALS["ad_pictures_grid"] = new cad_pictures_grid(); // get detail page object
			$GLOBALS["ad_pictures_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, FALSE);

		// year_id
		$this->year_id->SetDbValueDef($rsnew, $this->year_id->CurrentValue, 0, FALSE);

		// registered_in
		$this->registered_in->SetDbValueDef($rsnew, $this->registered_in->CurrentValue, 0, FALSE);

		// city_id
		$this->city_id->SetDbValueDef($rsnew, $this->city_id->CurrentValue, 0, FALSE);

		// make_id
		$this->make_id->SetDbValueDef($rsnew, $this->make_id->CurrentValue, 0, FALSE);

		// model_id
		$this->model_id->SetDbValueDef($rsnew, $this->model_id->CurrentValue, NULL, FALSE);

		// version_id
		$this->version_id->SetDbValueDef($rsnew, $this->version_id->CurrentValue, NULL, FALSE);

		// milage
		$this->milage->SetDbValueDef($rsnew, $this->milage->CurrentValue, NULL, FALSE);

		// color_id
		$this->color_id->SetDbValueDef($rsnew, $this->color_id->CurrentValue, 0, FALSE);

		// demand_price
		$this->demand_price->SetDbValueDef($rsnew, $this->demand_price->CurrentValue, 0, FALSE);

		// details
		$this->details->SetDbValueDef($rsnew, $this->details->CurrentValue, "", FALSE);

		// engine_type_id
		$this->engine_type_id->SetDbValueDef($rsnew, $this->engine_type_id->CurrentValue, NULL, FALSE);

		// engine_capicity
		$this->engine_capicity->SetDbValueDef($rsnew, $this->engine_capicity->CurrentValue, NULL, FALSE);

		// transmition
		$this->transmition->SetDbValueDef($rsnew, $this->transmition->CurrentValue, NULL, FALSE);

		// assembly
		$this->assembly->SetDbValueDef($rsnew, $this->assembly->CurrentValue, NULL, FALSE);

		// mobile_number
		$this->mobile_number->SetDbValueDef($rsnew, $this->mobile_number->CurrentValue, "", FALSE);

		// secondary_number
		$this->secondary_number->SetDbValueDef($rsnew, $this->secondary_number->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

		// address
		$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, NULL, FALSE);

		// allow_whatsapp
		$this->allow_whatsapp->SetDbValueDef($rsnew, ((strval($this->allow_whatsapp->CurrentValue) == "1") ? "1" : "0"), 0, strval($this->allow_whatsapp->CurrentValue) == "");

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, "", strval($this->status->CurrentValue) == "");

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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("ad_features", $DetailTblVar) && $GLOBALS["ad_features"]->DetailAdd) {
				$GLOBALS["ad_features"]->ad_id->setSessionValue($this->ID->CurrentValue); // Set master key
				if (!isset($GLOBALS["ad_features_grid"])) $GLOBALS["ad_features_grid"] = new cad_features_grid(); // Get detail page object
				$AddRow = $GLOBALS["ad_features_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["ad_features"]->ad_id->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("ad_pictures", $DetailTblVar) && $GLOBALS["ad_pictures"]->DetailAdd) {
				$GLOBALS["ad_pictures"]->ad_id->setSessionValue($this->ID->CurrentValue); // Set master key
				if (!isset($GLOBALS["ad_pictures_grid"])) $GLOBALS["ad_pictures_grid"] = new cad_pictures_grid(); // Get detail page object
				$AddRow = $GLOBALS["ad_pictures_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["ad_pictures"]->ad_id->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
			if (in_array("ad_features", $DetailTblVar)) {
				if (!isset($GLOBALS["ad_features_grid"]))
					$GLOBALS["ad_features_grid"] = new cad_features_grid;
				if ($GLOBALS["ad_features_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["ad_features_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["ad_features_grid"]->CurrentMode = "add";
					$GLOBALS["ad_features_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["ad_features_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["ad_features_grid"]->setStartRecordNumber(1);
					$GLOBALS["ad_features_grid"]->ad_id->FldIsDetailKey = TRUE;
					$GLOBALS["ad_features_grid"]->ad_id->CurrentValue = $this->ID->CurrentValue;
					$GLOBALS["ad_features_grid"]->ad_id->setSessionValue($GLOBALS["ad_features_grid"]->ad_id->CurrentValue);
				}
			}
			if (in_array("ad_pictures", $DetailTblVar)) {
				if (!isset($GLOBALS["ad_pictures_grid"]))
					$GLOBALS["ad_pictures_grid"] = new cad_pictures_grid;
				if ($GLOBALS["ad_pictures_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["ad_pictures_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["ad_pictures_grid"]->CurrentMode = "add";
					$GLOBALS["ad_pictures_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["ad_pictures_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["ad_pictures_grid"]->setStartRecordNumber(1);
					$GLOBALS["ad_pictures_grid"]->ad_id->FldIsDetailKey = TRUE;
					$GLOBALS["ad_pictures_grid"]->ad_id->CurrentValue = $this->ID->CurrentValue;
					$GLOBALS["ad_pictures_grid"]->ad_id->setSessionValue($GLOBALS["ad_pictures_grid"]->ad_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("car_adslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Set up detail pages
	function SetupDetailPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add('ad_features');
		$pages->Add('ad_pictures');
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
if (!isset($car_ads_add)) $car_ads_add = new ccar_ads_add();

// Page init
$car_ads_add->Page_Init();

// Page main
$car_ads_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$car_ads_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fcar_adsadd = new ew_Form("fcar_adsadd", "add");

// Validate form
fcar_adsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->user_id->FldCaption(), $car_ads->user_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($car_ads->user_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_year_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->year_id->FldCaption(), $car_ads->year_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_registered_in");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->registered_in->FldCaption(), $car_ads->registered_in->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_city_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->city_id->FldCaption(), $car_ads->city_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_make_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->make_id->FldCaption(), $car_ads->make_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_milage");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($car_ads->milage->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_color_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->color_id->FldCaption(), $car_ads->color_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_demand_price");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->demand_price->FldCaption(), $car_ads->demand_price->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_demand_price");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($car_ads->demand_price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_details");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->details->FldCaption(), $car_ads->details->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_engine_capicity");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($car_ads->engine_capicity->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mobile_number");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->mobile_number->FldCaption(), $car_ads->mobile_number->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->name->FldCaption(), $car_ads->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_allow_whatsapp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->allow_whatsapp->FldCaption(), $car_ads->allow_whatsapp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $car_ads->status->FldCaption(), $car_ads->status->ReqErrMsg)) ?>");

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
fcar_adsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcar_adsadd.ValidateRequired = true;
<?php } else { ?>
fcar_adsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcar_adsadd.Lists["x_user_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","x__email","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_year_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_year","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_registered_in"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_city_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_make_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_model_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_version_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_color_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_engine_type_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_transmition"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_transmition"].Options = <?php echo json_encode($car_ads->transmition->Options()) ?>;
fcar_adsadd.Lists["x_assembly"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_assembly"].Options = <?php echo json_encode($car_ads->assembly->Options()) ?>;
fcar_adsadd.Lists["x_allow_whatsapp"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_allow_whatsapp"].Options = <?php echo json_encode($car_ads->allow_whatsapp->Options()) ?>;
fcar_adsadd.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsadd.Lists["x_status"].Options = <?php echo json_encode($car_ads->status->Options()) ?>;

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
<?php $car_ads_add->ShowPageHeader(); ?>
<?php
$car_ads_add->ShowMessage();
?>
<form name="fcar_adsadd" id="fcar_adsadd" class="<?php echo $car_ads_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($car_ads_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $car_ads_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="car_ads">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($car_ads->user_id->Visible) { // user_id ?>
	<div id="r_user_id" class="form-group">
		<label id="elh_car_ads_user_id" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->user_id->CellAttributes() ?>>
<span id="el_car_ads_user_id">
<?php
$wrkonchange = trim(" " . @$car_ads->user_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$car_ads->user_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_user_id" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_user_id" id="sv_x_user_id" value="<?php echo $car_ads->user_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($car_ads->user_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($car_ads->user_id->getPlaceHolder()) ?>"<?php echo $car_ads->user_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="car_ads" data-field="x_user_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->user_id->DisplayValueSeparator) ? json_encode($car_ads->user_id->DisplayValueSeparator) : $car_ads->user_id->DisplayValueSeparator) ?>" name="x_user_id" id="x_user_id" value="<?php echo ew_HtmlEncode($car_ads->user_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, `email` AS `Disp2Fld` FROM `users`";
$sWhereWrk = "`name` LIKE '{query_value}%' OR CONCAT(`name`,'" . ew_ValueSeparator(1, $Page->user_id) . "',`email`) LIKE '{query_value}%'";
$car_ads->Lookup_Selecting($car_ads->user_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_user_id" id="q_x_user_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fcar_adsadd.CreateAutoSuggest({"id":"x_user_id","forceSelect":false});
</script>
</span>
<?php echo $car_ads->user_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->year_id->Visible) { // year_id ?>
	<div id="r_year_id" class="form-group">
		<label id="elh_car_ads_year_id" for="x_year_id" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->year_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->year_id->CellAttributes() ?>>
<span id="el_car_ads_year_id">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $car_ads->year_id->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x_year_id" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
$arwrk = $car_ads->year_id->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($car_ads->year_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "") {
			$emptywrk = FALSE;
?>
<input type="radio" data-table="car_ads" data-field="x_year_id" name="x_year_id" id="x_year_id_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $car_ads->year_id->EditAttributes() ?>><?php echo $car_ads->year_id->DisplayValue($arwrk[$rowcntwrk]) ?>
<?php
		}
	}
	if ($emptywrk && strval($car_ads->year_id->CurrentValue) <> "") {
?>
<input type="radio" data-table="car_ads" data-field="x_year_id" name="x_year_id" id="x_year_id_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($car_ads->year_id->CurrentValue) ?>" checked<?php echo $car_ads->year_id->EditAttributes() ?>><?php echo $car_ads->year_id->CurrentValue ?>
<?php
    }
}
?>
		</div>
	</div>
	<div id="tp_x_year_id" class="ewTemplate"><input type="radio" data-table="car_ads" data-field="x_year_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->year_id->DisplayValueSeparator) ? json_encode($car_ads->year_id->DisplayValueSeparator) : $car_ads->year_id->DisplayValueSeparator) ?>" name="x_year_id" id="x_year_id" value="{value}"<?php echo $car_ads->year_id->EditAttributes() ?>></div>
</div>
<?php
$sSqlWrk = "SELECT `ID`, `year` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_years`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->year_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->year_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->year_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->year_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_year_id" id="s_x_year_id" value="<?php echo $car_ads->year_id->LookupFilterQuery() ?>">
</span>
<?php echo $car_ads->year_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->registered_in->Visible) { // registered_in ?>
	<div id="r_registered_in" class="form-group">
		<label id="elh_car_ads_registered_in" for="x_registered_in" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->registered_in->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->registered_in->CellAttributes() ?>>
<span id="el_car_ads_registered_in">
<select data-table="car_ads" data-field="x_registered_in" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->registered_in->DisplayValueSeparator) ? json_encode($car_ads->registered_in->DisplayValueSeparator) : $car_ads->registered_in->DisplayValueSeparator) ?>" id="x_registered_in" name="x_registered_in"<?php echo $car_ads->registered_in->EditAttributes() ?>>
<?php
if (is_array($car_ads->registered_in->EditValue)) {
	$arwrk = $car_ads->registered_in->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->registered_in->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->registered_in->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->registered_in->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->registered_in->CurrentValue) ?>" selected><?php echo $car_ads->registered_in->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
$sWhereWrk = "";
$car_ads->registered_in->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->registered_in->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->registered_in, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->registered_in->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_registered_in" id="s_x_registered_in" value="<?php echo $car_ads->registered_in->LookupFilterQuery() ?>">
</span>
<?php echo $car_ads->registered_in->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->city_id->Visible) { // city_id ?>
	<div id="r_city_id" class="form-group">
		<label id="elh_car_ads_city_id" for="x_city_id" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->city_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->city_id->CellAttributes() ?>>
<span id="el_car_ads_city_id">
<select data-table="car_ads" data-field="x_city_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->city_id->DisplayValueSeparator) ? json_encode($car_ads->city_id->DisplayValueSeparator) : $car_ads->city_id->DisplayValueSeparator) ?>" id="x_city_id" name="x_city_id"<?php echo $car_ads->city_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->city_id->EditValue)) {
	$arwrk = $car_ads->city_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->city_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->city_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->city_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->city_id->CurrentValue) ?>" selected><?php echo $car_ads->city_id->CurrentValue ?></option>
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
$car_ads->city_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->city_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->city_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->city_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_city_id" id="s_x_city_id" value="<?php echo $car_ads->city_id->LookupFilterQuery() ?>">
</span>
<?php echo $car_ads->city_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->make_id->Visible) { // make_id ?>
	<div id="r_make_id" class="form-group">
		<label id="elh_car_ads_make_id" for="x_make_id" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->make_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->make_id->CellAttributes() ?>>
<span id="el_car_ads_make_id">
<select data-table="car_ads" data-field="x_make_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->make_id->DisplayValueSeparator) ? json_encode($car_ads->make_id->DisplayValueSeparator) : $car_ads->make_id->DisplayValueSeparator) ?>" id="x_make_id" name="x_make_id"<?php echo $car_ads->make_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->make_id->EditValue)) {
	$arwrk = $car_ads->make_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->make_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->make_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->make_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->make_id->CurrentValue) ?>" selected><?php echo $car_ads->make_id->CurrentValue ?></option>
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
$car_ads->make_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->make_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->make_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->make_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_make_id" id="s_x_make_id" value="<?php echo $car_ads->make_id->LookupFilterQuery() ?>">
</span>
<?php echo $car_ads->make_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->model_id->Visible) { // model_id ?>
	<div id="r_model_id" class="form-group">
		<label id="elh_car_ads_model_id" for="x_model_id" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->model_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->model_id->CellAttributes() ?>>
<span id="el_car_ads_model_id">
<select data-table="car_ads" data-field="x_model_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->model_id->DisplayValueSeparator) ? json_encode($car_ads->model_id->DisplayValueSeparator) : $car_ads->model_id->DisplayValueSeparator) ?>" id="x_model_id" name="x_model_id"<?php echo $car_ads->model_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->model_id->EditValue)) {
	$arwrk = $car_ads->model_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->model_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->model_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->model_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->model_id->CurrentValue) ?>" selected><?php echo $car_ads->model_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_models`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->model_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->model_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->model_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->model_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_model_id" id="s_x_model_id" value="<?php echo $car_ads->model_id->LookupFilterQuery() ?>">
</span>
<?php echo $car_ads->model_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->version_id->Visible) { // version_id ?>
	<div id="r_version_id" class="form-group">
		<label id="elh_car_ads_version_id" for="x_version_id" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->version_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->version_id->CellAttributes() ?>>
<span id="el_car_ads_version_id">
<select data-table="car_ads" data-field="x_version_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->version_id->DisplayValueSeparator) ? json_encode($car_ads->version_id->DisplayValueSeparator) : $car_ads->version_id->DisplayValueSeparator) ?>" id="x_version_id" name="x_version_id"<?php echo $car_ads->version_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->version_id->EditValue)) {
	$arwrk = $car_ads->version_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->version_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->version_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->version_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->version_id->CurrentValue) ?>" selected><?php echo $car_ads->version_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_car_versions`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->version_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->version_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->version_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->version_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_version_id" id="s_x_version_id" value="<?php echo $car_ads->version_id->LookupFilterQuery() ?>">
</span>
<?php echo $car_ads->version_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->milage->Visible) { // milage ?>
	<div id="r_milage" class="form-group">
		<label id="elh_car_ads_milage" for="x_milage" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->milage->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->milage->CellAttributes() ?>>
<span id="el_car_ads_milage">
<input type="text" data-table="car_ads" data-field="x_milage" name="x_milage" id="x_milage" size="30" placeholder="<?php echo ew_HtmlEncode($car_ads->milage->getPlaceHolder()) ?>" value="<?php echo $car_ads->milage->EditValue ?>"<?php echo $car_ads->milage->EditAttributes() ?>>
</span>
<?php echo $car_ads->milage->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->color_id->Visible) { // color_id ?>
	<div id="r_color_id" class="form-group">
		<label id="elh_car_ads_color_id" for="x_color_id" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->color_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->color_id->CellAttributes() ?>>
<span id="el_car_ads_color_id">
<select data-table="car_ads" data-field="x_color_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->color_id->DisplayValueSeparator) ? json_encode($car_ads->color_id->DisplayValueSeparator) : $car_ads->color_id->DisplayValueSeparator) ?>" id="x_color_id" name="x_color_id"<?php echo $car_ads->color_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->color_id->EditValue)) {
	$arwrk = $car_ads->color_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->color_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->color_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->color_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->color_id->CurrentValue) ?>" selected><?php echo $car_ads->color_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_body_colors`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->color_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->color_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->color_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->color_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_color_id" id="s_x_color_id" value="<?php echo $car_ads->color_id->LookupFilterQuery() ?>">
</span>
<?php echo $car_ads->color_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->demand_price->Visible) { // demand_price ?>
	<div id="r_demand_price" class="form-group">
		<label id="elh_car_ads_demand_price" for="x_demand_price" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->demand_price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->demand_price->CellAttributes() ?>>
<span id="el_car_ads_demand_price">
<input type="text" data-table="car_ads" data-field="x_demand_price" name="x_demand_price" id="x_demand_price" size="30" placeholder="<?php echo ew_HtmlEncode($car_ads->demand_price->getPlaceHolder()) ?>" value="<?php echo $car_ads->demand_price->EditValue ?>"<?php echo $car_ads->demand_price->EditAttributes() ?>>
</span>
<?php echo $car_ads->demand_price->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->details->Visible) { // details ?>
	<div id="r_details" class="form-group">
		<label id="elh_car_ads_details" for="x_details" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->details->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->details->CellAttributes() ?>>
<span id="el_car_ads_details">
<textarea data-table="car_ads" data-field="x_details" name="x_details" id="x_details" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($car_ads->details->getPlaceHolder()) ?>"<?php echo $car_ads->details->EditAttributes() ?>><?php echo $car_ads->details->EditValue ?></textarea>
</span>
<?php echo $car_ads->details->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->engine_type_id->Visible) { // engine_type_id ?>
	<div id="r_engine_type_id" class="form-group">
		<label id="elh_car_ads_engine_type_id" for="x_engine_type_id" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->engine_type_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->engine_type_id->CellAttributes() ?>>
<span id="el_car_ads_engine_type_id">
<select data-table="car_ads" data-field="x_engine_type_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->engine_type_id->DisplayValueSeparator) ? json_encode($car_ads->engine_type_id->DisplayValueSeparator) : $car_ads->engine_type_id->DisplayValueSeparator) ?>" id="x_engine_type_id" name="x_engine_type_id"<?php echo $car_ads->engine_type_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->engine_type_id->EditValue)) {
	$arwrk = $car_ads->engine_type_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->engine_type_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->engine_type_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->engine_type_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->engine_type_id->CurrentValue) ?>" selected><?php echo $car_ads->engine_type_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_engine_types`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->engine_type_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->engine_type_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->engine_type_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->engine_type_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_engine_type_id" id="s_x_engine_type_id" value="<?php echo $car_ads->engine_type_id->LookupFilterQuery() ?>">
</span>
<?php echo $car_ads->engine_type_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->engine_capicity->Visible) { // engine_capicity ?>
	<div id="r_engine_capicity" class="form-group">
		<label id="elh_car_ads_engine_capicity" for="x_engine_capicity" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->engine_capicity->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->engine_capicity->CellAttributes() ?>>
<span id="el_car_ads_engine_capicity">
<input type="text" data-table="car_ads" data-field="x_engine_capicity" name="x_engine_capicity" id="x_engine_capicity" size="30" placeholder="<?php echo ew_HtmlEncode($car_ads->engine_capicity->getPlaceHolder()) ?>" value="<?php echo $car_ads->engine_capicity->EditValue ?>"<?php echo $car_ads->engine_capicity->EditAttributes() ?>>
</span>
<?php echo $car_ads->engine_capicity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->transmition->Visible) { // transmition ?>
	<div id="r_transmition" class="form-group">
		<label id="elh_car_ads_transmition" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->transmition->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->transmition->CellAttributes() ?>>
<span id="el_car_ads_transmition">
<div id="tp_x_transmition" class="ewTemplate"><input type="radio" data-table="car_ads" data-field="x_transmition" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->transmition->DisplayValueSeparator) ? json_encode($car_ads->transmition->DisplayValueSeparator) : $car_ads->transmition->DisplayValueSeparator) ?>" name="x_transmition" id="x_transmition" value="{value}"<?php echo $car_ads->transmition->EditAttributes() ?>></div>
<div id="dsl_x_transmition" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $car_ads->transmition->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($car_ads->transmition->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_transmition" name="x_transmition" id="x_transmition_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $car_ads->transmition->EditAttributes() ?>><?php echo $car_ads->transmition->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($car_ads->transmition->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_transmition" name="x_transmition" id="x_transmition_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($car_ads->transmition->CurrentValue) ?>" checked<?php echo $car_ads->transmition->EditAttributes() ?>><?php echo $car_ads->transmition->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $car_ads->transmition->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->assembly->Visible) { // assembly ?>
	<div id="r_assembly" class="form-group">
		<label id="elh_car_ads_assembly" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->assembly->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->assembly->CellAttributes() ?>>
<span id="el_car_ads_assembly">
<div id="tp_x_assembly" class="ewTemplate"><input type="radio" data-table="car_ads" data-field="x_assembly" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->assembly->DisplayValueSeparator) ? json_encode($car_ads->assembly->DisplayValueSeparator) : $car_ads->assembly->DisplayValueSeparator) ?>" name="x_assembly" id="x_assembly" value="{value}"<?php echo $car_ads->assembly->EditAttributes() ?>></div>
<div id="dsl_x_assembly" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $car_ads->assembly->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($car_ads->assembly->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_assembly" name="x_assembly" id="x_assembly_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $car_ads->assembly->EditAttributes() ?>><?php echo $car_ads->assembly->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($car_ads->assembly->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_assembly" name="x_assembly" id="x_assembly_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($car_ads->assembly->CurrentValue) ?>" checked<?php echo $car_ads->assembly->EditAttributes() ?>><?php echo $car_ads->assembly->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $car_ads->assembly->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->mobile_number->Visible) { // mobile_number ?>
	<div id="r_mobile_number" class="form-group">
		<label id="elh_car_ads_mobile_number" for="x_mobile_number" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->mobile_number->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->mobile_number->CellAttributes() ?>>
<span id="el_car_ads_mobile_number">
<input type="text" data-table="car_ads" data-field="x_mobile_number" name="x_mobile_number" id="x_mobile_number" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($car_ads->mobile_number->getPlaceHolder()) ?>" value="<?php echo $car_ads->mobile_number->EditValue ?>"<?php echo $car_ads->mobile_number->EditAttributes() ?>>
</span>
<?php echo $car_ads->mobile_number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->secondary_number->Visible) { // secondary_number ?>
	<div id="r_secondary_number" class="form-group">
		<label id="elh_car_ads_secondary_number" for="x_secondary_number" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->secondary_number->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->secondary_number->CellAttributes() ?>>
<span id="el_car_ads_secondary_number">
<input type="text" data-table="car_ads" data-field="x_secondary_number" name="x_secondary_number" id="x_secondary_number" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($car_ads->secondary_number->getPlaceHolder()) ?>" value="<?php echo $car_ads->secondary_number->EditValue ?>"<?php echo $car_ads->secondary_number->EditAttributes() ?>>
</span>
<?php echo $car_ads->secondary_number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_car_ads__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->_email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->_email->CellAttributes() ?>>
<span id="el_car_ads__email">
<input type="text" data-table="car_ads" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($car_ads->_email->getPlaceHolder()) ?>" value="<?php echo $car_ads->_email->EditValue ?>"<?php echo $car_ads->_email->EditAttributes() ?>>
</span>
<?php echo $car_ads->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_car_ads_name" for="x_name" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->name->CellAttributes() ?>>
<span id="el_car_ads_name">
<input type="text" data-table="car_ads" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($car_ads->name->getPlaceHolder()) ?>" value="<?php echo $car_ads->name->EditValue ?>"<?php echo $car_ads->name->EditAttributes() ?>>
</span>
<?php echo $car_ads->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_car_ads_address" for="x_address" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->address->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->address->CellAttributes() ?>>
<span id="el_car_ads_address">
<input type="text" data-table="car_ads" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($car_ads->address->getPlaceHolder()) ?>" value="<?php echo $car_ads->address->EditValue ?>"<?php echo $car_ads->address->EditAttributes() ?>>
</span>
<?php echo $car_ads->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->allow_whatsapp->Visible) { // allow_whatsapp ?>
	<div id="r_allow_whatsapp" class="form-group">
		<label id="elh_car_ads_allow_whatsapp" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->allow_whatsapp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->allow_whatsapp->CellAttributes() ?>>
<span id="el_car_ads_allow_whatsapp">
<div id="tp_x_allow_whatsapp" class="ewTemplate"><input type="radio" data-table="car_ads" data-field="x_allow_whatsapp" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->allow_whatsapp->DisplayValueSeparator) ? json_encode($car_ads->allow_whatsapp->DisplayValueSeparator) : $car_ads->allow_whatsapp->DisplayValueSeparator) ?>" name="x_allow_whatsapp" id="x_allow_whatsapp" value="{value}"<?php echo $car_ads->allow_whatsapp->EditAttributes() ?>></div>
<div id="dsl_x_allow_whatsapp" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $car_ads->allow_whatsapp->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($car_ads->allow_whatsapp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_allow_whatsapp" name="x_allow_whatsapp" id="x_allow_whatsapp_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $car_ads->allow_whatsapp->EditAttributes() ?>><?php echo $car_ads->allow_whatsapp->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($car_ads->allow_whatsapp->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_allow_whatsapp" name="x_allow_whatsapp" id="x_allow_whatsapp_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($car_ads->allow_whatsapp->CurrentValue) ?>" checked<?php echo $car_ads->allow_whatsapp->EditAttributes() ?>><?php echo $car_ads->allow_whatsapp->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $car_ads->allow_whatsapp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($car_ads->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_car_ads_status" class="col-sm-2 control-label ewLabel"><?php echo $car_ads->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $car_ads->status->CellAttributes() ?>>
<span id="el_car_ads_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="car_ads" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->status->DisplayValueSeparator) ? json_encode($car_ads->status->DisplayValueSeparator) : $car_ads->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $car_ads->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $car_ads->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($car_ads->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $car_ads->status->EditAttributes() ?>><?php echo $car_ads->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($car_ads->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($car_ads->status->CurrentValue) ?>" checked<?php echo $car_ads->status->EditAttributes() ?>><?php echo $car_ads->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $car_ads->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if ($car_ads->getCurrentDetailTable() <> "") { ?>
<?php
	$FirstActiveDetailTable = $car_ads_add->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages">
<div class="tabbable" id="car_ads_add_details">
	<ul class="nav<?php echo $car_ads_add->DetailPages->NavStyle() ?>">
<?php
	if (in_array("ad_features", explode(",", $car_ads->getCurrentDetailTable())) && $ad_features->DetailAdd) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "ad_features") {
			$FirstActiveDetailTable = "ad_features";
		}
?>
		<li<?php echo $car_ads_add->DetailPages->TabStyle("ad_features") ?>><a href="#tab_ad_features" data-toggle="tab"><?php echo $Language->TablePhrase("ad_features", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("ad_pictures", explode(",", $car_ads->getCurrentDetailTable())) && $ad_pictures->DetailAdd) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "ad_pictures") {
			$FirstActiveDetailTable = "ad_pictures";
		}
?>
		<li<?php echo $car_ads_add->DetailPages->TabStyle("ad_pictures") ?>><a href="#tab_ad_pictures" data-toggle="tab"><?php echo $Language->TablePhrase("ad_pictures", "TblCaption") ?></a></li>
<?php
	}
?>
	</ul>
	<div class="tab-content">
<?php
	if (in_array("ad_features", explode(",", $car_ads->getCurrentDetailTable())) && $ad_features->DetailAdd) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "ad_features") {
			$FirstActiveDetailTable = "ad_features";
		}
?>
		<div class="tab-pane<?php echo $car_ads_add->DetailPages->PageStyle("ad_features") ?>" id="tab_ad_features">
<?php include_once "ad_featuresgrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("ad_pictures", explode(",", $car_ads->getCurrentDetailTable())) && $ad_pictures->DetailAdd) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "ad_pictures") {
			$FirstActiveDetailTable = "ad_pictures";
		}
?>
		<div class="tab-pane<?php echo $car_ads_add->DetailPages->PageStyle("ad_pictures") ?>" id="tab_ad_pictures">
<?php include_once "ad_picturesgrid.php" ?>
		</div>
<?php } ?>
	</div>
</div>
</div>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $car_ads_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcar_adsadd.Init();
</script>
<?php
$car_ads_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$car_ads_add->Page_Terminate();
?>
