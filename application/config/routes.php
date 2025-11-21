<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
$route['default_controller'] = 'Welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'Api/LoginController/login';
$route['get_districts'] = 'Api/LocationController/getDistricts';
$route['get_circles'] = 'Api/LocationController/getCircles';
$route['get_mouzas'] = 'Api/LocationController/getMouzas';
$route['get_lots'] = 'Api/LocationController/getLots';
$route['get_vills'] = 'Api/LocationController/getVills';
$route['get_dags'] = 'Api/LocationController/getDags';
$route['get_dag_data'] = 'Api/LocationController/getDagData';
$route['get_partdag_data'] = 'Api/LocationController/getPartdagData';
$route['get_land_revenue'] = 'Api/LocationController/getLandRevenue';
$route['submit_part_dag'] = 'Api/PartDagController/submitPartDag';
$route['get_tenants'] = 'Api/PartDagController/getTenants';
$route['submit_possessor'] = 'Api/PartDagController/submitPossessor';
$route['delete_possessor'] = 'Api/PartDagController/deletePossessor';
$route['update_part_dag'] = 'Api/PartDagController/updatePartDag';
$route['delete_part_dag'] = 'Api/PartDagController/deletePartDag';
$route['update_possessor_photo'] = 'Api/PartDagController/updatePossessorPhoto';
$route['remove_possessor_photo'] = 'Api/PartDagController/removePossessorPhoto';
$route['delete_possessor_ownership_document'] = 'Api/PartDagController/deletePossessorOwnershipDocument';
$route['update_possessor'] = 'Api/PartDagController/updatePossessor';

$route['get-master-data'] = 'Api/ResurveyDataController/getResurveyMasterData';
$route['get-dag-data'] = 'Api/ResurveyDataController/getResurveyDagData';
$route['get-survey-no-data'] = 'Api/ResurveyDataController/getSurveyNoData';
$route['get_chitha_data'] = 'Api/ResurveyDataController/getChithaData';
$route['get_partdag_data_preview'] = 'Api/LocationController/getPartdagDataPreview';


//reports
$route['get-resurvey-report'] = 'Api/ResurveyReportController/getResurveyReportData';
$route['get-district-report'] = 'Api/ResurveyReportController/getResurveyReportDistData';
$route['get-report'] = 'Api/ResurveyReportController/getResurveyReport';
//co routes
$route['get-circle-resurvey-report'] = 'Api/ResurveyReportController/getResurveyReportCoData';
$route['get-circle-resurvey-dashboard'] = 'Api/ResurveyReportController/getResurveyCoDashData';
$route['get_co_mut_cases'] = 'Api/MutationDataController/getCOMutCases';
$route['get_co_case'] = 'Api/MutationDataController/getCoCase';
$route['submit_co_case'] = 'Api/MutationDataController/submitCase';

//lm routes
$route['get-lm-resurvey-dashboard'] = 'Api/ResurveyReportController/getResurveyLmDashData';
$route['get_lm_villages'] = 'Api/MutationDataController/getLmVillages';
$route['get_lm_patta_types'] = 'Api/MutationDataController/getPattaTypes';
$route['get_lm_pattas'] = 'Api/MutationDataController/getPattaNos';
$route['get_lm_dags'] = 'Api/MutationDataController/getDags';
$route['get_lm_pattadars'] = 'Api/MutationDataController/getPattadars';
$route['get_lm_pdar_dags'] = 'Api/MutationDataController/getPdarDags';
$route['get_lm_transfer_types'] = 'Api/MutationDataController/getTransferTypes';
$route['submit_applicant'] = 'Api/MutationDataController/submitApplicant';
$route['get_lm_pattadar_dags'] = 'Api/MutationDataController/getPattadarDags';
$route['lm_mut_submit'] = 'Api/MutationDataController/submitLmMutation';
$route['get_lm_mut_cases'] = 'Api/MutationDataController/getLmMutCases';



//singlesignlogin routes
$route['add_login_log'] = 'Api/LoginController/addLoginLog';
$route['singlesign_login'] = 'Api/LoginController/singleSignRedirect';

//deeds
$route['get_deed'] = 'Api/ResurveyDataController/getDeed';


//api reports
$route['api/report/districts'] = 'Api/ReportApiController/districts';
$route['api/report/circles'] = 'Api/ReportApiController/circles';
$route['api/report/mouzas'] = 'Api/ReportApiController/mouzas';
$route['api/report/lots'] = 'Api/ReportApiController/lots';
$route['api/report/villages'] = 'Api/ReportApiController/villages';
$route['api/report/dag'] = 'Api/ReportApiController/dags';
$route['api/report/collection'] = 'Api/ReportApiController/collection_report';

//maps api
$route['api/report/village_map_data'] = 'Api/ReportApiController/get_village_map_data';

//user management
$route['api/users/create'] = 'Api/UserManagementController/create';












