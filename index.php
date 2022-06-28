<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Page object name
	var $PageObjName = 'default';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'default', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

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

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadUserLevel(); // Load User Level
		if ($Security->AllowList(CurrentProjectID() . 'dashboard.php'))
		$this->Page_Terminate("dashboard.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 'customers'))
			$this->Page_Terminate("customerslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'adm_users'))
			$this->Page_Terminate("adm_userslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'user_level_permission'))
			$this->Page_Terminate("user_level_permissionlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'user_levels'))
			$this->Page_Terminate("user_levelslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'car_ads'))
			$this->Page_Terminate("car_adslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_body_colors'))
			$this->Page_Terminate("cfg_body_colorslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_body_type'))
			$this->Page_Terminate("cfg_body_typelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_car_versions'))
			$this->Page_Terminate("cfg_car_versionslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_cities'))
			$this->Page_Terminate("cfg_citieslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_engine_types'))
			$this->Page_Terminate("cfg_engine_typeslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_features'))
			$this->Page_Terminate("cfg_featureslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_make_companies'))
			$this->Page_Terminate("cfg_make_companieslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_models'))
			$this->Page_Terminate("cfg_modelslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'dealers_showroom'))
			$this->Page_Terminate("dealers_showroomlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'audittrail'))
			$this->Page_Terminate("audittraillist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_province'))
			$this->Page_Terminate("cfg_provincelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_years'))
			$this->Page_Terminate("cfg_yearslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'ad_features'))
			$this->Page_Terminate("ad_featureslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'ad_pictures'))
			$this->Page_Terminate("ad_pictureslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'failed_jobs'))
			$this->Page_Terminate("failed_jobslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'bank_accounts'))
			$this->Page_Terminate("bank_accountslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'packages'))
			$this->Page_Terminate("packageslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'pay_methods'))
			$this->Page_Terminate("pay_methodslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'payment_transactions'))
			$this->Page_Terminate("payment_transactionslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'vw_pending_ads_list'))
			$this->Page_Terminate("vw_pending_ads_listlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_classified_attribure'))
			$this->Page_Terminate("cfg_classified_attriburelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'classified_attributes'))
			$this->Page_Terminate("classified_attributeslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'classified_colors'))
			$this->Page_Terminate("classified_colorslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'classified_data'))
			$this->Page_Terminate("classified_datalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'classified_faqs'))
			$this->Page_Terminate("classified_faqslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'classified_pictures'))
			$this->Page_Terminate("classified_pictureslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_business_directory'))
			$this->Page_Terminate("cfg_business_directorylist.php");
		if ($Security->AllowList(CurrentProjectID() . 'vw_approved_ads_list'))
			$this->Page_Terminate("vw_approved_ads_listlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'vw_ads_payment_detail'))
			$this->Page_Terminate("vw_ads_payment_detaillist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_bussiness_listing_category'))
			$this->Page_Terminate("cfg_bussiness_listing_categorylist.php");
		if ($Security->AllowList(CurrentProjectID() . 'vw_payment_api_response'))
			$this->Page_Terminate("vw_payment_api_responselist.php");
		if ($Security->AllowList(CurrentProjectID() . 'vw_current_feature_ads'))
			$this->Page_Terminate("vw_current_feature_adslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cfg_advertisement_spaces'))
			$this->Page_Terminate("cfg_advertisement_spaceslist.php");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage($Language->Phrase("NoPermission") . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
		}
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
