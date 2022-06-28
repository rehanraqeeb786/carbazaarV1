<?php

// Global variable for table object
$cfg_business_directory = NULL;

//
// Table class for cfg_business_directory
//
class ccfg_business_directory extends cTable {
	var $ID;
	var $business_title;
	var $cat_id;
	var $province_id;
	var $city_id;
	var $business_address;
	var $business_logo_link;
	var $image_2;
	var $img_3;
	var $detail_desc;
	var $longitute;
	var $latitude;
	var $primary_number;
	var $secondary_number;
	var $fb_page;
	var $timings;
	var $website;
	var $status;
	var $ETD;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'cfg_business_directory';
		$this->TableName = 'cfg_business_directory';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`cfg_business_directory`";
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
		$this->ID = new cField('cfg_business_directory', 'cfg_business_directory', 'x_ID', 'ID', '`ID`', '`ID`', 3, -1, FALSE, '`ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// business_title
		$this->business_title = new cField('cfg_business_directory', 'cfg_business_directory', 'x_business_title', 'business_title', '`business_title`', '`business_title`', 200, -1, FALSE, '`business_title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['business_title'] = &$this->business_title;

		// cat_id
		$this->cat_id = new cField('cfg_business_directory', 'cfg_business_directory', 'x_cat_id', 'cat_id', '`cat_id`', '`cat_id`', 3, -1, FALSE, '`cat_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->cat_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cat_id'] = &$this->cat_id;

		// province_id
		$this->province_id = new cField('cfg_business_directory', 'cfg_business_directory', 'x_province_id', 'province_id', '`province_id`', '`province_id`', 3, -1, FALSE, '`province_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->province_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['province_id'] = &$this->province_id;

		// city_id
		$this->city_id = new cField('cfg_business_directory', 'cfg_business_directory', 'x_city_id', 'city_id', '`city_id`', '`city_id`', 3, -1, FALSE, '`city_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->city_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['city_id'] = &$this->city_id;

		// business_address
		$this->business_address = new cField('cfg_business_directory', 'cfg_business_directory', 'x_business_address', 'business_address', '`business_address`', '`business_address`', 200, -1, FALSE, '`business_address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['business_address'] = &$this->business_address;

		// business_logo_link
		$this->business_logo_link = new cField('cfg_business_directory', 'cfg_business_directory', 'x_business_logo_link', 'business_logo_link', '`business_logo_link`', '`business_logo_link`', 200, -1, TRUE, '`business_logo_link`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->fields['business_logo_link'] = &$this->business_logo_link;

		// image_2
		$this->image_2 = new cField('cfg_business_directory', 'cfg_business_directory', 'x_image_2', 'image_2', '`image_2`', '`image_2`', 200, -1, TRUE, '`image_2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->fields['image_2'] = &$this->image_2;

		// img_3
		$this->img_3 = new cField('cfg_business_directory', 'cfg_business_directory', 'x_img_3', 'img_3', '`img_3`', '`img_3`', 200, -1, TRUE, '`img_3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->fields['img_3'] = &$this->img_3;

		// detail_desc
		$this->detail_desc = new cField('cfg_business_directory', 'cfg_business_directory', 'x_detail_desc', 'detail_desc', '`detail_desc`', '`detail_desc`', 201, -1, FALSE, '`detail_desc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['detail_desc'] = &$this->detail_desc;

		// longitute
		$this->longitute = new cField('cfg_business_directory', 'cfg_business_directory', 'x_longitute', 'longitute', '`longitute`', '`longitute`', 3, -1, FALSE, '`longitute`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->longitute->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['longitute'] = &$this->longitute;

		// latitude
		$this->latitude = new cField('cfg_business_directory', 'cfg_business_directory', 'x_latitude', 'latitude', '`latitude`', '`latitude`', 3, -1, FALSE, '`latitude`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->latitude->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['latitude'] = &$this->latitude;

		// primary_number
		$this->primary_number = new cField('cfg_business_directory', 'cfg_business_directory', 'x_primary_number', 'primary_number', '`primary_number`', '`primary_number`', 3, -1, FALSE, '`primary_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->primary_number->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['primary_number'] = &$this->primary_number;

		// secondary_number
		$this->secondary_number = new cField('cfg_business_directory', 'cfg_business_directory', 'x_secondary_number', 'secondary_number', '`secondary_number`', '`secondary_number`', 3, -1, FALSE, '`secondary_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->secondary_number->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['secondary_number'] = &$this->secondary_number;

		// fb_page
		$this->fb_page = new cField('cfg_business_directory', 'cfg_business_directory', 'x_fb_page', 'fb_page', '`fb_page`', '`fb_page`', 200, -1, FALSE, '`fb_page`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['fb_page'] = &$this->fb_page;

		// timings
		$this->timings = new cField('cfg_business_directory', 'cfg_business_directory', 'x_timings', 'timings', '`timings`', '`timings`', 200, -1, FALSE, '`timings`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['timings'] = &$this->timings;

		// website
		$this->website = new cField('cfg_business_directory', 'cfg_business_directory', 'x_website', 'website', '`website`', '`website`', 200, -1, FALSE, '`website`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['website'] = &$this->website;

		// status
		$this->status = new cField('cfg_business_directory', 'cfg_business_directory', 'x_status', 'status', '`status`', '`status`', 202, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->status->FldDataType = EW_DATATYPE_BOOLEAN;
		$this->status->OptionCount = 2;
		$this->fields['status'] = &$this->status;

		// ETD
		$this->ETD = new cField('cfg_business_directory', 'cfg_business_directory', 'x_ETD', 'ETD', '`ETD`', 'DATE_FORMAT(`ETD`, \'%Y/%m/%d\')', 135, 5, FALSE, '`ETD`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`cfg_business_directory`";
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
			return "cfg_business_directorylist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "cfg_business_directorylist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("cfg_business_directoryview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("cfg_business_directoryview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "cfg_business_directoryadd.php?" . $this->UrlParm($parm);
		else
			$url = "cfg_business_directoryadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("cfg_business_directoryedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("cfg_business_directoryadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("cfg_business_directorydelete.php", $this->UrlParm());
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
		$this->business_title->setDbValue($rs->fields('business_title'));
		$this->cat_id->setDbValue($rs->fields('cat_id'));
		$this->province_id->setDbValue($rs->fields('province_id'));
		$this->city_id->setDbValue($rs->fields('city_id'));
		$this->business_address->setDbValue($rs->fields('business_address'));
		$this->business_logo_link->Upload->DbValue = $rs->fields('business_logo_link');
		$this->image_2->Upload->DbValue = $rs->fields('image_2');
		$this->img_3->Upload->DbValue = $rs->fields('img_3');
		$this->detail_desc->setDbValue($rs->fields('detail_desc'));
		$this->longitute->setDbValue($rs->fields('longitute'));
		$this->latitude->setDbValue($rs->fields('latitude'));
		$this->primary_number->setDbValue($rs->fields('primary_number'));
		$this->secondary_number->setDbValue($rs->fields('secondary_number'));
		$this->fb_page->setDbValue($rs->fields('fb_page'));
		$this->timings->setDbValue($rs->fields('timings'));
		$this->website->setDbValue($rs->fields('website'));
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

		$this->ETD->CellCssStyle = "white-space: nowrap;";

		// ID
		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

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

		// ETD
		$this->ETD->ViewValue = $this->ETD->CurrentValue;
		$this->ETD->ViewValue = ew_FormatDateTime($this->ETD->ViewValue, 5);
		$this->ETD->ViewCustomAttributes = "";

		// ID
		$this->ID->LinkCustomAttributes = "";
		$this->ID->HrefValue = "";
		$this->ID->TooltipValue = "";

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

		// business_title
		$this->business_title->EditAttrs["class"] = "form-control";
		$this->business_title->EditCustomAttributes = "";
		$this->business_title->EditValue = $this->business_title->CurrentValue;
		$this->business_title->PlaceHolder = ew_RemoveHtml($this->business_title->FldCaption());

		// cat_id
		$this->cat_id->EditAttrs["class"] = "form-control";
		$this->cat_id->EditCustomAttributes = "";

		// province_id
		$this->province_id->EditAttrs["class"] = "form-control";
		$this->province_id->EditCustomAttributes = "";

		// city_id
		$this->city_id->EditAttrs["class"] = "form-control";
		$this->city_id->EditCustomAttributes = "";

		// business_address
		$this->business_address->EditAttrs["class"] = "form-control";
		$this->business_address->EditCustomAttributes = "";
		$this->business_address->EditValue = $this->business_address->CurrentValue;
		$this->business_address->PlaceHolder = ew_RemoveHtml($this->business_address->FldCaption());

		// business_logo_link
		$this->business_logo_link->EditAttrs["class"] = "form-control";
		$this->business_logo_link->EditCustomAttributes = "";
		$this->business_logo_link->UploadPath = 'uploads/business';
		if (!ew_Empty($this->business_logo_link->Upload->DbValue)) {
			$this->business_logo_link->EditValue = $this->business_logo_link->Upload->DbValue;
		} else {
			$this->business_logo_link->EditValue = "";
		}
		if (!ew_Empty($this->business_logo_link->CurrentValue))
			$this->business_logo_link->Upload->FileName = $this->business_logo_link->CurrentValue;

		// image_2
		$this->image_2->EditAttrs["class"] = "form-control";
		$this->image_2->EditCustomAttributes = "";
		$this->image_2->UploadPath = 'uploads/business';
		if (!ew_Empty($this->image_2->Upload->DbValue)) {
			$this->image_2->EditValue = $this->image_2->Upload->DbValue;
		} else {
			$this->image_2->EditValue = "";
		}
		if (!ew_Empty($this->image_2->CurrentValue))
			$this->image_2->Upload->FileName = $this->image_2->CurrentValue;

		// img_3
		$this->img_3->EditAttrs["class"] = "form-control";
		$this->img_3->EditCustomAttributes = "";
		$this->img_3->UploadPath = 'uploads/business';
		if (!ew_Empty($this->img_3->Upload->DbValue)) {
			$this->img_3->EditValue = $this->img_3->Upload->DbValue;
		} else {
			$this->img_3->EditValue = "";
		}
		if (!ew_Empty($this->img_3->CurrentValue))
			$this->img_3->Upload->FileName = $this->img_3->CurrentValue;

		// detail_desc
		$this->detail_desc->EditAttrs["class"] = "form-control";
		$this->detail_desc->EditCustomAttributes = "";
		$this->detail_desc->EditValue = $this->detail_desc->CurrentValue;
		$this->detail_desc->PlaceHolder = ew_RemoveHtml($this->detail_desc->FldCaption());

		// longitute
		$this->longitute->EditAttrs["class"] = "form-control";
		$this->longitute->EditCustomAttributes = "";
		$this->longitute->EditValue = $this->longitute->CurrentValue;
		$this->longitute->PlaceHolder = ew_RemoveHtml($this->longitute->FldCaption());

		// latitude
		$this->latitude->EditAttrs["class"] = "form-control";
		$this->latitude->EditCustomAttributes = "";
		$this->latitude->EditValue = $this->latitude->CurrentValue;
		$this->latitude->PlaceHolder = ew_RemoveHtml($this->latitude->FldCaption());

		// primary_number
		$this->primary_number->EditAttrs["class"] = "form-control";
		$this->primary_number->EditCustomAttributes = "";
		$this->primary_number->EditValue = $this->primary_number->CurrentValue;
		$this->primary_number->PlaceHolder = ew_RemoveHtml($this->primary_number->FldCaption());

		// secondary_number
		$this->secondary_number->EditAttrs["class"] = "form-control";
		$this->secondary_number->EditCustomAttributes = "";
		$this->secondary_number->EditValue = $this->secondary_number->CurrentValue;
		$this->secondary_number->PlaceHolder = ew_RemoveHtml($this->secondary_number->FldCaption());

		// fb_page
		$this->fb_page->EditAttrs["class"] = "form-control";
		$this->fb_page->EditCustomAttributes = "";
		$this->fb_page->EditValue = $this->fb_page->CurrentValue;
		$this->fb_page->PlaceHolder = ew_RemoveHtml($this->fb_page->FldCaption());

		// timings
		$this->timings->EditAttrs["class"] = "form-control";
		$this->timings->EditCustomAttributes = "";
		$this->timings->EditValue = $this->timings->CurrentValue;
		$this->timings->PlaceHolder = ew_RemoveHtml($this->timings->FldCaption());

		// website
		$this->website->EditAttrs["class"] = "form-control";
		$this->website->EditCustomAttributes = "";
		$this->website->EditValue = $this->website->CurrentValue;
		$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

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
					if ($this->business_title->Exportable) $Doc->ExportCaption($this->business_title);
					if ($this->cat_id->Exportable) $Doc->ExportCaption($this->cat_id);
					if ($this->province_id->Exportable) $Doc->ExportCaption($this->province_id);
					if ($this->city_id->Exportable) $Doc->ExportCaption($this->city_id);
					if ($this->business_address->Exportable) $Doc->ExportCaption($this->business_address);
					if ($this->business_logo_link->Exportable) $Doc->ExportCaption($this->business_logo_link);
					if ($this->image_2->Exportable) $Doc->ExportCaption($this->image_2);
					if ($this->img_3->Exportable) $Doc->ExportCaption($this->img_3);
					if ($this->detail_desc->Exportable) $Doc->ExportCaption($this->detail_desc);
					if ($this->longitute->Exportable) $Doc->ExportCaption($this->longitute);
					if ($this->latitude->Exportable) $Doc->ExportCaption($this->latitude);
					if ($this->primary_number->Exportable) $Doc->ExportCaption($this->primary_number);
					if ($this->secondary_number->Exportable) $Doc->ExportCaption($this->secondary_number);
					if ($this->fb_page->Exportable) $Doc->ExportCaption($this->fb_page);
					if ($this->timings->Exportable) $Doc->ExportCaption($this->timings);
					if ($this->website->Exportable) $Doc->ExportCaption($this->website);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				} else {
					if ($this->business_title->Exportable) $Doc->ExportCaption($this->business_title);
					if ($this->cat_id->Exportable) $Doc->ExportCaption($this->cat_id);
					if ($this->province_id->Exportable) $Doc->ExportCaption($this->province_id);
					if ($this->city_id->Exportable) $Doc->ExportCaption($this->city_id);
					if ($this->business_address->Exportable) $Doc->ExportCaption($this->business_address);
					if ($this->business_logo_link->Exportable) $Doc->ExportCaption($this->business_logo_link);
					if ($this->image_2->Exportable) $Doc->ExportCaption($this->image_2);
					if ($this->img_3->Exportable) $Doc->ExportCaption($this->img_3);
					if ($this->longitute->Exportable) $Doc->ExportCaption($this->longitute);
					if ($this->latitude->Exportable) $Doc->ExportCaption($this->latitude);
					if ($this->primary_number->Exportable) $Doc->ExportCaption($this->primary_number);
					if ($this->secondary_number->Exportable) $Doc->ExportCaption($this->secondary_number);
					if ($this->fb_page->Exportable) $Doc->ExportCaption($this->fb_page);
					if ($this->timings->Exportable) $Doc->ExportCaption($this->timings);
					if ($this->website->Exportable) $Doc->ExportCaption($this->website);
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
						if ($this->business_title->Exportable) $Doc->ExportField($this->business_title);
						if ($this->cat_id->Exportable) $Doc->ExportField($this->cat_id);
						if ($this->province_id->Exportable) $Doc->ExportField($this->province_id);
						if ($this->city_id->Exportable) $Doc->ExportField($this->city_id);
						if ($this->business_address->Exportable) $Doc->ExportField($this->business_address);
						if ($this->business_logo_link->Exportable) $Doc->ExportField($this->business_logo_link);
						if ($this->image_2->Exportable) $Doc->ExportField($this->image_2);
						if ($this->img_3->Exportable) $Doc->ExportField($this->img_3);
						if ($this->detail_desc->Exportable) $Doc->ExportField($this->detail_desc);
						if ($this->longitute->Exportable) $Doc->ExportField($this->longitute);
						if ($this->latitude->Exportable) $Doc->ExportField($this->latitude);
						if ($this->primary_number->Exportable) $Doc->ExportField($this->primary_number);
						if ($this->secondary_number->Exportable) $Doc->ExportField($this->secondary_number);
						if ($this->fb_page->Exportable) $Doc->ExportField($this->fb_page);
						if ($this->timings->Exportable) $Doc->ExportField($this->timings);
						if ($this->website->Exportable) $Doc->ExportField($this->website);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
					} else {
						if ($this->business_title->Exportable) $Doc->ExportField($this->business_title);
						if ($this->cat_id->Exportable) $Doc->ExportField($this->cat_id);
						if ($this->province_id->Exportable) $Doc->ExportField($this->province_id);
						if ($this->city_id->Exportable) $Doc->ExportField($this->city_id);
						if ($this->business_address->Exportable) $Doc->ExportField($this->business_address);
						if ($this->business_logo_link->Exportable) $Doc->ExportField($this->business_logo_link);
						if ($this->image_2->Exportable) $Doc->ExportField($this->image_2);
						if ($this->img_3->Exportable) $Doc->ExportField($this->img_3);
						if ($this->longitute->Exportable) $Doc->ExportField($this->longitute);
						if ($this->latitude->Exportable) $Doc->ExportField($this->latitude);
						if ($this->primary_number->Exportable) $Doc->ExportField($this->primary_number);
						if ($this->secondary_number->Exportable) $Doc->ExportField($this->secondary_number);
						if ($this->fb_page->Exportable) $Doc->ExportField($this->fb_page);
						if ($this->timings->Exportable) $Doc->ExportField($this->timings);
						if ($this->website->Exportable) $Doc->ExportField($this->website);
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
