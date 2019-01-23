/*

	JOSÉ MARIO LOPEZ LEIVA
	2017
	marioleiva2011@gmail.com
*/


//OBTENER DEL FORMULARIO que está en class.EstadisticasDepartamentales.php
var actasAhuachapan = $("#actasAhuachapan").val();
var actasCabañas = $("#actasCabañas").val();
var actasChalate = $("#actasChalate").val();
var actasCusca = $("#actasCusca").val();
var actasLaLibertad = $("#actasLaLibertad").val();
var actasLaPaz = $("#actasLaPaz").val();
var actasLaUnion = $("#actasLaUnion").val();
var actasMorazan = $("#actasMorazan").val();
var actasSanMiguel = $("#actasSanMiguel").val();
var actasSanSalvador = $("#actasSanSalvador").val();
var actasSanVicente = $("#actasSanVicente").val();
var actasSantaAna = $("#actasSantaAna").val();
var actasSonsonate = $("#actasSonsonate").val();
var actasUsulutan = $("#actasUsulutan").val();
//obtener declaratorias
var reservas=new Array();
var reservasAhuachapan = $("#reservasAhuachapan").val(); reservas.push(reservasAhuachapan);
var reservasCabañas = $("#reservasCabañas").val(); reservas.push(reservasCabañas);
var reservasChalate = $("#reservasChalate").val(); reservas.push(reservasChalate);
var reservasCusca = $("#reservasCusca").val(); reservas.push(reservasCusca);
var reservasLaLibertad = $("#reservasLaLibertad").val(); reservas.push(reservasLaLibertad);
var reservasLaPaz = $("#reservasLaPaz").val(); reservas.push(reservasLaPaz);
var reservasLaUnion = $("#reservasLaUnion").val(); reservas.push(reservasLaUnion);
var reservasMorazan = $("#reservasMorazan").val(); reservas.push(reservasMorazan);
var reservasSanMiguel = $("#reservasSanMiguel").val(); reservas.push(reservasSanMiguel);
var reservasSanSalvador = $("#reservasSanSalvador").val(); reservas.push(reservasSanSalvador);
var reservasSanVicente = $("#reservasSanVicente").val(); reservas.push(reservasSanVicente);
var reservasSantaAna = $("#reservasSantaAna").val(); reservas.push(reservasSantaAna);
var reservasSonsonate = $("#reservasSonsonate").val();reservas.push(reservasSonsonate);
var reservasUsulutan = $("#reservasUsulutan").val(); reservas.push(reservasUsulutan);
//obtener sin registro

/////////////////////////////////////////////
var numActasMunicipios = $("#numActasMunicipios").val(); 
var numReservasMunicipios = $("#numReservasMunicipios").val(); 
var numSinRegistroMunicipios = $("#numSinRegistroMunicipios").val(); 
//////////////////////////////////////////////
var acumuladorReservas = $("#acumuladorReservas").val(); 
var acumuladorActas = $("#acumuladorActas").val(); 
var acumuladorSin = $("#acumuladorSin").val(); 
//console.log(reservas.toString());
var mayor = Math.max(...reservas); //obtengo el numero mayor, para poder ajustar la escala de la gráfica
//console.log(mayor);
            // bar chart data
            var barData = 
			{
                labels : ["Ahuachapán","Cabañas","Chalatenango","Cuscatlán","La Libertad","La Paz","La Unión","Morazán","San Miguel","San Salvador","San Vicente","Santa Ana","Sonsonate", "Usulután"],
                datasets : [
                    {
                        fillColor : "#48A497",
                        strokeColor : "#48A4D1",
						label: 'Actas de inexistencia',
                        data : [actasAhuachapan,actasCabañas,actasChalate,actasCusca,actasLaLibertad,actasLaPaz,actasLaUnion,actasMorazan,actasSanMiguel,actasSanSalvador,actasSanVicente,actasSantaAna,actasSonsonate,actasUsulutan]
                    },
                    {
                        fillColor : "rgba(73,188,170,0.4)",
                        strokeColor : "rgba(72,174,209,0.4)",
						label: 'Declaratorias de reserva',
                        data : [reservasAhuachapan,reservasCabañas,reservasChalate,reservasCusca,reservasLaLibertad,reservasLaPaz,reservasLaUnion,reservasMorazan,reservasSanMiguel,reservasSanSalvador,reservasSanVicente,reservasSantaAna,reservasSonsonate,reservasUsulutan]
                    }
				
                ]
            }
            //get bar chart canvas
            var income = document.getElementById("graficoBarras").getContext("2d");
            //draw bar chart
			var config= {
				scaleOverride : true,
				scaleSteps : 1,
				scaleStepWidth : mayor,
				scaleStartValue : 0
				
				 //legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
					
}			
    var myChart= new Chart(income).Bar(barData,config);
			//document.getElementById("leyenda").innerHTML = grafico.generateLegend();
//document.getElementById('js-legend').innerHTML = myChart.generateLegend();



/////////////////
var pieData = [
				{
					value: numActasMunicipios,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "Actas"
				},
				{
					value: numReservasMunicipios,
					color: "#ffac38",
					highlight: "#526868",
					label: "Índices"
				},
				{
					value: numSinRegistroMunicipios,
					color: "#18a547",
					highlight: "#37c9ff",
					label: "Sin registro"
				}
			

			];
/* 			window.onload = function(){
				var ctx = document.getElementById("pastel1").getContext("2d");
				window.myPie = new Chart(ctx).Pie(pieData);
			}; */
var pieData2 = [
				{
					value: acumuladorActas,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "Actas"
				},
				{
					value: acumuladorReservas,
					color: "#ffac38",
					highlight: "#526868",
					label: "Índices"
				},
				{
					value: acumuladorSin,
					color: "#18a547",
					highlight: "#37c9ff",
					label: "Sin registro"
				}
			];
			window.onload = function(){
				var pastel2 = document.getElementById("pastel2").getContext("2d");
				window.myPie = new Chart(pastel2).Pie(pieData2);
				var ctx = document.getElementById("pastel1").getContext("2d");
				window.myPie = new Chart(ctx).Pie(pieData);
			};