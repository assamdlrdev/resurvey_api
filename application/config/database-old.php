<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF postgres
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record postgres lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = '10.177.7.147';
$db['default']['username'] = 'postgres';
$db['default']['password'] = 'postgres';
$db['default']['database'] = 'locmaster';
$db['default']['dbdriver'] = 'postgre';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;
$db['default']['port'] = 5432;

$db['lsp1']['hostname'] = '127.0.0.1';
$db['lsp1']['username'] = 'postgres';
$db['lsp1']['password'] = 'postgres';
$db['lsp1']['database'] = 'barpeta';
$db['lsp1']['dbdriver'] = 'postgre';
$db['lsp1']['dbprefix'] = '';
$db['lsp1']['pconnect'] = FALSE;
$db['lsp1']['db_debug'] = TRUE;
$db['lsp1']['cache_on'] = FALSE;
$db['lsp1']['cachedir'] = '';
$db['lsp1']['char_set'] = 'utf8';
$db['lsp1']['dbcollat'] = 'utf8_general_ci';
$db['lsp1']['swap_pre'] = '';
$db['lsp1']['autoinit'] = TRUE;
$db['lsp1']['stricton'] = FALSE;
$db['lsp1']['port'] = 5432;

$db['lsp2']['hostname'] = '127.0.0.1';
$db['lsp2']['username'] = 'postgres';
$db['lsp2']['password'] = 'postgres';
$db['lsp2']['database'] = 'bongaigaon';
$db['lsp2']['dbdriver'] = 'postgre';
$db['lsp2']['dbprefix'] = '';
$db['lsp2']['pconnect'] = FALSE;
$db['lsp2']['db_debug'] = TRUE;
$db['lsp2']['cache_on'] = FALSE;
$db['lsp2']['cachedir'] = '';
$db['lsp2']['char_set'] = 'utf8';
$db['lsp2']['dbcollat'] = 'utf8_general_ci';
$db['lsp2']['swap_pre'] = '';
$db['lsp2']['autoinit'] = TRUE;
$db['lsp2']['stricton'] = FALSE;
$db['lsp2']['port'] = 5432;

$db['lsp3']['hostname'] = '127.0.0.1';
$db['lsp3']['username'] = 'postgres';
$db['lsp3']['password'] = 'postgres';
$db['lsp3']['database'] = 'dhubri';
$db['lsp3']['dbdriver'] = 'postgre';
$db['lsp3']['dbprefix'] = '';
$db['lsp3']['pconnect'] = FALSE;
$db['lsp3']['db_debug'] = TRUE;
$db['lsp3']['cache_on'] = FALSE;
$db['lsp3']['cachedir'] = '';
$db['lsp3']['char_set'] = 'utf8';
$db['lsp3']['dbcollat'] = 'utf8_general_ci';
$db['lsp3']['swap_pre'] = '';
$db['lsp3']['autoinit'] = TRUE;
$db['lsp3']['stricton'] = FALSE;
$db['lsp3']['port'] = 5432;

$db['lsp4']['hostname'] = '10.177.7.147';
$db['lsp4']['username'] = 'postgres';
$db['lsp4']['password'] = 'postgres';
$db['lsp4']['database'] = 'cdibrugarh';
$db['lsp4']['dbdriver'] = 'postgre';
$db['lsp4']['dbprefix'] = '';
$db['lsp4']['pconnect'] = FALSE;
$db['lsp4']['db_debug'] = TRUE;
$db['lsp4']['cache_on'] = FALSE;
$db['lsp4']['cachedir'] = '';
$db['lsp4']['char_set'] = 'utf8';
$db['lsp4']['dbcollat'] = 'utf8_general_ci';
$db['lsp4']['swap_pre'] = '';
$db['lsp4']['autoinit'] = TRUE;
$db['lsp4']['stricton'] = FALSE;
$db['lsp4']['port'] = 5432;

$db['lsp5']['hostname'] = '127.0.0.1';
$db['lsp5']['username'] = 'postgres';
$db['lsp5']['password'] = 'postgres';
$db['lsp5']['database'] = 'cjorhat';
$db['lsp5']['dbdriver'] = 'postgre';
$db['lsp5']['dbprefix'] = '';
$db['lsp5']['pconnect'] = FALSE;
$db['lsp5']['db_debug'] = TRUE;
$db['lsp5']['cache_on'] = FALSE;
$db['lsp5']['cachedir'] = '';
$db['lsp5']['char_set'] = 'utf8';
$db['lsp5']['dbcollat'] = 'utf8_general_ci';
$db['lsp5']['swap_pre'] = '';
$db['lsp5']['autoinit'] = TRUE;
$db['lsp5']['stricton'] = FALSE;
$db['lsp5']['port'] = 5432;

$db['lsp6']['hostname'] = '127.0.0.1';
$db['lsp6']['username'] = 'postgres';
$db['lsp6']['password'] = 'postgres';
$db['lsp6']['database'] = 'golaghat';
$db['lsp6']['dbdriver'] = 'postgre';
$db['lsp6']['dbprefix'] = '';
$db['lsp6']['pconnect'] = FALSE;
$db['lsp6']['db_debug'] = TRUE;
$db['lsp6']['cache_on'] = FALSE;
$db['lsp6']['cachedir'] = '';
$db['lsp6']['char_set'] = 'utf8';
$db['lsp6']['dbcollat'] = 'utf8_general_ci';
$db['lsp6']['swap_pre'] = '';
$db['lsp6']['autoinit'] = TRUE;
$db['lsp6']['stricton'] = FALSE;
$db['lsp6']['port'] = 5432;

$db['lsp7']['hostname'] = '127.0.0.1';
$db['lsp7']['username'] = 'postgres';
$db['lsp7']['password'] = 'postgres';
$db['lsp7']['database'] = 'kamrup';
$db['lsp7']['dbdriver'] = 'postgre';
$db['lsp7']['dbprefix'] = '';
$db['lsp7']['pconnect'] = FALSE;
$db['lsp7']['db_debug'] = TRUE;
$db['lsp7']['cache_on'] = FALSE;
$db['lsp7']['cachedir'] = '';
$db['lsp7']['char_set'] = 'utf8';
$db['lsp7']['dbcollat'] = 'utf8_general_ci';
$db['lsp7']['swap_pre'] = '';
$db['lsp7']['autoinit'] = TRUE;
$db['lsp7']['stricton'] = FALSE;
$db['lsp7']['port'] = 5432;

$db['lsp8']['hostname'] = '127.0.0.1';
$db['lsp8']['username'] = 'postgres';
$db['lsp8']['password'] = 'postgres';
$db['lsp8']['database'] = 'goalpara';
$db['lsp8']['dbdriver'] = 'postgre';
$db['lsp8']['dbprefix'] = '';
$db['lsp8']['pconnect'] = FALSE;
$db['lsp8']['db_debug'] = TRUE;
$db['lsp8']['cache_on'] = FALSE;
$db['lsp8']['cachedir'] = '';
$db['lsp8']['char_set'] = 'utf8';
$db['lsp8']['dbcollat'] = 'utf8_general_ci';
$db['lsp8']['swap_pre'] = '';
$db['lsp8']['autoinit'] = TRUE;
$db['lsp8']['stricton'] = FALSE;
$db['lsp8']['port'] = 5432;

$db['lsp9']['hostname'] = '127.0.0.1';
$db['lsp9']['username'] = 'postgres';
$db['lsp9']['password'] = 'postgres';
$db['lsp9']['database'] = 'tinsukia';
$db['lsp9']['dbdriver'] = 'postgre';
$db['lsp9']['dbprefix'] = '';
$db['lsp9']['pconnect'] = FALSE;
$db['lsp9']['db_debug'] = TRUE;
$db['lsp9']['cache_on'] = FALSE;
$db['lsp9']['cachedir'] = '';
$db['lsp9']['char_set'] = 'utf8';
$db['lsp9']['dbcollat'] = 'utf8_general_ci';
$db['lsp9']['swap_pre'] = '';
$db['lsp9']['autoinit'] = TRUE;
$db['lsp9']['stricton'] = FALSE;
$db['lsp9']['port'] = 5432;

$db['lsp10']['hostname'] = '127.0.0.1';
$db['lsp10']['username'] = 'postgres';
$db['lsp10']['password'] = 'postgres';
$db['lsp10']['database'] = 'ckamrupm';
$db['lsp10']['dbdriver'] = 'postgre';
$db['lsp10']['dbprefix'] = '';
$db['lsp10']['pconnect'] = FALSE;
$db['lsp10']['db_debug'] = TRUE;
$db['lsp10']['cache_on'] = FALSE;
$db['lsp10']['cachedir'] = '';
$db['lsp10']['char_set'] = 'utf8';
$db['lsp10']['dbcollat'] = 'utf8_general_ci';
$db['lsp10']['swap_pre'] = '';
$db['lsp10']['autoinit'] = TRUE;
$db['lsp10']['stricton'] = FALSE;
$db['lsp10']['port'] = 5432;

$db['lsp11']['hostname'] = '127.0.0.1';
$db['lsp11']['username'] = 'postgres';
$db['lsp11']['password'] = 'postgres';
$db['lsp11']['database'] = 'nalbari';
$db['lsp11']['dbdriver'] = 'postgre';
$db['lsp11']['dbprefix'] = '';
$db['lsp11']['pconnect'] = FALSE;
$db['lsp11']['db_debug'] = TRUE;
$db['lsp11']['cache_on'] = FALSE;
$db['lsp11']['cachedir'] = '';
$db['lsp11']['char_set'] = 'utf8';
$db['lsp11']['dbcollat'] = 'utf8_general_ci';
$db['lsp11']['swap_pre'] = '';
$db['lsp11']['autoinit'] = TRUE;
$db['lsp11']['stricton'] = FALSE;
$db['lsp11']['port'] = 5432;

$db['lsp12']['hostname'] = '127.0.0.1';
$db['lsp12']['username'] = 'postgres';
$db['lsp12']['password'] = 'postgres';
$db['lsp12']['database'] = 'sonitpur';
$db['lsp12']['dbdriver'] = 'postgre';
$db['lsp12']['dbprefix'] = '';
$db['lsp12']['pconnect'] = FALSE;
$db['lsp12']['db_debug'] = TRUE;
$db['lsp12']['cache_on'] = FALSE;
$db['lsp12']['cachedir'] = '';
$db['lsp12']['char_set'] = 'utf8';
$db['lsp12']['dbcollat'] = 'utf8_general_ci';
$db['lsp12']['swap_pre'] = '';
$db['lsp12']['autoinit'] = TRUE;
$db['lsp12']['stricton'] = FALSE;
$db['lsp12']['port'] = 5432;

$db['lsp13']['hostname'] = '127.0.0.1';
$db['lsp13']['username'] = 'postgres';
$db['lsp13']['password'] = 'postgres';
$db['lsp13']['database'] = 'lakhimpur';
$db['lsp13']['dbdriver'] = 'postgre';
$db['lsp13']['dbprefix'] = '';
$db['lsp13']['pconnect'] = FALSE;
$db['lsp13']['db_debug'] = TRUE;
$db['lsp13']['cache_on'] = FALSE;
$db['lsp13']['cachedir'] = '';
$db['lsp13']['char_set'] = 'utf8';
$db['lsp13']['dbcollat'] = 'utf8_general_ci';
$db['lsp13']['swap_pre'] = '';
$db['lsp13']['autoinit'] = TRUE;
$db['lsp13']['stricton'] = FALSE;
$db['lsp13']['port'] = 5432;

$db['lsp14']['hostname'] = '127.0.0.1';
$db['lsp14']['username'] = 'postgres';
$db['lsp14']['password'] = 'postgres';
$db['lsp14']['database'] = 'sibsagar';
$db['lsp14']['dbdriver'] = 'postgre';
$db['lsp14']['dbprefix'] = '';
$db['lsp14']['pconnect'] = FALSE;
$db['lsp14']['db_debug'] = TRUE;
$db['lsp14']['cache_on'] = FALSE;
$db['lsp14']['cachedir'] = '';
$db['lsp14']['char_set'] = 'utf8';
$db['lsp14']['dbcollat'] = 'utf8_general_ci';
$db['lsp14']['swap_pre'] = '';
$db['lsp14']['autoinit'] = TRUE;
$db['lsp14']['stricton'] = FALSE;
$db['lsp14']['port'] = 5432;

$db['lsp15']['hostname'] = '127.0.0.1';
$db['lsp15']['username'] = 'postgres';
$db['lsp15']['password'] = 'postgres';
$db['lsp15']['database'] = 'morigaon';
$db['lsp15']['dbdriver'] = 'postgre';
$db['lsp15']['dbprefix'] = '';
$db['lsp15']['pconnect'] = FALSE;
$db['lsp15']['db_debug'] = TRUE;
$db['lsp15']['cache_on'] = FALSE;
$db['lsp15']['cachedir'] = '';
$db['lsp15']['char_set'] = 'utf8';
$db['lsp15']['dbcollat'] = 'utf8_general_ci';
$db['lsp15']['swap_pre'] = '';
$db['lsp15']['autoinit'] = TRUE;
$db['lsp15']['stricton'] = FALSE;
$db['lsp15']['port'] = 5432;

$db['lsp16']['hostname'] = '127.0.0.1';
$db['lsp16']['username'] = 'postgres';
$db['lsp16']['password'] = 'postgres';
$db['lsp16']['database'] = 'nagaon';
$db['lsp16']['dbdriver'] = 'postgre';
$db['lsp16']['dbprefix'] = '';
$db['lsp16']['pconnect'] = FALSE;
$db['lsp16']['db_debug'] = TRUE;
$db['lsp16']['cache_on'] = FALSE;
$db['lsp16']['cachedir'] = '';
$db['lsp16']['char_set'] = 'utf8';
$db['lsp16']['dbcollat'] = 'utf8_general_ci';
$db['lsp16']['swap_pre'] = '';
$db['lsp16']['autoinit'] = TRUE;
$db['lsp16']['stricton'] = FALSE;
$db['lsp16']['port'] = 5432;

$db['lsp17']['hostname'] = '127.0.0.1';
$db['lsp17']['username'] = 'postgres';
$db['lsp17']['password'] = 'postgres';
$db['lsp17']['database'] = 'majuli';
$db['lsp17']['dbdriver'] = 'postgre';
$db['lsp17']['dbprefix'] = '';
$db['lsp17']['pconnect'] = FALSE;
$db['lsp17']['db_debug'] = TRUE;
$db['lsp17']['cache_on'] = FALSE;
$db['lsp17']['cachedir'] = '';
$db['lsp17']['char_set'] = 'utf8';
$db['lsp17']['dbcollat'] = 'utf8_general_ci';
$db['lsp17']['swap_pre'] = '';
$db['lsp17']['autoinit'] = TRUE;
$db['lsp17']['stricton'] = FALSE;
$db['lsp17']['port'] = 5432;

$db['lsp18']['hostname'] = '127.0.0.1';
$db['lsp18']['username'] = 'postgres';
$db['lsp18']['password'] = 'postgres';
$db['lsp18']['database'] = 'karimganj';
$db['lsp18']['dbdriver'] = 'postgre';
$db['lsp18']['dbprefix'] = '';
$db['lsp18']['pconnect'] = FALSE;
$db['lsp18']['db_debug'] = TRUE;
$db['lsp18']['cache_on'] = FALSE;
$db['lsp18']['cachedir'] = '';
$db['lsp18']['char_set'] = 'utf8';
$db['lsp18']['dbcollat'] = 'utf8_general_ci';
$db['lsp18']['swap_pre'] = '';
$db['lsp18']['autoinit'] = TRUE;
$db['lsp18']['stricton'] = FALSE;
$db['lsp18']['port'] = 5432;

$db['lsp19']['hostname'] = '127.0.0.1';
$db['lsp19']['username'] = 'postgres';
$db['lsp19']['password'] = 'postgres';
$db['lsp19']['database'] = 'darrang';
$db['lsp19']['dbdriver'] = 'postgre';
$db['lsp19']['dbprefix'] = '';
$db['lsp19']['pconnect'] = FALSE;
$db['lsp19']['db_debug'] = TRUE;
$db['lsp19']['cache_on'] = FALSE;
$db['lsp19']['cachedir'] = '';
$db['lsp19']['char_set'] = 'utf8';
$db['lsp19']['dbcollat'] = 'utf8_general_ci';
$db['lsp19']['swap_pre'] = '';
$db['lsp19']['autoinit'] = TRUE;
$db['lsp19']['stricton'] = FALSE;
$db['lsp19']['port'] = 5432;

$db['lsp20']['hostname'] = '127.0.0.1';
$db['lsp20']['username'] = 'postgres';
$db['lsp20']['password'] = 'postgres';
$db['lsp20']['database'] = 'biswanath';
$db['lsp20']['dbdriver'] = 'postgre';
$db['lsp20']['dbprefix'] = '';
$db['lsp20']['pconnect'] = FALSE;
$db['lsp20']['db_debug'] = TRUE;
$db['lsp20']['cache_on'] = FALSE;
$db['lsp20']['cachedir'] = '';
$db['lsp20']['char_set'] = 'utf8';
$db['lsp20']['dbcollat'] = 'utf8_general_ci';
$db['lsp20']['swap_pre'] = '';
$db['lsp20']['autoinit'] = TRUE;
$db['lsp20']['stricton'] = FALSE;
$db['lsp20']['port'] = 5432;

$db['lsp21']['hostname'] = '127.0.0.1';
$db['lsp21']['username'] = 'postgres';
$db['lsp21']['password'] = 'postgres';
$db['lsp21']['database'] = 'hojai';
$db['lsp21']['dbdriver'] = 'postgre';
$db['lsp21']['dbprefix'] = '';
$db['lsp21']['pconnect'] = FALSE;
$db['lsp21']['db_debug'] = TRUE;
$db['lsp21']['cache_on'] = FALSE;
$db['lsp21']['cachedir'] = '';
$db['lsp21']['char_set'] = 'utf8';
$db['lsp21']['dbcollat'] = 'utf8_general_ci';
$db['lsp21']['swap_pre'] = '';
$db['lsp21']['autoinit'] = TRUE;
$db['lsp21']['stricton'] = FALSE;
$db['lsp21']['port'] = 5432;

$db['lsp22']['hostname'] = '127.0.0.1';
$db['lsp22']['username'] = 'postgres';
$db['lsp22']['password'] = 'postgres';
$db['lsp22']['database'] = 'ckamrupm';
$db['lsp22']['dbdriver'] = 'postgre';
$db['lsp22']['dbprefix'] = '';
$db['lsp22']['pconnect'] = FALSE;
$db['lsp22']['db_debug'] = TRUE;
$db['lsp22']['cache_on'] = FALSE;
$db['lsp22']['cachedir'] = '';
$db['lsp22']['char_set'] = 'utf8';
$db['lsp22']['dbcollat'] = 'utf8_general_ci';
$db['lsp22']['swap_pre'] = '';
$db['lsp22']['autoinit'] = TRUE;
$db['lsp22']['stricton'] = FALSE;
$db['lsp22']['port'] = 5432;

$db['lsp23']['hostname'] = '127.0.0.1';
$db['lsp23']['username'] = 'postgres';
$db['lsp23']['password'] = 'postgres';
$db['lsp23']['database'] = 'dhemaji';
$db['lsp23']['dbdriver'] = 'postgre';
$db['lsp23']['dbprefix'] = '';
$db['lsp23']['pconnect'] = FALSE;
$db['lsp23']['db_debug'] = TRUE;
$db['lsp23']['cache_on'] = FALSE;
$db['lsp23']['cachedir'] = '';
$db['lsp23']['char_set'] = 'utf8';
$db['lsp23']['dbcollat'] = 'utf8_general_ci';
$db['lsp23']['swap_pre'] = '';
$db['lsp23']['autoinit'] = TRUE;
$db['lsp23']['stricton'] = FALSE;
$db['lsp23']['port'] = 5432;

$db['lsp24']['hostname'] = '127.0.0.1';
$db['lsp24']['username'] = 'postgres';
$db['lsp24']['password'] = 'postgres';
$db['lsp24']['database'] = 'cchirang';
$db['lsp24']['dbdriver'] = 'postgre';
$db['lsp24']['dbprefix'] = '';
$db['lsp24']['pconnect'] = FALSE;
$db['lsp24']['db_debug'] = TRUE;
$db['lsp24']['cache_on'] = FALSE;
$db['lsp24']['cachedir'] = '';
$db['lsp24']['char_set'] = 'utf8';
$db['lsp24']['dbcollat'] = 'utf8_general_ci';
$db['lsp24']['swap_pre'] = '';
$db['lsp24']['autoinit'] = TRUE;
$db['lsp24']['stricton'] = FALSE;
$db['lsp24']['port'] = 5432;

$db['lsp25']['hostname'] = '127.0.0.1';
$db['lsp25']['username'] = 'postgres';
$db['lsp25']['password'] = 'postgres';
$db['lsp25']['database'] = 'ssalmara';
$db['lsp25']['dbdriver'] = 'postgre';
$db['lsp25']['dbprefix'] = '';
$db['lsp25']['pconnect'] = FALSE;
$db['lsp25']['db_debug'] = TRUE;
$db['lsp25']['cache_on'] = FALSE;
$db['lsp25']['cachedir'] = '';
$db['lsp25']['char_set'] = 'utf8';
$db['lsp25']['dbcollat'] = 'utf8_general_ci';
$db['lsp25']['swap_pre'] = '';
$db['lsp25']['autoinit'] = TRUE;
$db['lsp25']['stricton'] = FALSE;
$db['lsp25']['port'] = 5432;

$db['lsp26']['hostname'] = '127.0.0.1';
$db['lsp26']['username'] = 'postgres';
$db['lsp26']['password'] = 'postgres';
$db['lsp26']['database'] = 'bajali';
$db['lsp26']['dbdriver'] = 'postgre';
$db['lsp26']['dbprefix'] = '';
$db['lsp26']['pconnect'] = FALSE;
$db['lsp26']['db_debug'] = TRUE;
$db['lsp26']['cache_on'] = FALSE;
$db['lsp26']['cachedir'] = '';
$db['lsp26']['char_set'] = 'utf8';
$db['lsp26']['dbcollat'] = 'utf8_general_ci';
$db['lsp26']['swap_pre'] = '';
$db['lsp26']['autoinit'] = TRUE;
$db['lsp26']['stricton'] = FALSE;
$db['lsp26']['port'] = 5432;

$db['lsp27']['hostname'] = '127.0.0.1';
$db['lsp27']['username'] = 'postgres';
$db['lsp27']['password'] = 'postgres';
$db['lsp27']['database'] = 'chailakandi';
$db['lsp27']['dbdriver'] = 'postgre';
$db['lsp27']['dbprefix'] = '';
$db['lsp27']['pconnect'] = FALSE;
$db['lsp27']['db_debug'] = TRUE;
$db['lsp27']['cache_on'] = FALSE;
$db['lsp27']['cachedir'] = '';
$db['lsp27']['char_set'] = 'utf8';
$db['lsp27']['dbcollat'] = 'utf8_general_ci';
$db['lsp27']['swap_pre'] = '';
$db['lsp27']['autoinit'] = TRUE;
$db['lsp27']['stricton'] = FALSE;
$db['lsp27']['port'] = 5432;

$db['lsp28']['hostname'] = '127.0.0.1';
$db['lsp28']['username'] = 'postgres';
$db['lsp28']['password'] = 'postgres';
$db['lsp28']['database'] = 'ccachar';
$db['lsp28']['dbdriver'] = 'postgre';
$db['lsp28']['dbprefix'] = '';
$db['lsp28']['pconnect'] = FALSE;
$db['lsp28']['db_debug'] = TRUE;
$db['lsp28']['cache_on'] = FALSE;
$db['lsp28']['cachedir'] = '';
$db['lsp28']['char_set'] = 'utf8';
$db['lsp28']['dbcollat'] = 'utf8_general_ci';
$db['lsp28']['swap_pre'] = '';
$db['lsp28']['autoinit'] = TRUE;
$db['lsp28']['stricton'] = FALSE;
$db['lsp28']['port'] = 5432;

$db['lsp29']['hostname'] = '127.0.0.1';
$db['lsp29']['username'] = 'postgres';
$db['lsp29']['password'] = 'postgres';
$db['lsp29']['database'] = 'ckokrajhar';
$db['lsp29']['dbdriver'] = 'postgre';
$db['lsp29']['dbprefix'] = '';
$db['lsp29']['pconnect'] = FALSE;
$db['lsp29']['db_debug'] = TRUE;
$db['lsp29']['cache_on'] = FALSE;
$db['lsp29']['cachedir'] = '';
$db['lsp29']['char_set'] = 'utf8';
$db['lsp29']['dbcollat'] = 'utf8_general_ci';
$db['lsp29']['swap_pre'] = '';
$db['lsp29']['autoinit'] = TRUE;
$db['lsp29']['stricton'] = FALSE;
$db['lsp29']['port'] = 5432;



/* End of file database.php */
/* Location: ./application/config/database.php */