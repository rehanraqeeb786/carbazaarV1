<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "classified_attributesinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "classified_datainfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$classified_attributes_search = NULL; // Initialize page object first

class cclassified_attributes_search extends cclassified_attributes {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'classified_attributes';

	// Page object name
	var $PageObjName = 'classified_attributes_search';

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

		// Table object (classified_attributes)
		if (!isset($GLOBALS["classified_attributes"]) || get_class($GLOBALS["classified_attributes"]) == "cclassified_attributes") {
			$GLOBALS["classified_attributes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["classified_attributes"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Table object (classified_data)
		if (!isset($GLOBALS['classified_data'])) $GLOBALS['classified_data'] = new cclassified_data();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'classified_attributes', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("classified_attributeslist.php"));
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
		global $EW_EXPORT, $classified_attributes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($classified_attributes);
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
						$sSrchStr = "classified_attributeslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->classified_id); // classified_id
		$this->BuildSearchUrl($sSrchUrl, $this->attribute_id); // attribute_id
		$this->BuildSearchUrl($sSrchUrl, $this->attribute_value); // attribute_value
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
		// classified_id

		$this->classified_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_classified_id"));
		$this->classified_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_classified_id");

		// attribute_id
		$this->attribute_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_attribute_id"));
		$this->attribute_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_attribute_id");

		// attribute_value
		$this->attribute_value->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_attribute_value"));
		$this->attribute_value->AdvancedSearch->SearchOperator = $objForm->GetValue("z_attribute_value");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// ID
		// classified_id
		// attribute_id
		// attribute_value
		// ETD

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// classified_id
		$this->classified_id->ViewValue = $this->classified_id->CurrentValue;
		if (strval($this->classified_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->classified_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `classified_data`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->classified_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->classified_id->ViewValue = $this->classified_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->classified_id->ViewValue = $this->classified_id->CurrentValue;
			}
		} else {
			$this->classified_id->ViewValue = NULL;
		}
		$this->classified_id->ViewCustomAttributes = "";

		// attribute_id
		$this->attribute_id->ViewValue = $this->attribute_id->CurrentValue;
		if (strval($this->attribute_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->attribute_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_classified_attribure`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->attribute_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->attribute_id->ViewValue = $this->attribute_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->attribute_id->ViewValue = $this->attribute_id->CurrentValue;
			}
		} else {
			$this->attribute_id->ViewValue = NULL;
		}
		$this->attribute_id->ViewCustomAttributes = "";

		// attribute_value
		$this->attribute_value->ViewValue = $this->attribute_value->CurrentValue;
		$this->attribute_value->ViewCustomAttributes = "";

			// classified_id
			$this->classified_id->LinkCustomAttributes = "";
			$this->classified_id->HrefValue = "";
			$this->classified_id->TooltipValue = "";

			// attribute_id
			$this->attribute_id->LinkCustomAttributes = "";
			$this->attribute_id->HrefValue = "";
			$this->attribute_id->TooltipValue = "";

			// attribute_value
			$this->attribute_value->LinkCustomAttributes = "";
			$this->attribute_value->HrefValue = "";
			$this->attribute_value->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// classified_id
			$this->classified_id->EditAttrs["class"] = "form-control";
			$this->classified_id->EditCustomAttributes = "";
			$this->classified_id->EditValue = ew_HtmlEncode($this->classified_id->AdvancedSearch->SearchValue);
			if (strval($this->classified_id->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->classified_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `classified_data`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->classified_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->classified_id->EditValue = $this->classified_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->classified_id->EditValue = ew_HtmlEncode($this->classified_id->AdvancedSearch->SearchValue);
				}
			} else {
				$this->classified_id->EditValue = NULL;
			}
			$this->classified_id->PlaceHolder = ew_RemoveHtml($this->classified_id->FldCaption());

			// attribute_id
			$this->attribute_id->EditAttrs["class"] = "form-control";
			$this->attribute_id->EditCustomAttributes = "";
			$this->attribute_id->EditValue = ew_HtmlEncode($this->attribute_id->AdvancedSearch->SearchValue);
			if (strval($this->attribute_id->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->attribute_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_classified_attribure`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->attribute_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->attribute_id->EditValue = $this->attribute_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->attribute_id->EditValue = ew_HtmlEncode($this->attribute_id->AdvancedSearch->SearchValue);
				}
			} else {
				$this->attribute_id->EditValue = NULL;
			}
			$this->attribute_id->PlaceHolder = ew_RemoveHtml($this->attribute_id->FldCaption());

			// attribute_value
			$this->attribute_value->EditAttrs["class"] = "form-control";
			$this->attribute_value->EditCustomAttributes = "";
			$this->attribute_value->EditValue = ew_HtmlEncode($this->attribute_value->AdvancedSearch->SearchValue);
			$this->attribute_value->PlaceHolder = ew_RemoveHtml($this->attribute_value->FldCaption());
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
		if (!ew_CheckInteger($this->classified_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->classified_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->attribute_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->attribute_id->FldErrMsg());
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
		$this->classified_id->AdvancedSearch->Load();
		$this->attribute_id->AdvancedSearch->Load();
		$this->attribute_value->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("classified_attributeslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($classified_attributes_search)) $classified_attributes_search = new cclassified_attributes_search();

// Page init
$classified_attributes_search->Page_Init();

// Page main
$classified_attributes_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$classified_attributes_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($classified_attributes_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fclassified_attributessearch = new ew_Form("fclassified_attributessearch", "search");
<?php } else { ?>
var CurrentForm = fclassified_attributessearch = new ew_Form("fclassified_attributessearch", "search");
<?php } ?>

// Form_CustomValidate event
fclassified_attributessearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclassified_attributessearch.ValidateRequired = true;
<?php } else { ?>
fclassified_attributessearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclassified_attributessearch.Lists["x_classified_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fclassified_attributessearch.Lists["x_attribute_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_attribute_title","x_attribute_type","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
// Validate function for search

fclassified_attributessearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_classified_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($classified_attributes->classified_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_attribute_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($classified_attributes->attribute_id->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$classified_attributes_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $classified_attributes_search->ShowPageHeader(); ?>
<?php
$classified_attributes_search->ShowMessage();
?>
<form name="fclassified_attributessearch" id="fclassified_attributessearch" class="<?php echo $classified_attributes_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($classified_attributes_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $classified_attributes_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="classified_attributes">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($classified_attributes_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($classified_attributes->classified_id->Visible) { // classified_id ?>
	<div id="r_classified_id" class="form-group">
		<label class="<?php echo $classified_attributes_search->SearchLabelClass ?>"><span id="elh_classified_attributes_classified_id"><?php echo $classified_attributes->classified_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_classified_id" id="z_classified_id" value="="></p>
		</label>
		<div class="<?php echo $classified_attributes_search->SearchRightColumnClass ?>"><div<?php echo $classified_attributes->classified_id->CellAttributes() ?>>
			<span id="el_classified_attributes_classified_id">
<?php
$wrkonchange = trim(" " . @$classified_attributes->classified_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_attributes->classified_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_classified_id" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_classified_id" id="sv_x_classified_id" value="<?php echo $classified_attributes->classified_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_attributes->classified_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_attributes->classified_id->getPlaceHolder()) ?>"<?php echo $classified_attributes->classified_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_classified_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_attributes->classified_id->DisplayValueSeparator) ? json_encode($classified_attributes->classified_id->DisplayValueSeparator) : $classified_attributes->classified_id->DisplayValueSeparator) ?>" name="x_classified_id" id="x_classified_id" value="<?php echo ew_HtmlEncode($classified_attributes->classified_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `title` AS `DispFld` FROM `classified_data`";
$sWhereWrk = "`title` LIKE '{query_value}%'";
$classified_attributes->Lookup_Selecting($classified_attributes->classified_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_classified_id" id="q_x_classified_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_attributessearch.CreateAutoSuggest({"id":"x_classified_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($classified_attributes->attribute_id->Visible) { // attribute_id ?>
	<div id="r_attribute_id" class="form-group">
		<label class="<?php echo $classified_attributes_search->SearchLabelClass ?>"><span id="elh_classified_attributes_attribute_id"><?php echo $classified_attributes->attribute_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_attribute_id" id="z_attribute_id" value="="></p>
		</label>
		<div class="<?php echo $classified_attributes_search->SearchRightColumnClass ?>"><div<?php echo $classified_attributes->attribute_id->CellAttributes() ?>>
			<span id="el_classified_attributes_attribute_id">
<?php
$wrkonchange = trim(" " . @$classified_attributes->attribute_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$classified_attributes->attribute_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_attribute_id" style="white-space: nowrap; z-index: 8970">
	<input type="text" name="sv_x_attribute_id" id="sv_x_attribute_id" value="<?php echo $classified_attributes->attribute_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->getPlaceHolder()) ?>"<?php echo $classified_attributes->attribute_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="classified_attributes" data-field="x_attribute_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($classified_attributes->attribute_id->DisplayValueSeparator) ? json_encode($classified_attributes->attribute_id->DisplayValueSeparator) : $classified_attributes->attribute_id->DisplayValueSeparator) ?>" name="x_attribute_id" id="x_attribute_id" value="<?php echo ew_HtmlEncode($classified_attributes->attribute_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `attribute_title` AS `DispFld`, `attribute_type` AS `Disp2Fld` FROM `cfg_classified_attribure`";
$sWhereWrk = "`attribute_title` LIKE '{query_value}%' OR CONCAT(`attribute_title`,'" . ew_ValueSeparator(1, $Page->attribute_id) . "',`attribute_type`) LIKE '{query_value}%'";
$classified_attributes->Lookup_Selecting($classified_attributes->attribute_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_attribute_id" id="q_x_attribute_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fclassified_attributessearch.CreateAutoSuggest({"id":"x_attribute_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($classified_attributes->attribute_value->Visible) { // attribute_value ?>
	<div id="r_attribute_value" class="form-group">
		<label for="x_attribute_value" class="<?php echo $classified_attributes_search->SearchLabelClass ?>"><span id="elh_classified_attributes_attribute_value"><?php echo $classified_attributes->attribute_value->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_attribute_value" id="z_attribute_value" value="LIKE"></p>
		</label>
		<div class="<?php echo $classified_attributes_search->SearchRightColumnClass ?>"><div<?php echo $classified_attributes->attribute_value->CellAttributes() ?>>
			<span id="el_classified_attributes_attribute_value">
<input type="text" data-table="classified_attributes" data-field="x_attribute_value" name="x_attribute_value" id="x_attribute_value" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($classified_attributes->attribute_value->getPlaceHolder()) ?>" value="<?php echo $classified_attributes->attribute_value->EditValue ?>"<?php echo $classified_attributes->attribute_value->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$classified_attributes_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fclassified_attributessearch.Init();
</script>
<?php
$classified_attributes_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$classified_attributes_search->Page_Terminate();
?>
