<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD con PHP, PDO, Ajax y DataTables.js</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DATATABLE  -->
    <link href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
</head>
  <body>
  <div class="container fondo">
    <h1 class="text-center">CRUD con PHP, PDO, Ajax y Datatables.js</h1>
    
    <div class="row">
      <div class="col-2 offset-10">
        <div class="text-center">
          <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalUsuario" id="botonCrear">
            <i class="bi bi-plus-circle-fill"></i>Crear
          </button>
        </div>
      </div>
    </div>
    <br/>
    <br/>
    
    <div class="table-responsive">
      <table id="datos_usuario" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Telefono</th>
            <th>Email</th>
            <th>Imagen</th>
            <th>Fecha Creacion</th>
            <th>Editar</th>
            <th>Borrar</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  
<!-- Modal -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" id="formulario" enctype="multipart/form-data">
            <div class="modal-content">
              <label for="nombre">Ingrese el nombre</label>
              <input type="text" name="nombre" id="nombre" class="form-control">
              <br />

              <label for="apellido">Ingrese los apellidos</label>
              <input type="text" name="apellidos" id="apellidos" class="form-control">
              <br />

              <label for="telefono">Ingrese el telefono</label>
              <input type="text" name="telefono" id="telefono" class="form-control">
              <br />

              <label for="email">Ingrese el email</label>
              <input type="email" name="email" id="email" class="form-control">
              <br />
            
              <label for="imagen">Seleccione una imagen</label>
              <input type="file" name="imagenUsuario" id="imagenUsuario" class="form-control">
              <span id="imagenSubida"></span>
              <br />
            </div>

            <div class="modal-footer">
              <input type="hidden" name="id_usuario" id="id_usuario">
              <input type="hidden" name="operacion" id="operacion">
              <input type="submit" name="action" id="action" class="btn btn-success" value="Crear">
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- DATATABLES SCRIPT  -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+30JU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  
  <script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
  <script type="text/javascript">
      $(document).ready(function(){
        $("#botonCrear").click(function(){
          $("#formulario")[0].reset();
          $(".modal-tittle").text("Crear Usuario");
          $("#action").val("Crear");
          $("#operacion").val("Crear");
          $("#imagen_subida").html("");
        })

        var dataTable = $('#datos_usuario').DataTable({
          "processing":true,
          "serverside":true,
          "order":[],
          "ajax":{
            url: "obtenerRegistros.php",
            type: "POST"
          },
          "columnsDefs":[
            {
            "targets":[0,3,4],
            "orderable":false
            },
          ]
        });
        
        // Codigo de Insercion
        $(document).on('submit', '#formulario', function(event){
        event.preventDefault();
        var nombres = $("#nombre").val();
        var apellidos = $("#apellido").val()
        var telefono = $("#telefono").val()
        var email = $("#email").val()
        var extension = $("#imagen_usuario").val().split('.').pop().toLowerCase();
      
        if(extension != ''){
            if(jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1){
            alert("Formato de imagen no es valido");
            $("#imagen_usuario").val('');
            return false;
          }
        }

        if(nombres != '' && apellidos != '' && email != ''){
          $.ajax({
            url:"crear.php",
            method: "POST", 
            data:new FormData(this),
            contentType: false,
            processData: false,
            success:function(data){
              alert(data),
              $('#formulario')[0].reset();
              $('#modalUsuario').modal.hide();
              dataTable.ajax.reload(); 
            }
          });
        }else{
          alert("Algunos campos son obligatorios");
        }
      });

        // Funcion editar
        $(document).on('click', 'editar', function(){
          var id_usuario = $(this).attr("id");
          $.ajax({
            url:"obtener_registro.php",
            method: "POST",
            data:{id_usuario:id_usuario},
            dataType:"json",
            success:function(data){
              $('#modalUsuario').modal('show');
              $('#nombre').val(data.nombre);
              $('#apellidos').val(data.apelli);
              $('#telefono').val(data.telefono);
              $('#email').val(data.email);
              $('.modal-tittle').text("Editar Usuario");
              $('#id_usuario').val(id_usuario);
              $('#imagen_subida').html(data.imagen_usuario);
              $('#action').val("Editar");
              $('#operacion').val("Editar");
            },
            error: function(jqXHR, textStatus, errorThrown){
              console.log(textStatus, errorThrown);
            }
          })
        })

        // Funcionalidad de borrar
        $(document).on('click', '.borrar', function(){
          var id_usuario = $(this).attr("id");
          if(confirm("Esta seguro de borrar este registro? " + id_usuario)){
            $.ajax({
              url:"borrar.php",
              method:"POST",
              data:{id_usuario:id_usuario},
              success:function(data)
              {
                alert(data)
                dataTable.ajax.reload();
              }
            });
          }
          else
          {
            return false;
          }
        });

      });      
    </script>
    
</body>
</html>