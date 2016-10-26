<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
* 自定义错误码
*/
defined('ERROR_SUCCESS') OR define('ERROR_SUCCESS', 0);
defined('ERROR_PARAM') OR define('ERROR_PARAM', -1001);
defined('ERROR_USER_PASSWORD') OR define('ERROR_USER_PASSWORD', -1002);
defined('ERROR_NOT_LOGIN') OR define('ERROR_NOT_LOGIN', -1003);
defined('ERROR_MYSQL') OR define('ERROR_MYSQL', -1004);
/*
* 购物车错误码，从-2000开始
*/
defined('ERROR_MAX_CARTCOUNT') OR define('ERROR_MAX_CARTCOUNT', -2000);
defined('ERROR_ALREADY_IN_CART') OR define('ERROR_ALREADY_IN_CART', -2001);

/*
订单错误
*/
defined('ERROR_NO_GOODS')  OR define('ERROR_NO_GOODS', -3000);
defined('ERROR_GOODS_NOT_ENOUGH')  OR define('ERROR_GOODS_NOT_ENOUGH', -3001);
defined('ERROR_USER_ADDRESS')  OR define('ERROR_USER_ADDRESS', -3003);
defined('ERROR_NO_USER_ADDRESS')  OR define('ERROR_NO_USER_ADDRESS', -3004);

/*
* 订单状态
*/
defined('ORDER_STATUS_WAIT_FOR_PAY') OR define('ORDER_STATUS_WAIT_FOR_PAY', 0);//待付款
defined('ORDER_STATUS_WAIT_FOR_SEND') OR define('ORDER_STATUS_WAIT_FOR_SEND', 1);//待发货
defined('ORDER_STATUS_WAIT_FOR_RECV') OR define('ORDER_STATUS_WAIT_FOR_RECV', 2);//待收获
defined('ORDER_STATUS_WAIT_FOR_COMMENT') OR define('ORDER_STATUS_WAIT_FOR_COMMENT', 3);//待评论
defined('ORDER_STATUS_COMMENTED') OR define('ORDER_STATUS_COMMENTED', 4);//已经评论
defined('ORDER_STATUS_WAIT_FOR_CANCEL') OR define('ORDER_STATUS_WAIT_FOR_CANCEL', 5);//待撤销