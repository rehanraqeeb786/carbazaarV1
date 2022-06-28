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

$classified_data_view = NULL; // Initialize page object first

class cclassified_data_view extends cclassified_data {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'classified_data';

	// Page object name
	var $PageObjName = 'classified_data_view';

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
				$this->Page_Terminate(ew_GetUrl("classified_datalist.php"));
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
				$sReturnUrl = "classified_datalist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "classified_datalist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "classified_datalist.php"; // Not page request, return to list
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

		// "detail_classified_colors"
		$item = &$option->Add("detail_classified_colors");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("classified_colors", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("classified_colorslist.php?" . EW_TABLE_SHOW_MASTER . "=classified_data&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["classified_colors_grid"] && $GLOBALS["classified_colors_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'classified_colors')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=classified_colors")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "classified_colors";
		}
		if ($GLOBALS["classified_colors_grid"] && $GLOBALS["classified_colors_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'classified_colors')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=classified_colors")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "classified_colors";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'classified_colors');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "classified_colors";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_classified_attributes"
		$item = &$option->Add("detail_classified_attributes");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("classified_attributes", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("classified_attributeslist.php?" . EW_TABLE_SHOW_MASTER . "=classified_data&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["classified_attributes_grid"] && $GLOBALS["classified_attributes_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'classified_attributes')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=classified_attributes")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "classified_attributes";
		}
		if ($GLOBALS["classified_attributes_grid"] && $GLOBALS["classified_attributes_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'classified_attributes')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=classified_attributes")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "classified_attributes";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'classified_attributes');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "classified_attributes";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_classified_faqs"
		$item = &$option->Add("detail_classified_faqs");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("classified_faqs", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("classified_faqslist.php?" . EW_TABLE_SHOW_MASTER . "=classified_data&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["classified_faqs_grid"] && $GLOBALS["classified_faqs_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'classified_faqs')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=classified_faqs")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "classified_faqs";
		}
		if ($GLOBALS["classified_faqs_grid"] && $GLOBALS["classified_faqs_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'classified_faqs')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=classified_faqs")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "classified_faqs";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'classified_faqs');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "classified_faqs";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_classified_pictures"
		$item = &$option->Add("detail_classified_pictures");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("classified_pictures", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("classified_pictureslist.php?" . EW_TABLE_SHOW_MASTER . "=classified_data&fk_ID=" . urlencode(strval($this->ID->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["classified_pictures_grid"] && $GLOBALS["classified_pictures_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'classified_pictures')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=classified_pictures")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "classified_pictures";
		}
		if ($GLOBALS["classified_pictures_grid"] && $GLOBALS["classified_pictures_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'classified_pictures')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=classified_pictures")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "classified_pictures";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'classified_pictures');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "classified_pictures";
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
			if (in_array("classified_colors", $DetailTblVar)) {
				if (!isset($GLOBALS["classified_colors_grid"]))
					$GLOBALS["classified_colors_grid"] = new cclassified_colors_grid;
				if ($GLOBALS["classified_colors_grid"]->DetailView) {
					$GLOBALS["classified_colors_grid"]->CurrentMode = "view";

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
				if ($GLOBALS["classified_attributes_grid"]->DetailView) {
					$GLOBALS["classified_attributes_grid"]->CurrentMode = "view";

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
				if ($GLOBALS["classified_faqs_grid"]->DetailView) {
					$GLOBALS["classified_faqs_grid"]->CurrentMode = "view";

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
				if ($GLOBALS["classified_pictures_grid"]->DetailView) {
					$GLOBALS["classified_pictures_grid"]->CurrentMode = "view";

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
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($classified_data_view)) $classified_data_view = new cclassified_data_view();

// Page init
$classified_data_view->Page_Init();

// Page main
$classified_data_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$classified_data_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fclassified_dataview = new ew_Form("fclassified_dataview", "view");

// Form_CustomValidate event
fclassified_dataview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclassified_dataview.ValidateRequired = true;
<?php } else { ?>
fclassified_dataview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclassified_dataview.Lists["x_car_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataview.Lists["x_car_type"].Options = <?php echo json_encode($classified_data->car_type->Options()) ?>;
fclassified_dataview.Lists["x_car_make_company_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":["x_car_model_id"],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataview.Lists["x_car_model_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataview.Lists["x_transmition"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataview.Lists["x_transmition"].Options = <?php echo json_encode($classified_data->transmition->Options()) ?>;
fclassified_dataview.Lists["x_fuel_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataview.Lists["x_fuel_type"].Options = <?php echo json_encode($classified_data->fuel_type->Options()) ?>;
fclassified_dataview.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_dataview.Lists["x_status"].Options = <?php echo json_encode($classified_data->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $classified_data_view->ExportOptions->Render("body") ?>
<?php
	foreach ($classified_data_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $classified_data_view->ShowPageHeader(); ?>
<?php
$classified_data_view->ShowMessage();
?>
<form name="fclassified_dataview" id="fclassified_dataview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($classified_data_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $classified_data_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="classified_data">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($classified_data->title->Visible) { // title ?>
	<tr id="r_title">
		<td><span id="elh_classified_data_title"><?php echo $classified_data->title->FldCaption() ?></span></td>
		<td data-name="title"<?php echo $classified_data->title->CellAttributes() ?>>
<span id="el_classified_data_title">
<span<?php echo $classified_data->title->ViewAttributes() ?>>
<?php echo $classified_data->title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($classified_data->car_type->Visible) { // car_type ?>
	<tr id="r_car_type">
		<td><span id="elh_classified_data_car_type"><?php echo $classified_data->car_type->FldCaption() ?></span></td>
		<td data-name="car_type"<?php echo $classified_data->car_type->CellAttributes() ?>>
<span id="el_classified_data_car_type">
<span<?php echo $classified_data->car_type->ViewAttributes() ?>>
<?php echo $classified_data->car_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($classified_data->car_make_company_id->Visible) { // car_make_company_id ?>
	<tr id="r_car_make_company_id">
		<td><span id="elh_classified_data_car_make_company_id"><?php echo $classified_data->car_make_company_id->FldCaption() ?></span></td>
		<td data-name="car_make_company_id"<?php echo $classified_data->car_make_company_id->CellAttributes() ?>>
<span id="el_classified_data_car_make_company_id">
<span<?php echo $classified_data->car_make_company_id->ViewAttributes() ?>>
<?php echo $classified_data->car_make_company_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($classified_data->car_model_id->Visible) { // car_model_id ?>
	<tr id="r_car_model_id">
		<td><span id="elh_classified_data_car_model_id"><?php echo $classified_data->car_model_id->FldCaption() ?></span></td>
		<td data-name="car_model_id"<?php echo $classified_data->car_model_id->CellAttributes() ?>>
<span id="el_classified_data_car_model_id">
<span<?php echo $classified_data->car_model_id->ViewAttributes() ?>>
<?php echo $classified_data->car_model_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($classified_data->price_range->Visible) { // price_range ?>
	<tr id="r_price_range">
		<td><span id="elh_classified_data_price_range"><?php echo $classified_data->price_range->FldCaption() ?></span></td>
		<td data-name="price_range"<?php echo $classified_data->price_range->CellAttributes() ?>>
<span id="el_classified_data_price_range">
<span<?php echo $classified_data->price_range->ViewAttributes() ?>>
<?php echo $classified_data->price_range->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($classified_data->milage_km_liter->Visible) { // milage_km_liter ?>
	<tr id="r_milage_km_liter">
		<td><span id="elh_classified_data_milage_km_liter"><?php echo $classified_data->milage_km_liter->FldCaption() ?></span></td>
		<td data-name="milage_km_liter"<?php echo $classified_data->milage_km_liter->CellAttributes() ?>>
<span id="el_classified_data_milage_km_liter">
<span<?php echo $classified_data->milage_km_liter->ViewAttributes() ?>>
<?php echo $classified_data->milage_km_liter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($classified_data->transmition->Visible) { // transmition ?>
	<tr id="r_transmition">
		<td><span id="elh_classified_data_transmition"><?php echo $classified_data->transmition->FldCaption() ?></span></td>
		<td data-name="transmition"<?php echo $classified_data->transmition->CellAttributes() ?>>
<span id="el_classified_data_transmition">
<span<?php echo $classified_data->transmition->ViewAttributes() ?>>
<?php echo $classified_data->transmition->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($classified_data->fuel_type->Visible) { // fuel_type ?>
	<tr id="r_fuel_type">
		<td><span id="elh_classified_data_fuel_type"><?php echo $classified_data->fuel_type->FldCaption() ?></span></td>
		<td data-name="fuel_type"<?php echo $classified_data->fuel_type->CellAttributes() ?>>
<span id="el_classified_data_fuel_type">
<span<?php echo $classified_data->fuel_type->ViewAttributes() ?>>
<?php echo $classified_data->fuel_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($classified_data->engine_capicity->Visible) { // engine_capicity ?>
	<tr id="r_engine_capicity">
		<td><span id="elh_classified_data_engine_capicity"><?php echo $classified_data->engine_capicity->FldCaption() ?></span></td>
		<td data-name="engine_capicity"<?php echo $classified_data->engine_capicity->CellAttributes() ?>>
<span id="el_classified_data_engine_capicity">
<span<?php echo $classified_data->engine_capicity->ViewAttributes() ?>>
<?php echo $classified_data->engine_capicity->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($classified_data->detail_text->Visible) { // detail_text ?>
	<tr id="r_detail_text">
		<td><span id="elh_classified_data_detail_text"><?php echo $classified_data->detail_text->FldCaption() ?></span></td>
		<td data-name="detail_text"<?php echo $classified_data->detail_text->CellAttributes() ?>>
<span id="el_classified_data_detail_text">
<span<?php echo $classified_data->detail_text->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($classified_data->detail_text->ViewValue)) && $classified_data->detail_text->LinkAttributes() <> "") { ?>
<a<?php echo $classified_data->detail_text->LinkAttributes() ?>><?php echo $classified_data->detail_text->ViewValue ?></a>
<?php } else { ?>
<?php echo $classified_data->detail_text->ViewValue ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($classified_data->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_classified_data_status"><?php echo $classified_data->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $classified_data->status->CellAttributes() ?>>
<span id="el_classified_data_status">
<span<?php echo $classified_data->status->ViewAttributes() ?>>
<?php echo $classified_data->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($classified_data->getCurrentDetailTable() <> "") { ?>
<?php
	$FirstActiveDetailTable = $classified_data_view->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages">
<div class="tabbable" id="classified_data_view_details">
	<ul class="nav<?php echo $classified_data_view->DetailPages->NavStyle() ?>">
<?php
	if (in_array("classified_colors", explode(",", $classified_data->getCurrentDetailTable())) && $classified_colors->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_colors") {
			$FirstActiveDetailTable = "classified_colors";
		}
?>
		<li<?php echo $classified_data_view->DetailPages->TabStyle("classified_colors") ?>><a href="#tab_classified_colors" data-toggle="tab"><?php echo $Language->TablePhrase("classified_colors", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("classified_attributes", explode(",", $classified_data->getCurrentDetailTable())) && $classified_attributes->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_attributes") {
			$FirstActiveDetailTable = "classified_attributes";
		}
?>
		<li<?php echo $classified_data_view->DetailPages->TabStyle("classified_attributes") ?>><a href="#tab_classified_attributes" data-toggle="tab"><?php echo $Language->TablePhrase("classified_attributes", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("classified_faqs", explode(",", $classified_data->getCurrentDetailTable())) && $classified_faqs->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_faqs") {
			$FirstActiveDetailTable = "classified_faqs";
		}
?>
		<li<?php echo $classified_data_view->DetailPages->TabStyle("classified_faqs") ?>><a href="#tab_classified_faqs" data-toggle="tab"><?php echo $Language->TablePhrase("classified_faqs", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("classified_pictures", explode(",", $classified_data->getCurrentDetailTable())) && $classified_pictures->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_pictures") {
			$FirstActiveDetailTable = "classified_pictures";
		}
?>
		<li<?php echo $classified_data_view->DetailPages->TabStyle("classified_pictures") ?>><a href="#tab_classified_pictures" data-toggle="tab"><?php echo $Language->TablePhrase("classified_pictures", "TblCaption") ?></a></li>
<?php
	}
?>
	</ul>
	<div class="tab-content">
<?php
	if (in_array("classified_colors", explode(",", $classified_data->getCurrentDetailTable())) && $classified_colors->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_colors") {
			$FirstActiveDetailTable = "classified_colors";
		}
?>
		<div class="tab-pane<?php echo $classified_data_view->DetailPages->PageStyle("classified_colors") ?>" id="tab_classified_colors">
<?php include_once "classified_colorsgrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("classified_attributes", explode(",", $classified_data->getCurrentDetailTable())) && $classified_attributes->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_attributes") {
			$FirstActiveDetailTable = "classified_attributes";
		}
?>
		<div class="tab-pane<?php echo $classified_data_view->DetailPages->PageStyle("classified_attributes") ?>" id="tab_classified_attributes">
<?php include_once "classified_attributesgrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("classified_faqs", explode(",", $classified_data->getCurrentDetailTable())) && $classified_faqs->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_faqs") {
			$FirstActiveDetailTable = "classified_faqs";
		}
?>
		<div class="tab-pane<?php echo $classified_data_view->DetailPages->PageStyle("classified_faqs") ?>" id="tab_classified_faqs">
<?php include_once "classified_faqsgrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("classified_pictures", explode(",", $classified_data->getCurrentDetailTable())) && $classified_pictures->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "classified_pictures") {
			$FirstActiveDetailTable = "classified_pictures";
		}
?>
		<div class="tab-pane<?php echo $classified_data_view->DetailPages->PageStyle("classified_pictures") ?>" id="tab_classified_pictures">
<?php include_once "classified_picturesgrid.php" ?>
		</div>
<?php } ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fclassified_dataview.Init();
</script>
<?php
$classified_data_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$classified_data_view->Page_Terminate();
?>
