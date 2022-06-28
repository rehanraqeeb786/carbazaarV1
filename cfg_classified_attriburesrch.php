<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_classified_attribureinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_classified_attribure_search = NULL; // Initialize page object first

class ccfg_classified_attribure_search extends ccfg_classified_attribure {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_classified_attribure';

	// Page object name
	var $PageObjName = 'cfg_classified_attribure_search';

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

		// Table object (cfg_classified_attribure)
		if (!isset($GLOBALS["cfg_classified_attribure"]) || get_class($GLOBALS["cfg_classified_attribure"]) == "ccfg_classified_attribure") {
			$GLOBALS["cfg_classified_attribure"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_classified_attribure"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cfg_classified_attribure', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_classified_attriburelist.php"));
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
		global $EW_EXPORT, $cfg_classified_attribure;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_classified_attribure);
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
						$sSrchStr = "cfg_classified_attriburelist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->parent_attribute_id); // parent_attribute_id
		$this->BuildSearchUrl($sSrchUrl, $this->attribute_title); // attribute_title
		$this->BuildSearchUrl($sSrchUrl, $this->attribute_type); // attribute_type
		$this->BuildSearchUrl($sSrchUrl, $this->status); // status
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
		// parent_attribute_id

		$this->parent_attribute_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_parent_attribute_id"));
		$this->parent_attribute_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_parent_attribute_id");

		// attribute_title
		$this->attribute_title->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_attribute_title"));
		$this->attribute_title->AdvancedSearch->SearchOperator = $objForm->GetValue("z_attribute_title");

		// attribute_type
		$this->attribute_type->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_attribute_type"));
		$this->attribute_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_attribute_type");

		// status
		$this->status->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_status"));
		$this->status->AdvancedSearch->SearchOperator = $objForm->GetValue("z_status");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// ID
		// parent_attribute_id
		// attribute_title
		// attribute_type
		// status
		// ETD

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// parent_attribute_id
		$this->parent_attribute_id->ViewValue = $this->parent_attribute_id->CurrentValue;
		if (strval($this->parent_attribute_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->parent_attribute_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_classified_attribure`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->parent_attribute_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->parent_attribute_id->ViewValue = $this->parent_attribute_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->parent_attribute_id->ViewValue = $this->parent_attribute_id->CurrentValue;
			}
		} else {
			$this->parent_attribute_id->ViewValue = NULL;
		}
		$this->parent_attribute_id->ViewCustomAttributes = "";

		// attribute_title
		$this->attribute_title->ViewValue = $this->attribute_title->CurrentValue;
		$this->attribute_title->ViewCustomAttributes = "";

		// attribute_type
		if (strval($this->attribute_type->CurrentValue) <> "") {
			$this->attribute_type->ViewValue = $this->attribute_type->OptionCaption($this->attribute_type->CurrentValue);
		} else {
			$this->attribute_type->ViewValue = NULL;
		}
		$this->attribute_type->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// parent_attribute_id
			$this->parent_attribute_id->LinkCustomAttributes = "";
			$this->parent_attribute_id->HrefValue = "";
			$this->parent_attribute_id->TooltipValue = "";

			// attribute_title
			$this->attribute_title->LinkCustomAttributes = "";
			$this->attribute_title->HrefValue = "";
			$this->attribute_title->TooltipValue = "";

			// attribute_type
			$this->attribute_type->LinkCustomAttributes = "";
			$this->attribute_type->HrefValue = "";
			$this->attribute_type->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// parent_attribute_id
			$this->parent_attribute_id->EditAttrs["class"] = "form-control";
			$this->parent_attribute_id->EditCustomAttributes = "";
			$this->parent_attribute_id->EditValue = ew_HtmlEncode($this->parent_attribute_id->AdvancedSearch->SearchValue);
			if (strval($this->parent_attribute_id->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->parent_attribute_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_classified_attribure`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->parent_attribute_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->parent_attribute_id->EditValue = $this->parent_attribute_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->parent_attribute_id->EditValue = ew_HtmlEncode($this->parent_attribute_id->AdvancedSearch->SearchValue);
				}
			} else {
				$this->parent_attribute_id->EditValue = NULL;
			}
			$this->parent_attribute_id->PlaceHolder = ew_RemoveHtml($this->parent_attribute_id->FldCaption());

			// attribute_title
			$this->attribute_title->EditAttrs["class"] = "form-control";
			$this->attribute_title->EditCustomAttributes = "";
			$this->attribute_title->EditValue = ew_HtmlEncode($this->attribute_title->AdvancedSearch->SearchValue);
			$this->attribute_title->PlaceHolder = ew_RemoveHtml($this->attribute_title->FldCaption());

			// attribute_type
			$this->attribute_type->EditCustomAttributes = "";
			$this->attribute_type->EditValue = $this->attribute_type->Options(FALSE);

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);
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
		if (!ew_CheckInteger($this->parent_attribute_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->parent_attribute_id->FldErrMsg());
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
		$this->parent_attribute_id->AdvancedSearch->Load();
		$this->attribute_title->AdvancedSearch->Load();
		$this->attribute_type->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_classified_attriburelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_classified_attribure_search)) $cfg_classified_attribure_search = new ccfg_classified_attribure_search();

// Page init
$cfg_classified_attribure_search->Page_Init();

// Page main
$cfg_classified_attribure_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_classified_attribure_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($cfg_classified_attribure_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fcfg_classified_attriburesearch = new ew_Form("fcfg_classified_attriburesearch", "search");
<?php } else { ?>
var CurrentForm = fcfg_classified_attriburesearch = new ew_Form("fcfg_classified_attriburesearch", "search");
<?php } ?>

// Form_CustomValidate event
fcfg_classified_attriburesearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_classified_attriburesearch.ValidateRequired = true;
<?php } else { ?>
fcfg_classified_attriburesearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_classified_attriburesearch.Lists["x_parent_attribute_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_attribute_title","x_attribute_type","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_classified_attriburesearch.Lists["x_attribute_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_classified_attriburesearch.Lists["x_attribute_type"].Options = <?php echo json_encode($cfg_classified_attribure->attribute_type->Options()) ?>;
fcfg_classified_attriburesearch.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_classified_attriburesearch.Lists["x_status"].Options = <?php echo json_encode($cfg_classified_attribure->status->Options()) ?>;

// Form object for search
// Validate function for search

fcfg_classified_attriburesearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_parent_attribute_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_classified_attribure->parent_attribute_id->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$cfg_classified_attribure_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $cfg_classified_attribure_search->ShowPageHeader(); ?>
<?php
$cfg_classified_attribure_search->ShowMessage();
?>
<form name="fcfg_classified_attriburesearch" id="fcfg_classified_attriburesearch" class="<?php echo $cfg_classified_attribure_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_classified_attribure_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_classified_attribure_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_classified_attribure">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($cfg_classified_attribure_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($cfg_classified_attribure->parent_attribute_id->Visible) { // parent_attribute_id ?>
	<div id="r_parent_attribute_id" class="form-group">
		<label class="<?php echo $cfg_classified_attribure_search->SearchLabelClass ?>"><span id="elh_cfg_classified_attribure_parent_attribute_id"><?php echo $cfg_classified_attribure->parent_attribute_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_parent_attribute_id" id="z_parent_attribute_id" value="="></p>
		</label>
		<div class="<?php echo $cfg_classified_attribure_search->SearchRightColumnClass ?>"><div<?php echo $cfg_classified_attribure->parent_attribute_id->CellAttributes() ?>>
			<span id="el_cfg_classified_attribure_parent_attribute_id">
<?php
$wrkonchange = trim(" " . @$cfg_classified_attribure->parent_attribute_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$cfg_classified_attribure->parent_attribute_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_parent_attribute_id" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_parent_attribute_id" id="sv_x_parent_attribute_id" value="<?php echo $cfg_classified_attribure->parent_attribute_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($cfg_classified_attribure->parent_attribute_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($cfg_classified_attribure->parent_attribute_id->getPlaceHolder()) ?>"<?php echo $cfg_classified_attribure->parent_attribute_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="cfg_classified_attribure" data-field="x_parent_attribute_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_classified_attribure->parent_attribute_id->DisplayValueSeparator) ? json_encode($cfg_classified_attribure->parent_attribute_id->DisplayValueSeparator) : $cfg_classified_attribure->parent_attribute_id->DisplayValueSeparator) ?>" name="x_parent_attribute_id" id="x_parent_attribute_id" value="<?php echo ew_HtmlEncode($cfg_classified_attribure->parent_attribute_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld` FROM `cfg_classified_attribure`";
$sWhereWrk = "`attribute_title` LIKE '{query_value}%' OR CONCAT(`attribute_title`,'" . ew_ValueSeparator(1, $Page->parent_attribute_id) . "',`attribute_type`) LIKE '{query_value}%'";
$cfg_classified_attribure->Lookup_Selecting($cfg_classified_attribure->parent_attribute_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_parent_attribute_id" id="q_x_parent_attribute_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fcfg_classified_attriburesearch.CreateAutoSuggest({"id":"x_parent_attribute_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_classified_attribure->attribute_title->Visible) { // attribute_title ?>
	<div id="r_attribute_title" class="form-group">
		<label for="x_attribute_title" class="<?php echo $cfg_classified_attribure_search->SearchLabelClass ?>"><span id="elh_cfg_classified_attribure_attribute_title"><?php echo $cfg_classified_attribure->attribute_title->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_attribute_title" id="z_attribute_title" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_classified_attribure_search->SearchRightColumnClass ?>"><div<?php echo $cfg_classified_attribure->attribute_title->CellAttributes() ?>>
			<span id="el_cfg_classified_attribure_attribute_title">
<input type="text" data-table="cfg_classified_attribure" data-field="x_attribute_title" name="x_attribute_title" id="x_attribute_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_classified_attribure->attribute_title->getPlaceHolder()) ?>" value="<?php echo $cfg_classified_attribure->attribute_title->EditValue ?>"<?php echo $cfg_classified_attribure->attribute_title->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_classified_attribure->attribute_type->Visible) { // attribute_type ?>
	<div id="r_attribute_type" class="form-group">
		<label class="<?php echo $cfg_classified_attribure_search->SearchLabelClass ?>"><span id="elh_cfg_classified_attribure_attribute_type"><?php echo $cfg_classified_attribure->attribute_type->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_attribute_type" id="z_attribute_type" value="="></p>
		</label>
		<div class="<?php echo $cfg_classified_attribure_search->SearchRightColumnClass ?>"><div<?php echo $cfg_classified_attribure->attribute_type->CellAttributes() ?>>
			<span id="el_cfg_classified_attribure_attribute_type">
<div id="tp_x_attribute_type" class="ewTemplate"><input type="radio" data-table="cfg_classified_attribure" data-field="x_attribute_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_classified_attribure->attribute_type->DisplayValueSeparator) ? json_encode($cfg_classified_attribure->attribute_type->DisplayValueSeparator) : $cfg_classified_attribure->attribute_type->DisplayValueSeparator) ?>" name="x_attribute_type" id="x_attribute_type" value="{value}"<?php echo $cfg_classified_attribure->attribute_type->EditAttributes() ?>></div>
<div id="dsl_x_attribute_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_classified_attribure->attribute_type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_classified_attribure->attribute_type->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_classified_attribure" data-field="x_attribute_type" name="x_attribute_type" id="x_attribute_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_classified_attribure->attribute_type->EditAttributes() ?>><?php echo $cfg_classified_attribure->attribute_type->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_classified_attribure->attribute_type->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_classified_attribure" data-field="x_attribute_type" name="x_attribute_type" id="x_attribute_type_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_classified_attribure->attribute_type->CurrentValue) ?>" checked<?php echo $cfg_classified_attribure->attribute_type->EditAttributes() ?>><?php echo $cfg_classified_attribure->attribute_type->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_classified_attribure->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $cfg_classified_attribure_search->SearchLabelClass ?>"><span id="elh_cfg_classified_attribure_status"><?php echo $cfg_classified_attribure->status->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $cfg_classified_attribure_search->SearchRightColumnClass ?>"><div<?php echo $cfg_classified_attribure->status->CellAttributes() ?>>
			<span id="el_cfg_classified_attribure_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="cfg_classified_attribure" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_classified_attribure->status->DisplayValueSeparator) ? json_encode($cfg_classified_attribure->status->DisplayValueSeparator) : $cfg_classified_attribure->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $cfg_classified_attribure->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_classified_attribure->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_classified_attribure->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_classified_attribure" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_classified_attribure->status->EditAttributes() ?>><?php echo $cfg_classified_attribure->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_classified_attribure->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_classified_attribure" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_classified_attribure->status->CurrentValue) ?>" checked<?php echo $cfg_classified_attribure->status->EditAttributes() ?>><?php echo $cfg_classified_attribure->status->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$cfg_classified_attribure_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcfg_classified_attriburesearch.Init();
</script>
<?php
$cfg_classified_attribure_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_classified_attribure_search->Page_Terminate();
?>
