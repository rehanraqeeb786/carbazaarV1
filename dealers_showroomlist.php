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

$dealers_showroom_list = NULL; // Initialize page object first

class cdealers_showroom_list extends cdealers_showroom {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'dealers_showroom';

	// Page object name
	var $PageObjName = 'dealers_showroom_list';

	// Grid form hidden field names
	var $FormName = 'fdealers_showroomlist';
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

		// Table object (dealers_showroom)
		if (!isset($GLOBALS["dealers_showroom"]) || get_class($GLOBALS["dealers_showroom"]) == "cdealers_showroom") {
			$GLOBALS["dealers_showroom"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dealers_showroom"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "dealers_showroomadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "dealers_showroomdelete.php";
		$this->MultiUpdateUrl = "dealers_showroomupdate.php";

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fdealers_showroomlistsrch";

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
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

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

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

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

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

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
		$sFilterList = ew_Concat($sFilterList, $this->ID->AdvancedSearch->ToJSON(), ","); // Field ID
		$sFilterList = ew_Concat($sFilterList, $this->customer_id->AdvancedSearch->ToJSON(), ","); // Field customer_id
		$sFilterList = ew_Concat($sFilterList, $this->showroom_name->AdvancedSearch->ToJSON(), ","); // Field showroom_name
		$sFilterList = ew_Concat($sFilterList, $this->showroom_address->AdvancedSearch->ToJSON(), ","); // Field showroom_address
		$sFilterList = ew_Concat($sFilterList, $this->city_id->AdvancedSearch->ToJSON(), ","); // Field city_id
		$sFilterList = ew_Concat($sFilterList, $this->owner_name->AdvancedSearch->ToJSON(), ","); // Field owner_name
		$sFilterList = ew_Concat($sFilterList, $this->contact_1->AdvancedSearch->ToJSON(), ","); // Field contact_1
		$sFilterList = ew_Concat($sFilterList, $this->contat_2->AdvancedSearch->ToJSON(), ","); // Field contat_2
		$sFilterList = ew_Concat($sFilterList, $this->showroom_logo_link->AdvancedSearch->ToJSON(), ","); // Field showroom_logo_link
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJSON(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->created_at->AdvancedSearch->ToJSON(), ","); // Field created_at
		$sFilterList = ew_Concat($sFilterList, $this->updated_at->AdvancedSearch->ToJSON(), ","); // Field updated_at
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

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

		// Field ID
		$this->ID->AdvancedSearch->SearchValue = @$filter["x_ID"];
		$this->ID->AdvancedSearch->SearchOperator = @$filter["z_ID"];
		$this->ID->AdvancedSearch->SearchCondition = @$filter["v_ID"];
		$this->ID->AdvancedSearch->SearchValue2 = @$filter["y_ID"];
		$this->ID->AdvancedSearch->SearchOperator2 = @$filter["w_ID"];
		$this->ID->AdvancedSearch->Save();

		// Field customer_id
		$this->customer_id->AdvancedSearch->SearchValue = @$filter["x_customer_id"];
		$this->customer_id->AdvancedSearch->SearchOperator = @$filter["z_customer_id"];
		$this->customer_id->AdvancedSearch->SearchCondition = @$filter["v_customer_id"];
		$this->customer_id->AdvancedSearch->SearchValue2 = @$filter["y_customer_id"];
		$this->customer_id->AdvancedSearch->SearchOperator2 = @$filter["w_customer_id"];
		$this->customer_id->AdvancedSearch->Save();

		// Field showroom_name
		$this->showroom_name->AdvancedSearch->SearchValue = @$filter["x_showroom_name"];
		$this->showroom_name->AdvancedSearch->SearchOperator = @$filter["z_showroom_name"];
		$this->showroom_name->AdvancedSearch->SearchCondition = @$filter["v_showroom_name"];
		$this->showroom_name->AdvancedSearch->SearchValue2 = @$filter["y_showroom_name"];
		$this->showroom_name->AdvancedSearch->SearchOperator2 = @$filter["w_showroom_name"];
		$this->showroom_name->AdvancedSearch->Save();

		// Field showroom_address
		$this->showroom_address->AdvancedSearch->SearchValue = @$filter["x_showroom_address"];
		$this->showroom_address->AdvancedSearch->SearchOperator = @$filter["z_showroom_address"];
		$this->showroom_address->AdvancedSearch->SearchCondition = @$filter["v_showroom_address"];
		$this->showroom_address->AdvancedSearch->SearchValue2 = @$filter["y_showroom_address"];
		$this->showroom_address->AdvancedSearch->SearchOperator2 = @$filter["w_showroom_address"];
		$this->showroom_address->AdvancedSearch->Save();

		// Field city_id
		$this->city_id->AdvancedSearch->SearchValue = @$filter["x_city_id"];
		$this->city_id->AdvancedSearch->SearchOperator = @$filter["z_city_id"];
		$this->city_id->AdvancedSearch->SearchCondition = @$filter["v_city_id"];
		$this->city_id->AdvancedSearch->SearchValue2 = @$filter["y_city_id"];
		$this->city_id->AdvancedSearch->SearchOperator2 = @$filter["w_city_id"];
		$this->city_id->AdvancedSearch->Save();

		// Field owner_name
		$this->owner_name->AdvancedSearch->SearchValue = @$filter["x_owner_name"];
		$this->owner_name->AdvancedSearch->SearchOperator = @$filter["z_owner_name"];
		$this->owner_name->AdvancedSearch->SearchCondition = @$filter["v_owner_name"];
		$this->owner_name->AdvancedSearch->SearchValue2 = @$filter["y_owner_name"];
		$this->owner_name->AdvancedSearch->SearchOperator2 = @$filter["w_owner_name"];
		$this->owner_name->AdvancedSearch->Save();

		// Field contact_1
		$this->contact_1->AdvancedSearch->SearchValue = @$filter["x_contact_1"];
		$this->contact_1->AdvancedSearch->SearchOperator = @$filter["z_contact_1"];
		$this->contact_1->AdvancedSearch->SearchCondition = @$filter["v_contact_1"];
		$this->contact_1->AdvancedSearch->SearchValue2 = @$filter["y_contact_1"];
		$this->contact_1->AdvancedSearch->SearchOperator2 = @$filter["w_contact_1"];
		$this->contact_1->AdvancedSearch->Save();

		// Field contat_2
		$this->contat_2->AdvancedSearch->SearchValue = @$filter["x_contat_2"];
		$this->contat_2->AdvancedSearch->SearchOperator = @$filter["z_contat_2"];
		$this->contat_2->AdvancedSearch->SearchCondition = @$filter["v_contat_2"];
		$this->contat_2->AdvancedSearch->SearchValue2 = @$filter["y_contat_2"];
		$this->contat_2->AdvancedSearch->SearchOperator2 = @$filter["w_contat_2"];
		$this->contat_2->AdvancedSearch->Save();

		// Field showroom_logo_link
		$this->showroom_logo_link->AdvancedSearch->SearchValue = @$filter["x_showroom_logo_link"];
		$this->showroom_logo_link->AdvancedSearch->SearchOperator = @$filter["z_showroom_logo_link"];
		$this->showroom_logo_link->AdvancedSearch->SearchCondition = @$filter["v_showroom_logo_link"];
		$this->showroom_logo_link->AdvancedSearch->SearchValue2 = @$filter["y_showroom_logo_link"];
		$this->showroom_logo_link->AdvancedSearch->SearchOperator2 = @$filter["w_showroom_logo_link"];
		$this->showroom_logo_link->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field created_at
		$this->created_at->AdvancedSearch->SearchValue = @$filter["x_created_at"];
		$this->created_at->AdvancedSearch->SearchOperator = @$filter["z_created_at"];
		$this->created_at->AdvancedSearch->SearchCondition = @$filter["v_created_at"];
		$this->created_at->AdvancedSearch->SearchValue2 = @$filter["y_created_at"];
		$this->created_at->AdvancedSearch->SearchOperator2 = @$filter["w_created_at"];
		$this->created_at->AdvancedSearch->Save();

		// Field updated_at
		$this->updated_at->AdvancedSearch->SearchValue = @$filter["x_updated_at"];
		$this->updated_at->AdvancedSearch->SearchOperator = @$filter["z_updated_at"];
		$this->updated_at->AdvancedSearch->SearchCondition = @$filter["v_updated_at"];
		$this->updated_at->AdvancedSearch->SearchValue2 = @$filter["y_updated_at"];
		$this->updated_at->AdvancedSearch->SearchOperator2 = @$filter["w_updated_at"];
		$this->updated_at->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->ID, $Default, FALSE); // ID
		$this->BuildSearchSql($sWhere, $this->customer_id, $Default, FALSE); // customer_id
		$this->BuildSearchSql($sWhere, $this->showroom_name, $Default, FALSE); // showroom_name
		$this->BuildSearchSql($sWhere, $this->showroom_address, $Default, FALSE); // showroom_address
		$this->BuildSearchSql($sWhere, $this->city_id, $Default, FALSE); // city_id
		$this->BuildSearchSql($sWhere, $this->owner_name, $Default, FALSE); // owner_name
		$this->BuildSearchSql($sWhere, $this->contact_1, $Default, FALSE); // contact_1
		$this->BuildSearchSql($sWhere, $this->contat_2, $Default, FALSE); // contat_2
		$this->BuildSearchSql($sWhere, $this->showroom_logo_link, $Default, FALSE); // showroom_logo_link
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->created_at, $Default, FALSE); // created_at
		$this->BuildSearchSql($sWhere, $this->updated_at, $Default, FALSE); // updated_at

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->ID->AdvancedSearch->Save(); // ID
			$this->customer_id->AdvancedSearch->Save(); // customer_id
			$this->showroom_name->AdvancedSearch->Save(); // showroom_name
			$this->showroom_address->AdvancedSearch->Save(); // showroom_address
			$this->city_id->AdvancedSearch->Save(); // city_id
			$this->owner_name->AdvancedSearch->Save(); // owner_name
			$this->contact_1->AdvancedSearch->Save(); // contact_1
			$this->contat_2->AdvancedSearch->Save(); // contat_2
			$this->showroom_logo_link->AdvancedSearch->Save(); // showroom_logo_link
			$this->status->AdvancedSearch->Save(); // status
			$this->created_at->AdvancedSearch->Save(); // created_at
			$this->updated_at->AdvancedSearch->Save(); // updated_at
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

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->showroom_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->showroom_address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->owner_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->contact_1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->contat_2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->showroom_logo_link, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->ID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->customer_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->showroom_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->showroom_address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->city_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->owner_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contact_1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contat_2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->showroom_logo_link->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->created_at->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->updated_at->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->ID->AdvancedSearch->UnsetSession();
		$this->customer_id->AdvancedSearch->UnsetSession();
		$this->showroom_name->AdvancedSearch->UnsetSession();
		$this->showroom_address->AdvancedSearch->UnsetSession();
		$this->city_id->AdvancedSearch->UnsetSession();
		$this->owner_name->AdvancedSearch->UnsetSession();
		$this->contact_1->AdvancedSearch->UnsetSession();
		$this->contat_2->AdvancedSearch->UnsetSession();
		$this->showroom_logo_link->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->created_at->AdvancedSearch->UnsetSession();
		$this->updated_at->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->ID->AdvancedSearch->Load();
		$this->customer_id->AdvancedSearch->Load();
		$this->showroom_name->AdvancedSearch->Load();
		$this->showroom_address->AdvancedSearch->Load();
		$this->city_id->AdvancedSearch->Load();
		$this->owner_name->AdvancedSearch->Load();
		$this->contact_1->AdvancedSearch->Load();
		$this->contat_2->AdvancedSearch->Load();
		$this->showroom_logo_link->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->created_at->AdvancedSearch->Load();
		$this->updated_at->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->ID); // ID
			$this->UpdateSort($this->customer_id); // customer_id
			$this->UpdateSort($this->showroom_name); // showroom_name
			$this->UpdateSort($this->showroom_address); // showroom_address
			$this->UpdateSort($this->city_id); // city_id
			$this->UpdateSort($this->owner_name); // owner_name
			$this->UpdateSort($this->contact_1); // contact_1
			$this->UpdateSort($this->contat_2); // contat_2
			$this->UpdateSort($this->showroom_logo_link); // showroom_logo_link
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->created_at); // created_at
			$this->UpdateSort($this->updated_at); // updated_at
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
				$this->ID->setSort("");
				$this->customer_id->setSort("");
				$this->showroom_name->setSort("");
				$this->showroom_address->setSort("");
				$this->city_id->setSort("");
				$this->owner_name->setSort("");
				$this->contact_1->setSort("");
				$this->contat_2->setSort("");
				$this->showroom_logo_link->setSort("");
				$this->status->setSort("");
				$this->created_at->setSort("");
				$this->updated_at->setSort("");
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

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

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

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fdealers_showroomlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fdealers_showroomlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fdealers_showroomlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fdealers_showroomlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// ID

		$this->ID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ID"]);
		if ($this->ID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ID->AdvancedSearch->SearchOperator = @$_GET["z_ID"];

		// customer_id
		$this->customer_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_customer_id"]);
		if ($this->customer_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->customer_id->AdvancedSearch->SearchOperator = @$_GET["z_customer_id"];

		// showroom_name
		$this->showroom_name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_showroom_name"]);
		if ($this->showroom_name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->showroom_name->AdvancedSearch->SearchOperator = @$_GET["z_showroom_name"];

		// showroom_address
		$this->showroom_address->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_showroom_address"]);
		if ($this->showroom_address->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->showroom_address->AdvancedSearch->SearchOperator = @$_GET["z_showroom_address"];

		// city_id
		$this->city_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_city_id"]);
		if ($this->city_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->city_id->AdvancedSearch->SearchOperator = @$_GET["z_city_id"];

		// owner_name
		$this->owner_name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_owner_name"]);
		if ($this->owner_name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->owner_name->AdvancedSearch->SearchOperator = @$_GET["z_owner_name"];

		// contact_1
		$this->contact_1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_contact_1"]);
		if ($this->contact_1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->contact_1->AdvancedSearch->SearchOperator = @$_GET["z_contact_1"];

		// contat_2
		$this->contat_2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_contat_2"]);
		if ($this->contat_2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->contat_2->AdvancedSearch->SearchOperator = @$_GET["z_contat_2"];

		// showroom_logo_link
		$this->showroom_logo_link->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_showroom_logo_link"]);
		if ($this->showroom_logo_link->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->showroom_logo_link->AdvancedSearch->SearchOperator = @$_GET["z_showroom_logo_link"];

		// status
		$this->status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_status"]);
		if ($this->status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// created_at
		$this->created_at->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_created_at"]);
		if ($this->created_at->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->created_at->AdvancedSearch->SearchOperator = @$_GET["z_created_at"];

		// updated_at
		$this->updated_at->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_updated_at"]);
		if ($this->updated_at->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->updated_at->AdvancedSearch->SearchOperator = @$_GET["z_updated_at"];
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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// ID
			$this->ID->EditAttrs["class"] = "form-control";
			$this->ID->EditCustomAttributes = "";
			$this->ID->EditValue = ew_HtmlEncode($this->ID->AdvancedSearch->SearchValue);
			$this->ID->PlaceHolder = ew_RemoveHtml($this->ID->FldCaption());

			// customer_id
			$this->customer_id->EditAttrs["class"] = "form-control";
			$this->customer_id->EditCustomAttributes = "";
			$this->customer_id->EditValue = ew_HtmlEncode($this->customer_id->AdvancedSearch->SearchValue);
			$this->customer_id->PlaceHolder = ew_RemoveHtml($this->customer_id->FldCaption());

			// showroom_name
			$this->showroom_name->EditAttrs["class"] = "form-control";
			$this->showroom_name->EditCustomAttributes = "";
			$this->showroom_name->EditValue = ew_HtmlEncode($this->showroom_name->AdvancedSearch->SearchValue);
			$this->showroom_name->PlaceHolder = ew_RemoveHtml($this->showroom_name->FldCaption());

			// showroom_address
			$this->showroom_address->EditAttrs["class"] = "form-control";
			$this->showroom_address->EditCustomAttributes = "";
			$this->showroom_address->EditValue = ew_HtmlEncode($this->showroom_address->AdvancedSearch->SearchValue);
			$this->showroom_address->PlaceHolder = ew_RemoveHtml($this->showroom_address->FldCaption());

			// city_id
			$this->city_id->EditAttrs["class"] = "form-control";
			$this->city_id->EditCustomAttributes = "";
			$this->city_id->EditValue = ew_HtmlEncode($this->city_id->AdvancedSearch->SearchValue);
			$this->city_id->PlaceHolder = ew_RemoveHtml($this->city_id->FldCaption());

			// owner_name
			$this->owner_name->EditAttrs["class"] = "form-control";
			$this->owner_name->EditCustomAttributes = "";
			$this->owner_name->EditValue = ew_HtmlEncode($this->owner_name->AdvancedSearch->SearchValue);
			$this->owner_name->PlaceHolder = ew_RemoveHtml($this->owner_name->FldCaption());

			// contact_1
			$this->contact_1->EditAttrs["class"] = "form-control";
			$this->contact_1->EditCustomAttributes = "";
			$this->contact_1->EditValue = ew_HtmlEncode($this->contact_1->AdvancedSearch->SearchValue);
			$this->contact_1->PlaceHolder = ew_RemoveHtml($this->contact_1->FldCaption());

			// contat_2
			$this->contat_2->EditAttrs["class"] = "form-control";
			$this->contat_2->EditCustomAttributes = "";
			$this->contat_2->EditValue = ew_HtmlEncode($this->contat_2->AdvancedSearch->SearchValue);
			$this->contat_2->PlaceHolder = ew_RemoveHtml($this->contat_2->FldCaption());

			// showroom_logo_link
			$this->showroom_logo_link->EditAttrs["class"] = "form-control";
			$this->showroom_logo_link->EditCustomAttributes = "";
			$this->showroom_logo_link->EditValue = ew_HtmlEncode($this->showroom_logo_link->AdvancedSearch->SearchValue);
			$this->showroom_logo_link->PlaceHolder = ew_RemoveHtml($this->showroom_logo_link->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// created_at
			$this->created_at->EditAttrs["class"] = "form-control";
			$this->created_at->EditCustomAttributes = "";
			$this->created_at->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->created_at->AdvancedSearch->SearchValue, 5), 5));
			$this->created_at->PlaceHolder = ew_RemoveHtml($this->created_at->FldCaption());

			// updated_at
			$this->updated_at->EditAttrs["class"] = "form-control";
			$this->updated_at->EditCustomAttributes = "";
			$this->updated_at->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->updated_at->AdvancedSearch->SearchValue, 5), 5));
			$this->updated_at->PlaceHolder = ew_RemoveHtml($this->updated_at->FldCaption());
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
		$this->ID->AdvancedSearch->Load();
		$this->customer_id->AdvancedSearch->Load();
		$this->showroom_name->AdvancedSearch->Load();
		$this->showroom_address->AdvancedSearch->Load();
		$this->city_id->AdvancedSearch->Load();
		$this->owner_name->AdvancedSearch->Load();
		$this->contact_1->AdvancedSearch->Load();
		$this->contat_2->AdvancedSearch->Load();
		$this->showroom_logo_link->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->created_at->AdvancedSearch->Load();
		$this->updated_at->AdvancedSearch->Load();
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
if (!isset($dealers_showroom_list)) $dealers_showroom_list = new cdealers_showroom_list();

// Page init
$dealers_showroom_list->Page_Init();

// Page main
$dealers_showroom_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dealers_showroom_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fdealers_showroomlist = new ew_Form("fdealers_showroomlist", "list");
fdealers_showroomlist.FormKeyCountName = '<?php echo $dealers_showroom_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdealers_showroomlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdealers_showroomlist.ValidateRequired = true;
<?php } else { ?>
fdealers_showroomlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdealers_showroomlist.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdealers_showroomlist.Lists["x_status"].Options = <?php echo json_encode($dealers_showroom->status->Options()) ?>;

// Form object for search
var CurrentSearchForm = fdealers_showroomlistsrch = new ew_Form("fdealers_showroomlistsrch");

// Validate function for search
fdealers_showroomlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fdealers_showroomlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdealers_showroomlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fdealers_showroomlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fdealers_showroomlistsrch.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdealers_showroomlistsrch.Lists["x_status"].Options = <?php echo json_encode($dealers_showroom->status->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($dealers_showroom_list->TotalRecs > 0 && $dealers_showroom_list->ExportOptions->Visible()) { ?>
<?php $dealers_showroom_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($dealers_showroom_list->SearchOptions->Visible()) { ?>
<?php $dealers_showroom_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($dealers_showroom_list->FilterOptions->Visible()) { ?>
<?php $dealers_showroom_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $dealers_showroom_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($dealers_showroom_list->TotalRecs <= 0)
			$dealers_showroom_list->TotalRecs = $dealers_showroom->SelectRecordCount();
	} else {
		if (!$dealers_showroom_list->Recordset && ($dealers_showroom_list->Recordset = $dealers_showroom_list->LoadRecordset()))
			$dealers_showroom_list->TotalRecs = $dealers_showroom_list->Recordset->RecordCount();
	}
	$dealers_showroom_list->StartRec = 1;
	if ($dealers_showroom_list->DisplayRecs <= 0 || ($dealers_showroom->Export <> "" && $dealers_showroom->ExportAll)) // Display all records
		$dealers_showroom_list->DisplayRecs = $dealers_showroom_list->TotalRecs;
	if (!($dealers_showroom->Export <> "" && $dealers_showroom->ExportAll))
		$dealers_showroom_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$dealers_showroom_list->Recordset = $dealers_showroom_list->LoadRecordset($dealers_showroom_list->StartRec-1, $dealers_showroom_list->DisplayRecs);

	// Set no record found message
	if ($dealers_showroom->CurrentAction == "" && $dealers_showroom_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$dealers_showroom_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($dealers_showroom_list->SearchWhere == "0=101")
			$dealers_showroom_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$dealers_showroom_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$dealers_showroom_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($dealers_showroom->Export == "" && $dealers_showroom->CurrentAction == "") { ?>
<form name="fdealers_showroomlistsrch" id="fdealers_showroomlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($dealers_showroom_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fdealers_showroomlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="dealers_showroom">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$dealers_showroom_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$dealers_showroom->RowType = EW_ROWTYPE_SEARCH;

// Render row
$dealers_showroom->ResetAttrs();
$dealers_showroom_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($dealers_showroom->status->Visible) { // status ?>
	<div id="xsc_status" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $dealers_showroom->status->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="dealers_showroom" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($dealers_showroom->status->DisplayValueSeparator) ? json_encode($dealers_showroom->status->DisplayValueSeparator) : $dealers_showroom->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $dealers_showroom->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $dealers_showroom->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($dealers_showroom->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
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
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($dealers_showroom_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($dealers_showroom_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $dealers_showroom_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($dealers_showroom_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($dealers_showroom_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($dealers_showroom_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($dealers_showroom_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $dealers_showroom_list->ShowPageHeader(); ?>
<?php
$dealers_showroom_list->ShowMessage();
?>
<?php if ($dealers_showroom_list->TotalRecs > 0 || $dealers_showroom->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fdealers_showroomlist" id="fdealers_showroomlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dealers_showroom_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dealers_showroom_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dealers_showroom">
<div id="gmp_dealers_showroom" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($dealers_showroom_list->TotalRecs > 0) { ?>
<table id="tbl_dealers_showroomlist" class="table ewTable">
<?php echo $dealers_showroom->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$dealers_showroom_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$dealers_showroom_list->RenderListOptions();

// Render list options (header, left)
$dealers_showroom_list->ListOptions->Render("header", "left");
?>
<?php if ($dealers_showroom->ID->Visible) { // ID ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->ID) == "") { ?>
		<th data-name="ID"><div id="elh_dealers_showroom_ID" class="dealers_showroom_ID"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->ID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->ID) ?>',1);"><div id="elh_dealers_showroom_ID" class="dealers_showroom_ID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->ID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->ID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->ID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->customer_id->Visible) { // customer_id ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->customer_id) == "") { ?>
		<th data-name="customer_id"><div id="elh_dealers_showroom_customer_id" class="dealers_showroom_customer_id"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->customer_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="customer_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->customer_id) ?>',1);"><div id="elh_dealers_showroom_customer_id" class="dealers_showroom_customer_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->customer_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->customer_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->customer_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->showroom_name->Visible) { // showroom_name ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->showroom_name) == "") { ?>
		<th data-name="showroom_name"><div id="elh_dealers_showroom_showroom_name" class="dealers_showroom_showroom_name"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->showroom_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="showroom_name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->showroom_name) ?>',1);"><div id="elh_dealers_showroom_showroom_name" class="dealers_showroom_showroom_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->showroom_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->showroom_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->showroom_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->showroom_address->Visible) { // showroom_address ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->showroom_address) == "") { ?>
		<th data-name="showroom_address"><div id="elh_dealers_showroom_showroom_address" class="dealers_showroom_showroom_address"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->showroom_address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="showroom_address"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->showroom_address) ?>',1);"><div id="elh_dealers_showroom_showroom_address" class="dealers_showroom_showroom_address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->showroom_address->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->showroom_address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->showroom_address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->city_id->Visible) { // city_id ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->city_id) == "") { ?>
		<th data-name="city_id"><div id="elh_dealers_showroom_city_id" class="dealers_showroom_city_id"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->city_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="city_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->city_id) ?>',1);"><div id="elh_dealers_showroom_city_id" class="dealers_showroom_city_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->city_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->city_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->city_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->owner_name->Visible) { // owner_name ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->owner_name) == "") { ?>
		<th data-name="owner_name"><div id="elh_dealers_showroom_owner_name" class="dealers_showroom_owner_name"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->owner_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="owner_name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->owner_name) ?>',1);"><div id="elh_dealers_showroom_owner_name" class="dealers_showroom_owner_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->owner_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->owner_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->owner_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->contact_1->Visible) { // contact_1 ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->contact_1) == "") { ?>
		<th data-name="contact_1"><div id="elh_dealers_showroom_contact_1" class="dealers_showroom_contact_1"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->contact_1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="contact_1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->contact_1) ?>',1);"><div id="elh_dealers_showroom_contact_1" class="dealers_showroom_contact_1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->contact_1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->contact_1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->contact_1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->contat_2->Visible) { // contat_2 ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->contat_2) == "") { ?>
		<th data-name="contat_2"><div id="elh_dealers_showroom_contat_2" class="dealers_showroom_contat_2"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->contat_2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="contat_2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->contat_2) ?>',1);"><div id="elh_dealers_showroom_contat_2" class="dealers_showroom_contat_2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->contat_2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->contat_2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->contat_2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->showroom_logo_link->Visible) { // showroom_logo_link ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->showroom_logo_link) == "") { ?>
		<th data-name="showroom_logo_link"><div id="elh_dealers_showroom_showroom_logo_link" class="dealers_showroom_showroom_logo_link"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->showroom_logo_link->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="showroom_logo_link"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->showroom_logo_link) ?>',1);"><div id="elh_dealers_showroom_showroom_logo_link" class="dealers_showroom_showroom_logo_link">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->showroom_logo_link->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->showroom_logo_link->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->showroom_logo_link->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->status->Visible) { // status ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->status) == "") { ?>
		<th data-name="status"><div id="elh_dealers_showroom_status" class="dealers_showroom_status"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->status) ?>',1);"><div id="elh_dealers_showroom_status" class="dealers_showroom_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->created_at->Visible) { // created_at ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->created_at) == "") { ?>
		<th data-name="created_at"><div id="elh_dealers_showroom_created_at" class="dealers_showroom_created_at"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->created_at->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created_at"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->created_at) ?>',1);"><div id="elh_dealers_showroom_created_at" class="dealers_showroom_created_at">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->created_at->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->created_at->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->created_at->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($dealers_showroom->updated_at->Visible) { // updated_at ?>
	<?php if ($dealers_showroom->SortUrl($dealers_showroom->updated_at) == "") { ?>
		<th data-name="updated_at"><div id="elh_dealers_showroom_updated_at" class="dealers_showroom_updated_at"><div class="ewTableHeaderCaption"><?php echo $dealers_showroom->updated_at->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="updated_at"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dealers_showroom->SortUrl($dealers_showroom->updated_at) ?>',1);"><div id="elh_dealers_showroom_updated_at" class="dealers_showroom_updated_at">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dealers_showroom->updated_at->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dealers_showroom->updated_at->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dealers_showroom->updated_at->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$dealers_showroom_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($dealers_showroom->ExportAll && $dealers_showroom->Export <> "") {
	$dealers_showroom_list->StopRec = $dealers_showroom_list->TotalRecs;
} else {

	// Set the last record to display
	if ($dealers_showroom_list->TotalRecs > $dealers_showroom_list->StartRec + $dealers_showroom_list->DisplayRecs - 1)
		$dealers_showroom_list->StopRec = $dealers_showroom_list->StartRec + $dealers_showroom_list->DisplayRecs - 1;
	else
		$dealers_showroom_list->StopRec = $dealers_showroom_list->TotalRecs;
}
$dealers_showroom_list->RecCnt = $dealers_showroom_list->StartRec - 1;
if ($dealers_showroom_list->Recordset && !$dealers_showroom_list->Recordset->EOF) {
	$dealers_showroom_list->Recordset->MoveFirst();
	$bSelectLimit = $dealers_showroom_list->UseSelectLimit;
	if (!$bSelectLimit && $dealers_showroom_list->StartRec > 1)
		$dealers_showroom_list->Recordset->Move($dealers_showroom_list->StartRec - 1);
} elseif (!$dealers_showroom->AllowAddDeleteRow && $dealers_showroom_list->StopRec == 0) {
	$dealers_showroom_list->StopRec = $dealers_showroom->GridAddRowCount;
}

// Initialize aggregate
$dealers_showroom->RowType = EW_ROWTYPE_AGGREGATEINIT;
$dealers_showroom->ResetAttrs();
$dealers_showroom_list->RenderRow();
while ($dealers_showroom_list->RecCnt < $dealers_showroom_list->StopRec) {
	$dealers_showroom_list->RecCnt++;
	if (intval($dealers_showroom_list->RecCnt) >= intval($dealers_showroom_list->StartRec)) {
		$dealers_showroom_list->RowCnt++;

		// Set up key count
		$dealers_showroom_list->KeyCount = $dealers_showroom_list->RowIndex;

		// Init row class and style
		$dealers_showroom->ResetAttrs();
		$dealers_showroom->CssClass = "";
		if ($dealers_showroom->CurrentAction == "gridadd") {
		} else {
			$dealers_showroom_list->LoadRowValues($dealers_showroom_list->Recordset); // Load row values
		}
		$dealers_showroom->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$dealers_showroom->RowAttrs = array_merge($dealers_showroom->RowAttrs, array('data-rowindex'=>$dealers_showroom_list->RowCnt, 'id'=>'r' . $dealers_showroom_list->RowCnt . '_dealers_showroom', 'data-rowtype'=>$dealers_showroom->RowType));

		// Render row
		$dealers_showroom_list->RenderRow();

		// Render list options
		$dealers_showroom_list->RenderListOptions();
?>
	<tr<?php echo $dealers_showroom->RowAttributes() ?>>
<?php

// Render list options (body, left)
$dealers_showroom_list->ListOptions->Render("body", "left", $dealers_showroom_list->RowCnt);
?>
	<?php if ($dealers_showroom->ID->Visible) { // ID ?>
		<td data-name="ID"<?php echo $dealers_showroom->ID->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_ID" class="dealers_showroom_ID">
<span<?php echo $dealers_showroom->ID->ViewAttributes() ?>>
<?php echo $dealers_showroom->ID->ListViewValue() ?></span>
</span>
<a id="<?php echo $dealers_showroom_list->PageObjName . "_row_" . $dealers_showroom_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($dealers_showroom->customer_id->Visible) { // customer_id ?>
		<td data-name="customer_id"<?php echo $dealers_showroom->customer_id->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_customer_id" class="dealers_showroom_customer_id">
<span<?php echo $dealers_showroom->customer_id->ViewAttributes() ?>>
<?php echo $dealers_showroom->customer_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dealers_showroom->showroom_name->Visible) { // showroom_name ?>
		<td data-name="showroom_name"<?php echo $dealers_showroom->showroom_name->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_showroom_name" class="dealers_showroom_showroom_name">
<span<?php echo $dealers_showroom->showroom_name->ViewAttributes() ?>>
<?php echo $dealers_showroom->showroom_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dealers_showroom->showroom_address->Visible) { // showroom_address ?>
		<td data-name="showroom_address"<?php echo $dealers_showroom->showroom_address->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_showroom_address" class="dealers_showroom_showroom_address">
<span<?php echo $dealers_showroom->showroom_address->ViewAttributes() ?>>
<?php echo $dealers_showroom->showroom_address->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dealers_showroom->city_id->Visible) { // city_id ?>
		<td data-name="city_id"<?php echo $dealers_showroom->city_id->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_city_id" class="dealers_showroom_city_id">
<span<?php echo $dealers_showroom->city_id->ViewAttributes() ?>>
<?php echo $dealers_showroom->city_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dealers_showroom->owner_name->Visible) { // owner_name ?>
		<td data-name="owner_name"<?php echo $dealers_showroom->owner_name->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_owner_name" class="dealers_showroom_owner_name">
<span<?php echo $dealers_showroom->owner_name->ViewAttributes() ?>>
<?php echo $dealers_showroom->owner_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dealers_showroom->contact_1->Visible) { // contact_1 ?>
		<td data-name="contact_1"<?php echo $dealers_showroom->contact_1->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_contact_1" class="dealers_showroom_contact_1">
<span<?php echo $dealers_showroom->contact_1->ViewAttributes() ?>>
<?php echo $dealers_showroom->contact_1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dealers_showroom->contat_2->Visible) { // contat_2 ?>
		<td data-name="contat_2"<?php echo $dealers_showroom->contat_2->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_contat_2" class="dealers_showroom_contat_2">
<span<?php echo $dealers_showroom->contat_2->ViewAttributes() ?>>
<?php echo $dealers_showroom->contat_2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dealers_showroom->showroom_logo_link->Visible) { // showroom_logo_link ?>
		<td data-name="showroom_logo_link"<?php echo $dealers_showroom->showroom_logo_link->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_showroom_logo_link" class="dealers_showroom_showroom_logo_link">
<span<?php echo $dealers_showroom->showroom_logo_link->ViewAttributes() ?>>
<?php echo $dealers_showroom->showroom_logo_link->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dealers_showroom->status->Visible) { // status ?>
		<td data-name="status"<?php echo $dealers_showroom->status->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_status" class="dealers_showroom_status">
<span<?php echo $dealers_showroom->status->ViewAttributes() ?>>
<?php echo $dealers_showroom->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dealers_showroom->created_at->Visible) { // created_at ?>
		<td data-name="created_at"<?php echo $dealers_showroom->created_at->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_created_at" class="dealers_showroom_created_at">
<span<?php echo $dealers_showroom->created_at->ViewAttributes() ?>>
<?php echo $dealers_showroom->created_at->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dealers_showroom->updated_at->Visible) { // updated_at ?>
		<td data-name="updated_at"<?php echo $dealers_showroom->updated_at->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_list->RowCnt ?>_dealers_showroom_updated_at" class="dealers_showroom_updated_at">
<span<?php echo $dealers_showroom->updated_at->ViewAttributes() ?>>
<?php echo $dealers_showroom->updated_at->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$dealers_showroom_list->ListOptions->Render("body", "right", $dealers_showroom_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($dealers_showroom->CurrentAction <> "gridadd")
		$dealers_showroom_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($dealers_showroom->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($dealers_showroom_list->Recordset)
	$dealers_showroom_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($dealers_showroom->CurrentAction <> "gridadd" && $dealers_showroom->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($dealers_showroom_list->Pager)) $dealers_showroom_list->Pager = new cPrevNextPager($dealers_showroom_list->StartRec, $dealers_showroom_list->DisplayRecs, $dealers_showroom_list->TotalRecs) ?>
<?php if ($dealers_showroom_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($dealers_showroom_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $dealers_showroom_list->PageUrl() ?>start=<?php echo $dealers_showroom_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($dealers_showroom_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $dealers_showroom_list->PageUrl() ?>start=<?php echo $dealers_showroom_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $dealers_showroom_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($dealers_showroom_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $dealers_showroom_list->PageUrl() ?>start=<?php echo $dealers_showroom_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($dealers_showroom_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $dealers_showroom_list->PageUrl() ?>start=<?php echo $dealers_showroom_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $dealers_showroom_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $dealers_showroom_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $dealers_showroom_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $dealers_showroom_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($dealers_showroom_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($dealers_showroom_list->TotalRecs == 0 && $dealers_showroom->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($dealers_showroom_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fdealers_showroomlistsrch.Init();
fdealers_showroomlistsrch.FilterList = <?php echo $dealers_showroom_list->GetFilterList() ?>;
fdealers_showroomlist.Init();
</script>
<?php
$dealers_showroom_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dealers_showroom_list->Page_Terminate();
?>
