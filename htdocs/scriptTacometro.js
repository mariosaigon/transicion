
      var totalf1 = $("#totalf1").val();
      var totalf2 = $("#totalf2").val();
      var totalf3 = $("#totalf3").val();
      var totalf4 = $("#totalf4").val();
      var totalf5 = $("#totalf5").val();
      var totalf6 = $("#totalf6").val();
      var totalf7 = $("#totalf7").val();
      document.getElementById("f1").innerHTML = "Se deben subir un total de 2 documentos";
var gaugef1 = new JustGage({
                id: "gaugef1",
                value: totalf1,
                min: 0,
                max: 2,
                title: "1. Marco estratégico y normativo institucional"
                });
                 document.getElementById("f2").innerHTML = "Se deben subir un total de 4 documentos";
  var gaugef2 = new JustGage({
    id: "gaugef2",
    value: totalf2,
    min: 0,
    max: 4,
    title: "2. Cumplimiento de objetivos y metas institucionales"
  });
  document.getElementById("f3").innerHTML = "Se deben subir un total de 8 documentos";
  var gaugef3 = new JustGage({
    id: "gaugef3",
    value: totalf3,
    min: 0,
    max: 8,
    title: "3. Presupestos aprobados en el quinquenio y ejecución presupuestaria"
  });
  document.getElementById("f4").innerHTML = "Se deben subir un total de 11 documentos";
  var gaugef4 = new JustGage({
    id: "gaugef4",
    value: totalf4,
    min: 0,
    max: 11,
    title: "4. Organización interna"
  });
  document.getElementById("f5").innerHTML = "Se deben subir un total de 3 documentos";
  var gaugef5 = new JustGage({
    id: "gaugef5",
    value: totalf5,
    min: 0,
    max: 3,
    title: "5. Auditorías y juicios"
  });
  document.getElementById("f6").innerHTML = "Se deben subir un total de 5 documentos";
  var gaugef6 = new JustGage({
    id: "gaugef6",
    value: totalf6,
    min: 0,
    max: 5,
    title: "6. Transparencia y rendición de cuentas"
  });
  document.getElementById("f7").innerHTML = "Se deben subir un total de un documento";
  var gaugef7 = new JustGage({
    id: "gaugef7",
    value:totalf7,
    min: 0,
    max: 1,
    title: "7. Principales procesos estratégicos en marcha"
  });