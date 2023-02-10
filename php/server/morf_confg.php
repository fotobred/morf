<?PHP
/*
*	конфигурационный файл проекта Morf
*/
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
	


?>