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

$cfg_business_directory_view = NULL; // Initialize page object first

class ccfg_business_directory_view extends ccfg_business_directory {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_business_directory';

	// Page object name
	var $PageObjName = 'cfg_business_directory_view';

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

		// Table object (cfg_business_directory)
		if (!isset($GLOBALS["cfg_business_directory"]) || get_class($GLOBALS["cfg_business_directory"]) == "ccfg_business_directory") {
			$GLOBALS["cfg_business_directory"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_business_directory"];
		}
		$KeyUrl = "";
		if (@$_GET["ID"] <> "") {
			$this->RecKey["ID"] = $_GET["ID"];
			$KeyUrl .= "&amp;ID=" . urlencode($this->RecKey["ID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["ID"] <> "") {
				$this->ID->setQueryStringValue($_GET["ID"]);
				$this->RecKey["ID"] = $this->ID->QueryStringValue;
			} elseif (@$_POST["ID"] <> "") {
				$this->ID->setFormValue($_POST["ID"]);
				$this->RecKey["ID"] = $this->ID->FormValue;
			} else {
				$sReturnUrl = "cfg_business_directorylist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "cfg_business_directorylist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "cfg_business_directorylist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		if (!ew_Empty($this->business_logo_link->Upload->DbValue)) {
			$this->business_logo_link->ViewValue = $this->business_logo_link->Upload->DbValue;
		} else {
			$this->business_logo_link->ViewValue = "";
		}
		$this->business_logo_link->ViewCustomAttributes = "";

		// image_2
		if (!ew_Empty($this->image_2->Upload->DbValue)) {
			$this->image_2->ViewValue = $this->image_2->Upload->DbValue;
		} else {
			$this->image_2->ViewValue = "";
		}
		$this->image_2->ViewCustomAttributes = "";

		// img_3
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_business_directorylist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($cfg_business_directory_view)) $cfg_business_directory_view = new ccfg_business_directory_view();

// Page init
$cfg_business_directory_view->Page_Init();

// Page main
$cfg_business_directory_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_business_directory_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fcfg_business_directoryview = new ew_Form("fcfg_business_directoryview", "view");

// Form_CustomValidate event
fcfg_business_directoryview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_business_directoryview.ValidateRequired = true;
<?php } else { ?>
fcfg_business_directoryview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_business_directoryview.Lists["x_cat_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_cat_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_business_directoryview.Lists["x_province_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_province_name","","",""],"ParentFields":[],"ChildFields":["x_city_id"],"FilterFields":[],"Options":[],"Template":""};
fcfg_business_directoryview.Lists["x_city_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_business_directoryview.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_business_directoryview.Lists["x_status"].Options = <?php echo json_encode($cfg_business_directory->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $cfg_business_directory_view->ExportOptions->Render("body") ?>
<?php
	foreach ($cfg_business_directory_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $cfg_business_directory_view->ShowPageHeader(); ?>
<?php
$cfg_business_directory_view->ShowMessage();
?>
<form name="fcfg_business_directoryview" id="fcfg_business_directoryview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_business_directory_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_business_directory_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_business_directory">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($cfg_business_directory->business_title->Visible) { // business_title ?>
	<tr id="r_business_title">
		<td><span id="elh_cfg_business_directory_business_title"><?php echo $cfg_business_directory->business_title->FldCaption() ?></span></td>
		<td data-name="business_title"<?php echo $cfg_business_directory->business_title->CellAttributes() ?>>
<span id="el_cfg_business_directory_business_title">
<span<?php echo $cfg_business_directory->business_title->ViewAttributes() ?>>
<?php echo $cfg_business_directory->business_title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->cat_id->Visible) { // cat_id ?>
	<tr id="r_cat_id">
		<td><span id="elh_cfg_business_directory_cat_id"><?php echo $cfg_business_directory->cat_id->FldCaption() ?></span></td>
		<td data-name="cat_id"<?php echo $cfg_business_directory->cat_id->CellAttributes() ?>>
<span id="el_cfg_business_directory_cat_id">
<span<?php echo $cfg_business_directory->cat_id->ViewAttributes() ?>>
<?php echo $cfg_business_directory->cat_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->province_id->Visible) { // province_id ?>
	<tr id="r_province_id">
		<td><span id="elh_cfg_business_directory_province_id"><?php echo $cfg_business_directory->province_id->FldCaption() ?></span></td>
		<td data-name="province_id"<?php echo $cfg_business_directory->province_id->CellAttributes() ?>>
<span id="el_cfg_business_directory_province_id">
<span<?php echo $cfg_business_directory->province_id->ViewAttributes() ?>>
<?php echo $cfg_business_directory->province_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->city_id->Visible) { // city_id ?>
	<tr id="r_city_id">
		<td><span id="elh_cfg_business_directory_city_id"><?php echo $cfg_business_directory->city_id->FldCaption() ?></span></td>
		<td data-name="city_id"<?php echo $cfg_business_directory->city_id->CellAttributes() ?>>
<span id="el_cfg_business_directory_city_id">
<span<?php echo $cfg_business_directory->city_id->ViewAttributes() ?>>
<?php echo $cfg_business_directory->city_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->business_address->Visible) { // business_address ?>
	<tr id="r_business_address">
		<td><span id="elh_cfg_business_directory_business_address"><?php echo $cfg_business_directory->business_address->FldCaption() ?></span></td>
		<td data-name="business_address"<?php echo $cfg_business_directory->business_address->CellAttributes() ?>>
<span id="el_cfg_business_directory_business_address">
<span<?php echo $cfg_business_directory->business_address->ViewAttributes() ?>>
<?php echo $cfg_business_directory->business_address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->business_logo_link->Visible) { // business_logo_link ?>
	<tr id="r_business_logo_link">
		<td><span id="elh_cfg_business_directory_business_logo_link"><?php echo $cfg_business_directory->business_logo_link->FldCaption() ?></span></td>
		<td data-name="business_logo_link"<?php echo $cfg_business_directory->business_logo_link->CellAttributes() ?>>
<span id="el_cfg_business_directory_business_logo_link">
<span<?php echo $cfg_business_directory->business_logo_link->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($cfg_business_directory->business_logo_link, $cfg_business_directory->business_logo_link->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->image_2->Visible) { // image_2 ?>
	<tr id="r_image_2">
		<td><span id="elh_cfg_business_directory_image_2"><?php echo $cfg_business_directory->image_2->FldCaption() ?></span></td>
		<td data-name="image_2"<?php echo $cfg_business_directory->image_2->CellAttributes() ?>>
<span id="el_cfg_business_directory_image_2">
<span<?php echo $cfg_business_directory->image_2->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($cfg_business_directory->image_2, $cfg_business_directory->image_2->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->img_3->Visible) { // img_3 ?>
	<tr id="r_img_3">
		<td><span id="elh_cfg_business_directory_img_3"><?php echo $cfg_business_directory->img_3->FldCaption() ?></span></td>
		<td data-name="img_3"<?php echo $cfg_business_directory->img_3->CellAttributes() ?>>
<span id="el_cfg_business_directory_img_3">
<span<?php echo $cfg_business_directory->img_3->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($cfg_business_directory->img_3, $cfg_business_directory->img_3->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->detail_desc->Visible) { // detail_desc ?>
	<tr id="r_detail_desc">
		<td><span id="elh_cfg_business_directory_detail_desc"><?php echo $cfg_business_directory->detail_desc->FldCaption() ?></span></td>
		<td data-name="detail_desc"<?php echo $cfg_business_directory->detail_desc->CellAttributes() ?>>
<span id="el_cfg_business_directory_detail_desc">
<span<?php echo $cfg_business_directory->detail_desc->ViewAttributes() ?>>
<?php echo $cfg_business_directory->detail_desc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->longitute->Visible) { // longitute ?>
	<tr id="r_longitute">
		<td><span id="elh_cfg_business_directory_longitute"><?php echo $cfg_business_directory->longitute->FldCaption() ?></span></td>
		<td data-name="longitute"<?php echo $cfg_business_directory->longitute->CellAttributes() ?>>
<span id="el_cfg_business_directory_longitute">
<span<?php echo $cfg_business_directory->longitute->ViewAttributes() ?>>
<?php echo $cfg_business_directory->longitute->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->latitude->Visible) { // latitude ?>
	<tr id="r_latitude">
		<td><span id="elh_cfg_business_directory_latitude"><?php echo $cfg_business_directory->latitude->FldCaption() ?></span></td>
		<td data-name="latitude"<?php echo $cfg_business_directory->latitude->CellAttributes() ?>>
<span id="el_cfg_business_directory_latitude">
<span<?php echo $cfg_business_directory->latitude->ViewAttributes() ?>>
<?php echo $cfg_business_directory->latitude->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->primary_number->Visible) { // primary_number ?>
	<tr id="r_primary_number">
		<td><span id="elh_cfg_business_directory_primary_number"><?php echo $cfg_business_directory->primary_number->FldCaption() ?></span></td>
		<td data-name="primary_number"<?php echo $cfg_business_directory->primary_number->CellAttributes() ?>>
<span id="el_cfg_business_directory_primary_number">
<span<?php echo $cfg_business_directory->primary_number->ViewAttributes() ?>>
<?php echo $cfg_business_directory->primary_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->secondary_number->Visible) { // secondary_number ?>
	<tr id="r_secondary_number">
		<td><span id="elh_cfg_business_directory_secondary_number"><?php echo $cfg_business_directory->secondary_number->FldCaption() ?></span></td>
		<td data-name="secondary_number"<?php echo $cfg_business_directory->secondary_number->CellAttributes() ?>>
<span id="el_cfg_business_directory_secondary_number">
<span<?php echo $cfg_business_directory->secondary_number->ViewAttributes() ?>>
<?php echo $cfg_business_directory->secondary_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->fb_page->Visible) { // fb_page ?>
	<tr id="r_fb_page">
		<td><span id="elh_cfg_business_directory_fb_page"><?php echo $cfg_business_directory->fb_page->FldCaption() ?></span></td>
		<td data-name="fb_page"<?php echo $cfg_business_directory->fb_page->CellAttributes() ?>>
<span id="el_cfg_business_directory_fb_page">
<span<?php echo $cfg_business_directory->fb_page->ViewAttributes() ?>>
<?php echo $cfg_business_directory->fb_page->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->timings->Visible) { // timings ?>
	<tr id="r_timings">
		<td><span id="elh_cfg_business_directory_timings"><?php echo $cfg_business_directory->timings->FldCaption() ?></span></td>
		<td data-name="timings"<?php echo $cfg_business_directory->timings->CellAttributes() ?>>
<span id="el_cfg_business_directory_timings">
<span<?php echo $cfg_business_directory->timings->ViewAttributes() ?>>
<?php echo $cfg_business_directory->timings->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->website->Visible) { // website ?>
	<tr id="r_website">
		<td><span id="elh_cfg_business_directory_website"><?php echo $cfg_business_directory->website->FldCaption() ?></span></td>
		<td data-name="website"<?php echo $cfg_business_directory->website->CellAttributes() ?>>
<span id="el_cfg_business_directory_website">
<span<?php echo $cfg_business_directory->website->ViewAttributes() ?>>
<?php echo $cfg_business_directory->website->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_business_directory->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_cfg_business_directory_status"><?php echo $cfg_business_directory->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $cfg_business_directory->status->CellAttributes() ?>>
<span id="el_cfg_business_directory_status">
<span<?php echo $cfg_business_directory->status->ViewAttributes() ?>>
<?php echo $cfg_business_directory->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fcfg_business_directoryview.Init();
</script>
<?php
$cfg_business_directory_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_business_directory_view->Page_Terminate();
?>
