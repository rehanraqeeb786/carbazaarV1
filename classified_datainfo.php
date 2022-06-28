<?php

// Global variable for table object
$classified_data = NULL;

//
// Table class for classified_data
//
class cclassified_data extends cTable {
	var $ID;
	var $title;
	var $car_type;
	var $car_make_company_id;
	var $car_model_id;
	var $price_range;
	var $milage_km_liter;
	var $transmition;
	var $fuel_type;
	var $engine_capicity;
	var $detail_text;
	var $status;
	var $ETD;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'classified_data';
		$this->TableName = 'classified_data';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`classified_data`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = TRUE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// ID
		$this->ID = new cField('classified_data', 'classified_data', 'x_ID', 'ID', '`ID`', '`ID`', 3, -1, FALSE, '`ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// title
		$this->title = new cField('classified_data', 'classified_data', 'x_title', 'title', '`title`', '`title`', 200, -1, FALSE, '`title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['title'] = &$this->title;

		// car_type
		$this->car_type = new cField('classified_data', 'classified_data', 'x_car_type', 'car_type', '`car_type`', '`car_type`', 202, -1, FALSE, '`car_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->car_type->OptionCount = 2;
		$this->fields['car_type'] = &$this->car_type;

		// car_make_company_id
		$this->car_make_company_id = new cField('classified_data', 'classified_data', 'x_car_make_company_id', 'car_make_company_id', '`car_make_company_id`', '`car_make_company_id`', 3, -1, FALSE, '`car_make_company_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->car_make_company_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['car_make_company_id'] = &$this->car_make_company_id;

		// car_model_id
		$this->car_model_id = new cField('classified_data', 'classified_data', 'x_car_model_id', 'car_model_id', '`car_model_id`', '`car_model_id`', 3, -1, FALSE, '`car_model_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->car_model_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['car_model_id'] = &$this->car_model_id;

		// price_range
		$this->price_range = new cField('classified_data', 'classified_data', 'x_price_range', 'price_range', '`price_range`', '`price_range`', 200, -1, FALSE, '`price_range`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['price_range'] = &$this->price_range;

		// milage_km_liter
		$this->milage_km_liter = new cField('classified_data', 'classified_data', 'x_milage_km_liter', 'milage_km_liter', '`milage_km_liter`', '`milage_km_liter`', 200, -1, FALSE, '`milage_km_liter`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['milage_km_liter'] = &$this->milage_km_liter;

		// transmition
		$this->transmition = new cField('classified_data', 'classified_data', 'x_transmition', 'transmition', '`transmition`', '`transmition`', 202, -1, FALSE, '`transmition`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->transmition->OptionCount = 2;
		$this->fields['transmition'] = &$this->transmition;

		// fuel_type
		$this->fuel_type = new cField('classified_data', 'classified_data', 'x_fuel_type', 'fuel_type', '`fuel_type`', '`fuel_type`', 202, -1, FALSE, '`fuel_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->fuel_type->OptionCount = 3;
		$this->fields['fuel_type'] = &$this->fuel_type;

		// engine_capicity
		$this->engine_capicity = new cField('classified_data', 'classified_data', 'x_engine_capicity', 'engine_capicity', '`engine_capicity`', '`engine_capicity`', 3, -1, FALSE, '`engine_capicity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->engine_capicity->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['engine_capicity'] = &$this->engine_capicity;

		// detail_text
		$this->detail_text = new cField('classified_data', 'classified_data', 'x_detail_text', 'detail_text', '`detail_text`', '`detail_text`', 201, -1, FALSE, '`detail_text`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['detail_text'] = &$this->detail_text;

		// status
		$this->status = new cField('classified_data', 'classified_data', 'x_status', 'status', '`status`', '`status`', 202, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->status->FldDataType = EW_DATATYPE_BOOLEAN;
		$this->status->OptionCount = 2;
		$this->fields['status'] = &$this->status;

		// ETD
		$this->ETD = new cField('classified_data', 'classified_data', 'x_ETD', 'ETD', '`ETD`', 'DATE_FORMAT(`ETD`, \'%Y/%m/%d\')', 135, 5, FALSE, '`ETD`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ETD->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['ETD'] = &$this->ETD;
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "classified_colors") {
			$sDetailUrl = $GLOBALS["classified_colors"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_ID=" . urlencode($this->ID->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "classified_attributes") {
			$sDetailUrl = $GLOBALS["classified_attributes"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_ID=" . urlencode($this->ID->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "classified_faqs") {
			$sDetailUrl = $GLOBALS["classified_faqs"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_ID=" . urlencode($this->ID->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "classified_pictures") {
			$sDetailUrl = $GLOBALS["classified_pictures"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_ID=" . urlencode($this->ID->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "classified_datalist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`classified_data`";
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
			return "classified_datalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "classified_datalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("classified_dataview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("classified_dataview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "classified_dataadd.php?" . $this->UrlParm($parm);
		else
			$url = "classified_dataadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("classified_dataedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("classified_dataedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("classified_dataadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("classified_dataadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("classified_datadelete.php", $this->UrlParm());
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
		$this->car_type->setDbValue($rs->fields('car_type'));
		$this->car_make_company_id->setDbValue($rs->fields('car_make_company_id'));
		$this->car_model_id->setDbValue($rs->fields('car_model_id'));
		$this->price_range->setDbValue($rs->fields('price_range'));
		$this->milage_km_liter->setDbValue($rs->fields('milage_km_liter'));
		$this->transmition->setDbValue($rs->fields('transmition'));
		$this->fuel_type->setDbValue($rs->fields('fuel_type'));
		$this->engine_capicity->setDbValue($rs->fields('engine_capicity'));
		$this->detail_text->setDbValue($rs->fields('detail_text'));
		$this->status->setDbValue($rs->fields('status'));
		$this->ETD->setDbValue($rs->fields('ETD'));
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
		// car_type
		// car_make_company_id
		// car_model_id
		// price_range
		// milage_km_liter
		// transmition
		// fuel_type
		// engine_capicity
		// detail_text
		// status
		// ETD

		$this->ETD->CellCssStyle = "white-space: nowrap;";

		// ID
		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// car_type
		if (strval($this->car_type->CurrentValue) <> "") {
			$this->car_type->ViewValue = $this->car_type->OptionCaption($this->car_type->CurrentValue);
		} else {
			$this->car_type->ViewValue = NULL;
		}
		$this->car_type->ViewCustomAttributes = "";

		// car_make_company_id
		if (strval($this->car_make_company_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->car_make_company_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->car_make_company_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->car_make_company_id->ViewValue = $this->car_make_company_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->car_make_company_id->ViewValue = $this->car_make_company_id->CurrentValue;
			}
		} else {
			$this->car_make_company_id->ViewValue = NULL;
		}
		$this->car_make_company_id->ViewCustomAttributes = "";

		// car_model_id
		if (strval($this->car_model_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->car_model_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_models`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->car_model_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->car_model_id->ViewValue = $this->car_model_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->car_model_id->ViewValue = $this->car_model_id->CurrentValue;
			}
		} else {
			$this->car_model_id->ViewValue = NULL;
		}
		$this->car_model_id->ViewCustomAttributes = "";

		// price_range
		$this->price_range->ViewValue = $this->price_range->CurrentValue;
		$this->price_range->ViewCustomAttributes = "";

		// milage_km_liter
		$this->milage_km_liter->ViewValue = $this->milage_km_liter->CurrentValue;
		$this->milage_km_liter->ViewCustomAttributes = "";

		// transmition
		if (strval($this->transmition->CurrentValue) <> "") {
			$this->transmition->ViewValue = $this->transmition->OptionCaption($this->transmition->CurrentValue);
		} else {
			$this->transmition->ViewValue = NULL;
		}
		$this->transmition->ViewCustomAttributes = "";

		// fuel_type
		if (strval($this->fuel_type->CurrentValue) <> "") {
			$this->fuel_type->ViewValue = $this->fuel_type->OptionCaption($this->fuel_type->CurrentValue);
		} else {
			$this->fuel_type->ViewValue = NULL;
		}
		$this->fuel_type->ViewCustomAttributes = "";

		// engine_capicity
		$this->engine_capicity->ViewValue = $this->engine_capicity->CurrentValue;
		$this->engine_capicity->ViewCustomAttributes = "";

		// detail_text
		$this->detail_text->ViewValue = $this->detail_text->CurrentValue;
		$this->detail_text->ViewCustomAttributes = "";

		// status
		if (ew_ConvertToBool($this->status->CurrentValue)) {
			$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : "Active";
		} else {
			$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : "Inactive";
		}
		$this->status->ViewCustomAttributes = "";

		// ETD
		$this->ETD->ViewValue = $this->ETD->CurrentValue;
		$this->ETD->ViewValue = ew_FormatDateTime($this->ETD->ViewValue, 5);
		$this->ETD->ViewCustomAttributes = "";

		// ID
		$this->ID->LinkCustomAttributes = "";
		$this->ID->HrefValue = "";
		$this->ID->TooltipValue = "";

		// title
		$this->title->LinkCustomAttributes = "";
		$this->title->HrefValue = "";
		$this->title->TooltipValue = "";

		// car_type
		$this->car_type->LinkCustomAttributes = "";
		$this->car_type->HrefValue = "";
		$this->car_type->TooltipValue = "";

		// car_make_company_id
		$this->car_make_company_id->LinkCustomAttributes = "";
		$this->car_make_company_id->HrefValue = "";
		$this->car_make_company_id->TooltipValue = "";

		// car_model_id
		$this->car_model_id->LinkCustomAttributes = "";
		$this->car_model_id->HrefValue = "";
		$this->car_model_id->TooltipValue = "";

		// price_range
		$this->price_range->LinkCustomAttributes = "";
		$this->price_range->HrefValue = "";
		$this->price_range->TooltipValue = "";

		// milage_km_liter
		$this->milage_km_liter->LinkCustomAttributes = "";
		$this->milage_km_liter->HrefValue = "";
		$this->milage_km_liter->TooltipValue = "";

		// transmition
		$this->transmition->LinkCustomAttributes = "";
		$this->transmition->HrefValue = "";
		$this->transmition->TooltipValue = "";

		// fuel_type
		$this->fuel_type->LinkCustomAttributes = "";
		$this->fuel_type->HrefValue = "";
		$this->fuel_type->TooltipValue = "";

		// engine_capicity
		$this->engine_capicity->LinkCustomAttributes = "";
		$this->engine_capicity->HrefValue = "";
		$this->engine_capicity->TooltipValue = "";

		// detail_text
		$this->detail_text->LinkCustomAttributes = "";
		if (!ew_Empty($this->detail_text->CurrentValue)) {
			$this->detail_text->HrefValue = ((!empty($this->detail_text->ViewValue)) ? ew_RemoveHtml($this->detail_text->ViewValue) : $this->detail_text->CurrentValue); // Add prefix/suffix
			$this->detail_text->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->detail_text->HrefValue = ew_ConvertFullUrl($this->detail_text->HrefValue);
		} else {
			$this->detail_text->HrefValue = "";
		}
		$this->detail_text->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// ETD
		$this->ETD->LinkCustomAttributes = "";
		$this->ETD->HrefValue = "";
		$this->ETD->TooltipValue = "";

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

		// car_type
		$this->car_type->EditCustomAttributes = "";
		$this->car_type->EditValue = $this->car_type->Options(FALSE);

		// car_make_company_id
		$this->car_make_company_id->EditAttrs["class"] = "form-control";
		$this->car_make_company_id->EditCustomAttributes = "";

		// car_model_id
		$this->car_model_id->EditAttrs["class"] = "form-control";
		$this->car_model_id->EditCustomAttributes = "";

		// price_range
		$this->price_range->EditAttrs["class"] = "form-control";
		$this->price_range->EditCustomAttributes = "";
		$this->price_range->EditValue = $this->price_range->CurrentValue;
		$this->price_range->PlaceHolder = ew_RemoveHtml($this->price_range->FldCaption());

		// milage_km_liter
		$this->milage_km_liter->EditAttrs["class"] = "form-control";
		$this->milage_km_liter->EditCustomAttributes = "";
		$this->milage_km_liter->EditValue = $this->milage_km_liter->CurrentValue;
		$this->milage_km_liter->PlaceHolder = ew_RemoveHtml($this->milage_km_liter->FldCaption());

		// transmition
		$this->transmition->EditCustomAttributes = "";
		$this->transmition->EditValue = $this->transmition->Options(FALSE);

		// fuel_type
		$this->fuel_type->EditCustomAttributes = "";
		$this->fuel_type->EditValue = $this->fuel_type->Options(FALSE);

		// engine_capicity
		$this->engine_capicity->EditAttrs["class"] = "form-control";
		$this->engine_capicity->EditCustomAttributes = "";
		$this->engine_capicity->EditValue = $this->engine_capicity->CurrentValue;
		$this->engine_capicity->PlaceHolder = ew_RemoveHtml($this->engine_capicity->FldCaption());

		// detail_text
		$this->detail_text->EditAttrs["class"] = "form-control";
		$this->detail_text->EditCustomAttributes = "";
		$this->detail_text->EditValue = $this->detail_text->CurrentValue;
		$this->detail_text->PlaceHolder = ew_RemoveHtml($this->detail_text->FldCaption());

		// status
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->Options(FALSE);

		// ETD
		$this->ETD->EditAttrs["class"] = "form-control";
		$this->ETD->EditCustomAttributes = "";
		$this->ETD->EditValue = ew_FormatDateTime($this->ETD->CurrentValue, 5);
		$this->ETD->PlaceHolder = ew_RemoveHtml($this->ETD->FldCaption());

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
					if ($this->car_type->Exportable) $Doc->ExportCaption($this->car_type);
					if ($this->car_make_company_id->Exportable) $Doc->ExportCaption($this->car_make_company_id);
					if ($this->car_model_id->Exportable) $Doc->ExportCaption($this->car_model_id);
					if ($this->price_range->Exportable) $Doc->ExportCaption($this->price_range);
					if ($this->milage_km_liter->Exportable) $Doc->ExportCaption($this->milage_km_liter);
					if ($this->transmition->Exportable) $Doc->ExportCaption($this->transmition);
					if ($this->fuel_type->Exportable) $Doc->ExportCaption($this->fuel_type);
					if ($this->engine_capicity->Exportable) $Doc->ExportCaption($this->engine_capicity);
					if ($this->detail_text->Exportable) $Doc->ExportCaption($this->detail_text);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				} else {
					if ($this->title->Exportable) $Doc->ExportCaption($this->title);
					if ($this->car_type->Exportable) $Doc->ExportCaption($this->car_type);
					if ($this->car_make_company_id->Exportable) $Doc->ExportCaption($this->car_make_company_id);
					if ($this->car_model_id->Exportable) $Doc->ExportCaption($this->car_model_id);
					if ($this->price_range->Exportable) $Doc->ExportCaption($this->price_range);
					if ($this->milage_km_liter->Exportable) $Doc->ExportCaption($this->milage_km_liter);
					if ($this->transmition->Exportable) $Doc->ExportCaption($this->transmition);
					if ($this->fuel_type->Exportable) $Doc->ExportCaption($this->fuel_type);
					if ($this->engine_capicity->Exportable) $Doc->ExportCaption($this->engine_capicity);
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
						if ($this->car_type->Exportable) $Doc->ExportField($this->car_type);
						if ($this->car_make_company_id->Exportable) $Doc->ExportField($this->car_make_company_id);
						if ($this->car_model_id->Exportable) $Doc->ExportField($this->car_model_id);
						if ($this->price_range->Exportable) $Doc->ExportField($this->price_range);
						if ($this->milage_km_liter->Exportable) $Doc->ExportField($this->milage_km_liter);
						if ($this->transmition->Exportable) $Doc->ExportField($this->transmition);
						if ($this->fuel_type->Exportable) $Doc->ExportField($this->fuel_type);
						if ($this->engine_capicity->Exportable) $Doc->ExportField($this->engine_capicity);
						if ($this->detail_text->Exportable) $Doc->ExportField($this->detail_text);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
					} else {
						if ($this->title->Exportable) $Doc->ExportField($this->title);
						if ($this->car_type->Exportable) $Doc->ExportField($this->car_type);
						if ($this->car_make_company_id->Exportable) $Doc->ExportField($this->car_make_company_id);
						if ($this->car_model_id->Exportable) $Doc->ExportField($this->car_model_id);
						if ($this->price_range->Exportable) $Doc->ExportField($this->price_range);
						if ($this->milage_km_liter->Exportable) $Doc->ExportField($this->milage_km_liter);
						if ($this->transmition->Exportable) $Doc->ExportField($this->transmition);
						if ($this->fuel_type->Exportable) $Doc->ExportField($this->fuel_type);
						if ($this->engine_capicity->Exportable) $Doc->ExportField($this->engine_capicity);
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
