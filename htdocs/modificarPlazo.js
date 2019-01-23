$( document ).ready(function() 
{
    console.log( "Gestor de plazos de entrega del índice. Mario López - marioleiva2011@gmail.com" );
	$.fn.editable.defaults.mode = 'inline';
	//$('[id="mesInicioJulio"]').editable();

	$('[id="mesInicioJulio"]').editable({
			params: function(params) {  //params already contain `name`, `value` and `pk`
			var data = {};
			data['pk'] = params.pk;
			data['name'] = params.name;
			data['value'] = params.value;
			return data;
		  }
        });

	$('[id="diaInicioJulio"]').editable({
			params: function(params) {  //params already contain `name`, `value` and `pk`
			var data = {};
			data['pk'] = params.pk;
			data['name'] = params.name;
			data['value'] = params.value;
			return data;
		  }
        });

	$('[id="mesFinJulio"]').editable({
			params: function(params) {  //params already contain `name`, `value` and `pk`
			var data = {};
			data['pk'] = params.pk;
			data['name'] = params.name;
			data['value'] = params.value;
			return data;
		  }
        });

	$('[id="diaFinJulio"]').editable({
			params: function(params) {  //params already contain `name`, `value` and `pk`
			var data = {};
			data['pk'] = params.pk;
			data['name'] = params.name;
			data['value'] = params.value;
			return data;
		  }
        });
	//$('[id^="gerencia"]').editable();
	////////////////////////////// CAMBIOS EN PESTAÑA 1


		
	});