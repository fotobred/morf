	Morf.prog = {
		msgs: {
			name:	"msgs",
			query:	"select msg_1.msg_1_text as msg_1, msg_2.msg_2_text as msg_2, msg_3.msg_3_text as msg_3 from tema join msg_1 as msg_1 on msg_1.msg_1_id = tema.msg_1 join msg_2 as msg_2 on msg_2.msg_2_id = tema.msg_2 join msg_3 as msg_3 on msg_3.msg_3_id = tema.msg_3 where tema.id_old=111222",
			param:	"111221",
			note:	"получение текстов к узлу"
		},
		msg_1: {
			name:	"msg_1",
			query: "select msg_1.msg_1_text as msg_1 from tema join msg_1 as msg_1 on msg_1.msg_1_id = tema.msg_1 where tema.id_old='111222' ",
			param: "111221",
			note:	"получение текста msg_1 к узлу"
		},

		all_tems: {
			name:	"all_tems",
			query: "select  tema.id_tema, tema.id_old, tema.parent, tema.dsc, tema.pict, msg_1.msg_1_text as msg_1_text,  pict.path, pict.size_s, pict.size_m, pict.size_n  from tema  join msg_1 as msg_1 on msg_1.msg_1_id = tema.msg_1  left join pict as pict on pict_id_old = tema.pict  limit 721 " ,
			param: "нет",
			note:	"все темы с именем и фото - какой то бред"
		},		

		all_first: {
			name:	"first",
			query: "select tema.id_tema, tema.dsc, tema.pict, tema.parent, msg_1.msg_1_text as msg_1 from tema join msg_1 as msg_1 on msg_1.msg_1_id = tema.msg_1 where tema.parent = 2  limit 721" ,
			param: "tema.parent = 2 ",
			note:	"вывод 'первых' тем"
		},


		all: {
			name:	"all",
			query: "select * from tema join msg_1 as msg_1 on msg_1.msg_1_id = tema.msg_1 join msg_2 as msg_2 on msg_2.msg_2_id = tema.msg_2 join msg_3 as msg_3 on msg_3.msg_3_id = tema.msg_3 where tema.id_old='111222' " ,
			param: "111221",
			note:	"жадный запрос к узлу"
		},
		
	};