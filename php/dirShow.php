<?PHP
/*
*	dir.php?dir=/gb
*	dir=/gb		- часть пути до сканируемого раздела видная всем
*
*/

require_once('morf_confg.php');	// "библиотечный" файл проекта	

//	echo ( '<!DOCTYPE html><html lang="ru"><head><meta http-equiv="Content-type" content="text/html; charset=UTF-8" /></head><body>');

/**/
	header( $csp );
	header( $json_type );	 
	header( $Acs_ctrl );
	
/*
*	function dir2json()    сбор содержимого каталога 
*		i_path=/stdt	- "неПубличная" часть пути до сканируемого раздела
*		dir=/gb			- часть пути до сканируемого раздела видная всем
*		$dir
*		$step = 1	-	текущая итерация.
*/ 
	function dir2json ( $i_path='/stdt', $dir='' ){ 
		$handl  = $_SERVER['DOCUMENT_ROOT'];	// путь до сайта
		$handleT = $handl.$i_path.$dir ;		// полный путь до содержимого раздела
		$rdir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($handleT, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST  );

		$rdir->setMaxDepth(0);					//  ограничение глубины "сканирования" за проход

//		echo '<table> ';						//  оформление тестовой печати
		foreach ($rdir as $file){				//  перебор содержимого раздела
			$type = '';							//  обнулили тип

			$name = $rdir->getFilename();	
			if ( $rdir->isDir() ) { 		//  если запись - раздел
				$type = 'DIR'; 				// 	то тип 'DIR'
			} else {						//	иначе
				if( strpos( $name, '.' ) ) {				// если есть тип файла
					$ar = explode(".", $name);	// то  выделяем его из имени
					$type = '.'.end( $ar );	// то  выделяем его из имени
				}
			}
			$path = $rdir->getPathname() ;
			$ar =  explode( $handleT, $path  );	// выделяем "внутренний" путь
			$path = end( $ar );	// выделяем "внутренний" путь
			$path = str_replace( '\\', '/', $path );	//преобразуем слеши
			$size  = $rdir->getSize() ;
	
			$node_name = de_fi(re_sl( $dir.$path));
			$node[$node_name]['param'] = array (		// заполняем описание раздела/файла
				"id"   => $node_name,
				"dir"  => $dir,
				"path" => $dir.$path,
				"name" => $name,
				"size" => $size,
				"type" => $type
			);
		}
		if( isset( $node ) ) {
			return ( $node );
		} else {
			return ;
		}
	}

	   $path = $path_root ;
	   $dir  = add_sl( $_GET['dir']  ?? '###' );	// путь до содержимого раздела


		$node_name = de_fi(re_sl( $dir));
		
		$Json[ $node_name ]['param'] =  array ( 
						"path" => $path_root ,
						"dir"  => $dir,
						"name" => $dir,
						"step" => "0",
						"type" => "DIR" 
					);
		$Json[ $node_name ]['child'] = dir2json( $path, $dir ) ;
		$Json[ $node_name ]['time'] = time()-$time;
			if ( is_null( $Json[ $node_name ][ 'child' ] ) ) { 	// если результат пустой ""
					unset( $Json[ $node_name ][ 'child' ] ) ;		// то удаляем элемент
			}
		
	$j = out_json( $Json ); 

	echo ( $j );
	
	
?>
