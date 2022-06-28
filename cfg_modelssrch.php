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

$cfg_models_search = NULL; // Initialize page object first

class ccfg_models_search extends ccfg_models {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_models';

	// Page object name
	var $PageObjName = 'cfg_models_search';

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

		// Table object (cfg_models)
		if (!isset($GLOBALS["cfg_models"]) || get_class($GLOBALS["cfg_models"]) == "ccfg_models") {
			$GLOBALS["cfg_models"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_models"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_modelslist.php"));
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
						$sSrchStr = "cfg_modelslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->title); // title
		$this->BuildSearchUrl($sSrchUrl, $this->make_company_id); // make_company_id
		$this->BuildSearchUrl($sSrchUrl, $this->main_pic_link); // main_pic_link
		$this->BuildSearchUrl($sSrchUrl, $this->pic_2); // pic_2
		$this->BuildSearchUrl($sSrchUrl, $this->pic_3); // pic_3
		$this->BuildSearchUrl($sSrchUrl, $this->pic_4); // pic_4
		$this->BuildSearchUrl($sSrchUrl, $this->pic_5); // pic_5
		$this->BuildSearchUrl($sSrchUrl, $this->details); // details
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
		// title

		$this->title->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_title"));
		$this->title->AdvancedSearch->SearchOperator = $objForm->GetValue("z_title");

		// make_company_id
		$this->make_company_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_make_company_id"));
		$this->make_company_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_make_company_id");

		// main_pic_link
		$this->main_pic_link->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_main_pic_link"));
		$this->main_pic_link->AdvancedSearch->SearchOperator = $objForm->GetValue("z_main_pic_link");

		// pic_2
		$this->pic_2->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_pic_2"));
		$this->pic_2->AdvancedSearch->SearchOperator = $objForm->GetValue("z_pic_2");

		// pic_3
		$this->pic_3->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_pic_3"));
		$this->pic_3->AdvancedSearch->SearchOperator = $objForm->GetValue("z_pic_3");

		// pic_4
		$this->pic_4->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_pic_4"));
		$this->pic_4->AdvancedSearch->SearchOperator = $objForm->GetValue("z_pic_4");

		// pic_5
		$this->pic_5->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_pic_5"));
		$this->pic_5->AdvancedSearch->SearchOperator = $objForm->GetValue("z_pic_5");

		// details
		$this->details->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_details"));
		$this->details->AdvancedSearch->SearchOperator = $objForm->GetValue("z_details");

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->AdvancedSearch->SearchValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// make_company_id
			$this->make_company_id->EditAttrs["class"] = "form-control";
			$this->make_company_id->EditCustomAttributes = "";
			if (trim(strval($this->make_company_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->make_company_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_make_companies`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->make_company_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->make_company_id->EditValue = $arwrk;

			// main_pic_link
			$this->main_pic_link->EditAttrs["class"] = "form-control";
			$this->main_pic_link->EditCustomAttributes = "";
			$this->main_pic_link->EditValue = ew_HtmlEncode($this->main_pic_link->AdvancedSearch->SearchValue);
			$this->main_pic_link->PlaceHolder = ew_RemoveHtml($this->main_pic_link->FldCaption());

			// pic_2
			$this->pic_2->EditAttrs["class"] = "form-control";
			$this->pic_2->EditCustomAttributes = "";
			$this->pic_2->EditValue = ew_HtmlEncode($this->pic_2->AdvancedSearch->SearchValue);
			$this->pic_2->PlaceHolder = ew_RemoveHtml($this->pic_2->FldCaption());

			// pic_3
			$this->pic_3->EditAttrs["class"] = "form-control";
			$this->pic_3->EditCustomAttributes = "";
			$this->pic_3->EditValue = ew_HtmlEncode($this->pic_3->AdvancedSearch->SearchValue);
			$this->pic_3->PlaceHolder = ew_RemoveHtml($this->pic_3->FldCaption());

			// pic_4
			$this->pic_4->EditAttrs["class"] = "form-control";
			$this->pic_4->EditCustomAttributes = "";
			$this->pic_4->EditValue = ew_HtmlEncode($this->pic_4->AdvancedSearch->SearchValue);
			$this->pic_4->PlaceHolder = ew_RemoveHtml($this->pic_4->FldCaption());

			// pic_5
			$this->pic_5->EditAttrs["class"] = "form-control";
			$this->pic_5->EditCustomAttributes = "";
			$this->pic_5->EditValue = ew_HtmlEncode($this->pic_5->AdvancedSearch->SearchValue);
			$this->pic_5->PlaceHolder = ew_RemoveHtml($this->pic_5->FldCaption());

			// details
			$this->details->EditAttrs["class"] = "form-control";
			$this->details->EditCustomAttributes = "";
			$this->details->EditValue = ew_HtmlEncode($this->details->AdvancedSearch->SearchValue);
			$this->details->PlaceHolder = ew_RemoveHtml($this->details->FldCaption());

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
		$this->title->AdvancedSearch->Load();
		$this->make_company_id->AdvancedSearch->Load();
		$this->main_pic_link->AdvancedSearch->Load();
		$this->pic_2->AdvancedSearch->Load();
		$this->pic_3->AdvancedSearch->Load();
		$this->pic_4->AdvancedSearch->Load();
		$this->pic_5->AdvancedSearch->Load();
		$this->details->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_modelslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_models_search)) $cfg_models_search = new ccfg_models_search();

// Page init
$cfg_models_search->Page_Init();

// Page main
$cfg_models_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_models_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($cfg_models_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fcfg_modelssearch = new ew_Form("fcfg_modelssearch", "search");
<?php } else { ?>
var CurrentForm = fcfg_modelssearch = new ew_Form("fcfg_modelssearch", "search");
<?php } ?>

// Form_CustomValidate event
fcfg_modelssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_modelssearch.ValidateRequired = true;
<?php } else { ?>
fcfg_modelssearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_modelssearch.Lists["x_make_company_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_modelssearch.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_modelssearch.Lists["x_status"].Options = <?php echo json_encode($cfg_models->status->Options()) ?>;

// Form object for search
// Validate function for search

fcfg_modelssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$cfg_models_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $cfg_models_search->ShowPageHeader(); ?>
<?php
$cfg_models_search->ShowMessage();
?>
<form name="fcfg_modelssearch" id="fcfg_modelssearch" class="<?php echo $cfg_models_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_models_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_models_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_models">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($cfg_models_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($cfg_models->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label for="x_title" class="<?php echo $cfg_models_search->SearchLabelClass ?>"><span id="elh_cfg_models_title"><?php echo $cfg_models->title->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_title" id="z_title" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_models_search->SearchRightColumnClass ?>"><div<?php echo $cfg_models->title->CellAttributes() ?>>
			<span id="el_cfg_models_title">
<input type="text" data-table="cfg_models" data-field="x_title" name="x_title" id="x_title" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($cfg_models->title->getPlaceHolder()) ?>" value="<?php echo $cfg_models->title->EditValue ?>"<?php echo $cfg_models->title->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->make_company_id->Visible) { // make_company_id ?>
	<div id="r_make_company_id" class="form-group">
		<label for="x_make_company_id" class="<?php echo $cfg_models_search->SearchLabelClass ?>"><span id="elh_cfg_models_make_company_id"><?php echo $cfg_models->make_company_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_make_company_id" id="z_make_company_id" value="="></p>
		</label>
		<div class="<?php echo $cfg_models_search->SearchRightColumnClass ?>"><div<?php echo $cfg_models->make_company_id->CellAttributes() ?>>
			<span id="el_cfg_models_make_company_id">
<select data-table="cfg_models" data-field="x_make_company_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_models->make_company_id->DisplayValueSeparator) ? json_encode($cfg_models->make_company_id->DisplayValueSeparator) : $cfg_models->make_company_id->DisplayValueSeparator) ?>" id="x_make_company_id" name="x_make_company_id"<?php echo $cfg_models->make_company_id->EditAttributes() ?>>
<?php
if (is_array($cfg_models->make_company_id->EditValue)) {
	$arwrk = $cfg_models->make_company_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($cfg_models->make_company_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $cfg_models->make_company_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($cfg_models->make_company_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($cfg_models->make_company_id->CurrentValue) ?>" selected><?php echo $cfg_models->make_company_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$cfg_models->make_company_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$cfg_models->make_company_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$cfg_models->Lookup_Selecting($cfg_models->make_company_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $cfg_models->make_company_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_make_company_id" id="s_x_make_company_id" value="<?php echo $cfg_models->make_company_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->main_pic_link->Visible) { // main_pic_link ?>
	<div id="r_main_pic_link" class="form-group">
		<label class="<?php echo $cfg_models_search->SearchLabelClass ?>"><span id="elh_cfg_models_main_pic_link"><?php echo $cfg_models->main_pic_link->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_main_pic_link" id="z_main_pic_link" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_models_search->SearchRightColumnClass ?>"><div<?php echo $cfg_models->main_pic_link->CellAttributes() ?>>
			<span id="el_cfg_models_main_pic_link">
<input type="text" data-table="cfg_models" data-field="x_main_pic_link" name="x_main_pic_link" id="x_main_pic_link" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_models->main_pic_link->getPlaceHolder()) ?>" value="<?php echo $cfg_models->main_pic_link->EditValue ?>"<?php echo $cfg_models->main_pic_link->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->pic_2->Visible) { // pic_2 ?>
	<div id="r_pic_2" class="form-group">
		<label class="<?php echo $cfg_models_search->SearchLabelClass ?>"><span id="elh_cfg_models_pic_2"><?php echo $cfg_models->pic_2->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pic_2" id="z_pic_2" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_models_search->SearchRightColumnClass ?>"><div<?php echo $cfg_models->pic_2->CellAttributes() ?>>
			<span id="el_cfg_models_pic_2">
<input type="text" data-table="cfg_models" data-field="x_pic_2" name="x_pic_2" id="x_pic_2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_models->pic_2->getPlaceHolder()) ?>" value="<?php echo $cfg_models->pic_2->EditValue ?>"<?php echo $cfg_models->pic_2->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->pic_3->Visible) { // pic_3 ?>
	<div id="r_pic_3" class="form-group">
		<label class="<?php echo $cfg_models_search->SearchLabelClass ?>"><span id="elh_cfg_models_pic_3"><?php echo $cfg_models->pic_3->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pic_3" id="z_pic_3" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_models_search->SearchRightColumnClass ?>"><div<?php echo $cfg_models->pic_3->CellAttributes() ?>>
			<span id="el_cfg_models_pic_3">
<input type="text" data-table="cfg_models" data-field="x_pic_3" name="x_pic_3" id="x_pic_3" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_models->pic_3->getPlaceHolder()) ?>" value="<?php echo $cfg_models->pic_3->EditValue ?>"<?php echo $cfg_models->pic_3->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->pic_4->Visible) { // pic_4 ?>
	<div id="r_pic_4" class="form-group">
		<label class="<?php echo $cfg_models_search->SearchLabelClass ?>"><span id="elh_cfg_models_pic_4"><?php echo $cfg_models->pic_4->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pic_4" id="z_pic_4" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_models_search->SearchRightColumnClass ?>"><div<?php echo $cfg_models->pic_4->CellAttributes() ?>>
			<span id="el_cfg_models_pic_4">
<input type="text" data-table="cfg_models" data-field="x_pic_4" name="x_pic_4" id="x_pic_4" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_models->pic_4->getPlaceHolder()) ?>" value="<?php echo $cfg_models->pic_4->EditValue ?>"<?php echo $cfg_models->pic_4->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->pic_5->Visible) { // pic_5 ?>
	<div id="r_pic_5" class="form-group">
		<label class="<?php echo $cfg_models_search->SearchLabelClass ?>"><span id="elh_cfg_models_pic_5"><?php echo $cfg_models->pic_5->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pic_5" id="z_pic_5" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_models_search->SearchRightColumnClass ?>"><div<?php echo $cfg_models->pic_5->CellAttributes() ?>>
			<span id="el_cfg_models_pic_5">
<input type="text" data-table="cfg_models" data-field="x_pic_5" name="x_pic_5" id="x_pic_5" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_models->pic_5->getPlaceHolder()) ?>" value="<?php echo $cfg_models->pic_5->EditValue ?>"<?php echo $cfg_models->pic_5->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->details->Visible) { // details ?>
	<div id="r_details" class="form-group">
		<label class="<?php echo $cfg_models_search->SearchLabelClass ?>"><span id="elh_cfg_models_details"><?php echo $cfg_models->details->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_details" id="z_details" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_models_search->SearchRightColumnClass ?>"><div<?php echo $cfg_models->details->CellAttributes() ?>>
			<span id="el_cfg_models_details">
<input type="text" data-table="cfg_models" data-field="x_details" name="x_details" id="x_details" size="35" placeholder="<?php echo ew_HtmlEncode($cfg_models->details->getPlaceHolder()) ?>" value="<?php echo $cfg_models->details->EditValue ?>"<?php echo $cfg_models->details->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_models->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $cfg_models_search->SearchLabelClass ?>"><span id="elh_cfg_models_status"><?php echo $cfg_models->status->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $cfg_models_search->SearchRightColumnClass ?>"><div<?php echo $cfg_models->status->CellAttributes() ?>>
			<span id="el_cfg_models_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="cfg_models" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_models->status->DisplayValueSeparator) ? json_encode($cfg_models->status->DisplayValueSeparator) : $cfg_models->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $cfg_models->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_models->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_models->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_models" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_models->status->EditAttributes() ?>><?php echo $cfg_models->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_models->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_models" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_models->status->CurrentValue) ?>" checked<?php echo $cfg_models->status->EditAttributes() ?>><?php echo $cfg_models->status->CurrentValue ?></label>
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
<?php if (!$cfg_models_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcfg_modelssearch.Init();
</script>
<?php
$cfg_models_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_models_search->Page_Terminate();
?>
