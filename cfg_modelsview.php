<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_modelsinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_models_view = NULL; // Initialize page object first

class ccfg_models_view extends ccfg_models {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_models';

	// Page object name
	var $PageObjName = 'cfg_models_view';

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

		// Table object (cfg_models)
		if (!isset($GLOBALS["cfg_models"]) || get_class($GLOBALS["cfg_models"]) == "ccfg_models") {
			$GLOBALS["cfg_models"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_models"];
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
			define("EW_TABLE_NAME", 'cfg_models', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_modelslist.php"));
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
		global $EW_EXPORT, $cfg_models;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_models);
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
				$sReturnUrl = "cfg_modelslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "cfg_modelslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "cfg_modelslist.php"; // Not page request, return to list
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
		$this->title->setDbValue($rs->fields('title'));
		$this->make_company_id->setDbValue($rs->fields('make_company_id'));
		$this->main_pic_link->Upload->DbValue = $rs->fields('main_pic_link');
		$this->main_pic_link->CurrentValue = $this->main_pic_link->Upload->DbValue;
		$this->pic_2->Upload->DbValue = $rs->fields('pic_2');
		$this->pic_2->CurrentValue = $this->pic_2->Upload->DbValue;
		$this->pic_3->Upload->DbValue = $rs->fields('pic_3');
		$this->pic_3->CurrentValue = $this->pic_3->Upload->DbValue;
		$this->pic_4->Upload->DbValue = $rs->fields('pic_4');
		$this->pic_4->CurrentValue = $this->pic_4->Upload->DbValue;
		$this->pic_5->Upload->DbValue = $rs->fields('pic_5');
		$this->pic_5->CurrentValue = $this->pic_5->Upload->DbValue;
		$this->details->setDbValue($rs->fields('details'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->title->DbValue = $row['title'];
		$this->make_company_id->DbValue = $row['make_company_id'];
		$this->main_pic_link->Upload->DbValue = $row['main_pic_link'];
		$this->pic_2->Upload->DbValue = $row['pic_2'];
		$this->pic_3->Upload->DbValue = $row['pic_3'];
		$this->pic_4->Upload->DbValue = $row['pic_4'];
		$this->pic_5->Upload->DbValue = $row['pic_5'];
		$this->details->DbValue = $row['details'];
		$this->status->DbValue = $row['status'];
		$this->created_at->DbValue = $row['created_at'];
		$this->updated_at->DbValue = $row['updated_at'];
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
		// title
		// make_company_id
		// main_pic_link
		// pic_2
		// pic_3
		// pic_4
		// pic_5
		// details
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// make_company_id
		if (strval($this->make_company_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->make_company_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->make_company_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->make_company_id->ViewValue = $this->make_company_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->make_company_id->ViewValue = $this->make_company_id->CurrentValue;
			}
		} else {
			$this->make_company_id->ViewValue = NULL;
		}
		$this->make_company_id->ViewCustomAttributes = "";

		// main_pic_link
		if (!ew_Empty($this->main_pic_link->Upload->DbValue)) {
			$this->main_pic_link->ViewValue = $this->main_pic_link->Upload->DbValue;
		} else {
			$this->main_pic_link->ViewValue = "";
		}
		$this->main_pic_link->ViewCustomAttributes = "";

		// pic_2
		if (!ew_Empty($this->pic_2->Upload->DbValue)) {
			$this->pic_2->ViewValue = $this->pic_2->Upload->DbValue;
		} else {
			$this->pic_2->ViewValue = "";
		}
		$this->pic_2->ViewCustomAttributes = "";

		// pic_3
		if (!ew_Empty($this->pic_3->Upload->DbValue)) {
			$this->pic_3->ViewValue = $this->pic_3->Upload->DbValue;
		} else {
			$this->pic_3->ViewValue = "";
		}
		$this->pic_3->ViewCustomAttributes = "";

		// pic_4
		if (!ew_Empty($this->pic_4->Upload->DbValue)) {
			$this->pic_4->ViewValue = $this->pic_4->Upload->DbValue;
		} else {
			$this->pic_4->ViewValue = "";
		}
		$this->pic_4->ViewCustomAttributes = "";

		// pic_5
		if (!ew_Empty($this->pic_5->Upload->DbValue)) {
			$this->pic_5->ViewValue = $this->pic_5->Upload->DbValue;
		} else {
			$this->pic_5->ViewValue = "";
		}
		$this->pic_5->ViewCustomAttributes = "";

		// details
		$this->details->ViewValue = $this->details->CurrentValue;
		$this->details->ViewCustomAttributes = "";

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

			// make_company_id
			$this->make_company_id->LinkCustomAttributes = "";
			$this->make_company_id->HrefValue = "";
			$this->make_company_id->TooltipValue = "";

			// main_pic_link
			$this->main_pic_link->LinkCustomAttributes = "";
			$this->main_pic_link->HrefValue = "";
			$this->main_pic_link->HrefValue2 = $this->main_pic_link->UploadPath . $this->main_pic_link->Upload->DbValue;
			$this->main_pic_link->TooltipValue = "";

			// pic_2
			$this->pic_2->LinkCustomAttributes = "";
			$this->pic_2->HrefValue = "";
			$this->pic_2->HrefValue2 = $this->pic_2->UploadPath . $this->pic_2->Upload->DbValue;
			$this->pic_2->TooltipValue = "";

			// pic_3
			$this->pic_3->LinkCustomAttributes = "";
			$this->pic_3->HrefValue = "";
			$this->pic_3->HrefValue2 = $this->pic_3->UploadPath . $this->pic_3->Upload->DbValue;
			$this->pic_3->TooltipValue = "";

			// pic_4
			$this->pic_4->LinkCustomAttributes = "";
			$this->pic_4->HrefValue = "";
			$this->pic_4->HrefValue2 = $this->pic_4->UploadPath . $this->pic_4->Upload->DbValue;
			$this->pic_4->TooltipValue = "";

			// pic_5
			$this->pic_5->LinkCustomAttributes = "";
			$this->pic_5->HrefValue = "";
			$this->pic_5->HrefValue2 = $this->pic_5->UploadPath . $this->pic_5->Upload->DbValue;
			$this->pic_5->TooltipValue = "";

			// details
			$this->details->LinkCustomAttributes = "";
			$this->details->HrefValue = "";
			$this->details->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_modelslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_models_view)) $cfg_models_view = new ccfg_models_view();

// Page init
$cfg_models_view->Page_Init();

// Page main
$cfg_models_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_models_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fcfg_modelsview = new ew_Form("fcfg_modelsview", "view");

// Form_CustomValidate event
fcfg_modelsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_modelsview.ValidateRequired = true;
<?php } else { ?>
fcfg_modelsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_modelsview.Lists["x_make_company_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_modelsview.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_modelsview.Lists["x_status"].Options = <?php echo json_encode($cfg_models->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $cfg_models_view->ExportOptions->Render("body") ?>
<?php
	foreach ($cfg_models_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $cfg_models_view->ShowPageHeader(); ?>
<?php
$cfg_models_view->ShowMessage();
?>
<form name="fcfg_modelsview" id="fcfg_modelsview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_models_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_models_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_models">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($cfg_models->title->Visible) { // title ?>
	<tr id="r_title">
		<td><span id="elh_cfg_models_title"><?php echo $cfg_models->title->FldCaption() ?></span></td>
		<td data-name="title"<?php echo $cfg_models->title->CellAttributes() ?>>
<span id="el_cfg_models_title">
<span<?php echo $cfg_models->title->ViewAttributes() ?>>
<?php echo $cfg_models->title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_models->make_company_id->Visible) { // make_company_id ?>
	<tr id="r_make_company_id">
		<td><span id="elh_cfg_models_make_company_id"><?php echo $cfg_models->make_company_id->FldCaption() ?></span></td>
		<td data-name="make_company_id"<?php echo $cfg_models->make_company_id->CellAttributes() ?>>
<span id="el_cfg_models_make_company_id">
<span<?php echo $cfg_models->make_company_id->ViewAttributes() ?>>
<?php echo $cfg_models->make_company_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_models->main_pic_link->Visible) { // main_pic_link ?>
	<tr id="r_main_pic_link">
		<td><span id="elh_cfg_models_main_pic_link"><?php echo $cfg_models->main_pic_link->FldCaption() ?></span></td>
		<td data-name="main_pic_link"<?php echo $cfg_models->main_pic_link->CellAttributes() ?>>
<span id="el_cfg_models_main_pic_link">
<span<?php echo $cfg_models->main_pic_link->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($cfg_models->main_pic_link, $cfg_models->main_pic_link->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_models->pic_2->Visible) { // pic_2 ?>
	<tr id="r_pic_2">
		<td><span id="elh_cfg_models_pic_2"><?php echo $cfg_models->pic_2->FldCaption() ?></span></td>
		<td data-name="pic_2"<?php echo $cfg_models->pic_2->CellAttributes() ?>>
<span id="el_cfg_models_pic_2">
<span<?php echo $cfg_models->pic_2->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($cfg_models->pic_2, $cfg_models->pic_2->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_models->pic_3->Visible) { // pic_3 ?>
	<tr id="r_pic_3">
		<td><span id="elh_cfg_models_pic_3"><?php echo $cfg_models->pic_3->FldCaption() ?></span></td>
		<td data-name="pic_3"<?php echo $cfg_models->pic_3->CellAttributes() ?>>
<span id="el_cfg_models_pic_3">
<span<?php echo $cfg_models->pic_3->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($cfg_models->pic_3, $cfg_models->pic_3->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_models->pic_4->Visible) { // pic_4 ?>
	<tr id="r_pic_4">
		<td><span id="elh_cfg_models_pic_4"><?php echo $cfg_models->pic_4->FldCaption() ?></span></td>
		<td data-name="pic_4"<?php echo $cfg_models->pic_4->CellAttributes() ?>>
<span id="el_cfg_models_pic_4">
<span<?php echo $cfg_models->pic_4->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($cfg_models->pic_4, $cfg_models->pic_4->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_models->pic_5->Visible) { // pic_5 ?>
	<tr id="r_pic_5">
		<td><span id="elh_cfg_models_pic_5"><?php echo $cfg_models->pic_5->FldCaption() ?></span></td>
		<td data-name="pic_5"<?php echo $cfg_models->pic_5->CellAttributes() ?>>
<span id="el_cfg_models_pic_5">
<span<?php echo $cfg_models->pic_5->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($cfg_models->pic_5, $cfg_models->pic_5->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_models->details->Visible) { // details ?>
	<tr id="r_details">
		<td><span id="elh_cfg_models_details"><?php echo $cfg_models->details->FldCaption() ?></span></td>
		<td data-name="details"<?php echo $cfg_models->details->CellAttributes() ?>>
<span id="el_cfg_models_details">
<span<?php echo $cfg_models->details->ViewAttributes() ?>>
<?php echo $cfg_models->details->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cfg_models->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_cfg_models_status"><?php echo $cfg_models->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $cfg_models->status->CellAttributes() ?>>
<span id="el_cfg_models_status">
<span<?php echo $cfg_models->status->ViewAttributes() ?>>
<?php echo $cfg_models->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fcfg_modelsview.Init();
</script>
<?php
$cfg_models_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_models_view->Page_Terminate();
?>
