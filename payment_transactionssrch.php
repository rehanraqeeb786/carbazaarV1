<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "payment_transactionsinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$payment_transactions_search = NULL; // Initialize page object first

class cpayment_transactions_search extends cpayment_transactions {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'payment_transactions';

	// Page object name
	var $PageObjName = 'payment_transactions_search';

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

		// Table object (payment_transactions)
		if (!isset($GLOBALS["payment_transactions"]) || get_class($GLOBALS["payment_transactions"]) == "cpayment_transactions") {
			$GLOBALS["payment_transactions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["payment_transactions"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'payment_transactions', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("payment_transactionslist.php"));
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
		global $EW_EXPORT, $payment_transactions;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($payment_transactions);
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
						$sSrchStr = "payment_transactionslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->package_id); // package_id
		$this->BuildSearchUrl($sSrchUrl, $this->pay_method_id); // pay_method_id
		$this->BuildSearchUrl($sSrchUrl, $this->bank_id); // bank_id
		$this->BuildSearchUrl($sSrchUrl, $this->transaction_id); // transaction_id
		$this->BuildSearchUrl($sSrchUrl, $this->order_reference_id); // order_reference_id
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
		// package_id

		$this->package_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_package_id"));
		$this->package_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_package_id");

		// pay_method_id
		$this->pay_method_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_pay_method_id"));
		$this->pay_method_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_pay_method_id");

		// bank_id
		$this->bank_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_bank_id"));
		$this->bank_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_bank_id");

		// transaction_id
		$this->transaction_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_transaction_id"));
		$this->transaction_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_transaction_id");

		// order_reference_id
		$this->order_reference_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_order_reference_id"));
		$this->order_reference_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_order_reference_id");

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
		// ad_id
		// package_id
		// pay_method_id
		// bank_id
		// transaction_id
		// order_reference_id
		// amount
		// response
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ad_id
		$this->ad_id->ViewValue = $this->ad_id->CurrentValue;
		if (strval($this->ad_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->ad_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `ad_title` AS `DispFld`, `demand_price` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `car_ads`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->ad_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->ad_id->ViewValue = $this->ad_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->ad_id->ViewValue = $this->ad_id->CurrentValue;
			}
		} else {
			$this->ad_id->ViewValue = NULL;
		}
		$this->ad_id->ViewCustomAttributes = "";

		// package_id
		if (strval($this->package_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->package_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `package_fee` AS `DispFld`, `number_of_days` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `packages`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->package_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
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

		// bank_id
		if (strval($this->bank_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->bank_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `bank_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank_accounts`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->bank_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->bank_id->ViewValue = $this->bank_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->bank_id->ViewValue = $this->bank_id->CurrentValue;
			}
		} else {
			$this->bank_id->ViewValue = NULL;
		}
		$this->bank_id->ViewCustomAttributes = "";

		// transaction_id
		$this->transaction_id->ViewValue = $this->transaction_id->CurrentValue;
		$this->transaction_id->ViewCustomAttributes = "";

		// order_reference_id
		$this->order_reference_id->ViewValue = $this->order_reference_id->CurrentValue;
		$this->order_reference_id->ViewCustomAttributes = "";

		// amount
		$this->amount->ViewValue = $this->amount->CurrentValue;
		$this->amount->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// package_id
			$this->package_id->LinkCustomAttributes = "";
			$this->package_id->HrefValue = "";
			$this->package_id->TooltipValue = "";

			// pay_method_id
			$this->pay_method_id->LinkCustomAttributes = "";
			$this->pay_method_id->HrefValue = "";
			$this->pay_method_id->TooltipValue = "";

			// bank_id
			$this->bank_id->LinkCustomAttributes = "";
			$this->bank_id->HrefValue = "";
			$this->bank_id->TooltipValue = "";

			// transaction_id
			$this->transaction_id->LinkCustomAttributes = "";
			$this->transaction_id->HrefValue = "";
			$this->transaction_id->TooltipValue = "";

			// order_reference_id
			$this->order_reference_id->LinkCustomAttributes = "";
			$this->order_reference_id->HrefValue = "";
			$this->order_reference_id->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// package_id
			$this->package_id->EditAttrs["class"] = "form-control";
			$this->package_id->EditCustomAttributes = "";
			if (trim(strval($this->package_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->package_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `package_fee` AS `DispFld`, `number_of_days` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `packages`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->package_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->package_id->EditValue = $arwrk;

			// pay_method_id
			$this->pay_method_id->EditAttrs["class"] = "form-control";
			$this->pay_method_id->EditCustomAttributes = "";
			if (trim(strval($this->pay_method_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->pay_method_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pay_methods`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->pay_method_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->pay_method_id->EditValue = $arwrk;

			// bank_id
			$this->bank_id->EditAttrs["class"] = "form-control";
			$this->bank_id->EditCustomAttributes = "";
			if (trim(strval($this->bank_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->bank_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `bank_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `bank_accounts`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->bank_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->bank_id->EditValue = $arwrk;

			// transaction_id
			$this->transaction_id->EditAttrs["class"] = "form-control";
			$this->transaction_id->EditCustomAttributes = "";
			$this->transaction_id->EditValue = ew_HtmlEncode($this->transaction_id->AdvancedSearch->SearchValue);
			$this->transaction_id->PlaceHolder = ew_RemoveHtml($this->transaction_id->FldCaption());

			// order_reference_id
			$this->order_reference_id->EditAttrs["class"] = "form-control";
			$this->order_reference_id->EditCustomAttributes = "";
			$this->order_reference_id->EditValue = ew_HtmlEncode($this->order_reference_id->AdvancedSearch->SearchValue);
			$this->order_reference_id->PlaceHolder = ew_RemoveHtml($this->order_reference_id->FldCaption());

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
		$this->package_id->AdvancedSearch->Load();
		$this->pay_method_id->AdvancedSearch->Load();
		$this->bank_id->AdvancedSearch->Load();
		$this->transaction_id->AdvancedSearch->Load();
		$this->order_reference_id->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("payment_transactionslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($payment_transactions_search)) $payment_transactions_search = new cpayment_transactions_search();

// Page init
$payment_transactions_search->Page_Init();

// Page main
$payment_transactions_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$payment_transactions_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($payment_transactions_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fpayment_transactionssearch = new ew_Form("fpayment_transactionssearch", "search");
<?php } else { ?>
var CurrentForm = fpayment_transactionssearch = new ew_Form("fpayment_transactionssearch", "search");
<?php } ?>

// Form_CustomValidate event
fpayment_transactionssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpayment_transactionssearch.ValidateRequired = true;
<?php } else { ?>
fpayment_transactionssearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpayment_transactionssearch.Lists["x_package_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_package_fee","x_number_of_days","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpayment_transactionssearch.Lists["x_pay_method_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpayment_transactionssearch.Lists["x_bank_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_bank_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpayment_transactionssearch.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpayment_transactionssearch.Lists["x_status"].Options = <?php echo json_encode($payment_transactions->status->Options()) ?>;

// Form object for search
// Validate function for search

fpayment_transactionssearch.Validate = function(fobj) {
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
<?php if (!$payment_transactions_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $payment_transactions_search->ShowPageHeader(); ?>
<?php
$payment_transactions_search->ShowMessage();
?>
<form name="fpayment_transactionssearch" id="fpayment_transactionssearch" class="<?php echo $payment_transactions_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($payment_transactions_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $payment_transactions_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="payment_transactions">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($payment_transactions_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($payment_transactions->package_id->Visible) { // package_id ?>
	<div id="r_package_id" class="form-group">
		<label for="x_package_id" class="<?php echo $payment_transactions_search->SearchLabelClass ?>"><span id="elh_payment_transactions_package_id"><?php echo $payment_transactions->package_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_package_id" id="z_package_id" value="="></p>
		</label>
		<div class="<?php echo $payment_transactions_search->SearchRightColumnClass ?>"><div<?php echo $payment_transactions->package_id->CellAttributes() ?>>
			<span id="el_payment_transactions_package_id">
<select data-table="payment_transactions" data-field="x_package_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($payment_transactions->package_id->DisplayValueSeparator) ? json_encode($payment_transactions->package_id->DisplayValueSeparator) : $payment_transactions->package_id->DisplayValueSeparator) ?>" id="x_package_id" name="x_package_id"<?php echo $payment_transactions->package_id->EditAttributes() ?>>
<?php
if (is_array($payment_transactions->package_id->EditValue)) {
	$arwrk = $payment_transactions->package_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($payment_transactions->package_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $payment_transactions->package_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($payment_transactions->package_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($payment_transactions->package_id->CurrentValue) ?>" selected><?php echo $payment_transactions->package_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `id`, `package_fee` AS `DispFld`, `number_of_days` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `packages`";
$sWhereWrk = "";
$payment_transactions->package_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$payment_transactions->package_id->LookupFilters += array("f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$payment_transactions->Lookup_Selecting($payment_transactions->package_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $payment_transactions->package_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_package_id" id="s_x_package_id" value="<?php echo $payment_transactions->package_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->pay_method_id->Visible) { // pay_method_id ?>
	<div id="r_pay_method_id" class="form-group">
		<label for="x_pay_method_id" class="<?php echo $payment_transactions_search->SearchLabelClass ?>"><span id="elh_payment_transactions_pay_method_id"><?php echo $payment_transactions->pay_method_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_pay_method_id" id="z_pay_method_id" value="="></p>
		</label>
		<div class="<?php echo $payment_transactions_search->SearchRightColumnClass ?>"><div<?php echo $payment_transactions->pay_method_id->CellAttributes() ?>>
			<span id="el_payment_transactions_pay_method_id">
<select data-table="payment_transactions" data-field="x_pay_method_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($payment_transactions->pay_method_id->DisplayValueSeparator) ? json_encode($payment_transactions->pay_method_id->DisplayValueSeparator) : $payment_transactions->pay_method_id->DisplayValueSeparator) ?>" id="x_pay_method_id" name="x_pay_method_id"<?php echo $payment_transactions->pay_method_id->EditAttributes() ?>>
<?php
if (is_array($payment_transactions->pay_method_id->EditValue)) {
	$arwrk = $payment_transactions->pay_method_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($payment_transactions->pay_method_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $payment_transactions->pay_method_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($payment_transactions->pay_method_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($payment_transactions->pay_method_id->CurrentValue) ?>" selected><?php echo $payment_transactions->pay_method_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `id`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pay_methods`";
$sWhereWrk = "";
$payment_transactions->pay_method_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$payment_transactions->pay_method_id->LookupFilters += array("f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$payment_transactions->Lookup_Selecting($payment_transactions->pay_method_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $payment_transactions->pay_method_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_pay_method_id" id="s_x_pay_method_id" value="<?php echo $payment_transactions->pay_method_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->bank_id->Visible) { // bank_id ?>
	<div id="r_bank_id" class="form-group">
		<label for="x_bank_id" class="<?php echo $payment_transactions_search->SearchLabelClass ?>"><span id="elh_payment_transactions_bank_id"><?php echo $payment_transactions->bank_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_bank_id" id="z_bank_id" value="="></p>
		</label>
		<div class="<?php echo $payment_transactions_search->SearchRightColumnClass ?>"><div<?php echo $payment_transactions->bank_id->CellAttributes() ?>>
			<span id="el_payment_transactions_bank_id">
<select data-table="payment_transactions" data-field="x_bank_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($payment_transactions->bank_id->DisplayValueSeparator) ? json_encode($payment_transactions->bank_id->DisplayValueSeparator) : $payment_transactions->bank_id->DisplayValueSeparator) ?>" id="x_bank_id" name="x_bank_id"<?php echo $payment_transactions->bank_id->EditAttributes() ?>>
<?php
if (is_array($payment_transactions->bank_id->EditValue)) {
	$arwrk = $payment_transactions->bank_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($payment_transactions->bank_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $payment_transactions->bank_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($payment_transactions->bank_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($payment_transactions->bank_id->CurrentValue) ?>" selected><?php echo $payment_transactions->bank_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `id`, `bank_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank_accounts`";
$sWhereWrk = "";
$payment_transactions->bank_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$payment_transactions->bank_id->LookupFilters += array("f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$payment_transactions->Lookup_Selecting($payment_transactions->bank_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $payment_transactions->bank_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_bank_id" id="s_x_bank_id" value="<?php echo $payment_transactions->bank_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->transaction_id->Visible) { // transaction_id ?>
	<div id="r_transaction_id" class="form-group">
		<label for="x_transaction_id" class="<?php echo $payment_transactions_search->SearchLabelClass ?>"><span id="elh_payment_transactions_transaction_id"><?php echo $payment_transactions->transaction_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_transaction_id" id="z_transaction_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $payment_transactions_search->SearchRightColumnClass ?>"><div<?php echo $payment_transactions->transaction_id->CellAttributes() ?>>
			<span id="el_payment_transactions_transaction_id">
<input type="text" data-table="payment_transactions" data-field="x_transaction_id" name="x_transaction_id" id="x_transaction_id" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($payment_transactions->transaction_id->getPlaceHolder()) ?>" value="<?php echo $payment_transactions->transaction_id->EditValue ?>"<?php echo $payment_transactions->transaction_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->order_reference_id->Visible) { // order_reference_id ?>
	<div id="r_order_reference_id" class="form-group">
		<label for="x_order_reference_id" class="<?php echo $payment_transactions_search->SearchLabelClass ?>"><span id="elh_payment_transactions_order_reference_id"><?php echo $payment_transactions->order_reference_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_order_reference_id" id="z_order_reference_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $payment_transactions_search->SearchRightColumnClass ?>"><div<?php echo $payment_transactions->order_reference_id->CellAttributes() ?>>
			<span id="el_payment_transactions_order_reference_id">
<input type="text" data-table="payment_transactions" data-field="x_order_reference_id" name="x_order_reference_id" id="x_order_reference_id" size="30" maxlength="22" placeholder="<?php echo ew_HtmlEncode($payment_transactions->order_reference_id->getPlaceHolder()) ?>" value="<?php echo $payment_transactions->order_reference_id->EditValue ?>"<?php echo $payment_transactions->order_reference_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $payment_transactions_search->SearchLabelClass ?>"><span id="elh_payment_transactions_status"><?php echo $payment_transactions->status->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $payment_transactions_search->SearchRightColumnClass ?>"><div<?php echo $payment_transactions->status->CellAttributes() ?>>
			<span id="el_payment_transactions_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="payment_transactions" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($payment_transactions->status->DisplayValueSeparator) ? json_encode($payment_transactions->status->DisplayValueSeparator) : $payment_transactions->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $payment_transactions->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $payment_transactions->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($payment_transactions->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="payment_transactions" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $payment_transactions->status->EditAttributes() ?>><?php echo $payment_transactions->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($payment_transactions->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="payment_transactions" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($payment_transactions->status->CurrentValue) ?>" checked<?php echo $payment_transactions->status->EditAttributes() ?>><?php echo $payment_transactions->status->CurrentValue ?></label>
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
<?php if (!$payment_transactions_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fpayment_transactionssearch.Init();
</script>
<?php
$payment_transactions_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$payment_transactions_search->Page_Terminate();
?>
