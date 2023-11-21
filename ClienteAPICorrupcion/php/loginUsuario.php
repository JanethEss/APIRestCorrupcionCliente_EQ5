<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
        header('Location: ../index.php');
        exit;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {        
        // URL de la API REST para validar la contraseña
        $api_url = 'http://APIRestCorrupcionEQ5.somee.com/api/usuario/ValidarUsuario';

        // Datos del formulario de inicio de sesión
        $usuario = $_GET['usuario'];
        $contrasena = $_GET['contrasena'];

        // Agregar los parámetros a la URL
        $api_url .= "?user=$usuario";

        // Inicializar cURL
        $ch = curl_init($api_url);

        // Configurar opciones de cURL para una solicitud GET
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar errores
        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
        }

        // Cerrar cURL
        curl_close($ch);

        // Decodificar la respuesta JSON
        $result = json_decode($response, true);

        // Procesar la respuesta
        if ($result && !empty($result)) {
            // Validar la contraseña en el lado del cliente
            $contrasenaCorrecta = ($result[0]['Contrasena'] === $contrasena);

            if ($contrasenaCorrecta) {
                // Autenticación exitosa
                session_start();
                $_SESSION['usuario'] = $usuario;
                header('Location: ../index.php');
                exit;
            } else {
                // Mostrar mensaje de error si las credenciales son incorrectas
                echo 'Error al iniciar sesión. Respuesta no válida.';
                header('Location: ../login.php');
                exit;
            }
        } else {
            // Mostrar mensaje de error si la respuesta no es válida
            echo 'Error al iniciar sesión. Respuesta no válida.';
            header('Location: ../login.php');
                exit;
        }
    } else {
        // Si la solicitud no es GET ni POST, redirigir o manejar el error según tus necesidades
        echo 'Error: Solicitud no válida.';
    }
?>