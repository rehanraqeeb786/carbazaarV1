<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "dealers_showroominfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$dealers_showroom_delete = NULL; // Initialize page object first

class cdealers_showroom_delete extends cdealers_showroom {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'dealers_showroom';

	// Page object name
	var $PageObjName = 'dealers_showroom_delete';

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

		// Table object (dealers_showroom)
		if (!isset($GLOBALS["dealers_showroom"]) || get_class($GLOBALS["dealers_showroom"]) == "cdealers_showroom") {
			$GLOBALS["dealers_showroom"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dealers_showroom"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'dealers_showroom', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("dealers_showroomlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $dealers_showroom;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($dealers_showroom);
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("dealers_showroomlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in dealers_showroom class, dealers_showroominfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->ID->setDbValue($rs->fields('ID'));
		$this->customer_id->setDbValue($rs->fields('customer_id'));
		$this->showroom_name->setDbValue($rs->fields('showroom_name'));
		$this->showroom_address->setDbValue($rs->fields('showroom_address'));
		$this->city_id->setDbValue($rs->fields('city_id'));
		$this->owner_name->setDbValue($rs->fields('owner_name'));
		$this->contact_1->setDbValue($rs->fields('contact_1'));
		$this->contat_2->setDbValue($rs->fields('contat_2'));
		$this->showroom_logo_link->setDbValue($rs->fields('showroom_logo_link'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->customer_id->DbValue = $row['customer_id'];
		$this->showroom_name->DbValue = $row['showroom_name'];
		$this->showroom_address->DbValue = $row['showroom_address'];
		$this->city_id->DbValue = $row['city_id'];
		$this->owner_name->DbValue = $row['owner_name'];
		$this->contact_1->DbValue = $row['contact_1'];
		$this->contat_2->DbValue = $row['contat_2'];
		$this->showroom_logo_link->DbValue = $row['showroom_logo_link'];
		$this->status->DbValue = $row['status'];
		$this->created_at->DbValue = $row['created_at'];
		$this->updated_at->DbValue = $row['updated_at'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// ID
		// customer_id
		// showroom_name
		// showroom_address
		// city_id
		// owner_name
		// contact_1
		// contat_2
		// showroom_logo_link
		// status
		// created_at
		// updated_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ID
		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// customer_id
		$this->customer_id->ViewValue = $this->customer_id->CurrentValue;
		$this->customer_id->ViewCustomAttributes = "";

		// showroom_name
		$this->showroom_name->ViewValue = $this->showroom_name->CurrentValue;
		$this->showroom_name->ViewCustomAttributes = "";

		// showroom_address
		$this->showroom_address->ViewValue = $this->showroom_address->CurrentValue;
		$this->showroom_address->ViewCustomAttributes = "";

		// city_id
		$this->city_id->ViewValue = $this->city_id->CurrentValue;
		$this->city_id->ViewCustomAttributes = "";

		// owner_name
		$this->owner_name->ViewValue = $this->owner_name->CurrentValue;
		$this->owner_name->ViewCustomAttributes = "";

		// contact_1
		$this->contact_1->ViewValue = $this->contact_1->CurrentValue;
		$this->contact_1->ViewCustomAttributes = "";

		// contat_2
		$this->contat_2->ViewValue = $this->contat_2->CurrentValue;
		$this->contat_2->ViewCustomAttributes = "";

		// showroom_logo_link
		$this->showroom_logo_link->ViewValue = $this->showroom_logo_link->CurrentValue;
		$this->showroom_logo_link->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "1";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "0";
		}
		$this->status->ViewCustomAttributes = "";

		// created_at
		$this->created_at->ViewValue = $this->created_at->CurrentValue;
		$this->created_at->ViewValue = ew_FormatDateTime($this->created_at->ViewValue, 5);
		$this->created_at->ViewCustomAttributes = "";

		// updated_at
		$this->updated_at->ViewValue = $this->updated_at->CurrentValue;
		$this->updated_at->ViewValue = ew_FormatDateTime($this->updated_at->ViewValue, 5);
		$this->updated_at->ViewCustomAttributes = "";

			// ID
			$this->ID->LinkCustomAttributes = "";
			$this->ID->HrefValue = "";
			$this->ID->TooltipValue = "";

			// customer_id
			$this->customer_id->LinkCustomAttributes = "";
			$this->customer_id->HrefValue = "";
			$this->customer_id->TooltipValue = "";

			// showroom_name
			$this->showroom_name->LinkCustomAttributes = "";
			$this->showroom_name->HrefValue = "";
			$this->showroom_name->TooltipValue = "";

			// showroom_address
			$this->showroom_address->LinkCustomAttributes = "";
			$this->showroom_address->HrefValue = "";
			$this->showroom_address->TooltipValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";
			$this->city_id->TooltipValue = "";

			// owner_name
			$this->owner_name->LinkCustomAttributes = "";
			$this->owner_name->HrefValue = "";
			$this->owner_name->TooltipValue = "";

			// contact_1
			$this->contact_1->LinkCustomAttributes = "";
			$this->contact_1->HrefValue = "";
			$this->contact_1->TooltipValue = "";

			// contat_2
			$this->contat_2->LinkCustomAttributes = "";
			$this->contat_2->HrefValue = "";
			$this->contat_2->TooltipValue = "";

			// showroom_logo_link
			$this->showroom_logo_link->LinkCustomAttributes = "";
			$this->showroom_logo_link->HrefValue = "";
			$this->showroom_logo_link->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// created_at
			$this->created_at->LinkCustomAttributes = "";
			$this->created_at->HrefValue = "";
			$this->created_at->TooltipValue = "";

			// updated_at
			$this->updated_at->LinkCustomAttributes = "";
			$this->updated_at->HrefValue = "";
			$this->updated_at->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['ID'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("dealers_showroomlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($dealers_showroom_delete)) $dealers_showroom_delete = new cdealers_showroom_delete();

// Page init
$dealers_showroom_delete->Page_Init();

// Page main
$dealers_showroom_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dealers_showroom_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fdealers_showroomdelete = new ew_Form("fdealers_showroomdelete", "delete");

// Form_CustomValidate event
fdealers_showroomdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdealers_showroomdelete.ValidateRequired = true;
<?php } else { ?>
fdealers_showroomdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdealers_showroomdelete.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdealers_showroomdelete.Lists["x_status"].Options = <?php echo json_encode($dealers_showroom->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($dealers_showroom_delete->Recordset = $dealers_showroom_delete->LoadRecordset())
	$dealers_showroom_deleteTotalRecs = $dealers_showroom_delete->Recordset->RecordCount(); // Get record count
if ($dealers_showroom_deleteTotalRecs <= 0) { // No record found, exit
	if ($dealers_showroom_delete->Recordset)
		$dealers_showroom_delete->Recordset->Close();
	$dealers_showroom_delete->Page_Terminate("dealers_showroomlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $dealers_showroom_delete->ShowPageHeader(); ?>
<?php
$dealers_showroom_delete->ShowMessage();
?>
<form name="fdealers_showroomdelete" id="fdealers_showroomdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dealers_showroom_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dealers_showroom_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dealers_showroom">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($dealers_showroom_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $dealers_showroom->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($dealers_showroom->ID->Visible) { // ID ?>
		<th><span id="elh_dealers_showroom_ID" class="dealers_showroom_ID"><?php echo $dealers_showroom->ID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->customer_id->Visible) { // customer_id ?>
		<th><span id="elh_dealers_showroom_customer_id" class="dealers_showroom_customer_id"><?php echo $dealers_showroom->customer_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->showroom_name->Visible) { // showroom_name ?>
		<th><span id="elh_dealers_showroom_showroom_name" class="dealers_showroom_showroom_name"><?php echo $dealers_showroom->showroom_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->showroom_address->Visible) { // showroom_address ?>
		<th><span id="elh_dealers_showroom_showroom_address" class="dealers_showroom_showroom_address"><?php echo $dealers_showroom->showroom_address->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->city_id->Visible) { // city_id ?>
		<th><span id="elh_dealers_showroom_city_id" class="dealers_showroom_city_id"><?php echo $dealers_showroom->city_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->owner_name->Visible) { // owner_name ?>
		<th><span id="elh_dealers_showroom_owner_name" class="dealers_showroom_owner_name"><?php echo $dealers_showroom->owner_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->contact_1->Visible) { // contact_1 ?>
		<th><span id="elh_dealers_showroom_contact_1" class="dealers_showroom_contact_1"><?php echo $dealers_showroom->contact_1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->contat_2->Visible) { // contat_2 ?>
		<th><span id="elh_dealers_showroom_contat_2" class="dealers_showroom_contat_2"><?php echo $dealers_showroom->contat_2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->showroom_logo_link->Visible) { // showroom_logo_link ?>
		<th><span id="elh_dealers_showroom_showroom_logo_link" class="dealers_showroom_showroom_logo_link"><?php echo $dealers_showroom->showroom_logo_link->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->status->Visible) { // status ?>
		<th><span id="elh_dealers_showroom_status" class="dealers_showroom_status"><?php echo $dealers_showroom->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->created_at->Visible) { // created_at ?>
		<th><span id="elh_dealers_showroom_created_at" class="dealers_showroom_created_at"><?php echo $dealers_showroom->created_at->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dealers_showroom->updated_at->Visible) { // updated_at ?>
		<th><span id="elh_dealers_showroom_updated_at" class="dealers_showroom_updated_at"><?php echo $dealers_showroom->updated_at->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$dealers_showroom_delete->RecCnt = 0;
$i = 0;
while (!$dealers_showroom_delete->Recordset->EOF) {
	$dealers_showroom_delete->RecCnt++;
	$dealers_showroom_delete->RowCnt++;

	// Set row properties
	$dealers_showroom->ResetAttrs();
	$dealers_showroom->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$dealers_showroom_delete->LoadRowValues($dealers_showroom_delete->Recordset);

	// Render row
	$dealers_showroom_delete->RenderRow();
?>
	<tr<?php echo $dealers_showroom->RowAttributes() ?>>
<?php if ($dealers_showroom->ID->Visible) { // ID ?>
		<td<?php echo $dealers_showroom->ID->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_ID" class="dealers_showroom_ID">
<span<?php echo $dealers_showroom->ID->ViewAttributes() ?>>
<?php echo $dealers_showroom->ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->customer_id->Visible) { // customer_id ?>
		<td<?php echo $dealers_showroom->customer_id->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_customer_id" class="dealers_showroom_customer_id">
<span<?php echo $dealers_showroom->customer_id->ViewAttributes() ?>>
<?php echo $dealers_showroom->customer_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->showroom_name->Visible) { // showroom_name ?>
		<td<?php echo $dealers_showroom->showroom_name->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_showroom_name" class="dealers_showroom_showroom_name">
<span<?php echo $dealers_showroom->showroom_name->ViewAttributes() ?>>
<?php echo $dealers_showroom->showroom_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->showroom_address->Visible) { // showroom_address ?>
		<td<?php echo $dealers_showroom->showroom_address->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_showroom_address" class="dealers_showroom_showroom_address">
<span<?php echo $dealers_showroom->showroom_address->ViewAttributes() ?>>
<?php echo $dealers_showroom->showroom_address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->city_id->Visible) { // city_id ?>
		<td<?php echo $dealers_showroom->city_id->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_city_id" class="dealers_showroom_city_id">
<span<?php echo $dealers_showroom->city_id->ViewAttributes() ?>>
<?php echo $dealers_showroom->city_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->owner_name->Visible) { // owner_name ?>
		<td<?php echo $dealers_showroom->owner_name->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_owner_name" class="dealers_showroom_owner_name">
<span<?php echo $dealers_showroom->owner_name->ViewAttributes() ?>>
<?php echo $dealers_showroom->owner_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->contact_1->Visible) { // contact_1 ?>
		<td<?php echo $dealers_showroom->contact_1->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_contact_1" class="dealers_showroom_contact_1">
<span<?php echo $dealers_showroom->contact_1->ViewAttributes() ?>>
<?php echo $dealers_showroom->contact_1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->contat_2->Visible) { // contat_2 ?>
		<td<?php echo $dealers_showroom->contat_2->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_contat_2" class="dealers_showroom_contat_2">
<span<?php echo $dealers_showroom->contat_2->ViewAttributes() ?>>
<?php echo $dealers_showroom->contat_2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->showroom_logo_link->Visible) { // showroom_logo_link ?>
		<td<?php echo $dealers_showroom->showroom_logo_link->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_showroom_logo_link" class="dealers_showroom_showroom_logo_link">
<span<?php echo $dealers_showroom->showroom_logo_link->ViewAttributes() ?>>
<?php echo $dealers_showroom->showroom_logo_link->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->status->Visible) { // status ?>
		<td<?php echo $dealers_showroom->status->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_status" class="dealers_showroom_status">
<span<?php echo $dealers_showroom->status->ViewAttributes() ?>>
<?php echo $dealers_showroom->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->created_at->Visible) { // created_at ?>
		<td<?php echo $dealers_showroom->created_at->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_created_at" class="dealers_showroom_created_at">
<span<?php echo $dealers_showroom->created_at->ViewAttributes() ?>>
<?php echo $dealers_showroom->created_at->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dealers_showroom->updated_at->Visible) { // updated_at ?>
		<td<?php echo $dealers_showroom->updated_at->CellAttributes() ?>>
<span id="el<?php echo $dealers_showroom_delete->RowCnt ?>_dealers_showroom_updated_at" class="dealers_showroom_updated_at">
<span<?php echo $dealers_showroom->updated_at->ViewAttributes() ?>>
<?php echo $dealers_showroom->updated_at->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$dealers_showroom_delete->Recordset->MoveNext();
}
$dealers_showroom_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $dealers_showroom_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdealers_showroomdelete.Init();
</script>
<?php
$dealers_showroom_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dealers_showroom_delete->Page_Terminate();
?>
