<?PHP
/*
*	конфигурационный файл проекта Morf
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('morf_functions.php');	// "конфигурационный" файл проекта	

	$path_root = '/stdt';
//	$path_root = '/stdt/gb'; 

	$json_type = "";
	$csp = "";
	$Acs_ctrl = "";
	
	$json_type = "Content-Type: application/json";
	$csp = "Content-Security-Policy-Report-Only: "
       . "default-src 'self'; "
       . "img-src 'self'; ";
	$Acs_ctrl = "Access-Control-Allow-Origin: *";



	$Test = 'test' ;
//	$res ='teeest';	  // пока - ни о чём...


	global $curDBlink ;		// дескриптор соединения с базой
	
	// база данных	
		define ('HOST',			"localhost");
		define ('DB_USER',		"walks_admin");
		define ('DB_PASSWORD',	"walks_admin_123");
		define ('DB',			"walks");
		define ('LIMIT_MIN',	22 );
		define ('LIMIT_PAG',	44 );
		define ('LIMIT_MAX',	88 );
	
?>