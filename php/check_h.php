<?
// Скрипт проверки
//
//
// Структура таблицы `users` <br>
/*
CREATE TABLE `users` ( 
`user_id` int(11) unsigned NOT NULL auto_increment, 
`user_login` varchar(30) NOT NULL, 
`user_password` varchar(32) NOT NULL, 
`user_hash` varchar(32) NOT NULL, 
`user_ip` int(10) unsigned NOT NULL default '0', 
PRIMARY KEY (`user_id`) 
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;
*/

// require_once('morf_confg.php');	// "конфигурационный" файл проекта	

$json_type = "Content-Type: application/json";
	$csp = "Content-Security-Policy-Report-Only: "
       . "default-src 'self'; "
       . "img-src 'self'; ";

	header($csp);
	header($json_type);	   
	   
echo "begin------------------------<br>" ;
	$Json['repl'] = '';
	$Json['not_ip'] = 'not_ip' ;
	$Json['remote_addr'] = $_SERVER['REMOTE_ADDR'] ;
	$Json['coock_time'] = 1 ;	// теперь в днях time() - 3600*24*30*12 ; - год
	$Json['login']      = '1111';	 
	$Json['password ']  = '1111';
/*	$Json['coock_in']   = $_POST['coock_in'];  
	$Json['coock_id']   = $_POST['coock_id'];  
	$Json['coock_hash'] = $_POST['coock_hash'];
*/

print_r ($Json);

	// база данных	
		define ('HOST',			"localhost");
		define ('DB_USER',		"walks_admin");
		define ('DB_PASSWORD',	"walks_admin_123");
		define ('DB',			"walks");

//	$res ='teeest';	  // пока - ни о чём...
	
	function OpenDB(){
		global $curDBlink ;		// дескриптор соединения с базой
		$curDBlink = mysqli_connect( HOST, DB_USER, DB_PASSWORD, DB );
			if ( !$curDBlink ) die("Error");

//		echo '<br> open DB<hr>';
		return $curDBlink;
	};
	
	
	function CloseDB( $curDBlink ){
		mysqli_close($curDBlink);
	};

	function out_json( $Json ){
		$J = json_encode( $Json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
		print_r ( $J );
//		echo $J ;
		return $J;
	}	

	
$morfDBlink = OpenDB();

# запрашиваем данные по Логину
function query_S($login) {
	global $morfDBlink;
	$q = "SELECT *, INET_NTOA(user_ip) as user_ip_r FROM users WHERE user_login = '".$login."' LIMIT 1";
	$query = mysqli_query($morfDBlink, $q );
	return  mysqli_fetch_assoc($query);	
}	

// Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  
    }
    return $code;
}

// Записываем в БД новый хеш авторизации и IP(Если пользователя выбрал привязку к IP)
function new_hash($userdata){
	global $morfDBlink;
	global $Json;
		// Если пользователя выбрал привязку к IP   		
		if(  $Json['not_ip'] == 'false'  or  $Json['not_ip'] == null ) { 
            // Переводим IP в строку
            $insip = "INET_ATON('".$Json['remote_addr']."')";
			$Json['repl'] = $Json['repl']."\n Сохраняем IP: ". $Json['remote_addr']."  "; 
		} else {		
			$insip = 0;  // без привязки к IP
		}		
        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));
        // Записываем в БД новый хеш авторизации и IP
        $my = "UPDATE users SET user_hash='".$hash."' , user_ip=".$insip." WHERE user_id='".$userdata['user_id']."'" ;
        mysqli_query($morfDBlink, $my );	
	return $hash;
}	//  new_hash($userdata)  

/*	$Json['repl'] = '';
	$Json['not_ip'] = $_POST['not_ip'] ;
	$Json['remote_addr'] = $_SERVER['REMOTE_ADDR'] ;
	$Json['coock_time'] = 1 ;	// теперь в днях time() - 3600*24*30*12 ; - год
	$Json['login']     = $_POST['login'];	 
	$Json['password '] = $_POST['password'];	 
*/
	switch ( $Json['enter'] ) {
		case "enter":
			$Json['enter'] = "enter" ;
			// если введены Логин и Пароль
			if( isset( $Json['login']) and isset( $Json['password']) and !empty( $Json['password']) ) { 
				// запрашиваем данные по Логину
				$userdata = query_S( $Json['login'] );
		//		$Json['userdata'] = $userdata;

				if( $userdata['user_password'] !== md5(md5( $Json['password'])) ) {
				// если введены Логин и Пароль не совпали Или на введеный Логин нет записи
					$Json['status'] = 2 ;
					$Json['repl']   = "Ошибочные  логин и/или пароль";
				} else {
				// если введенные Логин и Пароль совпали
					$Json['status'] = 20 ;
					$Json['repl']   = "Привет, ".$userdata['user_login'].". Вход совершен!";

					//	оформляется Сессия и Куки
					$Json['new_id'] = $userdata['user_id'];
					$Json['new_hash'] = new_hash($userdata);
				
				}
			} // if( isset($_POST['login']) and isset($_POST['password']) and !empty($_POST['password']) ) 
		break;	// case "enter":
		case "cookie":
			if ( !empty( $Json['coock_in']) and !empty( $Json['coock_id']) and !empty( $Json['coock_hash'])){   
				
				// запрашиваем данные по Логину
				$userdata = query_S( $Json['coock_in'] );
				$Json['userdata'] = $userdata;

	/*			$Json['coock_in']   = $_POST['coock_in'];  
				$Json['coock_id']   = $_POST['coock_id'];  
				$Json['coock_hash'] = $_POST['coock_hash'];
*/
				if( $userdata['user_ip'] == 0 ){ $Json['remote_addr'] = 0; }
				if(     ( $userdata['user_hash']  == $Json['coock_hash'] ) 
					and ( $userdata['user_id']    == $Json['coock_id'] ) 
					and (  $userdata['user_ip_r'] == $Json['remote_addr'] )
				)  {
					$Json['status'] = 30 ;
					$Json['repl']   = $Json['repl']."Привет, ".$userdata['user_login'].". Всё работает!<br>";
						//	обновляются Сессия и Куки
						$Json['new_id'] = $userdata['user_id'];
						$Json['new_hash'] = new_hash($userdata);
				}    else    {
					$Json['status'] = 10 ;
					$Json['repl']   = $Json['repl']."Необходимо заново ввести логин и пароль";
					if( $userdata['user_id'] !== $Json['coock_id'] ) {
						$Json['repl']  = $Json['repl']." id ";
				//		$Json['repl']  = $Json['repl']."\n".$userdata['user_id']  ." !== ".$_POST['coock_id'];
					}
					if( $userdata['user_hash'] !== $Json['coock_hash'] ) {
						$Json['repl']  = $Json['repl']." hsh ";
				//		$Json['repl']  = $Json['repl']."\n".$userdata['user_hash']." !== ".$_POST['coock_hash'];
					}
					if( $userdata['user_ip_r'] !== $Json['remote_addr'] ) {
						$Json['repl']  = $Json['repl']." IP ";
				//	$Json['repl']   = $Json['repl']."\n".$userdata['user_ip_r']  ." !== ".$Json['remote_addr'];
					}
				}
			} else {   // if ( !empty($_POST['coock_in']) and !empty($_POST['coock_id']) and !empty($_POST['coock_hash']))
				$Json['status'] = 1 ;
				$Json['repl']   = "Не введены логин и/или пароль";
			}	
		break;	// case "cookie":
		case "register":
			$Json['enter'] = "--- register" ;
			$err = 0;

			# проверям логин
			if(!preg_match("/^[a-zA-Z0-9]+$/",$Json['login']))   {
				$err = $err + 1;
				$Json['repl'] = $Json['repl'] . "\n Логин может состоять только из букв английского алфавита и цифр";
			}

			if(strlen($Json['login']) < 3 or strlen($Json['login']) > 30)   {
				$err = $err + 1;
				$Json['repl'] = $Json['repl'] . "\n Логин должен быть не меньше 3-х символов и не больше 30";
			}
			
			# проверяем, не сущестует ли пользователя с таким именем
			$txt = "SELECT COUNT(user_id)AS col FROM users WHERE user_login='".$Json['login']."'" ;
			$query = mysqli_query( $morfDBlink, $txt );
			$r = mysqli_fetch_assoc($query);

			$Json['r'] = $r['col'];

			if( $r['col'] > 0)   {
				$Json['repl'] = $Json['repl'] . "\n Пользователь с таким логином уже существует в базе данных";
				$err = $err + 1;
			} /* else {
				$Json['repl'] = $Json['repl'] . "\n Пользователя с таким логином нет в базе данных";
			} */

			# Если нет ошибок, то добавляем в БД нового пользователя
			if( $err == 0)   {
				$login = $Json['login'];

				# Убераем лишние пробелы и делаем двойное шифрование
				$password = md5(md5(trim($Json['password'])));

				mysqli_query( $morfDBlink, "INSERT INTO users SET user_login='".$login."', user_password='".$password."'");
				$Json['repl'] = $Json['repl'] . "\n Регистрация прошла успешно";
				$Json['status'] = 100  ;
			} else {
				$Json['repl'] = "При регистрации произошли ошибки".$Json['repl'] ;
				$Json['status'] = 100 + $err ;
			}			
		break;	// case "register":
	} //  switch ( $_POST['enter'] ) 

	CloseDB( $morfDBlink );

	$j = out_json( $Json ); 

	echo ( $j );

?>
