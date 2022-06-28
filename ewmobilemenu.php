<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(199, "mmi_dashboard_php", $Language->MenuPhrase("199", "MenuText"), "dashboard.php", -1, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}dashboard.php'), FALSE);
$RootMenu->AddMenuItem(284, "mmci_Reporting", $Language->MenuPhrase("284", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(252, "mmi_vw_ads_payment_detail", $Language->MenuPhrase("252", "MenuText"), "vw_ads_payment_detaillist.php", 284, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}vw_ads_payment_detail'), FALSE);
$RootMenu->AddMenuItem(286, "mmi_vw_payment_api_response", $Language->MenuPhrase("286", "MenuText"), "vw_payment_api_responselist.php", 284, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}vw_payment_api_response'), FALSE);
$RootMenu->AddMenuItem(198, "mmci_Business_Directory", $Language->MenuPhrase("198", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(150, "mmi_cfg_business_directory", $Language->MenuPhrase("150", "MenuText"), "cfg_business_directorylist.php", 198, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_business_directory'), FALSE);
$RootMenu->AddMenuItem(285, "mmi_cfg_bussiness_listing_category", $Language->MenuPhrase("285", "MenuText"), "cfg_bussiness_listing_categorylist.php", 198, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_bussiness_listing_category'), FALSE);
$RootMenu->AddMenuItem(149, "mmci_Classified_Management", $Language->MenuPhrase("149", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(116, "mmi_classified_data", $Language->MenuPhrase("116", "MenuText"), "classified_datalist.php", 149, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}classified_data'), FALSE);
$RootMenu->AddMenuItem(113, "mmi_cfg_classified_attribure", $Language->MenuPhrase("113", "MenuText"), "cfg_classified_attriburelist.php", 149, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_classified_attribure'), FALSE);
$RootMenu->AddMenuItem(45, "mmci_Ads_Management", $Language->MenuPhrase("45", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(6, "mmi_car_ads", $Language->MenuPhrase("6", "MenuText"), "car_adslist.php", 45, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}car_ads'), FALSE);
$RootMenu->AddMenuItem(287, "mmi_vw_current_feature_ads", $Language->MenuPhrase("287", "MenuText"), "vw_current_feature_adslist.php", 45, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}vw_current_feature_ads'), FALSE);
$RootMenu->AddMenuItem(112, "mmi_vw_pending_ads_list", $Language->MenuPhrase("112", "MenuText"), "vw_pending_ads_listlist.php", 45, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}vw_pending_ads_list'), FALSE);
$RootMenu->AddMenuItem(251, "mmi_vw_approved_ads_list", $Language->MenuPhrase("251", "MenuText"), "vw_approved_ads_listlist.php", 45, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}vw_approved_ads_list'), FALSE);
$RootMenu->AddMenuItem(66, "mmci_Approved_Ads", $Language->MenuPhrase("66", "MenuText"), "", 45, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(67, "mmci_Closed_Ads", $Language->MenuPhrase("67", "MenuText"), "", 45, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(68, "mmci_Featured_Ads", $Language->MenuPhrase("68", "MenuText"), "", 45, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(69, "mmci_Archived_Ads", $Language->MenuPhrase("69", "MenuText"), "", 45, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(250, "mmci_Customer_Management", $Language->MenuPhrase("250", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(1, "mmi_customers", $Language->MenuPhrase("1", "MenuText"), "customerslist.php", 250, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}customers'), FALSE);
$RootMenu->AddMenuItem(5, "mmci_User_Managements", $Language->MenuPhrase("5", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(2, "mmi_adm_users", $Language->MenuPhrase("2", "MenuText"), "adm_userslist.php", 5, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}adm_users'), FALSE);
$RootMenu->AddMenuItem(4, "mmi_user_levels", $Language->MenuPhrase("4", "MenuText"), "user_levelslist.php", 5, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(3, "mmi_user_level_permission", $Language->MenuPhrase("3", "MenuText"), "user_level_permissionlist.php", 5, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(32, "mmci_Configurations", $Language->MenuPhrase("32", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(7, "mmi_cfg_body_colors", $Language->MenuPhrase("7", "MenuText"), "cfg_body_colorslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_body_colors'), FALSE);
$RootMenu->AddMenuItem(8, "mmi_cfg_body_type", $Language->MenuPhrase("8", "MenuText"), "cfg_body_typelist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_body_type'), FALSE);
$RootMenu->AddMenuItem(9, "mmi_cfg_car_versions", $Language->MenuPhrase("9", "MenuText"), "cfg_car_versionslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_car_versions'), FALSE);
$RootMenu->AddMenuItem(11, "mmi_cfg_engine_types", $Language->MenuPhrase("11", "MenuText"), "cfg_engine_typeslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_engine_types'), FALSE);
$RootMenu->AddMenuItem(12, "mmi_cfg_features", $Language->MenuPhrase("12", "MenuText"), "cfg_featureslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_features'), FALSE);
$RootMenu->AddMenuItem(13, "mmi_cfg_make_companies", $Language->MenuPhrase("13", "MenuText"), "cfg_make_companieslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_make_companies'), FALSE);
$RootMenu->AddMenuItem(14, "mmi_cfg_models", $Language->MenuPhrase("14", "MenuText"), "cfg_modelslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_models'), FALSE);
$RootMenu->AddMenuItem(34, "mmi_cfg_province", $Language->MenuPhrase("34", "MenuText"), "cfg_provincelist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_province'), FALSE);
$RootMenu->AddMenuItem(10, "mmi_cfg_cities", $Language->MenuPhrase("10", "MenuText"), "cfg_citieslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_cities'), FALSE);
$RootMenu->AddMenuItem(35, "mmi_cfg_years", $Language->MenuPhrase("35", "MenuText"), "cfg_yearslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_years'), FALSE);
$RootMenu->AddMenuItem(288, "mmi_cfg_advertisement_spaces", $Language->MenuPhrase("288", "MenuText"), "cfg_advertisement_spaceslist.php", 32, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}cfg_advertisement_spaces'), FALSE);
$RootMenu->AddMenuItem(109, "mmci_Payment_Configuratios", $Language->MenuPhrase("109", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(77, "mmi_bank_accounts", $Language->MenuPhrase("77", "MenuText"), "bank_accountslist.php", 109, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}bank_accounts'), FALSE);
$RootMenu->AddMenuItem(78, "mmi_packages", $Language->MenuPhrase("78", "MenuText"), "packageslist.php", 109, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}packages'), FALSE);
$RootMenu->AddMenuItem(79, "mmi_pay_methods", $Language->MenuPhrase("79", "MenuText"), "pay_methodslist.php", 109, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}pay_methods'), FALSE);
$RootMenu->AddMenuItem(111, "mmci_Transactions", $Language->MenuPhrase("111", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(80, "mmi_payment_transactions", $Language->MenuPhrase("80", "MenuText"), "payment_transactionslist.php", 111, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}payment_transactions'), FALSE);
$RootMenu->AddMenuItem(72, "mmi_failed_jobs", $Language->MenuPhrase("72", "MenuText"), "failed_jobslist.php", 111, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}failed_jobs'), FALSE);
$RootMenu->AddMenuItem(33, "mmci_Dealers_", $Language->MenuPhrase("33", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(15, "mmi_dealers_showroom", $Language->MenuPhrase("15", "MenuText"), "dealers_showroomlist.php", 33, "", AllowListMenu('{B7B92366-0BC8-4357-900F-FDBD8A72F51D}dealers_showroom'), FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
