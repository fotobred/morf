<?
// работа с базой данных  :: запрос ВСЕХ данных о теме
//  slctDB_smpl.php?tema=2 - вариант обращения  
//					tema=2 - id темы

require_once('morf_confg.php');	// "конфигурационный" файл проекта	
require_once('morf_functions.php');	// "конфигурационный" файл проекта	
/*
	echo ( '<!DOCTYPE html><html lang="ru"><head><meta http-equiv="Content-type" content="text/html; charset=UTF-8" /></head><body>');
	echo ('<pre>');
*/

	header( $csp );
	header( $json_type );	 
	header( $Acs_ctrl );

	global $curDBlink ;		// дескриптор соединения с базой
	$Json = [];

	$Json['enter']['info'] = 	' slctDB_smpl?tema=2 - вариант обращения       <br>        '
								.' tema=2 - id темы'
								.' pict=183 - id картинки'
								.'<br> lang=570 - язык ответа';
								
	$INP = inPUT( )	;	// взяли исходные данные там, где их дали

//	разобрали данные
	$Json['enter']['tema']	  = censor( $INP['tema'] ?? 1 );   // тема задана или 1 (по умолчанию)
	$Json['enter']['lang']	  = censor( $INP['lang'] ?? 570 );   // тема задана или 1 (по умолчанию)
//	$Json['enter']['select'] = " * FROM relations WHERE Id = ".$Json['enter']['tema']." limit 111";  // запрос

	$curDBlink = OpenDB();
//		$Json["SELECT"] = select( $Json['enter']['select'] );
	
		$Json["Tema"]["msg_1"]   = select_one( " text FROM msg_1 WHERE lang = ".$Json['enter']['lang']." AND msg_1_id = ".$Json['enter']['tema']." limit 1" );  // запрос
		$Json["Tema"]["msg_2"]   = select_one( " text FROM msg_2 WHERE lang = ".$Json['enter']['lang']." AND msg_2_id = ".$Json['enter']['tema']." limit 1" );  // запрос
		$Json["Tema"]["msg_3"]   = select_one( " text FROM msg_3 WHERE lang = ".$Json['enter']['lang']." AND msg_3_id = ".$Json['enter']['tema']." limit 1" );  // запрос
		$Json["Tema"]["pict"]["id"] = select_one( " child_id FROM relations WHERE parent_id  = ".$Json['enter']['tema']." AND child_type=2 limit 1" );  // запрос
		$Json["Tema"]["pict"]    = select( " * FROM pict WHERE id  = ".$Json["Tema"]["pict"]["id"]." limit 1" );  // запрос
		$Json["Tema"]["pict"]["path"]= select_one( " path FROM path WHERE id = ".$Json["Tema"]["pict"]["path"]." limit 1" );  // запрос
		$Json["Tema"]["pict"]["msg_1"] = select_one( " text FROM msg_1 WHERE lang = ".$Json['enter']['lang']." AND msg_1_id = ".$Json["Tema"]["pict"]["msg_1"]." limit 1" );  // запрос
		$Json["Tema"]["pict"]["msg_2"] = select_one( " text FROM msg_2 WHERE lang = ".$Json['enter']['lang']." AND msg_2_id = ".$Json["Tema"]["pict"]["msg_2"]." limit 1" );  // запрос
		$Json["Tema"]["pict"]["msg_3"] = select_one( " text FROM msg_3 WHERE lang = ".$Json['enter']['lang']." AND msg_3_id = ".$Json["Tema"]["pict"]["msg_3"]." limit 1" );  // запрос
/*	*/	
		$Json["Tema"]["param"]   = select( " * FROM relations WHERE Id = ".$Json['enter']['tema']." limit 111" );  // запрос
		$Json["parent"]			 = select( " * FROM relations WHERE child_id  = ".$Json['enter']['tema']." AND "."child_type  = 1 "." limit 111" );  // запрос
		$Json["child"]			 = select( " * FROM relations WHERE parent_id = ".$Json['enter']['tema']." limit 111" );  // запрос


	CloseDB( $curDBlink );

	$j = out_json( $Json ); 

	echo ( $j );

/*
echo ( '<hr>');
print_r  ( $j );
print_r ( $Json );
echo ( "<br>" );
echo ( "<hr>" );
echo ( $Json );
echo ( '\n\b' );
echo ('<pre>');
echo ( '<br>end<br><br>');
echo ( '<hr>');
*/


?>
