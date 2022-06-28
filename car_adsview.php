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

$car_ads_view = NULL; // Initialize page object first

class ccar_ads_view extends ccar_ads {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'car_ads';

	// Page object name
	var $PageObjName = 'car_ads_view';

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
				$this->Page_Terminate(ew_GetUrl("car_adslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
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
	var $DetailPages; // Detail pages object

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
				$sReturnUrl = "car_adslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "car_adslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "car_adslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
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
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_ad_features"
		$item = &$option->Add("detail_ad_features");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("ad_features", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("ad_featureslist.php?" . EW_TABLE_SHOW_MASTER . "=car_ads&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["ad_features_grid"] && $GLOBALS["ad_features_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'ad_features')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=ad_features")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "ad_features";
		}
		if ($GLOBALS["ad_features_grid"] && $GLOBALS["ad_features_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'ad_features')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=ad_features")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "ad_features";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'ad_features');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "ad_features";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_ad_pictures"
		$item = &$option->Add("detail_ad_pictures");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("ad_pictures", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("ad_pictureslist.php?" . EW_TABLE_SHOW_MASTER . "=car_ads&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["ad_pictures_grid"] && $GLOBALS["ad_pictures_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'ad_pictures')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=ad_pictures")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "ad_pictures";
		}
		if ($GLOBALS["ad_pictures_grid"] && $GLOBALS["ad_pictures_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'ad_pictures')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=ad_pictures")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "ad_pictures";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'ad_pictures');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "ad_pictures";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
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
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
				if ($GLOBALS["ad_features_grid"]->DetailView) {
					$GLOBALS["ad_features_grid"]->CurrentMode = "view";

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
				if ($GLOBALS["ad_pictures_grid"]->DetailView) {
					$GLOBALS["ad_pictures_grid"]->CurrentMode = "view";

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
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($car_ads_view)) $car_ads_view = new ccar_ads_view();

// Page init
$car_ads_view->Page_Init();

// Page main
$car_ads_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$car_ads_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fcar_adsview = new ew_Form("fcar_adsview", "view");

// Form_CustomValidate event
fcar_adsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcar_adsview.ValidateRequired = true;
<?php } else { ?>
fcar_adsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcar_adsview.Lists["x_user_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","x__email","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_year_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_year","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_registered_in"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_city_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_make_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_model_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_version_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_color_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_engine_type_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_transmition"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_transmition"].Options = <?php echo json_encode($car_ads->transmition->Options()) ?>;
fcar_adsview.Lists["x_assembly"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_assembly"].Options = <?php echo json_encode($car_ads->assembly->Options()) ?>;
fcar_adsview.Lists["x_allow_whatsapp"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_allow_whatsapp"].Options = <?php echo json_encode($car_ads->allow_whatsapp->Options()) ?>;
fcar_adsview.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adsview.Lists["x_status"].Options = <?php echo json_encode($car_ads->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $car_ads_view->ExportOptions->Render("body") ?>
<?php
	foreach ($car_ads_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $car_ads_view->ShowPageHeader(); ?>
<?php
$car_ads_view->ShowMessage();
?>
<form name="fcar_adsview" id="fcar_adsview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($car_ads_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $car_ads_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="car_ads">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($car_ads->user_id->Visible) { // user_id ?>
	<tr id="r_user_id">
		<td><span id="elh_car_ads_user_id"><?php echo $car_ads->user_id->FldCaption() ?></span></td>
		<td data-name="user_id"<?php echo $car_ads->user_id->CellAttributes() ?>>
<span id="el_car_ads_user_id">
<span<?php echo $car_ads->user_id->ViewAttributes() ?>>
<?php echo $car_ads->user_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->ad_title->Visible) { // ad_title ?>
	<tr id="r_ad_title">
		<td><span id="elh_car_ads_ad_title"><?php echo $car_ads->ad_title->FldCaption() ?></span></td>
		<td data-name="ad_title"<?php echo $car_ads->ad_title->CellAttributes() ?>>
<span id="el_car_ads_ad_title">
<span<?php echo $car_ads->ad_title->ViewAttributes() ?>>
<?php echo $car_ads->ad_title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->year_id->Visible) { // year_id ?>
	<tr id="r_year_id">
		<td><span id="elh_car_ads_year_id"><?php echo $car_ads->year_id->FldCaption() ?></span></td>
		<td data-name="year_id"<?php echo $car_ads->year_id->CellAttributes() ?>>
<span id="el_car_ads_year_id">
<span<?php echo $car_ads->year_id->ViewAttributes() ?>>
<?php echo $car_ads->year_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->registered_in->Visible) { // registered_in ?>
	<tr id="r_registered_in">
		<td><span id="elh_car_ads_registered_in"><?php echo $car_ads->registered_in->FldCaption() ?></span></td>
		<td data-name="registered_in"<?php echo $car_ads->registered_in->CellAttributes() ?>>
<span id="el_car_ads_registered_in">
<span<?php echo $car_ads->registered_in->ViewAttributes() ?>>
<?php echo $car_ads->registered_in->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->city_id->Visible) { // city_id ?>
	<tr id="r_city_id">
		<td><span id="elh_car_ads_city_id"><?php echo $car_ads->city_id->FldCaption() ?></span></td>
		<td data-name="city_id"<?php echo $car_ads->city_id->CellAttributes() ?>>
<span id="el_car_ads_city_id">
<span<?php echo $car_ads->city_id->ViewAttributes() ?>>
<?php echo $car_ads->city_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->make_id->Visible) { // make_id ?>
	<tr id="r_make_id">
		<td><span id="elh_car_ads_make_id"><?php echo $car_ads->make_id->FldCaption() ?></span></td>
		<td data-name="make_id"<?php echo $car_ads->make_id->CellAttributes() ?>>
<span id="el_car_ads_make_id">
<span<?php echo $car_ads->make_id->ViewAttributes() ?>>
<?php echo $car_ads->make_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->model_id->Visible) { // model_id ?>
	<tr id="r_model_id">
		<td><span id="elh_car_ads_model_id"><?php echo $car_ads->model_id->FldCaption() ?></span></td>
		<td data-name="model_id"<?php echo $car_ads->model_id->CellAttributes() ?>>
<span id="el_car_ads_model_id">
<span<?php echo $car_ads->model_id->ViewAttributes() ?>>
<?php echo $car_ads->model_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->version_id->Visible) { // version_id ?>
	<tr id="r_version_id">
		<td><span id="elh_car_ads_version_id"><?php echo $car_ads->version_id->FldCaption() ?></span></td>
		<td data-name="version_id"<?php echo $car_ads->version_id->CellAttributes() ?>>
<span id="el_car_ads_version_id">
<span<?php echo $car_ads->version_id->ViewAttributes() ?>>
<?php echo $car_ads->version_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->milage->Visible) { // milage ?>
	<tr id="r_milage">
		<td><span id="elh_car_ads_milage"><?php echo $car_ads->milage->FldCaption() ?></span></td>
		<td data-name="milage"<?php echo $car_ads->milage->CellAttributes() ?>>
<span id="el_car_ads_milage">
<span<?php echo $car_ads->milage->ViewAttributes() ?>>
<?php echo $car_ads->milage->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->color_id->Visible) { // color_id ?>
	<tr id="r_color_id">
		<td><span id="elh_car_ads_color_id"><?php echo $car_ads->color_id->FldCaption() ?></span></td>
		<td data-name="color_id"<?php echo $car_ads->color_id->CellAttributes() ?>>
<span id="el_car_ads_color_id">
<span<?php echo $car_ads->color_id->ViewAttributes() ?>>
<?php echo $car_ads->color_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->demand_price->Visible) { // demand_price ?>
	<tr id="r_demand_price">
		<td><span id="elh_car_ads_demand_price"><?php echo $car_ads->demand_price->FldCaption() ?></span></td>
		<td data-name="demand_price"<?php echo $car_ads->demand_price->CellAttributes() ?>>
<span id="el_car_ads_demand_price">
<span<?php echo $car_ads->demand_price->ViewAttributes() ?>>
<?php echo $car_ads->demand_price->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->details->Visible) { // details ?>
	<tr id="r_details">
		<td><span id="elh_car_ads_details"><?php echo $car_ads->details->FldCaption() ?></span></td>
		<td data-name="details"<?php echo $car_ads->details->CellAttributes() ?>>
<span id="el_car_ads_details">
<span<?php echo $car_ads->details->ViewAttributes() ?>>
<?php echo $car_ads->details->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->engine_type_id->Visible) { // engine_type_id ?>
	<tr id="r_engine_type_id">
		<td><span id="elh_car_ads_engine_type_id"><?php echo $car_ads->engine_type_id->FldCaption() ?></span></td>
		<td data-name="engine_type_id"<?php echo $car_ads->engine_type_id->CellAttributes() ?>>
<span id="el_car_ads_engine_type_id">
<span<?php echo $car_ads->engine_type_id->ViewAttributes() ?>>
<?php echo $car_ads->engine_type_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->engine_capicity->Visible) { // engine_capicity ?>
	<tr id="r_engine_capicity">
		<td><span id="elh_car_ads_engine_capicity"><?php echo $car_ads->engine_capicity->FldCaption() ?></span></td>
		<td data-name="engine_capicity"<?php echo $car_ads->engine_capicity->CellAttributes() ?>>
<span id="el_car_ads_engine_capicity">
<span<?php echo $car_ads->engine_capicity->ViewAttributes() ?>>
<?php echo $car_ads->engine_capicity->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->transmition->Visible) { // transmition ?>
	<tr id="r_transmition">
		<td><span id="elh_car_ads_transmition"><?php echo $car_ads->transmition->FldCaption() ?></span></td>
		<td data-name="transmition"<?php echo $car_ads->transmition->CellAttributes() ?>>
<span id="el_car_ads_transmition">
<span<?php echo $car_ads->transmition->ViewAttributes() ?>>
<?php echo $car_ads->transmition->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->assembly->Visible) { // assembly ?>
	<tr id="r_assembly">
		<td><span id="elh_car_ads_assembly"><?php echo $car_ads->assembly->FldCaption() ?></span></td>
		<td data-name="assembly"<?php echo $car_ads->assembly->CellAttributes() ?>>
<span id="el_car_ads_assembly">
<span<?php echo $car_ads->assembly->ViewAttributes() ?>>
<?php echo $car_ads->assembly->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->mobile_number->Visible) { // mobile_number ?>
	<tr id="r_mobile_number">
		<td><span id="elh_car_ads_mobile_number"><?php echo $car_ads->mobile_number->FldCaption() ?></span></td>
		<td data-name="mobile_number"<?php echo $car_ads->mobile_number->CellAttributes() ?>>
<span id="el_car_ads_mobile_number">
<span<?php echo $car_ads->mobile_number->ViewAttributes() ?>>
<?php echo $car_ads->mobile_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->secondary_number->Visible) { // secondary_number ?>
	<tr id="r_secondary_number">
		<td><span id="elh_car_ads_secondary_number"><?php echo $car_ads->secondary_number->FldCaption() ?></span></td>
		<td data-name="secondary_number"<?php echo $car_ads->secondary_number->CellAttributes() ?>>
<span id="el_car_ads_secondary_number">
<span<?php echo $car_ads->secondary_number->ViewAttributes() ?>>
<?php echo $car_ads->secondary_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_car_ads__email"><?php echo $car_ads->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $car_ads->_email->CellAttributes() ?>>
<span id="el_car_ads__email">
<span<?php echo $car_ads->_email->ViewAttributes() ?>>
<?php echo $car_ads->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->name->Visible) { // name ?>
	<tr id="r_name">
		<td><span id="elh_car_ads_name"><?php echo $car_ads->name->FldCaption() ?></span></td>
		<td data-name="name"<?php echo $car_ads->name->CellAttributes() ?>>
<span id="el_car_ads_name">
<span<?php echo $car_ads->name->ViewAttributes() ?>>
<?php echo $car_ads->name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->address->Visible) { // address ?>
	<tr id="r_address">
		<td><span id="elh_car_ads_address"><?php echo $car_ads->address->FldCaption() ?></span></td>
		<td data-name="address"<?php echo $car_ads->address->CellAttributes() ?>>
<span id="el_car_ads_address">
<span<?php echo $car_ads->address->ViewAttributes() ?>>
<?php echo $car_ads->address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->allow_whatsapp->Visible) { // allow_whatsapp ?>
	<tr id="r_allow_whatsapp">
		<td><span id="elh_car_ads_allow_whatsapp"><?php echo $car_ads->allow_whatsapp->FldCaption() ?></span></td>
		<td data-name="allow_whatsapp"<?php echo $car_ads->allow_whatsapp->CellAttributes() ?>>
<span id="el_car_ads_allow_whatsapp">
<span<?php echo $car_ads->allow_whatsapp->ViewAttributes() ?>>
<?php echo $car_ads->allow_whatsapp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($car_ads->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_car_ads_status"><?php echo $car_ads->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $car_ads->status->CellAttributes() ?>>
<span id="el_car_ads_status">
<span<?php echo $car_ads->status->ViewAttributes() ?>>
<?php echo $car_ads->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($car_ads->getCurrentDetailTable() <> "") { ?>
<?php
	$FirstActiveDetailTable = $car_ads_view->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages">
<div class="tabbable" id="car_ads_view_details">
	<ul class="nav<?php echo $car_ads_view->DetailPages->NavStyle() ?>">
<?php
	if (in_array("ad_features", explode(",", $car_ads->getCurrentDetailTable())) && $ad_features->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "ad_features") {
			$FirstActiveDetailTable = "ad_features";
		}
?>
		<li<?php echo $car_ads_view->DetailPages->TabStyle("ad_features") ?>><a href="#tab_ad_features" data-toggle="tab"><?php echo $Language->TablePhrase("ad_features", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("ad_pictures", explode(",", $car_ads->getCurrentDetailTable())) && $ad_pictures->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "ad_pictures") {
			$FirstActiveDetailTable = "ad_pictures";
		}
?>
		<li<?php echo $car_ads_view->DetailPages->TabStyle("ad_pictures") ?>><a href="#tab_ad_pictures" data-toggle="tab"><?php echo $Language->TablePhrase("ad_pictures", "TblCaption") ?></a></li>
<?php
	}
?>
	</ul>
	<div class="tab-content">
<?php
	if (in_array("ad_features", explode(",", $car_ads->getCurrentDetailTable())) && $ad_features->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "ad_features") {
			$FirstActiveDetailTable = "ad_features";
		}
?>
		<div class="tab-pane<?php echo $car_ads_view->DetailPages->PageStyle("ad_features") ?>" id="tab_ad_features">
<?php include_once "ad_featuresgrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("ad_pictures", explode(",", $car_ads->getCurrentDetailTable())) && $ad_pictures->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "ad_pictures") {
			$FirstActiveDetailTable = "ad_pictures";
		}
?>
		<div class="tab-pane<?php echo $car_ads_view->DetailPages->PageStyle("ad_pictures") ?>" id="tab_ad_pictures">
<?php include_once "ad_picturesgrid.php" ?>
		</div>
<?php } ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcar_adsview.Init();
</script>
<?php
$car_ads_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$car_ads_view->Page_Terminate();
?>
