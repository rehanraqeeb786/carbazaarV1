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

$car_ads_list = NULL; // Initialize page object first

class ccar_ads_list extends ccar_ads {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'car_ads';

	// Page object name
	var $PageObjName = 'car_ads_list';

	// Grid form hidden field names
	var $FormName = 'fcar_adslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "car_adsadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "car_adsdelete.php";
		$this->MultiUpdateUrl = "car_adsupdate.php";

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fcar_adslistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Restore filter list
			$this->RestoreFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->ID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->ID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->user_id->AdvancedSearch->ToJSON(), ","); // Field user_id
		$sFilterList = ew_Concat($sFilterList, $this->ad_title->AdvancedSearch->ToJSON(), ","); // Field ad_title
		$sFilterList = ew_Concat($sFilterList, $this->year_id->AdvancedSearch->ToJSON(), ","); // Field year_id
		$sFilterList = ew_Concat($sFilterList, $this->registered_in->AdvancedSearch->ToJSON(), ","); // Field registered_in
		$sFilterList = ew_Concat($sFilterList, $this->city_id->AdvancedSearch->ToJSON(), ","); // Field city_id
		$sFilterList = ew_Concat($sFilterList, $this->make_id->AdvancedSearch->ToJSON(), ","); // Field make_id
		$sFilterList = ew_Concat($sFilterList, $this->model_id->AdvancedSearch->ToJSON(), ","); // Field model_id
		$sFilterList = ew_Concat($sFilterList, $this->version_id->AdvancedSearch->ToJSON(), ","); // Field version_id
		$sFilterList = ew_Concat($sFilterList, $this->milage->AdvancedSearch->ToJSON(), ","); // Field milage
		$sFilterList = ew_Concat($sFilterList, $this->color_id->AdvancedSearch->ToJSON(), ","); // Field color_id
		$sFilterList = ew_Concat($sFilterList, $this->demand_price->AdvancedSearch->ToJSON(), ","); // Field demand_price
		$sFilterList = ew_Concat($sFilterList, $this->details->AdvancedSearch->ToJSON(), ","); // Field details
		$sFilterList = ew_Concat($sFilterList, $this->engine_type_id->AdvancedSearch->ToJSON(), ","); // Field engine_type_id
		$sFilterList = ew_Concat($sFilterList, $this->engine_capicity->AdvancedSearch->ToJSON(), ","); // Field engine_capicity
		$sFilterList = ew_Concat($sFilterList, $this->transmition->AdvancedSearch->ToJSON(), ","); // Field transmition
		$sFilterList = ew_Concat($sFilterList, $this->assembly->AdvancedSearch->ToJSON(), ","); // Field assembly
		$sFilterList = ew_Concat($sFilterList, $this->mobile_number->AdvancedSearch->ToJSON(), ","); // Field mobile_number
		$sFilterList = ew_Concat($sFilterList, $this->secondary_number->AdvancedSearch->ToJSON(), ","); // Field secondary_number
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJSON(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->name->AdvancedSearch->ToJSON(), ","); // Field name
		$sFilterList = ew_Concat($sFilterList, $this->address->AdvancedSearch->ToJSON(), ","); // Field address
		$sFilterList = ew_Concat($sFilterList, $this->allow_whatsapp->AdvancedSearch->ToJSON(), ","); // Field allow_whatsapp
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJSON(), ","); // Field status

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field user_id
		$this->user_id->AdvancedSearch->SearchValue = @$filter["x_user_id"];
		$this->user_id->AdvancedSearch->SearchOperator = @$filter["z_user_id"];
		$this->user_id->AdvancedSearch->SearchCondition = @$filter["v_user_id"];
		$this->user_id->AdvancedSearch->SearchValue2 = @$filter["y_user_id"];
		$this->user_id->AdvancedSearch->SearchOperator2 = @$filter["w_user_id"];
		$this->user_id->AdvancedSearch->Save();

		// Field ad_title
		$this->ad_title->AdvancedSearch->SearchValue = @$filter["x_ad_title"];
		$this->ad_title->AdvancedSearch->SearchOperator = @$filter["z_ad_title"];
		$this->ad_title->AdvancedSearch->SearchCondition = @$filter["v_ad_title"];
		$this->ad_title->AdvancedSearch->SearchValue2 = @$filter["y_ad_title"];
		$this->ad_title->AdvancedSearch->SearchOperator2 = @$filter["w_ad_title"];
		$this->ad_title->AdvancedSearch->Save();

		// Field year_id
		$this->year_id->AdvancedSearch->SearchValue = @$filter["x_year_id"];
		$this->year_id->AdvancedSearch->SearchOperator = @$filter["z_year_id"];
		$this->year_id->AdvancedSearch->SearchCondition = @$filter["v_year_id"];
		$this->year_id->AdvancedSearch->SearchValue2 = @$filter["y_year_id"];
		$this->year_id->AdvancedSearch->SearchOperator2 = @$filter["w_year_id"];
		$this->year_id->AdvancedSearch->Save();

		// Field registered_in
		$this->registered_in->AdvancedSearch->SearchValue = @$filter["x_registered_in"];
		$this->registered_in->AdvancedSearch->SearchOperator = @$filter["z_registered_in"];
		$this->registered_in->AdvancedSearch->SearchCondition = @$filter["v_registered_in"];
		$this->registered_in->AdvancedSearch->SearchValue2 = @$filter["y_registered_in"];
		$this->registered_in->AdvancedSearch->SearchOperator2 = @$filter["w_registered_in"];
		$this->registered_in->AdvancedSearch->Save();

		// Field city_id
		$this->city_id->AdvancedSearch->SearchValue = @$filter["x_city_id"];
		$this->city_id->AdvancedSearch->SearchOperator = @$filter["z_city_id"];
		$this->city_id->AdvancedSearch->SearchCondition = @$filter["v_city_id"];
		$this->city_id->AdvancedSearch->SearchValue2 = @$filter["y_city_id"];
		$this->city_id->AdvancedSearch->SearchOperator2 = @$filter["w_city_id"];
		$this->city_id->AdvancedSearch->Save();

		// Field make_id
		$this->make_id->AdvancedSearch->SearchValue = @$filter["x_make_id"];
		$this->make_id->AdvancedSearch->SearchOperator = @$filter["z_make_id"];
		$this->make_id->AdvancedSearch->SearchCondition = @$filter["v_make_id"];
		$this->make_id->AdvancedSearch->SearchValue2 = @$filter["y_make_id"];
		$this->make_id->AdvancedSearch->SearchOperator2 = @$filter["w_make_id"];
		$this->make_id->AdvancedSearch->Save();

		// Field model_id
		$this->model_id->AdvancedSearch->SearchValue = @$filter["x_model_id"];
		$this->model_id->AdvancedSearch->SearchOperator = @$filter["z_model_id"];
		$this->model_id->AdvancedSearch->SearchCondition = @$filter["v_model_id"];
		$this->model_id->AdvancedSearch->SearchValue2 = @$filter["y_model_id"];
		$this->model_id->AdvancedSearch->SearchOperator2 = @$filter["w_model_id"];
		$this->model_id->AdvancedSearch->Save();

		// Field version_id
		$this->version_id->AdvancedSearch->SearchValue = @$filter["x_version_id"];
		$this->version_id->AdvancedSearch->SearchOperator = @$filter["z_version_id"];
		$this->version_id->AdvancedSearch->SearchCondition = @$filter["v_version_id"];
		$this->version_id->AdvancedSearch->SearchValue2 = @$filter["y_version_id"];
		$this->version_id->AdvancedSearch->SearchOperator2 = @$filter["w_version_id"];
		$this->version_id->AdvancedSearch->Save();

		// Field milage
		$this->milage->AdvancedSearch->SearchValue = @$filter["x_milage"];
		$this->milage->AdvancedSearch->SearchOperator = @$filter["z_milage"];
		$this->milage->AdvancedSearch->SearchCondition = @$filter["v_milage"];
		$this->milage->AdvancedSearch->SearchValue2 = @$filter["y_milage"];
		$this->milage->AdvancedSearch->SearchOperator2 = @$filter["w_milage"];
		$this->milage->AdvancedSearch->Save();

		// Field color_id
		$this->color_id->AdvancedSearch->SearchValue = @$filter["x_color_id"];
		$this->color_id->AdvancedSearch->SearchOperator = @$filter["z_color_id"];
		$this->color_id->AdvancedSearch->SearchCondition = @$filter["v_color_id"];
		$this->color_id->AdvancedSearch->SearchValue2 = @$filter["y_color_id"];
		$this->color_id->AdvancedSearch->SearchOperator2 = @$filter["w_color_id"];
		$this->color_id->AdvancedSearch->Save();

		// Field demand_price
		$this->demand_price->AdvancedSearch->SearchValue = @$filter["x_demand_price"];
		$this->demand_price->AdvancedSearch->SearchOperator = @$filter["z_demand_price"];
		$this->demand_price->AdvancedSearch->SearchCondition = @$filter["v_demand_price"];
		$this->demand_price->AdvancedSearch->SearchValue2 = @$filter["y_demand_price"];
		$this->demand_price->AdvancedSearch->SearchOperator2 = @$filter["w_demand_price"];
		$this->demand_price->AdvancedSearch->Save();

		// Field details
		$this->details->AdvancedSearch->SearchValue = @$filter["x_details"];
		$this->details->AdvancedSearch->SearchOperator = @$filter["z_details"];
		$this->details->AdvancedSearch->SearchCondition = @$filter["v_details"];
		$this->details->AdvancedSearch->SearchValue2 = @$filter["y_details"];
		$this->details->AdvancedSearch->SearchOperator2 = @$filter["w_details"];
		$this->details->AdvancedSearch->Save();

		// Field engine_type_id
		$this->engine_type_id->AdvancedSearch->SearchValue = @$filter["x_engine_type_id"];
		$this->engine_type_id->AdvancedSearch->SearchOperator = @$filter["z_engine_type_id"];
		$this->engine_type_id->AdvancedSearch->SearchCondition = @$filter["v_engine_type_id"];
		$this->engine_type_id->AdvancedSearch->SearchValue2 = @$filter["y_engine_type_id"];
		$this->engine_type_id->AdvancedSearch->SearchOperator2 = @$filter["w_engine_type_id"];
		$this->engine_type_id->AdvancedSearch->Save();

		// Field engine_capicity
		$this->engine_capicity->AdvancedSearch->SearchValue = @$filter["x_engine_capicity"];
		$this->engine_capicity->AdvancedSearch->SearchOperator = @$filter["z_engine_capicity"];
		$this->engine_capicity->AdvancedSearch->SearchCondition = @$filter["v_engine_capicity"];
		$this->engine_capicity->AdvancedSearch->SearchValue2 = @$filter["y_engine_capicity"];
		$this->engine_capicity->AdvancedSearch->SearchOperator2 = @$filter["w_engine_capicity"];
		$this->engine_capicity->AdvancedSearch->Save();

		// Field transmition
		$this->transmition->AdvancedSearch->SearchValue = @$filter["x_transmition"];
		$this->transmition->AdvancedSearch->SearchOperator = @$filter["z_transmition"];
		$this->transmition->AdvancedSearch->SearchCondition = @$filter["v_transmition"];
		$this->transmition->AdvancedSearch->SearchValue2 = @$filter["y_transmition"];
		$this->transmition->AdvancedSearch->SearchOperator2 = @$filter["w_transmition"];
		$this->transmition->AdvancedSearch->Save();

		// Field assembly
		$this->assembly->AdvancedSearch->SearchValue = @$filter["x_assembly"];
		$this->assembly->AdvancedSearch->SearchOperator = @$filter["z_assembly"];
		$this->assembly->AdvancedSearch->SearchCondition = @$filter["v_assembly"];
		$this->assembly->AdvancedSearch->SearchValue2 = @$filter["y_assembly"];
		$this->assembly->AdvancedSearch->SearchOperator2 = @$filter["w_assembly"];
		$this->assembly->AdvancedSearch->Save();

		// Field mobile_number
		$this->mobile_number->AdvancedSearch->SearchValue = @$filter["x_mobile_number"];
		$this->mobile_number->AdvancedSearch->SearchOperator = @$filter["z_mobile_number"];
		$this->mobile_number->AdvancedSearch->SearchCondition = @$filter["v_mobile_number"];
		$this->mobile_number->AdvancedSearch->SearchValue2 = @$filter["y_mobile_number"];
		$this->mobile_number->AdvancedSearch->SearchOperator2 = @$filter["w_mobile_number"];
		$this->mobile_number->AdvancedSearch->Save();

		// Field secondary_number
		$this->secondary_number->AdvancedSearch->SearchValue = @$filter["x_secondary_number"];
		$this->secondary_number->AdvancedSearch->SearchOperator = @$filter["z_secondary_number"];
		$this->secondary_number->AdvancedSearch->SearchCondition = @$filter["v_secondary_number"];
		$this->secondary_number->AdvancedSearch->SearchValue2 = @$filter["y_secondary_number"];
		$this->secondary_number->AdvancedSearch->SearchOperator2 = @$filter["w_secondary_number"];
		$this->secondary_number->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field name
		$this->name->AdvancedSearch->SearchValue = @$filter["x_name"];
		$this->name->AdvancedSearch->SearchOperator = @$filter["z_name"];
		$this->name->AdvancedSearch->SearchCondition = @$filter["v_name"];
		$this->name->AdvancedSearch->SearchValue2 = @$filter["y_name"];
		$this->name->AdvancedSearch->SearchOperator2 = @$filter["w_name"];
		$this->name->AdvancedSearch->Save();

		// Field address
		$this->address->AdvancedSearch->SearchValue = @$filter["x_address"];
		$this->address->AdvancedSearch->SearchOperator = @$filter["z_address"];
		$this->address->AdvancedSearch->SearchCondition = @$filter["v_address"];
		$this->address->AdvancedSearch->SearchValue2 = @$filter["y_address"];
		$this->address->AdvancedSearch->SearchOperator2 = @$filter["w_address"];
		$this->address->AdvancedSearch->Save();

		// Field allow_whatsapp
		$this->allow_whatsapp->AdvancedSearch->SearchValue = @$filter["x_allow_whatsapp"];
		$this->allow_whatsapp->AdvancedSearch->SearchOperator = @$filter["z_allow_whatsapp"];
		$this->allow_whatsapp->AdvancedSearch->SearchCondition = @$filter["v_allow_whatsapp"];
		$this->allow_whatsapp->AdvancedSearch->SearchValue2 = @$filter["y_allow_whatsapp"];
		$this->allow_whatsapp->AdvancedSearch->SearchOperator2 = @$filter["w_allow_whatsapp"];
		$this->allow_whatsapp->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->user_id, $Default, FALSE); // user_id
		$this->BuildSearchSql($sWhere, $this->ad_title, $Default, FALSE); // ad_title
		$this->BuildSearchSql($sWhere, $this->year_id, $Default, FALSE); // year_id
		$this->BuildSearchSql($sWhere, $this->registered_in, $Default, FALSE); // registered_in
		$this->BuildSearchSql($sWhere, $this->city_id, $Default, FALSE); // city_id
		$this->BuildSearchSql($sWhere, $this->make_id, $Default, FALSE); // make_id
		$this->BuildSearchSql($sWhere, $this->model_id, $Default, FALSE); // model_id
		$this->BuildSearchSql($sWhere, $this->version_id, $Default, FALSE); // version_id
		$this->BuildSearchSql($sWhere, $this->milage, $Default, FALSE); // milage
		$this->BuildSearchSql($sWhere, $this->color_id, $Default, FALSE); // color_id
		$this->BuildSearchSql($sWhere, $this->demand_price, $Default, FALSE); // demand_price
		$this->BuildSearchSql($sWhere, $this->details, $Default, FALSE); // details
		$this->BuildSearchSql($sWhere, $this->engine_type_id, $Default, FALSE); // engine_type_id
		$this->BuildSearchSql($sWhere, $this->engine_capicity, $Default, FALSE); // engine_capicity
		$this->BuildSearchSql($sWhere, $this->transmition, $Default, FALSE); // transmition
		$this->BuildSearchSql($sWhere, $this->assembly, $Default, FALSE); // assembly
		$this->BuildSearchSql($sWhere, $this->mobile_number, $Default, FALSE); // mobile_number
		$this->BuildSearchSql($sWhere, $this->secondary_number, $Default, FALSE); // secondary_number
		$this->BuildSearchSql($sWhere, $this->_email, $Default, FALSE); // email
		$this->BuildSearchSql($sWhere, $this->name, $Default, FALSE); // name
		$this->BuildSearchSql($sWhere, $this->address, $Default, FALSE); // address
		$this->BuildSearchSql($sWhere, $this->allow_whatsapp, $Default, FALSE); // allow_whatsapp
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->user_id->AdvancedSearch->Save(); // user_id
			$this->ad_title->AdvancedSearch->Save(); // ad_title
			$this->year_id->AdvancedSearch->Save(); // year_id
			$this->registered_in->AdvancedSearch->Save(); // registered_in
			$this->city_id->AdvancedSearch->Save(); // city_id
			$this->make_id->AdvancedSearch->Save(); // make_id
			$this->model_id->AdvancedSearch->Save(); // model_id
			$this->version_id->AdvancedSearch->Save(); // version_id
			$this->milage->AdvancedSearch->Save(); // milage
			$this->color_id->AdvancedSearch->Save(); // color_id
			$this->demand_price->AdvancedSearch->Save(); // demand_price
			$this->details->AdvancedSearch->Save(); // details
			$this->engine_type_id->AdvancedSearch->Save(); // engine_type_id
			$this->engine_capicity->AdvancedSearch->Save(); // engine_capicity
			$this->transmition->AdvancedSearch->Save(); // transmition
			$this->assembly->AdvancedSearch->Save(); // assembly
			$this->mobile_number->AdvancedSearch->Save(); // mobile_number
			$this->secondary_number->AdvancedSearch->Save(); // secondary_number
			$this->_email->AdvancedSearch->Save(); // email
			$this->name->AdvancedSearch->Save(); // name
			$this->address->AdvancedSearch->Save(); // address
			$this->allow_whatsapp->AdvancedSearch->Save(); // allow_whatsapp
			$this->status->AdvancedSearch->Save(); // status
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->user_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ad_title->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->year_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->registered_in->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->city_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->make_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->model_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->version_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->milage->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->color_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->demand_price->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->details->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->engine_type_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->engine_capicity->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->transmition->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->assembly->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mobile_number->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->secondary_number->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->allow_whatsapp->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->user_id->AdvancedSearch->UnsetSession();
		$this->ad_title->AdvancedSearch->UnsetSession();
		$this->year_id->AdvancedSearch->UnsetSession();
		$this->registered_in->AdvancedSearch->UnsetSession();
		$this->city_id->AdvancedSearch->UnsetSession();
		$this->make_id->AdvancedSearch->UnsetSession();
		$this->model_id->AdvancedSearch->UnsetSession();
		$this->version_id->AdvancedSearch->UnsetSession();
		$this->milage->AdvancedSearch->UnsetSession();
		$this->color_id->AdvancedSearch->UnsetSession();
		$this->demand_price->AdvancedSearch->UnsetSession();
		$this->details->AdvancedSearch->UnsetSession();
		$this->engine_type_id->AdvancedSearch->UnsetSession();
		$this->engine_capicity->AdvancedSearch->UnsetSession();
		$this->transmition->AdvancedSearch->UnsetSession();
		$this->assembly->AdvancedSearch->UnsetSession();
		$this->mobile_number->AdvancedSearch->UnsetSession();
		$this->secondary_number->AdvancedSearch->UnsetSession();
		$this->_email->AdvancedSearch->UnsetSession();
		$this->name->AdvancedSearch->UnsetSession();
		$this->address->AdvancedSearch->UnsetSession();
		$this->allow_whatsapp->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->user_id->AdvancedSearch->Load();
		$this->ad_title->AdvancedSearch->Load();
		$this->year_id->AdvancedSearch->Load();
		$this->registered_in->AdvancedSearch->Load();
		$this->city_id->AdvancedSearch->Load();
		$this->make_id->AdvancedSearch->Load();
		$this->model_id->AdvancedSearch->Load();
		$this->version_id->AdvancedSearch->Load();
		$this->milage->AdvancedSearch->Load();
		$this->color_id->AdvancedSearch->Load();
		$this->demand_price->AdvancedSearch->Load();
		$this->details->AdvancedSearch->Load();
		$this->engine_type_id->AdvancedSearch->Load();
		$this->engine_capicity->AdvancedSearch->Load();
		$this->transmition->AdvancedSearch->Load();
		$this->assembly->AdvancedSearch->Load();
		$this->mobile_number->AdvancedSearch->Load();
		$this->secondary_number->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->name->AdvancedSearch->Load();
		$this->address->AdvancedSearch->Load();
		$this->allow_whatsapp->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->user_id); // user_id
			$this->UpdateSort($this->ad_title); // ad_title
			$this->UpdateSort($this->year_id); // year_id
			$this->UpdateSort($this->registered_in); // registered_in
			$this->UpdateSort($this->city_id); // city_id
			$this->UpdateSort($this->make_id); // make_id
			$this->UpdateSort($this->model_id); // model_id
			$this->UpdateSort($this->demand_price); // demand_price
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->address); // address
			$this->UpdateSort($this->allow_whatsapp); // allow_whatsapp
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->user_id->setSort("");
				$this->ad_title->setSort("");
				$this->year_id->setSort("");
				$this->registered_in->setSort("");
				$this->city_id->setSort("");
				$this->make_id->setSort("");
				$this->model_id->setSort("");
				$this->demand_price->setSort("");
				$this->_email->setSort("");
				$this->name->setSort("");
				$this->address->setSort("");
				$this->allow_whatsapp->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "detail_ad_features"
		$item = &$this->ListOptions->Add("detail_ad_features");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'ad_features') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["ad_features_grid"])) $GLOBALS["ad_features_grid"] = new cad_features_grid;

		// "detail_ad_pictures"
		$item = &$this->ListOptions->Add("detail_ad_pictures");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'ad_pictures') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["ad_pictures_grid"])) $GLOBALS["ad_pictures_grid"] = new cad_pictures_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = FALSE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("ad_features");
		$pages->Add("ad_pictures");
		$this->DetailPages = $pages;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// "sequence"
		$item = &$this->ListOptions->Add("sequence");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE; // Always on left
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_ad_features"
		$oListOpt = &$this->ListOptions->Items["detail_ad_features"];
		if ($Security->AllowList(CurrentProjectID() . 'ad_features')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("ad_features", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("ad_featureslist.php?" . EW_TABLE_SHOW_MASTER . "=car_ads&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["ad_features_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'ad_features')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=ad_features")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "ad_features";
			}
			if ($GLOBALS["ad_features_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'ad_features')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=ad_features")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "ad_features";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_ad_pictures"
		$oListOpt = &$this->ListOptions->Items["detail_ad_pictures"];
		if ($Security->AllowList(CurrentProjectID() . 'ad_pictures')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("ad_pictures", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("ad_pictureslist.php?" . EW_TABLE_SHOW_MASTER . "=car_ads&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["ad_pictures_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'ad_pictures')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=ad_pictures")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "ad_pictures";
			}
			if ($GLOBALS["ad_pictures_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'ad_pictures')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=ad_pictures")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "ad_pictures";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->ID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_ad_features");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=ad_features");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["ad_features"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["ad_features"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'ad_features') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "ad_features";
		}
		$item = &$option->Add("detailadd_ad_pictures");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=ad_pictures");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["ad_pictures"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["ad_pictures"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'ad_pictures') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "ad_pictures";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink);
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fcar_adslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fcar_adslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fcar_adslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"car_adssrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// user_id

		$this->user_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_user_id"]);
		if ($this->user_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->user_id->AdvancedSearch->SearchOperator = @$_GET["z_user_id"];

		// ad_title
		$this->ad_title->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ad_title"]);
		if ($this->ad_title->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ad_title->AdvancedSearch->SearchOperator = @$_GET["z_ad_title"];

		// year_id
		$this->year_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_year_id"]);
		if ($this->year_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->year_id->AdvancedSearch->SearchOperator = @$_GET["z_year_id"];

		// registered_in
		$this->registered_in->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_registered_in"]);
		if ($this->registered_in->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->registered_in->AdvancedSearch->SearchOperator = @$_GET["z_registered_in"];

		// city_id
		$this->city_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_city_id"]);
		if ($this->city_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->city_id->AdvancedSearch->SearchOperator = @$_GET["z_city_id"];

		// make_id
		$this->make_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_make_id"]);
		if ($this->make_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->make_id->AdvancedSearch->SearchOperator = @$_GET["z_make_id"];

		// model_id
		$this->model_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_model_id"]);
		if ($this->model_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->model_id->AdvancedSearch->SearchOperator = @$_GET["z_model_id"];

		// version_id
		$this->version_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_version_id"]);
		if ($this->version_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->version_id->AdvancedSearch->SearchOperator = @$_GET["z_version_id"];

		// milage
		$this->milage->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_milage"]);
		if ($this->milage->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->milage->AdvancedSearch->SearchOperator = @$_GET["z_milage"];

		// color_id
		$this->color_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_color_id"]);
		if ($this->color_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->color_id->AdvancedSearch->SearchOperator = @$_GET["z_color_id"];

		// demand_price
		$this->demand_price->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_demand_price"]);
		if ($this->demand_price->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->demand_price->AdvancedSearch->SearchOperator = @$_GET["z_demand_price"];

		// details
		$this->details->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_details"]);
		if ($this->details->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->details->AdvancedSearch->SearchOperator = @$_GET["z_details"];

		// engine_type_id
		$this->engine_type_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_engine_type_id"]);
		if ($this->engine_type_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->engine_type_id->AdvancedSearch->SearchOperator = @$_GET["z_engine_type_id"];

		// engine_capicity
		$this->engine_capicity->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_engine_capicity"]);
		if ($this->engine_capicity->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->engine_capicity->AdvancedSearch->SearchOperator = @$_GET["z_engine_capicity"];

		// transmition
		$this->transmition->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_transmition"]);
		if ($this->transmition->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->transmition->AdvancedSearch->SearchOperator = @$_GET["z_transmition"];

		// assembly
		$this->assembly->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_assembly"]);
		if ($this->assembly->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->assembly->AdvancedSearch->SearchOperator = @$_GET["z_assembly"];

		// mobile_number
		$this->mobile_number->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_mobile_number"]);
		if ($this->mobile_number->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->mobile_number->AdvancedSearch->SearchOperator = @$_GET["z_mobile_number"];

		// secondary_number
		$this->secondary_number->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_secondary_number"]);
		if ($this->secondary_number->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->secondary_number->AdvancedSearch->SearchOperator = @$_GET["z_secondary_number"];

		// email
		$this->_email->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__email"]);
		if ($this->_email->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_email->AdvancedSearch->SearchOperator = @$_GET["z__email"];

		// name
		$this->name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_name"]);
		if ($this->name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->name->AdvancedSearch->SearchOperator = @$_GET["z_name"];

		// address
		$this->address->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_address"]);
		if ($this->address->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->address->AdvancedSearch->SearchOperator = @$_GET["z_address"];

		// allow_whatsapp
		$this->allow_whatsapp->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_allow_whatsapp"]);
		if ($this->allow_whatsapp->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->allow_whatsapp->AdvancedSearch->SearchOperator = @$_GET["z_allow_whatsapp"];

		// status
		$this->status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_status"]);
		if ($this->status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// ID

		$this->ID->CellCssStyle = "white-space: nowrap;";

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

		$this->created_at->CellCssStyle = "white-space: nowrap;";

		// updated_at
		$this->updated_at->CellCssStyle = "white-space: nowrap;";
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

		// ad_title
		$this->ad_title->ViewValue = $this->ad_title->CurrentValue;
		$this->ad_title->ViewCustomAttributes = "";

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

			// ad_title
			$this->ad_title->LinkCustomAttributes = "";
			$this->ad_title->HrefValue = "";
			$this->ad_title->TooltipValue = "";

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

			// demand_price
			$this->demand_price->LinkCustomAttributes = "";
			$this->demand_price->HrefValue = "";
			$this->demand_price->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->user_id->AdvancedSearch->Load();
		$this->ad_title->AdvancedSearch->Load();
		$this->year_id->AdvancedSearch->Load();
		$this->registered_in->AdvancedSearch->Load();
		$this->city_id->AdvancedSearch->Load();
		$this->make_id->AdvancedSearch->Load();
		$this->model_id->AdvancedSearch->Load();
		$this->version_id->AdvancedSearch->Load();
		$this->milage->AdvancedSearch->Load();
		$this->color_id->AdvancedSearch->Load();
		$this->demand_price->AdvancedSearch->Load();
		$this->details->AdvancedSearch->Load();
		$this->engine_type_id->AdvancedSearch->Load();
		$this->engine_capicity->AdvancedSearch->Load();
		$this->transmition->AdvancedSearch->Load();
		$this->assembly->AdvancedSearch->Load();
		$this->mobile_number->AdvancedSearch->Load();
		$this->secondary_number->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->name->AdvancedSearch->Load();
		$this->address->AdvancedSearch->Load();
		$this->allow_whatsapp->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

		$this->ListOptions->Items["view"]->Visible = FALSE; 
		$this->ListOptions->Items["edit"]->Visible = FALSE;
	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($car_ads_list)) $car_ads_list = new ccar_ads_list();

// Page init
$car_ads_list->Page_Init();

// Page main
$car_ads_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$car_ads_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fcar_adslist = new ew_Form("fcar_adslist", "list");
fcar_adslist.FormKeyCountName = '<?php echo $car_ads_list->FormKeyCountName ?>';

// Form_CustomValidate event
fcar_adslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcar_adslist.ValidateRequired = true;
<?php } else { ?>
fcar_adslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcar_adslist.Lists["x_user_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","x__email","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adslist.Lists["x_year_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_year","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adslist.Lists["x_registered_in"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adslist.Lists["x_city_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adslist.Lists["x_make_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adslist.Lists["x_model_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adslist.Lists["x_allow_whatsapp"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adslist.Lists["x_allow_whatsapp"].Options = <?php echo json_encode($car_ads->allow_whatsapp->Options()) ?>;

// Form object for search
var CurrentSearchForm = fcar_adslistsrch = new ew_Form("fcar_adslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
$(document).ready(function ()
{
	$(".ewListOtherOptions .ewAddEditOption ").hide()
})
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($car_ads_list->TotalRecs > 0 && $car_ads_list->ExportOptions->Visible()) { ?>
<?php $car_ads_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($car_ads_list->SearchOptions->Visible()) { ?>
<?php $car_ads_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($car_ads_list->FilterOptions->Visible()) { ?>
<?php $car_ads_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $car_ads_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($car_ads_list->TotalRecs <= 0)
			$car_ads_list->TotalRecs = $car_ads->SelectRecordCount();
	} else {
		if (!$car_ads_list->Recordset && ($car_ads_list->Recordset = $car_ads_list->LoadRecordset()))
			$car_ads_list->TotalRecs = $car_ads_list->Recordset->RecordCount();
	}
	$car_ads_list->StartRec = 1;
	if ($car_ads_list->DisplayRecs <= 0 || ($car_ads->Export <> "" && $car_ads->ExportAll)) // Display all records
		$car_ads_list->DisplayRecs = $car_ads_list->TotalRecs;
	if (!($car_ads->Export <> "" && $car_ads->ExportAll))
		$car_ads_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$car_ads_list->Recordset = $car_ads_list->LoadRecordset($car_ads_list->StartRec-1, $car_ads_list->DisplayRecs);

	// Set no record found message
	if ($car_ads->CurrentAction == "" && $car_ads_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$car_ads_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($car_ads_list->SearchWhere == "0=101")
			$car_ads_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$car_ads_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$car_ads_list->RenderOtherOptions();
?>
<?php $car_ads_list->ShowPageHeader(); ?>
<?php
$car_ads_list->ShowMessage();
?>
<?php if ($car_ads_list->TotalRecs > 0 || $car_ads->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fcar_adslist" id="fcar_adslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($car_ads_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $car_ads_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="car_ads">
<div id="gmp_car_ads" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($car_ads_list->TotalRecs > 0) { ?>
<table id="tbl_car_adslist" class="table ewTable">
<?php echo $car_ads->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$car_ads_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$car_ads_list->RenderListOptions();

// Render list options (header, left)
$car_ads_list->ListOptions->Render("header", "left");
?>
<?php if ($car_ads->user_id->Visible) { // user_id ?>
	<?php if ($car_ads->SortUrl($car_ads->user_id) == "") { ?>
		<th data-name="user_id"><div id="elh_car_ads_user_id" class="car_ads_user_id"><div class="ewTableHeaderCaption"><?php echo $car_ads->user_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->user_id) ?>',1);"><div id="elh_car_ads_user_id" class="car_ads_user_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->user_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->user_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->user_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->ad_title->Visible) { // ad_title ?>
	<?php if ($car_ads->SortUrl($car_ads->ad_title) == "") { ?>
		<th data-name="ad_title"><div id="elh_car_ads_ad_title" class="car_ads_ad_title"><div class="ewTableHeaderCaption"><?php echo $car_ads->ad_title->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ad_title"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->ad_title) ?>',1);"><div id="elh_car_ads_ad_title" class="car_ads_ad_title">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->ad_title->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->ad_title->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->ad_title->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->year_id->Visible) { // year_id ?>
	<?php if ($car_ads->SortUrl($car_ads->year_id) == "") { ?>
		<th data-name="year_id"><div id="elh_car_ads_year_id" class="car_ads_year_id"><div class="ewTableHeaderCaption"><?php echo $car_ads->year_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="year_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->year_id) ?>',1);"><div id="elh_car_ads_year_id" class="car_ads_year_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->year_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->year_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->year_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->registered_in->Visible) { // registered_in ?>
	<?php if ($car_ads->SortUrl($car_ads->registered_in) == "") { ?>
		<th data-name="registered_in"><div id="elh_car_ads_registered_in" class="car_ads_registered_in"><div class="ewTableHeaderCaption"><?php echo $car_ads->registered_in->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="registered_in"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->registered_in) ?>',1);"><div id="elh_car_ads_registered_in" class="car_ads_registered_in">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->registered_in->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->registered_in->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->registered_in->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->city_id->Visible) { // city_id ?>
	<?php if ($car_ads->SortUrl($car_ads->city_id) == "") { ?>
		<th data-name="city_id"><div id="elh_car_ads_city_id" class="car_ads_city_id"><div class="ewTableHeaderCaption"><?php echo $car_ads->city_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="city_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->city_id) ?>',1);"><div id="elh_car_ads_city_id" class="car_ads_city_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->city_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->city_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->city_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->make_id->Visible) { // make_id ?>
	<?php if ($car_ads->SortUrl($car_ads->make_id) == "") { ?>
		<th data-name="make_id"><div id="elh_car_ads_make_id" class="car_ads_make_id"><div class="ewTableHeaderCaption"><?php echo $car_ads->make_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="make_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->make_id) ?>',1);"><div id="elh_car_ads_make_id" class="car_ads_make_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->make_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->make_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->make_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->model_id->Visible) { // model_id ?>
	<?php if ($car_ads->SortUrl($car_ads->model_id) == "") { ?>
		<th data-name="model_id"><div id="elh_car_ads_model_id" class="car_ads_model_id"><div class="ewTableHeaderCaption"><?php echo $car_ads->model_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="model_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->model_id) ?>',1);"><div id="elh_car_ads_model_id" class="car_ads_model_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->model_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->model_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->model_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->demand_price->Visible) { // demand_price ?>
	<?php if ($car_ads->SortUrl($car_ads->demand_price) == "") { ?>
		<th data-name="demand_price"><div id="elh_car_ads_demand_price" class="car_ads_demand_price"><div class="ewTableHeaderCaption"><?php echo $car_ads->demand_price->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="demand_price"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->demand_price) ?>',1);"><div id="elh_car_ads_demand_price" class="car_ads_demand_price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->demand_price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->demand_price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->demand_price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->_email->Visible) { // email ?>
	<?php if ($car_ads->SortUrl($car_ads->_email) == "") { ?>
		<th data-name="_email"><div id="elh_car_ads__email" class="car_ads__email"><div class="ewTableHeaderCaption"><?php echo $car_ads->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->_email) ?>',1);"><div id="elh_car_ads__email" class="car_ads__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->_email->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->name->Visible) { // name ?>
	<?php if ($car_ads->SortUrl($car_ads->name) == "") { ?>
		<th data-name="name"><div id="elh_car_ads_name" class="car_ads_name"><div class="ewTableHeaderCaption"><?php echo $car_ads->name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->name) ?>',1);"><div id="elh_car_ads_name" class="car_ads_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->address->Visible) { // address ?>
	<?php if ($car_ads->SortUrl($car_ads->address) == "") { ?>
		<th data-name="address"><div id="elh_car_ads_address" class="car_ads_address"><div class="ewTableHeaderCaption"><?php echo $car_ads->address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="address"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->address) ?>',1);"><div id="elh_car_ads_address" class="car_ads_address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->address->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($car_ads->allow_whatsapp->Visible) { // allow_whatsapp ?>
	<?php if ($car_ads->SortUrl($car_ads->allow_whatsapp) == "") { ?>
		<th data-name="allow_whatsapp"><div id="elh_car_ads_allow_whatsapp" class="car_ads_allow_whatsapp"><div class="ewTableHeaderCaption"><?php echo $car_ads->allow_whatsapp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="allow_whatsapp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $car_ads->SortUrl($car_ads->allow_whatsapp) ?>',1);"><div id="elh_car_ads_allow_whatsapp" class="car_ads_allow_whatsapp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $car_ads->allow_whatsapp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($car_ads->allow_whatsapp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($car_ads->allow_whatsapp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$car_ads_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($car_ads->ExportAll && $car_ads->Export <> "") {
	$car_ads_list->StopRec = $car_ads_list->TotalRecs;
} else {

	// Set the last record to display
	if ($car_ads_list->TotalRecs > $car_ads_list->StartRec + $car_ads_list->DisplayRecs - 1)
		$car_ads_list->StopRec = $car_ads_list->StartRec + $car_ads_list->DisplayRecs - 1;
	else
		$car_ads_list->StopRec = $car_ads_list->TotalRecs;
}
$car_ads_list->RecCnt = $car_ads_list->StartRec - 1;
if ($car_ads_list->Recordset && !$car_ads_list->Recordset->EOF) {
	$car_ads_list->Recordset->MoveFirst();
	$bSelectLimit = $car_ads_list->UseSelectLimit;
	if (!$bSelectLimit && $car_ads_list->StartRec > 1)
		$car_ads_list->Recordset->Move($car_ads_list->StartRec - 1);
} elseif (!$car_ads->AllowAddDeleteRow && $car_ads_list->StopRec == 0) {
	$car_ads_list->StopRec = $car_ads->GridAddRowCount;
}

// Initialize aggregate
$car_ads->RowType = EW_ROWTYPE_AGGREGATEINIT;
$car_ads->ResetAttrs();
$car_ads_list->RenderRow();
while ($car_ads_list->RecCnt < $car_ads_list->StopRec) {
	$car_ads_list->RecCnt++;
	if (intval($car_ads_list->RecCnt) >= intval($car_ads_list->StartRec)) {
		$car_ads_list->RowCnt++;

		// Set up key count
		$car_ads_list->KeyCount = $car_ads_list->RowIndex;

		// Init row class and style
		$car_ads->ResetAttrs();
		$car_ads->CssClass = "";
		if ($car_ads->CurrentAction == "gridadd") {
		} else {
			$car_ads_list->LoadRowValues($car_ads_list->Recordset); // Load row values
		}
		$car_ads->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$car_ads->RowAttrs = array_merge($car_ads->RowAttrs, array('data-rowindex'=>$car_ads_list->RowCnt, 'id'=>'r' . $car_ads_list->RowCnt . '_car_ads', 'data-rowtype'=>$car_ads->RowType));

		// Render row
		$car_ads_list->RenderRow();

		// Render list options
		$car_ads_list->RenderListOptions();
?>
	<tr<?php echo $car_ads->RowAttributes() ?>>
<?php

// Render list options (body, left)
$car_ads_list->ListOptions->Render("body", "left", $car_ads_list->RowCnt);
?>
	<?php if ($car_ads->user_id->Visible) { // user_id ?>
		<td data-name="user_id"<?php echo $car_ads->user_id->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_user_id" class="car_ads_user_id">
<span<?php echo $car_ads->user_id->ViewAttributes() ?>>
<?php echo $car_ads->user_id->ListViewValue() ?></span>
</span>
<a id="<?php echo $car_ads_list->PageObjName . "_row_" . $car_ads_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($car_ads->ad_title->Visible) { // ad_title ?>
		<td data-name="ad_title"<?php echo $car_ads->ad_title->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_ad_title" class="car_ads_ad_title">
<span<?php echo $car_ads->ad_title->ViewAttributes() ?>>
<?php echo $car_ads->ad_title->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($car_ads->year_id->Visible) { // year_id ?>
		<td data-name="year_id"<?php echo $car_ads->year_id->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_year_id" class="car_ads_year_id">
<span<?php echo $car_ads->year_id->ViewAttributes() ?>>
<?php echo $car_ads->year_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($car_ads->registered_in->Visible) { // registered_in ?>
		<td data-name="registered_in"<?php echo $car_ads->registered_in->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_registered_in" class="car_ads_registered_in">
<span<?php echo $car_ads->registered_in->ViewAttributes() ?>>
<?php echo $car_ads->registered_in->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($car_ads->city_id->Visible) { // city_id ?>
		<td data-name="city_id"<?php echo $car_ads->city_id->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_city_id" class="car_ads_city_id">
<span<?php echo $car_ads->city_id->ViewAttributes() ?>>
<?php echo $car_ads->city_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($car_ads->make_id->Visible) { // make_id ?>
		<td data-name="make_id"<?php echo $car_ads->make_id->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_make_id" class="car_ads_make_id">
<span<?php echo $car_ads->make_id->ViewAttributes() ?>>
<?php echo $car_ads->make_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($car_ads->model_id->Visible) { // model_id ?>
		<td data-name="model_id"<?php echo $car_ads->model_id->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_model_id" class="car_ads_model_id">
<span<?php echo $car_ads->model_id->ViewAttributes() ?>>
<?php echo $car_ads->model_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($car_ads->demand_price->Visible) { // demand_price ?>
		<td data-name="demand_price"<?php echo $car_ads->demand_price->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_demand_price" class="car_ads_demand_price">
<span<?php echo $car_ads->demand_price->ViewAttributes() ?>>
<?php echo $car_ads->demand_price->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($car_ads->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $car_ads->_email->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads__email" class="car_ads__email">
<span<?php echo $car_ads->_email->ViewAttributes() ?>>
<?php echo $car_ads->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($car_ads->name->Visible) { // name ?>
		<td data-name="name"<?php echo $car_ads->name->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_name" class="car_ads_name">
<span<?php echo $car_ads->name->ViewAttributes() ?>>
<?php echo $car_ads->name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($car_ads->address->Visible) { // address ?>
		<td data-name="address"<?php echo $car_ads->address->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_address" class="car_ads_address">
<span<?php echo $car_ads->address->ViewAttributes() ?>>
<?php echo $car_ads->address->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($car_ads->allow_whatsapp->Visible) { // allow_whatsapp ?>
		<td data-name="allow_whatsapp"<?php echo $car_ads->allow_whatsapp->CellAttributes() ?>>
<span id="el<?php echo $car_ads_list->RowCnt ?>_car_ads_allow_whatsapp" class="car_ads_allow_whatsapp">
<span<?php echo $car_ads->allow_whatsapp->ViewAttributes() ?>>
<?php echo $car_ads->allow_whatsapp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$car_ads_list->ListOptions->Render("body", "right", $car_ads_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($car_ads->CurrentAction <> "gridadd")
		$car_ads_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($car_ads->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($car_ads_list->Recordset)
	$car_ads_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($car_ads->CurrentAction <> "gridadd" && $car_ads->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($car_ads_list->Pager)) $car_ads_list->Pager = new cPrevNextPager($car_ads_list->StartRec, $car_ads_list->DisplayRecs, $car_ads_list->TotalRecs) ?>
<?php if ($car_ads_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($car_ads_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $car_ads_list->PageUrl() ?>start=<?php echo $car_ads_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($car_ads_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $car_ads_list->PageUrl() ?>start=<?php echo $car_ads_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $car_ads_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($car_ads_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $car_ads_list->PageUrl() ?>start=<?php echo $car_ads_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($car_ads_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $car_ads_list->PageUrl() ?>start=<?php echo $car_ads_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $car_ads_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $car_ads_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $car_ads_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $car_ads_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($car_ads_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($car_ads_list->TotalRecs == 0 && $car_ads->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($car_ads_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fcar_adslistsrch.Init();
fcar_adslistsrch.FilterList = <?php echo $car_ads_list->GetFilterList() ?>;
fcar_adslist.Init();
</script>
<?php
$car_ads_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$car_ads_list->Page_Terminate();
?>
