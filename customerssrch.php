<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "customersinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$customers_search = NULL; // Initialize page object first

class ccustomers_search extends ccustomers {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'customers';

	// Page object name
	var $PageObjName = 'customers_search';

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

		// Table object (customers)
		if (!isset($GLOBALS["customers"]) || get_class($GLOBALS["customers"]) == "ccustomers") {
			$GLOBALS["customers"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["customers"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'customers', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("customerslist.php"));
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
		global $EW_EXPORT, $customers;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($customers);
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
						$sSrchStr = "customerslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->email_id); // email_id
		$this->BuildSearchUrl($sSrchUrl, $this->customer_name); // customer_name
		$this->BuildSearchUrl($sSrchUrl, $this->city_id, TRUE); // city_id
		$this->BuildSearchUrl($sSrchUrl, $this->customer_type); // customer_type
		$this->BuildSearchUrl($sSrchUrl, $this->mobile_num); // mobile_num
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
		// email_id

		$this->email_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_email_id"));
		$this->email_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_email_id");

		// customer_name
		$this->customer_name->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_customer_name"));
		$this->customer_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_customer_name");

		// city_id
		$this->city_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_city_id"));
		$this->city_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_city_id");

		// customer_type
		$this->customer_type->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_customer_type"));
		$this->customer_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_customer_type");

		// mobile_num
		$this->mobile_num->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_mobile_num"));
		$this->mobile_num->AdvancedSearch->SearchOperator = $objForm->GetValue("z_mobile_num");

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
		// email_id
		// password
		// customer_name
		// profile_pic
		// dob
		// city_id
		// customer_type
		// mobile_num
		// status
		// createdAt
		// updatedAt

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// email_id
		$this->email_id->ViewValue = $this->email_id->CurrentValue;
		$this->email_id->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// customer_name
		$this->customer_name->ViewValue = $this->customer_name->CurrentValue;
		$this->customer_name->ViewCustomAttributes = "";

		// profile_pic
		if (!ew_Empty($this->profile_pic->Upload->DbValue)) {
			$this->profile_pic->ImageAlt = $this->profile_pic->FldAlt();
			$this->profile_pic->ViewValue = $this->profile_pic->Upload->DbValue;
		} else {
			$this->profile_pic->ViewValue = "";
		}
		$this->profile_pic->ViewCustomAttributes = "";

		// dob
		$this->dob->ViewValue = $this->dob->CurrentValue;
		$this->dob->ViewValue = ew_FormatDateTime($this->dob->ViewValue, 7);
		$this->dob->ViewCustomAttributes = "";

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

		// customer_type
		if (strval($this->customer_type->CurrentValue) <> "") {
			$this->customer_type->ViewValue = $this->customer_type->OptionCaption($this->customer_type->CurrentValue);
		} else {
			$this->customer_type->ViewValue = NULL;
		}
		$this->customer_type->ViewCustomAttributes = "";

		// mobile_num
		$this->mobile_num->ViewValue = $this->mobile_num->CurrentValue;
		$this->mobile_num->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// email_id
			$this->email_id->LinkCustomAttributes = "";
			$this->email_id->HrefValue = "";
			$this->email_id->TooltipValue = "";

			// customer_name
			$this->customer_name->LinkCustomAttributes = "";
			$this->customer_name->HrefValue = "";
			$this->customer_name->TooltipValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";
			$this->city_id->TooltipValue = "";

			// customer_type
			$this->customer_type->LinkCustomAttributes = "";
			$this->customer_type->HrefValue = "";
			$this->customer_type->TooltipValue = "";

			// mobile_num
			$this->mobile_num->LinkCustomAttributes = "";
			$this->mobile_num->HrefValue = "";
			$this->mobile_num->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// email_id
			$this->email_id->EditAttrs["class"] = "form-control";
			$this->email_id->EditCustomAttributes = "";
			$this->email_id->EditValue = ew_HtmlEncode($this->email_id->AdvancedSearch->SearchValue);
			$this->email_id->PlaceHolder = ew_RemoveHtml($this->email_id->FldCaption());

			// customer_name
			$this->customer_name->EditAttrs["class"] = "form-control";
			$this->customer_name->EditCustomAttributes = "";
			$this->customer_name->EditValue = ew_HtmlEncode($this->customer_name->AdvancedSearch->SearchValue);
			$this->customer_name->PlaceHolder = ew_RemoveHtml($this->customer_name->FldCaption());

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

			// customer_type
			$this->customer_type->EditCustomAttributes = "";
			$this->customer_type->EditValue = $this->customer_type->Options(FALSE);

			// mobile_num
			$this->mobile_num->EditAttrs["class"] = "form-control";
			$this->mobile_num->EditCustomAttributes = "";
			$this->mobile_num->EditValue = ew_HtmlEncode($this->mobile_num->AdvancedSearch->SearchValue);
			$this->mobile_num->PlaceHolder = ew_RemoveHtml($this->mobile_num->FldCaption());

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
		$this->email_id->AdvancedSearch->Load();
		$this->customer_name->AdvancedSearch->Load();
		$this->city_id->AdvancedSearch->Load();
		$this->customer_type->AdvancedSearch->Load();
		$this->mobile_num->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("customerslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($customers_search)) $customers_search = new ccustomers_search();

// Page init
$customers_search->Page_Init();

// Page main
$customers_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$customers_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($customers_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fcustomerssearch = new ew_Form("fcustomerssearch", "search");
<?php } else { ?>
var CurrentForm = fcustomerssearch = new ew_Form("fcustomerssearch", "search");
<?php } ?>

// Form_CustomValidate event
fcustomerssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcustomerssearch.ValidateRequired = true;
<?php } else { ?>
fcustomerssearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcustomerssearch.Lists["x_city_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcustomerssearch.Lists["x_customer_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcustomerssearch.Lists["x_customer_type"].Options = <?php echo json_encode($customers->customer_type->Options()) ?>;
fcustomerssearch.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcustomerssearch.Lists["x_status"].Options = <?php echo json_encode($customers->status->Options()) ?>;

// Form object for search
// Validate function for search

fcustomerssearch.Validate = function(fobj) {
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
<?php if (!$customers_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $customers_search->ShowPageHeader(); ?>
<?php
$customers_search->ShowMessage();
?>
<form name="fcustomerssearch" id="fcustomerssearch" class="<?php echo $customers_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($customers_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $customers_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="customers">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($customers_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($customers->email_id->Visible) { // email_id ?>
	<div id="r_email_id" class="form-group">
		<label for="x_email_id" class="<?php echo $customers_search->SearchLabelClass ?>"><span id="elh_customers_email_id"><?php echo $customers->email_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_email_id" id="z_email_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $customers_search->SearchRightColumnClass ?>"><div<?php echo $customers->email_id->CellAttributes() ?>>
			<span id="el_customers_email_id">
<input type="text" data-table="customers" data-field="x_email_id" name="x_email_id" id="x_email_id" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($customers->email_id->getPlaceHolder()) ?>" value="<?php echo $customers->email_id->EditValue ?>"<?php echo $customers->email_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($customers->customer_name->Visible) { // customer_name ?>
	<div id="r_customer_name" class="form-group">
		<label for="x_customer_name" class="<?php echo $customers_search->SearchLabelClass ?>"><span id="elh_customers_customer_name"><?php echo $customers->customer_name->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_customer_name" id="z_customer_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $customers_search->SearchRightColumnClass ?>"><div<?php echo $customers->customer_name->CellAttributes() ?>>
			<span id="el_customers_customer_name">
<input type="text" data-table="customers" data-field="x_customer_name" name="x_customer_name" id="x_customer_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($customers->customer_name->getPlaceHolder()) ?>" value="<?php echo $customers->customer_name->EditValue ?>"<?php echo $customers->customer_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($customers->city_id->Visible) { // city_id ?>
	<div id="r_city_id" class="form-group">
		<label for="x_city_id" class="<?php echo $customers_search->SearchLabelClass ?>"><span id="elh_customers_city_id"><?php echo $customers->city_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"></p>
		</label>
		<div class="<?php echo $customers_search->SearchRightColumnClass ?>"><div<?php echo $customers->city_id->CellAttributes() ?>>
			<span id="el_customers_city_id">
<?php
$selwrk = ($customers->city_id->AdvancedSearch->SearchOperator == "IS NOT NULL") ? " checked" : "";
?>
<input type="hidden" name="z_city_id" id="z_city_id" value="<?php echo $customers->city_id->AdvancedSearch->SearchOperator ?>">
<span class="checkbox-inline"><label><input type="checkbox" id="n_z_city_id" onclick="ew_ToggleSrchOpr.call(this, 'z_city_id', 'IS NOT NULL');"<?php echo $selwrk ?>><?php echo $Language->Phrase("IS NOT NULL") ?></label></span>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($customers->customer_type->Visible) { // customer_type ?>
	<div id="r_customer_type" class="form-group">
		<label class="<?php echo $customers_search->SearchLabelClass ?>"><span id="elh_customers_customer_type"><?php echo $customers->customer_type->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_customer_type" id="z_customer_type" value="="></p>
		</label>
		<div class="<?php echo $customers_search->SearchRightColumnClass ?>"><div<?php echo $customers->customer_type->CellAttributes() ?>>
			<span id="el_customers_customer_type">
<div id="tp_x_customer_type" class="ewTemplate"><input type="radio" data-table="customers" data-field="x_customer_type" data-value-separator="<?php echo ew_HtmlEncode(is_array($customers->customer_type->DisplayValueSeparator) ? json_encode($customers->customer_type->DisplayValueSeparator) : $customers->customer_type->DisplayValueSeparator) ?>" name="x_customer_type" id="x_customer_type" value="{value}"<?php echo $customers->customer_type->EditAttributes() ?>></div>
<div id="dsl_x_customer_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $customers->customer_type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($customers->customer_type->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="customers" data-field="x_customer_type" name="x_customer_type" id="x_customer_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $customers->customer_type->EditAttributes() ?>><?php echo $customers->customer_type->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($customers->customer_type->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="customers" data-field="x_customer_type" name="x_customer_type" id="x_customer_type_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($customers->customer_type->CurrentValue) ?>" checked<?php echo $customers->customer_type->EditAttributes() ?>><?php echo $customers->customer_type->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($customers->mobile_num->Visible) { // mobile_num ?>
	<div id="r_mobile_num" class="form-group">
		<label for="x_mobile_num" class="<?php echo $customers_search->SearchLabelClass ?>"><span id="elh_customers_mobile_num"><?php echo $customers->mobile_num->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobile_num" id="z_mobile_num" value="LIKE"></p>
		</label>
		<div class="<?php echo $customers_search->SearchRightColumnClass ?>"><div<?php echo $customers->mobile_num->CellAttributes() ?>>
			<span id="el_customers_mobile_num">
<input type="text" data-table="customers" data-field="x_mobile_num" name="x_mobile_num" id="x_mobile_num" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($customers->mobile_num->getPlaceHolder()) ?>" value="<?php echo $customers->mobile_num->EditValue ?>"<?php echo $customers->mobile_num->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($customers->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $customers_search->SearchLabelClass ?>"><span id="elh_customers_status"><?php echo $customers->status->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $customers_search->SearchRightColumnClass ?>"><div<?php echo $customers->status->CellAttributes() ?>>
			<span id="el_customers_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="customers" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($customers->status->DisplayValueSeparator) ? json_encode($customers->status->DisplayValueSeparator) : $customers->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $customers->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $customers->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($customers->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="customers" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $customers->status->EditAttributes() ?>><?php echo $customers->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($customers->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="customers" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($customers->status->CurrentValue) ?>" checked<?php echo $customers->status->EditAttributes() ?>><?php echo $customers->status->CurrentValue ?></label>
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
<?php if (!$customers_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcustomerssearch.Init();
</script>
<?php
$customers_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$customers_search->Page_Terminate();
?>
