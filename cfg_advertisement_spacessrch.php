<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_advertisement_spacesinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_advertisement_spaces_search = NULL; // Initialize page object first

class ccfg_advertisement_spaces_search extends ccfg_advertisement_spaces {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_advertisement_spaces';

	// Page object name
	var $PageObjName = 'cfg_advertisement_spaces_search';

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

		// Table object (cfg_advertisement_spaces)
		if (!isset($GLOBALS["cfg_advertisement_spaces"]) || get_class($GLOBALS["cfg_advertisement_spaces"]) == "ccfg_advertisement_spaces") {
			$GLOBALS["cfg_advertisement_spaces"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_advertisement_spaces"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cfg_advertisement_spaces', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_advertisement_spaceslist.php"));
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
		global $EW_EXPORT, $cfg_advertisement_spaces;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_advertisement_spaces);
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
						$sSrchStr = "cfg_advertisement_spaceslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->ad_space_name); // ad_space_name
		$this->BuildSearchUrl($sSrchUrl, $this->ad_type); // ad_type
		$this->BuildSearchUrl($sSrchUrl, $this->ad_placement); // ad_placement
		$this->BuildSearchUrl($sSrchUrl, $this->ad_from); // ad_from
		$this->BuildSearchUrl($sSrchUrl, $this->ad_till); // ad_till
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
		// ad_space_name

		$this->ad_space_name->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ad_space_name"));
		$this->ad_space_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ad_space_name");

		// ad_type
		$this->ad_type->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ad_type"));
		$this->ad_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ad_type");

		// ad_placement
		$this->ad_placement->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ad_placement"));
		$this->ad_placement->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ad_placement");

		// ad_from
		$this->ad_from->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ad_from"));
		$this->ad_from->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ad_from");
		$this->ad_from->AdvancedSearch->SearchCondition = $objForm->GetValue("v_ad_from");
		$this->ad_from->AdvancedSearch->SearchValue2 = ew_StripSlashes($objForm->GetValue("y_ad_from"));
		$this->ad_from->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_ad_from");

		// ad_till
		$this->ad_till->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ad_till"));
		$this->ad_till->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ad_till");
		$this->ad_till->AdvancedSearch->SearchCondition = $objForm->GetValue("v_ad_till");
		$this->ad_till->AdvancedSearch->SearchValue2 = ew_StripSlashes($objForm->GetValue("y_ad_till"));
		$this->ad_till->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_ad_till");

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
		// id
		// ad_space_name
		// ad_type
		// ad_placement
		// ad_script
		// ad_image
		// link_for_image
		// ad_from
		// ad_till
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ad_space_name
		$this->ad_space_name->ViewValue = $this->ad_space_name->CurrentValue;
		$this->ad_space_name->ViewCustomAttributes = "";

		// ad_type
		if (strval($this->ad_type->CurrentValue) <> "") {
			$this->ad_type->ViewValue = $this->ad_type->OptionCaption($this->ad_type->CurrentValue);
		} else {
			$this->ad_type->ViewValue = NULL;
		}
		$this->ad_type->ViewCustomAttributes = "";

		// ad_placement
		if (strval($this->ad_placement->CurrentValue) <> "") {
			$this->ad_placement->ViewValue = $this->ad_placement->OptionCaption($this->ad_placement->CurrentValue);
		} else {
			$this->ad_placement->ViewValue = NULL;
		}
		$this->ad_placement->ViewCustomAttributes = "";

		// ad_image
		if (!ew_Empty($this->ad_image->Upload->DbValue)) {
			$this->ad_image->ImageAlt = $this->ad_image->FldAlt();
			$this->ad_image->ViewValue = $this->ad_image->Upload->DbValue;
		} else {
			$this->ad_image->ViewValue = "";
		}
		$this->ad_image->ViewCustomAttributes = "";

		// link_for_image
		$this->link_for_image->ViewValue = $this->link_for_image->CurrentValue;
		$this->link_for_image->ViewCustomAttributes = "";

		// ad_from
		$this->ad_from->ViewValue = $this->ad_from->CurrentValue;
		$this->ad_from->ViewValue = ew_FormatDateTime($this->ad_from->ViewValue, 5);
		$this->ad_from->ViewCustomAttributes = "";

		// ad_till
		$this->ad_till->ViewValue = $this->ad_till->CurrentValue;
		$this->ad_till->ViewValue = ew_FormatDateTime($this->ad_till->ViewValue, 5);
		$this->ad_till->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// ad_space_name
			$this->ad_space_name->LinkCustomAttributes = "";
			$this->ad_space_name->HrefValue = "";
			$this->ad_space_name->TooltipValue = "";

			// ad_type
			$this->ad_type->LinkCustomAttributes = "";
			$this->ad_type->HrefValue = "";
			$this->ad_type->TooltipValue = "";

			// ad_placement
			$this->ad_placement->LinkCustomAttributes = "";
			$this->ad_placement->HrefValue = "";
			$this->ad_placement->TooltipValue = "";

			// ad_from
			$this->ad_from->LinkCustomAttributes = "";
			$this->ad_from->HrefValue = "";
			$this->ad_from->TooltipValue = "";

			// ad_till
			$this->ad_till->LinkCustomAttributes = "";
			$this->ad_till->HrefValue = "";
			$this->ad_till->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// ad_space_name
			$this->ad_space_name->EditAttrs["class"] = "form-control";
			$this->ad_space_name->EditCustomAttributes = "";
			$this->ad_space_name->EditValue = ew_HtmlEncode($this->ad_space_name->AdvancedSearch->SearchValue);
			$this->ad_space_name->PlaceHolder = ew_RemoveHtml($this->ad_space_name->FldCaption());

			// ad_type
			$this->ad_type->EditCustomAttributes = "";
			$this->ad_type->EditValue = $this->ad_type->Options(FALSE);

			// ad_placement
			$this->ad_placement->EditCustomAttributes = "";
			$this->ad_placement->EditValue = $this->ad_placement->Options(FALSE);

			// ad_from
			$this->ad_from->EditAttrs["class"] = "form-control";
			$this->ad_from->EditCustomAttributes = "";
			$this->ad_from->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->ad_from->AdvancedSearch->SearchValue, 5), 5));
			$this->ad_from->PlaceHolder = ew_RemoveHtml($this->ad_from->FldCaption());
			$this->ad_from->EditAttrs["class"] = "form-control";
			$this->ad_from->EditCustomAttributes = "";
			$this->ad_from->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->ad_from->AdvancedSearch->SearchValue2, 5), 5));
			$this->ad_from->PlaceHolder = ew_RemoveHtml($this->ad_from->FldCaption());

			// ad_till
			$this->ad_till->EditAttrs["class"] = "form-control";
			$this->ad_till->EditCustomAttributes = "";
			$this->ad_till->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->ad_till->AdvancedSearch->SearchValue, 5), 5));
			$this->ad_till->PlaceHolder = ew_RemoveHtml($this->ad_till->FldCaption());
			$this->ad_till->EditAttrs["class"] = "form-control";
			$this->ad_till->EditCustomAttributes = "";
			$this->ad_till->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->ad_till->AdvancedSearch->SearchValue2, 5), 5));
			$this->ad_till->PlaceHolder = ew_RemoveHtml($this->ad_till->FldCaption());

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
		if (!ew_CheckDate($this->ad_from->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->ad_from->FldErrMsg());
		}
		if (!ew_CheckDate($this->ad_from->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->ad_from->FldErrMsg());
		}
		if (!ew_CheckDate($this->ad_till->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->ad_till->FldErrMsg());
		}
		if (!ew_CheckDate($this->ad_till->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->ad_till->FldErrMsg());
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
		$this->ad_space_name->AdvancedSearch->Load();
		$this->ad_type->AdvancedSearch->Load();
		$this->ad_placement->AdvancedSearch->Load();
		$this->ad_from->AdvancedSearch->Load();
		$this->ad_till->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_advertisement_spaceslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_advertisement_spaces_search)) $cfg_advertisement_spaces_search = new ccfg_advertisement_spaces_search();

// Page init
$cfg_advertisement_spaces_search->Page_Init();

// Page main
$cfg_advertisement_spaces_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_advertisement_spaces_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($cfg_advertisement_spaces_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fcfg_advertisement_spacessearch = new ew_Form("fcfg_advertisement_spacessearch", "search");
<?php } else { ?>
var CurrentForm = fcfg_advertisement_spacessearch = new ew_Form("fcfg_advertisement_spacessearch", "search");
<?php } ?>

// Form_CustomValidate event
fcfg_advertisement_spacessearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_advertisement_spacessearch.ValidateRequired = true;
<?php } else { ?>
fcfg_advertisement_spacessearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_advertisement_spacessearch.Lists["x_ad_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_advertisement_spacessearch.Lists["x_ad_type"].Options = <?php echo json_encode($cfg_advertisement_spaces->ad_type->Options()) ?>;
fcfg_advertisement_spacessearch.Lists["x_ad_placement"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_advertisement_spacessearch.Lists["x_ad_placement"].Options = <?php echo json_encode($cfg_advertisement_spaces->ad_placement->Options()) ?>;
fcfg_advertisement_spacessearch.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_advertisement_spacessearch.Lists["x_status"].Options = <?php echo json_encode($cfg_advertisement_spaces->status->Options()) ?>;

// Form object for search
// Validate function for search

fcfg_advertisement_spacessearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_ad_from");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_advertisement_spaces->ad_from->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_ad_till");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_advertisement_spaces->ad_till->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$cfg_advertisement_spaces_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $cfg_advertisement_spaces_search->ShowPageHeader(); ?>
<?php
$cfg_advertisement_spaces_search->ShowMessage();
?>
<form name="fcfg_advertisement_spacessearch" id="fcfg_advertisement_spacessearch" class="<?php echo $cfg_advertisement_spaces_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_advertisement_spaces_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_advertisement_spaces_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_advertisement_spaces">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($cfg_advertisement_spaces_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($cfg_advertisement_spaces->ad_space_name->Visible) { // ad_space_name ?>
	<div id="r_ad_space_name" class="form-group">
		<label for="x_ad_space_name" class="<?php echo $cfg_advertisement_spaces_search->SearchLabelClass ?>"><span id="elh_cfg_advertisement_spaces_ad_space_name"><?php echo $cfg_advertisement_spaces->ad_space_name->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ad_space_name" id="z_ad_space_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_advertisement_spaces_search->SearchRightColumnClass ?>"><div<?php echo $cfg_advertisement_spaces->ad_space_name->CellAttributes() ?>>
			<span id="el_cfg_advertisement_spaces_ad_space_name">
<input type="text" data-table="cfg_advertisement_spaces" data-field="x_ad_space_name" name="x_ad_space_name" id="x_ad_space_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_space_name->getPlaceHolder()) ?>" value="<?php echo $cfg_advertisement_spaces->ad_space_name->EditValue ?>"<?php echo $cfg_advertisement_spaces->ad_space_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_type->Visible) { // ad_type ?>
	<div id="r_ad_type" class="form-group">
		<label class="<?php echo $cfg_advertisement_spaces_search->SearchLabelClass ?>"><span id="elh_cfg_advertisement_spaces_ad_type"><?php echo $cfg_advertisement_spaces->ad_type->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_ad_type" id="z_ad_type" value="="></p>
		</label>
		<div class="<?php echo $cfg_advertisement_spaces_search->SearchRightColumnClass ?>"><div<?php echo $cfg_advertisement_spaces->ad_type->CellAttributes() ?>>
			<span id="el_cfg_advertisement_spaces_ad_type">
<div id="tp_x_ad_type" class="ewTemplate"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_advertisement_spaces->ad_type->DisplayValueSeparator) ? json_encode($cfg_advertisement_spaces->ad_type->DisplayValueSeparator) : $cfg_advertisement_spaces->ad_type->DisplayValueSeparator) ?>" name="x_ad_type" id="x_ad_type" value="{value}"<?php echo $cfg_advertisement_spaces->ad_type->EditAttributes() ?>></div>
<div id="dsl_x_ad_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_advertisement_spaces->ad_type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_advertisement_spaces->ad_type->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_type" name="x_ad_type" id="x_ad_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_advertisement_spaces->ad_type->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->ad_type->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_advertisement_spaces->ad_type->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_type" name="x_ad_type" id="x_ad_type_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_type->CurrentValue) ?>" checked<?php echo $cfg_advertisement_spaces->ad_type->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->ad_type->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_placement->Visible) { // ad_placement ?>
	<div id="r_ad_placement" class="form-group">
		<label class="<?php echo $cfg_advertisement_spaces_search->SearchLabelClass ?>"><span id="elh_cfg_advertisement_spaces_ad_placement"><?php echo $cfg_advertisement_spaces->ad_placement->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_ad_placement" id="z_ad_placement" value="="></p>
		</label>
		<div class="<?php echo $cfg_advertisement_spaces_search->SearchRightColumnClass ?>"><div<?php echo $cfg_advertisement_spaces->ad_placement->CellAttributes() ?>>
			<span id="el_cfg_advertisement_spaces_ad_placement">
<div id="tp_x_ad_placement" class="ewTemplate"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_placement" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_advertisement_spaces->ad_placement->DisplayValueSeparator) ? json_encode($cfg_advertisement_spaces->ad_placement->DisplayValueSeparator) : $cfg_advertisement_spaces->ad_placement->DisplayValueSeparator) ?>" name="x_ad_placement" id="x_ad_placement" value="{value}"<?php echo $cfg_advertisement_spaces->ad_placement->EditAttributes() ?>></div>
<div id="dsl_x_ad_placement" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_advertisement_spaces->ad_placement->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_advertisement_spaces->ad_placement->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_placement" name="x_ad_placement" id="x_ad_placement_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_advertisement_spaces->ad_placement->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->ad_placement->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_advertisement_spaces->ad_placement->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_ad_placement" name="x_ad_placement" id="x_ad_placement_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_placement->CurrentValue) ?>" checked<?php echo $cfg_advertisement_spaces->ad_placement->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->ad_placement->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_from->Visible) { // ad_from ?>
	<div id="r_ad_from" class="form-group">
		<label for="x_ad_from" class="<?php echo $cfg_advertisement_spaces_search->SearchLabelClass ?>"><span id="elh_cfg_advertisement_spaces_ad_from"><?php echo $cfg_advertisement_spaces->ad_from->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_ad_from" id="z_ad_from" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $cfg_advertisement_spaces_search->SearchRightColumnClass ?>"><div<?php echo $cfg_advertisement_spaces->ad_from->CellAttributes() ?>>
			<span id="el_cfg_advertisement_spaces_ad_from">
<input type="text" data-table="cfg_advertisement_spaces" data-field="x_ad_from" data-format="5" name="x_ad_from" id="x_ad_from" placeholder="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_from->getPlaceHolder()) ?>" value="<?php echo $cfg_advertisement_spaces->ad_from->EditValue ?>"<?php echo $cfg_advertisement_spaces->ad_from->EditAttributes() ?>>
<?php if (!$cfg_advertisement_spaces->ad_from->ReadOnly && !$cfg_advertisement_spaces->ad_from->Disabled && !isset($cfg_advertisement_spaces->ad_from->EditAttrs["readonly"]) && !isset($cfg_advertisement_spaces->ad_from->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcfg_advertisement_spacessearch", "x_ad_from", "%Y/%m/%d");
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_ad_from">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_cfg_advertisement_spaces_ad_from" class="btw1_ad_from">
<input type="text" data-table="cfg_advertisement_spaces" data-field="x_ad_from" data-format="5" name="y_ad_from" id="y_ad_from" placeholder="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_from->getPlaceHolder()) ?>" value="<?php echo $cfg_advertisement_spaces->ad_from->EditValue2 ?>"<?php echo $cfg_advertisement_spaces->ad_from->EditAttributes() ?>>
<?php if (!$cfg_advertisement_spaces->ad_from->ReadOnly && !$cfg_advertisement_spaces->ad_from->Disabled && !isset($cfg_advertisement_spaces->ad_from->EditAttrs["readonly"]) && !isset($cfg_advertisement_spaces->ad_from->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcfg_advertisement_spacessearch", "y_ad_from", "%Y/%m/%d");
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_till->Visible) { // ad_till ?>
	<div id="r_ad_till" class="form-group">
		<label for="x_ad_till" class="<?php echo $cfg_advertisement_spaces_search->SearchLabelClass ?>"><span id="elh_cfg_advertisement_spaces_ad_till"><?php echo $cfg_advertisement_spaces->ad_till->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_ad_till" id="z_ad_till" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $cfg_advertisement_spaces_search->SearchRightColumnClass ?>"><div<?php echo $cfg_advertisement_spaces->ad_till->CellAttributes() ?>>
			<span id="el_cfg_advertisement_spaces_ad_till">
<input type="text" data-table="cfg_advertisement_spaces" data-field="x_ad_till" data-format="5" name="x_ad_till" id="x_ad_till" placeholder="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_till->getPlaceHolder()) ?>" value="<?php echo $cfg_advertisement_spaces->ad_till->EditValue ?>"<?php echo $cfg_advertisement_spaces->ad_till->EditAttributes() ?>>
<?php if (!$cfg_advertisement_spaces->ad_till->ReadOnly && !$cfg_advertisement_spaces->ad_till->Disabled && !isset($cfg_advertisement_spaces->ad_till->EditAttrs["readonly"]) && !isset($cfg_advertisement_spaces->ad_till->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcfg_advertisement_spacessearch", "x_ad_till", "%Y/%m/%d");
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_ad_till">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_cfg_advertisement_spaces_ad_till" class="btw1_ad_till">
<input type="text" data-table="cfg_advertisement_spaces" data-field="x_ad_till" data-format="5" name="y_ad_till" id="y_ad_till" placeholder="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->ad_till->getPlaceHolder()) ?>" value="<?php echo $cfg_advertisement_spaces->ad_till->EditValue2 ?>"<?php echo $cfg_advertisement_spaces->ad_till->EditAttributes() ?>>
<?php if (!$cfg_advertisement_spaces->ad_till->ReadOnly && !$cfg_advertisement_spaces->ad_till->Disabled && !isset($cfg_advertisement_spaces->ad_till->EditAttrs["readonly"]) && !isset($cfg_advertisement_spaces->ad_till->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcfg_advertisement_spacessearch", "y_ad_till", "%Y/%m/%d");
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_advertisement_spaces->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $cfg_advertisement_spaces_search->SearchLabelClass ?>"><span id="elh_cfg_advertisement_spaces_status"><?php echo $cfg_advertisement_spaces->status->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $cfg_advertisement_spaces_search->SearchRightColumnClass ?>"><div<?php echo $cfg_advertisement_spaces->status->CellAttributes() ?>>
			<span id="el_cfg_advertisement_spaces_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_advertisement_spaces->status->DisplayValueSeparator) ? json_encode($cfg_advertisement_spaces->status->DisplayValueSeparator) : $cfg_advertisement_spaces->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $cfg_advertisement_spaces->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_advertisement_spaces->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_advertisement_spaces->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_advertisement_spaces->status->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_advertisement_spaces->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_advertisement_spaces" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_advertisement_spaces->status->CurrentValue) ?>" checked<?php echo $cfg_advertisement_spaces->status->EditAttributes() ?>><?php echo $cfg_advertisement_spaces->status->CurrentValue ?></label>
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
<?php if (!$cfg_advertisement_spaces_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcfg_advertisement_spacessearch.Init();
</script>
<?php
$cfg_advertisement_spaces_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_advertisement_spaces_search->Page_Terminate();
?>
