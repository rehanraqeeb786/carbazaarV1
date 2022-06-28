<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "vw_current_feature_adsinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$vw_current_feature_ads_search = NULL; // Initialize page object first

class cvw_current_feature_ads_search extends cvw_current_feature_ads {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'vw_current_feature_ads';

	// Page object name
	var $PageObjName = 'vw_current_feature_ads_search';

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

		// Table object (vw_current_feature_ads)
		if (!isset($GLOBALS["vw_current_feature_ads"]) || get_class($GLOBALS["vw_current_feature_ads"]) == "cvw_current_feature_ads") {
			$GLOBALS["vw_current_feature_ads"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vw_current_feature_ads"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vw_current_feature_ads', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("vw_current_feature_adslist.php"));
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
		global $EW_EXPORT, $vw_current_feature_ads;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($vw_current_feature_ads);
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
						$sSrchStr = "vw_current_feature_adslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->demand_price); // demand_price
		$this->BuildSearchUrl($sSrchUrl, $this->mobile_number); // mobile_number
		$this->BuildSearchUrl($sSrchUrl, $this->ad_payment_from); // ad_payment_from
		$this->BuildSearchUrl($sSrchUrl, $this->ad_payment_till); // ad_payment_till
		$this->BuildSearchUrl($sSrchUrl, $this->amount); // amount
		$this->BuildSearchUrl($sSrchUrl, $this->package_id); // package_id
		$this->BuildSearchUrl($sSrchUrl, $this->pay_method_id); // pay_method_id
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

		// demand_price
		$this->demand_price->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_demand_price"));
		$this->demand_price->AdvancedSearch->SearchOperator = $objForm->GetValue("z_demand_price");

		// mobile_number
		$this->mobile_number->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_mobile_number"));
		$this->mobile_number->AdvancedSearch->SearchOperator = $objForm->GetValue("z_mobile_number");

		// ad_payment_from
		$this->ad_payment_from->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ad_payment_from"));
		$this->ad_payment_from->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ad_payment_from");

		// ad_payment_till
		$this->ad_payment_till->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ad_payment_till"));
		$this->ad_payment_till->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ad_payment_till");

		// amount
		$this->amount->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_amount"));
		$this->amount->AdvancedSearch->SearchOperator = $objForm->GetValue("z_amount");

		// package_id
		$this->package_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_package_id"));
		$this->package_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_package_id");

		// pay_method_id
		$this->pay_method_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_pay_method_id"));
		$this->pay_method_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_pay_method_id");
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
		// demand_price
		// mobile_number
		// ad_payment_from
		// ad_payment_till
		// amount
		// package_id
		// pay_method_id

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

		// demand_price
		$this->demand_price->ViewValue = $this->demand_price->CurrentValue;
		$this->demand_price->ViewCustomAttributes = "";

		// mobile_number
		$this->mobile_number->ViewValue = $this->mobile_number->CurrentValue;
		$this->mobile_number->ViewCustomAttributes = "";

		// ad_payment_from
		$this->ad_payment_from->ViewValue = $this->ad_payment_from->CurrentValue;
		$this->ad_payment_from->ViewValue = ew_FormatDateTime($this->ad_payment_from->ViewValue, 5);
		$this->ad_payment_from->ViewCustomAttributes = "";

		// ad_payment_till
		$this->ad_payment_till->ViewValue = $this->ad_payment_till->CurrentValue;
		$this->ad_payment_till->ViewValue = ew_FormatDateTime($this->ad_payment_till->ViewValue, 5);
		$this->ad_payment_till->ViewCustomAttributes = "";

		// amount
		$this->amount->ViewValue = $this->amount->CurrentValue;
		$this->amount->ViewCustomAttributes = "";

		// package_id
		$this->package_id->ViewValue = $this->package_id->CurrentValue;
		if (strval($this->package_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->package_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `number_of_days` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `packages`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->package_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->package_id->ViewValue = $this->package_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->package_id->ViewValue = $this->package_id->CurrentValue;
			}
		} else {
			$this->package_id->ViewValue = NULL;
		}
		$this->package_id->ViewCustomAttributes = "";

		// pay_method_id
		$this->pay_method_id->ViewValue = $this->pay_method_id->CurrentValue;
		if (strval($this->pay_method_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->pay_method_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pay_methods`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pay_method_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pay_method_id->ViewValue = $this->pay_method_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pay_method_id->ViewValue = $this->pay_method_id->CurrentValue;
			}
		} else {
			$this->pay_method_id->ViewValue = NULL;
		}
		$this->pay_method_id->ViewCustomAttributes = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// ad_title
			$this->ad_title->LinkCustomAttributes = "";
			$this->ad_title->HrefValue = "";
			$this->ad_title->TooltipValue = "";

			// demand_price
			$this->demand_price->LinkCustomAttributes = "";
			$this->demand_price->HrefValue = "";
			$this->demand_price->TooltipValue = "";

			// mobile_number
			$this->mobile_number->LinkCustomAttributes = "";
			$this->mobile_number->HrefValue = "";
			$this->mobile_number->TooltipValue = "";

			// ad_payment_from
			$this->ad_payment_from->LinkCustomAttributes = "";
			$this->ad_payment_from->HrefValue = "";
			$this->ad_payment_from->TooltipValue = "";

			// ad_payment_till
			$this->ad_payment_till->LinkCustomAttributes = "";
			$this->ad_payment_till->HrefValue = "";
			$this->ad_payment_till->TooltipValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";
			$this->amount->TooltipValue = "";

			// package_id
			$this->package_id->LinkCustomAttributes = "";
			$this->package_id->HrefValue = "";
			$this->package_id->TooltipValue = "";

			// pay_method_id
			$this->pay_method_id->LinkCustomAttributes = "";
			$this->pay_method_id->HrefValue = "";
			$this->pay_method_id->TooltipValue = "";
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

			// demand_price
			$this->demand_price->EditAttrs["class"] = "form-control";
			$this->demand_price->EditCustomAttributes = "";
			$this->demand_price->EditValue = ew_HtmlEncode($this->demand_price->AdvancedSearch->SearchValue);
			$this->demand_price->PlaceHolder = ew_RemoveHtml($this->demand_price->FldCaption());

			// mobile_number
			$this->mobile_number->EditAttrs["class"] = "form-control";
			$this->mobile_number->EditCustomAttributes = "";
			$this->mobile_number->EditValue = ew_HtmlEncode($this->mobile_number->AdvancedSearch->SearchValue);
			$this->mobile_number->PlaceHolder = ew_RemoveHtml($this->mobile_number->FldCaption());

			// ad_payment_from
			$this->ad_payment_from->EditAttrs["class"] = "form-control";
			$this->ad_payment_from->EditCustomAttributes = "";
			$this->ad_payment_from->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->ad_payment_from->AdvancedSearch->SearchValue, 5), 5));
			$this->ad_payment_from->PlaceHolder = ew_RemoveHtml($this->ad_payment_from->FldCaption());

			// ad_payment_till
			$this->ad_payment_till->EditAttrs["class"] = "form-control";
			$this->ad_payment_till->EditCustomAttributes = "";
			$this->ad_payment_till->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->ad_payment_till->AdvancedSearch->SearchValue, 5), 5));
			$this->ad_payment_till->PlaceHolder = ew_RemoveHtml($this->ad_payment_till->FldCaption());

			// amount
			$this->amount->EditAttrs["class"] = "form-control";
			$this->amount->EditCustomAttributes = "";
			$this->amount->EditValue = ew_HtmlEncode($this->amount->AdvancedSearch->SearchValue);
			$this->amount->PlaceHolder = ew_RemoveHtml($this->amount->FldCaption());

			// package_id
			$this->package_id->EditAttrs["class"] = "form-control";
			$this->package_id->EditCustomAttributes = "";
			$this->package_id->EditValue = ew_HtmlEncode($this->package_id->AdvancedSearch->SearchValue);
			if (strval($this->package_id->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->package_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `number_of_days` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `packages`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->package_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->package_id->EditValue = $this->package_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->package_id->EditValue = ew_HtmlEncode($this->package_id->AdvancedSearch->SearchValue);
				}
			} else {
				$this->package_id->EditValue = NULL;
			}
			$this->package_id->PlaceHolder = ew_RemoveHtml($this->package_id->FldCaption());

			// pay_method_id
			$this->pay_method_id->EditAttrs["class"] = "form-control";
			$this->pay_method_id->EditCustomAttributes = "";
			$this->pay_method_id->EditValue = ew_HtmlEncode($this->pay_method_id->AdvancedSearch->SearchValue);
			if (strval($this->pay_method_id->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->pay_method_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pay_methods`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->pay_method_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->pay_method_id->EditValue = $this->pay_method_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->pay_method_id->EditValue = ew_HtmlEncode($this->pay_method_id->AdvancedSearch->SearchValue);
				}
			} else {
				$this->pay_method_id->EditValue = NULL;
			}
			$this->pay_method_id->PlaceHolder = ew_RemoveHtml($this->pay_method_id->FldCaption());
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
		if (!ew_CheckInteger($this->demand_price->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->demand_price->FldErrMsg());
		}
		if (!ew_CheckDate($this->ad_payment_from->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->ad_payment_from->FldErrMsg());
		}
		if (!ew_CheckDate($this->ad_payment_till->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->ad_payment_till->FldErrMsg());
		}
		if (!ew_CheckInteger($this->amount->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->amount->FldErrMsg());
		}
		if (!ew_CheckInteger($this->package_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->package_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->pay_method_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->pay_method_id->FldErrMsg());
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
		$this->demand_price->AdvancedSearch->Load();
		$this->mobile_number->AdvancedSearch->Load();
		$this->ad_payment_from->AdvancedSearch->Load();
		$this->ad_payment_till->AdvancedSearch->Load();
		$this->amount->AdvancedSearch->Load();
		$this->package_id->AdvancedSearch->Load();
		$this->pay_method_id->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("vw_current_feature_adslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($vw_current_feature_ads_search)) $vw_current_feature_ads_search = new cvw_current_feature_ads_search();

// Page init
$vw_current_feature_ads_search->Page_Init();

// Page main
$vw_current_feature_ads_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vw_current_feature_ads_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($vw_current_feature_ads_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fvw_current_feature_adssearch = new ew_Form("fvw_current_feature_adssearch", "search");
<?php } else { ?>
var CurrentForm = fvw_current_feature_adssearch = new ew_Form("fvw_current_feature_adssearch", "search");
<?php } ?>

// Form_CustomValidate event
fvw_current_feature_adssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvw_current_feature_adssearch.ValidateRequired = true;
<?php } else { ?>
fvw_current_feature_adssearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvw_current_feature_adssearch.Lists["x_user_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","x__email","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fvw_current_feature_adssearch.Lists["x_package_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_number_of_days","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fvw_current_feature_adssearch.Lists["x_pay_method_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
// Validate function for search

fvw_current_feature_adssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_user_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($vw_current_feature_ads->user_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_demand_price");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($vw_current_feature_ads->demand_price->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_ad_payment_from");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($vw_current_feature_ads->ad_payment_from->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_ad_payment_till");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($vw_current_feature_ads->ad_payment_till->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_amount");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($vw_current_feature_ads->amount->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_package_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($vw_current_feature_ads->package_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_pay_method_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($vw_current_feature_ads->pay_method_id->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$vw_current_feature_ads_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $vw_current_feature_ads_search->ShowPageHeader(); ?>
<?php
$vw_current_feature_ads_search->ShowMessage();
?>
<form name="fvw_current_feature_adssearch" id="fvw_current_feature_adssearch" class="<?php echo $vw_current_feature_ads_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vw_current_feature_ads_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vw_current_feature_ads_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vw_current_feature_ads">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($vw_current_feature_ads_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($vw_current_feature_ads->user_id->Visible) { // user_id ?>
	<div id="r_user_id" class="form-group">
		<label class="<?php echo $vw_current_feature_ads_search->SearchLabelClass ?>"><span id="elh_vw_current_feature_ads_user_id"><?php echo $vw_current_feature_ads->user_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_user_id" id="z_user_id" value="="></p>
		</label>
		<div class="<?php echo $vw_current_feature_ads_search->SearchRightColumnClass ?>"><div<?php echo $vw_current_feature_ads->user_id->CellAttributes() ?>>
			<span id="el_vw_current_feature_ads_user_id">
<?php
$wrkonchange = trim(" " . @$vw_current_feature_ads->user_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$vw_current_feature_ads->user_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_user_id" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_user_id" id="sv_x_user_id" value="<?php echo $vw_current_feature_ads->user_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->user_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->user_id->getPlaceHolder()) ?>"<?php echo $vw_current_feature_ads->user_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vw_current_feature_ads" data-field="x_user_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($vw_current_feature_ads->user_id->DisplayValueSeparator) ? json_encode($vw_current_feature_ads->user_id->DisplayValueSeparator) : $vw_current_feature_ads->user_id->DisplayValueSeparator) ?>" name="x_user_id" id="x_user_id" value="<?php echo ew_HtmlEncode($vw_current_feature_ads->user_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, `email` AS `Disp2Fld` FROM `users`";
$sWhereWrk = "`name` LIKE '{query_value}%' OR CONCAT(`name`,'" . ew_ValueSeparator(1, $Page->user_id) . "',`email`) LIKE '{query_value}%'";
$vw_current_feature_ads->Lookup_Selecting($vw_current_feature_ads->user_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_user_id" id="q_x_user_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fvw_current_feature_adssearch.CreateAutoSuggest({"id":"x_user_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($vw_current_feature_ads->ad_title->Visible) { // ad_title ?>
	<div id="r_ad_title" class="form-group">
		<label for="x_ad_title" class="<?php echo $vw_current_feature_ads_search->SearchLabelClass ?>"><span id="elh_vw_current_feature_ads_ad_title"><?php echo $vw_current_feature_ads->ad_title->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ad_title" id="z_ad_title" value="LIKE"></p>
		</label>
		<div class="<?php echo $vw_current_feature_ads_search->SearchRightColumnClass ?>"><div<?php echo $vw_current_feature_ads->ad_title->CellAttributes() ?>>
			<span id="el_vw_current_feature_ads_ad_title">
<input type="text" data-table="vw_current_feature_ads" data-field="x_ad_title" name="x_ad_title" id="x_ad_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->ad_title->getPlaceHolder()) ?>" value="<?php echo $vw_current_feature_ads->ad_title->EditValue ?>"<?php echo $vw_current_feature_ads->ad_title->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($vw_current_feature_ads->demand_price->Visible) { // demand_price ?>
	<div id="r_demand_price" class="form-group">
		<label for="x_demand_price" class="<?php echo $vw_current_feature_ads_search->SearchLabelClass ?>"><span id="elh_vw_current_feature_ads_demand_price"><?php echo $vw_current_feature_ads->demand_price->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_demand_price" id="z_demand_price" value="="></p>
		</label>
		<div class="<?php echo $vw_current_feature_ads_search->SearchRightColumnClass ?>"><div<?php echo $vw_current_feature_ads->demand_price->CellAttributes() ?>>
			<span id="el_vw_current_feature_ads_demand_price">
<input type="text" data-table="vw_current_feature_ads" data-field="x_demand_price" name="x_demand_price" id="x_demand_price" size="30" placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->demand_price->getPlaceHolder()) ?>" value="<?php echo $vw_current_feature_ads->demand_price->EditValue ?>"<?php echo $vw_current_feature_ads->demand_price->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($vw_current_feature_ads->mobile_number->Visible) { // mobile_number ?>
	<div id="r_mobile_number" class="form-group">
		<label for="x_mobile_number" class="<?php echo $vw_current_feature_ads_search->SearchLabelClass ?>"><span id="elh_vw_current_feature_ads_mobile_number"><?php echo $vw_current_feature_ads->mobile_number->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobile_number" id="z_mobile_number" value="LIKE"></p>
		</label>
		<div class="<?php echo $vw_current_feature_ads_search->SearchRightColumnClass ?>"><div<?php echo $vw_current_feature_ads->mobile_number->CellAttributes() ?>>
			<span id="el_vw_current_feature_ads_mobile_number">
<input type="text" data-table="vw_current_feature_ads" data-field="x_mobile_number" name="x_mobile_number" id="x_mobile_number" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->mobile_number->getPlaceHolder()) ?>" value="<?php echo $vw_current_feature_ads->mobile_number->EditValue ?>"<?php echo $vw_current_feature_ads->mobile_number->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($vw_current_feature_ads->ad_payment_from->Visible) { // ad_payment_from ?>
	<div id="r_ad_payment_from" class="form-group">
		<label for="x_ad_payment_from" class="<?php echo $vw_current_feature_ads_search->SearchLabelClass ?>"><span id="elh_vw_current_feature_ads_ad_payment_from"><?php echo $vw_current_feature_ads->ad_payment_from->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_ad_payment_from" id="z_ad_payment_from" value="="></p>
		</label>
		<div class="<?php echo $vw_current_feature_ads_search->SearchRightColumnClass ?>"><div<?php echo $vw_current_feature_ads->ad_payment_from->CellAttributes() ?>>
			<span id="el_vw_current_feature_ads_ad_payment_from">
<input type="text" data-table="vw_current_feature_ads" data-field="x_ad_payment_from" data-format="5" name="x_ad_payment_from" id="x_ad_payment_from" placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->ad_payment_from->getPlaceHolder()) ?>" value="<?php echo $vw_current_feature_ads->ad_payment_from->EditValue ?>"<?php echo $vw_current_feature_ads->ad_payment_from->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($vw_current_feature_ads->ad_payment_till->Visible) { // ad_payment_till ?>
	<div id="r_ad_payment_till" class="form-group">
		<label for="x_ad_payment_till" class="<?php echo $vw_current_feature_ads_search->SearchLabelClass ?>"><span id="elh_vw_current_feature_ads_ad_payment_till"><?php echo $vw_current_feature_ads->ad_payment_till->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_ad_payment_till" id="z_ad_payment_till" value="="></p>
		</label>
		<div class="<?php echo $vw_current_feature_ads_search->SearchRightColumnClass ?>"><div<?php echo $vw_current_feature_ads->ad_payment_till->CellAttributes() ?>>
			<span id="el_vw_current_feature_ads_ad_payment_till">
<input type="text" data-table="vw_current_feature_ads" data-field="x_ad_payment_till" data-format="5" name="x_ad_payment_till" id="x_ad_payment_till" placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->ad_payment_till->getPlaceHolder()) ?>" value="<?php echo $vw_current_feature_ads->ad_payment_till->EditValue ?>"<?php echo $vw_current_feature_ads->ad_payment_till->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($vw_current_feature_ads->amount->Visible) { // amount ?>
	<div id="r_amount" class="form-group">
		<label for="x_amount" class="<?php echo $vw_current_feature_ads_search->SearchLabelClass ?>"><span id="elh_vw_current_feature_ads_amount"><?php echo $vw_current_feature_ads->amount->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_amount" id="z_amount" value="="></p>
		</label>
		<div class="<?php echo $vw_current_feature_ads_search->SearchRightColumnClass ?>"><div<?php echo $vw_current_feature_ads->amount->CellAttributes() ?>>
			<span id="el_vw_current_feature_ads_amount">
<input type="text" data-table="vw_current_feature_ads" data-field="x_amount" name="x_amount" id="x_amount" size="30" placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->amount->getPlaceHolder()) ?>" value="<?php echo $vw_current_feature_ads->amount->EditValue ?>"<?php echo $vw_current_feature_ads->amount->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($vw_current_feature_ads->package_id->Visible) { // package_id ?>
	<div id="r_package_id" class="form-group">
		<label class="<?php echo $vw_current_feature_ads_search->SearchLabelClass ?>"><span id="elh_vw_current_feature_ads_package_id"><?php echo $vw_current_feature_ads->package_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_package_id" id="z_package_id" value="="></p>
		</label>
		<div class="<?php echo $vw_current_feature_ads_search->SearchRightColumnClass ?>"><div<?php echo $vw_current_feature_ads->package_id->CellAttributes() ?>>
			<span id="el_vw_current_feature_ads_package_id">
<?php
$wrkonchange = trim(" " . @$vw_current_feature_ads->package_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$vw_current_feature_ads->package_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_package_id" style="white-space: nowrap; z-index: 8910">
	<input type="text" name="sv_x_package_id" id="sv_x_package_id" value="<?php echo $vw_current_feature_ads->package_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->package_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->package_id->getPlaceHolder()) ?>"<?php echo $vw_current_feature_ads->package_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vw_current_feature_ads" data-field="x_package_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($vw_current_feature_ads->package_id->DisplayValueSeparator) ? json_encode($vw_current_feature_ads->package_id->DisplayValueSeparator) : $vw_current_feature_ads->package_id->DisplayValueSeparator) ?>" name="x_package_id" id="x_package_id" value="<?php echo ew_HtmlEncode($vw_current_feature_ads->package_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `number_of_days` AS `DispFld` FROM `packages`";
$sWhereWrk = "`number_of_days` LIKE '{query_value}%'";
$vw_current_feature_ads->Lookup_Selecting($vw_current_feature_ads->package_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_package_id" id="q_x_package_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fvw_current_feature_adssearch.CreateAutoSuggest({"id":"x_package_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($vw_current_feature_ads->pay_method_id->Visible) { // pay_method_id ?>
	<div id="r_pay_method_id" class="form-group">
		<label class="<?php echo $vw_current_feature_ads_search->SearchLabelClass ?>"><span id="elh_vw_current_feature_ads_pay_method_id"><?php echo $vw_current_feature_ads->pay_method_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_pay_method_id" id="z_pay_method_id" value="="></p>
		</label>
		<div class="<?php echo $vw_current_feature_ads_search->SearchRightColumnClass ?>"><div<?php echo $vw_current_feature_ads->pay_method_id->CellAttributes() ?>>
			<span id="el_vw_current_feature_ads_pay_method_id">
<?php
$wrkonchange = trim(" " . @$vw_current_feature_ads->pay_method_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$vw_current_feature_ads->pay_method_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_pay_method_id" style="white-space: nowrap; z-index: 8900">
	<input type="text" name="sv_x_pay_method_id" id="sv_x_pay_method_id" value="<?php echo $vw_current_feature_ads->pay_method_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->pay_method_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($vw_current_feature_ads->pay_method_id->getPlaceHolder()) ?>"<?php echo $vw_current_feature_ads->pay_method_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vw_current_feature_ads" data-field="x_pay_method_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($vw_current_feature_ads->pay_method_id->DisplayValueSeparator) ? json_encode($vw_current_feature_ads->pay_method_id->DisplayValueSeparator) : $vw_current_feature_ads->pay_method_id->DisplayValueSeparator) ?>" name="x_pay_method_id" id="x_pay_method_id" value="<?php echo ew_HtmlEncode($vw_current_feature_ads->pay_method_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `title` AS `DispFld` FROM `pay_methods`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$vw_current_feature_ads->Lookup_Selecting($vw_current_feature_ads->pay_method_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_pay_method_id" id="q_x_pay_method_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fvw_current_feature_adssearch.CreateAutoSuggest({"id":"x_pay_method_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$vw_current_feature_ads_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fvw_current_feature_adssearch.Init();
</script>
<?php
$vw_current_feature_ads_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vw_current_feature_ads_search->Page_Terminate();
?>
