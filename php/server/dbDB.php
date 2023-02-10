<?
// работа с базой данных
//

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
			$Json["TABLES"] = show_DB( );
		
	if( isset( $_POST['enter']) ){
		$Json['enter'] 		= $_POST['enter'];
	} else {
		$Json['enter'] 		= 'show_db';
	}	

	switch ( $Json['enter'] ) {
		case "show_db":
			$Json["TABLES"] = show_DB( );
		break;	// case "show_db":
	} //  switch ( $_POST['enter'] ) 

$j = out_json( $Json ); 

?>
