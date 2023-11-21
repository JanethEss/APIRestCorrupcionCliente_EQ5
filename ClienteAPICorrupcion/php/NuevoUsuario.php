<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // URL de la API REST para registrar un nuevo usuario
    $api_url = 'http://APIRestCorrupcionEQ5.somee.com/api/NuevoUsuario';

    // Datos del nuevo usuario
    $newUserData = [
        'Usuario1' => $_POST['usuario'],
        'Nombre' => $_POST['nombre'],
        'ApeMaterno' => $_POST['apeMaterno'],
        'ApePaterno' => $_POST['apePaterno'],
        'Contrasena' => $_POST['contrasena']
    ];

    // Inicializar cURL
    $ch = curl_init($api_url);

    // Convertir los datos del usuario a formato JSON
    $jsonData = json_encode($newUserData);

    // Configurar opciones de cURL para una solicitud POST con formato JSON
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
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
        if ($result === true) {
            // Redirigir al usuario a la página de inicio de sesión
            header('Location: ../login.php');
            exit;
        } elseif ($result && isset($result['message'])) {
            // Mostrar mensaje de error si existe
            echo 'Error al registrar el usuario. Mensaje: ' . $result['message'];
        } else {
            // Manejar cualquier otro error
            echo 'Error desconocido al registrar el usuario. Detalles de la respuesta: ' . var_export($result, true);
        }
    } else 
        // Si la solicitud no es POST, redirigir o manejar el error según tus necesidades
        echo 'Error: Solicitud no válida.';

?>