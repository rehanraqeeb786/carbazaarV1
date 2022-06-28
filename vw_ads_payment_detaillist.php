<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "vw_ads_payment_detailinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$vw_ads_payment_detail_list = NULL; // Initialize page object first

class cvw_ads_payment_detail_list extends cvw_ads_payment_detail {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'vw_ads_payment_detail';

	// Page object name
	var $PageObjName = 'vw_ads_payment_detail_list';

	// Grid form hidden field names
	var $FormName = 'fvw_ads_payment_detaillist';
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

		// Table object (vw_ads_payment_detail)
		if (!isset($GLOBALS["vw_ads_payment_detail"]) || get_class($GLOBALS["vw_ads_payment_detail"]) == "cvw_ads_payment_detail") {
			$GLOBALS["vw_ads_payment_detail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vw_ads_payment_detail"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vw_ads_payment_detailadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vw_ads_payment_detaildelete.php";
		$this->MultiUpdateUrl = "vw_ads_payment_detailupdate.php";

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vw_ads_payment_detail', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fvw_ads_payment_detaillistsrch";

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
		global $EW_EXPORT, $vw_ads_payment_detail;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($vw_ads_payment_detail);
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
	var $DisplayRecs = 100;
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
			$this->DisplayRecs = 100; // Load default
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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->created_at->AdvancedSearch->ToJSON(), ","); // Field created_at
		$sFilterList = ew_Concat($sFilterList, $this->package_id->AdvancedSearch->ToJSON(), ","); // Field package_id
		$sFilterList = ew_Concat($sFilterList, $this->pay_method_id->AdvancedSearch->ToJSON(), ","); // Field pay_method_id
		$sFilterList = ew_Concat($sFilterList, $this->bank_id->AdvancedSearch->ToJSON(), ","); // Field bank_id
		$sFilterList = ew_Concat($sFilterList, $this->transaction_id->AdvancedSearch->ToJSON(), ","); // Field transaction_id
		$sFilterList = ew_Concat($sFilterList, $this->order_reference_id->AdvancedSearch->ToJSON(), ","); // Field order_reference_id

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

		// Field created_at
		$this->created_at->AdvancedSearch->SearchValue = @$filter["x_created_at"];
		$this->created_at->AdvancedSearch->SearchOperator = @$filter["z_created_at"];
		$this->created_at->AdvancedSearch->SearchCondition = @$filter["v_created_at"];
		$this->created_at->AdvancedSearch->SearchValue2 = @$filter["y_created_at"];
		$this->created_at->AdvancedSearch->SearchOperator2 = @$filter["w_created_at"];
		$this->created_at->AdvancedSearch->Save();

		// Field package_id
		$this->package_id->AdvancedSearch->SearchValue = @$filter["x_package_id"];
		$this->package_id->AdvancedSearch->SearchOperator = @$filter["z_package_id"];
		$this->package_id->AdvancedSearch->SearchCondition = @$filter["v_package_id"];
		$this->package_id->AdvancedSearch->SearchValue2 = @$filter["y_package_id"];
		$this->package_id->AdvancedSearch->SearchOperator2 = @$filter["w_package_id"];
		$this->package_id->AdvancedSearch->Save();

		// Field pay_method_id
		$this->pay_method_id->AdvancedSearch->SearchValue = @$filter["x_pay_method_id"];
		$this->pay_method_id->AdvancedSearch->SearchOperator = @$filter["z_pay_method_id"];
		$this->pay_method_id->AdvancedSearch->SearchCondition = @$filter["v_pay_method_id"];
		$this->pay_method_id->AdvancedSearch->SearchValue2 = @$filter["y_pay_method_id"];
		$this->pay_method_id->AdvancedSearch->SearchOperator2 = @$filter["w_pay_method_id"];
		$this->pay_method_id->AdvancedSearch->Save();

		// Field bank_id
		$this->bank_id->AdvancedSearch->SearchValue = @$filter["x_bank_id"];
		$this->bank_id->AdvancedSearch->SearchOperator = @$filter["z_bank_id"];
		$this->bank_id->AdvancedSearch->SearchCondition = @$filter["v_bank_id"];
		$this->bank_id->AdvancedSearch->SearchValue2 = @$filter["y_bank_id"];
		$this->bank_id->AdvancedSearch->SearchOperator2 = @$filter["w_bank_id"];
		$this->bank_id->AdvancedSearch->Save();

		// Field transaction_id
		$this->transaction_id->AdvancedSearch->SearchValue = @$filter["x_transaction_id"];
		$this->transaction_id->AdvancedSearch->SearchOperator = @$filter["z_transaction_id"];
		$this->transaction_id->AdvancedSearch->SearchCondition = @$filter["v_transaction_id"];
		$this->transaction_id->AdvancedSearch->SearchValue2 = @$filter["y_transaction_id"];
		$this->transaction_id->AdvancedSearch->SearchOperator2 = @$filter["w_transaction_id"];
		$this->transaction_id->AdvancedSearch->Save();

		// Field order_reference_id
		$this->order_reference_id->AdvancedSearch->SearchValue = @$filter["x_order_reference_id"];
		$this->order_reference_id->AdvancedSearch->SearchOperator = @$filter["z_order_reference_id"];
		$this->order_reference_id->AdvancedSearch->SearchCondition = @$filter["v_order_reference_id"];
		$this->order_reference_id->AdvancedSearch->SearchValue2 = @$filter["y_order_reference_id"];
		$this->order_reference_id->AdvancedSearch->SearchOperator2 = @$filter["w_order_reference_id"];
		$this->order_reference_id->AdvancedSearch->Save();
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->created_at, $Default, FALSE); // created_at
		$this->BuildSearchSql($sWhere, $this->package_id, $Default, FALSE); // package_id
		$this->BuildSearchSql($sWhere, $this->pay_method_id, $Default, FALSE); // pay_method_id
		$this->BuildSearchSql($sWhere, $this->bank_id, $Default, FALSE); // bank_id
		$this->BuildSearchSql($sWhere, $this->transaction_id, $Default, FALSE); // transaction_id
		$this->BuildSearchSql($sWhere, $this->order_reference_id, $Default, FALSE); // order_reference_id

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->created_at->AdvancedSearch->Save(); // created_at
			$this->package_id->AdvancedSearch->Save(); // package_id
			$this->pay_method_id->AdvancedSearch->Save(); // pay_method_id
			$this->bank_id->AdvancedSearch->Save(); // bank_id
			$this->transaction_id->AdvancedSearch->Save(); // transaction_id
			$this->order_reference_id->AdvancedSearch->Save(); // order_reference_id
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
		if ($this->created_at->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->package_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pay_method_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bank_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->transaction_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->order_reference_id->AdvancedSearch->IssetSession())
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
		$this->created_at->AdvancedSearch->UnsetSession();
		$this->package_id->AdvancedSearch->UnsetSession();
		$this->pay_method_id->AdvancedSearch->UnsetSession();
		$this->bank_id->AdvancedSearch->UnsetSession();
		$this->transaction_id->AdvancedSearch->UnsetSession();
		$this->order_reference_id->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->created_at->AdvancedSearch->Load();
		$this->package_id->AdvancedSearch->Load();
		$this->pay_method_id->AdvancedSearch->Load();
		$this->bank_id->AdvancedSearch->Load();
		$this->transaction_id->AdvancedSearch->Load();
		$this->order_reference_id->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->created_at); // created_at
			$this->UpdateSort($this->ad_id); // ad_id
			$this->UpdateSort($this->amount); // amount
			$this->UpdateSort($this->package_id); // package_id
			$this->UpdateSort($this->pay_method_id); // pay_method_id
			$this->UpdateSort($this->bank_id); // bank_id
			$this->UpdateSort($this->transaction_id); // transaction_id
			$this->UpdateSort($this->order_reference_id); // order_reference_id
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
				$this->created_at->setSort("");
				$this->ad_id->setSort("");
				$this->amount->setSort("");
				$this->package_id->setSort("");
				$this->pay_method_id->setSort("");
				$this->bank_id->setSort("");
				$this->transaction_id->setSort("");
				$this->order_reference_id->setSort("");
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
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fvw_ads_payment_detaillistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fvw_ads_payment_detaillistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fvw_ads_payment_detaillist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"vw_ads_payment_detailsrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
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
		// created_at

		$this->created_at->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_created_at"]);
		if ($this->created_at->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->created_at->AdvancedSearch->SearchOperator = @$_GET["z_created_at"];
		$this->created_at->AdvancedSearch->SearchCondition = @$_GET["v_created_at"];
		$this->created_at->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_created_at"]);
		if ($this->created_at->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->created_at->AdvancedSearch->SearchOperator2 = @$_GET["w_created_at"];

		// package_id
		$this->package_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_package_id"]);
		if ($this->package_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->package_id->AdvancedSearch->SearchOperator = @$_GET["z_package_id"];

		// pay_method_id
		$this->pay_method_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pay_method_id"]);
		if ($this->pay_method_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->pay_method_id->AdvancedSearch->SearchOperator = @$_GET["z_pay_method_id"];

		// bank_id
		$this->bank_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bank_id"]);
		if ($this->bank_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bank_id->AdvancedSearch->SearchOperator = @$_GET["z_bank_id"];

		// transaction_id
		$this->transaction_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_transaction_id"]);
		if ($this->transaction_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->transaction_id->AdvancedSearch->SearchOperator = @$_GET["z_transaction_id"];

		// order_reference_id
		$this->order_reference_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_order_reference_id"]);
		if ($this->order_reference_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->order_reference_id->AdvancedSearch->SearchOperator = @$_GET["z_order_reference_id"];
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
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->ad_id->setDbValue($rs->fields('ad_id'));
		$this->amount->setDbValue($rs->fields('amount'));
		$this->package_id->setDbValue($rs->fields('package_id'));
		$this->pay_method_id->setDbValue($rs->fields('pay_method_id'));
		$this->bank_id->setDbValue($rs->fields('bank_id'));
		$this->transaction_id->setDbValue($rs->fields('transaction_id'));
		$this->order_reference_id->setDbValue($rs->fields('order_reference_id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->created_at->DbValue = $row['created_at'];
		$this->ad_id->DbValue = $row['ad_id'];
		$this->amount->DbValue = $row['amount'];
		$this->package_id->DbValue = $row['package_id'];
		$this->pay_method_id->DbValue = $row['pay_method_id'];
		$this->bank_id->DbValue = $row['bank_id'];
		$this->transaction_id->DbValue = $row['transaction_id'];
		$this->order_reference_id->DbValue = $row['order_reference_id'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

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
		// created_at

		$this->created_at->CellCssStyle = "white-space: nowrap;";

		// ad_id
		// amount
		// package_id
		// pay_method_id
		// bank_id
		// transaction_id
		// order_reference_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// created_at
		$this->created_at->ViewValue = $this->created_at->CurrentValue;
		$this->created_at->ViewValue = ew_FormatDateTime($this->created_at->ViewValue, 5);
		$this->created_at->ViewCustomAttributes = "";

		// ad_id
		$this->ad_id->ViewValue = $this->ad_id->CurrentValue;
		if (strval($this->ad_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->ad_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `ad_title` AS `DispFld`, `demand_price` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `car_ads`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->ad_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->ad_id->ViewValue = $this->ad_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->ad_id->ViewValue = $this->ad_id->CurrentValue;
			}
		} else {
			$this->ad_id->ViewValue = NULL;
		}
		$this->ad_id->ViewCustomAttributes = "";

		// amount
		$this->amount->ViewValue = $this->amount->CurrentValue;
		$this->amount->ViewCustomAttributes = "";

		// package_id
		if (strval($this->package_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->package_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `package_fee` AS `DispFld`, `number_of_days` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `packages`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->package_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->package_id->ViewValue = $this->package_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->package_id->ViewValue = $this->package_id->CurrentValue;
			}
		} else {
			$this->package_id->ViewValue = NULL;
		}
		$this->package_id->ViewCustomAttributes = "";

		// pay_method_id
		if (strval($this->pay_method_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->pay_method_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pay_methods`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pay_method_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pay_method_id->ViewValue = $this->pay_method_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pay_method_id->ViewValue = $this->pay_method_id->CurrentValue;
			}
		} else {
			$this->pay_method_id->ViewValue = NULL;
		}
		$this->pay_method_id->ViewCustomAttributes = "";

		// bank_id
		if (strval($this->bank_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->bank_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `bank_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank_accounts`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->bank_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->bank_id->ViewValue = $this->bank_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->bank_id->ViewValue = $this->bank_id->CurrentValue;
			}
		} else {
			$this->bank_id->ViewValue = NULL;
		}
		$this->bank_id->ViewCustomAttributes = "";

		// transaction_id
		$this->transaction_id->ViewValue = $this->transaction_id->CurrentValue;
		$this->transaction_id->ViewCustomAttributes = "";

		// order_reference_id
		$this->order_reference_id->ViewValue = $this->order_reference_id->CurrentValue;
		$this->order_reference_id->ViewCustomAttributes = "";

			// created_at
			$this->created_at->LinkCustomAttributes = "";
			$this->created_at->HrefValue = "";
			$this->created_at->TooltipValue = "";

			// ad_id
			$this->ad_id->LinkCustomAttributes = "";
			$this->ad_id->HrefValue = "";
			$this->ad_id->TooltipValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";
			$this->amount->TooltipValue = "";

			// package_id
			$this->package_id->LinkCustomAttributes = "";
			$this->package_id->HrefValue = "";
			$this->package_id->TooltipValue = "";

			// pay_method_id
			$this->pay_method_id->LinkCustomAttributes = "";
			$this->pay_method_id->HrefValue = "";
			$this->pay_method_id->TooltipValue = "";

			// bank_id
			$this->bank_id->LinkCustomAttributes = "";
			$this->bank_id->HrefValue = "";
			$this->bank_id->TooltipValue = "";

			// transaction_id
			$this->transaction_id->LinkCustomAttributes = "";
			$this->transaction_id->HrefValue = "";
			$this->transaction_id->TooltipValue = "";

			// order_reference_id
			$this->order_reference_id->LinkCustomAttributes = "";
			$this->order_reference_id->HrefValue = "";
			$this->order_reference_id->TooltipValue = "";
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
		$this->created_at->AdvancedSearch->Load();
		$this->package_id->AdvancedSearch->Load();
		$this->pay_method_id->AdvancedSearch->Load();
		$this->bank_id->AdvancedSearch->Load();
		$this->transaction_id->AdvancedSearch->Load();
		$this->order_reference_id->AdvancedSearch->Load();
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
if (!isset($vw_ads_payment_detail_list)) $vw_ads_payment_detail_list = new cvw_ads_payment_detail_list();

// Page init
$vw_ads_payment_detail_list->Page_Init();

// Page main
$vw_ads_payment_detail_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vw_ads_payment_detail_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fvw_ads_payment_detaillist = new ew_Form("fvw_ads_payment_detaillist", "list");
fvw_ads_payment_detaillist.FormKeyCountName = '<?php echo $vw_ads_payment_detail_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvw_ads_payment_detaillist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvw_ads_payment_detaillist.ValidateRequired = true;
<?php } else { ?>
fvw_ads_payment_detaillist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvw_ads_payment_detaillist.Lists["x_ad_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_ad_title","x_demand_price","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fvw_ads_payment_detaillist.Lists["x_package_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_package_fee","x_number_of_days","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fvw_ads_payment_detaillist.Lists["x_pay_method_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fvw_ads_payment_detaillist.Lists["x_bank_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_bank_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fvw_ads_payment_detaillistsrch = new ew_Form("fvw_ads_payment_detaillistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($vw_ads_payment_detail_list->TotalRecs > 0 && $vw_ads_payment_detail_list->ExportOptions->Visible()) { ?>
<?php $vw_ads_payment_detail_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($vw_ads_payment_detail_list->SearchOptions->Visible()) { ?>
<?php $vw_ads_payment_detail_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($vw_ads_payment_detail_list->FilterOptions->Visible()) { ?>
<?php $vw_ads_payment_detail_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $vw_ads_payment_detail_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($vw_ads_payment_detail_list->TotalRecs <= 0)
			$vw_ads_payment_detail_list->TotalRecs = $vw_ads_payment_detail->SelectRecordCount();
	} else {
		if (!$vw_ads_payment_detail_list->Recordset && ($vw_ads_payment_detail_list->Recordset = $vw_ads_payment_detail_list->LoadRecordset()))
			$vw_ads_payment_detail_list->TotalRecs = $vw_ads_payment_detail_list->Recordset->RecordCount();
	}
	$vw_ads_payment_detail_list->StartRec = 1;
	if ($vw_ads_payment_detail_list->DisplayRecs <= 0 || ($vw_ads_payment_detail->Export <> "" && $vw_ads_payment_detail->ExportAll)) // Display all records
		$vw_ads_payment_detail_list->DisplayRecs = $vw_ads_payment_detail_list->TotalRecs;
	if (!($vw_ads_payment_detail->Export <> "" && $vw_ads_payment_detail->ExportAll))
		$vw_ads_payment_detail_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vw_ads_payment_detail_list->Recordset = $vw_ads_payment_detail_list->LoadRecordset($vw_ads_payment_detail_list->StartRec-1, $vw_ads_payment_detail_list->DisplayRecs);

	// Set no record found message
	if ($vw_ads_payment_detail->CurrentAction == "" && $vw_ads_payment_detail_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$vw_ads_payment_detail_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($vw_ads_payment_detail_list->SearchWhere == "0=101")
			$vw_ads_payment_detail_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$vw_ads_payment_detail_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$vw_ads_payment_detail_list->RenderOtherOptions();
?>
<?php $vw_ads_payment_detail_list->ShowPageHeader(); ?>
<?php
$vw_ads_payment_detail_list->ShowMessage();
?>
<?php if ($vw_ads_payment_detail_list->TotalRecs > 0 || $vw_ads_payment_detail->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fvw_ads_payment_detaillist" id="fvw_ads_payment_detaillist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vw_ads_payment_detail_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vw_ads_payment_detail_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vw_ads_payment_detail">
<div id="gmp_vw_ads_payment_detail" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($vw_ads_payment_detail_list->TotalRecs > 0) { ?>
<table id="tbl_vw_ads_payment_detaillist" class="table ewTable">
<?php echo $vw_ads_payment_detail->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$vw_ads_payment_detail_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$vw_ads_payment_detail_list->RenderListOptions();

// Render list options (header, left)
$vw_ads_payment_detail_list->ListOptions->Render("header", "left");
?>
<?php if ($vw_ads_payment_detail->created_at->Visible) { // created_at ?>
	<?php if ($vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->created_at) == "") { ?>
		<th data-name="created_at"><div id="elh_vw_ads_payment_detail_created_at" class="vw_ads_payment_detail_created_at"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $vw_ads_payment_detail->created_at->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created_at"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->created_at) ?>',1);"><div id="elh_vw_ads_payment_detail_created_at" class="vw_ads_payment_detail_created_at">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->created_at->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vw_ads_payment_detail->created_at->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vw_ads_payment_detail->created_at->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vw_ads_payment_detail->ad_id->Visible) { // ad_id ?>
	<?php if ($vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->ad_id) == "") { ?>
		<th data-name="ad_id"><div id="elh_vw_ads_payment_detail_ad_id" class="vw_ads_payment_detail_ad_id"><div class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->ad_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ad_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->ad_id) ?>',1);"><div id="elh_vw_ads_payment_detail_ad_id" class="vw_ads_payment_detail_ad_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->ad_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vw_ads_payment_detail->ad_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vw_ads_payment_detail->ad_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vw_ads_payment_detail->amount->Visible) { // amount ?>
	<?php if ($vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->amount) == "") { ?>
		<th data-name="amount"><div id="elh_vw_ads_payment_detail_amount" class="vw_ads_payment_detail_amount"><div class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->amount->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="amount"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->amount) ?>',1);"><div id="elh_vw_ads_payment_detail_amount" class="vw_ads_payment_detail_amount">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->amount->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vw_ads_payment_detail->amount->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vw_ads_payment_detail->amount->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vw_ads_payment_detail->package_id->Visible) { // package_id ?>
	<?php if ($vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->package_id) == "") { ?>
		<th data-name="package_id"><div id="elh_vw_ads_payment_detail_package_id" class="vw_ads_payment_detail_package_id"><div class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->package_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="package_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->package_id) ?>',1);"><div id="elh_vw_ads_payment_detail_package_id" class="vw_ads_payment_detail_package_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->package_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vw_ads_payment_detail->package_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vw_ads_payment_detail->package_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vw_ads_payment_detail->pay_method_id->Visible) { // pay_method_id ?>
	<?php if ($vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->pay_method_id) == "") { ?>
		<th data-name="pay_method_id"><div id="elh_vw_ads_payment_detail_pay_method_id" class="vw_ads_payment_detail_pay_method_id"><div class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->pay_method_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pay_method_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->pay_method_id) ?>',1);"><div id="elh_vw_ads_payment_detail_pay_method_id" class="vw_ads_payment_detail_pay_method_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->pay_method_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vw_ads_payment_detail->pay_method_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vw_ads_payment_detail->pay_method_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vw_ads_payment_detail->bank_id->Visible) { // bank_id ?>
	<?php if ($vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->bank_id) == "") { ?>
		<th data-name="bank_id"><div id="elh_vw_ads_payment_detail_bank_id" class="vw_ads_payment_detail_bank_id"><div class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->bank_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bank_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->bank_id) ?>',1);"><div id="elh_vw_ads_payment_detail_bank_id" class="vw_ads_payment_detail_bank_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->bank_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vw_ads_payment_detail->bank_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vw_ads_payment_detail->bank_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vw_ads_payment_detail->transaction_id->Visible) { // transaction_id ?>
	<?php if ($vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->transaction_id) == "") { ?>
		<th data-name="transaction_id"><div id="elh_vw_ads_payment_detail_transaction_id" class="vw_ads_payment_detail_transaction_id"><div class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->transaction_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="transaction_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->transaction_id) ?>',1);"><div id="elh_vw_ads_payment_detail_transaction_id" class="vw_ads_payment_detail_transaction_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->transaction_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vw_ads_payment_detail->transaction_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vw_ads_payment_detail->transaction_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vw_ads_payment_detail->order_reference_id->Visible) { // order_reference_id ?>
	<?php if ($vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->order_reference_id) == "") { ?>
		<th data-name="order_reference_id"><div id="elh_vw_ads_payment_detail_order_reference_id" class="vw_ads_payment_detail_order_reference_id"><div class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->order_reference_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="order_reference_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vw_ads_payment_detail->SortUrl($vw_ads_payment_detail->order_reference_id) ?>',1);"><div id="elh_vw_ads_payment_detail_order_reference_id" class="vw_ads_payment_detail_order_reference_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vw_ads_payment_detail->order_reference_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vw_ads_payment_detail->order_reference_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vw_ads_payment_detail->order_reference_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vw_ads_payment_detail_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vw_ads_payment_detail->ExportAll && $vw_ads_payment_detail->Export <> "") {
	$vw_ads_payment_detail_list->StopRec = $vw_ads_payment_detail_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vw_ads_payment_detail_list->TotalRecs > $vw_ads_payment_detail_list->StartRec + $vw_ads_payment_detail_list->DisplayRecs - 1)
		$vw_ads_payment_detail_list->StopRec = $vw_ads_payment_detail_list->StartRec + $vw_ads_payment_detail_list->DisplayRecs - 1;
	else
		$vw_ads_payment_detail_list->StopRec = $vw_ads_payment_detail_list->TotalRecs;
}
$vw_ads_payment_detail_list->RecCnt = $vw_ads_payment_detail_list->StartRec - 1;
if ($vw_ads_payment_detail_list->Recordset && !$vw_ads_payment_detail_list->Recordset->EOF) {
	$vw_ads_payment_detail_list->Recordset->MoveFirst();
	$bSelectLimit = $vw_ads_payment_detail_list->UseSelectLimit;
	if (!$bSelectLimit && $vw_ads_payment_detail_list->StartRec > 1)
		$vw_ads_payment_detail_list->Recordset->Move($vw_ads_payment_detail_list->StartRec - 1);
} elseif (!$vw_ads_payment_detail->AllowAddDeleteRow && $vw_ads_payment_detail_list->StopRec == 0) {
	$vw_ads_payment_detail_list->StopRec = $vw_ads_payment_detail->GridAddRowCount;
}

// Initialize aggregate
$vw_ads_payment_detail->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vw_ads_payment_detail->ResetAttrs();
$vw_ads_payment_detail_list->RenderRow();
while ($vw_ads_payment_detail_list->RecCnt < $vw_ads_payment_detail_list->StopRec) {
	$vw_ads_payment_detail_list->RecCnt++;
	if (intval($vw_ads_payment_detail_list->RecCnt) >= intval($vw_ads_payment_detail_list->StartRec)) {
		$vw_ads_payment_detail_list->RowCnt++;

		// Set up key count
		$vw_ads_payment_detail_list->KeyCount = $vw_ads_payment_detail_list->RowIndex;

		// Init row class and style
		$vw_ads_payment_detail->ResetAttrs();
		$vw_ads_payment_detail->CssClass = "";
		if ($vw_ads_payment_detail->CurrentAction == "gridadd") {
		} else {
			$vw_ads_payment_detail_list->LoadRowValues($vw_ads_payment_detail_list->Recordset); // Load row values
		}
		$vw_ads_payment_detail->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vw_ads_payment_detail->RowAttrs = array_merge($vw_ads_payment_detail->RowAttrs, array('data-rowindex'=>$vw_ads_payment_detail_list->RowCnt, 'id'=>'r' . $vw_ads_payment_detail_list->RowCnt . '_vw_ads_payment_detail', 'data-rowtype'=>$vw_ads_payment_detail->RowType));

		// Render row
		$vw_ads_payment_detail_list->RenderRow();

		// Render list options
		$vw_ads_payment_detail_list->RenderListOptions();
?>
	<tr<?php echo $vw_ads_payment_detail->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vw_ads_payment_detail_list->ListOptions->Render("body", "left", $vw_ads_payment_detail_list->RowCnt);
?>
	<?php if ($vw_ads_payment_detail->created_at->Visible) { // created_at ?>
		<td data-name="created_at"<?php echo $vw_ads_payment_detail->created_at->CellAttributes() ?>>
<span id="el<?php echo $vw_ads_payment_detail_list->RowCnt ?>_vw_ads_payment_detail_created_at" class="vw_ads_payment_detail_created_at">
<span<?php echo $vw_ads_payment_detail->created_at->ViewAttributes() ?>>
<?php echo $vw_ads_payment_detail->created_at->ListViewValue() ?></span>
</span>
<a id="<?php echo $vw_ads_payment_detail_list->PageObjName . "_row_" . $vw_ads_payment_detail_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vw_ads_payment_detail->ad_id->Visible) { // ad_id ?>
		<td data-name="ad_id"<?php echo $vw_ads_payment_detail->ad_id->CellAttributes() ?>>
<span id="el<?php echo $vw_ads_payment_detail_list->RowCnt ?>_vw_ads_payment_detail_ad_id" class="vw_ads_payment_detail_ad_id">
<span<?php echo $vw_ads_payment_detail->ad_id->ViewAttributes() ?>>
<?php echo $vw_ads_payment_detail->ad_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vw_ads_payment_detail->amount->Visible) { // amount ?>
		<td data-name="amount"<?php echo $vw_ads_payment_detail->amount->CellAttributes() ?>>
<span id="el<?php echo $vw_ads_payment_detail_list->RowCnt ?>_vw_ads_payment_detail_amount" class="vw_ads_payment_detail_amount">
<span<?php echo $vw_ads_payment_detail->amount->ViewAttributes() ?>>
<?php echo $vw_ads_payment_detail->amount->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vw_ads_payment_detail->package_id->Visible) { // package_id ?>
		<td data-name="package_id"<?php echo $vw_ads_payment_detail->package_id->CellAttributes() ?>>
<span id="el<?php echo $vw_ads_payment_detail_list->RowCnt ?>_vw_ads_payment_detail_package_id" class="vw_ads_payment_detail_package_id">
<span<?php echo $vw_ads_payment_detail->package_id->ViewAttributes() ?>>
<?php echo $vw_ads_payment_detail->package_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vw_ads_payment_detail->pay_method_id->Visible) { // pay_method_id ?>
		<td data-name="pay_method_id"<?php echo $vw_ads_payment_detail->pay_method_id->CellAttributes() ?>>
<span id="el<?php echo $vw_ads_payment_detail_list->RowCnt ?>_vw_ads_payment_detail_pay_method_id" class="vw_ads_payment_detail_pay_method_id">
<span<?php echo $vw_ads_payment_detail->pay_method_id->ViewAttributes() ?>>
<?php echo $vw_ads_payment_detail->pay_method_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vw_ads_payment_detail->bank_id->Visible) { // bank_id ?>
		<td data-name="bank_id"<?php echo $vw_ads_payment_detail->bank_id->CellAttributes() ?>>
<span id="el<?php echo $vw_ads_payment_detail_list->RowCnt ?>_vw_ads_payment_detail_bank_id" class="vw_ads_payment_detail_bank_id">
<span<?php echo $vw_ads_payment_detail->bank_id->ViewAttributes() ?>>
<?php echo $vw_ads_payment_detail->bank_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vw_ads_payment_detail->transaction_id->Visible) { // transaction_id ?>
		<td data-name="transaction_id"<?php echo $vw_ads_payment_detail->transaction_id->CellAttributes() ?>>
<span id="el<?php echo $vw_ads_payment_detail_list->RowCnt ?>_vw_ads_payment_detail_transaction_id" class="vw_ads_payment_detail_transaction_id">
<span<?php echo $vw_ads_payment_detail->transaction_id->ViewAttributes() ?>>
<?php echo $vw_ads_payment_detail->transaction_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vw_ads_payment_detail->order_reference_id->Visible) { // order_reference_id ?>
		<td data-name="order_reference_id"<?php echo $vw_ads_payment_detail->order_reference_id->CellAttributes() ?>>
<span id="el<?php echo $vw_ads_payment_detail_list->RowCnt ?>_vw_ads_payment_detail_order_reference_id" class="vw_ads_payment_detail_order_reference_id">
<span<?php echo $vw_ads_payment_detail->order_reference_id->ViewAttributes() ?>>
<?php echo $vw_ads_payment_detail->order_reference_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$vw_ads_payment_detail_list->ListOptions->Render("body", "right", $vw_ads_payment_detail_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vw_ads_payment_detail->CurrentAction <> "gridadd")
		$vw_ads_payment_detail_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vw_ads_payment_detail->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vw_ads_payment_detail_list->Recordset)
	$vw_ads_payment_detail_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($vw_ads_payment_detail->CurrentAction <> "gridadd" && $vw_ads_payment_detail->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($vw_ads_payment_detail_list->Pager)) $vw_ads_payment_detail_list->Pager = new cPrevNextPager($vw_ads_payment_detail_list->StartRec, $vw_ads_payment_detail_list->DisplayRecs, $vw_ads_payment_detail_list->TotalRecs) ?>
<?php if ($vw_ads_payment_detail_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($vw_ads_payment_detail_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $vw_ads_payment_detail_list->PageUrl() ?>start=<?php echo $vw_ads_payment_detail_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($vw_ads_payment_detail_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $vw_ads_payment_detail_list->PageUrl() ?>start=<?php echo $vw_ads_payment_detail_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $vw_ads_payment_detail_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($vw_ads_payment_detail_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $vw_ads_payment_detail_list->PageUrl() ?>start=<?php echo $vw_ads_payment_detail_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($vw_ads_payment_detail_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $vw_ads_payment_detail_list->PageUrl() ?>start=<?php echo $vw_ads_payment_detail_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $vw_ads_payment_detail_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vw_ads_payment_detail_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vw_ads_payment_detail_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vw_ads_payment_detail_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vw_ads_payment_detail_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($vw_ads_payment_detail_list->TotalRecs == 0 && $vw_ads_payment_detail->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vw_ads_payment_detail_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fvw_ads_payment_detaillistsrch.Init();
fvw_ads_payment_detaillistsrch.FilterList = <?php echo $vw_ads_payment_detail_list->GetFilterList() ?>;
fvw_ads_payment_detaillist.Init();
</script>
<?php
$vw_ads_payment_detail_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vw_ads_payment_detail_list->Page_Terminate();
?>
