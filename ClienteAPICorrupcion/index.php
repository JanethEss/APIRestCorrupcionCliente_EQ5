<?php
session_start();
if(isset($_SESSION['usuario']))
{
        $usuarioSesion=$_SESSION['usuario'];       
}
else
{
        $usuarioSesion='';        
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
        <!-- Main Component -->
        <div class="main">
            <nav class="px-3 border-bottom">                
                <h3 class="espacio">Tablero de Reportes</h3>                                
            </nav>
            
            <main class="content px-3 py-2">
                <div class="container-fluid">
                      
                    <div class="mb-3 py-4">  
                    <p>
                        <form action="" method="POST" class="">
                            <input type="text" name="buscarfolio" placeholder="Ingrese el folio del reporte que desea buscar" class="buscador">
                            <button type="submit" name="buscar" class="filtrar">Buscar</button>
                            <a href="index.php"><button type="submit"  class="filtrar">Restaurar</button></a>
                        </form>
                        
                    </p>  
                    
                    <table class="table table-bordered">
                            <thead>
                                <tr class="columnas">                            
                                    <th class="col1">Folio</th>
                                    <th class="col2">Descripción</th>
                                    <th class="col3">Fecha de registro</th>
                                    <th class="col3">Usuario</th>
                                    <th class="col3">Status</th>                                    
                                </tr>
                            </thead>
                            <tbody>  
                                                              
                            
                            <?php
                            if(isset($_POST['buscar'])) 
                            { 
                                $folio = $_POST['buscarfolio'];
                                // URL de la API REST a consumir
                                $api_url = 'http://APIRestCorrupcionEQ5.somee.com/api/reportes/BuscarReporte?folio='. $_POST['buscarfolio'];

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
                                                <td>' . $report['Usuario'] . '</td> 
                                                <td>' . $report['Status'] . '</td>                                  
                                            </tr>';
                                    }
                                } else {
                                        // Mostrar un mensaje de error si la respuesta no es válida
                                    echo '<tr><td colspan="4">Error al obtener los reportes desde la API.</td></tr>';
                                } 
                            }else{
                                ?><!-- Aqui se usa la API REST -->                           
                            <?php
                                // URL de la API REST a consumir
                                $api_url = 'http://APIRestCorrupcionEQ5.somee.com/api/reportes/ObtenerTodos';

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
                                                <td>' . $report['Usuario'] . '</td> 
                                                <td>' . $report['Status'] . '</td>                                  
                                            </tr>';
                                    }
                                } else {
                                        // Mostrar un mensaje de error si la respuesta no es válida
                                    echo '<tr><td colspan="4">Error al obtener los reportes desde la API.</td></tr>';
                                }
                            
                            }
                            ?>
                            </tbody>                                           
                        </table>                        
                    </div>
                </div>
            </main>
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>