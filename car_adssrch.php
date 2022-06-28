<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "car_adsinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$car_ads_search = NULL; // Initialize page object first

class ccar_ads_search extends ccar_ads {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'car_ads';

	// Page object name
	var $PageObjName = 'car_ads_search';

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

		// Table object (car_ads)
		if (!isset($GLOBALS["car_ads"]) || get_class($GLOBALS["car_ads"]) == "ccar_ads") {
			$GLOBALS["car_ads"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["car_ads"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("car_adslist.php"));
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
						$sSrchStr = "car_adslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->user_id); // user_id
		$this->BuildSearchUrl($sSrchUrl, $this->ad_title); // ad_title
		$this->BuildSearchUrl($sSrchUrl, $this->year_id); // year_id
		$this->BuildSearchUrl($sSrchUrl, $this->registered_in); // registered_in
		$this->BuildSearchUrl($sSrchUrl, $this->city_id); // city_id
		$this->BuildSearchUrl($sSrchUrl, $this->make_id); // make_id
		$this->BuildSearchUrl($sSrchUrl, $this->model_id); // model_id
		$this->BuildSearchUrl($sSrchUrl, $this->version_id); // version_id
		$this->BuildSearchUrl($sSrchUrl, $this->milage); // milage
		$this->BuildSearchUrl($sSrchUrl, $this->color_id); // color_id
		$this->BuildSearchUrl($sSrchUrl, $this->demand_price); // demand_price
		$this->BuildSearchUrl($sSrchUrl, $this->details); // details
		$this->BuildSearchUrl($sSrchUrl, $this->engine_type_id); // engine_type_id
		$this->BuildSearchUrl($sSrchUrl, $this->engine_capicity); // engine_capicity
		$this->BuildSearchUrl($sSrchUrl, $this->transmition); // transmition
		$this->BuildSearchUrl($sSrchUrl, $this->assembly); // assembly
		$this->BuildSearchUrl($sSrchUrl, $this->mobile_number); // mobile_number
		$this->BuildSearchUrl($sSrchUrl, $this->secondary_number); // secondary_number
		$this->BuildSearchUrl($sSrchUrl, $this->_email); // email
		$this->BuildSearchUrl($sSrchUrl, $this->name); // name
		$this->BuildSearchUrl($sSrchUrl, $this->address); // address
		$this->BuildSearchUrl($sSrchUrl, $this->allow_whatsapp); // allow_whatsapp
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
		// user_id

		$this->user_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_user_id"));
		$this->user_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_user_id");

		// ad_title
		$this->ad_title->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ad_title"));
		$this->ad_title->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ad_title");

		// year_id
		$this->year_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_year_id"));
		$this->year_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_year_id");

		// registered_in
		$this->registered_in->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_registered_in"));
		$this->registered_in->AdvancedSearch->SearchOperator = $objForm->GetValue("z_registered_in");

		// city_id
		$this->city_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_city_id"));
		$this->city_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_city_id");

		// make_id
		$this->make_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_make_id"));
		$this->make_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_make_id");

		// model_id
		$this->model_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_model_id"));
		$this->model_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_model_id");

		// version_id
		$this->version_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_version_id"));
		$this->version_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_version_id");

		// milage
		$this->milage->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_milage"));
		$this->milage->AdvancedSearch->SearchOperator = $objForm->GetValue("z_milage");

		// color_id
		$this->color_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_color_id"));
		$this->color_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_color_id");

		// demand_price
		$this->demand_price->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_demand_price"));
		$this->demand_price->AdvancedSearch->SearchOperator = $objForm->GetValue("z_demand_price");

		// details
		$this->details->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_details"));
		$this->details->AdvancedSearch->SearchOperator = $objForm->GetValue("z_details");

		// engine_type_id
		$this->engine_type_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_engine_type_id"));
		$this->engine_type_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_engine_type_id");

		// engine_capicity
		$this->engine_capicity->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_engine_capicity"));
		$this->engine_capicity->AdvancedSearch->SearchOperator = $objForm->GetValue("z_engine_capicity");

		// transmition
		$this->transmition->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_transmition"));
		$this->transmition->AdvancedSearch->SearchOperator = $objForm->GetValue("z_transmition");

		// assembly
		$this->assembly->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_assembly"));
		$this->assembly->AdvancedSearch->SearchOperator = $objForm->GetValue("z_assembly");

		// mobile_number
		$this->mobile_number->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_mobile_number"));
		$this->mobile_number->AdvancedSearch->SearchOperator = $objForm->GetValue("z_mobile_number");

		// secondary_number
		$this->secondary_number->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_secondary_number"));
		$this->secondary_number->AdvancedSearch->SearchOperator = $objForm->GetValue("z_secondary_number");

		// email
		$this->_email->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x__email"));
		$this->_email->AdvancedSearch->SearchOperator = $objForm->GetValue("z__email");

		// name
		$this->name->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_name"));
		$this->name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_name");

		// address
		$this->address->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_address"));
		$this->address->AdvancedSearch->SearchOperator = $objForm->GetValue("z_address");

		// allow_whatsapp
		$this->allow_whatsapp->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_allow_whatsapp"));
		$this->allow_whatsapp->AdvancedSearch->SearchOperator = $objForm->GetValue("z_allow_whatsapp");

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// user_id
			$this->user_id->EditAttrs["class"] = "form-control";
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->AdvancedSearch->SearchValue);
			if (strval($this->user_id->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->user_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, `email` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->user_id->EditValue = $this->user_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->user_id->EditValue = ew_HtmlEncode($this->user_id->AdvancedSearch->SearchValue);
				}
			} else {
				$this->user_id->EditValue = NULL;
			}
			$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

			// ad_title
			$this->ad_title->EditAttrs["class"] = "form-control";
			$this->ad_title->EditCustomAttributes = "";
			$this->ad_title->EditValue = ew_HtmlEncode($this->ad_title->AdvancedSearch->SearchValue);
			$this->ad_title->PlaceHolder = ew_RemoveHtml($this->ad_title->FldCaption());

			// year_id
			$this->year_id->EditCustomAttributes = "";
			if (trim(strval($this->year_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->year_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `year` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_years`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->year_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->year_id->AdvancedSearch->ViewValue = $this->year_id->DisplayValue($arwrk);
			} else {
				$this->year_id->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->year_id->EditValue = $arwrk;

			// registered_in
			$this->registered_in->EditAttrs["class"] = "form-control";
			$this->registered_in->EditCustomAttributes = "";
			if (trim(strval($this->registered_in->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->registered_in->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_cities`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->registered_in, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->registered_in->EditValue = $arwrk;

			// city_id
			$this->city_id->EditAttrs["class"] = "form-control";
			$this->city_id->EditCustomAttributes = "";
			if (trim(strval($this->city_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->city_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_cities`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->city_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->city_id->EditValue = $arwrk;

			// make_id
			$this->make_id->EditAttrs["class"] = "form-control";
			$this->make_id->EditCustomAttributes = "";
			if (trim(strval($this->make_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->make_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_make_companies`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->make_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->make_id->EditValue = $arwrk;

			// model_id
			$this->model_id->EditAttrs["class"] = "form-control";
			$this->model_id->EditCustomAttributes = "";
			if (trim(strval($this->model_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->model_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_models`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->model_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->model_id->EditValue = $arwrk;

			// version_id
			$this->version_id->EditAttrs["class"] = "form-control";
			$this->version_id->EditCustomAttributes = "";
			if (trim(strval($this->version_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->version_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_car_versions`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->version_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->version_id->EditValue = $arwrk;

			// milage
			$this->milage->EditAttrs["class"] = "form-control";
			$this->milage->EditCustomAttributes = "";
			$this->milage->EditValue = ew_HtmlEncode($this->milage->AdvancedSearch->SearchValue);
			$this->milage->PlaceHolder = ew_RemoveHtml($this->milage->FldCaption());

			// color_id
			$this->color_id->EditAttrs["class"] = "form-control";
			$this->color_id->EditCustomAttributes = "";
			if (trim(strval($this->color_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->color_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_body_colors`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->color_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->color_id->EditValue = $arwrk;

			// demand_price
			$this->demand_price->EditAttrs["class"] = "form-control";
			$this->demand_price->EditCustomAttributes = "";
			$this->demand_price->EditValue = ew_HtmlEncode($this->demand_price->AdvancedSearch->SearchValue);
			$this->demand_price->PlaceHolder = ew_RemoveHtml($this->demand_price->FldCaption());

			// details
			$this->details->EditAttrs["class"] = "form-control";
			$this->details->EditCustomAttributes = "";
			$this->details->EditValue = ew_HtmlEncode($this->details->AdvancedSearch->SearchValue);
			$this->details->PlaceHolder = ew_RemoveHtml($this->details->FldCaption());

			// engine_type_id
			$this->engine_type_id->EditAttrs["class"] = "form-control";
			$this->engine_type_id->EditCustomAttributes = "";
			if (trim(strval($this->engine_type_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->engine_type_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_engine_types`";
			$sWhereWrk = "";
			$lookuptblfilter = "`status`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->engine_type_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->engine_type_id->EditValue = $arwrk;

			// engine_capicity
			$this->engine_capicity->EditAttrs["class"] = "form-control";
			$this->engine_capicity->EditCustomAttributes = "";
			$this->engine_capicity->EditValue = ew_HtmlEncode($this->engine_capicity->AdvancedSearch->SearchValue);
			$this->engine_capicity->PlaceHolder = ew_RemoveHtml($this->engine_capicity->FldCaption());

			// transmition
			$this->transmition->EditCustomAttributes = "";
			$this->transmition->EditValue = $this->transmition->Options(FALSE);

			// assembly
			$this->assembly->EditCustomAttributes = "";
			$this->assembly->EditValue = $this->assembly->Options(FALSE);

			// mobile_number
			$this->mobile_number->EditAttrs["class"] = "form-control";
			$this->mobile_number->EditCustomAttributes = "";
			$this->mobile_number->EditValue = ew_HtmlEncode($this->mobile_number->AdvancedSearch->SearchValue);
			$this->mobile_number->PlaceHolder = ew_RemoveHtml($this->mobile_number->FldCaption());

			// secondary_number
			$this->secondary_number->EditAttrs["class"] = "form-control";
			$this->secondary_number->EditCustomAttributes = "";
			$this->secondary_number->EditValue = ew_HtmlEncode($this->secondary_number->AdvancedSearch->SearchValue);
			$this->secondary_number->PlaceHolder = ew_RemoveHtml($this->secondary_number->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->AdvancedSearch->SearchValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->AdvancedSearch->SearchValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->AdvancedSearch->SearchValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// allow_whatsapp
			$this->allow_whatsapp->EditCustomAttributes = "";
			$this->allow_whatsapp->EditValue = $this->allow_whatsapp->Options(FALSE);

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
		if (!ew_CheckInteger($this->user_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->user_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->milage->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->milage->FldErrMsg());
		}
		if (!ew_CheckInteger($this->demand_price->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->demand_price->FldErrMsg());
		}
		if (!ew_CheckInteger($this->engine_capicity->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->engine_capicity->FldErrMsg());
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
		$this->user_id->AdvancedSearch->Load();
		$this->ad_title->AdvancedSearch->Load();
		$this->year_id->AdvancedSearch->Load();
		$this->registered_in->AdvancedSearch->Load();
		$this->city_id->AdvancedSearch->Load();
		$this->make_id->AdvancedSearch->Load();
		$this->model_id->AdvancedSearch->Load();
		$this->version_id->AdvancedSearch->Load();
		$this->milage->AdvancedSearch->Load();
		$this->color_id->AdvancedSearch->Load();
		$this->demand_price->AdvancedSearch->Load();
		$this->details->AdvancedSearch->Load();
		$this->engine_type_id->AdvancedSearch->Load();
		$this->engine_capicity->AdvancedSearch->Load();
		$this->transmition->AdvancedSearch->Load();
		$this->assembly->AdvancedSearch->Load();
		$this->mobile_number->AdvancedSearch->Load();
		$this->secondary_number->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->name->AdvancedSearch->Load();
		$this->address->AdvancedSearch->Load();
		$this->allow_whatsapp->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("car_adslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($car_ads_search)) $car_ads_search = new ccar_ads_search();

// Page init
$car_ads_search->Page_Init();

// Page main
$car_ads_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$car_ads_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($car_ads_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fcar_adssearch = new ew_Form("fcar_adssearch", "search");
<?php } else { ?>
var CurrentForm = fcar_adssearch = new ew_Form("fcar_adssearch", "search");
<?php } ?>

// Form_CustomValidate event
fcar_adssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcar_adssearch.ValidateRequired = true;
<?php } else { ?>
fcar_adssearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcar_adssearch.Lists["x_user_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","x__email","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_year_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_year","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_registered_in"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_city_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_make_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_model_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_version_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_color_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_engine_type_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_transmition"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_transmition"].Options = <?php echo json_encode($car_ads->transmition->Options()) ?>;
fcar_adssearch.Lists["x_assembly"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_assembly"].Options = <?php echo json_encode($car_ads->assembly->Options()) ?>;
fcar_adssearch.Lists["x_allow_whatsapp"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_allow_whatsapp"].Options = <?php echo json_encode($car_ads->allow_whatsapp->Options()) ?>;
fcar_adssearch.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcar_adssearch.Lists["x_status"].Options = <?php echo json_encode($car_ads->status->Options()) ?>;

// Form object for search
// Validate function for search

fcar_adssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_user_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($car_ads->user_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_milage");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($car_ads->milage->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_demand_price");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($car_ads->demand_price->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_engine_capicity");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($car_ads->engine_capicity->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$car_ads_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $car_ads_search->ShowPageHeader(); ?>
<?php
$car_ads_search->ShowMessage();
?>
<form name="fcar_adssearch" id="fcar_adssearch" class="<?php echo $car_ads_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($car_ads_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $car_ads_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="car_ads">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($car_ads_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($car_ads->user_id->Visible) { // user_id ?>
	<div id="r_user_id" class="form-group">
		<label class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_user_id"><?php echo $car_ads->user_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_user_id" id="z_user_id" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->user_id->CellAttributes() ?>>
			<span id="el_car_ads_user_id">
<?php
$wrkonchange = trim(" " . @$car_ads->user_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$car_ads->user_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_user_id" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_user_id" id="sv_x_user_id" value="<?php echo $car_ads->user_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($car_ads->user_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($car_ads->user_id->getPlaceHolder()) ?>"<?php echo $car_ads->user_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="car_ads" data-field="x_user_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->user_id->DisplayValueSeparator) ? json_encode($car_ads->user_id->DisplayValueSeparator) : $car_ads->user_id->DisplayValueSeparator) ?>" name="x_user_id" id="x_user_id" value="<?php echo ew_HtmlEncode($car_ads->user_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, `email` AS `Disp2Fld` FROM `users`";
$sWhereWrk = "`name` LIKE '{query_value}%' OR CONCAT(`name`,'" . ew_ValueSeparator(1, $Page->user_id) . "',`email`) LIKE '{query_value}%'";
$car_ads->Lookup_Selecting($car_ads->user_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_user_id" id="q_x_user_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fcar_adssearch.CreateAutoSuggest({"id":"x_user_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->ad_title->Visible) { // ad_title ?>
	<div id="r_ad_title" class="form-group">
		<label for="x_ad_title" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_ad_title"><?php echo $car_ads->ad_title->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ad_title" id="z_ad_title" value="LIKE"></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->ad_title->CellAttributes() ?>>
			<span id="el_car_ads_ad_title">
<input type="text" data-table="car_ads" data-field="x_ad_title" name="x_ad_title" id="x_ad_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($car_ads->ad_title->getPlaceHolder()) ?>" value="<?php echo $car_ads->ad_title->EditValue ?>"<?php echo $car_ads->ad_title->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->year_id->Visible) { // year_id ?>
	<div id="r_year_id" class="form-group">
		<label for="x_year_id" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_year_id"><?php echo $car_ads->year_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_year_id" id="z_year_id" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->year_id->CellAttributes() ?>>
			<span id="el_car_ads_year_id">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $car_ads->year_id->AdvancedSearch->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x_year_id" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
$arwrk = $car_ads->year_id->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($car_ads->year_id->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "") {
			$emptywrk = FALSE;
?>
<input type="radio" data-table="car_ads" data-field="x_year_id" name="x_year_id" id="x_year_id_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $car_ads->year_id->EditAttributes() ?>><?php echo $car_ads->year_id->DisplayValue($arwrk[$rowcntwrk]) ?>
<?php
		}
	}
	if ($emptywrk && strval($car_ads->year_id->CurrentValue) <> "") {
?>
<input type="radio" data-table="car_ads" data-field="x_year_id" name="x_year_id" id="x_year_id_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($car_ads->year_id->CurrentValue) ?>" checked<?php echo $car_ads->year_id->EditAttributes() ?>><?php echo $car_ads->year_id->CurrentValue ?>
<?php
    }
}
?>
		</div>
	</div>
	<div id="tp_x_year_id" class="ewTemplate"><input type="radio" data-table="car_ads" data-field="x_year_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->year_id->DisplayValueSeparator) ? json_encode($car_ads->year_id->DisplayValueSeparator) : $car_ads->year_id->DisplayValueSeparator) ?>" name="x_year_id" id="x_year_id" value="{value}"<?php echo $car_ads->year_id->EditAttributes() ?>></div>
</div>
<?php
$sSqlWrk = "SELECT `ID`, `year` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_years`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->year_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->year_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->year_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->year_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_year_id" id="s_x_year_id" value="<?php echo $car_ads->year_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->registered_in->Visible) { // registered_in ?>
	<div id="r_registered_in" class="form-group">
		<label for="x_registered_in" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_registered_in"><?php echo $car_ads->registered_in->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_registered_in" id="z_registered_in" value="LIKE"></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->registered_in->CellAttributes() ?>>
			<span id="el_car_ads_registered_in">
<select data-table="car_ads" data-field="x_registered_in" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->registered_in->DisplayValueSeparator) ? json_encode($car_ads->registered_in->DisplayValueSeparator) : $car_ads->registered_in->DisplayValueSeparator) ?>" id="x_registered_in" name="x_registered_in"<?php echo $car_ads->registered_in->EditAttributes() ?>>
<?php
if (is_array($car_ads->registered_in->EditValue)) {
	$arwrk = $car_ads->registered_in->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->registered_in->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->registered_in->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->registered_in->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->registered_in->CurrentValue) ?>" selected><?php echo $car_ads->registered_in->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
$sWhereWrk = "";
$car_ads->registered_in->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->registered_in->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->registered_in, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->registered_in->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_registered_in" id="s_x_registered_in" value="<?php echo $car_ads->registered_in->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->city_id->Visible) { // city_id ?>
	<div id="r_city_id" class="form-group">
		<label for="x_city_id" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_city_id"><?php echo $car_ads->city_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_city_id" id="z_city_id" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->city_id->CellAttributes() ?>>
			<span id="el_car_ads_city_id">
<select data-table="car_ads" data-field="x_city_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->city_id->DisplayValueSeparator) ? json_encode($car_ads->city_id->DisplayValueSeparator) : $car_ads->city_id->DisplayValueSeparator) ?>" id="x_city_id" name="x_city_id"<?php echo $car_ads->city_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->city_id->EditValue)) {
	$arwrk = $car_ads->city_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->city_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->city_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->city_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->city_id->CurrentValue) ?>" selected><?php echo $car_ads->city_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->city_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->city_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->city_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->city_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_city_id" id="s_x_city_id" value="<?php echo $car_ads->city_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->make_id->Visible) { // make_id ?>
	<div id="r_make_id" class="form-group">
		<label for="x_make_id" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_make_id"><?php echo $car_ads->make_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_make_id" id="z_make_id" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->make_id->CellAttributes() ?>>
			<span id="el_car_ads_make_id">
<select data-table="car_ads" data-field="x_make_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->make_id->DisplayValueSeparator) ? json_encode($car_ads->make_id->DisplayValueSeparator) : $car_ads->make_id->DisplayValueSeparator) ?>" id="x_make_id" name="x_make_id"<?php echo $car_ads->make_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->make_id->EditValue)) {
	$arwrk = $car_ads->make_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->make_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->make_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->make_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->make_id->CurrentValue) ?>" selected><?php echo $car_ads->make_id->CurrentValue ?></option>
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
$car_ads->make_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->make_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->make_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->make_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_make_id" id="s_x_make_id" value="<?php echo $car_ads->make_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->model_id->Visible) { // model_id ?>
	<div id="r_model_id" class="form-group">
		<label for="x_model_id" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_model_id"><?php echo $car_ads->model_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_model_id" id="z_model_id" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->model_id->CellAttributes() ?>>
			<span id="el_car_ads_model_id">
<select data-table="car_ads" data-field="x_model_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->model_id->DisplayValueSeparator) ? json_encode($car_ads->model_id->DisplayValueSeparator) : $car_ads->model_id->DisplayValueSeparator) ?>" id="x_model_id" name="x_model_id"<?php echo $car_ads->model_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->model_id->EditValue)) {
	$arwrk = $car_ads->model_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->model_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->model_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->model_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->model_id->CurrentValue) ?>" selected><?php echo $car_ads->model_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_models`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->model_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->model_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->model_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->model_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_model_id" id="s_x_model_id" value="<?php echo $car_ads->model_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->version_id->Visible) { // version_id ?>
	<div id="r_version_id" class="form-group">
		<label for="x_version_id" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_version_id"><?php echo $car_ads->version_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_version_id" id="z_version_id" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->version_id->CellAttributes() ?>>
			<span id="el_car_ads_version_id">
<select data-table="car_ads" data-field="x_version_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->version_id->DisplayValueSeparator) ? json_encode($car_ads->version_id->DisplayValueSeparator) : $car_ads->version_id->DisplayValueSeparator) ?>" id="x_version_id" name="x_version_id"<?php echo $car_ads->version_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->version_id->EditValue)) {
	$arwrk = $car_ads->version_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->version_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->version_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->version_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->version_id->CurrentValue) ?>" selected><?php echo $car_ads->version_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_car_versions`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->version_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->version_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->version_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->version_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_version_id" id="s_x_version_id" value="<?php echo $car_ads->version_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->milage->Visible) { // milage ?>
	<div id="r_milage" class="form-group">
		<label for="x_milage" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_milage"><?php echo $car_ads->milage->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_milage" id="z_milage" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->milage->CellAttributes() ?>>
			<span id="el_car_ads_milage">
<input type="text" data-table="car_ads" data-field="x_milage" name="x_milage" id="x_milage" size="30" placeholder="<?php echo ew_HtmlEncode($car_ads->milage->getPlaceHolder()) ?>" value="<?php echo $car_ads->milage->EditValue ?>"<?php echo $car_ads->milage->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->color_id->Visible) { // color_id ?>
	<div id="r_color_id" class="form-group">
		<label for="x_color_id" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_color_id"><?php echo $car_ads->color_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_color_id" id="z_color_id" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->color_id->CellAttributes() ?>>
			<span id="el_car_ads_color_id">
<select data-table="car_ads" data-field="x_color_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->color_id->DisplayValueSeparator) ? json_encode($car_ads->color_id->DisplayValueSeparator) : $car_ads->color_id->DisplayValueSeparator) ?>" id="x_color_id" name="x_color_id"<?php echo $car_ads->color_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->color_id->EditValue)) {
	$arwrk = $car_ads->color_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->color_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->color_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->color_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->color_id->CurrentValue) ?>" selected><?php echo $car_ads->color_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_body_colors`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->color_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->color_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->color_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->color_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_color_id" id="s_x_color_id" value="<?php echo $car_ads->color_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->demand_price->Visible) { // demand_price ?>
	<div id="r_demand_price" class="form-group">
		<label for="x_demand_price" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_demand_price"><?php echo $car_ads->demand_price->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_demand_price" id="z_demand_price" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->demand_price->CellAttributes() ?>>
			<span id="el_car_ads_demand_price">
<input type="text" data-table="car_ads" data-field="x_demand_price" name="x_demand_price" id="x_demand_price" size="30" placeholder="<?php echo ew_HtmlEncode($car_ads->demand_price->getPlaceHolder()) ?>" value="<?php echo $car_ads->demand_price->EditValue ?>"<?php echo $car_ads->demand_price->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->details->Visible) { // details ?>
	<div id="r_details" class="form-group">
		<label for="x_details" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_details"><?php echo $car_ads->details->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_details" id="z_details" value="LIKE"></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->details->CellAttributes() ?>>
			<span id="el_car_ads_details">
<input type="text" data-table="car_ads" data-field="x_details" name="x_details" id="x_details" size="35" placeholder="<?php echo ew_HtmlEncode($car_ads->details->getPlaceHolder()) ?>" value="<?php echo $car_ads->details->EditValue ?>"<?php echo $car_ads->details->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->engine_type_id->Visible) { // engine_type_id ?>
	<div id="r_engine_type_id" class="form-group">
		<label for="x_engine_type_id" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_engine_type_id"><?php echo $car_ads->engine_type_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_engine_type_id" id="z_engine_type_id" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->engine_type_id->CellAttributes() ?>>
			<span id="el_car_ads_engine_type_id">
<select data-table="car_ads" data-field="x_engine_type_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->engine_type_id->DisplayValueSeparator) ? json_encode($car_ads->engine_type_id->DisplayValueSeparator) : $car_ads->engine_type_id->DisplayValueSeparator) ?>" id="x_engine_type_id" name="x_engine_type_id"<?php echo $car_ads->engine_type_id->EditAttributes() ?>>
<?php
if (is_array($car_ads->engine_type_id->EditValue)) {
	$arwrk = $car_ads->engine_type_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($car_ads->engine_type_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $car_ads->engine_type_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($car_ads->engine_type_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($car_ads->engine_type_id->CurrentValue) ?>" selected><?php echo $car_ads->engine_type_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_engine_types`";
$sWhereWrk = "";
$lookuptblfilter = "`status`=1";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$car_ads->engine_type_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$car_ads->engine_type_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$car_ads->Lookup_Selecting($car_ads->engine_type_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $car_ads->engine_type_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_engine_type_id" id="s_x_engine_type_id" value="<?php echo $car_ads->engine_type_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->engine_capicity->Visible) { // engine_capicity ?>
	<div id="r_engine_capicity" class="form-group">
		<label for="x_engine_capicity" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_engine_capicity"><?php echo $car_ads->engine_capicity->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_engine_capicity" id="z_engine_capicity" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->engine_capicity->CellAttributes() ?>>
			<span id="el_car_ads_engine_capicity">
<input type="text" data-table="car_ads" data-field="x_engine_capicity" name="x_engine_capicity" id="x_engine_capicity" size="30" placeholder="<?php echo ew_HtmlEncode($car_ads->engine_capicity->getPlaceHolder()) ?>" value="<?php echo $car_ads->engine_capicity->EditValue ?>"<?php echo $car_ads->engine_capicity->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->transmition->Visible) { // transmition ?>
	<div id="r_transmition" class="form-group">
		<label class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_transmition"><?php echo $car_ads->transmition->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_transmition" id="z_transmition" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->transmition->CellAttributes() ?>>
			<span id="el_car_ads_transmition">
<div id="tp_x_transmition" class="ewTemplate"><input type="radio" data-table="car_ads" data-field="x_transmition" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->transmition->DisplayValueSeparator) ? json_encode($car_ads->transmition->DisplayValueSeparator) : $car_ads->transmition->DisplayValueSeparator) ?>" name="x_transmition" id="x_transmition" value="{value}"<?php echo $car_ads->transmition->EditAttributes() ?>></div>
<div id="dsl_x_transmition" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $car_ads->transmition->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($car_ads->transmition->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_transmition" name="x_transmition" id="x_transmition_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $car_ads->transmition->EditAttributes() ?>><?php echo $car_ads->transmition->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($car_ads->transmition->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_transmition" name="x_transmition" id="x_transmition_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($car_ads->transmition->CurrentValue) ?>" checked<?php echo $car_ads->transmition->EditAttributes() ?>><?php echo $car_ads->transmition->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->assembly->Visible) { // assembly ?>
	<div id="r_assembly" class="form-group">
		<label class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_assembly"><?php echo $car_ads->assembly->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_assembly" id="z_assembly" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->assembly->CellAttributes() ?>>
			<span id="el_car_ads_assembly">
<div id="tp_x_assembly" class="ewTemplate"><input type="radio" data-table="car_ads" data-field="x_assembly" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->assembly->DisplayValueSeparator) ? json_encode($car_ads->assembly->DisplayValueSeparator) : $car_ads->assembly->DisplayValueSeparator) ?>" name="x_assembly" id="x_assembly" value="{value}"<?php echo $car_ads->assembly->EditAttributes() ?>></div>
<div id="dsl_x_assembly" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $car_ads->assembly->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($car_ads->assembly->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_assembly" name="x_assembly" id="x_assembly_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $car_ads->assembly->EditAttributes() ?>><?php echo $car_ads->assembly->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($car_ads->assembly->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_assembly" name="x_assembly" id="x_assembly_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($car_ads->assembly->CurrentValue) ?>" checked<?php echo $car_ads->assembly->EditAttributes() ?>><?php echo $car_ads->assembly->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->mobile_number->Visible) { // mobile_number ?>
	<div id="r_mobile_number" class="form-group">
		<label for="x_mobile_number" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_mobile_number"><?php echo $car_ads->mobile_number->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobile_number" id="z_mobile_number" value="LIKE"></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->mobile_number->CellAttributes() ?>>
			<span id="el_car_ads_mobile_number">
<input type="text" data-table="car_ads" data-field="x_mobile_number" name="x_mobile_number" id="x_mobile_number" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($car_ads->mobile_number->getPlaceHolder()) ?>" value="<?php echo $car_ads->mobile_number->EditValue ?>"<?php echo $car_ads->mobile_number->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->secondary_number->Visible) { // secondary_number ?>
	<div id="r_secondary_number" class="form-group">
		<label for="x_secondary_number" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_secondary_number"><?php echo $car_ads->secondary_number->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_secondary_number" id="z_secondary_number" value="LIKE"></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->secondary_number->CellAttributes() ?>>
			<span id="el_car_ads_secondary_number">
<input type="text" data-table="car_ads" data-field="x_secondary_number" name="x_secondary_number" id="x_secondary_number" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($car_ads->secondary_number->getPlaceHolder()) ?>" value="<?php echo $car_ads->secondary_number->EditValue ?>"<?php echo $car_ads->secondary_number->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label for="x__email" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads__email"><?php echo $car_ads->_email->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__email" id="z__email" value="LIKE"></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->_email->CellAttributes() ?>>
			<span id="el_car_ads__email">
<input type="text" data-table="car_ads" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($car_ads->_email->getPlaceHolder()) ?>" value="<?php echo $car_ads->_email->EditValue ?>"<?php echo $car_ads->_email->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label for="x_name" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_name"><?php echo $car_ads->name->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_name" id="z_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->name->CellAttributes() ?>>
			<span id="el_car_ads_name">
<input type="text" data-table="car_ads" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($car_ads->name->getPlaceHolder()) ?>" value="<?php echo $car_ads->name->EditValue ?>"<?php echo $car_ads->name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label for="x_address" class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_address"><?php echo $car_ads->address->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_address" id="z_address" value="LIKE"></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->address->CellAttributes() ?>>
			<span id="el_car_ads_address">
<input type="text" data-table="car_ads" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($car_ads->address->getPlaceHolder()) ?>" value="<?php echo $car_ads->address->EditValue ?>"<?php echo $car_ads->address->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->allow_whatsapp->Visible) { // allow_whatsapp ?>
	<div id="r_allow_whatsapp" class="form-group">
		<label class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_allow_whatsapp"><?php echo $car_ads->allow_whatsapp->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_allow_whatsapp" id="z_allow_whatsapp" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->allow_whatsapp->CellAttributes() ?>>
			<span id="el_car_ads_allow_whatsapp">
<div id="tp_x_allow_whatsapp" class="ewTemplate"><input type="radio" data-table="car_ads" data-field="x_allow_whatsapp" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->allow_whatsapp->DisplayValueSeparator) ? json_encode($car_ads->allow_whatsapp->DisplayValueSeparator) : $car_ads->allow_whatsapp->DisplayValueSeparator) ?>" name="x_allow_whatsapp" id="x_allow_whatsapp" value="{value}"<?php echo $car_ads->allow_whatsapp->EditAttributes() ?>></div>
<div id="dsl_x_allow_whatsapp" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $car_ads->allow_whatsapp->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($car_ads->allow_whatsapp->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_allow_whatsapp" name="x_allow_whatsapp" id="x_allow_whatsapp_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $car_ads->allow_whatsapp->EditAttributes() ?>><?php echo $car_ads->allow_whatsapp->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($car_ads->allow_whatsapp->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_allow_whatsapp" name="x_allow_whatsapp" id="x_allow_whatsapp_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($car_ads->allow_whatsapp->CurrentValue) ?>" checked<?php echo $car_ads->allow_whatsapp->EditAttributes() ?>><?php echo $car_ads->allow_whatsapp->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($car_ads->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $car_ads_search->SearchLabelClass ?>"><span id="elh_car_ads_status"><?php echo $car_ads->status->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $car_ads_search->SearchRightColumnClass ?>"><div<?php echo $car_ads->status->CellAttributes() ?>>
			<span id="el_car_ads_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="car_ads" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($car_ads->status->DisplayValueSeparator) ? json_encode($car_ads->status->DisplayValueSeparator) : $car_ads->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $car_ads->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $car_ads->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($car_ads->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $car_ads->status->EditAttributes() ?>><?php echo $car_ads->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($car_ads->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="car_ads" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($car_ads->status->CurrentValue) ?>" checked<?php echo $car_ads->status->EditAttributes() ?>><?php echo $car_ads->status->CurrentValue ?></label>
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
<?php if (!$car_ads_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcar_adssearch.Init();
</script>
<?php
$car_ads_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$car_ads_search->Page_Terminate();
?>
