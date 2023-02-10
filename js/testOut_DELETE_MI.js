/* *
* 	адаптация 2020.02,  работает с  jquery-1.10.2   
* 
*    testOut_DELETE_MI.js  -  описание объекта помогающего в отладке JavaScript
*	содержит все, что нужно для своей работы
*	за исключением  jQuery
*
*	@namespace testOUT
*
*	изменение размеров тестового окна:
*		курсор над окном, нажата клавиша [Ctrl] + [левая клавиша мыши]
*		сдвигая курсор - меняем размер окна от правого верхнего угла окна
*	
*	изменение положения тестового окна:
*		курсор над окном, нажата клавиша [Shift] + [левая клавиша мыши]
*		сдвигая курсор - сдвигаем окно 
*	
*	скрыть/открыть окно - сочетание клавиш [Ctr] + [Q]
*
*	выводим сообщение
*		testOUT.msgs('здесь текст сообщения'); 
*		/ тег <br> в конце строки не требуется
*		/ в строке могут присутствовать переменные
*		/  'изменилось значение foo: ' + foo
*
*
*	добавляем  контрольный пункт
*		testOUT.item(  'название пункта' ) ; 
*		testOUT.item({  'название пункта': '' }) ; 
*		testOUT.item({  'название пункта': 'название пункта' }) ; 
*		- производится подсчет всех обращений
*		к  'название пункта' из любого места скрипта
*
*		testOUT.item({  'название пункта': контролируемая_переменная }) ; 
*		testOUT.item({  'ширина окна':widthScrn }) ; 
*		- выводится значение контролируемой_переменной
*
*	добавляем  несколько контрольных пунктов
*		testOUT.item({
*				'widthScrn':widthScrn ,
*				'heightScrn':heightScrn 
*		}) ;
*		при таком запросе все контролируемые пункты 
*		будут записываться в протокол  одной строкой
*
*	для облегчения контроля за некоторыми событиями
*		можно поменять, например, цвет фона в тестовом окне
*		testOUT.stl({'background-color':'#ddddee'});
*		или еще что-нибудь из стиля
*
*
*/

var testOUT = ( function() {
	var ii = 0 ;		//  сквозная нумерация контрольной информации
	var logStr = '';	//  протокол контрольной информации
	var items = {};		//  хранилище контольных точек
//	var msg = '';		//  хранилище сообщения
	var TESTOUT = 'testOUT'; //  имя  блока вывода информации , может быть изменено

	var CSSdef = {  //  стили для блока по умолчанию
		'position': 'fixed',
		'display': 'none',			//   'none'  /  'block' - скрыть/показать блок по умолчанию
		'z-index': '888', 
		'top': '222px', 
		'right': '11px', 
		'width': '442px',
		'height': '222px',
		'padding': '1em',
		'padding-left': '1.5em',
		'overflow-x': 'hidden',
		'overflow-y': 'auto',
		'background-color': '#eeeeee',
		'border': '1px solid #777777',
		'font': '12px/1.31 arial,helvetica,clean,sans-serif', 
		'-moz-user-select': 'none',
		'cursor': 'pointer'
	};
	
	var CSSmie = {  //  стили для блока в IE
		'user-select': 'none',
		'position': 'absolute'
	};


	var stl = function(j) {		//  внесение стилевых изменений
			for (var i in j) {
				$('#'+TESTOUT).css( i, j[i] )  ;
			};
	};
	

	var initTestOUT = function() {  // создаем и оформляем всем необходимым тестовое окно / блок
		if( !$('#'+TESTOUT).length > 0 ){ // если нет окна для тестового вывода
			$('body').prepend('<div id="'+TESTOUT+'"></div>');  //  создаем окно для тестового вывода
			stl ( CSSdef );    // нагружаем окошко стилями
			CSSdef = {} ;    		// чистим место
			if ( navigator.appName == "Microsoft Internet Explorer") {
				stl ( CSSmie );    // догружаем окошко стилями для IE
			} ;  				
			CSSmie = {} ;    		// чистим место
			
		//	alert('create div TESTOUT');
			
			$(document).keydown(function (e) { // ждем нажатия клавиши Ctrl
				if( e.which == 81 && e.ctrlKey ) { //и нажата клавиша  [ q ]
					if ($('#'+TESTOUT).css('display') == 'none'){ // и если блок скрыт
						$('#'+TESTOUT).css('display', 'block');	// то показываем его
					} else{										// иначе
						$('#'+TESTOUT).css('display', 'none');	// убираем его с экрана
						//testOUT.stl ({'display':'none'});	// убираем его с экрана
					}
					return false;
				} // if( e.which == 81 && e.ctrlKey )
			});		// ждем нажатия клавиши Ctrl	
			
			$('#'+TESTOUT).mousedown( function (e) { // ждем нажатия клавиши мыши
					
				if(e.shiftKey) {
					$('#'+TESTOUT).addClass('shiftMouseDown'); // метим смещаемый блок классом shiftMouseDown
					this.mouseMoveX = e.pageX;	//	смещение мыши по Х
					this.mouseMoveY = e.pageY;	//	смещение мыши по Х
					this.beginX = $('#'+TESTOUT).css('right');	// получаем отступ блока справа
					this.beginY = $('#'+TESTOUT).css('top');		// получаем отступ блока ссверху
					this.beginX = parseInt(this.beginX.replace("\D*",""),10); // вырезаем  не цифры и приводим к числу
					this.beginY = parseInt(this.beginY.replace("\D*",""),10); // вырезаем  не цифры и приводим к числу
				};
				 if(e.ctrlKey) {
					$('#'+TESTOUT).addClass('ctrlMouseDown');
					this.mouseMoveX = e.pageX;	//	смещение мыши по Х
					this.mouseMoveY = e.pageY;	//	смещение мыши по Х
					this.beginX = $('#'+TESTOUT).width();	// получаем ширину блока 
					this.beginY = $('#'+TESTOUT).height();	// получаем высоту блока 
				};
				
			}).mouseup( function (e) {
						$('#'+TESTOUT).removeClass('ctrlMouseDown');
						$('#'+TESTOUT).removeClass('shiftMouseDown');
			});	
			
			$('body').on( 'mousemove', '.ctrlMouseDown',  // объект помеченный стилем ctrlMouseDown подлежит изменению размеров
				function (m) {
					$('.ctrlMouseDown').width(this.beginX-(m.pageX - this.mouseMoveX));
					$('.ctrlMouseDown').height(this.beginY+(m.pageY - this.mouseMoveY));
				}		
			);  	

			 $('body').on( 'mousemove', '.shiftMouseDown', // объект помеченный стилем shiftMouseDown подлежит перемещению
				function (m) {
					 $('.shiftMouseDown').css('right', this.beginX-(m.pageX - this.mouseMoveX));
					 $('.shiftMouseDown').css('top', this.beginY+(m.pageY - this.mouseMoveY));
				}		
			);  	
		
		} else { alert('initTestOUT: Упс.. это ошибка!'); };	
		
		initTestOUT = function() { 
			alert('initTestOUT: я на пенсии!');
			return true;
		} ;  // удалили метод ??
		
	};	//  initTestOUT  --- создаем и оформляем всем необходимым тестовое окно / блок

	var	show = function(msg) {		// показываем информацию
		var str = '' ;

		if( msg != 0 ){  //  если msg == 0  - значит вызов не информационный
			ii++;
			str = '<b>'+ii+'! '+msg+'</b><br>'; // информация в 1 строке
			logStr = '<b>'+ii+'!</b> '+msg+'<br>'+ logStr; // собираем протокол всего... что показывалось
		}
		
		if( $('#'+TESTOUT).length > 0 ){ // если есть окошко для тестового вывода

			for (var i in items) {		// собираем все items для вывода
				str += i + ' : ' + items[i] + '<br>' ;
			};
			str = str + '<hr>' + logStr;	
			$('#'+TESTOUT).html( str );

		} else {  // если нет  окошка для тестового вывода
			return false;  	 // 
		}; 
		
	}; // show :  отображаем информацию
		
	$(function() {  //по готовности документа ( появился тег body )
		initTestOUT();	//  создаем и оформляем всем необходимым тестовое окно / блок
		show(0);
	});
	
	return {  //  выделение общедоступных элементов		

		stl : stl, // открываем доступ  к функции изменения стиля
		
		msgs : function(j) {	// вывод сообщения 
			// msg = j;
			show(j);
		},	
						
		item : function(k) {	//  добавляем контрольный пункт/пункты и показываем результат
			var j = {};
			var msg = ''; 
			
			if ( typeof(k) !== "object" ) {		// если нужно - создаем объект ( пустой )
				j[k] = '' ;
			} else {
				j = k ;
			};
			for ( var i in j ) {
				if( i != j[i] && j[i] != '' ) {
					if ( typeof(j[i]) === "object" ){ j[i] = "It's object"; } //  фильтруем object'ы 
					items[i] = j[i]; 
				} else {
					if ( items[i] ) { 
						items[i] ++ ; 
					} else {
						items[i] = 1 ; 
					};
				};
				msg += i + ' : ' + items[i] + ', ';
			};
			msg = msg.replace(/,\s*$/," "); // заменили последнюю запятую на пробел/точку
			show(msg);
		}

	};
} ());

