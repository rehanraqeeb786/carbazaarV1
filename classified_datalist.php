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

$classified_data_list = NULL; // Initialize page object first

class cclassified_data_list extends cclassified_data {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'classified_data';

	// Page object name
	var $PageObjName = 'classified_data_list';

	// Grid form hidden field names
	var $FormName = 'fclassified_datalist';
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

		// Table object (classified_data)
		if (!isset($GLOBALS["classified_data"]) || get_class($GLOBALS["classified_data"]) == "cclassified_data") {
			$GLOBALS["classified_data"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["classified_data"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "classified_dataadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "classified_datadelete.php";
		$this->MultiUpdateUrl = "classified_dataupdate.php";

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fclassified_datalistsrch";

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
		$sFilterList = ew_Concat($sFilterList, $this->title->AdvancedSearch->ToJSON(), ","); // Field title
		$sFilterList = ew_Concat($sFilterList, $this->car_type->AdvancedSearch->ToJSON(), ","); // Field car_type
		$sFilterList = ew_Concat($sFilterList, $this->car_make_company_id->AdvancedSearch->ToJSON(), ","); // Field car_make_company_id
		$sFilterList = ew_Concat($sFilterList, $this->car_model_id->AdvancedSearch->ToJSON(), ","); // Field car_model_id
		$sFilterList = ew_Concat($sFilterList, $this->price_range->AdvancedSearch->ToJSON(), ","); // Field price_range
		$sFilterList = ew_Concat($sFilterList, $this->transmition->AdvancedSearch->ToJSON(), ","); // Field transmition
		$sFilterList = ew_Concat($sFilterList, $this->fuel_type->AdvancedSearch->ToJSON(), ","); // Field fuel_type
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

		// Field title
		$this->title->AdvancedSearch->SearchValue = @$filter["x_title"];
		$this->title->AdvancedSearch->SearchOperator = @$filter["z_title"];
		$this->title->AdvancedSearch->SearchCondition = @$filter["v_title"];
		$this->title->AdvancedSearch->SearchValue2 = @$filter["y_title"];
		$this->title->AdvancedSearch->SearchOperator2 = @$filter["w_title"];
		$this->title->AdvancedSearch->Save();

		// Field car_type
		$this->car_type->AdvancedSearch->SearchValue = @$filter["x_car_type"];
		$this->car_type->AdvancedSearch->SearchOperator = @$filter["z_car_type"];
		$this->car_type->AdvancedSearch->SearchCondition = @$filter["v_car_type"];
		$this->car_type->AdvancedSearch->SearchValue2 = @$filter["y_car_type"];
		$this->car_type->AdvancedSearch->SearchOperator2 = @$filter["w_car_type"];
		$this->car_type->AdvancedSearch->Save();

		// Field car_make_company_id
		$this->car_make_company_id->AdvancedSearch->SearchValue = @$filter["x_car_make_company_id"];
		$this->car_make_company_id->AdvancedSearch->SearchOperator = @$filter["z_car_make_company_id"];
		$this->car_make_company_id->AdvancedSearch->SearchCondition = @$filter["v_car_make_company_id"];
		$this->car_make_company_id->AdvancedSearch->SearchValue2 = @$filter["y_car_make_company_id"];
		$this->car_make_company_id->AdvancedSearch->SearchOperator2 = @$filter["w_car_make_company_id"];
		$this->car_make_company_id->AdvancedSearch->Save();

		// Field car_model_id
		$this->car_model_id->AdvancedSearch->SearchValue = @$filter["x_car_model_id"];
		$this->car_model_id->AdvancedSearch->SearchOperator = @$filter["z_car_model_id"];
		$this->car_model_id->AdvancedSearch->SearchCondition = @$filter["v_car_model_id"];
		$this->car_model_id->AdvancedSearch->SearchValue2 = @$filter["y_car_model_id"];
		$this->car_model_id->AdvancedSearch->SearchOperator2 = @$filter["w_car_model_id"];
		$this->car_model_id->AdvancedSearch->Save();

		// Field price_range
		$this->price_range->AdvancedSearch->SearchValue = @$filter["x_price_range"];
		$this->price_range->AdvancedSearch->SearchOperator = @$filter["z_price_range"];
		$this->price_range->AdvancedSearch->SearchCondition = @$filter["v_price_range"];
		$this->price_range->AdvancedSearch->SearchValue2 = @$filter["y_price_range"];
		$this->price_range->AdvancedSearch->SearchOperator2 = @$filter["w_price_range"];
		$this->price_range->AdvancedSearch->Save();

		// Field transmition
		$this->transmition->AdvancedSearch->SearchValue = @$filter["x_transmition"];
		$this->transmition->AdvancedSearch->SearchOperator = @$filter["z_transmition"];
		$this->transmition->AdvancedSearch->SearchCondition = @$filter["v_transmition"];
		$this->transmition->AdvancedSearch->SearchValue2 = @$filter["y_transmition"];
		$this->transmition->AdvancedSearch->SearchOperator2 = @$filter["w_transmition"];
		$this->transmition->AdvancedSearch->Save();

		// Field fuel_type
		$this->fuel_type->AdvancedSearch->SearchValue = @$filter["x_fuel_type"];
		$this->fuel_type->AdvancedSearch->SearchOperator = @$filter["z_fuel_type"];
		$this->fuel_type->AdvancedSearch->SearchCondition = @$filter["v_fuel_type"];
		$this->fuel_type->AdvancedSearch->SearchValue2 = @$filter["y_fuel_type"];
		$this->fuel_type->AdvancedSearch->SearchOperator2 = @$filter["w_fuel_type"];
		$this->fuel_type->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->title, $Default, FALSE); // title
		$this->BuildSearchSql($sWhere, $this->car_type, $Default, FALSE); // car_type
		$this->BuildSearchSql($sWhere, $this->car_make_company_id, $Default, FALSE); // car_make_company_id
		$this->BuildSearchSql($sWhere, $this->car_model_id, $Default, FALSE); // car_model_id
		$this->BuildSearchSql($sWhere, $this->price_range, $Default, FALSE); // price_range
		$this->BuildSearchSql($sWhere, $this->transmition, $Default, FALSE); // transmition
		$this->BuildSearchSql($sWhere, $this->fuel_type, $Default, FALSE); // fuel_type
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->title->AdvancedSearch->Save(); // title
			$this->car_type->AdvancedSearch->Save(); // car_type
			$this->car_make_company_id->AdvancedSearch->Save(); // car_make_company_id
			$this->car_model_id->AdvancedSearch->Save(); // car_model_id
			$this->price_range->AdvancedSearch->Save(); // price_range
			$this->transmition->AdvancedSearch->Save(); // transmition
			$this->fuel_type->AdvancedSearch->Save(); // fuel_type
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
		if ($this->title->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->car_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->car_make_company_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->car_model_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->price_range->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->transmition->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fuel_type->AdvancedSearch->IssetSession())
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
		$this->title->AdvancedSearch->UnsetSession();
		$this->car_type->AdvancedSearch->UnsetSession();
		$this->car_make_company_id->AdvancedSearch->UnsetSession();
		$this->car_model_id->AdvancedSearch->UnsetSession();
		$this->price_range->AdvancedSearch->UnsetSession();
		$this->transmition->AdvancedSearch->UnsetSession();
		$this->fuel_type->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->title->AdvancedSearch->Load();
		$this->car_type->AdvancedSearch->Load();
		$this->car_make_company_id->AdvancedSearch->Load();
		$this->car_model_id->AdvancedSearch->Load();
		$this->price_range->AdvancedSearch->Load();
		$this->transmition->AdvancedSearch->Load();
		$this->fuel_type->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->title); // title
			$this->UpdateSort($this->car_type); // car_type
			$this->UpdateSort($this->car_make_company_id); // car_make_company_id
			$this->UpdateSort($this->car_model_id); // car_model_id
			$this->UpdateSort($this->price_range); // price_range
			$this->UpdateSort($this->engine_capicity); // engine_capicity
			$this->UpdateSort($this->status); // status
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
				$this->title->setSort("");
				$this->car_type->setSort("");
				$this->car_make_company_id->setSort("");
				$this->car_model_id->setSort("");
				$this->price_range->setSort("");
				$this->engine_capicity->setSort("");
				$this->status->setSort("");
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

		// "detail_classified_colors"
		$item = &$this->ListOptions->Add("detail_classified_colors");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'classified_colors') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["classified_colors_grid"])) $GLOBALS["classified_colors_grid"] = new cclassified_colors_grid;

		// "detail_classified_attributes"
		$item = &$this->ListOptions->Add("detail_classified_attributes");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'classified_attributes') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["classified_attributes_grid"])) $GLOBALS["classified_attributes_grid"] = new cclassified_attributes_grid;

		// "detail_classified_faqs"
		$item = &$this->ListOptions->Add("detail_classified_faqs");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'classified_faqs') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["classified_faqs_grid"])) $GLOBALS["classified_faqs_grid"] = new cclassified_faqs_grid;

		// "detail_classified_pictures"
		$item = &$this->ListOptions->Add("detail_classified_pictures");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'classified_pictures') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["classified_pictures_grid"])) $GLOBALS["classified_pictures_grid"] = new cclassified_pictures_grid;

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
		$pages->Add("classified_colors");
		$pages->Add("classified_attributes");
		$pages->Add("classified_faqs");
		$pages->Add("classified_pictures");
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

		// "detail_classified_colors"
		$oListOpt = &$this->ListOptions->Items["detail_classified_colors"];
		if ($Security->AllowList(CurrentProjectID() . 'classified_colors')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("classified_colors", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("classified_colorslist.php?" . EW_TABLE_SHOW_MASTER . "=classified_data&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["classified_colors_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'classified_colors')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=classified_colors")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "classified_colors";
			}
			if ($GLOBALS["classified_colors_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'classified_colors')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=classified_colors")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "classified_colors";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_classified_attributes"
		$oListOpt = &$this->ListOptions->Items["detail_classified_attributes"];
		if ($Security->AllowList(CurrentProjectID() . 'classified_attributes')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("classified_attributes", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("classified_attributeslist.php?" . EW_TABLE_SHOW_MASTER . "=classified_data&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["classified_attributes_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'classified_attributes')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=classified_attributes")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "classified_attributes";
			}
			if ($GLOBALS["classified_attributes_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'classified_attributes')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=classified_attributes")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "classified_attributes";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_classified_faqs"
		$oListOpt = &$this->ListOptions->Items["detail_classified_faqs"];
		if ($Security->AllowList(CurrentProjectID() . 'classified_faqs')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("classified_faqs", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("classified_faqslist.php?" . EW_TABLE_SHOW_MASTER . "=classified_data&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["classified_faqs_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'classified_faqs')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=classified_faqs")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "classified_faqs";
			}
			if ($GLOBALS["classified_faqs_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'classified_faqs')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=classified_faqs")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "classified_faqs";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_classified_pictures"
		$oListOpt = &$this->ListOptions->Items["detail_classified_pictures"];
		if ($Security->AllowList(CurrentProjectID() . 'classified_pictures')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("classified_pictures", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("classified_pictureslist.php?" . EW_TABLE_SHOW_MASTER . "=classified_data&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["classified_pictures_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'classified_pictures')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=classified_pictures")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "classified_pictures";
			}
			if ($GLOBALS["classified_pictures_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'classified_pictures')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=classified_pictures")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "classified_pictures";
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
		$item = &$option->Add("detailadd_classified_colors");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=classified_colors");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["classified_colors"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["classified_colors"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'classified_colors') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "classified_colors";
		}
		$item = &$option->Add("detailadd_classified_attributes");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=classified_attributes");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["classified_attributes"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["classified_attributes"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'classified_attributes') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "classified_attributes";
		}
		$item = &$option->Add("detailadd_classified_faqs");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=classified_faqs");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["classified_faqs"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["classified_faqs"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'classified_faqs') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "classified_faqs";
		}
		$item = &$option->Add("detailadd_classified_pictures");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=classified_pictures");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["classified_pictures"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["classified_pictures"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'classified_pictures') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "classified_pictures";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fclassified_datalistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fclassified_datalistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fclassified_datalist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"classified_datasrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
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
		// title

		$this->title->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_title"]);
		if ($this->title->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->title->AdvancedSearch->SearchOperator = @$_GET["z_title"];

		// car_type
		$this->car_type->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_car_type"]);
		if ($this->car_type->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->car_type->AdvancedSearch->SearchOperator = @$_GET["z_car_type"];

		// car_make_company_id
		$this->car_make_company_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_car_make_company_id"]);
		if ($this->car_make_company_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->car_make_company_id->AdvancedSearch->SearchOperator = @$_GET["z_car_make_company_id"];

		// car_model_id
		$this->car_model_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_car_model_id"]);
		if ($this->car_model_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->car_model_id->AdvancedSearch->SearchOperator = @$_GET["z_car_model_id"];

		// price_range
		$this->price_range->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_price_range"]);
		if ($this->price_range->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->price_range->AdvancedSearch->SearchOperator = @$_GET["z_price_range"];

		// transmition
		$this->transmition->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_transmition"]);
		if ($this->transmition->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->transmition->AdvancedSearch->SearchOperator = @$_GET["z_transmition"];

		// fuel_type
		$this->fuel_type->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fuel_type"]);
		if ($this->fuel_type->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fuel_type->AdvancedSearch->SearchOperator = @$_GET["z_fuel_type"];

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

		$this->ETD->CellCssStyle = "white-space: nowrap;";
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

			// engine_capicity
			$this->engine_capicity->LinkCustomAttributes = "";
			$this->engine_capicity->HrefValue = "";
			$this->engine_capicity->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
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
		$this->title->AdvancedSearch->Load();
		$this->car_type->AdvancedSearch->Load();
		$this->car_make_company_id->AdvancedSearch->Load();
		$this->car_model_id->AdvancedSearch->Load();
		$this->price_range->AdvancedSearch->Load();
		$this->transmition->AdvancedSearch->Load();
		$this->fuel_type->AdvancedSearch->Load();
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
if (!isset($classified_data_list)) $classified_data_list = new cclassified_data_list();

// Page init
$classified_data_list->Page_Init();

// Page main
$classified_data_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$classified_data_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fclassified_datalist = new ew_Form("fclassified_datalist", "list");
fclassified_datalist.FormKeyCountName = '<?php echo $classified_data_list->FormKeyCountName ?>';

// Form_CustomValidate event
fclassified_datalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclassified_datalist.ValidateRequired = true;
<?php } else { ?>
fclassified_datalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclassified_datalist.Lists["x_car_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_datalist.Lists["x_car_type"].Options = <?php echo json_encode($classified_data->car_type->Options()) ?>;
fclassified_datalist.Lists["x_car_make_company_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":["x_car_model_id"],"FilterFields":[],"Options":[],"Template":""};
fclassified_datalist.Lists["x_car_model_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_datalist.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_datalist.Lists["x_status"].Options = <?php echo json_encode($classified_data->status->Options()) ?>;

// Form object for search
var CurrentSearchForm = fclassified_datalistsrch = new ew_Form("fclassified_datalistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
//hiding single page add button

$(document).ready(function ()
{
	$(".ewListOtherOptions .ewAddEditOption ").hide()
})
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($classified_data_list->TotalRecs > 0 && $classified_data_list->ExportOptions->Visible()) { ?>
<?php $classified_data_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($classified_data_list->SearchOptions->Visible()) { ?>
<?php $classified_data_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($classified_data_list->FilterOptions->Visible()) { ?>
<?php $classified_data_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $classified_data_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($classified_data_list->TotalRecs <= 0)
			$classified_data_list->TotalRecs = $classified_data->SelectRecordCount();
	} else {
		if (!$classified_data_list->Recordset && ($classified_data_list->Recordset = $classified_data_list->LoadRecordset()))
			$classified_data_list->TotalRecs = $classified_data_list->Recordset->RecordCount();
	}
	$classified_data_list->StartRec = 1;
	if ($classified_data_list->DisplayRecs <= 0 || ($classified_data->Export <> "" && $classified_data->ExportAll)) // Display all records
		$classified_data_list->DisplayRecs = $classified_data_list->TotalRecs;
	if (!($classified_data->Export <> "" && $classified_data->ExportAll))
		$classified_data_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$classified_data_list->Recordset = $classified_data_list->LoadRecordset($classified_data_list->StartRec-1, $classified_data_list->DisplayRecs);

	// Set no record found message
	if ($classified_data->CurrentAction == "" && $classified_data_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$classified_data_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($classified_data_list->SearchWhere == "0=101")
			$classified_data_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$classified_data_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$classified_data_list->RenderOtherOptions();
?>
<?php $classified_data_list->ShowPageHeader(); ?>
<?php
$classified_data_list->ShowMessage();
?>
<?php if ($classified_data_list->TotalRecs > 0 || $classified_data->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fclassified_datalist" id="fclassified_datalist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($classified_data_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $classified_data_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="classified_data">
<div id="gmp_classified_data" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($classified_data_list->TotalRecs > 0) { ?>
<table id="tbl_classified_datalist" class="table ewTable">
<?php echo $classified_data->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$classified_data_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$classified_data_list->RenderListOptions();

// Render list options (header, left)
$classified_data_list->ListOptions->Render("header", "left");
?>
<?php if ($classified_data->title->Visible) { // title ?>
	<?php if ($classified_data->SortUrl($classified_data->title) == "") { ?>
		<th data-name="title"><div id="elh_classified_data_title" class="classified_data_title"><div class="ewTableHeaderCaption"><?php echo $classified_data->title->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="title"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $classified_data->SortUrl($classified_data->title) ?>',1);"><div id="elh_classified_data_title" class="classified_data_title">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_data->title->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_data->title->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_data->title->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_data->car_type->Visible) { // car_type ?>
	<?php if ($classified_data->SortUrl($classified_data->car_type) == "") { ?>
		<th data-name="car_type"><div id="elh_classified_data_car_type" class="classified_data_car_type"><div class="ewTableHeaderCaption"><?php echo $classified_data->car_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="car_type"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $classified_data->SortUrl($classified_data->car_type) ?>',1);"><div id="elh_classified_data_car_type" class="classified_data_car_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_data->car_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_data->car_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_data->car_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_data->car_make_company_id->Visible) { // car_make_company_id ?>
	<?php if ($classified_data->SortUrl($classified_data->car_make_company_id) == "") { ?>
		<th data-name="car_make_company_id"><div id="elh_classified_data_car_make_company_id" class="classified_data_car_make_company_id"><div class="ewTableHeaderCaption"><?php echo $classified_data->car_make_company_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="car_make_company_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $classified_data->SortUrl($classified_data->car_make_company_id) ?>',1);"><div id="elh_classified_data_car_make_company_id" class="classified_data_car_make_company_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_data->car_make_company_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_data->car_make_company_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_data->car_make_company_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_data->car_model_id->Visible) { // car_model_id ?>
	<?php if ($classified_data->SortUrl($classified_data->car_model_id) == "") { ?>
		<th data-name="car_model_id"><div id="elh_classified_data_car_model_id" class="classified_data_car_model_id"><div class="ewTableHeaderCaption"><?php echo $classified_data->car_model_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="car_model_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $classified_data->SortUrl($classified_data->car_model_id) ?>',1);"><div id="elh_classified_data_car_model_id" class="classified_data_car_model_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_data->car_model_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_data->car_model_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_data->car_model_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_data->price_range->Visible) { // price_range ?>
	<?php if ($classified_data->SortUrl($classified_data->price_range) == "") { ?>
		<th data-name="price_range"><div id="elh_classified_data_price_range" class="classified_data_price_range"><div class="ewTableHeaderCaption"><?php echo $classified_data->price_range->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="price_range"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $classified_data->SortUrl($classified_data->price_range) ?>',1);"><div id="elh_classified_data_price_range" class="classified_data_price_range">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_data->price_range->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_data->price_range->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_data->price_range->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_data->engine_capicity->Visible) { // engine_capicity ?>
	<?php if ($classified_data->SortUrl($classified_data->engine_capicity) == "") { ?>
		<th data-name="engine_capicity"><div id="elh_classified_data_engine_capicity" class="classified_data_engine_capicity"><div class="ewTableHeaderCaption"><?php echo $classified_data->engine_capicity->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="engine_capicity"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $classified_data->SortUrl($classified_data->engine_capicity) ?>',1);"><div id="elh_classified_data_engine_capicity" class="classified_data_engine_capicity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_data->engine_capicity->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_data->engine_capicity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_data->engine_capicity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($classified_data->status->Visible) { // status ?>
	<?php if ($classified_data->SortUrl($classified_data->status) == "") { ?>
		<th data-name="status"><div id="elh_classified_data_status" class="classified_data_status"><div class="ewTableHeaderCaption"><?php echo $classified_data->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $classified_data->SortUrl($classified_data->status) ?>',1);"><div id="elh_classified_data_status" class="classified_data_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $classified_data->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($classified_data->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($classified_data->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$classified_data_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($classified_data->ExportAll && $classified_data->Export <> "") {
	$classified_data_list->StopRec = $classified_data_list->TotalRecs;
} else {

	// Set the last record to display
	if ($classified_data_list->TotalRecs > $classified_data_list->StartRec + $classified_data_list->DisplayRecs - 1)
		$classified_data_list->StopRec = $classified_data_list->StartRec + $classified_data_list->DisplayRecs - 1;
	else
		$classified_data_list->StopRec = $classified_data_list->TotalRecs;
}
$classified_data_list->RecCnt = $classified_data_list->StartRec - 1;
if ($classified_data_list->Recordset && !$classified_data_list->Recordset->EOF) {
	$classified_data_list->Recordset->MoveFirst();
	$bSelectLimit = $classified_data_list->UseSelectLimit;
	if (!$bSelectLimit && $classified_data_list->StartRec > 1)
		$classified_data_list->Recordset->Move($classified_data_list->StartRec - 1);
} elseif (!$classified_data->AllowAddDeleteRow && $classified_data_list->StopRec == 0) {
	$classified_data_list->StopRec = $classified_data->GridAddRowCount;
}

// Initialize aggregate
$classified_data->RowType = EW_ROWTYPE_AGGREGATEINIT;
$classified_data->ResetAttrs();
$classified_data_list->RenderRow();
while ($classified_data_list->RecCnt < $classified_data_list->StopRec) {
	$classified_data_list->RecCnt++;
	if (intval($classified_data_list->RecCnt) >= intval($classified_data_list->StartRec)) {
		$classified_data_list->RowCnt++;

		// Set up key count
		$classified_data_list->KeyCount = $classified_data_list->RowIndex;

		// Init row class and style
		$classified_data->ResetAttrs();
		$classified_data->CssClass = "";
		if ($classified_data->CurrentAction == "gridadd") {
		} else {
			$classified_data_list->LoadRowValues($classified_data_list->Recordset); // Load row values
		}
		$classified_data->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$classified_data->RowAttrs = array_merge($classified_data->RowAttrs, array('data-rowindex'=>$classified_data_list->RowCnt, 'id'=>'r' . $classified_data_list->RowCnt . '_classified_data', 'data-rowtype'=>$classified_data->RowType));

		// Render row
		$classified_data_list->RenderRow();

		// Render list options
		$classified_data_list->RenderListOptions();
?>
	<tr<?php echo $classified_data->RowAttributes() ?>>
<?php

// Render list options (body, left)
$classified_data_list->ListOptions->Render("body", "left", $classified_data_list->RowCnt);
?>
	<?php if ($classified_data->title->Visible) { // title ?>
		<td data-name="title"<?php echo $classified_data->title->CellAttributes() ?>>
<span id="el<?php echo $classified_data_list->RowCnt ?>_classified_data_title" class="classified_data_title">
<span<?php echo $classified_data->title->ViewAttributes() ?>>
<?php echo $classified_data->title->ListViewValue() ?></span>
</span>
<a id="<?php echo $classified_data_list->PageObjName . "_row_" . $classified_data_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($classified_data->car_type->Visible) { // car_type ?>
		<td data-name="car_type"<?php echo $classified_data->car_type->CellAttributes() ?>>
<span id="el<?php echo $classified_data_list->RowCnt ?>_classified_data_car_type" class="classified_data_car_type">
<span<?php echo $classified_data->car_type->ViewAttributes() ?>>
<?php echo $classified_data->car_type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($classified_data->car_make_company_id->Visible) { // car_make_company_id ?>
		<td data-name="car_make_company_id"<?php echo $classified_data->car_make_company_id->CellAttributes() ?>>
<span id="el<?php echo $classified_data_list->RowCnt ?>_classified_data_car_make_company_id" class="classified_data_car_make_company_id">
<span<?php echo $classified_data->car_make_company_id->ViewAttributes() ?>>
<?php echo $classified_data->car_make_company_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($classified_data->car_model_id->Visible) { // car_model_id ?>
		<td data-name="car_model_id"<?php echo $classified_data->car_model_id->CellAttributes() ?>>
<span id="el<?php echo $classified_data_list->RowCnt ?>_classified_data_car_model_id" class="classified_data_car_model_id">
<span<?php echo $classified_data->car_model_id->ViewAttributes() ?>>
<?php echo $classified_data->car_model_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($classified_data->price_range->Visible) { // price_range ?>
		<td data-name="price_range"<?php echo $classified_data->price_range->CellAttributes() ?>>
<span id="el<?php echo $classified_data_list->RowCnt ?>_classified_data_price_range" class="classified_data_price_range">
<span<?php echo $classified_data->price_range->ViewAttributes() ?>>
<?php echo $classified_data->price_range->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($classified_data->engine_capicity->Visible) { // engine_capicity ?>
		<td data-name="engine_capicity"<?php echo $classified_data->engine_capicity->CellAttributes() ?>>
<span id="el<?php echo $classified_data_list->RowCnt ?>_classified_data_engine_capicity" class="classified_data_engine_capicity">
<span<?php echo $classified_data->engine_capicity->ViewAttributes() ?>>
<?php echo $classified_data->engine_capicity->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($classified_data->status->Visible) { // status ?>
		<td data-name="status"<?php echo $classified_data->status->CellAttributes() ?>>
<span id="el<?php echo $classified_data_list->RowCnt ?>_classified_data_status" class="classified_data_status">
<span<?php echo $classified_data->status->ViewAttributes() ?>>
<?php echo $classified_data->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$classified_data_list->ListOptions->Render("body", "right", $classified_data_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($classified_data->CurrentAction <> "gridadd")
		$classified_data_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($classified_data->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($classified_data_list->Recordset)
	$classified_data_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($classified_data->CurrentAction <> "gridadd" && $classified_data->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($classified_data_list->Pager)) $classified_data_list->Pager = new cPrevNextPager($classified_data_list->StartRec, $classified_data_list->DisplayRecs, $classified_data_list->TotalRecs) ?>
<?php if ($classified_data_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($classified_data_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $classified_data_list->PageUrl() ?>start=<?php echo $classified_data_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($classified_data_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $classified_data_list->PageUrl() ?>start=<?php echo $classified_data_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $classified_data_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($classified_data_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $classified_data_list->PageUrl() ?>start=<?php echo $classified_data_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($classified_data_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $classified_data_list->PageUrl() ?>start=<?php echo $classified_data_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $classified_data_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $classified_data_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $classified_data_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $classified_data_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($classified_data_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($classified_data_list->TotalRecs == 0 && $classified_data->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($classified_data_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fclassified_datalistsrch.Init();
fclassified_datalistsrch.FilterList = <?php echo $classified_data_list->GetFilterList() ?>;
fclassified_datalist.Init();
</script>
<?php
$classified_data_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$classified_data_list->Page_Terminate();
?>