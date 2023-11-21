<?php
session_start();
if(isset($_SESSION['usuario']))
{
        $usuarioSesion=$_SESSION['usuario'];  
        
}
else
{
    header('Location: index.php');		   
    die() ;       
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente API REST Corrupción</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">    
    <link rel="shortcut icon" href="images/logoAPICirculo.png">
    <link rel="stylesheet" href="css/style-tablero.css">
    <link rel="stylesheet" href="css/ventanamodal.css">

</head>
<body>
    <div class="wrapper">
        <!-- Menu Lateral -->
        <aside id="sidebar">
            <div class="h-100">
                <div class="sidebar-logo">
                    <center> <a href="index.php"><img src="images/logoAPICirculo.png" width="100px" /></a></center>
                </div>
                <!-- Sidebar Navigation -->
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Menú
                    </li>
                    <li>
                        <a href="index.php" class="sidebar-link">
                            <i class="fa-solid fa-list pe-2"></i>
                            Tablero de reportes
                        </a>
                    </li>
                    <li>
                        <?php
                            if($usuarioSesion<>'')
                            {
                                ?>
                                <!--Solo con sesión abierta-->
                                <a href="MisReportes.php" class="sidebar-link">
                                <i class="fa-solid fa-file-lines pe-2"></i>
                                    Mis Reportes
                                </a>
                                <?php
                            }
                            else
                            {
                                ?>
                                <!--Sin sesión abierta-->
                                <a href="login.php" class="sidebar-link">
                                <i class="fa-solid fa-file-lines pe-2"></i>
                                    Mis Reportes
                                </a>
                                <?php
                            }

                        ?>                        
                    </li>
                    <?php
                        if($usuarioSesion<>'')
                        {
                            ?>
                            <!--Solo con sesión abierta-->
                            <li>
                                <a href="miinformacion.php" class="sidebar-link">
                                    <i class="fa-solid fa-gear pe-2"></i>
                                    Mi información
                                </a>
                            </li>                                        
                            <li class="sidebar-link">                        
                                    <i class="fa-solid fa-user pe-2" ></i>
                                    <?php echo $usuarioSesion ?>                        
                            </li>
                            <li>
                                <a href="php/cerrarsesion.php" class="sidebar-link">
                                    <i class="fa-solid fa-right-from-bracket pe-2"></i>
                                    Cerrar Sesion
                                </a>
                            </li> 
                            <?php
                        }
                        else
                        {
                            ?>
                            <!--Sin sesión abierta-->
                            <li>
                                <a href="login.php" class="sidebar-link">
                                    <i class="fa-solid fa-user pe-2"></i>
                                    Iniciar sesión
                                </a>
                            </li>
                            <?php
                        }
                    ?>                                                                                                                                         
                </ul>
            </div>
        </aside>
        <!-- Contenido -->
        <div class="main">
            <nav class="px-3 border-bottom">                
                <h3 class="espacio">Mis reportes</h3>                                
            </nav>
            
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <div class="mb-3">
                        <a href="#openModal"><button class="nuevoReporte">Ingresar nuevo reporte</button> </a>                   
                    </div>  
                    <div class="mb-3">  
                        <table class="table table-bordered">
                            <thead>
                                <tr class="columnas">                            
                                    <th class="col1">Folio</th>
                                    <th class="col2">Descripción</th>
                                    <th class="col3">Fecha de registro</th>
                                    <th class="col3">Status</th>
                                    <th class="col1">Modificar</th>
                                    <th class="col1">Eliminar</th>                                   
                                </tr>
                            </thead>
                            <tbody>                                    
                            <!---->    
                                <!-- Aqui se usa la API REST -->                           
                            <?php
                                // URL de la API REST a consumir    $usuarioSesion
                                $api_url = 'http://APIRestCorrupcionEQ5.somee.com/api/reportes/MisReportes?user='. $usuarioSesion;

                                // Obtener la respuesta de la API
                                $response = file_get_contents($api_url);

                                // Decodificar la respuesta JSON
                                $reports = json_decode($response, true);

                                // Verificar si se recibió una respuesta válida
                                if ($reports && is_array($reports)) {
                                    // Recorrer los reportes y mostrar en la tabla
                                    foreach ($reports as $report) {                                         
                                        $Fecha =  $report['Fecha_Reporte'];
                                        $nfecha = date("Y-m-d", strtotime( $Fecha));                                                                                 
                                        echo '<tr class="registros">                            
                                                <td>' . $report['Folio_Reporte'] . '</td>
                                                <td>' . $report['Descripcion'] . '</td>                                                 
                                                <td>' . $nfecha.'</td>                                                                                                        
                                                <td>' . $report['Status'] . '</td>  
                                                <td>
                                                    <a href="modificarReporte.php?folio=' . $report['Folio_Reporte'] . '" class="">
                                                        <i class="fa-solid fa-pen-to-square acciones"></i>                                        
                                                    </a>                                        
                                                </td>
                                                <td> '?>
                                                <form onsubmit="return confirmarEliminacion()" id="deleteForm" method="post" action="php/eliminarRegistro.php">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="id" value="<?php echo $report['Folio_Reporte'] ?>">
                                                    <center><button type="submit" class="fa-solid fa-trash acciones eliminar"></center>
                                                </form> 
                                                <script>
                                                    function confirmarEliminacion() {
                                                        return confirm("¿Estás seguro de que deseas eliminar este reporte?");
                                                    }
                                                </script>                                               
                                                    <?php '
                                                </td>                                
                                            </tr>';                                        
                                    }
                                } else {
                                        // Mostrar un mensaje de error si la respuesta no es válida
                                    echo '<tr><td colspan="4">No hay reportes.</td></tr>';
                                }
                            ?>                            
                            </tbody>             
                        </table>                        

                    </div>
                </div>
            </main>            
        </div>
        <!-- Ventana modal para ingresar nuevo reporte-->        
        <div id="openModal" class="modalDialog">
            <div>
                <a href="#close" title="Close" class="close">X</a>
                <h2>Nuevo reporte</h2>
                <p>Describe detalladamente el caso que deseas reportar, se objetivo, respetuoso y habla con la verdad, si la información del caso es falsa el reporte sera cancelado.</p>
                <form action="php/NuevoReporte.php" method="POST" class="">                        
                        <label for="" class="py-2 lbl">Descripción</label><br>
                        <textarea type="text" name="Descripcion" placeholder="Ingresar descripción del reporte" class="texttarea" required></textarea><br>

                        <label for="" class="py-2 lbl">Usuario:</label>
                        <input type="text" name="Usuario"  value="<?php echo $usuarioSesion ?>" class="ingresar" required readonly><br>

                        <label for="" class="py-2 lbl">Fecha de ingreso:</label>
                        <!--<label for="" class="" id="current_date">Hoy</label><br>-->
                        <input type="text" name="Fecha_Reporte" class="ingresar" id="fecha" value="" required readonly><br>

                        <label for="" class="py-2 lbl">Estatus:</label>
                        <input type="text" name="Status" value="Activo" class="ingresar" required readonly><br>

                        <center><button class="nuevoReporte botonventanas" type="submit">Ingresar reporte</button></center>
                </form>
            </div>
        </div>        

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script>
        date = new Date();
        year = date.getFullYear();
        month = date.getMonth() + 1;
        day = date.getDate();
        //document.getElementById("current_date").innerHTML = year + "/" + month + "/" + day;
        document.getElementById("fecha").value = year + "-" + month + "-" + day;
    </script>
</body>
</html>