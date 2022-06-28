<?php

// Global variable for table object
$vw_pending_ads_list = NULL;

//
// Table class for vw_pending_ads_list
//
class cvw_pending_ads_list extends cTable {
	var $ID;
	var $user_id;
	var $ad_title;
	var $year_id;
	var $registered_in;
	var $city_id;
	var $make_id;
	var $model_id;
	var $version_id;
	var $milage;
	var $color_id;
	var $demand_price;
	var $details;
	var $engine_type_id;
	var $engine_capicity;
	var $transmition;
	var $assembly;
	var $mobile_number;
	var $secondary_number;
	var $_email;
	var $name;
	var $address;
	var $allow_whatsapp;
	var $status;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vw_pending_ads_list';
		$this->TableName = 'vw_pending_ads_list';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`vw_pending_ads_list`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// ID
		$this->ID = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_ID', 'ID', '`ID`', '`ID`', 3, -1, FALSE, '`ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// user_id
		$this->user_id = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_user_id', 'user_id', '`user_id`', '`user_id`', 3, -1, FALSE, '`user_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->user_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['user_id'] = &$this->user_id;

		// ad_title
		$this->ad_title = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_ad_title', 'ad_title', '`ad_title`', '`ad_title`', 200, -1, FALSE, '`ad_title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['ad_title'] = &$this->ad_title;

		// year_id
		$this->year_id = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_year_id', 'year_id', '`year_id`', '`year_id`', 3, -1, FALSE, '`year_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->year_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['year_id'] = &$this->year_id;

		// registered_in
		$this->registered_in = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_registered_in', 'registered_in', '`registered_in`', '`registered_in`', 3, -1, FALSE, '`registered_in`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['registered_in'] = &$this->registered_in;

		// city_id
		$this->city_id = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_city_id', 'city_id', '`city_id`', '`city_id`', 3, -1, FALSE, '`city_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->city_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['city_id'] = &$this->city_id;

		// make_id
		$this->make_id = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_make_id', 'make_id', '`make_id`', '`make_id`', 3, -1, FALSE, '`make_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->make_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['make_id'] = &$this->make_id;

		// model_id
		$this->model_id = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_model_id', 'model_id', '`model_id`', '`model_id`', 3, -1, FALSE, '`model_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->model_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['model_id'] = &$this->model_id;

		// version_id
		$this->version_id = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_version_id', 'version_id', '`version_id`', '`version_id`', 3, -1, FALSE, '`version_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->version_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['version_id'] = &$this->version_id;

		// milage
		$this->milage = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_milage', 'milage', '`milage`', '`milage`', 3, -1, FALSE, '`milage`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->milage->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['milage'] = &$this->milage;

		// color_id
		$this->color_id = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_color_id', 'color_id', '`color_id`', '`color_id`', 3, -1, FALSE, '`color_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->color_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['color_id'] = &$this->color_id;

		// demand_price
		$this->demand_price = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_demand_price', 'demand_price', '`demand_price`', '`demand_price`', 3, -1, FALSE, '`demand_price`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->demand_price->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['demand_price'] = &$this->demand_price;

		// details
		$this->details = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_details', 'details', '`details`', '`details`', 201, -1, FALSE, '`details`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['details'] = &$this->details;

		// engine_type_id
		$this->engine_type_id = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_engine_type_id', 'engine_type_id', '`engine_type_id`', '`engine_type_id`', 3, -1, FALSE, '`engine_type_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->engine_type_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['engine_type_id'] = &$this->engine_type_id;

		// engine_capicity
		$this->engine_capicity = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_engine_capicity', 'engine_capicity', '`engine_capicity`', '`engine_capicity`', 3, -1, FALSE, '`engine_capicity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->engine_capicity->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['engine_capicity'] = &$this->engine_capicity;

		// transmition
		$this->transmition = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_transmition', 'transmition', '`transmition`', '`transmition`', 202, -1, FALSE, '`transmition`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->transmition->OptionCount = 2;
		$this->fields['transmition'] = &$this->transmition;

		// assembly
		$this->assembly = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_assembly', 'assembly', '`assembly`', '`assembly`', 202, -1, FALSE, '`assembly`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->assembly->OptionCount = 2;
		$this->fields['assembly'] = &$this->assembly;

		// mobile_number
		$this->mobile_number = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_mobile_number', 'mobile_number', '`mobile_number`', '`mobile_number`', 200, -1, FALSE, '`mobile_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['mobile_number'] = &$this->mobile_number;

		// secondary_number
		$this->secondary_number = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_secondary_number', 'secondary_number', '`secondary_number`', '`secondary_number`', 200, -1, FALSE, '`secondary_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['secondary_number'] = &$this->secondary_number;

		// email
		$this->_email = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['email'] = &$this->_email;

		// name
		$this->name = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_name', 'name', '`name`', '`name`', 200, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['name'] = &$this->name;

		// address
		$this->address = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_address', 'address', '`address`', '`address`', 200, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['address'] = &$this->address;

		// allow_whatsapp
		$this->allow_whatsapp = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_allow_whatsapp', 'allow_whatsapp', '`allow_whatsapp`', '`allow_whatsapp`', 202, -1, FALSE, '`allow_whatsapp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->allow_whatsapp->FldDataType = EW_DATATYPE_BOOLEAN;
		$this->allow_whatsapp->OptionCount = 2;
		$this->fields['allow_whatsapp'] = &$this->allow_whatsapp;

		// status
		$this->status = new cField('vw_pending_ads_list', 'vw_pending_ads_list', 'x_status', 'status', '`status`', '`status`', 202, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->status->OptionCount = 5;
		$this->fields['status'] = &$this->status;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`vw_pending_ads_list`";
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
			return "vw_pending_ads_listlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vw_pending_ads_listlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("vw_pending_ads_listview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("vw_pending_ads_listview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "vw_pending_ads_listadd.php?" . $this->UrlParm($parm);
		else
			$url = "vw_pending_ads_listadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("vw_pending_ads_listedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("vw_pending_ads_listadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vw_pending_ads_listdelete.php", $this->UrlParm());
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
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->ad_title->setDbValue($rs->fields('ad_title'));
		$this->year_id->setDbValue($rs->fields('year_id'));
		$this->registered_in->setDbValue($rs->fields('registered_in'));
		$this->city_id->setDbValue($rs->fields('city_id'));
		$this->make_id->setDbValue($rs->fields('make_id'));
		$this->model_id->setDbValue($rs->fields('model_id'));
		$this->version_id->setDbValue($rs->fields('version_id'));
		$this->milage->setDbValue($rs->fields('milage'));
		$this->color_id->setDbValue($rs->fields('color_id'));
		$this->demand_price->setDbValue($rs->fields('demand_price'));
		$this->details->setDbValue($rs->fields('details'));
		$this->engine_type_id->setDbValue($rs->fields('engine_type_id'));
		$this->engine_capicity->setDbValue($rs->fields('engine_capicity'));
		$this->transmition->setDbValue($rs->fields('transmition'));
		$this->assembly->setDbValue($rs->fields('assembly'));
		$this->mobile_number->setDbValue($rs->fields('mobile_number'));
		$this->secondary_number->setDbValue($rs->fields('secondary_number'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->name->setDbValue($rs->fields('name'));
		$this->address->setDbValue($rs->fields('address'));
		$this->allow_whatsapp->setDbValue($rs->fields('allow_whatsapp'));
		$this->status->setDbValue($rs->fields('status'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ID

		$this->ID->CellCssStyle = "white-space: nowrap;";

		// user_id
		// ad_title
		// year_id
		// registered_in
		// city_id
		// make_id
		// model_id
		// version_id
		// milage
		// color_id
		// demand_price
		// details
		// engine_type_id
		// engine_capicity
		// transmition
		// assembly
		// mobile_number
		// secondary_number
		// email
		// name
		// address
		// allow_whatsapp
		// status
		// ID

		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// user_id
		$this->user_id->ViewValue = $this->user_id->CurrentValue;
		if (strval($this->user_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->user_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, `email` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->user_id->ViewValue = $this->user_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->user_id->ViewValue = $this->user_id->CurrentValue;
			}
		} else {
			$this->user_id->ViewValue = NULL;
		}
		$this->user_id->ViewCustomAttributes = "";

		// ad_title
		$this->ad_title->ViewValue = $this->ad_title->CurrentValue;
		$this->ad_title->ViewCustomAttributes = "";

		// year_id
		if (strval($this->year_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->year_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `year` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_years`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->year_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->year_id->ViewValue = $this->year_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->year_id->ViewValue = $this->year_id->CurrentValue;
			}
		} else {
			$this->year_id->ViewValue = NULL;
		}
		$this->year_id->ViewCustomAttributes = "";

		// registered_in
		if (strval($this->registered_in->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->registered_in->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `city_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_cities`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->registered_in, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->registered_in->ViewValue = $this->registered_in->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->registered_in->ViewValue = $this->registered_in->CurrentValue;
			}
		} else {
			$this->registered_in->ViewValue = NULL;
		}
		$this->registered_in->ViewCustomAttributes = "";

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

		// make_id
		if (strval($this->make_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->make_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_make_companies`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->make_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->make_id->ViewValue = $this->make_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->make_id->ViewValue = $this->make_id->CurrentValue;
			}
		} else {
			$this->make_id->ViewValue = NULL;
		}
		$this->make_id->ViewCustomAttributes = "";

		// model_id
		if (strval($this->model_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->model_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_models`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->model_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->model_id->ViewValue = $this->model_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->model_id->ViewValue = $this->model_id->CurrentValue;
			}
		} else {
			$this->model_id->ViewValue = NULL;
		}
		$this->model_id->ViewCustomAttributes = "";

		// version_id
		if (strval($this->version_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->version_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_car_versions`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->version_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->version_id->ViewValue = $this->version_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->version_id->ViewValue = $this->version_id->CurrentValue;
			}
		} else {
			$this->version_id->ViewValue = NULL;
		}
		$this->version_id->ViewCustomAttributes = "";

		// milage
		$this->milage->ViewValue = $this->milage->CurrentValue;
		$this->milage->ViewCustomAttributes = "";

		// color_id
		if (strval($this->color_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->color_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_body_colors`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->color_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->color_id->ViewValue = $this->color_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->color_id->ViewValue = $this->color_id->CurrentValue;
			}
		} else {
			$this->color_id->ViewValue = NULL;
		}
		$this->color_id->ViewCustomAttributes = "";

		// demand_price
		$this->demand_price->ViewValue = $this->demand_price->CurrentValue;
		$this->demand_price->ViewCustomAttributes = "";

		// details
		$this->details->ViewValue = $this->details->CurrentValue;
		$this->details->ViewCustomAttributes = "";

		// engine_type_id
		if (strval($this->engine_type_id->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->engine_type_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `ID`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cfg_engine_types`";
		$sWhereWrk = "";
		$lookuptblfilter = "`status`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->engine_type_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->engine_type_id->ViewValue = $this->engine_type_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->engine_type_id->ViewValue = $this->engine_type_id->CurrentValue;
			}
		} else {
			$this->engine_type_id->ViewValue = NULL;
		}
		$this->engine_type_id->ViewCustomAttributes = "";

		// engine_capicity
		$this->engine_capicity->ViewValue = $this->engine_capicity->CurrentValue;
		$this->engine_capicity->ViewCustomAttributes = "";

		// transmition
		if (strval($this->transmition->CurrentValue) <> "") {
			$this->transmition->ViewValue = $this->transmition->OptionCaption($this->transmition->CurrentValue);
		} else {
			$this->transmition->ViewValue = NULL;
		}
		$this->transmition->ViewCustomAttributes = "";

		// assembly
		if (strval($this->assembly->CurrentValue) <> "") {
			$this->assembly->ViewValue = $this->assembly->OptionCaption($this->assembly->CurrentValue);
		} else {
			$this->assembly->ViewValue = NULL;
		}
		$this->assembly->ViewCustomAttributes = "";

		// mobile_number
		$this->mobile_number->ViewValue = $this->mobile_number->CurrentValue;
		$this->mobile_number->ViewCustomAttributes = "";

		// secondary_number
		$this->secondary_number->ViewValue = $this->secondary_number->CurrentValue;
		$this->secondary_number->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// allow_whatsapp
		if (ew_ConvertToBool($this->allow_whatsapp->CurrentValue)) {
			$this->allow_whatsapp->ViewValue = $this->allow_whatsapp->FldTagCaption(1) <> "" ? $this->allow_whatsapp->FldTagCaption(1) : "Yes";
		} else {
			$this->allow_whatsapp->ViewValue = $this->allow_whatsapp->FldTagCaption(2) <> "" ? $this->allow_whatsapp->FldTagCaption(2) : "No";
		}
		$this->allow_whatsapp->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$this->status->ViewValue = $this->status->OptionCaption($this->status->CurrentValue);
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

		// ID
		$this->ID->LinkCustomAttributes = "";
		$this->ID->HrefValue = "";
		$this->ID->TooltipValue = "";

		// user_id
		$this->user_id->LinkCustomAttributes = "";
		$this->user_id->HrefValue = "";
		$this->user_id->TooltipValue = "";

		// ad_title
		$this->ad_title->LinkCustomAttributes = "";
		$this->ad_title->HrefValue = "";
		$this->ad_title->TooltipValue = "";

		// year_id
		$this->year_id->LinkCustomAttributes = "";
		$this->year_id->HrefValue = "";
		$this->year_id->TooltipValue = "";

		// registered_in
		$this->registered_in->LinkCustomAttributes = "";
		$this->registered_in->HrefValue = "";
		$this->registered_in->TooltipValue = "";

		// city_id
		$this->city_id->LinkCustomAttributes = "";
		$this->city_id->HrefValue = "";
		$this->city_id->TooltipValue = "";

		// make_id
		$this->make_id->LinkCustomAttributes = "";
		$this->make_id->HrefValue = "";
		$this->make_id->TooltipValue = "";

		// model_id
		$this->model_id->LinkCustomAttributes = "";
		$this->model_id->HrefValue = "";
		$this->model_id->TooltipValue = "";

		// version_id
		$this->version_id->LinkCustomAttributes = "";
		$this->version_id->HrefValue = "";
		$this->version_id->TooltipValue = "";

		// milage
		$this->milage->LinkCustomAttributes = "";
		$this->milage->HrefValue = "";
		$this->milage->TooltipValue = "";

		// color_id
		$this->color_id->LinkCustomAttributes = "";
		$this->color_id->HrefValue = "";
		$this->color_id->TooltipValue = "";

		// demand_price
		$this->demand_price->LinkCustomAttributes = "";
		$this->demand_price->HrefValue = "";
		$this->demand_price->TooltipValue = "";

		// details
		$this->details->LinkCustomAttributes = "";
		$this->details->HrefValue = "";
		$this->details->TooltipValue = "";

		// engine_type_id
		$this->engine_type_id->LinkCustomAttributes = "";
		$this->engine_type_id->HrefValue = "";
		$this->engine_type_id->TooltipValue = "";

		// engine_capicity
		$this->engine_capicity->LinkCustomAttributes = "";
		$this->engine_capicity->HrefValue = "";
		$this->engine_capicity->TooltipValue = "";

		// transmition
		$this->transmition->LinkCustomAttributes = "";
		$this->transmition->HrefValue = "";
		$this->transmition->TooltipValue = "";

		// assembly
		$this->assembly->LinkCustomAttributes = "";
		$this->assembly->HrefValue = "";
		$this->assembly->TooltipValue = "";

		// mobile_number
		$this->mobile_number->LinkCustomAttributes = "";
		$this->mobile_number->HrefValue = "";
		$this->mobile_number->TooltipValue = "";

		// secondary_number
		$this->secondary_number->LinkCustomAttributes = "";
		$this->secondary_number->HrefValue = "";
		$this->secondary_number->TooltipValue = "";

		// email
		$this->_email->LinkCustomAttributes = "";
		$this->_email->HrefValue = "";
		$this->_email->TooltipValue = "";

		// name
		$this->name->LinkCustomAttributes = "";
		$this->name->HrefValue = "";
		$this->name->TooltipValue = "";

		// address
		$this->address->LinkCustomAttributes = "";
		$this->address->HrefValue = "";
		$this->address->TooltipValue = "";

		// allow_whatsapp
		$this->allow_whatsapp->LinkCustomAttributes = "";
		$this->allow_whatsapp->HrefValue = "";
		$this->allow_whatsapp->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

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

		// user_id
		$this->user_id->EditAttrs["class"] = "form-control";
		$this->user_id->EditCustomAttributes = "";
		$this->user_id->EditValue = $this->user_id->CurrentValue;
		$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

		// ad_title
		$this->ad_title->EditAttrs["class"] = "form-control";
		$this->ad_title->EditCustomAttributes = "";
		$this->ad_title->EditValue = $this->ad_title->CurrentValue;
		$this->ad_title->PlaceHolder = ew_RemoveHtml($this->ad_title->FldCaption());

		// year_id
		$this->year_id->EditCustomAttributes = "";

		// registered_in
		$this->registered_in->EditAttrs["class"] = "form-control";
		$this->registered_in->EditCustomAttributes = "";

		// city_id
		$this->city_id->EditAttrs["class"] = "form-control";
		$this->city_id->EditCustomAttributes = "";

		// make_id
		$this->make_id->EditAttrs["class"] = "form-control";
		$this->make_id->EditCustomAttributes = "";

		// model_id
		$this->model_id->EditAttrs["class"] = "form-control";
		$this->model_id->EditCustomAttributes = "";

		// version_id
		$this->version_id->EditAttrs["class"] = "form-control";
		$this->version_id->EditCustomAttributes = "";

		// milage
		$this->milage->EditAttrs["class"] = "form-control";
		$this->milage->EditCustomAttributes = "";
		$this->milage->EditValue = $this->milage->CurrentValue;
		$this->milage->PlaceHolder = ew_RemoveHtml($this->milage->FldCaption());

		// color_id
		$this->color_id->EditAttrs["class"] = "form-control";
		$this->color_id->EditCustomAttributes = "";

		// demand_price
		$this->demand_price->EditAttrs["class"] = "form-control";
		$this->demand_price->EditCustomAttributes = "";
		$this->demand_price->EditValue = $this->demand_price->CurrentValue;
		$this->demand_price->PlaceHolder = ew_RemoveHtml($this->demand_price->FldCaption());

		// details
		$this->details->EditAttrs["class"] = "form-control";
		$this->details->EditCustomAttributes = "";
		$this->details->EditValue = $this->details->CurrentValue;
		$this->details->PlaceHolder = ew_RemoveHtml($this->details->FldCaption());

		// engine_type_id
		$this->engine_type_id->EditAttrs["class"] = "form-control";
		$this->engine_type_id->EditCustomAttributes = "";

		// engine_capicity
		$this->engine_capicity->EditAttrs["class"] = "form-control";
		$this->engine_capicity->EditCustomAttributes = "";
		$this->engine_capicity->EditValue = $this->engine_capicity->CurrentValue;
		$this->engine_capicity->PlaceHolder = ew_RemoveHtml($this->engine_capicity->FldCaption());

		// transmition
		$this->transmition->EditCustomAttributes = "";
		$this->transmition->EditValue = $this->transmition->Options(FALSE);

		// assembly
		$this->assembly->EditCustomAttributes = "";
		$this->assembly->EditValue = $this->assembly->Options(FALSE);

		// mobile_number
		$this->mobile_number->EditAttrs["class"] = "form-control";
		$this->mobile_number->EditCustomAttributes = "";
		$this->mobile_number->EditValue = $this->mobile_number->CurrentValue;
		$this->mobile_number->PlaceHolder = ew_RemoveHtml($this->mobile_number->FldCaption());

		// secondary_number
		$this->secondary_number->EditAttrs["class"] = "form-control";
		$this->secondary_number->EditCustomAttributes = "";
		$this->secondary_number->EditValue = $this->secondary_number->CurrentValue;
		$this->secondary_number->PlaceHolder = ew_RemoveHtml($this->secondary_number->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// name
		$this->name->EditAttrs["class"] = "form-control";
		$this->name->EditCustomAttributes = "";
		$this->name->EditValue = $this->name->CurrentValue;
		$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

		// address
		$this->address->EditAttrs["class"] = "form-control";
		$this->address->EditCustomAttributes = "";
		$this->address->EditValue = $this->address->CurrentValue;
		$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

		// allow_whatsapp
		$this->allow_whatsapp->EditCustomAttributes = "";
		$this->allow_whatsapp->EditValue = $this->allow_whatsapp->Options(FALSE);

		// status
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->Options(FALSE);

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
					if ($this->user_id->Exportable) $Doc->ExportCaption($this->user_id);
					if ($this->ad_title->Exportable) $Doc->ExportCaption($this->ad_title);
					if ($this->year_id->Exportable) $Doc->ExportCaption($this->year_id);
					if ($this->registered_in->Exportable) $Doc->ExportCaption($this->registered_in);
					if ($this->city_id->Exportable) $Doc->ExportCaption($this->city_id);
					if ($this->make_id->Exportable) $Doc->ExportCaption($this->make_id);
					if ($this->model_id->Exportable) $Doc->ExportCaption($this->model_id);
					if ($this->version_id->Exportable) $Doc->ExportCaption($this->version_id);
					if ($this->milage->Exportable) $Doc->ExportCaption($this->milage);
					if ($this->color_id->Exportable) $Doc->ExportCaption($this->color_id);
					if ($this->demand_price->Exportable) $Doc->ExportCaption($this->demand_price);
					if ($this->details->Exportable) $Doc->ExportCaption($this->details);
					if ($this->engine_type_id->Exportable) $Doc->ExportCaption($this->engine_type_id);
					if ($this->engine_capicity->Exportable) $Doc->ExportCaption($this->engine_capicity);
					if ($this->transmition->Exportable) $Doc->ExportCaption($this->transmition);
					if ($this->assembly->Exportable) $Doc->ExportCaption($this->assembly);
					if ($this->mobile_number->Exportable) $Doc->ExportCaption($this->mobile_number);
					if ($this->secondary_number->Exportable) $Doc->ExportCaption($this->secondary_number);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->allow_whatsapp->Exportable) $Doc->ExportCaption($this->allow_whatsapp);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				} else {
					if ($this->user_id->Exportable) $Doc->ExportCaption($this->user_id);
					if ($this->year_id->Exportable) $Doc->ExportCaption($this->year_id);
					if ($this->registered_in->Exportable) $Doc->ExportCaption($this->registered_in);
					if ($this->city_id->Exportable) $Doc->ExportCaption($this->city_id);
					if ($this->make_id->Exportable) $Doc->ExportCaption($this->make_id);
					if ($this->model_id->Exportable) $Doc->ExportCaption($this->model_id);
					if ($this->version_id->Exportable) $Doc->ExportCaption($this->version_id);
					if ($this->milage->Exportable) $Doc->ExportCaption($this->milage);
					if ($this->color_id->Exportable) $Doc->ExportCaption($this->color_id);
					if ($this->demand_price->Exportable) $Doc->ExportCaption($this->demand_price);
					if ($this->engine_type_id->Exportable) $Doc->ExportCaption($this->engine_type_id);
					if ($this->engine_capicity->Exportable) $Doc->ExportCaption($this->engine_capicity);
					if ($this->transmition->Exportable) $Doc->ExportCaption($this->transmition);
					if ($this->assembly->Exportable) $Doc->ExportCaption($this->assembly);
					if ($this->mobile_number->Exportable) $Doc->ExportCaption($this->mobile_number);
					if ($this->secondary_number->Exportable) $Doc->ExportCaption($this->secondary_number);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->allow_whatsapp->Exportable) $Doc->ExportCaption($this->allow_whatsapp);
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
						if ($this->user_id->Exportable) $Doc->ExportField($this->user_id);
						if ($this->ad_title->Exportable) $Doc->ExportField($this->ad_title);
						if ($this->year_id->Exportable) $Doc->ExportField($this->year_id);
						if ($this->registered_in->Exportable) $Doc->ExportField($this->registered_in);
						if ($this->city_id->Exportable) $Doc->ExportField($this->city_id);
						if ($this->make_id->Exportable) $Doc->ExportField($this->make_id);
						if ($this->model_id->Exportable) $Doc->ExportField($this->model_id);
						if ($this->version_id->Exportable) $Doc->ExportField($this->version_id);
						if ($this->milage->Exportable) $Doc->ExportField($this->milage);
						if ($this->color_id->Exportable) $Doc->ExportField($this->color_id);
						if ($this->demand_price->Exportable) $Doc->ExportField($this->demand_price);
						if ($this->details->Exportable) $Doc->ExportField($this->details);
						if ($this->engine_type_id->Exportable) $Doc->ExportField($this->engine_type_id);
						if ($this->engine_capicity->Exportable) $Doc->ExportField($this->engine_capicity);
						if ($this->transmition->Exportable) $Doc->ExportField($this->transmition);
						if ($this->assembly->Exportable) $Doc->ExportField($this->assembly);
						if ($this->mobile_number->Exportable) $Doc->ExportField($this->mobile_number);
						if ($this->secondary_number->Exportable) $Doc->ExportField($this->secondary_number);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->allow_whatsapp->Exportable) $Doc->ExportField($this->allow_whatsapp);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
					} else {
						if ($this->user_id->Exportable) $Doc->ExportField($this->user_id);
						if ($this->year_id->Exportable) $Doc->ExportField($this->year_id);
						if ($this->registered_in->Exportable) $Doc->ExportField($this->registered_in);
						if ($this->city_id->Exportable) $Doc->ExportField($this->city_id);
						if ($this->make_id->Exportable) $Doc->ExportField($this->make_id);
						if ($this->model_id->Exportable) $Doc->ExportField($this->model_id);
						if ($this->version_id->Exportable) $Doc->ExportField($this->version_id);
						if ($this->milage->Exportable) $Doc->ExportField($this->milage);
						if ($this->color_id->Exportable) $Doc->ExportField($this->color_id);
						if ($this->demand_price->Exportable) $Doc->ExportField($this->demand_price);
						if ($this->engine_type_id->Exportable) $Doc->ExportField($this->engine_type_id);
						if ($this->engine_capicity->Exportable) $Doc->ExportField($this->engine_capicity);
						if ($this->transmition->Exportable) $Doc->ExportField($this->transmition);
						if ($this->assembly->Exportable) $Doc->ExportField($this->assembly);
						if ($this->mobile_number->Exportable) $Doc->ExportField($this->mobile_number);
						if ($this->secondary_number->Exportable) $Doc->ExportField($this->secondary_number);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->allow_whatsapp->Exportable) $Doc->ExportField($this->allow_whatsapp);
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
