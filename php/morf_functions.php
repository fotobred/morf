<?PHP
/*
*	склад функций проекта Morf	
*/


	$time = time();
	
	
	function censor($sl){  		// 	преобразуем слеши в интегралы	
		$list = "#\b|SELECT|DROP|UPDATE|INSERT|delete|create|alter|index|references|show|execute|grant|\b#is" ;
		$sl = preg_replace( $list, '' , $sl ); //удаляем лишнее из запросов
		$sl = preg_replace( '#\s+#i', ' ' , $sl ); //удаляем лишнее из запросов
		return $sl;
	}


	function re_sl($sl){  		// 	преобразуем слеши в интегралы	
		$sl = str_replace( '/', '∫',  $sl );   	//преобразуем слеши
		$sl = str_replace( '∫∫', '∫', $sl );	// в интегралы	
		return $sl;
	}
	// PS об интегралах - знак интеграла используется в качестве замены знака / в именах разделов

	function de_fi($sl){  		// 	удаляем интеграл в 1 позиции	
		$p = '/^∫/';
		$sl = preg_replace($p, '', $sl ); 
		return $sl;
	}

	function add_sl($sl){  		// проверка на наличия '/' в первой позиции (обработка шаблона)
		if(  $sl !== '###' ) {	// если параметр задан
//			$ss = strpos( $sl, '/' ) ?? FALSE ; // определение позиции 	//	echo $sl.' =  '.$ss.'   <br>';
			$ss = strpos( $sl, '/' )  ; // определение позиции 	//	echo $sl.' =  '.$ss.'   <br>';
			if(  $ss !== 0 ) {
				$sl = '/'.$sl ;	// дополнение '/'
			}
		} else {
			$sl = '';			//	 Зачистка, если параметр НЕ задан
		}
		return $sl;
	}	

	function out_json( $Json ){
	//	$Json['enter']['out_json']['encode_param'] = 'JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT' ;
		$J = json_encode( $Json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
	//	$J = json_encode( $Json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
		
		if( json_last_error() != 0 ){
		//	$Json['error']= "json_last_error". json_last_error_msg() ;
			echo ( "json_last_error<br>" );
			echo ( json_last_error_msg() );
			echo ( "<br>" );
		}		
//		echo $J ;
		return $J;
	}
	
	function printT( $name = '', $prn = '') {  // вывод строки или массива, если указан тестовый режим 
		if( $Test == 'test' ) { 
			if( $name ) { 
				$name = str_replace('<', '&lt;', $name );
				$name = str_replace('>', '&gt;', $name );
			echo ' '.$name.' :: '; 
			};
			if( is_array( $prn ) ){	echo '<pre>';
				$prn = str_replace('<', '&lt;', $prn );
				$prn = str_replace('>', '&gt;', $prn );			
				print_r( $prn ); echo '</pre>';				
			} else {
				$prn = str_replace('<', '&lt;', $prn );
				$prn = str_replace('>', '&gt;', $prn );
				echo '[ '.$prn.' ]' ; 
			};
			echo "<br>";
		};
	}; // function printT( $name = '', $prn = '')  - вывод строки или массива

	global $curDBlink ;		// дескриптор соединения с базой
	
	function OpenDB(){
		global $curDBlink ;		// дескриптор соединения с базой
		$curDBlink = mysqli_connect( HOST, DB_USER, DB_PASSWORD, DB );
		mysqli_set_charset( $curDBlink, 'utf8');
			if ( !$curDBlink ) die("Error");
		return $curDBlink;
	};
	
	function CloseDB( $DB ){
		mysqli_close($DB);
	};

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
	// function show()  таблицы в базе

	function show_Q( $q ) {  // таблицы в базе
		$result = show( $q ); 
		while ( $row = mysqli_fetch_array($result)) {
			  $res[] = $row[0] ;
		};			
		
		foreach ( $res as $in => $tbl ) {
			$result = show("columns from ".$tbl); 
			while ( $row = mysqli_fetch_array($result)) {
				$res[$tbl][$row[0]] = $row[1] ;
			};
			unset( $res[$in] );
		};
		return $res;		
	};	
	// function show_Q()  таблицы в базе

	function show_DB_strct_tbl( $name_tbl) {  // структура таблицы в базе
		$result = show("columns from ".$name_tbl); 
			$i = 0;
		while ( $row_s = mysqli_fetch_assoc($result)) {
			$res[] = $row_s ;
		};	
		return $res;
	};
	
	function show_DB() {  // таблицы в базе
		$result = show( "TABLE STATUS" ); 
		while ( $row = mysqli_fetch_assoc($result)) {
			$name_tbl = $row['Name'];
			foreach ( $row as $in => $tbl ) {
			//	echo ('  '.$in.' - > '.$tbl.'; ' );
				$res[$name_tbl]["param"][$in] = $tbl ;
			}; // foreach ( $row as $in => $tbl )
				// собираем структуру таблицы
				$res[$name_tbl]["strct"] = show_DB_strct_tbl( $name_tbl);
				// собираем данные таблицы
				$res[$name_tbl]["data"] = show_table( $name_tbl, LIMIT_MIN );
				
		};			
		return $res;		
	};	
	// function show_DB()  таблицы в базе
	
	function show_table( $name_tbl, $limit ) {  // таблицА в базе
		$slct = " * from ".$name_tbl." LIMIT ".$limit." " ; 
		$res = select(  $slct ); 

		return $res;		
	};	
	// function show_table()  таблицы в базе	

	function select( $par = '*' ) {  // запрос select
		global $curDBlink ;	// дескриптор соединения с базой
		$par = limit_ctrl( $par );
 		$query = "select ".$par ;
		$res = array();
		$result  =  mysqli_query( $curDBlink,  $query );
		if ( !$result ) { 
			echo "Произошла ошибка: [".$query."] "  .  mysqli_error(); 
			return 0 ;	
		} else {
			while ( $row = mysqli_fetch_assoc($result)) {
				$res[] = $row ;
			};	
			if( $res ){ 
/*				if( count( $res ) == 1 ){ 
					return $res[0] ; 
				} else {
					return $res;
				}
*/				return $res;
			} else {
				return 0;
			}
		};			
		return ;		 
	}; 	// function select()  запрос select


	function select_one( $par = '*' ) {  // запрос select c 1 результатом
		global $curDBlink ;	// дескриптор соединения с базой
		$par = limit_ctrl( $par );
 		$query = "select ".$par ;
		$result  =  mysqli_query( $curDBlink,  $query );
		if ( !$result ) { 
			echo "Произошла ошибка: [".$query."] "  .  mysqli_error(); 
			return 0 ;	
		} else {
			$row = mysqli_fetch_array($result);
			$res = $row[0] ;
			return $res;
		};			
		return ;		 
	}; 	// function select_one()  запрос select


	function limit_ctrl( $qu ) {  // контроль наличия LIMIT в простом запрос
		if(  !strpos( strtolower( $qu ) , 'limit' ) ){
			$qu = $qu.' LIMIT '.LIMIT_MIN ;
		};
		
		return $qu;
	};   // function limit_ctrl( $qu ) {  // контроль наличия LIMIT в простом запрос
	
	function slct_DB( $S ) {  // простой запрос по параметрам
	
		$str = $S['slc']." from ".$S['tbl'] ;
		
		if( $S['whr'] != "0" ) {
			$str = $str." WHERE ".$S['whr'] ; 
		}

		if( $S['lim'] != 0 ) {
			$str = $str." LIMIT ".$S['lim'] ; 
		}
		$res = select( $str ); 

		return $res;		
	};	
	// function slct_DB()  простой запрос


	function inPUT() {  // берем запрос оттуда, где его дают
		if( count( $_POST ) > 0 ){
			$INP = $_POST;
		} elseif( count( $_GET ) > 0 ){
			$INP = $_GET;
		} else {
		  return ;	
		}
	  return $INP;	
	};

?>