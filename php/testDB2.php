<?
// работа с базой данных
//

	$json_type = "Content-Type: application/json";
	$csp = "Content-Security-Policy-Report-Only: "
       . "default-src 'self'; "
       . "img-src 'self'; ";
	$Acs_ctrl = "Access-Control-Allow-Origin: *";


	echo ( '<!DOCTYPE html><html lang="ru"><head><meta http-equiv="Content-type" content="text/html; charset=UTF-8" /></head><body>');
	echo ('<pre>');
/*

	header( $csp );
	header( $json_type );	 
	header( $Acs_ctrl );
*/

	// база данных	
	define ('HOST',			"localhost");
	define ('DB_USER',		"walks_admin");
	define ('DB_PASSWORD',	"walks_admin_123");
	define ('DB',			"walks");
	

	function OpenDB(){
		global $curDBlink ;		// дескриптор соединения с базой
		$curDBlink = mysqli_connect( HOST, DB_USER, DB_PASSWORD, DB );
		mysqli_set_charset( $curDBlink, 'utf8');
			if ( !$curDBlink ) die("Error");
		return $curDBlink;
	};
	
	function CloseDB( $curDBlink ){
		mysqli_close($curDBlink);
	};

	function select( $par = '*' ) {  // запрос select
		global $curDBlink ;	// дескриптор соединения с базой
 		$query = "select ".$par ;
		$result  =  mysqli_query( $curDBlink,  $query );
		if ( !$result ) { 
			echo "Произошла ошибка: [".$query."] "  .  mysqli_error(); 
			return 0 ;	
		} else {
			while ( $row = mysqli_fetch_assoc($result)) {
				$res[] = $row ;
			};	
			return $res;
		};			
		return ;		 
	}; 	// function select()  таблицы в базе	

	function show( $par = '*' ) {  // запрос show
		global $curDBlink ;		// дескриптор соединения с базой
 		$query = "show ".$par."; " ;
		$result  =  mysqli_query( $curDBlink,  $query );
		if ( !$result ) { 
			echo "Произошла ошибка [show]: "  .  mysqli_error(); 
			return 0 ;	
		} else {
			return $result;		 
		};
	};

	function show_DB_strct_tbl( $name_tbl) {  // структура таблицы в базе
		$result = show("columns from ".$name_tbl); 
			$i = 0;
		while ( $row_s = mysqli_fetch_assoc($result)) {
			$res[] = $row_s ;
		};	
		return $res;
	};
	
	function show_DB( $lim, $tabl ) {  // таблицы в базе
		$result = show( "TABLE STATUS" ); 
	//	$result = show( "TABLES" ); 
		while ( $row = mysqli_fetch_assoc($result)) {
			$name_tbl = $row['Name'];
			foreach ( $row as $in => $tbl ) {
			//	echo ('  '.$in.' - > '.$tbl.'; ' );
	//			$res[$name_tbl]["param"][$in] = $tbl ;
			}; // foreach ( $row as $in => $tbl )
				// собираем структуру таблицы
	//			$res[$name_tbl]["strct"] = show_DB_strct_tbl( $name_tbl);
				// собираем данные таблицы
				if( $lim > 0 ){
					if( $tabl == '0' ){
						$res[$name_tbl]["data"] = select(" * from ".$name_tbl." LIMIT ".$lim ); 
					} elseif( $name_tbl == $tabl ){
						echo ( "name_tbl == tabl :::" );
						echo ( "[$name_tbl] == [$tabl] <br>" );
		//				$res[$tabl]["data"] = select(" * from ".$tabl." LIMIT ".$lim ); 
		
						$arr = select(" * from ".$tabl." LIMIT ".$lim ); 
						foreach ( $arr as $ar  ){
							print_r( $ar );
							foreach ( $ar as $i => $text ) {
								echo ( "[$text] -" );
								$text = mb_convert_encoding($text, 'utf-8', mb_detect_encoding($text));
								echo ( "--  [$text]" );
								echo ( "<br>" );
								$ar[$i] = $text ;
							}	
							$arrr[] = $ar ;
						}	
						$res[$tabl]["data"] = $arrr; 
						
					};
				};
				
		};			
	//	print_r( $res );
		return $res;		
	};	
	// function show_DB()  таблицы в базе


	global $curDBlink ;		// дескриптор соединения с базой
	$Json = [];

	$Json['enter']['info'] = 	' testDB.php?lim=2&reg=7 - вариант обращения        <br>        '
								.' lim - LIMIT в SELECT        <br>        ' 
								.' tbl - данные только из этой таблицы <br>        ' 
								.' reg - опции json_encode        <br>';
	$Json['enter']['lim']  = $_GET['lim'] ?? 0; // LIMIT в SELECT
	$Json['enter']['reg']  = $_GET['reg'] ?? 0; // опции json_encode
	$Json['enter']['tbl']  = $_GET['tbl'] ?? 0; // таблица для SELECT
	$tabl				   = $_GET['tbl'] ?? 0; // таблица для SELECT


	$morfDBlink = OpenDB();

	$Json["TABLES"] = show_DB( $Json['enter']['lim'], $tabl );

echo ( "<br>" );
echo ( "<hr>" );

	CloseDB( $morfDBlink );

//	print_r ( $Json );

//	echo ( '<hr>');

	switch( $Json['enter']['reg'] ){
		case 0:
			$Json['enter']['encode param'] = '0 - (  )' ;
			$j = json_encode( $Json );
		break;
		case 1:
			$Json['enter']['encode param'] = 'JSON_UNESCAPED_UNICODE' ;
			$j = json_encode( $Json, JSON_UNESCAPED_UNICODE  );
		break;
		case 2:
			$Json['enter']['encode param'] =  'JSON_UNESCAPED_SLASHES' ;
			$j = json_encode( $Json, JSON_UNESCAPED_SLASHES );
		break;
		case 3:
			$Json['enter']['encode param'] = 'JSON_PRETTY_PRINT' ;
			$j = json_encode( $Json,  JSON_PRETTY_PRINT );
		break;
		case 4:
			$Json['enter']['encode param'] =  'JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ' ;
			$j = json_encode( $Json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES   );
		break;
		case 5:
			$Json['enter']['encode param'] =  'JSON_UNESCAPED_UNICODE  | JSON_PRETTY_PRINT' ;
			$j = json_encode( $Json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
		break;
		case 6:
			$Json['enter']['encode param'] =  'JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT' ;
			$j = json_encode( $Json,  JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
		break;
		case 7:
			$Json['enter']['encode param'] = 'JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT' ;
			$j = json_encode( $Json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
		break;
	};
/*
//echo ( '<hr>');
// print_r  ( $j );
*/

echo ( "json_last_error" );
echo ( "<br>" );
echo ( json_last_error_msg() );
echo ( "<br>" );

echo ( $j );
echo ( $Json );
/*echo ( "/n/b" );
echo ( '\n\b' );
*/
echo ('<pre>');
echo ( '<br>end<br><br>');
echo ( '<hr>');


?>
