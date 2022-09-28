<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//Login
$route['login'] = "LoginController/login";
$route['logout'] = "LoginController/logout";
$route['auth_redirect']="LoginController/auth_redirect";
//Dashboard
$route['Dashboard'] = "welcome/Dashboard";
// --------------template -----------

$route["admin/form_template"] = "Welcome/from_template";
$route["admin/view_departments"] = "Welcome/view_department";
$route["admin/view_process/(:any)"] = "Welcome/view_process/$1";
$route["admin/getProcessOptions"] = "ProcessController/getProcessOptions";
$route["admin/saveProcessDetails"] = "ProcessController/saveProcessDetails";

$route["form_view/(:any)"] = "FormController/index/$1";
$route["form_view_personal/(:any)"] = "FormController/index_personal/$1";
$route["company/form_view/(:any)"] = "FormController/index/$1";
$route["getTemplateForm"]="FormController/get_form_fields";
$route["get_history_data"]="FormController/get_history_data";
$route["getTemplateFormPersonal"]="PersonalTemplateController/get_form_fields";

// -------------------------------- Department Section ---------------------------------------
$route["admin/getDepartments"]="DepartmentController/getDepartmentTableData";
$route["admin/fetchAllCompanies"]="DepartmentController/selectAllCompanies";
$route["company/fetchAllCompanies"]="DepartmentController/selectAllCompanies";
$route["admin/saveDepartment"]="DepartmentController/uploadDepartment";
$route["admin/ChangeDepartmentStatus"]="DepartmentController/ChangeDepartmentStatus";
$route["admin/getDepartmentDataById"]="DepartmentController/getDepartmentDataById";
$route["admin/companyDepartment"]='DepartmentController/selectCompanyDepartment';
$route["company/companyDepartment"]='DepartmentController/selectCompanyDepartment';
// ------------------------------ Template Section ---------------------------------------
$route["admin/view_template"] = "Welcome/view_template";
$route["admin/save_template"]="TemplateController/saveTemplate";
$route["admin/fetch_template_sections"]="TemplateController/getDepartmentSections";
$route["admin/fetch_section_details"]="TemplateController/getSectionElements";
$route["admin/deleteSection"]="TemplateController/deleteSection";
$route["admin/form_template"] = "Welcome/from_template";
$route["admin/deleteTemplateElement"]="TemplateController/deleteTemplateElement";
// ----------------- HandsonTable Section ----------------------

$route["HandsonConfiguration"]="HandsonTableController";
$route["addHandsonTemplate"]="HandsonTableController/addHandsonTemplate";
$route["getTablesList"]="HandsonTableController/getTablesList";
$route["edithandsontemplate"]="HandsonTableController/edithandsontemplate";
$route["TE_getAllTablesFromDatabase"]="HandsonTableController/getAllTablesFromDatabase";
$route["TE_getTemplateData"]="HandsonTableController/TE_getTemplateData";
$route["TE_getDBColumnNames"]="HandsonTableController/TE_getDBColumnNames";
$route["TE_SaveConfiguration"]="HandsonTableController/TE_SaveConfiguration";
$route["TE_getHandsonTableData"]="HandsonTableController/getHandsonTableData";
$route["TE_SaveTransaction"]="HandsonTableController/TE_SaveTransaction";
$route["TE_SavePrefillData"]="HandsonTableController/TE_SavePrefillData";
$route["TE_getAllHashKeys"]="HandsonTableController/TE_getAllHashKeys";
$route["RemoveRowFromDBFunc"]="HandsonTableController/RemoveRowFromDBFunc";
$route["TE_deactiveTemplate"]="HandsonTableController/TE_deactiveTemplate";



// -----------Page Configuration --------------------------------
$route['pageConfiguration/(:any)'] = "TemplateConfiguration/index/$1";
$route['saveConfiguration'] = "TemplateConfiguration/saveConfiguration";
$route['ShowForm'] = "TemplateConfiguration/ShowForm";
$route['getTableFields'] = "TemplateConfiguration/getTableFields";
$route['saveFormData'] = "TemplateConfiguration/saveFormData";

//supriya route
$route['template_list']="TemplateConfiguration/template_list";
$route['getAllTemplateList']="TemplateConfiguration/getAllTemplateList";
$route['viewForm/(:any)']="TemplateConfiguration/viewForm/$1";
$route['viewForm/(:any)/(:any)']="TemplateConfiguration/viewForm/$1/$2";
$route['getHandson'] = "TemplateConfiguration/getHandson";


//datatable
$route["getAllTables"]="DatatableEditorController/getAllTableNames";
$route["getAllColumns"]="DatatableEditorController/getAllColumns";
$route["saveDataTableEditor"]="DatatableEditorController/saveDataTableEditor";
$route['executeQuery']="DatatableEditorController/customQueryExecutor";
$route['getTemplateFormIdByName']="DatatableEditorController/getTemplateFormIdByName";
$route['getAllTemplateListOptions']="DatatableEditorController/getAllTemplateListOptions";
$route['getTemplateQueryParam']="DatatableEditorController/getTemplateQueryParam";
$route["getDataTableData"]="DatatableEditorController/getDynamicTableData";
$route["getEditFormData"]="DatatableEditorController/getEditFormData";
$route["getOnchangeDependantData"]="DatatableEditorController/getOnchangeDependantData";
$route["getDataTableTemplate"]="DatatableEditorController/getDataTableTemplate";

$route['getTableColumn'] = "TemplateConfiguration/getTableColumn";
$route["getAllTablesList"]="DatatableEditorController/getAllTablesList";

$route['getOptions'] = "TemplateConfiguration/getOptions";
$route['saveAddMore'] = "TemplateConfiguration/saveAddMore";
$route['UpdateAddMore'] = "TemplateConfiguration/UpdateAddMore";

$route['removeAddmoreRows'] = "TemplateConfiguration/removeAddmoreRows";


///REPORT ENGINE

//BMR Report
$route['report_list/(:any)/(:any)']='WordReportController/index/$1/$2';
$route['bmr_report_list']='WordReportController/report_list';
$route['saveHtmlTemplate']='WordReportController/saveHtmlTemplate';
$route['userProfiles']='WordReportController/userProfiles';
$route['getPagesList']='WordReportController/getWordReportMakerData';
$route['getPageDataToEditor']='WordReportController/getPageDataToEditor';
$route['bmr_report_view/(:any)/(:any)']='WordReportController/bmr_report_view/$1/$2';
$route['bmr_report_view/(:any)/(:any)/(:any)']='WordReportController/bmr_report_view/$1/$2/$3';
$route['getReportData']='WordReportController/getReportData';
$route['saveReportPageData']='WordReportController/saveReportPageData';
$route['bmrReport/(:any)/(:any)']='WordReportController/bmrReport/$1/$2';

//Production BMR Report Module
$route['getBMRMasterReportList']='WordReportController/getBMRMasterReportList';
$route['setBMRReportToScheduler']='WordReportController/setBMRReportToScheduler';
$route['checkB MRReportAttach']='WordReportController/checkBMRReportAttach';
$route['addNewBMR']='WordReportController/addNewBMR';
$route['getBMRList']='WordReportController/getBMRList';
$route['getBMRNameList']='WordReportController/getBMRNameList';
$route['checkBMRId']='WordReportController/checkBMRId';
$route['getHistoryTable']='WordReportController/getHistoryTable';
$route['all_bmr_report_view/(:any)/(:any)']='WordReportController/all_bmr_report_view/$1/$2';
$route['getAllBMRReport']='WordReportController/getAllBMRReport';
$route['getMaterialTable']='WordReportController/getMaterialTable';
$route['getIndentTable']='WordReportController/getIndentTable';
$route['getProcessFlowChart']='WordReportController/getProcessFlowChart';
$route['getAllTablesNames']='WordReportController/getAllTablesNames';
$route['getTableName']='WordReportController/getTableName';
$route['getColumnNames']='WordReportController/getColumnNames';
$route['getTableColumns']='WordReportController/getTableColumns';
$route['createTableDynamic']='WordReportController/createTableDynamic';
$route['getQueryParameterList']='WordReportController/getQueryParameterList';
$route['getReportPageData']='WordReportController/getReportPageData';
$route['getPageLabelData']='WordReportController/getPageLabelData';
$route['getBMRParamData']='WordReportController/getBMRParamData';
$route['getQueryParamData']='WordReportController/getQueryParamData';
$route['getGroupsList']='WordReportController/getGroupsList';
$route['getAwsLinkToDownload']='WordReportController/getAwsLinkToDownload';
$route['ChildGroup']='WordReportController/ChildGroup';


//report
$route["Reports_query"]="Report/Reports_query";
$route["ReportView"]="Report/report_view";
