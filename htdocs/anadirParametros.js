$( document ).ready(function() 
{
    console.log( "a modificar parámetros de la institución! marioleiva2011@gmail.com" );
	$.fn.editable.defaults.mode = 'inline';
	$('[id^="unidad"]').editable({
			params: function(params) {  //params already contain `name`, `value` and `pk`
			var data = {};
			data['pk'] = params.pk;
			data['name'] = params.name;
			data['value'] = params.value;
			data['tabla'] = "unidades";
		
			return data;
		  }
        });
	$('[id^="rubro"]').editable({
			params: function(params) {  //params already contain `name`, `value` and `pk`
			var data = {};
			data['pk'] = params.pk;
			data['name'] = params.name;
			data['value'] = params.value;
			data['tabla'] = "rubros";
		
			return data;
		  }
        });
	$('[id^="autoridad"]').editable({
			params: function(params) {  //params already contain `name`, `value` and `pk`
			var data = {};
			data['pk'] = params.pk;
			data['name'] = params.name;
			data['value'] = params.value;
			data['tabla'] = "autoridades";
		
			return data;
		  }
        });

	////////////////////////////// CAMBIOS EN PESTAÑA 1
	$('[id^="btn-add-"]').click(function(){		
		var mid=$(this).attr('id');
		//alert("hizo click en "+mid);
		var res = mid.split("-");
		var tabla=res[2];
		var usuario=document.getElementById('idUser').value;

		//console.log("Fichero: "+fichero);
		 var txt;
		var person = prompt("Ingrese el nombre del nuevo elemento:", "");
		if (person == null || person == "") 
		{
			alert("No ingresó ningun dato.");
			location.reload();
		}
		else
		{
			$.ajax({
				url:"/anadirElemento.php?tabla="+tabla+"&idUsuario="+usuario+"&valor="+person,
				success:function(result)
				{
					   //var codificar=JSON.stringify(result);
					   alert("Añadido correctamente el elemento "+person+" a la categoría seleccionada");	
					   location.reload();
				}
			}); //fin del ajax
		}
		
		
	
		
	});
	$('[id*="borrar-"]').click(function()
	{
		var mid=$(this).attr('id');
		var res = mid.split("-");

		//ejemplo: borrar-abierto-2 significa: Borra el elemento 2 de la lista de los temas del fichero "abierto"
		var tabla=res[1];
		var numElemento=res[2];
		//console.log("Quiere borrar el elemento "+numElemento+" del fichero "+fichero);
		//llamo a AJAX donde la función borrarTema de borrarTema.php 
		$.ajax({
				url:"/borrarElemento.php?tabla="+tabla+"&numElemento="+numElemento,
				success:function(result)
				{
					   //var codificar=JSON.stringify(result);
					   alert("Eliminado correctamente el elemento de la categoría seleccionada");	
					   location.reload();
				}
			}); //fin del ajax
		
		
	});
	
		
	});