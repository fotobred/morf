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

	$Json['enter']['info'] = 	' slctDB_smpl?tema=270&lang=570 - вариант обращения       <br>        '
								.' tema=2 - id темы'
								.' pict=183 - id картинки'
								.'<br> lang=570 - язык ответа';
								
	$INP = inPUT( )	;	// взяли исходные данные там, где их дали

//	разобрали данные
	$Json['enter']['tema']	  = censor( $INP['tema'] ?? 1 );   		// тема задана или 1 (по умолчанию)
	$Json['enter']['lang']	  = censor( $INP['lang'] ?? 570 );   	// язык задана или 570 ( русский по умолчанию)
//	$Json['enter']['select'] = " * FROM relations WHERE Id = ".$Json['enter']['tema']." limit 111";  // запрос

	$curDBlink = OpenDB();
 
		
 /*	
	
			*
			 from relations AS r 
			 join tema AS t 		 ON t.id_tema = r.parent_id 
			 left join msg_1 AS m1   ON m1.id_msg_1   = t.msg_1
			 left join msg_2 AS m2   ON m2.id_msg_2   = t.msg_2
			 left join msg_3 AS m3   ON m3.id_msg_3   = t.msg_3
			 left join path  AS t_p  ON t_p.id_path     = t.path
			 left join pict  AS i    ON i.id_pict     = t.pict
			 left join path  AS i_p  ON i_p.id_path   = i.path

			 left join msg_1 AS i_m1 ON i_m1.id_msg_1 = i.pict_msg_1
			 left join msg_2 AS i_m2 ON i_m2.id_msg_2 = i.pict_msg_2
			 left join msg_3 AS i_m3 ON i_m3.id_msg_3 = i.pict_msg_3

			 where parent_id = 271 AND   child_type = 2 AND parent_type = 1 limit 111

	*	 from relations AS r 
			 where parent_id = 271 AND   r.child_type = 2 AND r.parent_type = 1 limit 111		
			 
*/			 
		$Json["pict"] = select( " 
		*	 from relations       AS r 
			 left join pict       AS p 	  ON p.id_pict     = r.child_id 
			 left join path       AS i_p  ON i_p.id_path   = p.path
			 left join msg_1      AS i_m1 ON i_m1.id_msg_1 = p.pict_msg_1
			 left join msg_2      AS i_m2 ON i_m2.id_msg_2 = p.pict_msg_2
			 left join msg_3      AS i_m3 ON i_m3.id_msg_3 = p.pict_msg_3	
			 left join tema_type  AS type ON type.id_tema_type = r.child_type
			 where parent_id = 271 AND   r.child_type = 2 AND r.parent_type = 1 limit 3			 
			 

		" );  // запрос - тема "pict"



	CloseDB( $curDBlink );

	$j = out_json( $Json ); 

	echo ( $j );



?>
