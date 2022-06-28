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

$payment_transactions_add = NULL; // Initialize page object first

class cpayment_transactions_add extends cpayment_transactions {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'payment_transactions';

	// Page object name
	var $PageObjName = 'payment_transactions_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("payment_transactionslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "payment_transactionslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "payment_transactionsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->ad_id->CurrentValue = NULL;
		$this->ad_id->OldValue = $this->ad_id->CurrentValue;
		$this->package_id->CurrentValue = NULL;
		$this->package_id->OldValue = $this->package_id->CurrentValue;
		$this->pay_method_id->CurrentValue = NULL;
		$this->pay_method_id->OldValue = $this->pay_method_id->CurrentValue;
		$this->bank_id->CurrentValue = 0;
		$this->transaction_id->CurrentValue = NULL;
		$this->transaction_id->OldValue = $this->transaction_id->CurrentValue;
		$this->order_reference_id->CurrentValue = NULL;
		$this->order_reference_id->OldValue = $this->order_reference_id->CurrentValue;
		$this->amount->CurrentValue = NULL;
		$this->amount->OldValue = $this->amount->CurrentValue;
		$this->response->CurrentValue = NULL;
		$this->response->OldValue = $this->response->CurrentValue;
		$this->status->CurrentValue = "0";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->ad_id->FldIsDetailKey) {
			$this->ad_id->setFormValue($objForm->GetValue("x_ad_id"));
		}
		if (!$this->package_id->FldIsDetailKey) {
			$this->package_id->setFormValue($objForm->GetValue("x_package_id"));
		}
		if (!$this->pay_method_id->FldIsDetailKey) {
			$this->pay_method_id->setFormValue($objForm->GetValue("x_pay_method_id"));
		}
		if (!$this->bank_id->FldIsDetailKey) {
			$this->bank_id->setFormValue($objForm->GetValue("x_bank_id"));
		}
		if (!$this->transaction_id->FldIsDetailKey) {
			$this->transaction_id->setFormValue($objForm->GetValue("x_transaction_id"));
		}
		if (!$this->order_reference_id->FldIsDetailKey) {
			$this->order_reference_id->setFormValue($objForm->GetValue("x_order_reference_id"));
		}
		if (!$this->amount->FldIsDetailKey) {
			$this->amount->setFormValue($objForm->GetValue("x_amount"));
		}
		if (!$this->response->FldIsDetailKey) {
			$this->response->setFormValue($objForm->GetValue("x_response"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->ad_id->CurrentValue = $this->ad_id->FormValue;
		$this->package_id->CurrentValue = $this->package_id->FormValue;
		$this->pay_method_id->CurrentValue = $this->pay_method_id->FormValue;
		$this->bank_id->CurrentValue = $this->bank_id->FormValue;
		$this->transaction_id->CurrentValue = $this->transaction_id->FormValue;
		$this->order_reference_id->CurrentValue = $this->order_reference_id->FormValue;
		$this->amount->CurrentValue = $this->amount->FormValue;
		$this->response->CurrentValue = $this->response->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->ad_id->setDbValue($rs->fields('ad_id'));
		$this->package_id->setDbValue($rs->fields('package_id'));
		$this->pay_method_id->setDbValue($rs->fields('pay_method_id'));
		$this->bank_id->setDbValue($rs->fields('bank_id'));
		$this->transaction_id->setDbValue($rs->fields('transaction_id'));
		$this->order_reference_id->setDbValue($rs->fields('order_reference_id'));
		$this->amount->setDbValue($rs->fields('amount'));
		$this->response->setDbValue($rs->fields('response'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->ad_id->DbValue = $row['ad_id'];
		$this->package_id->DbValue = $row['package_id'];
		$this->pay_method_id->DbValue = $row['pay_method_id'];
		$this->bank_id->DbValue = $row['bank_id'];
		$this->transaction_id->DbValue = $row['transaction_id'];
		$this->order_reference_id->DbValue = $row['order_reference_id'];
		$this->amount->DbValue = $row['amount'];
		$this->response->DbValue = $row['response'];
		$this->status->DbValue = $row['status'];
		$this->created_at->DbValue = $row['created_at'];
		$this->updated_at->DbValue = $row['updated_at'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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

		// response
		$this->response->ViewValue = $this->response->CurrentValue;
		$this->response->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

			// ad_id
			$this->ad_id->LinkCustomAttributes = "";
			$this->ad_id->HrefValue = "";
			$this->ad_id->TooltipValue = "";

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

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";
			$this->amount->TooltipValue = "";

			// response
			$this->response->LinkCustomAttributes = "";
			$this->response->HrefValue = "";
			$this->response->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// ad_id
			$this->ad_id->EditAttrs["class"] = "form-control";
			$this->ad_id->EditCustomAttributes = "";
			$this->ad_id->EditValue = ew_HtmlEncode($this->ad_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->ad_id->EditValue = $this->ad_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->ad_id->EditValue = ew_HtmlEncode($this->ad_id->CurrentValue);
				}
			} else {
				$this->ad_id->EditValue = NULL;
			}
			$this->ad_id->PlaceHolder = ew_RemoveHtml($this->ad_id->FldCaption());

			// package_id
			$this->package_id->EditAttrs["class"] = "form-control";
			$this->package_id->EditCustomAttributes = "";
			if (trim(strval($this->package_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->package_id->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			if (trim(strval($this->pay_method_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->pay_method_id->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			if (trim(strval($this->bank_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->bank_id->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			$this->transaction_id->EditValue = ew_HtmlEncode($this->transaction_id->CurrentValue);
			$this->transaction_id->PlaceHolder = ew_RemoveHtml($this->transaction_id->FldCaption());

			// order_reference_id
			$this->order_reference_id->EditAttrs["class"] = "form-control";
			$this->order_reference_id->EditCustomAttributes = "";
			$this->order_reference_id->EditValue = ew_HtmlEncode($this->order_reference_id->CurrentValue);
			$this->order_reference_id->PlaceHolder = ew_RemoveHtml($this->order_reference_id->FldCaption());

			// amount
			$this->amount->EditAttrs["class"] = "form-control";
			$this->amount->EditCustomAttributes = "";
			$this->amount->EditValue = ew_HtmlEncode($this->amount->CurrentValue);
			$this->amount->PlaceHolder = ew_RemoveHtml($this->amount->FldCaption());

			// response
			$this->response->EditAttrs["class"] = "form-control";
			$this->response->EditCustomAttributes = "";
			$this->response->EditValue = ew_HtmlEncode($this->response->CurrentValue);
			$this->response->PlaceHolder = ew_RemoveHtml($this->response->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Add refer script
			// ad_id

			$this->ad_id->LinkCustomAttributes = "";
			$this->ad_id->HrefValue = "";

			// package_id
			$this->package_id->LinkCustomAttributes = "";
			$this->package_id->HrefValue = "";

			// pay_method_id
			$this->pay_method_id->LinkCustomAttributes = "";
			$this->pay_method_id->HrefValue = "";

			// bank_id
			$this->bank_id->LinkCustomAttributes = "";
			$this->bank_id->HrefValue = "";

			// transaction_id
			$this->transaction_id->LinkCustomAttributes = "";
			$this->transaction_id->HrefValue = "";

			// order_reference_id
			$this->order_reference_id->LinkCustomAttributes = "";
			$this->order_reference_id->HrefValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";

			// response
			$this->response->LinkCustomAttributes = "";
			$this->response->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->ad_id->FldIsDetailKey && !is_null($this->ad_id->FormValue) && $this->ad_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ad_id->FldCaption(), $this->ad_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->ad_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->ad_id->FldErrMsg());
		}
		if (!$this->package_id->FldIsDetailKey && !is_null($this->package_id->FormValue) && $this->package_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->package_id->FldCaption(), $this->package_id->ReqErrMsg));
		}
		if (!$this->pay_method_id->FldIsDetailKey && !is_null($this->pay_method_id->FormValue) && $this->pay_method_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pay_method_id->FldCaption(), $this->pay_method_id->ReqErrMsg));
		}
		if (!$this->order_reference_id->FldIsDetailKey && !is_null($this->order_reference_id->FormValue) && $this->order_reference_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->order_reference_id->FldCaption(), $this->order_reference_id->ReqErrMsg));
		}
		if (!$this->amount->FldIsDetailKey && !is_null($this->amount->FormValue) && $this->amount->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->amount->FldCaption(), $this->amount->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->amount->FormValue)) {
			ew_AddMessage($gsFormError, $this->amount->FldErrMsg());
		}
		if ($this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// ad_id
		$this->ad_id->SetDbValueDef($rsnew, $this->ad_id->CurrentValue, 0, FALSE);

		// package_id
		$this->package_id->SetDbValueDef($rsnew, $this->package_id->CurrentValue, 0, FALSE);

		// pay_method_id
		$this->pay_method_id->SetDbValueDef($rsnew, $this->pay_method_id->CurrentValue, 0, FALSE);

		// bank_id
		$this->bank_id->SetDbValueDef($rsnew, $this->bank_id->CurrentValue, NULL, strval($this->bank_id->CurrentValue) == "");

		// transaction_id
		$this->transaction_id->SetDbValueDef($rsnew, $this->transaction_id->CurrentValue, NULL, FALSE);

		// order_reference_id
		$this->order_reference_id->SetDbValueDef($rsnew, $this->order_reference_id->CurrentValue, "", FALSE);

		// amount
		$this->amount->SetDbValueDef($rsnew, $this->amount->CurrentValue, 0, FALSE);

		// response
		$this->response->SetDbValueDef($rsnew, $this->response->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, ((strval($this->status->CurrentValue) == "1") ? "1" : "0"), 0, strval($this->status->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->id->setDbValue($conn->Insert_ID());
				$rsnew['id'] = $this->id->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("payment_transactionslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($payment_transactions_add)) $payment_transactions_add = new cpayment_transactions_add();

// Page init
$payment_transactions_add->Page_Init();

// Page main
$payment_transactions_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$payment_transactions_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fpayment_transactionsadd = new ew_Form("fpayment_transactionsadd", "add");

// Validate form
fpayment_transactionsadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_ad_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment_transactions->ad_id->FldCaption(), $payment_transactions->ad_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ad_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($payment_transactions->ad_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_package_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment_transactions->package_id->FldCaption(), $payment_transactions->package_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pay_method_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment_transactions->pay_method_id->FldCaption(), $payment_transactions->pay_method_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_order_reference_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment_transactions->order_reference_id->FldCaption(), $payment_transactions->order_reference_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_amount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment_transactions->amount->FldCaption(), $payment_transactions->amount->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_amount");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($payment_transactions->amount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment_transactions->status->FldCaption(), $payment_transactions->status->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpayment_transactionsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpayment_transactionsadd.ValidateRequired = true;
<?php } else { ?>
fpayment_transactionsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpayment_transactionsadd.Lists["x_ad_id"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_ad_title","x_demand_price","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpayment_transactionsadd.Lists["x_package_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_package_fee","x_number_of_days","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpayment_transactionsadd.Lists["x_pay_method_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpayment_transactionsadd.Lists["x_bank_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_bank_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpayment_transactionsadd.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpayment_transactionsadd.Lists["x_status"].Options = <?php echo json_encode($payment_transactions->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $payment_transactions_add->ShowPageHeader(); ?>
<?php
$payment_transactions_add->ShowMessage();
?>
<form name="fpayment_transactionsadd" id="fpayment_transactionsadd" class="<?php echo $payment_transactions_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($payment_transactions_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $payment_transactions_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="payment_transactions">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($payment_transactions->ad_id->Visible) { // ad_id ?>
	<div id="r_ad_id" class="form-group">
		<label id="elh_payment_transactions_ad_id" class="col-sm-2 control-label ewLabel"><?php echo $payment_transactions->ad_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $payment_transactions->ad_id->CellAttributes() ?>>
<span id="el_payment_transactions_ad_id">
<?php
$wrkonchange = trim(" " . @$payment_transactions->ad_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$payment_transactions->ad_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_ad_id" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_ad_id" id="sv_x_ad_id" value="<?php echo $payment_transactions->ad_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($payment_transactions->ad_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($payment_transactions->ad_id->getPlaceHolder()) ?>"<?php echo $payment_transactions->ad_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="payment_transactions" data-field="x_ad_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($payment_transactions->ad_id->DisplayValueSeparator) ? json_encode($payment_transactions->ad_id->DisplayValueSeparator) : $payment_transactions->ad_id->DisplayValueSeparator) ?>" name="x_ad_id" id="x_ad_id" value="<?php echo ew_HtmlEncode($payment_transactions->ad_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `ad_title` AS `DispFld`, `demand_price` AS `Disp2Fld` FROM `car_ads`";
$sWhereWrk = "`ad_title` LIKE '{query_value}%' OR CONCAT(`ad_title`,'" . ew_ValueSeparator(1, $Page->ad_id) . "',`demand_price`) LIKE '{query_value}%'";
$payment_transactions->Lookup_Selecting($payment_transactions->ad_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_ad_id" id="q_x_ad_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fpayment_transactionsadd.CreateAutoSuggest({"id":"x_ad_id","forceSelect":false});
</script>
</span>
<?php echo $payment_transactions->ad_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->package_id->Visible) { // package_id ?>
	<div id="r_package_id" class="form-group">
		<label id="elh_payment_transactions_package_id" for="x_package_id" class="col-sm-2 control-label ewLabel"><?php echo $payment_transactions->package_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $payment_transactions->package_id->CellAttributes() ?>>
<span id="el_payment_transactions_package_id">
<select data-table="payment_transactions" data-field="x_package_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($payment_transactions->package_id->DisplayValueSeparator) ? json_encode($payment_transactions->package_id->DisplayValueSeparator) : $payment_transactions->package_id->DisplayValueSeparator) ?>" id="x_package_id" name="x_package_id"<?php echo $payment_transactions->package_id->EditAttributes() ?>>
<?php
if (is_array($payment_transactions->package_id->EditValue)) {
	$arwrk = $payment_transactions->package_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($payment_transactions->package_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
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
<?php echo $payment_transactions->package_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->pay_method_id->Visible) { // pay_method_id ?>
	<div id="r_pay_method_id" class="form-group">
		<label id="elh_payment_transactions_pay_method_id" for="x_pay_method_id" class="col-sm-2 control-label ewLabel"><?php echo $payment_transactions->pay_method_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $payment_transactions->pay_method_id->CellAttributes() ?>>
<span id="el_payment_transactions_pay_method_id">
<select data-table="payment_transactions" data-field="x_pay_method_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($payment_transactions->pay_method_id->DisplayValueSeparator) ? json_encode($payment_transactions->pay_method_id->DisplayValueSeparator) : $payment_transactions->pay_method_id->DisplayValueSeparator) ?>" id="x_pay_method_id" name="x_pay_method_id"<?php echo $payment_transactions->pay_method_id->EditAttributes() ?>>
<?php
if (is_array($payment_transactions->pay_method_id->EditValue)) {
	$arwrk = $payment_transactions->pay_method_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($payment_transactions->pay_method_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
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
<?php echo $payment_transactions->pay_method_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->bank_id->Visible) { // bank_id ?>
	<div id="r_bank_id" class="form-group">
		<label id="elh_payment_transactions_bank_id" for="x_bank_id" class="col-sm-2 control-label ewLabel"><?php echo $payment_transactions->bank_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $payment_transactions->bank_id->CellAttributes() ?>>
<span id="el_payment_transactions_bank_id">
<select data-table="payment_transactions" data-field="x_bank_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($payment_transactions->bank_id->DisplayValueSeparator) ? json_encode($payment_transactions->bank_id->DisplayValueSeparator) : $payment_transactions->bank_id->DisplayValueSeparator) ?>" id="x_bank_id" name="x_bank_id"<?php echo $payment_transactions->bank_id->EditAttributes() ?>>
<?php
if (is_array($payment_transactions->bank_id->EditValue)) {
	$arwrk = $payment_transactions->bank_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($payment_transactions->bank_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
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
<?php echo $payment_transactions->bank_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->transaction_id->Visible) { // transaction_id ?>
	<div id="r_transaction_id" class="form-group">
		<label id="elh_payment_transactions_transaction_id" for="x_transaction_id" class="col-sm-2 control-label ewLabel"><?php echo $payment_transactions->transaction_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $payment_transactions->transaction_id->CellAttributes() ?>>
<span id="el_payment_transactions_transaction_id">
<input type="text" data-table="payment_transactions" data-field="x_transaction_id" name="x_transaction_id" id="x_transaction_id" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($payment_transactions->transaction_id->getPlaceHolder()) ?>" value="<?php echo $payment_transactions->transaction_id->EditValue ?>"<?php echo $payment_transactions->transaction_id->EditAttributes() ?>>
</span>
<?php echo $payment_transactions->transaction_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->order_reference_id->Visible) { // order_reference_id ?>
	<div id="r_order_reference_id" class="form-group">
		<label id="elh_payment_transactions_order_reference_id" for="x_order_reference_id" class="col-sm-2 control-label ewLabel"><?php echo $payment_transactions->order_reference_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $payment_transactions->order_reference_id->CellAttributes() ?>>
<span id="el_payment_transactions_order_reference_id">
<input type="text" data-table="payment_transactions" data-field="x_order_reference_id" name="x_order_reference_id" id="x_order_reference_id" size="30" maxlength="22" placeholder="<?php echo ew_HtmlEncode($payment_transactions->order_reference_id->getPlaceHolder()) ?>" value="<?php echo $payment_transactions->order_reference_id->EditValue ?>"<?php echo $payment_transactions->order_reference_id->EditAttributes() ?>>
</span>
<?php echo $payment_transactions->order_reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->amount->Visible) { // amount ?>
	<div id="r_amount" class="form-group">
		<label id="elh_payment_transactions_amount" for="x_amount" class="col-sm-2 control-label ewLabel"><?php echo $payment_transactions->amount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $payment_transactions->amount->CellAttributes() ?>>
<span id="el_payment_transactions_amount">
<input type="text" data-table="payment_transactions" data-field="x_amount" name="x_amount" id="x_amount" size="30" placeholder="<?php echo ew_HtmlEncode($payment_transactions->amount->getPlaceHolder()) ?>" value="<?php echo $payment_transactions->amount->EditValue ?>"<?php echo $payment_transactions->amount->EditAttributes() ?>>
</span>
<?php echo $payment_transactions->amount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->response->Visible) { // response ?>
	<div id="r_response" class="form-group">
		<label id="elh_payment_transactions_response" for="x_response" class="col-sm-2 control-label ewLabel"><?php echo $payment_transactions->response->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $payment_transactions->response->CellAttributes() ?>>
<span id="el_payment_transactions_response">
<textarea data-table="payment_transactions" data-field="x_response" name="x_response" id="x_response" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($payment_transactions->response->getPlaceHolder()) ?>"<?php echo $payment_transactions->response->EditAttributes() ?>><?php echo $payment_transactions->response->EditValue ?></textarea>
</span>
<?php echo $payment_transactions->response->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment_transactions->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_payment_transactions_status" class="col-sm-2 control-label ewLabel"><?php echo $payment_transactions->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $payment_transactions->status->CellAttributes() ?>>
<span id="el_payment_transactions_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="payment_transactions" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($payment_transactions->status->DisplayValueSeparator) ? json_encode($payment_transactions->status->DisplayValueSeparator) : $payment_transactions->status->DisplayValueSeparator) ?>" name="x_status" id="x_status" value="{value}"<?php echo $payment_transactions->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $payment_transactions->status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($payment_transactions->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
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
<?php echo $payment_transactions->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $payment_transactions_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fpayment_transactionsadd.Init();
</script>
<?php
$payment_transactions_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$payment_transactions_add->Page_Terminate();
?>
