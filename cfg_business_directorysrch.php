<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_business_directoryinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_business_directory_search = NULL; // Initialize page object first

class ccfg_business_directory_search extends ccfg_business_directory {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_business_directory';

	// Page object name
	var $PageObjName = 'cfg_business_directory_search';

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

		// Table object (cfg_business_directory)
		if (!isset($GLOBALS["cfg_business_directory"]) || get_class($GLOBALS["cfg_business_directory"]) == "ccfg_business_directory") {
			$GLOBALS["cfg_business_directory"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_business_directory"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cfg_business_directory', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_business_directorylist.php"));
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
		global $EW_EXPORT, $cfg_business_directory;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_business_directory);
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
						$sSrchStr = "cfg_business_directorylist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->business_title); // business_title
		$this->BuildSearchUrl($sSrchUrl, $this->cat_id); // cat_id
		$this->BuildSearchUrl($sSrchUrl, $this->province_id); // province_id
		$this->BuildSearchUrl($sSrchUrl, $this->city_id); // city_id
		$this->BuildSearchUrl($sSrchUrl, $this->business_address); // business_address
		$this->BuildSearchUrl($sSrchUrl, $this->business_logo_link); // business_logo_link
		$this->BuildSearchUrl($sSrchUrl, $this->image_2); // image_2
		$this->BuildSearchUrl($sSrchUrl, $this->img_3); // img_3
		$this->BuildSearchUrl($sSrchUrl, $this->detail_desc); // detail_desc
		$this->BuildSearchUrl($sSrchUrl, $this->longitute); // longitute
		$this->BuildSearchUrl($sSrchUrl, $this->latitude); // latitude
		$this->BuildSearchUrl($sSrchUrl, $this->primary_number); // primary_number
		$this->BuildSearchUrl($sSrchUrl, $this->secondary_number); // secondary_number
		$this->BuildSearchUrl($sSrchUrl, $this->fb_page); // fb_page
		$this->BuildSearchUrl($sSrchUrl, $this->timings); // timings
		$this->BuildSearchUrl($sSrchUrl, $this->website); // website
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
		// business_title

		$this->business_title->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_business_title"));
		$this->business_title->AdvancedSearch->SearchOperator = $objForm->GetValue("z_business_title");

		// cat_id
		$this->cat_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_cat_id"));
		$this->cat_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_cat_id");

		// province_id
		$this->province_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_province_id"));
		$this->province_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_province_id");

		// city_id
		$this->city_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_city_id"));
		$this->city_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_city_id");

		// business_address
		$this->business_address->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_business_address"));
		$this->business_address->AdvancedSearch->SearchOperator = $objForm->GetValue("z_business_address");

		// business_logo_link
		$this->business_logo_link->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_business_logo_link"));
		$this->business_logo_link->AdvancedSearch->SearchOperator = $objForm->GetValue("z_business_logo_link");

		// image_2
		$this->image_2->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_image_2"));
		$this->image_2->AdvancedSearch->SearchOperator = $objForm->GetValue("z_image_2");

		// img_3
		$this->img_3->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_img_3"));
		$this->img_3->AdvancedSearch->SearchOperator = $objForm->GetValue("z_img_3");

		// detail_desc
		$this->detail_desc->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_detail_desc"));
		$this->detail_desc->AdvancedSearch->SearchOperator = $objForm->GetValue("z_detail_desc");

		// longitute
		$this->longitute->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_longitute"));
		$this->longitute->AdvancedSearch->SearchOperator = $objForm->GetValue("z_longitute");

		// latitude
		$this->latitude->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_latitude"));
		$this->latitude->AdvancedSearch->SearchOperator = $objForm->GetValue("z_latitude");

		// primary_number
		$this->primary_number->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_primary_number"));
		$this->primary_number->AdvancedSearch->SearchOperator = $objForm->GetValue("z_primary_number");

		// secondary_number
		$this->secondary_number->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_secondary_number"));
		$this->secondary_number->AdvancedSearch->SearchOperator = $objForm->GetValue("z_secondary_number");

		// fb_page
		$this->fb_page->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fb_page"));
		$this->fb_page->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fb_page");

		// timings
		$this->timings->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_timings"));
		$this->timings->AdvancedSearch->SearchOperator = $objForm->GetValue("z_timings");

		// website
		$this->website->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_website"));
		$this->website->AdvancedSearch->SearchOperator = $objForm->GetValue("z_website");

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
		// business_title
		// cat_id
		// province_id
		// city_id
		// business_address
		// business_logo_link
		// image_2
		// img_3
		// detail_desc
		// longitute
		// latitude
		// primary_number
		// secondary_number
		// fb_page
		// timings
		// website
		// status
		// ETD

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// business_title
		$this->business_title->ViewValue = $this->business_title->CurrentValue;
		$this->business_title->ViewCustomAttributes = "";

		// cat_id
		if (strval($this->cat_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->cat_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_bussiness_listing_category`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->cat_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->cat_id->ViewValue = $this->cat_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->cat_id->ViewValue = $this->cat_id->CurrentValue;
			}
		} else {
			$this->cat_id->ViewValue = NULL;
		}
		$this->cat_id->ViewCustomAttributes = "";

		// province_id
		if (strval($this->province_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->province_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `province_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_province`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->province_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->province_id->ViewValue = $this->province_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->province_id->ViewValue = $this->province_id->CurrentValue;
			}
		} else {
			$this->province_id->ViewValue = NULL;
		}
		$this->province_id->ViewCustomAttributes = "";

		// city_id
		if (strval($this->city_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->city_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
		$sWhereWrk = "";
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

		// business_address
		$this->business_address->ViewValue = $this->business_address->CurrentValue;
		$this->business_address->ViewCustomAttributes = "";

		// business_logo_link
		$this->business_logo_link->UploadPath = 'uploads/business';
		if (!ew_Empty($this->business_logo_link->Upload->DbValue)) {
			$this->business_logo_link->ViewValue = $this->business_logo_link->Upload->DbValue;
		} else {
			$this->business_logo_link->ViewValue = "";
		}
		$this->business_logo_link->ViewCustomAttributes = "";

		// image_2
		$this->image_2->UploadPath = 'uploads/business';
		if (!ew_Empty($this->image_2->Upload->DbValue)) {
			$this->image_2->ViewValue = $this->image_2->Upload->DbValue;
		} else {
			$this->image_2->ViewValue = "";
		}
		$this->image_2->ViewCustomAttributes = "";

		// img_3
		$this->img_3->UploadPath = 'uploads/business';
		if (!ew_Empty($this->img_3->Upload->DbValue)) {
			$this->img_3->ViewValue = $this->img_3->Upload->DbValue;
		} else {
			$this->img_3->ViewValue = "";
		}
		$this->img_3->ViewCustomAttributes = "";

		// detail_desc
		$this->detail_desc->ViewValue = $this->detail_desc->CurrentValue;
		$this->detail_desc->ViewCustomAttributes = "";

		// longitute
		$this->longitute->ViewValue = $this->longitute->CurrentValue;
		$this->longitute->ViewCustomAttributes = "";

		// latitude
		$this->latitude->ViewValue = $this->latitude->CurrentValue;
		$this->latitude->ViewCustomAttributes = "";

		// primary_number
		$this->primary_number->ViewValue = $this->primary_number->CurrentValue;
		$this->primary_number->ViewCustomAttributes = "";

		// secondary_number
		$this->secondary_number->ViewValue = $this->secondary_number->CurrentValue;
		$this->secondary_number->ViewCustomAttributes = "";

		// fb_page
		$this->fb_page->ViewValue = $this->fb_page->CurrentValue;
		$this->fb_page->ViewCustomAttributes = "";

		// timings
		$this->timings->ViewValue = $this->timings->CurrentValue;
		$this->timings->ViewCustomAttributes = "";

		// website
		$this->website->ViewValue = $this->website->CurrentValue;
		$this->website->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// business_title
			$this->business_title->LinkCustomAttributes = "";
			$this->business_title->HrefValue = "";
			$this->business_title->TooltipValue = "";

			// cat_id
			$this->cat_id->LinkCustomAttributes = "";
			$this->cat_id->HrefValue = "";
			$this->cat_id->TooltipValue = "";

			// province_id
			$this->province_id->LinkCustomAttributes = "";
			$this->province_id->HrefValue = "";
			$this->province_id->TooltipValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";
			$this->city_id->TooltipValue = "";

			// business_address
			$this->business_address->LinkCustomAttributes = "";
			$this->business_address->HrefValue = "";
			$this->business_address->TooltipValue = "";

			// business_logo_link
			$this->business_logo_link->LinkCustomAttributes = "";
			$this->business_logo_link->HrefValue = "";
			$this->business_logo_link->HrefValue2 = $this->business_logo_link->UploadPath . $this->business_logo_link->Upload->DbValue;
			$this->business_logo_link->TooltipValue = "";

			// image_2
			$this->image_2->LinkCustomAttributes = "";
			$this->image_2->HrefValue = "";
			$this->image_2->HrefValue2 = $this->image_2->UploadPath . $this->image_2->Upload->DbValue;
			$this->image_2->TooltipValue = "";

			// img_3
			$this->img_3->LinkCustomAttributes = "";
			$this->img_3->HrefValue = "";
			$this->img_3->HrefValue2 = $this->img_3->UploadPath . $this->img_3->Upload->DbValue;
			$this->img_3->TooltipValue = "";

			// detail_desc
			$this->detail_desc->LinkCustomAttributes = "";
			$this->detail_desc->HrefValue = "";
			$this->detail_desc->TooltipValue = "";

			// longitute
			$this->longitute->LinkCustomAttributes = "";
			$this->longitute->HrefValue = "";
			$this->longitute->TooltipValue = "";

			// latitude
			$this->latitude->LinkCustomAttributes = "";
			$this->latitude->HrefValue = "";
			$this->latitude->TooltipValue = "";

			// primary_number
			$this->primary_number->LinkCustomAttributes = "";
			$this->primary_number->HrefValue = "";
			$this->primary_number->TooltipValue = "";

			// secondary_number
			$this->secondary_number->LinkCustomAttributes = "";
			$this->secondary_number->HrefValue = "";
			$this->secondary_number->TooltipValue = "";

			// fb_page
			$this->fb_page->LinkCustomAttributes = "";
			$this->fb_page->HrefValue = "";
			$this->fb_page->TooltipValue = "";

			// timings
			$this->timings->LinkCustomAttributes = "";
			$this->timings->HrefValue = "";
			$this->timings->TooltipValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";
			$this->website->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// business_title
			$this->business_title->EditAttrs["class"] = "form-control";
			$this->business_title->EditCustomAttributes = "";
			$this->business_title->EditValue = ew_HtmlEncode($this->business_title->AdvancedSearch->SearchValue);
			$this->business_title->PlaceHolder = ew_RemoveHtml($this->business_title->FldCaption());

			// cat_id
			$this->cat_id->EditAttrs["class"] = "form-control";
			$this->cat_id->EditCustomAttributes = "";
			if (trim(strval($this->cat_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->cat_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_bussiness_listing_category`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->cat_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->cat_id->EditValue = $arwrk;

			// province_id
			$this->province_id->EditAttrs["class"] = "form-control";
			$this->province_id->EditCustomAttributes = "";
			if (trim(strval($this->province_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->province_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `province_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_province`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->province_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->province_id->EditValue = $arwrk;

			// city_id
			$this->city_id->EditAttrs["class"] = "form-control";
			$this->city_id->EditCustomAttributes = "";
			if (trim(strval($this->city_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ID`" . ew_SearchString("=", $this->city_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `province_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cfg_cities`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->city_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->city_id->EditValue = $arwrk;

			// business_address
			$this->business_address->EditAttrs["class"] = "form-control";
			$this->business_address->EditCustomAttributes = "";
			$this->business_address->EditValue = ew_HtmlEncode($this->business_address->AdvancedSearch->SearchValue);
			$this->business_address->PlaceHolder = ew_RemoveHtml($this->business_address->FldCaption());

			// business_logo_link
			$this->business_logo_link->EditAttrs["class"] = "form-control";
			$this->business_logo_link->EditCustomAttributes = "";
			$this->business_logo_link->EditValue = ew_HtmlEncode($this->business_logo_link->AdvancedSearch->SearchValue);
			$this->business_logo_link->PlaceHolder = ew_RemoveHtml($this->business_logo_link->FldCaption());

			// image_2
			$this->image_2->EditAttrs["class"] = "form-control";
			$this->image_2->EditCustomAttributes = "";
			$this->image_2->EditValue = ew_HtmlEncode($this->image_2->AdvancedSearch->SearchValue);
			$this->image_2->PlaceHolder = ew_RemoveHtml($this->image_2->FldCaption());

			// img_3
			$this->img_3->EditAttrs["class"] = "form-control";
			$this->img_3->EditCustomAttributes = "";
			$this->img_3->EditValue = ew_HtmlEncode($this->img_3->AdvancedSearch->SearchValue);
			$this->img_3->PlaceHolder = ew_RemoveHtml($this->img_3->FldCaption());

			// detail_desc
			$this->detail_desc->EditAttrs["class"] = "form-control";
			$this->detail_desc->EditCustomAttributes = "";
			$this->detail_desc->EditValue = ew_HtmlEncode($this->detail_desc->AdvancedSearch->SearchValue);
			$this->detail_desc->PlaceHolder = ew_RemoveHtml($this->detail_desc->FldCaption());

			// longitute
			$this->longitute->EditAttrs["class"] = "form-control";
			$this->longitute->EditCustomAttributes = "";
			$this->longitute->EditValue = ew_HtmlEncode($this->longitute->AdvancedSearch->SearchValue);
			$this->longitute->PlaceHolder = ew_RemoveHtml($this->longitute->FldCaption());

			// latitude
			$this->latitude->EditAttrs["class"] = "form-control";
			$this->latitude->EditCustomAttributes = "";
			$this->latitude->EditValue = ew_HtmlEncode($this->latitude->AdvancedSearch->SearchValue);
			$this->latitude->PlaceHolder = ew_RemoveHtml($this->latitude->FldCaption());

			// primary_number
			$this->primary_number->EditAttrs["class"] = "form-control";
			$this->primary_number->EditCustomAttributes = "";
			$this->primary_number->EditValue = ew_HtmlEncode($this->primary_number->AdvancedSearch->SearchValue);
			$this->primary_number->PlaceHolder = ew_RemoveHtml($this->primary_number->FldCaption());

			// secondary_number
			$this->secondary_number->EditAttrs["class"] = "form-control";
			$this->secondary_number->EditCustomAttributes = "";
			$this->secondary_number->EditValue = ew_HtmlEncode($this->secondary_number->AdvancedSearch->SearchValue);
			$this->secondary_number->PlaceHolder = ew_RemoveHtml($this->secondary_number->FldCaption());

			// fb_page
			$this->fb_page->EditAttrs["class"] = "form-control";
			$this->fb_page->EditCustomAttributes = "";
			$this->fb_page->EditValue = ew_HtmlEncode($this->fb_page->AdvancedSearch->SearchValue);
			$this->fb_page->PlaceHolder = ew_RemoveHtml($this->fb_page->FldCaption());

			// timings
			$this->timings->EditAttrs["class"] = "form-control";
			$this->timings->EditCustomAttributes = "";
			$this->timings->EditValue = ew_HtmlEncode($this->timings->AdvancedSearch->SearchValue);
			$this->timings->PlaceHolder = ew_RemoveHtml($this->timings->FldCaption());

			// website
			$this->website->EditAttrs["class"] = "form-control";
			$this->website->EditCustomAttributes = "";
			$this->website->EditValue = ew_HtmlEncode($this->website->AdvancedSearch->SearchValue);
			$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

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
		if (!ew_CheckInteger($this->longitute->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->longitute->FldErrMsg());
		}
		if (!ew_CheckInteger($this->latitude->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->latitude->FldErrMsg());
		}
		if (!ew_CheckInteger($this->primary_number->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->primary_number->FldErrMsg());
		}
		if (!ew_CheckInteger($this->secondary_number->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->secondary_number->FldErrMsg());
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
		$this->business_title->AdvancedSearch->Load();
		$this->cat_id->AdvancedSearch->Load();
		$this->province_id->AdvancedSearch->Load();
		$this->city_id->AdvancedSearch->Load();
		$this->business_address->AdvancedSearch->Load();
		$this->business_logo_link->AdvancedSearch->Load();
		$this->image_2->AdvancedSearch->Load();
		$this->img_3->AdvancedSearch->Load();
		$this->detail_desc->AdvancedSearch->Load();
		$this->longitute->AdvancedSearch->Load();
		$this->latitude->AdvancedSearch->Load();
		$this->primary_number->AdvancedSearch->Load();
		$this->secondary_number->AdvancedSearch->Load();
		$this->fb_page->AdvancedSearch->Load();
		$this->timings->AdvancedSearch->Load();
		$this->website->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_business_directorylist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_business_directory_search)) $cfg_business_directory_search = new ccfg_business_directory_search();

// Page init
$cfg_business_directory_search->Page_Init();

// Page main
$cfg_business_directory_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_business_directory_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($cfg_business_directory_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fcfg_business_directorysearch = new ew_Form("fcfg_business_directorysearch", "search");
<?php } else { ?>
var CurrentForm = fcfg_business_directorysearch = new ew_Form("fcfg_business_directorysearch", "search");
<?php } ?>

// Form_CustomValidate event
fcfg_business_directorysearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_business_directorysearch.ValidateRequired = true;
<?php } else { ?>
fcfg_business_directorysearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_business_directorysearch.Lists["x_cat_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_cat_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_business_directorysearch.Lists["x_province_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_province_name","","",""],"ParentFields":[],"ChildFields":["x_city_id"],"FilterFields":[],"Options":[],"Template":""};
fcfg_business_directorysearch.Lists["x_city_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_city_name","","",""],"ParentFields":["x_province_id"],"ChildFields":[],"FilterFields":["x_province_id"],"Options":[],"Template":""};
fcfg_business_directorysearch.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_business_directorysearch.Lists["x_status"].Options = <?php echo json_encode($cfg_business_directory->status->Options()) ?>;

// Form object for search
// Validate function for search

fcfg_business_directorysearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_longitute");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_business_directory->longitute->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_latitude");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_business_directory->latitude->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_primary_number");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_business_directory->primary_number->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_secondary_number");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($cfg_business_directory->secondary_number->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$cfg_business_directory_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $cfg_business_directory_search->ShowPageHeader(); ?>
<?php
$cfg_business_directory_search->ShowMessage();
?>
<form name="fcfg_business_directorysearch" id="fcfg_business_directorysearch" class="<?php echo $cfg_business_directory_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_business_directory_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_business_directory_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_business_directory">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($cfg_business_directory_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($cfg_business_directory->business_title->Visible) { // business_title ?>
	<div id="r_business_title" class="form-group">
		<label for="x_business_title" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_business_title"><?php echo $cfg_business_directory->business_title->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_business_title" id="z_business_title" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->business_title->CellAttributes() ?>>
			<span id="el_cfg_business_directory_business_title">
<input type="text" data-table="cfg_business_directory" data-field="x_business_title" name="x_business_title" id="x_business_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->business_title->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->business_title->EditValue ?>"<?php echo $cfg_business_directory->business_title->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->cat_id->Visible) { // cat_id ?>
	<div id="r_cat_id" class="form-group">
		<label for="x_cat_id" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_cat_id"><?php echo $cfg_business_directory->cat_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_cat_id" id="z_cat_id" value="="></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->cat_id->CellAttributes() ?>>
			<span id="el_cfg_business_directory_cat_id">
<select data-table="cfg_business_directory" data-field="x_cat_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_business_directory->cat_id->DisplayValueSeparator) ? json_encode($cfg_business_directory->cat_id->DisplayValueSeparator) : $cfg_business_directory->cat_id->DisplayValueSeparator) ?>" id="x_cat_id" name="x_cat_id"<?php echo $cfg_business_directory->cat_id->EditAttributes() ?>>
<?php
if (is_array($cfg_business_directory->cat_id->EditValue)) {
	$arwrk = $cfg_business_directory->cat_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($cfg_business_directory->cat_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $cfg_business_directory->cat_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($cfg_business_directory->cat_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($cfg_business_directory->cat_id->CurrentValue) ?>" selected><?php echo $cfg_business_directory->cat_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_bussiness_listing_category`";
$sWhereWrk = "";
$cfg_business_directory->cat_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$cfg_business_directory->cat_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$cfg_business_directory->Lookup_Selecting($cfg_business_directory->cat_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $cfg_business_directory->cat_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_cat_id" id="s_x_cat_id" value="<?php echo $cfg_business_directory->cat_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->province_id->Visible) { // province_id ?>
	<div id="r_province_id" class="form-group">
		<label for="x_province_id" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_province_id"><?php echo $cfg_business_directory->province_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_province_id" id="z_province_id" value="="></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->province_id->CellAttributes() ?>>
			<span id="el_cfg_business_directory_province_id">
<?php $cfg_business_directory->province_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$cfg_business_directory->province_id->EditAttrs["onchange"]; ?>
<select data-table="cfg_business_directory" data-field="x_province_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_business_directory->province_id->DisplayValueSeparator) ? json_encode($cfg_business_directory->province_id->DisplayValueSeparator) : $cfg_business_directory->province_id->DisplayValueSeparator) ?>" id="x_province_id" name="x_province_id"<?php echo $cfg_business_directory->province_id->EditAttributes() ?>>
<?php
if (is_array($cfg_business_directory->province_id->EditValue)) {
	$arwrk = $cfg_business_directory->province_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($cfg_business_directory->province_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $cfg_business_directory->province_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($cfg_business_directory->province_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($cfg_business_directory->province_id->CurrentValue) ?>" selected><?php echo $cfg_business_directory->province_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `province_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_province`";
$sWhereWrk = "";
$cfg_business_directory->province_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$cfg_business_directory->province_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$cfg_business_directory->Lookup_Selecting($cfg_business_directory->province_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $cfg_business_directory->province_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_province_id" id="s_x_province_id" value="<?php echo $cfg_business_directory->province_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->city_id->Visible) { // city_id ?>
	<div id="r_city_id" class="form-group">
		<label for="x_city_id" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_city_id"><?php echo $cfg_business_directory->city_id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_city_id" id="z_city_id" value="="></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->city_id->CellAttributes() ?>>
			<span id="el_cfg_business_directory_city_id">
<select data-table="cfg_business_directory" data-field="x_city_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_business_directory->city_id->DisplayValueSeparator) ? json_encode($cfg_business_directory->city_id->DisplayValueSeparator) : $cfg_business_directory->city_id->DisplayValueSeparator) ?>" id="x_city_id" name="x_city_id"<?php echo $cfg_business_directory->city_id->EditAttributes() ?>>
<?php
if (is_array($cfg_business_directory->city_id->EditValue)) {
	$arwrk = $cfg_business_directory->city_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($cfg_business_directory->city_id->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $cfg_business_directory->city_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($cfg_business_directory->city_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($cfg_business_directory->city_id->CurrentValue) ?>" selected><?php echo $cfg_business_directory->city_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
$sWhereWrk = "{filter}";
$cfg_business_directory->city_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$cfg_business_directory->city_id->LookupFilters += array("f0" => "`ID` = {filter_value}", "t0" => "3", "fn0" => "");
$cfg_business_directory->city_id->LookupFilters += array("f1" => "`province_id` IN ({filter_value})", "t1" => "3", "fn1" => "");
$sSqlWrk = "";
$cfg_business_directory->Lookup_Selecting($cfg_business_directory->city_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $cfg_business_directory->city_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_city_id" id="s_x_city_id" value="<?php echo $cfg_business_directory->city_id->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->business_address->Visible) { // business_address ?>
	<div id="r_business_address" class="form-group">
		<label for="x_business_address" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_business_address"><?php echo $cfg_business_directory->business_address->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_business_address" id="z_business_address" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->business_address->CellAttributes() ?>>
			<span id="el_cfg_business_directory_business_address">
<input type="text" data-table="cfg_business_directory" data-field="x_business_address" name="x_business_address" id="x_business_address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->business_address->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->business_address->EditValue ?>"<?php echo $cfg_business_directory->business_address->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->business_logo_link->Visible) { // business_logo_link ?>
	<div id="r_business_logo_link" class="form-group">
		<label class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_business_logo_link"><?php echo $cfg_business_directory->business_logo_link->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_business_logo_link" id="z_business_logo_link" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->business_logo_link->CellAttributes() ?>>
			<span id="el_cfg_business_directory_business_logo_link">
<input type="text" data-table="cfg_business_directory" data-field="x_business_logo_link" name="x_business_logo_link" id="x_business_logo_link" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->business_logo_link->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->business_logo_link->EditValue ?>"<?php echo $cfg_business_directory->business_logo_link->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->image_2->Visible) { // image_2 ?>
	<div id="r_image_2" class="form-group">
		<label class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_image_2"><?php echo $cfg_business_directory->image_2->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_image_2" id="z_image_2" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->image_2->CellAttributes() ?>>
			<span id="el_cfg_business_directory_image_2">
<input type="text" data-table="cfg_business_directory" data-field="x_image_2" name="x_image_2" id="x_image_2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->image_2->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->image_2->EditValue ?>"<?php echo $cfg_business_directory->image_2->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->img_3->Visible) { // img_3 ?>
	<div id="r_img_3" class="form-group">
		<label class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_img_3"><?php echo $cfg_business_directory->img_3->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_img_3" id="z_img_3" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->img_3->CellAttributes() ?>>
			<span id="el_cfg_business_directory_img_3">
<input type="text" data-table="cfg_business_directory" data-field="x_img_3" name="x_img_3" id="x_img_3" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->img_3->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->img_3->EditValue ?>"<?php echo $cfg_business_directory->img_3->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->detail_desc->Visible) { // detail_desc ?>
	<div id="r_detail_desc" class="form-group">
		<label class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_detail_desc"><?php echo $cfg_business_directory->detail_desc->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_detail_desc" id="z_detail_desc" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->detail_desc->CellAttributes() ?>>
			<span id="el_cfg_business_directory_detail_desc">
<input type="text" data-table="cfg_business_directory" data-field="x_detail_desc" name="x_detail_desc" id="x_detail_desc" size="35" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->detail_desc->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->detail_desc->EditValue ?>"<?php echo $cfg_business_directory->detail_desc->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->longitute->Visible) { // longitute ?>
	<div id="r_longitute" class="form-group">
		<label for="x_longitute" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_longitute"><?php echo $cfg_business_directory->longitute->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_longitute" id="z_longitute" value="="></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->longitute->CellAttributes() ?>>
			<span id="el_cfg_business_directory_longitute">
<input type="text" data-table="cfg_business_directory" data-field="x_longitute" name="x_longitute" id="x_longitute" size="30" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->longitute->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->longitute->EditValue ?>"<?php echo $cfg_business_directory->longitute->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->latitude->Visible) { // latitude ?>
	<div id="r_latitude" class="form-group">
		<label for="x_latitude" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_latitude"><?php echo $cfg_business_directory->latitude->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_latitude" id="z_latitude" value="="></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->latitude->CellAttributes() ?>>
			<span id="el_cfg_business_directory_latitude">
<input type="text" data-table="cfg_business_directory" data-field="x_latitude" name="x_latitude" id="x_latitude" size="30" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->latitude->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->latitude->EditValue ?>"<?php echo $cfg_business_directory->latitude->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->primary_number->Visible) { // primary_number ?>
	<div id="r_primary_number" class="form-group">
		<label for="x_primary_number" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_primary_number"><?php echo $cfg_business_directory->primary_number->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_primary_number" id="z_primary_number" value="="></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->primary_number->CellAttributes() ?>>
			<span id="el_cfg_business_directory_primary_number">
<input type="text" data-table="cfg_business_directory" data-field="x_primary_number" name="x_primary_number" id="x_primary_number" size="30" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->primary_number->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->primary_number->EditValue ?>"<?php echo $cfg_business_directory->primary_number->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->secondary_number->Visible) { // secondary_number ?>
	<div id="r_secondary_number" class="form-group">
		<label for="x_secondary_number" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_secondary_number"><?php echo $cfg_business_directory->secondary_number->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_secondary_number" id="z_secondary_number" value="="></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->secondary_number->CellAttributes() ?>>
			<span id="el_cfg_business_directory_secondary_number">
<input type="text" data-table="cfg_business_directory" data-field="x_secondary_number" name="x_secondary_number" id="x_secondary_number" size="30" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->secondary_number->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->secondary_number->EditValue ?>"<?php echo $cfg_business_directory->secondary_number->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->fb_page->Visible) { // fb_page ?>
	<div id="r_fb_page" class="form-group">
		<label for="x_fb_page" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_fb_page"><?php echo $cfg_business_directory->fb_page->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_fb_page" id="z_fb_page" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->fb_page->CellAttributes() ?>>
			<span id="el_cfg_business_directory_fb_page">
<input type="text" data-table="cfg_business_directory" data-field="x_fb_page" name="x_fb_page" id="x_fb_page" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->fb_page->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->fb_page->EditValue ?>"<?php echo $cfg_business_directory->fb_page->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->timings->Visible) { // timings ?>
	<div id="r_timings" class="form-group">
		<label for="x_timings" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_timings"><?php echo $cfg_business_directory->timings->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_timings" id="z_timings" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->timings->CellAttributes() ?>>
			<span id="el_cfg_business_directory_timings">
<input type="text" data-table="cfg_business_directory" data-field="x_timings" name="x_timings" id="x_timings" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->timings->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->timings->EditValue ?>"<?php echo $cfg_business_directory->timings->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->website->Visible) { // website ?>
	<div id="r_website" class="form-group">
		<label for="x_website" class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_website"><?php echo $cfg_business_directory->website->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_website" id="z_website" value="LIKE"></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->website->CellAttributes() ?>>
			<span id="el_cfg_business_directory_website">
<input type="text" data-table="cfg_business_directory" data-field="x_website" name="x_website" id="x_website" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cfg_business_directory->website->getPlaceHolder()) ?>" value="<?php echo $cfg_business_directory->website->EditValue ?>"<?php echo $cfg_business_directory->website->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($cfg_business_directory->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $cfg_business_directory_search->SearchLabelClass ?>"><span id="elh_cfg_business_directory_status"><?php echo $cfg_business_directory->status->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $cfg_business_directory_search->SearchRightColumnClass ?>"><div<?php echo $cfg_business_directory->status->CellAttributes() ?>>
			<span id="el_cfg_business_directory_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="cfg_business_directory" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($cfg_business_directory->status->DisplayValueSeparator) ? json_encode($cfg_business_directory->status->DisplayValueSeparator) : $cfg_business_directory->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $cfg_business_directory->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $cfg_business_directory->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cfg_business_directory->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="cfg_business_directory" data-field="x_status" name="x_status" id="x_status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cfg_business_directory->status->EditAttributes() ?>><?php echo $cfg_business_directory->status->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($cfg_business_directory->status->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="cfg_business_directory" data-field="x_status" name="x_status" id="x_status_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($cfg_business_directory->status->CurrentValue) ?>" checked<?php echo $cfg_business_directory->status->EditAttributes() ?>><?php echo $cfg_business_directory->status->CurrentValue ?></label>
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
<?php if (!$cfg_business_directory_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcfg_business_directorysearch.Init();
</script>
<?php
$cfg_business_directory_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_business_directory_search->Page_Terminate();
?>
