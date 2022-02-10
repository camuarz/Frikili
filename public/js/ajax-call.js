
function MeGusta(Id){
  var Ruta = Routing.generate('Liks')
    $.ajax({
        type: 'POST',
        url: Ruta,
        data: ({id: Id}),
        async: true,
        dataType: "json",
        success: function (data) {
          window.location.reload();
        }
    });
}

