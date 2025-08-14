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
$route['default_controller'] = 'Login';
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

// // Jamabandi
// $route['set-location-for-jamabandi'] = 'JamabandiController/setJamabandiLocation';

// $route['get-patta-no-for-jamabandi'] = 'JamabandiController/getJamabandiPattaTypeCode';
// $route['get-patta-type-jamabandi']   = 'JamabandiController/getJamabandiPattaType';
// $route['set-patta-no-for-jamabandi'] = 'JamabandiController/setJamabandiPattaTypeCode';

// $route['set-location-for-jamabandi-report']      = 'JamabandiController/setJamabandiLocationReport';
// $route['get-patta-details-for-jamabandi-report'] = 'JamabandiController/getAllPattaTypeAndPattaNo';
// $route['get-patta-type-jamabandi-report']        = 'JamabandiController/getJamabandiPattaTypeForReport';
// $route['get-jamabandi-details-report']           = 'JamabandiController/getJamabandiDetailsReport';

// $route['set-location-for-jamabandi-remarks'] = 'JamabandiController/setJamabandiLocationRemarks';
// $route['get-patta-no-jamabandi-remarks']     = 'JamabandiController/getAllPattaTypeAndPattaNoForJamaRemarks';
// $route['get-patta-type-jamabandi-remarks']   = 'JamabandiController/getJamabandiPattaTypeForJamaRemarks';
// $route['get-jamabandi-details-remarks']      = 'JamabandiController/getJamabandiDetailsRemarks';
// $route['add-jamabandi-remarks']              = 'JamabandiController/AddJamabandiNewRemarks';
// $route['edit-jamabandi-remarks']             = 'JamabandiController/editJamabandiNewRemarks';
// $route['delete-jamabandi-remarks']           = 'JamabandiController/deleteJamabandiNewRemarks';

// /***** Jama pattadar view location form *******/
// $route['set-location-for-jama-pattadar-bulk-update'] = 'jamabandi/JamaPattadarController/setJamaPattadarLocation';
// $route['update-jama-pattdar-from-chitha-pattadars'] = 'jamabandi/JamaPattadarController/updateJamaPattdarFromChithaPattadars';

// //PATTA
// $route['patta-select-location'] = 'PattaController/selectLocation';
// $route['patta-view-form'] = 'PattaController/selectPattaView';


// // User Management
// $route['logout'] = 'Login/logout';
// $route['get-change-password'] = 'UserManageController/changeUserPassword';
// $route['set-new-password']    = 'UserManageController/setNewPasswordByLoginUser';


// // Remove Testing Data
// $route['set-removable-database']     = 'TestingDataCleanController/setDatabaseDetails';
// $route['get-removable-database']     = 'TestingDataCleanController/getDatabaseDetails';
// $route['delete-removable-test-data'] = 'TestingDataCleanController/deleteTestingDataByDistrictDetailsFinal';


// //SVAMITVA CARD
// $route['show-svamitva-card'] = 'SvamitvaCardController/getSvamitvaCard';


// $route['upload_document'] = 'Chithacontrol/uploadDocument';
// $route['dag-upload'] = 'Chithacontrol/dagUpload';
// $route['upload_report'] = 'Chithacontrol/uploadDocumentReport';
// //KHATIAN REPORT
// $route['show-khatian-report'] = 'reports/KhatianReportController/showKhatianReport';

// /*** NC Village ****/
// /*** LM ******/
// $route['nc-village-wise-dags-for-lm']     = 'nc_village/NcVillageLmController/ncVillages';

// // DELETE DATA
// $route['set-location-for-village-wise-remove-data']     = 'DataController/selectLocation';



// // AUTH ROUTES
// $route['reset-password'] = 'Auth/AuthController/resetPasswordForm';
// $route['reset-mobile'] = 'Auth/AuthController/resetMobileForm';
// $route['enter-otp'] = 'Auth/AuthController/otpForm';


// // ################ API ROUTES ###############
// $route['api/dashboard/all-verified-dag-count'] = 'api/VerifiedDagCountDashboardController/getAllVerfiedDagCount';
// $route['api/dashboard/district-wise-dag-count'] = 'api/VerifiedDagCountDashboardController/getDistricWiseDagCount';
// $route['api/dashboard/circle-wise-dag-count/(:any)'] = 'api/VerifiedDagCountDashboardController/getCircleWiseDagCount/$1';
// $route['api/dashboard/mouza-wise-dag-count/(:any)/(:any)/(:any)'] = 'api/VerifiedDagCountDashboardController/getMouzeWiseDagCount/$1/$2/$3';
// $route['api/dashboard/lot-wise-dag-count/(:any)/(:any)/(:any)/(:any)'] = 'api/VerifiedDagCountDashboardController/getLotWiseDagCount/$1/$2/$3/$4';
// $route['api/dashboard/village-wise-dag-count/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'api/VerifiedDagCountDashboardController/getVillageWiseDagCount/$1/$2/$3/$4/$5';

// // ################ TEAMS ROUTES ##############
// $route['teams'] = 'TeamController/index';
// $route['team-save'] = 'TeamController/store';
// $route['team/(:any)/update'] = 'TeamController/update/$1';
// $route['team/(:any)/delete'] = 'TeamController/destroy/$1';
// $route['my-teams'] = 'TeamController/myteams';

// $route['nc-village-notifications']     = 'nc_village/NcVillageCommonController/notifications';


// $route['nc-chitha-basic-sync']     = 'utility/ChithaBasicNcSyncController/index';
// $route['nc-chitha-basic-sync-bhunaksa']     = 'utility/ChithaBasicNcSyncController/index_bhunaksa';
// $route['sync-bhunaksa-village/(:any)']     = 'utility/ChithaBasicNcSyncController/sync_bhunaksa_view_dags/$1';

// $route['circle-assign-supervisor'] = 'CircleAssignToSupervisorController/index';
// $route['circle-assign-supervisor-get-subdivisions'] = 'CircleAssignToSupervisorController/subdivisiondetails';
// $route['circle-assign-supervisor-get-circlesdetails'] = 'CircleAssignToSupervisorController/circledetails';
// $route['circle-assign-supervisor-save'] = 'CircleAssignToSupervisorController/saveSupervisorCircles';

// // ############### INSTRUMENT ASSIGNER ###########
// $route['instrument-assingner'] = 'InstrumentAssignerController/index';
// $route['instrument-assingner-save'] = 'InstrumentAssignerController/store';
// $route['instrument-assingner/(:any)/update'] = 'InstrumentAssignerController/update/$1';

// // ############## SURVEY ROUTES #############
// $route['surveyor-village/(:any)/(:any)/list'] = 'SurveyController/surveyorVillageList/$1/$2';
// $route['upload-daily-progress-init'] = 'SurveyController/uploadDailyInit';
// $route['surveyor-village/(:any)/upload-daily-progress'] = 'SurveyController/uploadDailyProgress/$1';
// $route['surveyor-village/(:any)/update-daily-progress'] = 'SurveyController/updateDailyProgress/$1';
// $route['surveyor-village/(:any)/get-daily-progresses'] = 'SurveyController/getPreviousLogs/$1';
// $route['surveyor-village/(:any)/survey-complete'] = 'SurveyController/completeSurvey/$1';
// $route['surveyor-village/(:any)/final-upload'] = 'SurveyController/finalUpload/$1';
// $route['survey-users'] = 'SurveyController/viewUsers';

// $route['port-dhar-data']     = 'utility/PortDharDataController/index';

// $route['survey/home'] = 'SurveyController/index';
// $route['survey/home/village-list'] = 'SurveyController/getSurveyVillageList';
// $route['survey/home/complete-village-list'] = 'SurveyController/getCompletedVillageList';
// $route['survey/get-subdivs'] = 'SurveyController/subdivisiondetails';
// $route['survey/get-circles'] = 'SurveyController/circledetails';
// $route['survey/get-mouzas'] = 'SurveyController/mouzadetails';
// $route['survey/get-lots'] = 'SurveyController/lotdetails';
// $route['survey/get-villages'] = 'SurveyController/villagedetails';
// $route['survey/(:any)/(:any)/fetch-map'] = 'SurveyController/showMap/$1/$2';

// // GIS QAQC
// $route['gis/qa_qc/villages'] = 'Qaqc/GisQaqcController/index';
// $route['gis/qa_qc/(:any)/daily-upload-report/save'] = 'Qaqc/GisQaqcController/uploadDailyProgress/$1';
// $route['gis/qa_qc/(:any)/report-update'] = 'Qaqc/GisQaqcController/updateReport/$1';
// $route['gis/qa_qc/(:any)/mark-complete'] = 'Qaqc/GisQaqcController/markCompleteReport/$1';

// $route['qa_qc/villages'] = 'Qaqc/SpmuQaqcController/index';
// $route['qa_qc/survey-village/(:any)/revert'] = 'Qaqc/SpmuQaqcController/revert/$1';

// $route['re-assign/supervisor'] = 'ReassignSupervisorController/index';
// $route['re-assign/supervisor/get-assigned-module'] = 'ReassignSupervisorController/getAssignedModule';


// $route['nc_process_track'] = 'nc_village/NcVillageReportController/index_track';

// $route['gis/circle-assign'] = 'GisCircleAssignController/index';
// $route['gis/circle-assign-get-sub-div'] = 'GisCircleAssignController/subdivisiondetails';
// $route['gis/circle-assign-get-circle'] = 'GisCircleAssignController/circledetails';
// $route['gis/circle-assign/save'] = 'GisCircleAssignController/saveGisCircle';
// $route['gis/circle-assign/(:any)/delete'] = 'GisCircleAssignController/destroy/$1';

// $route['adhaar-sign-process']     = 'esign/AdhaarSignController/esignProcess';
// $route['adhaar-sign-response']     = 'esign/AdhaarSignController/esignResponse';
// $route['adhaar-sign-success']     = 'esign/AdhaarSignController/esignSuccess';

// $route['adhaar-sign-process-v2']     = 'esign/AdhaarSignController/esignProcessNew';
// $route['adhaar-sign-response-v2']     = 'esign/AdhaarSignController/esignResponseNew';
// $route['adhaar-sign-success-v2']     = 'esign/AdhaarSignController/esignSuccessNew';

// $route['survey-users'] = 'SurveyUserController/index';
// $route['survey-user/(:any)/edit'] = 'SurveyUserController/edit/$1';
// $route['survey-user/(:any)/update'] = 'SurveyUserController/update/$1';

// //API
// $route['dag-count'] = 'Api/DagController/dagCount';
// $route['dag-count/(:any)'] = 'Api/DagController/dagCount/$1';
// $route['dag-count/(:any)/(:any)'] = 'Api/DagController/dagCount/$1/$2';
// $route['dag-count/(:any)/(:any)/(:any)'] = 'Api/DagController/dagCount/$1/$2/$3';
// $route['dag-count/(:any)/(:any)/(:any)/(:any)'] = 'Api/DagController/dagCount/$1/$2/$3/$4';
// $route['get-circles/(:any)/(:any)'] = 'Api/DagController/getCircles/$1/$2';
// $route['get-mouzas/(:any)/(:any)/(:any)'] = 'Api/DagController/getMouzas/$1/$2/$3';
// $route['get-lots/(:any)/(:any)/(:any)/(:any)'] = 'Api/DagController/getLots/$1/$2/$3/$4';
// $route['get-villages'] = 'Api/DagController/getVillages';
// $route['get-district'] = 'Api/DagController/getDisticts';
// $route['get-subdiv/(:any)'] = 'Api/DagController/getSubdivs/$1';





