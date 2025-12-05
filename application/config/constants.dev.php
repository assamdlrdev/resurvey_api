defined('SURVEYED_FILE_UPLOAD_PATH') or define('SURVEYED_FILE_UPLOAD_PATH', 'uploads/survey/');
defined('FINAL_SURVEYED_FILE_UPLOAD_PATH') or define('FINAL_SURVEYED_FILE_UPLOAD_PATH', 'uploads/survey/finaldata/');


defined('ENABLE_FINAL_UPLOAD') or define('ENABLE_FINAL_UPLOAD', FALSE);
defined('SURVEY_REVERT_PENDING') or define('SURVEY_REVERT_PENDING', 0);
defined('SURVEY_REVERT_RESOLVED') or define('SURVEY_REVERT_RESOLVED', 1);

defined('DXF_MAP_SHOW_BASE_URL') or define('DXF_MAP_SHOW_BASE_URL', 'http://127.0.0.1:5000/');
defined('JWT_SECRET') or define('JWT_SECRET', 'asdaffefwefwefwefefewfweasdaffefwefwefwefefewfwefwetbhtrnjtyjtyhwdawefg35y5rhfgbdfgdrgfwetbhtrasdaffefwefwefwefefewfwefwetbhtrnjtyjtyhwdawefg35y5rhfgbdfgdrgnjtyjtyhwdawefg35y5rhfgbdfgdrg');

defined('ENABLE_BHUNAKSHA_DXF_MAP_API') or define('ENABLE_BHUNAKSHA_DXF_MAP_API', FALSE);
defined('SURVEYOR_BHUNAKSHA_DXF_UPLOAD_MAP') or define('SURVEYOR_BHUNAKSHA_DXF_UPLOAD_MAP', FALSE);
defined('ENABLE_DLY_SURVEY_REPORT_EDIT') or define('ENABLE_DLY_SURVEY_REPORT_EDIT', FALSE);
defined('ENABLE_ALL_DLY_SURVEY_REPORT_EDIT') or define('ENABLE_ALL_DLY_SURVEY_REPORT_EDIT', FALSE);
defined('SURVEY_NC_BTAD_VILLAGE_STATUS') or define('SURVEY_NC_BTAD_VILLAGE_STATUS', 'p');
<!-- defined('DEVELOPEMENT_MODE_DISTRICTS') or define('DEVELOPEMENT_MODE_DISTRICTS', ['07', '08', '21', '22', '23']); -->
defined('SURVEY_DISTRICTS') or define('SURVEY_DISTRICTS', ['25', '12', '23', '02', '07', '24', '21', '22', '13', '05']);
defined('SURVEY_REASSIGNMENT_MODULE') or define('SURVEY_REASSIGNMENT_MODULE', FALSE);
defined('SURVEY_SPMU_QAQC_MODULE') or define('SURVEY_SPMU_QAQC_MODULE', FALSE);

defined('COMPLETE_SURVEYOR_SURVEY_BTN_ENABLE') or define('COMPLETE_SURVEYOR_SURVEY_BTN_ENABLE', FALSE);
defined('COMPLETE_GIS_ASSISTANT_QAQC_BTN_ENABLE') or define('COMPLETE_GIS_ASSISTANT_QAQC_BTN_ENABLE', FALSE);

defined('CHITHA_APP_NAME') or define('CHITHA_APP_NAME', 'chithaentry/');
define('ESIGN_ROOT_DIRECTORY',  $_SERVER["DOCUMENT_ROOT"] . "/" . CHITHA_APP_NAME . "esign/");
define('SURVEY_MAX_SIZE', '20480'); // 20MB
