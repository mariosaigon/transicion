
function myFunction() {
  var x = document.getElementById("consejero").value;
  //document.getElementById("demo").innerHTML = "You selected: " + x;
   var dataf1=[];
	$.ajax({
                        url:"/seguimientoTacometro.php?id="+x,
                        success:function(result)
                        {
							
                                 //var text=JSON.stringify(result);
                             
                              datosf1 = JSON.parse(result);
                              if (!$.trim(datosf1)){   
                              alert("Este consejero no tiene ningún avance en ninguna de las fases, favor seleccionar otro");
                          }
                          else
                          {
                              var totalExpedientes=Number(datosf1[0]['Expedientes']); 
                //console.log("Total expedientes: "+totalExpedientes);
                              for (var index in datosf1) 
                              {
                              //console.log("key: "+datosf1[index]['key']);
                              //console.log("total: "+datosf1[index]['total']);
                              dataf1.push(Number(datosf1[index]['FaseID']));
                              dataf1.push(Number(datosf1[index]['Reuniones']));
                dataf1.push(Number(datosf1[index]['Reuniones_realizadas']))
                             }
                document.getElementById("cantidadExpedientes").innerHTML = "";
                              document.getElementById("gaugef1").innerHTML = "";
                document.getElementById("gaugef2").innerHTML = "";
                document.getElementById("gaugef3").innerHTML = "";
                document.getElementById("gaugef4").innerHTML = "";
                document.getElementById("gaugef5").innerHTML = "";
                document.getElementById("gaugef6").innerHTML = "";
                document.getElementById("gaugef7").innerHTML = "";
                document.getElementById("f1").innerHTML = "";
                document.getElementById("f2").innerHTML = "";
                document.getElementById("f3").innerHTML = "";
                document.getElementById("f4").innerHTML = "";
                document.getElementById("f5").innerHTML = "";
                document.getElementById("f6").innerHTML = "";
                document.getElementById("f7").innerHTML = "";
                
                //
                document.getElementById("cantidadExpedientes").innerHTML = "<b>Este/a Consejero/a tiene asignados un total de "+totalExpedientes+" expedientes</b>";
                document.getElementById("f1").innerHTML = "La fase 1 tiene una sola reunión por expediente.";
                var gaugef1 = new JustGage({
                id: "gaugef1",
                value: Number(dataf1[2]),
                min: 0,
                max: Number(dataf1[1]),
                title: "Fase 1"
                });
                 document.getElementById("f2").innerHTML = "La fase 2 tiene un total de 4 reuniones  por expediente.";
  var gaugef2 = new JustGage({
    id: "gaugef2",
    value: Number(dataf1[5]),
    min: 0,
    max: Number(dataf1[4]),
    title: "Fase 2"
  });
  document.getElementById("f3").innerHTML = "La fase 3 tiene un total de 4 reuniones  por expediente.";
  var gaugef3 = new JustGage({
    id: "gaugef3",
    value: Number(dataf1[8]),
    min: 0,
    max: Number(dataf1[7]),
    title: "Fase 3"
  });
  document.getElementById("f4").innerHTML = "La fase 4 tiene un total de 4 reuniones  por expediente.";
  var gaugef4 = new JustGage({
    id: "gaugef4",
    value: Number(dataf1[11]),
    min: 0,
    max: Number(dataf1[10]),
    title: "Fase 4"
  });
  document.getElementById("f5").innerHTML = "La fase 5 tiene un total de 4 reuniones  por expediente.";
  var gaugef5 = new JustGage({
    id: "gaugef5",
    value: Number(dataf1[14]),
    min: 0,
    max: Number(dataf1[13]),
    title: "Fase 5"
  });
  document.getElementById("f6").innerHTML = "La fase 6 tiene un total de 3 reuniones  por expediente.";
  var gaugef6 = new JustGage({
    id: "gaugef6",
    value: Number(dataf1[17]),
    min: 0,
    max: Number(dataf1[16]),
    title: "Fase 6"
  });
  document.getElementById("f7").innerHTML = "La fase 7 tiene una sola reunión  por expediente.";
  var gaugef7 = new JustGage({
    id: "gaugef7",
    value:Number(dataf1[20]),
    min: 0,
    max: Number(dataf1[19]),
    title: "Fase 7"
  });
              
                          }//fin del else
							  
							
							
                        } // fin de success
						
						//console.log("b: "+dataf1[1])
                    }); //fin del ajax

	

  


  

}
