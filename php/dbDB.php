<?
// работа с базой данных
//  dbDB.php?enter=show_table&param=msg_2&begin=22
//  enter=show_db / show_table  вид запроса
//  param=msg_2     название таблицы
//  begin=22		начало диапазона LIMIT


require_once('morf_confg.php');	// "конфигурационный" файл проекта	
require_once('morf_functions.php');	// "конфигурационный" файл проекта	

//	echo ( '<!DOCTYPE html><html lang="ru"><head><meta http-equiv="Content-type" content="text/html; charset=UTF-8" /></head><body>');

/**/
	header( $csp );
	header( $json_type );	 
	header( $Acs_ctrl );

$morfDBlink = OpenDB();

//	$Json['repl'] = '';
//	$Json['repl'] = "\n enter=".$_POST['enter']."; \nlogin=".$_POST['login'].";<br>\n" ;		
//			$Json["TABLES"] = show_DB( );

	$INP = inPUT( )	;	// взяли исходные данные там, где их дали	
	$Json['entr'] 	= $INP['enter'] ?? 'show_db';
		

	switch ( $Json['entr'] ) {
		case "show_db":
			$Json["TABLES"] = show_DB( );
		break;	// case "show_db":
		case "show_table":
			$Json["param"] = $INP['param'];
		//	$limit = LIMIT_PAG ;
		if( isset( $INP['lim'] ) ) {
				$limit = $INP['lim'] ;
			} else {
				$limit = LIMIT_PAG ;
			}
		/*	*/	if( isset( $INP['begin'] ) ) {
				$limit = $INP['begin'] . ', ' . $limit ;
			}
			$Json["TABLES"] = show_table( $INP['param'], $limit );
		break;	// case "show_db":
	} //  switch ( $Json['entr']] ) 

//	print_r( $Json );

	$j = out_json( $Json ); 

	echo $j;

?>
