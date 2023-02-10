<?PHP
/*
*	dirTree.php?dir=/gb
*	dir=/gb		- часть пути до сканируемого раздела видная всем
*
*/

require_once('morf_confg.php');	// "библиотечный" файл проекта	
ini_set("max_execution_time", 300);

//	echo ( '<!DOCTYPE html><html lang="ru"><head><meta http-equiv="Content-type" content="text/html; charset=UTF-8" /></head><body>');

/**/
	header( $csp );
	header( $json_type );	 
	header( $Acs_ctrl );


//echo "<pre>";

/*
*	function glob_Dir()    рекурсивный сбор дерева каталогов 
*		i_path=/stdt	- "неПубличная" часть пути до сканируемого раздела
*		dir=/gb			- часть пути до сканируемого раздела видная всем
*		$lim = 2		- ограничение на глубину / циклы
*		$l = 2			- текущая на глубина / цикл
*/
	function glob_Dir( $i_path='/stdt', $dir='', $lim=3, $l=0 ){ 
		$l = $l + 1 ; 
		$pth0 = $_SERVER['DOCUMENT_ROOT'].$i_path;	// путь до сайта
		$pthF  = $pth0.$dir ;		// полный путь до содержимого раздела
//		echo "<hr>".$l." read_hndl: ".$pthF." \n";
		if ( (  $l < $lim ) && ( $arr = glob( $pthF.'/*', GLOB_ONLYDIR ) )  ) {
//			print_r ( $arr );
			foreach ( $arr as $vol ){
			//	$path = end( explode( $pth0, $vol ) );	// выделяем "внутренний" путь
				$ar = explode( $pth0, $vol );			// выделяем "внутренний" путь
				$path = end( $ar );						// выделяем "внутренний" путь
				$ar = explode( '/', $path );			// выделяем "внутренний" путь
				$name = end( $ar );						// выделяем "внутренний" путь
			//	$name = end( explode( '/', $path ) );	// выделяем "внутренний" путь
				$path = str_replace( '\\', '/', $path );	//преобразуем слеши
				$id   = de_fi(re_sl( $path)); 			 	  	//преобразуем слеши
//				echo $id." ---- ".$name." ---- ".$path."<br>";
				$node[ $id ][ 'param' ] = array (				// заполняем описание раздела
					"id"  =>  $id,
					"path" => $path,
					"name" => $name,
					"level" => $l,
					"type" => 'DIR'
				);
				$node[ $id ][ 'child' ] = glob_Dir( $i_path, $path, $lim, $l );
//				print_r ( $node[ $id ] );
				
				if ( is_null( $node[ $id ][ 'child' ] ) ) { 	// если результат пустой ""
					unset( $node[ $id ][ 'child' ] ) ;		// то удаляем элемент
				 }				
			}	
		return $node ;
		}
	}
	
		$lim = 4; //	ограничение на глубину / циклы  сканирования
		
		$path = $path_root ;				// закрытый путь до содержимого раздела
		$dir  = $_GET['dir']  ?? '###' ;	// публичный путь до содержимого раздела
		$dir  = add_sl( $dir  );		
		

		$node_name = de_fi(re_sl( $dir ));
		if ( $node_name == '' ) { $node_name = '.'; };
		$Json[ $node_name ]['param'] =  array ( 
			"id" => $node_name,
		//	"path" => del_pr( $path ),
			"path" => '',
			"name" => $dir,
			"level"=> 0,
			"type" => "DIR" 
		);

		$Json[ $node_name ]['child']  = glob_Dir( $path, $dir, $lim );
		$Json[ $node_name ]['time'] = time()-$time;			
		$Json[ $node_name ]['path_root'] = $path_root;

	$j = out_json( $Json ); 

	echo ( $j );


//		echo "rdir<hr>";
//		print_r ( $rdir );

//	echo "</pre>";


?>
