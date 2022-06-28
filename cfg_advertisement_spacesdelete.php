<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cfg_advertisement_spacesinfo.php" ?>
<?php include_once "adm_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cfg_advertisement_spaces_delete = NULL; // Initialize page object first

class ccfg_advertisement_spaces_delete extends ccfg_advertisement_spaces {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{B7B92366-0BC8-4357-900F-FDBD8A72F51D}";

	// Table name
	var $TableName = 'cfg_advertisement_spaces';

	// Page object name
	var $PageObjName = 'cfg_advertisement_spaces_delete';

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

		// Table object (cfg_advertisement_spaces)
		if (!isset($GLOBALS["cfg_advertisement_spaces"]) || get_class($GLOBALS["cfg_advertisement_spaces"]) == "ccfg_advertisement_spaces") {
			$GLOBALS["cfg_advertisement_spaces"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cfg_advertisement_spaces"];
		}

		// Table object (adm_users)
		if (!isset($GLOBALS['adm_users'])) $GLOBALS['adm_users'] = new cadm_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cfg_advertisement_spaces', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cfg_advertisement_spaceslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
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
		global $EW_EXPORT, $cfg_advertisement_spaces;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cfg_advertisement_spaces);
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
			$this->Page_Terminate("cfg_advertisement_spaceslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in cfg_advertisement_spaces class, cfg_advertisement_spacesinfo.php

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
		$this->id->setDbValue($rs->fields('id'));
		$this->ad_space_name->setDbValue($rs->fields('ad_space_name'));
		$this->ad_type->setDbValue($rs->fields('ad_type'));
		$this->ad_placement->setDbValue($rs->fields('ad_placement'));
		$this->ad_script->setDbValue($rs->fields('ad_script'));
		$this->ad_image->Upload->DbValue = $rs->fields('ad_image');
		$this->ad_image->CurrentValue = $this->ad_image->Upload->DbValue;
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->ad_space_name->DbValue = $row['ad_space_name'];
		$this->ad_type->DbValue = $row['ad_type'];
		$this->ad_placement->DbValue = $row['ad_placement'];
		$this->ad_script->DbValue = $row['ad_script'];
		$this->ad_image->Upload->DbValue = $row['ad_image'];
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
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// ad_space_name
		// ad_type
		// ad_placement
		// ad_script
		// ad_image
		// status
		// created_at

		$this->created_at->CellCssStyle = "white-space: nowrap;";

		// updated_at
		$this->updated_at->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ad_space_name
		$this->ad_space_name->ViewValue = $this->ad_space_name->CurrentValue;
		$this->ad_space_name->ViewCustomAttributes = "";

		// ad_type
		if (strval($this->ad_type->CurrentValue) <> "") {
			$this->ad_type->ViewValue = $this->ad_type->OptionCaption($this->ad_type->CurrentValue);
		} else {
			$this->ad_type->ViewValue = NULL;
		}
		$this->ad_type->ViewCustomAttributes = "";

		// ad_placement
		if (strval($this->ad_placement->CurrentValue) <> "") {
			$this->ad_placement->ViewValue = $this->ad_placement->OptionCaption($this->ad_placement->CurrentValue);
		} else {
			$this->ad_placement->ViewValue = NULL;
		}
		$this->ad_placement->ViewCustomAttributes = "";

		// ad_image
		if (!ew_Empty($this->ad_image->Upload->DbValue)) {
			$this->ad_image->ViewValue = $this->ad_image->Upload->DbValue;
		} else {
			$this->ad_image->ViewValue = "";
		}
		$this->ad_image->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "1";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "0";
		}
		$this->status->ViewCustomAttributes = "";

			// ad_space_name
			$this->ad_space_name->LinkCustomAttributes = "";
			$this->ad_space_name->HrefValue = "";
			$this->ad_space_name->TooltipValue = "";

			// ad_type
			$this->ad_type->LinkCustomAttributes = "";
			$this->ad_type->HrefValue = "";
			$this->ad_type->TooltipValue = "";

			// ad_placement
			$this->ad_placement->LinkCustomAttributes = "";
			$this->ad_placement->HrefValue = "";
			$this->ad_placement->TooltipValue = "";

			// ad_image
			$this->ad_image->LinkCustomAttributes = "";
			$this->ad_image->HrefValue = "";
			$this->ad_image->HrefValue2 = $this->ad_image->UploadPath . $this->ad_image->Upload->DbValue;
			$this->ad_image->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
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
				$sThisKey .= $row['id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cfg_advertisement_spaceslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cfg_advertisement_spaces_delete)) $cfg_advertisement_spaces_delete = new ccfg_advertisement_spaces_delete();

// Page init
$cfg_advertisement_spaces_delete->Page_Init();

// Page main
$cfg_advertisement_spaces_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cfg_advertisement_spaces_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fcfg_advertisement_spacesdelete = new ew_Form("fcfg_advertisement_spacesdelete", "delete");

// Form_CustomValidate event
fcfg_advertisement_spacesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcfg_advertisement_spacesdelete.ValidateRequired = true;
<?php } else { ?>
fcfg_advertisement_spacesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcfg_advertisement_spacesdelete.Lists["x_ad_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_advertisement_spacesdelete.Lists["x_ad_type"].Options = <?php echo json_encode($cfg_advertisement_spaces->ad_type->Options()) ?>;
fcfg_advertisement_spacesdelete.Lists["x_ad_placement"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_advertisement_spacesdelete.Lists["x_ad_placement"].Options = <?php echo json_encode($cfg_advertisement_spaces->ad_placement->Options()) ?>;
fcfg_advertisement_spacesdelete.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcfg_advertisement_spacesdelete.Lists["x_status"].Options = <?php echo json_encode($cfg_advertisement_spaces->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($cfg_advertisement_spaces_delete->Recordset = $cfg_advertisement_spaces_delete->LoadRecordset())
	$cfg_advertisement_spaces_deleteTotalRecs = $cfg_advertisement_spaces_delete->Recordset->RecordCount(); // Get record count
if ($cfg_advertisement_spaces_deleteTotalRecs <= 0) { // No record found, exit
	if ($cfg_advertisement_spaces_delete->Recordset)
		$cfg_advertisement_spaces_delete->Recordset->Close();
	$cfg_advertisement_spaces_delete->Page_Terminate("cfg_advertisement_spaceslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $cfg_advertisement_spaces_delete->ShowPageHeader(); ?>
<?php
$cfg_advertisement_spaces_delete->ShowMessage();
?>
<form name="fcfg_advertisement_spacesdelete" id="fcfg_advertisement_spacesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cfg_advertisement_spaces_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cfg_advertisement_spaces_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cfg_advertisement_spaces">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($cfg_advertisement_spaces_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $cfg_advertisement_spaces->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($cfg_advertisement_spaces->ad_space_name->Visible) { // ad_space_name ?>
		<th><span id="elh_cfg_advertisement_spaces_ad_space_name" class="cfg_advertisement_spaces_ad_space_name"><?php echo $cfg_advertisement_spaces->ad_space_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_type->Visible) { // ad_type ?>
		<th><span id="elh_cfg_advertisement_spaces_ad_type" class="cfg_advertisement_spaces_ad_type"><?php echo $cfg_advertisement_spaces->ad_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_placement->Visible) { // ad_placement ?>
		<th><span id="elh_cfg_advertisement_spaces_ad_placement" class="cfg_advertisement_spaces_ad_placement"><?php echo $cfg_advertisement_spaces->ad_placement->FldCaption() ?></span></th>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_image->Visible) { // ad_image ?>
		<th><span id="elh_cfg_advertisement_spaces_ad_image" class="cfg_advertisement_spaces_ad_image"><?php echo $cfg_advertisement_spaces->ad_image->FldCaption() ?></span></th>
<?php } ?>
<?php if ($cfg_advertisement_spaces->status->Visible) { // status ?>
		<th><span id="elh_cfg_advertisement_spaces_status" class="cfg_advertisement_spaces_status"><?php echo $cfg_advertisement_spaces->status->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$cfg_advertisement_spaces_delete->RecCnt = 0;
$i = 0;
while (!$cfg_advertisement_spaces_delete->Recordset->EOF) {
	$cfg_advertisement_spaces_delete->RecCnt++;
	$cfg_advertisement_spaces_delete->RowCnt++;

	// Set row properties
	$cfg_advertisement_spaces->ResetAttrs();
	$cfg_advertisement_spaces->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$cfg_advertisement_spaces_delete->LoadRowValues($cfg_advertisement_spaces_delete->Recordset);

	// Render row
	$cfg_advertisement_spaces_delete->RenderRow();
?>
	<tr<?php echo $cfg_advertisement_spaces->RowAttributes() ?>>
<?php if ($cfg_advertisement_spaces->ad_space_name->Visible) { // ad_space_name ?>
		<td<?php echo $cfg_advertisement_spaces->ad_space_name->CellAttributes() ?>>
<span id="el<?php echo $cfg_advertisement_spaces_delete->RowCnt ?>_cfg_advertisement_spaces_ad_space_name" class="cfg_advertisement_spaces_ad_space_name">
<span<?php echo $cfg_advertisement_spaces->ad_space_name->ViewAttributes() ?>>
<?php echo $cfg_advertisement_spaces->ad_space_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_type->Visible) { // ad_type ?>
		<td<?php echo $cfg_advertisement_spaces->ad_type->CellAttributes() ?>>
<span id="el<?php echo $cfg_advertisement_spaces_delete->RowCnt ?>_cfg_advertisement_spaces_ad_type" class="cfg_advertisement_spaces_ad_type">
<span<?php echo $cfg_advertisement_spaces->ad_type->ViewAttributes() ?>>
<?php echo $cfg_advertisement_spaces->ad_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_placement->Visible) { // ad_placement ?>
		<td<?php echo $cfg_advertisement_spaces->ad_placement->CellAttributes() ?>>
<span id="el<?php echo $cfg_advertisement_spaces_delete->RowCnt ?>_cfg_advertisement_spaces_ad_placement" class="cfg_advertisement_spaces_ad_placement">
<span<?php echo $cfg_advertisement_spaces->ad_placement->ViewAttributes() ?>>
<?php echo $cfg_advertisement_spaces->ad_placement->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cfg_advertisement_spaces->ad_image->Visible) { // ad_image ?>
		<td<?php echo $cfg_advertisement_spaces->ad_image->CellAttributes() ?>>
<span id="el<?php echo $cfg_advertisement_spaces_delete->RowCnt ?>_cfg_advertisement_spaces_ad_image" class="cfg_advertisement_spaces_ad_image">
<span<?php echo $cfg_advertisement_spaces->ad_image->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($cfg_advertisement_spaces->ad_image, $cfg_advertisement_spaces->ad_image->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($cfg_advertisement_spaces->status->Visible) { // status ?>
		<td<?php echo $cfg_advertisement_spaces->status->CellAttributes() ?>>
<span id="el<?php echo $cfg_advertisement_spaces_delete->RowCnt ?>_cfg_advertisement_spaces_status" class="cfg_advertisement_spaces_status">
<span<?php echo $cfg_advertisement_spaces->status->ViewAttributes() ?>>
<?php echo $cfg_advertisement_spaces->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$cfg_advertisement_spaces_delete->Recordset->MoveNext();
}
$cfg_advertisement_spaces_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cfg_advertisement_spaces_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fcfg_advertisement_spacesdelete.Init();
</script>
<?php
$cfg_advertisement_spaces_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cfg_advertisement_spaces_delete->Page_Terminate();
?>
