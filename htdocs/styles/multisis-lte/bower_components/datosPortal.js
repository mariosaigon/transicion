/*

	JOSÉ MARIO LOPEZ LEIVA
	2017
	marioleiva2011@gmail.com
*/


//OBTENER DEL FORMULARIO que está en class.EstadisticasDepartamentales.php
var reservas2016 = $("#reservas2016").val();
var reservas2017 = $("#reservas2017").val();
var reservas2018 = $("#reservas2018").val(); 
var actas2016 = $("#actas2016").val();
var actas2017 = $("#actas2017").val();
var actas2018 = $("#actas2018").val()


//console.log(mayor);
            // bar chart data
            var barData = 
			{
                labels : ["2016","2017","2018"],
                datasets : [
                    {
                        fillColor : "#48A497",
                        strokeColor : "#48A4D1",
						label: 'Actas de inexistencia',
                        data : [actas2016,actas2017,actas2018]
                    },
                    {
                        fillColor : "rgba(73,188,170,0.4)",
                        strokeColor : "rgba(72,174,209,0.4)",
						label: 'Declaratorias de reserva',
                        data : [reservas2016,reservas2017,reservas2018]
                    }
				
                ]
            }
            //get bar chart canvas
            var income = document.getElementById("graficoBarras").getContext("2d");
            //draw bar chart
			var config= {
				scaleOverride : true,
				scaleSteps : 2,
				scaleStepWidth:25,
				scaleStartValue : 0
				
				 //legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
					
}			
    var myChart= new Chart(income).Bar(barData,config);
