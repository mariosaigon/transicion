/*
 ///////////////// SIGIR (SISTEMA DE GESTIÓN DEL INDICE DE INFORMACION RESERVADA DEL IAIP) //////////////////
 -Script que controla las acciones sobre el formulario de subida unica de declaratoria de reserva
 @auhtor JOSE MARIO LOPEZ LEIVA
 marioleiva2011@gmail.com
 Enero 2018.
////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/
///////////////////////////////////////


function goNextTab(currtab, nexttab, numeroActual,numeroSiguiente) 
{

    var curr = $('li.active');
    
    curr.removeClass('active');
    if (curr.is("li:last")) 
	{
        $("li:first-child").addClass('active');
    } 
	else 
	{
        curr.next().find("a").click();
        curr.next().addClass('active');
    }

	
			$("#nav-tab-"+numeroActual).removeClass("active");
			$("#nav-tab-"+numeroSiguiente).addClass("active");
			$('html, body').animate({scrollTop: 0}, 800);

    $('#' + currtab).attr('aria-expanded', 'false');
    $('#' + nexttab).attr('aria-expanded', 'true');

}
function eliminarDeTabla(tabla)
{
 //alert("eliminando de tabla: "+tabla);
    var table = document.getElementById(tabla);
    var rowCount = table.rows.length;

    if(rowCount>1)
	{            
        table.deleteRow(-1);
    }
}
function anadirExperiencia() 
{

	//alert("anadir entrada a tabla experiencias");
	var table = document.getElementById("tablaExperiencias");
	var usuario=document.getElementById('idUser').value;
	var rowCount = table.rows.length;
	//console.log("Añadir entrada en tabla: rowcount"+rowCount);
	if(rowCount < 10) //10 reservas a sbir como máximo
	{                            // limit the user from creating fields more than your limits
		var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		var cell3 = row.insertCell(2);
		var cell4 = row.insertCell(3);
		var cell5 = row.insertCell(4);
		var cell6 = row.insertCell(5);
		var cell7 = row.insertCell(6);
		var cell8 = row.insertCell(7);
		var cell9 = row.insertCell(8);
		var cell10 = row.insertCell(9);
		var cell11 = row.insertCell(10);
		var cell12 = row.insertCell(11);
		//creación de entradas. Idea: formar programáticamente algo así: 
		//<textarea  class="form-control" name="arrayUnidades[]" id="arrayUnidades1" placeholder="Indique..."></textarea>
		//empezando desde la izquierda 
		//1   creo elementos (input o textarea) segun puedo ver en class.ViewFolder.php
        var element1 = document.createElement('input'); // Nº declaracion
		var element2 = document.createElement('select'); //rubro
		var element3 = document.createElement('select');//tipo de clasificacion 
		var element4 = document.createElement('textarea');//detalle de la reserva parcial
		var element5 = document.createElement('textarea');//motivo de la reserva
		var element6 = document.createElement('select');//fundamento legal
		var element7 = document.createElement('select');//unidad administrativa que sugiere
		var element8 = document.createElement('select');//unidad administrativa que genera
		var element9 = document.createElement('select');//autoridad que reserva
		var element10 = document.createElement('input');//fecha clasifiacion
		var element11 = document.createElement('input');//fecha cad
		var element12 = document.createElement('input');//file
		//1
        element1.type="text"; element1.className="form-control"; 
        element1.setAttribute("name", "arrayNumeros[]"); element1.setAttribute("id", "arrayNumeros"+rowCount); 
        element1.setAttribute("placeholder", "Indique el identificador de la reserva...");
        element1.setAttribute("required", true); 
        ///////////// 2
        //rubros: debo obtenerlos de la BD antes con ajax
        $.ajax({
                        url:"/dameRubros.php?idUsuario="+usuario,
                        success:function(result)
                        {
                        	var longi=result.length;

                               var codificar=JSON.stringify(result);
                               opcionesTipos=result;
                               element2.className="form-control select"; 
						        element2.setAttribute("name", "arrayRubros[]"); element2.setAttribute("id", "arrayRubros"+rowCount); 
						 
						        element2.setAttribute("required", true); 
						        var option1 = document.createElement("option"); option1.setAttribute("disabled", true); option1.setAttribute("selected", true);
								option1.setAttribute("value", "-1"); option1.text="Seleccione un rubro";
								element2.appendChild(option1);
								//opcionesTipos=["Total","Parcial"];
								for (var i = 0; i < opcionesTipos.length; i++) 
								{
									var option = document.createElement("option");
									option.setAttribute("value", opcionesTipos[i]);
									option.text = opcionesTipos[i];
									element2.appendChild(option);
								}
								//							 
                              //var parsear = JSON.parse(codificar);                            
                        },
                        dataType:"json"
                    }); //fin del ajax
        

        //tipo de clasificación 3
        element3.setAttribute("name", "arrayTipos[]");
        element3.setAttribute("id", "arrayTipos_"+rowCount);
        element3.className="form-control select";
        element3.setAttribute("required", true); 

		//element3.setAttribute("class", "form-control select");
		var option1 = document.createElement("option"); option1.setAttribute("disabled", true); option1.setAttribute("selected", true);
		option1.setAttribute("value", "-1"); option1.text="Seleccione una opción";
		element3.appendChild(option1);
		opcionesTipos=["Total","Parcial"];
		for (var i = 0; i < opcionesTipos.length; i++) 
		{
			var option = document.createElement("option");
			option.setAttribute("value", opcionesTipos[i]);
			option.text = opcionesTipos[i];
			element3.appendChild(option);
		}
		//

	    element4.className="form-control"; 
        element4.setAttribute("name", "arrayParciales[]"); element4.setAttribute("id", "arrayParciales_"+rowCount); 
       
        element4.setAttribute("placeholder", "Indique el detalle si su elección fue Parcial..."); 
        //element4.setAttribute("required", true); 

        element5.className="form-control"; 
        element5.setAttribute("name", "arrayMotivos[]"); element5.setAttribute("id", "arrayMotivos"+rowCount); 
        element5.setAttribute("placeholder", "Escriba el motivo de la reserva..."); 
        element5.setAttribute("required", true); 

        element6.setAttribute("class", "form-control chzn-select");
        var indiceBueno=rowCount-1;	
        element6.setAttribute("name", "arrayFundamentos["+indiceBueno+"][]");
        element6.setAttribute("id", "arrayFundamentos"+rowCount);				
		element6.setAttribute("multiple", "multiple");
		element6.setAttribute("required", true); 
		element6.setAttribute("data-placeholder", "Seleccione uno o varios literales de la lista...");		
		var a="a)Planes militares secretos y negociaciones políticas a los que se refiere el art. 168 ordinal 7º  de la constitución.";
		var b="b)Información que perjudique o ponga en riesgo la defensa nacional o seguridad pública.";
		var c="c)Información que menoscabe las relaciones internacionales o la conducción de negociaciones diplomáticas del país.";
		var d="d)Que ponga en peligro evidente la vida; seguridad o salud de cualquier persona.";
		var e="e)Información que contenga opiniones o recomendaciones que formen parte del proceso deliberativo de los servidores públicos en tanto no sea adoptada la decisión definitiva.";
		var f="f)Información que causare un serio perjuicio en la prevención; investigación o persecución de actos ilícitos.";
		var g="g) Información que comprometiere las estrategias y funciones estatales en procedimientos judiciales o administrativos en curso.";
		var h="h)Información que pueda generar una ventaja indebida a una persona en perjuicio de un tercero.";
		literales=[];
		literales.push(a); literales.push(b);literales.push(c);literales.push(d);literales.push(e);literales.push(f);
		literales.push(g);literales.push(h);
		for (var i = 0; i < literales.length; i++) 
		{
			var option = document.createElement("option");
			option.setAttribute("value", literales[i]);
			option.text = literales[i];
			element6.appendChild(option);
		}

		//
		

         ///////////// 7: UNIDAD QUE SUGIERE       
        //: debo obtenerlos de la BD antes con ajax
        $.ajax({
                        url:"/dameUnidades.php?idUsuario="+usuario,
                        success:function(result)
                        {
                        	var longi=result.length;

                               var codificar=JSON.stringify(result);
                               opcionesTipos=result;
                               element7.className="form-control select"; 
						        element7.setAttribute("name", "arrayUnidades[]"); element7.setAttribute("id", "arrayUnidades"+rowCount); 
						        element7.setAttribute("required", true); 
						        var option1 = document.createElement("option"); option1.setAttribute("disabled", true); option1.setAttribute("selected", true);
								option1.setAttribute("value", "-1"); option1.text="Seleccione un rubro";
								element7.appendChild(option1);
								//opcionesTipos=["Total","Parcial"];
								for (var i = 0; i < opcionesTipos.length; i++) 
								{
									var option = document.createElement("option");
									option.setAttribute("value", opcionesTipos[i]);
									option.text = opcionesTipos[i];
									element7.appendChild(option);
								}
								//							 
                              //var parsear = JSON.parse(codificar);                            
                        },
                        dataType:"json"
                    }); //fin del ajax
        

 ///////////// 8: UNIDAD QUE GENERA       
        //: debo obtenerlos de la BD antes con ajax
        $.ajax({
                        url:"/dameUnidades.php?idUsuario="+usuario,
                        success:function(result)
                        {
                        	var longi=result.length;

                               var codificar=JSON.stringify(result);
                               opcionesTipos=result;
                               element8.className="form-control select"; 
						        element8.setAttribute("name", "arrayUnidadesGene[]"); element8.setAttribute("id", "arrayUnidadesGene"+rowCount); 
						        element8.setAttribute("required", true); 
						        var option1 = document.createElement("option"); option1.setAttribute("disabled", true); option1.setAttribute("selected", true);
								option1.setAttribute("value", "-1"); option1.text="Seleccione un rubro";
								element8.appendChild(option1);
								//opcionesTipos=["Total","Parcial"];
								for (var i = 0; i < opcionesTipos.length; i++) 
								{
									var option = document.createElement("option");
									option.setAttribute("value", opcionesTipos[i]);
									option.text = opcionesTipos[i];
									element8.appendChild(option);
								}
								//							 
                              //var parsear = JSON.parse(codificar);                            
                        },
                        dataType:"json"
                    }); //fin del ajax
        

/////////////

         ///////////// 9: AUTORIDAD  
        //: debo obtenerlos de la BD antes con ajax
        $.ajax({
                        url:"/dameAutoridades.php?idUsuario="+usuario,
                        success:function(result)
                        {
                        	var longi=result.length;

                               var codificar=JSON.stringify(result);
                               opcionesTipos=result;
                               element9.className="form-control select"; 
						        element9.setAttribute("name", "arrayAutoridades[]"); element9.setAttribute("id", "arrayAutoridades"+rowCount); 
						        element9.setAttribute("required", true); 
						        var option1 = document.createElement("option"); option1.setAttribute("disabled", true); option1.setAttribute("selected", true);
								option1.setAttribute("value", "-1"); option1.text="Seleccione un rubro";
								element9.appendChild(option1);
								//opcionesTipos=["Total","Parcial"];
								for (var i = 0; i < opcionesTipos.length; i++) 
								{
									var option = document.createElement("option");
									option.setAttribute("value", opcionesTipos[i]);
									option.text = opcionesTipos[i];
									element9.appendChild(option);
								}
								//							 
                              //var parsear = JSON.parse(codificar);                            
                        },
                        dataType:"json"
                    }); //fin del ajax
 

///////////// 10: FECHA CLASIFICACION  
        var dateObj = new Date();
		var month = dateObj.getUTCMonth() + 1; //months from 1-12
		if(month<10)
		{
			month="0"+month;
		}
		var day = dateObj.getUTCDate();
		if(day<10)
		{
			day="0"+day;
		}
		var year = dateObj.getUTCFullYear();
	    newdate = year + "-" + month + "-" + day;

	    element10.type="text";
        element10.setAttribute("name", "arrayFechas[]");
        element10.setAttribute("class", "form-control");
        element10.setAttribute("value", ""); 
        element10.setAttribute("required", true); 
        var dateSpan = document.createElement('span');
        dateSpan.className="input-append date datepicker";       
        var adicional = document.createElement('span');
        adicional.setAttribute("class", "add-on");
        dateSpan.setAttribute("data-date", newdate);
        dateSpan.setAttribute("data-date-format", "yyyy-mm-dd" );
        dateSpan.setAttribute("data-date-language", "es-ES");
        dateSpan.append(element10);
        dateSpan.append(adicional);
///////////// 11. FECHA CADUCIDAD
        var span11 = document.createElement('span');
        element11.type="text";
        element11.setAttribute("name", "arrayCaduca[]");
        element11.setAttribute("class", "form-control");
        element11.setAttribute("value", "");
        element11.setAttribute("required", true); 
        var adicional2 = document.createElement('span');
        adicional2.setAttribute("class", "add-on");
        span11.className="input-append date datepicker";
        span11.setAttribute("data-date", newdate);
        span11.setAttribute("data-date-format", "yyyy-mm-dd" );
        span11.setAttribute("data-date-language", "es-ES");
        span11.append(element11);
        span11.append(adicional2);

       ///////////// 12: fichero

        element12.setAttribute("name", "archivo[]"); 
        element12.type="file";
        element12.setAttribute("id", "archivo"+rowCount);
        element12.setAttribute("required", true); 


      	
        cell1.appendChild(element1);
		cell2.appendChild(element2);
		cell3.appendChild(element3);
		cell4.appendChild(element4);
		cell5.appendChild(element5);
		cell6.appendChild(element6);
		cell7.appendChild(element7);
		cell8.appendChild(element8);
		cell9.appendChild(element9);
		cell10.appendChild(dateSpan);
		cell11.appendChild(span11);
		cell12.appendChild(element12);
		
		var idFundamentos="arrayFundamentos"+rowCount; 
		//console.log("id fundamentos: "+idFundamentos);	//hago render del chzn select
		//UNICAMENTE DE LA ENTRADA EN LA TABLA QUE ACABO DE CREAR. Antes hacía  $(".chzn-select").chosen(); y me hacía RENDER DE TODOS,
		//desformateando de manera fea los campos que tenia pues el primero ya estaba pre formateado
		$("#"+idFundamentos).chosen();
		$(".datepicker").datepicker();
	}
	else
	{
		 alert("10 documentos reservados máximo para la subida.");
			   
	}
}
///////////////////////////////////////////////////////////////////////
$('#attributes_4').change(function() 
{
	//AQUI SE CONTROLA SI ELIJO la opcion "Parcial" para el atributo Tipo de clasificación (attributes_4)
    var val = $("#attributes_4 option:selected").text();
   console.log("Seleccionado "+val);
	var eldiv = document.getElementById("reservaParcial");

	if(val.localeCompare("Parcial")==0)
	{
		//SI SELECCIONO LA LEY VIGENTE MUESTRO LOS DIV DE VIGENTE Y OCULTO LAS DEROGADAS
		$(eldiv).show('slow');
	}

	if(val.localeCompare("Total")==0)
	{
		//SI SELECCIONO LA LEY VIGENTE MUESTRO LOS DIV DE VIGENTE Y OCULTO LAS DEROGADAS
		$(eldiv).hide('slow');
	}
});
$('#baseConstitucional').change(function() 
{
	var eldiv = document.getElementById("baseConsti"); //se define en class.Bootstrap.php
	  if (document.getElementById('baseConstitucional').checked) 
	  {
	  	
            //alert("elegido base constituciónal");
            $(eldiv).show('slow');
        }
        else
        {
        	 $(eldiv).hide('slow');
        }
	
  
});

$('#tratadosInternacionales').change(function() 
{
	var eldiv = document.getElementById("tratadosInter"); //se define en class.Bootstrap.php
	  if (document.getElementById('tratadosInternacionales').checked) 
	  {
	  	
            //alert("elegido base constituciónal");
            $(eldiv).show('slow');
        }
        else
        {
        	 $(eldiv).hide('slow');
        }
	
	
});


$('#normativaNacional').change(function() 
{
	var eldiv = document.getElementById("normNacional"); //se define en class.Bootstrap.php
	  if (document.getElementById('normativaNacional').checked) 
	  {
	  	
            //alert("elegido base constituciónal");
            $(eldiv).show('slow');
        }
        else
        {
        	 $(eldiv).hide('slow');
        }
	
   
});

$('#reglamentosAdministrativos').change(function() 
{
	var eldiv = document.getElementById("reglamentosAdmin"); //se define en class.Bootstrap.php
	  if (document.getElementById('reglamentosAdministrativos').checked) 
	  {
	  	
            //alert("elegido base constituciónal");
            $(eldiv).show('slow');
        }
        else
        {
        	 $(eldiv).hide('slow');
        }
	
	});


// $('body').on('change',".datepicker", function()
// {
// 	alert("calendar");
//     $(this).datepicker();
// });
//evento que verá el cambio en un select para ver si la elección fue Total o Parcial
//se disparará cuando cambie todo elemento de la clase "select"
 $(document).on('change', ".select", function () 
 {
        //console.log(this.value);
        //console.log("En ver parcial");
		var strChosen = $(this).attr('id');
		var val = $("#"+strChosen+" option:selected").text();
	    //console.log("Var"+val);
	    var soloNum = strChosen.split("_");
	    var num=soloNum[1];
			    //console.log("Solo numerito"+num);
	    var idDetalle="arrayParciales_"+num;
	       if(val.localeCompare("Parcial")==0) //si elijo parcial desbloquedo el textarea de Detalle de reserva parcial
			{
				
			    //console.log("Id del detalle: "+idDetalle);
			    document.getElementById(idDetalle).readOnly=false;
			}
			//Si elijo Total, bloqueo ese campo
			if(val.localeCompare("Total")==0) //si elijo parcial desbloquedo el textarea de Detalle de reserva parcial
			{
			    document.getElementById(idDetalle).readOnly=true;
			    document.getElementById(idDetalle).value="";
			   
			}
    });
////////////////////////////////// PRINCIPAL: DOCUMENT READY (Lleva los listeners de eventos) /////////////////////////////////////
$(document).ready(function() 
{
console.log("***INDICE DE INFORMACIÓN RESERVADA IAIP***** by José Mario López marioleiva2011@gmail.com");
$.fn.datepicker.defaults.autoclose = true;
document.getElementById("anadeTabla").addEventListener("click", function() {anadirExperiencia()});
document.getElementById("eliminaTabla").addEventListener("click", function() {eliminarDeTabla("tablaExperiencias")});

//si hago un select (especialmente tipo de reserva) quiro saber cual es para poner enables el campo "Detalle reserva parcial"
 //validar el formulario de subida única de

	

});

$('#resetMultiple').click(function(e) {
  setTimeout(function() 
  {

    limpiarMultiple()
  }, 10);
});
function limpiarMultiple() 
{
  console.log("a limpiar");
  document.getElementById("multiplesReservas").reset();
  $('select#arrayFundamentos').trigger('chosen:updated');

  
}