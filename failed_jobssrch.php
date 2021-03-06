<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "failed_jobsinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$failed_jobs_search = NULL; // Initialize page object first

class cfailed_jobs_search extends cfailed_jobs {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'failed_jobs';

	// Page object name
	var $PageObjName = 'failed_jobs_search';

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

		// Table object (failed_jobs)
		if (!isset($GLOBALS["failed_jobs"]) || get_class($GLOBALS["failed_jobs"]) == "cfailed_jobs") {
			$GLOBALS["failed_jobs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["failed_jobs"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'failed_jobs', TRUE);

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
		if (!$Security->CanSearch()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("failed_jobslist.php"));
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
		global $EW_EXPORT, $failed_jobs;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($failed_jobs);
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
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $SearchLabelClass = "col-sm-3 control-label ewLabel";
	var $SearchRightColumnClass = "col-sm-9";

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "failed_jobslist.php" . "?" . $sSrchStr;
						if ($this->IsModal) {
							$row = array();
							$row["url"] = $sSrchStr;
							echo ew_ArrayToJson(array($row));
							$this->Page_Terminate();
							exit();
						} else {
							$this->Page_Terminate($sSrchStr); // Go to list page
						}
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->uuid); // uuid
		$this->BuildSearchUrl($sSrchUrl, $this->connection); // connection
		$this->BuildSearchUrl($sSrchUrl, $this->queue); // queue
		$this->BuildSearchUrl($sSrchUrl, $this->payload); // payload
		$this->BuildSearchUrl($sSrchUrl, $this->exception); // exception
		$this->BuildSearchUrl($sSrchUrl, $this->failed_at); // failed_at
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// uuid

		$this->uuid->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_uuid"));
		$this->uuid->AdvancedSearch->SearchOperator = $objForm->GetValue("z_uuid");

		// connection
		$this->connection->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_connection"));
		$this->connection->AdvancedSearch->SearchOperator = $objForm->GetValue("z_connection");

		// queue
		$this->queue->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_queue"));
		$this->queue->AdvancedSearch->SearchOperator = $objForm->GetValue("z_queue");

		// payload
		$this->payload->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_payload"));
		$this->payload->AdvancedSearch->SearchOperator = $objForm->GetValue("z_payload");

		// exception
		$this->exception->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_exception"));
		$this->exception->AdvancedSearch->SearchOperator = $objForm->GetValue("z_exception");

		// failed_at
		$this->failed_at->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_failed_at"));
		$this->failed_at->AdvancedSearch->SearchOperator = $objForm->GetValue("z_failed_at");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// uuid
		// connection
		// queue
		// payload
		// exception
		// failed_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// uuid
		$this->uuid->ViewValue = $this->uuid->CurrentValue;
		$this->uuid->ViewCustomAttributes = "";

		// connection
		$this->connection->ViewValue = $this->connection->CurrentValue;
		$this->connection->ViewCustomAttributes = "";

		// queue
		$this->queue->ViewValue = $this->queue->CurrentValue;
		$this->queue->ViewCustomAttributes = "";

		// payload
		$this->payload->ViewValue = $this->payload->CurrentValue;
		$this->payload->ViewCustomAttributes = "";

		// exception
		$this->exception->ViewValue = $this->exception->CurrentValue;
		$this->exception->ViewCustomAttributes = "";

		// failed_at
		$this->failed_at->ViewValue = $this->failed_at->CurrentValue;
		$this->failed_at->ViewValue = ew_FormatDateTime($this->failed_at->ViewValue, 5);
		$this->failed_at->ViewCustomAttributes = "";

			// uuid
			$this->uuid->LinkCustomAttributes = "";
			$this->uuid->HrefValue = "";
			$this->uuid->TooltipValue = "";

			// connection
			$this->connection->LinkCustomAttributes = "";
			$this->connection->HrefValue = "";
			$this->connection->TooltipValue = "";

			// queue
			$this->queue->LinkCustomAttributes = "";
			$this->queue->HrefValue = "";
			$this->queue->TooltipValue = "";

			// payload
			$this->payload->LinkCustomAttributes = "";
			$this->payload->HrefValue = "";
			$this->payload->TooltipValue = "";

			// exception
			$this->exception->LinkCustomAttributes = "";
			$this->exception->HrefValue = "";
			$this->exception->TooltipValue = "";

			// failed_at
			$this->failed_at->LinkCustomAttributes = "";
			$this->failed_at->HrefValue = "";
			$this->failed_at->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// uuid
			$this->uuid->EditAttrs["class"] = "form-control";
			$this->uuid->EditCustomAttributes = "";
			$this->uuid->EditValue = ew_HtmlEncode($this->uuid->AdvancedSearch->SearchValue);
			$this->uuid->PlaceHolder = ew_RemoveHtml($this->uuid->FldCaption());

			// connection
			$this->connection->EditAttrs["class"] = "form-control";
			$this->connection->EditCustomAttributes = "";
			$this->connection->EditValue = ew_HtmlEncode($this->connection->AdvancedSearch->SearchValue);
			$this->connection->PlaceHolder = ew_RemoveHtml($this->connection->FldCaption());

			// queue
			$this->queue->EditAttrs["class"] = "form-control";
			$this->queue->EditCustomAttributes = "";
			$this->queue->EditValue = ew_HtmlEncode($this->queue->AdvancedSearch->SearchValue);
			$this->queue->PlaceHolder = ew_RemoveHtml($this->queue->FldCaption());

			// payload
			$this->payload->EditAttrs["class"] = "form-control";
			$this->payload->EditCustomAttributes = "";
			$this->payload->EditValue = ew_HtmlEncode($this->payload->AdvancedSearch->SearchValue);
			$this->payload->PlaceHolder = ew_RemoveHtml($this->payload->FldCaption());

			// exception
			$this->exception->EditAttrs["class"] = "form-control";
			$this->exception->EditCustomAttributes = "";
			$this->exception->EditValue = ew_HtmlEncode($this->exception->AdvancedSearch->SearchValue);
			$this->exception->PlaceHolder = ew_RemoveHtml($this->exception->FldCaption());

			// failed_at
			$this->failed_at->EditAttrs["class"] = "form-control";
			$this->failed_at->EditCustomAttributes = "";
			$this->failed_at->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->failed_at->AdvancedSearch->SearchValue, 5), 5));
			$this->failed_at->PlaceHolder = ew_RemoveHtml($this->failed_at->FldCaption());
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
		if (!ew_CheckDate($this->failed_at->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->failed_at->FldErrMsg());
		}

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
		$this->uuid->AdvancedSearch->Load();
		$this->connection->AdvancedSearch->Load();
		$this->queue->AdvancedSearch->Load();
		$this->payload->AdvancedSearch->Load();
		$this->exception->AdvancedSearch->Load();
		$this->failed_at->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("failed_jobslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($failed_jobs_search)) $failed_jobs_search = new cfailed_jobs_search();

// Page init
$failed_jobs_search->Page_Init();

// Page main
$failed_jobs_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$failed_jobs_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($failed_jobs_search->IsModal) { ?>
var CurrentAdvancedSearchForm = ffailed_jobssearch = new ew_Form("ffailed_jobssearch", "search");
<?php } else { ?>
var CurrentForm = ffailed_jobssearch = new ew_Form("ffailed_jobssearch", "search");
<?php } ?>

// Form_CustomValidate event
ffailed_jobssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffailed_jobssearch.ValidateRequired = true;
<?php } else { ?>
ffailed_jobssearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search
// Validate function for search

ffailed_jobssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_failed_at");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($failed_jobs->failed_at->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$failed_jobs_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $failed_jobs_search->ShowPageHeader(); ?>
<?php
$failed_jobs_search->ShowMessage();
?>
<form name="ffailed_jobssearch" id="ffailed_jobssearch" class="<?php echo $failed_jobs_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($failed_jobs_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $failed_jobs_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="failed_jobs">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($failed_jobs_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($failed_jobs->uuid->Visible) { // uuid ?>
	<div id="r_uuid" class="form-group">
		<label for="x_uuid" class="<?php echo $failed_jobs_search->SearchLabelClass ?>"><span id="elh_failed_jobs_uuid"><?php echo $failed_jobs->uuid->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_uuid" id="z_uuid" value="LIKE"></p>
		</label>
		<div class="<?php echo $failed_jobs_search->SearchRightColumnClass ?>"><div<?php echo $failed_jobs->uuid->CellAttributes() ?>>
			<span id="el_failed_jobs_uuid">
<input type="text" data-table="failed_jobs" data-field="x_uuid" name="x_uuid" id="x_uuid" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($failed_jobs->uuid->getPlaceHolder()) ?>" value="<?php echo $failed_jobs->uuid->EditValue ?>"<?php echo $failed_jobs->uuid->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($failed_jobs->connection->Visible) { // connection ?>
	<div id="r_connection" class="form-group">
		<label for="x_connection" class="<?php echo $failed_jobs_search->SearchLabelClass ?>"><span id="elh_failed_jobs_connection"><?php echo $failed_jobs->connection->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_connection" id="z_connection" value="LIKE"></p>
		</label>
		<div class="<?php echo $failed_jobs_search->SearchRightColumnClass ?>"><div<?php echo $failed_jobs->connection->CellAttributes() ?>>
			<span id="el_failed_jobs_connection">
<input type="text" data-table="failed_jobs" data-field="x_connection" name="x_connection" id="x_connection" size="35" placeholder="<?php echo ew_HtmlEncode($failed_jobs->connection->getPlaceHolder()) ?>" value="<?php echo $failed_jobs->connection->EditValue ?>"<?php echo $failed_jobs->connection->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($failed_jobs->queue->Visible) { // queue ?>
	<div id="r_queue" class="form-group">
		<label for="x_queue" class="<?php echo $failed_jobs_search->SearchLabelClass ?>"><span id="elh_failed_jobs_queue"><?php echo $failed_jobs->queue->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_queue" id="z_queue" value="LIKE"></p>
		</label>
		<div class="<?php echo $failed_jobs_search->SearchRightColumnClass ?>"><div<?php echo $failed_jobs->queue->CellAttributes() ?>>
			<span id="el_failed_jobs_queue">
<input type="text" data-table="failed_jobs" data-field="x_queue" name="x_queue" id="x_queue" size="35" placeholder="<?php echo ew_HtmlEncode($failed_jobs->queue->getPlaceHolder()) ?>" value="<?php echo $failed_jobs->queue->EditValue ?>"<?php echo $failed_jobs->queue->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($failed_jobs->payload->Visible) { // payload ?>
	<div id="r_payload" class="form-group">
		<label for="x_payload" class="<?php echo $failed_jobs_search->SearchLabelClass ?>"><span id="elh_failed_jobs_payload"><?php echo $failed_jobs->payload->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_payload" id="z_payload" value="LIKE"></p>
		</label>
		<div class="<?php echo $failed_jobs_search->SearchRightColumnClass ?>"><div<?php echo $failed_jobs->payload->CellAttributes() ?>>
			<span id="el_failed_jobs_payload">
<input type="text" data-table="failed_jobs" data-field="x_payload" name="x_payload" id="x_payload" size="35" placeholder="<?php echo ew_HtmlEncode($failed_jobs->payload->getPlaceHolder()) ?>" value="<?php echo $failed_jobs->payload->EditValue ?>"<?php echo $failed_jobs->payload->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($failed_jobs->exception->Visible) { // exception ?>
	<div id="r_exception" class="form-group">
		<label for="x_exception" class="<?php echo $failed_jobs_search->SearchLabelClass ?>"><span id="elh_failed_jobs_exception"><?php echo $failed_jobs->exception->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_exception" id="z_exception" value="LIKE"></p>
		</label>
		<div class="<?php echo $failed_jobs_search->SearchRightColumnClass ?>"><div<?php echo $failed_jobs->exception->CellAttributes() ?>>
			<span id="el_failed_jobs_exception">
<input type="text" data-table="failed_jobs" data-field="x_exception" name="x_exception" id="x_exception" size="35" placeholder="<?php echo ew_HtmlEncode($failed_jobs->exception->getPlaceHolder()) ?>" value="<?php echo $failed_jobs->exception->EditValue ?>"<?php echo $failed_jobs->exception->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($failed_jobs->failed_at->Visible) { // failed_at ?>
	<div id="r_failed_at" class="form-group">
		<label for="x_failed_at" class="<?php echo $failed_jobs_search->SearchLabelClass ?>"><span id="elh_failed_jobs_failed_at"><?php echo $failed_jobs->failed_at->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_failed_at" id="z_failed_at" value="="></p>
		</label>
		<div class="<?php echo $failed_jobs_search->SearchRightColumnClass ?>"><div<?php echo $failed_jobs->failed_at->CellAttributes() ?>>
			<span id="el_failed_jobs_failed_at">
<input type="text" data-table="failed_jobs" data-field="x_failed_at" data-format="5" name="x_failed_at" id="x_failed_at" placeholder="<?php echo ew_HtmlEncode($failed_jobs->failed_at->getPlaceHolder()) ?>" value="<?php echo $failed_jobs->failed_at->EditValue ?>"<?php echo $failed_jobs->failed_at->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$failed_jobs_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ffailed_jobssearch.Init();
</script>
<?php
$failed_jobs_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$failed_jobs_search->Page_Terminate();
?>
