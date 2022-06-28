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

$dealers_showroom_view = NULL; // Initialize page object first

class cdealers_showroom_view extends cdealers_showroom {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'dealers_showroom';

	// Page object name
	var $PageObjName = 'dealers_showroom_view';

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
				$this->Page_Terminate(ew_GetUrl("dealers_showroomlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
				$sReturnUrl = "dealers_showroomlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "dealers_showroomlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "dealers_showroomlist.php"; // Not page request, return to list
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

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("dealers_showroomlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($dealers_showroom_view)) $dealers_showroom_view = new cdealers_showroom_view();

// Page init
$dealers_showroom_view->Page_Init();

// Page main
$dealers_showroom_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dealers_showroom_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fdealers_showroomview = new ew_Form("fdealers_showroomview", "view");

// Form_CustomValidate event
fdealers_showroomview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdealers_showroomview.ValidateRequired = true;
<?php } else { ?>
fdealers_showroomview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdealers_showroomview.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdealers_showroomview.Lists["x_status"].Options = <?php echo json_encode($dealers_showroom->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $dealers_showroom_view->ExportOptions->Render("body") ?>
<?php
	foreach ($dealers_showroom_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $dealers_showroom_view->ShowPageHeader(); ?>
<?php
$dealers_showroom_view->ShowMessage();
?>
<form name="fdealers_showroomview" id="fdealers_showroomview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dealers_showroom_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dealers_showroom_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dealers_showroom">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($dealers_showroom->ID->Visible) { // ID ?>
	<tr id="r_ID">
		<td><span id="elh_dealers_showroom_ID"><?php echo $dealers_showroom->ID->FldCaption() ?></span></td>
		<td data-name="ID"<?php echo $dealers_showroom->ID->CellAttributes() ?>>
<span id="el_dealers_showroom_ID">
<span<?php echo $dealers_showroom->ID->ViewAttributes() ?>>
<?php echo $dealers_showroom->ID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->customer_id->Visible) { // customer_id ?>
	<tr id="r_customer_id">
		<td><span id="elh_dealers_showroom_customer_id"><?php echo $dealers_showroom->customer_id->FldCaption() ?></span></td>
		<td data-name="customer_id"<?php echo $dealers_showroom->customer_id->CellAttributes() ?>>
<span id="el_dealers_showroom_customer_id">
<span<?php echo $dealers_showroom->customer_id->ViewAttributes() ?>>
<?php echo $dealers_showroom->customer_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->showroom_name->Visible) { // showroom_name ?>
	<tr id="r_showroom_name">
		<td><span id="elh_dealers_showroom_showroom_name"><?php echo $dealers_showroom->showroom_name->FldCaption() ?></span></td>
		<td data-name="showroom_name"<?php echo $dealers_showroom->showroom_name->CellAttributes() ?>>
<span id="el_dealers_showroom_showroom_name">
<span<?php echo $dealers_showroom->showroom_name->ViewAttributes() ?>>
<?php echo $dealers_showroom->showroom_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->showroom_address->Visible) { // showroom_address ?>
	<tr id="r_showroom_address">
		<td><span id="elh_dealers_showroom_showroom_address"><?php echo $dealers_showroom->showroom_address->FldCaption() ?></span></td>
		<td data-name="showroom_address"<?php echo $dealers_showroom->showroom_address->CellAttributes() ?>>
<span id="el_dealers_showroom_showroom_address">
<span<?php echo $dealers_showroom->showroom_address->ViewAttributes() ?>>
<?php echo $dealers_showroom->showroom_address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->city_id->Visible) { // city_id ?>
	<tr id="r_city_id">
		<td><span id="elh_dealers_showroom_city_id"><?php echo $dealers_showroom->city_id->FldCaption() ?></span></td>
		<td data-name="city_id"<?php echo $dealers_showroom->city_id->CellAttributes() ?>>
<span id="el_dealers_showroom_city_id">
<span<?php echo $dealers_showroom->city_id->ViewAttributes() ?>>
<?php echo $dealers_showroom->city_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->owner_name->Visible) { // owner_name ?>
	<tr id="r_owner_name">
		<td><span id="elh_dealers_showroom_owner_name"><?php echo $dealers_showroom->owner_name->FldCaption() ?></span></td>
		<td data-name="owner_name"<?php echo $dealers_showroom->owner_name->CellAttributes() ?>>
<span id="el_dealers_showroom_owner_name">
<span<?php echo $dealers_showroom->owner_name->ViewAttributes() ?>>
<?php echo $dealers_showroom->owner_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->contact_1->Visible) { // contact_1 ?>
	<tr id="r_contact_1">
		<td><span id="elh_dealers_showroom_contact_1"><?php echo $dealers_showroom->contact_1->FldCaption() ?></span></td>
		<td data-name="contact_1"<?php echo $dealers_showroom->contact_1->CellAttributes() ?>>
<span id="el_dealers_showroom_contact_1">
<span<?php echo $dealers_showroom->contact_1->ViewAttributes() ?>>
<?php echo $dealers_showroom->contact_1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->contat_2->Visible) { // contat_2 ?>
	<tr id="r_contat_2">
		<td><span id="elh_dealers_showroom_contat_2"><?php echo $dealers_showroom->contat_2->FldCaption() ?></span></td>
		<td data-name="contat_2"<?php echo $dealers_showroom->contat_2->CellAttributes() ?>>
<span id="el_dealers_showroom_contat_2">
<span<?php echo $dealers_showroom->contat_2->ViewAttributes() ?>>
<?php echo $dealers_showroom->contat_2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->showroom_logo_link->Visible) { // showroom_logo_link ?>
	<tr id="r_showroom_logo_link">
		<td><span id="elh_dealers_showroom_showroom_logo_link"><?php echo $dealers_showroom->showroom_logo_link->FldCaption() ?></span></td>
		<td data-name="showroom_logo_link"<?php echo $dealers_showroom->showroom_logo_link->CellAttributes() ?>>
<span id="el_dealers_showroom_showroom_logo_link">
<span<?php echo $dealers_showroom->showroom_logo_link->ViewAttributes() ?>>
<?php echo $dealers_showroom->showroom_logo_link->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_dealers_showroom_status"><?php echo $dealers_showroom->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $dealers_showroom->status->CellAttributes() ?>>
<span id="el_dealers_showroom_status">
<span<?php echo $dealers_showroom->status->ViewAttributes() ?>>
<?php echo $dealers_showroom->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->created_at->Visible) { // created_at ?>
	<tr id="r_created_at">
		<td><span id="elh_dealers_showroom_created_at"><?php echo $dealers_showroom->created_at->FldCaption() ?></span></td>
		<td data-name="created_at"<?php echo $dealers_showroom->created_at->CellAttributes() ?>>
<span id="el_dealers_showroom_created_at">
<span<?php echo $dealers_showroom->created_at->ViewAttributes() ?>>
<?php echo $dealers_showroom->created_at->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dealers_showroom->updated_at->Visible) { // updated_at ?>
	<tr id="r_updated_at">
		<td><span id="elh_dealers_showroom_updated_at"><?php echo $dealers_showroom->updated_at->FldCaption() ?></span></td>
		<td data-name="updated_at"<?php echo $dealers_showroom->updated_at->CellAttributes() ?>>
<span id="el_dealers_showroom_updated_at">
<span<?php echo $dealers_showroom->updated_at->ViewAttributes() ?>>
<?php echo $dealers_showroom->updated_at->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fdealers_showroomview.Init();
</script>
<?php
$dealers_showroom_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dealers_showroom_view->Page_Terminate();
?>
