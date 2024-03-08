<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/estilos.css">

    <title>CRUD con PHP, PDO, Ajax y Datatables.js</title>
</head>

<body>
    <div class="container fondo">
        <h1 class="text-center">CRUD con PHP, PDO, Ajax y Datatables.js</h1>
        <h2 class="text-center">www.render2web.com</h2>

        <div class="row">
            <div class="col-2 offset-10">
                <div class="text-center">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalUsuario" id="botonCrear">
                        <i class="bi bi-plus-circle-fill"></i> Crear
                    </button>
                </div>
            </div>
        </div>

        <br><br>

        <div class="table-responsive">
            <table id="datos_usuario" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Imagen</th>
                        <th>Fecha Creación</th>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Usuario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formulario" action="" method="post" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-body">
                            <label for="nombre">Ingrese el nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control">
                            <br>

                            <label for="apellidos">Ingrese los apellidos</label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control">
                            <br>

                            <label for="telefono">Ingrese el teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control">
                            <br>

                            <label for="email">Ingrese el email</label>
                            <input type="text" name="email" id="email" class="form-control">
                            <br>

                            <label for="imagen_usuario">Seleccione una imagen</label>
                            <input type="file" name="imagen_usuario" id="imagen_usuario" class="form-control">
                            <span id="imagen-subida"></span>
                            <br>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" name="id_usuario" id="id_usuario">
                            <input type="hidden" name="operacion" id="operacion">
                            <input type="submit" name="action" id="action" class="btn btn-success" value="Crear">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="js/jquery-3.7.1.js"></script>

    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        let table;
        let tableInitialized = false;

        $(document).ready(function() {

            $("#botonCrear").click(function() {
                $("#formulario")[0].reset();
                $(".modal-title").text("Crear Usuario");
                $("#action").val("Crear");
                $("#operacion").val("Crear");
                $("#imagen-subida").html("");
            });

            table = new DataTable('#datos_usuario', {
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "obtener_registros.php",
                    type: "post"
                },
                "columnsDefs": [{
                    "targets": [0, 3, 4],
                    "orderable": false,
                }],
                "destroy": true
            });

            tableInitialized = true;

            $(document).on('submit', '#formulario', function(e) {
                e.preventDefault();
                var nombres = $("#nombre").val();
                var apellidos = $("#apellidos").val();
                var telefono = $("#telefono").val();
                var email = $("#email").val();
                var extension = $("#imagen_usuario").val().split('.').pop().toLowerCase();

                if (extension != '') {
                    if (jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                        alert("Formato de imagen inválido");
                        $("#imagen_usuario").val('');
                        return false;
                    }
                }

                if (nombres != '' && apellidos != '' && email != '') {
                    $.ajax({
                        url: "crear.php",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            alert(data);
                            $("#formulario")[0].reset();
                            $("#modalUsuario").modal('hide');
                            if (tableInitialized) {
                                table.destroy();
                            }
                            table = new DataTable('#datos_usuario', {
                                "processing": true,
                                "serverSide": true,
                                "order": [],
                                "ajax": {
                                    url: "obtener_registros.php",
                                    type: "post"
                                },
                                "columnsDefs": [{
                                    "targets": [0, 3, 4],
                                    "orderable": false,
                                }],
                                "destroy": true
                            });
                            tableInitialized = true;
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    })
                } else {
                    alert("Algunos campos son obligatorios");
                }
            });

            //Funcionalidad de editar
            $(document).on('click', '.editar', function() {
                var id_usuario = $(this).attr("id");
                $.ajax({
                    url: "obtener_registro.php",
                    method: "POST",
                    data: {
                        id_usuario: id_usuario
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#modalUsuario").modal('show');
                        $("#nombre").val(data.nombre);
                        $("#apellidos").val(data.apellidos);
                        $("#telefono").val(data.telefono);
                        $("#email").val(data.email);
                        $(".modal-title").text("Editar Usuario");
                        $("#id_usuario").val(id_usuario);
                        $("#imagen-subida").html(data.imagen_usuario);
                        $("#action").val("Editar");
                        $("#operacion").val("Editar");
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            });

            // Funcionalidad de borrar
            $(document).on('click', '.borrar', function() {
                var id_usuario = $(this).attr("id");
                if (confirm("¿Está seguro de borrar este registro?")) {
                    $.ajax({
                        url: "borrar.php",
                        method: "POST",
                        data: {
                            id_usuario: id_usuario
                        },
                        dataType: "html",
                        success: function(data) {
                            alert(data);
                            if (tableInitialized) {
                                table.destroy();
                            }
                            table = new DataTable('#datos_usuario', {
                                "processing": true,
                                "serverSide": true,
                                "order": [],
                                "ajax": {
                                    url: "obtener_registros.php",
                                    type: "post"
                                },
                                "columnsDefs": [{
                                    "targets": [0, 3, 4],
                                    "orderable": false,
                                }],
                                "destroy": true
                            });
                            tableInitialized = true;
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>