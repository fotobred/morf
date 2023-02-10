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
		 

		$Json["parent"] = select( " 
			#	 I_em		=	'parent'  ,
				 r.Id_rel			AS rel_Id_rel
				, type.type			AS type 
				, t.parent			AS tema_parent		
				, t.id_tema			AS tema_id
				, r.parent_type		AS rel_parent_type	
				, r.child_type		AS rel_child_type	
				, r.rel_type		AS rel_type	
				, t.id_old			AS tema_id_old		
				, t.dsc				AS tema_dsc			
				, m1.msg_1_text		AS tema_msg_1		
				, m2.msg_2_text		AS tema_msg_2		
				, m3.msg_3_text		AS tema_msg_3		
				, t_p.path			AS tema_path
				, i_m1.msg_1_text	AS pict_msg_1		
				, i_m2.msg_2_text	AS pict_msg_2		
				, i_m3.msg_3_text	AS pict_msg_3		
				, i_p.path 			AS pict_path
				, i.size_s			AS pict_size_s
				, i.size_m			AS pict_size_m
				, i.size_n			AS pict_size_n
				, i.size_g			AS pict_size_g
				, t.date			AS tema_date		
				, t.coords			AS tema_coords		
				, r.rel_order		AS rel_order	
				, t.source			AS tema_source						
				, t.reg_show		AS tema_reg_show	
				, t.map_zoom		AS tema_map_zoom	
	
				
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
			 left join tema_type  AS type ON type.id_tema_type = r.child_type
			 
			 where child_id = " . $Json['enter']['tema'] . "
			 AND   child_type = 1
			 AND   parent_type = 1
			 limit 111		
		" );  // запрос - тема "parent"
		
		$Json["curent"] = select( " 
			#	 I_em		=	'curent' ,
				 r.Id_rel			AS rel_Id_rel
				, type.type			AS type 
				, t.parent			AS tema_parent		
				, t.id_tema			AS tema_id
				, r.parent_type		AS rel_parent_type	
				, r.child_type		AS rel_child_type	
				, r.rel_type		AS rel_type	
				, t.id_old			AS tema_id_old		
				, t.dsc				AS tema_dsc			
				, m1.msg_1_text		AS tema_msg_1		
				, m2.msg_2_text		AS tema_msg_2		
				, m3.msg_3_text		AS tema_msg_3		
				, t_p.path			AS tema_path
				, i_m1.msg_1_text	AS pict_msg_1		
				, i_m2.msg_2_text	AS pict_msg_2		
				, i_m3.msg_3_text	AS pict_msg_3		
				, i_p.path 			AS pict_path
				, i.size_s			AS pict_size_s
				, i.size_m			AS pict_size_m
				, i.size_n			AS pict_size_n
				, i.size_g			AS pict_size_g
				, t.date			AS tema_date		
				, t.coords			AS tema_coords		
				, r.rel_order		AS rel_order	
				, t.source			AS tema_source						
				, t.reg_show		AS tema_reg_show	
				, t.map_zoom		AS tema_map_zoom	
			 from relations AS r 
			 join tema AS t 		 ON t.id_tema = r.child_id 
			 left join msg_1 AS m1   ON m1.id_msg_1   = t.msg_1
			 left join msg_2 AS m2   ON m2.id_msg_2   = t.msg_2
			 left join msg_3 AS m3   ON m3.id_msg_3   = t.msg_3
			 left join path  AS t_p  ON t_p.id_path     = t.path
			 left join pict  AS i    ON i.id_pict     = t.pict
			 left join path  AS i_p  ON i_p.id_path   = i.path

			 left join msg_1 AS i_m1 ON i_m1.id_msg_1 = i.pict_msg_1
			 left join msg_2 AS i_m2 ON i_m2.id_msg_2 = i.pict_msg_2
			 left join msg_3 AS i_m3 ON i_m3.id_msg_3 = i.pict_msg_3
			 left join tema_type  AS type ON type.id_tema_type = r.child_type
			 
			 where child_id = " . $Json['enter']['tema'] . "
			 AND   child_type = 1
			 AND   parent_type = 1
			 limit 111		
		" );  // запрос - тема "curent"		
		
		$Json["child"] = select( " 
				# I_em		=	'child'				, 
				 r.Id_rel			AS rel_Id_rel
				, type.type			AS type 
				, t.parent			AS tema_parent		
				, t.id_tema			AS tema_id
				, r.parent_type		AS rel_parent_type	
				, r.child_type		AS rel_child_type	
				, r.rel_type		AS rel_type	
				, t.id_old			AS tema_id_old		
				, t.dsc				AS tema_dsc			
				, m1.msg_1_text		AS tema_msg_1		
				, m2.msg_2_text		AS tema_msg_2		
				, m3.msg_3_text		AS tema_msg_3		
				, t_p.path			AS tema_path
				, i_m1.msg_1_text	AS pict_msg_1		
				, i_m2.msg_2_text	AS pict_msg_2		
				, i_m3.msg_3_text	AS pict_msg_3		
				, i_p.path 			AS pict_path
				, i.size_s			AS pict_size_s
				, i.size_m			AS pict_size_m
				, i.size_n			AS pict_size_n
				, i.size_g			AS pict_size_g
				, t.date			AS tema_date		
				, t.coords			AS tema_coords		
				, r.rel_order		AS rel_order	
				, t.source			AS tema_source						
				, t.reg_show		AS tema_reg_show	
				, t.map_zoom		AS tema_map_zoom	
			 from relations AS r 
			 join tema AS t 		 ON t.id_tema = r.child_id 
			 left join msg_1 AS m1   ON m1.id_msg_1   = t.msg_1
			 left join msg_2 AS m2   ON m2.id_msg_2   = t.msg_2
			 left join msg_3 AS m3   ON m3.id_msg_3   = t.msg_3
			 left join path  AS t_p  ON t_p.id_path     = t.path
			 left join pict  AS i    ON i.id_pict     = t.pict
			 left join path  AS i_p  ON i_p.id_path   = i.path

			 left join msg_1 AS i_m1 ON i_m1.id_msg_1 = i.pict_msg_1
			 left join msg_2 AS i_m2 ON i_m2.id_msg_2 = i.pict_msg_2
			 left join msg_3 AS i_m3 ON i_m3.id_msg_3 = i.pict_msg_3
			 left join tema_type  AS type ON type.id_tema_type = r.child_type
			 
			 where parent_id = " . $Json['enter']['tema'] . "
			 AND   child_type = 1
			 limit 111		
		" );  // запрос - тема "child"

		
 /*	
		$Json["pict"] = select( " 
			 
				 *


				 r.Id_rel			AS rel_Id_rel
				, t.parent			AS tema_parent		
				, t.id_tema			AS tema_id
				, r.parent_type		AS rel_parent_type	
				, r.child_type		AS rel_child_type	
				, r.rel_type		AS rel_type	
				, t.id_old			AS tema_id_old		
				, t.dsc				AS tema_dsc			
				, m1.msg_1_text		AS tema_msg_1		
				, m2.msg_2_text		AS tema_msg_2		
				, m3.msg_3_text		AS tema_msg_3		
				, t_p.path			AS tema_path
				, i_m1.msg_1_text	AS pict_msg_1		
				, i_m2.msg_2_text	AS pict_msg_2		
				, i_m3.msg_3_text	AS pict_msg_3		
				, i_p.path 			AS pict_path
				, i.size_s			AS pict_size_s
				, i.size_m			AS pict_size_m
				, i.size_n			AS pict_size_n
				, i.size_g			AS pict_size_g
				, t.date			AS tema_date		
				, t.coords			AS tema_coords		
				, r.rel_order		AS rel_order	
				, t.source			AS tema_source						
				, t.reg_show		AS tema_reg_show	
				, t.map_zoom		AS tema_map_zoom	


				 I_em			'parent'
				,	
*/

		$Json["pict"] = select( " 

			r.Id_rel			AS rel_Id_rel
			, type.type			AS type	
			, r.parent_id		AS tema_parent		
			, p.id_pict			AS id_pict
			, p.id_pict			
			, p.grupa
			, p.pict_id_old
			, p.pict_kod_old
			, r.rel_order	
			, i_m1.msg_1_text	AS pict_msg_1		
			, i_m2.msg_2_text	AS pict_msg_2		
			, i_m3.msg_3_text	AS pict_msg_3		
			, i_p.path			AS pict_path
			, p.size_s			AS pict_size_s
			, p.size_m			AS pict_size_m
			, p.size_n			AS pict_size_n
			, p.size_g			AS pict_size_g
	
			 from relations AS r 
			 left join pict  AS p 	 ON p.id_pict     = r.child_id 
			 left join path  AS i_p  ON i_p.id_path   = p.path
			 left join msg_1 AS i_m1 ON i_m1.id_msg_1 = p.pict_msg_1
			 left join msg_2 AS i_m2 ON i_m2.id_msg_2 = p.pict_msg_2
			 left join msg_3 AS i_m3 ON i_m3.id_msg_3 = p.pict_msg_3	
 			 left join tema_type  AS type ON type.id_tema_type = r.child_type
			 
			 where parent_id = " . $Json['enter']['tema'] . "
			 AND   r.child_type = 2 
			 AND r.parent_type = 1 
			 limit 333			 
			 
		" );  // запрос - тема "pict"


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
