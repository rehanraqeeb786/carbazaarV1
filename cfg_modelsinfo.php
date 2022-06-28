<?php

// Global variable for table object
$cfg_models = NULL;

//
// Table class for cfg_models
//
class ccfg_models extends cTable {
	var $ID;
	var $title;
	var $make_company_id;
	var $main_pic_link;
	var $pic_2;
	var $pic_3;
	var $pic_4;
	var $pic_5;
	var $details;
	var $status;
	var $created_at;
	var $updated_at;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'cfg_models';
		$this->TableName = 'cfg_models';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`cfg_models`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// ID
		$this->ID = new cField('cfg_models', 'cfg_models', 'x_ID', 'ID', '`ID`', '`ID`', 3, -1, FALSE, '`ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// title
		$this->title = new cField('cfg_models', 'cfg_models', 'x_title', 'title', '`title`', '`title`', 200, -1, FALSE, '`title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['title'] = &$this->title;

		// make_company_id
		$this->make_company_id = new cField('cfg_models', 'cfg_models', 'x_make_company_id', 'make_company_id', '`make_company_id`', '`make_company_id`', 3, -1, FALSE, '`make_company_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->make_company_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['make_company_id'] = &$this->make_company_id;

		// main_pic_link
		$this->main_pic_link = new cField('cfg_models', 'cfg_models', 'x_main_pic_link', 'main_pic_link', '`main_pic_link`', '`main_pic_link`', 200, -1, TRUE, '`main_pic_link`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->fields['main_pic_link'] = &$this->main_pic_link;

		// pic_2
		$this->pic_2 = new cField('cfg_models', 'cfg_models', 'x_pic_2', 'pic_2', '`pic_2`', '`pic_2`', 200, -1, TRUE, '`pic_2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->fields['pic_2'] = &$this->pic_2;

		// pic_3
		$this->pic_3 = new cField('cfg_models', 'cfg_models', 'x_pic_3', 'pic_3', '`pic_3`', '`pic_3`', 200, -1, TRUE, '`pic_3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->fields['pic_3'] = &$this->pic_3;

		// pic_4
		$this->pic_4 = new cField('cfg_models', 'cfg_models', 'x_pic_4', 'pic_4', '`pic_4`', '`pic_4`', 200, -1, TRUE, '`pic_4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->fields['pic_4'] = &$this->pic_4;

		// pic_5
		$this->pic_5 = new cField('cfg_models', 'cfg_models', 'x_pic_5', 'pic_5', '`pic_5`', '`pic_5`', 200, -1, TRUE, '`pic_5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->fields['pic_5'] = &$this->pic_5;

		// details
		$this->details = new cField('cfg_models', 'cfg_models', 'x_details', 'details', '`details`', '`details`', 201, -1, FALSE, '`details`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['details'] = &$this->details;

		// status
		$this->status = new cField('cfg_models', 'cfg_models', 'x_status', 'status', '`status`', '`status`', 202, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->status->FldDataType = EW_DATATYPE_BOOLEAN;
		$this->status->OptionCount = 2;
		$this->fields['status'] = &$this->status;

		// created_at
		$this->created_at = new cField('cfg_models', 'cfg_models', 'x_created_at', 'created_at', '`created_at`', 'DATE_FORMAT(`created_at`, \'%Y/%m/%d\')', 135, 5, FALSE, '`created_at`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->created_at->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['created_at'] = &$this->created_at;

		// updated_at
		$this->updated_at = new cField('cfg_models', 'cfg_models', 'x_updated_at', 'updated_at', '`updated_at`', 'DATE_FORMAT(`updated_at`, \'%Y/%m/%d\')', 135, 5, FALSE, '`updated_at`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->updated_at->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['updated_at'] = &$this->updated_at;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`cfg_models`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('ID', $rs))
				ew_AddFilter($where, ew_QuotedName('ID', $this->DBID) . '=' . ew_QuotedValue($rs['ID'], $this->ID->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`ID` = @ID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->ID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@ID@", ew_AdjustSql($this->ID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "cfg_modelslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "cfg_modelslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("cfg_modelsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("cfg_modelsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "cfg_modelsadd.php?" . $this->UrlParm($parm);
		else
			$url = "cfg_modelsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("cfg_modelsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("cfg_modelsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("cfg_modelsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "ID:" . ew_VarToJson($this->ID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->ID->CurrentValue)) {
			$sUrl .= "ID=" . urlencode($this->ID->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["ID"]))
				$arKeys[] = ew_StripSlashes($_POST["ID"]);
			elseif (isset($_GET["ID"]))
				$arKeys[] = ew_StripSlashes($_GET["ID"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->ID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->ID->setDbValue($rs->fields('ID'));
		$this->title->setDbValue($rs->fields('title'));
		$this->make_company_id->setDbValue($rs->fields('make_company_id'));
		$this->main_pic_link->Upload->DbValue = $rs->fields('main_pic_link');
		$this->pic_2->Upload->DbValue = $rs->fields('pic_2');
		$this->pic_3->Upload->DbValue = $rs->fields('pic_3');
		$this->pic_4->Upload->DbValue = $rs->fields('pic_4');
		$this->pic_5->Upload->DbValue = $rs->fields('pic_5');
		$this->details->setDbValue($rs->fields('details'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created_at->setDbValue($rs->fields('created_at'));
		$this->updated_at->setDbValue($rs->fields('updated_at'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ID

		$this->ID->CellCssStyle = "white-space: nowrap;";

		// title
		// make_company_id
		// main_pic_link
		// pic_2
		// pic_3
		// pic_4
		// pic_5
		// details
		// status
		// created_at

		$this->created_at->CellCssStyle = "white-space: nowrap;";

		// updated_at
		$this->updated_at->CellCssStyle = "white-space: nowrap;";

		// ID
		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// make_company_id
		if (strval($this->make_company_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->make_company_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->make_company_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->make_company_id->ViewValue = $this->make_company_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->make_company_id->ViewValue = $this->make_company_id->CurrentValue;
			}
		} else {
			$this->make_company_id->ViewValue = NULL;
		}
		$this->make_company_id->ViewCustomAttributes = "";

		// main_pic_link
		if (!ew_Empty($this->main_pic_link->Upload->DbValue)) {
			$this->main_pic_link->ViewValue = $this->main_pic_link->Upload->DbValue;
		} else {
			$this->main_pic_link->ViewValue = "";
		}
		$this->main_pic_link->ViewCustomAttributes = "";

		// pic_2
		if (!ew_Empty($this->pic_2->Upload->DbValue)) {
			$this->pic_2->ViewValue = $this->pic_2->Upload->DbValue;
		} else {
			$this->pic_2->ViewValue = "";
		}
		$this->pic_2->ViewCustomAttributes = "";

		// pic_3
		if (!ew_Empty($this->pic_3->Upload->DbValue)) {
			$this->pic_3->ViewValue = $this->pic_3->Upload->DbValue;
		} else {
			$this->pic_3->ViewValue = "";
		}
		$this->pic_3->ViewCustomAttributes = "";

		// pic_4
		if (!ew_Empty($this->pic_4->Upload->DbValue)) {
			$this->pic_4->ViewValue = $this->pic_4->Upload->DbValue;
		} else {
			$this->pic_4->ViewValue = "";
		}
		$this->pic_4->ViewCustomAttributes = "";

		// pic_5
		if (!ew_Empty($this->pic_5->Upload->DbValue)) {
			$this->pic_5->ViewValue = $this->pic_5->Upload->DbValue;
		} else {
			$this->pic_5->ViewValue = "";
		}
		$this->pic_5->ViewCustomAttributes = "";

		// details
		$this->details->ViewValue = $this->details->CurrentValue;
		$this->details->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
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

		// title
		$this->title->LinkCustomAttributes = "";
		$this->title->HrefValue = "";
		$this->title->TooltipValue = "";

		// make_company_id
		$this->make_company_id->LinkCustomAttributes = "";
		$this->make_company_id->HrefValue = "";
		$this->make_company_id->TooltipValue = "";

		// main_pic_link
		$this->main_pic_link->LinkCustomAttributes = "";
		$this->main_pic_link->HrefValue = "";
		$this->main_pic_link->HrefValue2 = $this->main_pic_link->UploadPath . $this->main_pic_link->Upload->DbValue;
		$this->main_pic_link->TooltipValue = "";

		// pic_2
		$this->pic_2->LinkCustomAttributes = "";
		$this->pic_2->HrefValue = "";
		$this->pic_2->HrefValue2 = $this->pic_2->UploadPath . $this->pic_2->Upload->DbValue;
		$this->pic_2->TooltipValue = "";

		// pic_3
		$this->pic_3->LinkCustomAttributes = "";
		$this->pic_3->HrefValue = "";
		$this->pic_3->HrefValue2 = $this->pic_3->UploadPath . $this->pic_3->Upload->DbValue;
		$this->pic_3->TooltipValue = "";

		// pic_4
		$this->pic_4->LinkCustomAttributes = "";
		$this->pic_4->HrefValue = "";
		$this->pic_4->HrefValue2 = $this->pic_4->UploadPath . $this->pic_4->Upload->DbValue;
		$this->pic_4->TooltipValue = "";

		// pic_5
		$this->pic_5->LinkCustomAttributes = "";
		$this->pic_5->HrefValue = "";
		$this->pic_5->HrefValue2 = $this->pic_5->UploadPath . $this->pic_5->Upload->DbValue;
		$this->pic_5->TooltipValue = "";

		// details
		$this->details->LinkCustomAttributes = "";
		$this->details->HrefValue = "";
		$this->details->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// ID
		$this->ID->EditAttrs["class"] = "form-control";
		$this->ID->EditCustomAttributes = "";
		$this->ID->EditValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// title
		$this->title->EditAttrs["class"] = "form-control";
		$this->title->EditCustomAttributes = "";
		$this->title->EditValue = $this->title->CurrentValue;
		$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

		// make_company_id
		$this->make_company_id->EditAttrs["class"] = "form-control";
		$this->make_company_id->EditCustomAttributes = "";

		// main_pic_link
		$this->main_pic_link->EditAttrs["class"] = "form-control";
		$this->main_pic_link->EditCustomAttributes = "";
		if (!ew_Empty($this->main_pic_link->Upload->DbValue)) {
			$this->main_pic_link->EditValue = $this->main_pic_link->Upload->DbValue;
		} else {
			$this->main_pic_link->EditValue = "";
		}
		if (!ew_Empty($this->main_pic_link->CurrentValue))
			$this->main_pic_link->Upload->FileName = $this->main_pic_link->CurrentValue;

		// pic_2
		$this->pic_2->EditAttrs["class"] = "form-control";
		$this->pic_2->EditCustomAttributes = "";
		if (!ew_Empty($this->pic_2->Upload->DbValue)) {
			$this->pic_2->EditValue = $this->pic_2->Upload->DbValue;
		} else {
			$this->pic_2->EditValue = "";
		}
		if (!ew_Empty($this->pic_2->CurrentValue))
			$this->pic_2->Upload->FileName = $this->pic_2->CurrentValue;

		// pic_3
		$this->pic_3->EditAttrs["class"] = "form-control";
		$this->pic_3->EditCustomAttributes = "";
		if (!ew_Empty($this->pic_3->Upload->DbValue)) {
			$this->pic_3->EditValue = $this->pic_3->Upload->DbValue;
		} else {
			$this->pic_3->EditValue = "";
		}
		if (!ew_Empty($this->pic_3->CurrentValue))
			$this->pic_3->Upload->FileName = $this->pic_3->CurrentValue;

		// pic_4
		$this->pic_4->EditAttrs["class"] = "form-control";
		$this->pic_4->EditCustomAttributes = "";
		if (!ew_Empty($this->pic_4->Upload->DbValue)) {
			$this->pic_4->EditValue = $this->pic_4->Upload->DbValue;
		} else {
			$this->pic_4->EditValue = "";
		}
		if (!ew_Empty($this->pic_4->CurrentValue))
			$this->pic_4->Upload->FileName = $this->pic_4->CurrentValue;

		// pic_5
		$this->pic_5->EditAttrs["class"] = "form-control";
		$this->pic_5->EditCustomAttributes = "";
		if (!ew_Empty($this->pic_5->Upload->DbValue)) {
			$this->pic_5->EditValue = $this->pic_5->Upload->DbValue;
		} else {
			$this->pic_5->EditValue = "";
		}
		if (!ew_Empty($this->pic_5->CurrentValue))
			$this->pic_5->Upload->FileName = $this->pic_5->CurrentValue;

		// details
		$this->details->EditAttrs["class"] = "form-control";
		$this->details->EditCustomAttributes = "";
		$this->details->EditValue = $this->details->CurrentValue;
		$this->details->PlaceHolder = ew_RemoveHtml($this->details->FldCaption());

		// status
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->Options(FALSE);

		// created_at
		$this->created_at->EditAttrs["class"] = "form-control";
		$this->created_at->EditCustomAttributes = "";
		$this->created_at->EditValue = ew_FormatDateTime($this->created_at->CurrentValue, 5);
		$this->created_at->PlaceHolder = ew_RemoveHtml($this->created_at->FldCaption());

		// updated_at
		$this->updated_at->EditAttrs["class"] = "form-control";
		$this->updated_at->EditCustomAttributes = "";
		$this->updated_at->EditValue = ew_FormatDateTime($this->updated_at->CurrentValue, 5);
		$this->updated_at->PlaceHolder = ew_RemoveHtml($this->updated_at->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->title->Exportable) $Doc->ExportCaption($this->title);
					if ($this->make_company_id->Exportable) $Doc->ExportCaption($this->make_company_id);
					if ($this->main_pic_link->Exportable) $Doc->ExportCaption($this->main_pic_link);
					if ($this->pic_2->Exportable) $Doc->ExportCaption($this->pic_2);
					if ($this->pic_3->Exportable) $Doc->ExportCaption($this->pic_3);
					if ($this->pic_4->Exportable) $Doc->ExportCaption($this->pic_4);
					if ($this->pic_5->Exportable) $Doc->ExportCaption($this->pic_5);
					if ($this->details->Exportable) $Doc->ExportCaption($this->details);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				} else {
					if ($this->title->Exportable) $Doc->ExportCaption($this->title);
					if ($this->make_company_id->Exportable) $Doc->ExportCaption($this->make_company_id);
					if ($this->main_pic_link->Exportable) $Doc->ExportCaption($this->main_pic_link);
					if ($this->pic_2->Exportable) $Doc->ExportCaption($this->pic_2);
					if ($this->pic_3->Exportable) $Doc->ExportCaption($this->pic_3);
					if ($this->pic_4->Exportable) $Doc->ExportCaption($this->pic_4);
					if ($this->pic_5->Exportable) $Doc->ExportCaption($this->pic_5);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->title->Exportable) $Doc->ExportField($this->title);
						if ($this->make_company_id->Exportable) $Doc->ExportField($this->make_company_id);
						if ($this->main_pic_link->Exportable) $Doc->ExportField($this->main_pic_link);
						if ($this->pic_2->Exportable) $Doc->ExportField($this->pic_2);
						if ($this->pic_3->Exportable) $Doc->ExportField($this->pic_3);
						if ($this->pic_4->Exportable) $Doc->ExportField($this->pic_4);
						if ($this->pic_5->Exportable) $Doc->ExportField($this->pic_5);
						if ($this->details->Exportable) $Doc->ExportField($this->details);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
					} else {
						if ($this->title->Exportable) $Doc->ExportField($this->title);
						if ($this->make_company_id->Exportable) $Doc->ExportField($this->make_company_id);
						if ($this->main_pic_link->Exportable) $Doc->ExportField($this->main_pic_link);
						if ($this->pic_2->Exportable) $Doc->ExportField($this->pic_2);
						if ($this->pic_3->Exportable) $Doc->ExportField($this->pic_3);
						if ($this->pic_4->Exportable) $Doc->ExportField($this->pic_4);
						if ($this->pic_5->Exportable) $Doc->ExportField($this->pic_5);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
