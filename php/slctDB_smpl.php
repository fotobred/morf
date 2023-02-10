<?
// работа с базой данных  :: простой запросё
//  slctDB_smpl.php?slc=id&tbl=lang&lim=2 - вариант обращения  
//					slc - SELECT
//					tbl - FROM 
//					whr - WHERE
//					lim - LIMIT

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

	$Json['enter']['info'] = 	' slctDB_smpl?slc=id&tbl=lang&lim=2 - вариант обращения        <br>        '
								.' slctDB_smpl?select=* from lang limit 12 - вариант обращения  <br>       ' 
								.' slc - SELECT <br>      ' 
								.' tbl - FROM <br>        ' 
								.' whr - WHERE<br>        ' 
								.' lim - LIMIT';
								
	$INP = inPUT( )	;	// взяли исходные данные там, где их дали

//	разобрали данные
	$Json['enter']['select']  = censor( $INP['select'] ?? '' ); // список вывода для SELECT
	$Json['enter']['slc']	  = censor( $INP['slc'] ?? '*' ); // список вывода для SELECT
	$Json['enter']['tbl']	  = censor( $INP['tbl'] ?? 0 );   // таблица для SELECT
	$Json['enter']['whr']	  = censor( $INP['whr'] ?? 0 );   // WHERE в SELECT
	$Json['enter']['lim']	  = censor( $INP['lim'] ?? LIMIT_MIN );   // LIMIT в SELECT

	$curDBlink = OpenDB();
	
	if( $Json['enter']['select'] != '' ) {
		$Json["SELECT"] = select( $Json['enter']['select'] );
		$Json['enter']['query']	= "[ SELECT ".$Json['enter']['select']." ]";
	} else {
		$Json['enter']['query']	= "[ SELECT ".$Json['enter']['slc']." FROM ".$Json['enter']['tbl']." WHERE ".$Json['enter']['whr']." LIMIT ".$Json['enter']['lim']." ]";
		$Json["SELECT"] = slct_DB( $Json['enter'] );
	}

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
