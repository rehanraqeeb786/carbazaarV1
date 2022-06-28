<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "classified_datainfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$classified_data_search = NULL; // Initialize page object first

class cclassified_data_search extends cclassified_data {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'classified_data';

	// Page object name
	var $PageObjName = 'classified_data_search';

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

		// Table object (classified_data)
		if (!isset($GLOBALS["classified_data"]) || get_class($GLOBALS["classified_data"]) == "cclassified_data") {
			$GLOBALS["classified_data"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["classified_data"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("classified_datalist.php"));
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
						$sSrchStr = "classified_datalist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->car_type); // car_type
		$this->BuildSearchUrl($sSrchUrl, $this->car_make_company_id); // car_make_company_id
		$this->BuildSearchUrl($sSrchUrl, $this->car_model_id); // car_model_id
		$this->BuildSearchUrl($sSrchUrl, $this->price_range); // price_range
		$this->BuildSearchUrl($sSrchUrl, $this->transmition); // transmition
		$this->BuildSearchUrl($sSrchUrl, $this->fuel_type); // fuel_type
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

		// car_type
		$this->car_type->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_car_type"));
		$this->car_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_car_type");

		// car_make_company_id
		$this->car_make_company_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_car_make_company_id"));
		$this->car_make_company_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_car_make_company_id");

		// car_model_id
		$this->car_model_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_car_model_id"));
		$this->car_model_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_car_model_id");

		// price_range
		$this->price_range->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_price_range"));
		$this->price_range->AdvancedSearch->SearchOperator = $objForm->GetValue("z_price_range");

		// transmition
		$this->transmition->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_transmition"));
		$this->transmition->AdvancedSearch->SearchOperator = $objForm->GetValue("z_transmition");

		// fuel_type
		$this->fuel_type->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fuel_type"));
		$this->fuel_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fuel_type");

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

			// transmition
			$this->transmition->LinkCustomAttributes = "";
			$this->transmition->HrefValue = "";
			$this->transmition->TooltipValue = "";

			// fuel_type
			$this->fuel_type->LinkCustomAttributes = "";
			$this->fuel_type->HrefValue = "";
			$this->fuel_type->TooltipValue = "";

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

			// car_type
			$this->car_type->EditCustomAttributes = "";
			$this->car_type->EditValue = $this->car_type->Options(FALSE);

			// car_make_company_id
			$this->car_make_company_id->EditAttrs["class"] = "form-control";
			$this->car_make_company_id->EditCustomAttributes = "";
			if (trim(strval($this->car_make_company_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->car_make_company_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_make_companies`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->car_make_company_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->car_make_company_id->EditValue = $arwrk;

			// car_model_id
			$this->car_model_id->EditAttrs["class"] = "form-control";
			$this->car_model_id->EditCustomAttributes = "";
			if (trim(strval($this->car_model_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->car_model_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `make_company_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_models`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->car_model_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->car_model_id->EditValue = $arwrk;

			// price_range
			$this->price_range->EditAttrs["class"] = "form-control";
			$this->price_range->EditCustomAttributes = "";
			$this->price_range->EditValue = ew_HtmlEncode($this->price_range->AdvancedSearch->SearchValue);
			$this->price_range->PlaceHolder = ew_RemoveHtml($this->price_range->FldCaption());

			// transmition
			$this->transmition->EditCustomAttributes = "";
			$this->transmition->EditValue = $this->transmition->Options(FALSE);

			// fuel_type
			$this->fuel_type->EditCustomAttributes = "";
			$this->fuel_type->EditValue = $this->fuel_type->Options(FALSE);

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
		$this->car_type->AdvancedSearch->Load();
		$this->car_make_company_id->AdvancedSearch->Load();
		$this->car_model_id->AdvancedSearch->Load();
		$this->price_range->AdvancedSearch->Load();
		$this->transmition->AdvancedSearch->Load();
		$this->fuel_type->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("classified_datalist.php"), "", $this->TableVar, TRUE);
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
if (!isset($classified_data_search)) $classified_data_search = new cclassified_data_search();

// Page init
$classified_data_search->Page_Init();

// Page main
$classified_data_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$classified_data_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($classified_data_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fclassified_datasearch = new ew_Form("fclassified_datasearch", "search");
<?php } else { ?>
var CurrentForm = fclassified_datasearch = new ew_Form("fclassified_datasearch", "search");
<?php } ?>

// Form_CustomValidate event
fclassified_datasearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclassified_datasearch.ValidateRequired = true;
<?php } else { ?>
fclassified_datasearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclassified_datasearch.Lists["x_car_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_datasearch.Lists["x_car_type"].Options = <?php echo json_encode($classified_data->car_type->Options()) ?>;
fclassified_datasearch.Lists["x_car_make_company_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":["x_car_model_id"],"FilterFields":[],"Options":[],"Template":""};
fclassified_datasearch.Lists["x_car_model_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":["x_car_make_company_id"],"ChildFields":[],"FilterFields":["x_make_company_id"],"Options":[],"Template":""};
fclassified_datasearch.Lists["x_transmition"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_datasearch.Lists["x_transmition"].Options = <?php echo json_encode($classified_data->transmition->Options()) ?>;
fclassified_datasearch.Lists["x_fuel_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_datasearch.Lists["x_fuel_type"].Options = <?php echo json_encode($classified_data->fuel_type->Options()) ?>;
fclassified_datasearch.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_datasearch.Lists["x_status"].Options = <?php echo json_encode($classified_data->status->Options()) ?>;

// Form object for search
// Validate function for search

fclassified_datasearch.Validate = function(fobj) {
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
<?php if (!$classified_data_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $classified_data_search->ShowPageHeader(); ?>
<?php
$classified_data_search->ShowMessage();
?>
<form name="fclassified_datasearch" id="fclassified_datasearch" class="<?php echo $classified_data_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($classified_data_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $classified_data_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="classified_data">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($classified_data_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($classified_data->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label for="x_title" class="<?php echo $classified_data_search->SearchLabelClass ?>"><span id="elh_classified_data_title"><?php echo $classified_data->title->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_title" id="z_title" value="LIKE"></p>
		</label>
		<div class="<?php echo $classified_data_search->SearchRightColumnClass ?>"><div<?php echo $classified_data->title->CellAttributes() ?>>
			<span id="el_classified_data_title">
<input type="text" data-table="classified_data" data-field="x_title" name="x_title" id="x_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($classified_data->title->getPlaceHolder()) ?>" value="<?php echo $classified_data->title->EditValue ?>"<?php echo $classified_data->title->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($classified_data->car_type->Visible) { // car_type ?>
	<div id="r_car_type" class="form-group">
		<label class="<?php echo $classified_data_search->SearchLabelClass ?>"><span id="elh_classified_data_car_type"><?php echo $classified_data->car_type->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_car_type" id="z_car_type" value="="></p>
		</label>
		<div class="<?php echo $classified_data_search->SearchRightColumnClass ?>"><div<?php echo $classified_data->car_type->CellAttributes() ?>>
			<span id="el_classified_data_car_type">
<div id="tp_x_car_type" class="ewTemplate"><input type="radio" data-table="classified_data" data-field="x_car_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->car_type->DisplayValueSeparator) ? json_encode($classified_data->car_type->DisplayValueSeparator) : $classified_data->car_type->DisplayValueSeparator) ?>" name="x_car_type" id="x_car_type" value="{value}"<?php echo $classified_data->car_type->EditAttributes() ?>></div>
<div id="dsl_x_car_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $classified_data->car_type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($classified_data->car_type->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_car_type" name="x_car_type" id="x_car_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $classified_data->car_type->EditAttributes() ?>><?php echo $classified_data->car_type->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($classified_data->car_type->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_car_type" name="x_car_type" id="x_car_type_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($classified_data->car_type->CurrentValue) ?>" checked<?php echo $classified_data->car_type->EditAttributes() ?>><?php echo $classified_data->car_type->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($classified_data->car_make_company_id->Visible) { // car_make_company_id ?>
	<div id="r_car_make_company_id" class="form-group">
		<label for="x_car_make_company_id" class="<?php echo $classified_data_search->SearchLabelClass ?>"><span id="elh_classified_data_car_make_company_id"><?php echo $classified_data->car_make_company_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_car_make_company_id" id="z_car_make_company_id" value="="></p>
		</label>
		<div class="<?php echo $classified_data_search->SearchRightColumnClass ?>"><div<?php echo $classified_data->car_make_company_id->CellAttributes() ?>>
			<span id="el_classified_data_car_make_company_id">
<?php $classified_data->car_make_company_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$classified_data->car_make_company_id->EditAttrs["onchange"]; ?>
<select data-table="classified_data" data-field="x_car_make_company_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->car_make_company_id->DisplayValueSeparator) ? json_encode($classified_data->car_make_company_id->DisplayValueSeparator) : $classified_data->car_make_company_id->DisplayValueSeparator) ?>" id="x_car_make_company_id" name="x_car_make_company_id"<?php echo $classified_data->car_make_company_id->EditAttributes() ?>>
<?php
if (is_array($classified_data->car_make_company_id->EditValue)) {
	$arwrk = $classified_data->car_make_company_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($classified_data->car_make_company_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $classified_data->car_make_company_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($classified_data->car_make_company_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($classified_data->car_make_company_id->CurrentValue) ?>" selected><?php echo $classified_data->car_make_company_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
$sWhereWrk = "";
$classified_data->car_make_company_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$classified_data->car_make_company_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$classified_data->Lookup_Selecting($classified_data->car_make_company_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $classified_data->car_make_company_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_car_make_company_id" id="s_x_car_make_company_id" value="<?php echo $classified_data->car_make_company_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($classified_data->car_model_id->Visible) { // car_model_id ?>
	<div id="r_car_model_id" class="form-group">
		<label for="x_car_model_id" class="<?php echo $classified_data_search->SearchLabelClass ?>"><span id="elh_classified_data_car_model_id"><?php echo $classified_data->car_model_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_car_model_id" id="z_car_model_id" value="="></p>
		</label>
		<div class="<?php echo $classified_data_search->SearchRightColumnClass ?>"><div<?php echo $classified_data->car_model_id->CellAttributes() ?>>
			<span id="el_classified_data_car_model_id">
<select data-table="classified_data" data-field="x_car_model_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->car_model_id->DisplayValueSeparator) ? json_encode($classified_data->car_model_id->DisplayValueSeparator) : $classified_data->car_model_id->DisplayValueSeparator) ?>" id="x_car_model_id" name="x_car_model_id"<?php echo $classified_data->car_model_id->EditAttributes() ?>>
<?php
if (is_array($classified_data->car_model_id->EditValue)) {
	$arwrk = $classified_data->car_model_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($classified_data->car_model_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $classified_data->car_model_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($classified_data->car_model_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($classified_data->car_model_id->CurrentValue) ?>" selected><?php echo $classified_data->car_model_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_models`";
$sWhereWrk = "{filter}";
$classified_data->car_model_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$classified_data->car_model_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$classified_data->car_model_id->LookupFilters += array("f1" => "`make_company_id` IN ({filter_value})", "t1" => "3", "fn1" => "");
$sSqlWrk = "";
$classified_data->Lookup_Selecting($classified_data->car_model_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $classified_data->car_model_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_car_model_id" id="s_x_car_model_id" value="<?php echo $classified_data->car_model_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($classified_data->price_range->Visible) { // price_range ?>
	<div id="r_price_range" class="form-group">
		<label for="x_price_range" class="<?php echo $classified_data_search->SearchLabelClass ?>"><span id="elh_classified_data_price_range"><?php echo $classified_data->price_range->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_price_range" id="z_price_range" value="LIKE"></p>
		</label>
		<div class="<?php echo $classified_data_search->SearchRightColumnClass ?>"><div<?php echo $classified_data->price_range->CellAttributes() ?>>
			<span id="el_classified_data_price_range">
<input type="text" data-table="classified_data" data-field="x_price_range" name="x_price_range" id="x_price_range" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($classified_data->price_range->getPlaceHolder()) ?>" value="<?php echo $classified_data->price_range->EditValue ?>"<?php echo $classified_data->price_range->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($classified_data->transmition->Visible) { // transmition ?>
	<div id="r_transmition" class="form-group">
		<label class="<?php echo $classified_data_search->SearchLabelClass ?>"><span id="elh_classified_data_transmition"><?php echo $classified_data->transmition->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_transmition" id="z_transmition" value="="></p>
		</label>
		<div class="<?php echo $classified_data_search->SearchRightColumnClass ?>"><div<?php echo $classified_data->transmition->CellAttributes() ?>>
			<span id="el_classified_data_transmition">
<div id="tp_x_transmition" class="ewTemplate"><input type="radio" data-table="classified_data" data-field="x_transmition" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->transmition->DisplayValueSeparator) ? json_encode($classified_data->transmition->DisplayValueSeparator) : $classified_data->transmition->DisplayValueSeparator) ?>" name="x_transmition" id="x_transmition" value="{value}"<?php echo $classified_data->transmition->EditAttributes() ?>></div>
<div id="dsl_x_transmition" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $classified_data->transmition->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($classified_data->transmition->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_transmition" name="x_transmition" id="x_transmition_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $classified_data->transmition->EditAttributes() ?>><?php echo $classified_data->transmition->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($classified_data->transmition->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_transmition" name="x_transmition" id="x_transmition_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($classified_data->transmition->CurrentValue) ?>" checked<?php echo $classified_data->transmition->EditAttributes() ?>><?php echo $classified_data->transmition->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($classified_data->fuel_type->Visible) { // fuel_type ?>
	<div id="r_fuel_type" class="form-group">
		<label class="<?php echo $classified_data_search->SearchLabelClass ?>"><span id="elh_classified_data_fuel_type"><?php echo $classified_data->fuel_type->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fuel_type" id="z_fuel_type" value="="></p>
		</label>
		<div class="<?php echo $classified_data_search->SearchRightColumnClass ?>"><div<?php echo $classified_data->fuel_type->CellAttributes() ?>>
			<span id="el_classified_data_fuel_type">
<div id="tp_x_fuel_type" class="ewTemplate"><input type="radio" data-table="classified_data" data-field="x_fuel_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->fuel_type->DisplayValueSeparator) ? json_encode($classified_data->fuel_type->DisplayValueSeparator) : $classified_data->fuel_type->DisplayValueSeparator) ?>" name="x_fuel_type" id="x_fuel_type" value="{value}"<?php echo $classified_data->fuel_type->EditAttributes() ?>></div>
<div id="dsl_x_fuel_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $classified_data->fuel_type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($classified_data->fuel_type->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_fuel_type" name="x_fuel_type" id="x_fuel_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $classified_data->fuel_type->EditAttributes() ?>><?php echo $classified_data->fuel_type->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($classified_data->fuel_type->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_fuel_type" name="x_fuel_type" id="x_fuel_type_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($classified_data->fuel_type->CurrentValue) ?>" checked<?php echo $classified_data->fuel_type->EditAttributes() ?>><?php echo $classified_data->fuel_type->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($classified_data->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $classified_data_search->SearchLabelClass ?>"><span id="elh_classified_data_status"><?php echo $classified_data->status->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $classified_data_search->SearchRightColumnClass ?>"><div<?php echo $classified_data->status->CellAttributes() ?>>
			<span id="el_classified_data_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="classified_data" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_data->status->DisplayValueSeparator) ? json_encode($classified_data->status->DisplayValueSeparator) : $classified_data->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $classified_data->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $classified_data->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($classified_data->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $classified_data->status->EditAttributes() ?>><?php echo $classified_data->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($classified_data->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="classified_data" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($classified_data->status->CurrentValue) ?>" checked<?php echo $classified_data->status->EditAttributes() ?>><?php echo $classified_data->status->CurrentValue ?></label>
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
<?php if (!$classified_data_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fclassified_datasearch.Init();
</script>
<?php
$classified_data_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$classified_data_search->Page_Terminate();
?>
