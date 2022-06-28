<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(199, "mi_dashboard_php", $Language->MenuPhrase("199", "MenuText"), "dashboard.php", -1, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}dashboard.php'), FALSE);
$RootMenu->AddMenuItem(284, "mci_Reporting", $Language->MenuPhrase("284", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(252, "mi_vw_ads_payment_detail", $Language->MenuPhrase("252", "MenuText"), "vw_ads_payment_detaillist.php", 284, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}vw_ads_payment_detail'), FALSE);
$RootMenu->AddMenuItem(286, "mi_vw_payment_api_response", $Language->MenuPhrase("286", "MenuText"), "vw_payment_api_responselist.php", 284, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}vw_payment_api_response'), FALSE);
$RootMenu->AddMenuItem(198, "mci_Business_Directory", $Language->MenuPhrase("198", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(150, "mi_cfg_business_directory", $Language->MenuPhrase("150", "MenuText"), "cfg_business_directorylist.php", 198, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_business_directory'), FALSE);
$RootMenu->AddMenuItem(285, "mi_cfg_bussiness_listing_category", $Language->MenuPhrase("285", "MenuText"), "cfg_bussiness_listing_categorylist.php", 198, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_bussiness_listing_category'), FALSE);
$RootMenu->AddMenuItem(149, "mci_Classified_Management", $Language->MenuPhrase("149", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(116, "mi_classified_data", $Language->MenuPhrase("116", "MenuText"), "classified_datalist.php", 149, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}classified_data'), FALSE);
$RootMenu->AddMenuItem(113, "mi_cfg_classified_attribure", $Language->MenuPhrase("113", "MenuText"), "cfg_classified_attriburelist.php", 149, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_classified_attribure'), FALSE);
$RootMenu->AddMenuItem(45, "mci_Ads_Management", $Language->MenuPhrase("45", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(6, "mi_car_ads", $Language->MenuPhrase("6", "MenuText"), "car_adslist.php", 45, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}car_ads'), FALSE);
$RootMenu->AddMenuItem(287, "mi_vw_current_feature_ads", $Language->MenuPhrase("287", "MenuText"), "vw_current_feature_adslist.php", 45, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}vw_current_feature_ads'), FALSE);
$RootMenu->AddMenuItem(112, "mi_vw_pending_ads_list", $Language->MenuPhrase("112", "MenuText"), "vw_pending_ads_listlist.php", 45, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}vw_pending_ads_list'), FALSE);
$RootMenu->AddMenuItem(251, "mi_vw_approved_ads_list", $Language->MenuPhrase("251", "MenuText"), "vw_approved_ads_listlist.php", 45, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}vw_approved_ads_list'), FALSE);
$RootMenu->AddMenuItem(66, "mci_Approved_Ads", $Language->MenuPhrase("66", "MenuText"), "", 45, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(67, "mci_Closed_Ads", $Language->MenuPhrase("67", "MenuText"), "", 45, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(68, "mci_Featured_Ads", $Language->MenuPhrase("68", "MenuText"), "", 45, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(69, "mci_Archived_Ads", $Language->MenuPhrase("69", "MenuText"), "", 45, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(250, "mci_Customer_Management", $Language->MenuPhrase("250", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(1, "mi_customers", $Language->MenuPhrase("1", "MenuText"), "customerslist.php", 250, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}customers'), FALSE);
$RootMenu->AddMenuItem(5, "mci_User_Managements", $Language->MenuPhrase("5", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(2, "mi_adm_users", $Language->MenuPhrase("2", "MenuText"), "adm_userslist.php", 5, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}adm_users'), FALSE);
$RootMenu->AddMenuItem(4, "mi_user_levels", $Language->MenuPhrase("4", "MenuText"), "user_levelslist.php", 5, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(3, "mi_user_level_permission", $Language->MenuPhrase("3", "MenuText"), "user_level_permissionlist.php", 5, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(32, "mci_Configurations", $Language->MenuPhrase("32", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(7, "mi_cfg_body_colors", $Language->MenuPhrase("7", "MenuText"), "cfg_body_colorslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_body_colors'), FALSE);
$RootMenu->AddMenuItem(8, "mi_cfg_body_type", $Language->MenuPhrase("8", "MenuText"), "cfg_body_typelist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_body_type'), FALSE);
$RootMenu->AddMenuItem(9, "mi_cfg_car_versions", $Language->MenuPhrase("9", "MenuText"), "cfg_car_versionslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_car_versions'), FALSE);
$RootMenu->AddMenuItem(11, "mi_cfg_engine_types", $Language->MenuPhrase("11", "MenuText"), "cfg_engine_typeslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_engine_types'), FALSE);
$RootMenu->AddMenuItem(12, "mi_cfg_features", $Language->MenuPhrase("12", "MenuText"), "cfg_featureslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_features'), FALSE);
$RootMenu->AddMenuItem(13, "mi_cfg_make_companies", $Language->MenuPhrase("13", "MenuText"), "cfg_make_companieslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_make_companies'), FALSE);
$RootMenu->AddMenuItem(14, "mi_cfg_models", $Language->MenuPhrase("14", "MenuText"), "cfg_modelslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_models'), FALSE);
$RootMenu->AddMenuItem(34, "mi_cfg_province", $Language->MenuPhrase("34", "MenuText"), "cfg_provincelist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_province'), FALSE);
$RootMenu->AddMenuItem(10, "mi_cfg_cities", $Language->MenuPhrase("10", "MenuText"), "cfg_citieslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_cities'), FALSE);
$RootMenu->AddMenuItem(35, "mi_cfg_years", $Language->MenuPhrase("35", "MenuText"), "cfg_yearslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_years'), FALSE);
$RootMenu->AddMenuItem(288, "mi_cfg_advertisement_spaces", $Language->MenuPhrase("288", "MenuText"), "cfg_advertisement_spaceslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_advertisement_spaces'), FALSE);
$RootMenu->AddMenuItem(109, "mci_Payment_Configuratios", $Language->MenuPhrase("109", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(77, "mi_bank_accounts", $Language->MenuPhrase("77", "MenuText"), "bank_accountslist.php", 109, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}bank_accounts'), FALSE);
$RootMenu->AddMenuItem(78, "mi_packages", $Language->MenuPhrase("78", "MenuText"), "packageslist.php", 109, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}packages'), FALSE);
$RootMenu->AddMenuItem(79, "mi_pay_methods", $Language->MenuPhrase("79", "MenuText"), "pay_methodslist.php", 109, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}pay_methods'), FALSE);
$RootMenu->AddMenuItem(111, "mci_Transactions", $Language->MenuPhrase("111", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(80, "mi_payment_transactions", $Language->MenuPhrase("80", "MenuText"), "payment_transactionslist.php", 111, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}payment_transactions'), FALSE);
$RootMenu->AddMenuItem(72, "mi_failed_jobs", $Language->MenuPhrase("72", "MenuText"), "failed_jobslist.php", 111, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}failed_jobs'), FALSE);
$RootMenu->AddMenuItem(33, "mci_Dealers_", $Language->MenuPhrase("33", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(15, "mi_dealers_showroom", $Language->MenuPhrase("15", "MenuText"), "dealers_showroomlist.php", 33, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}dealers_showroom'), FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
