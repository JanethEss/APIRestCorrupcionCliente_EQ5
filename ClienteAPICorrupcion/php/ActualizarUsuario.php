<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // URL de la API REST para registrar un nuevo usuario
    $userid = $_POST['Usuario']; // Supongamos que recibimos el ID del reporte a actualizar desde un formulario

    $api_url = 'http://APIRestCorrupcionEQ5.somee.com/api/ModificarUsuario?user='.$userid;

    // Datos del nuevo usuario
    $updateUserData = [
        'Nombre' => $_POST['Nombre'],
        'ApeMaterno' => $_POST['ApeMaterno'],
        'ApePaterno' => $_POST['ApePaterno'],
        'Contrasena' => $_POST['Contrasena']
    ];

    // Inicializar cURL
    $ch = curl_init($api_url);

    // Convertir los datos del usuario a formato JSON
    $jsonData = json_encode($updateUserData);

    // Configurar opciones de cURL para una solicitud POST con formato JSON
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
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

    // Procesar la respuesta de la API
    if ($response !== false) {
        $responseData = json_decode($response, true);        
        
        // Procesar la respuesta
        if ($responseData === true) {
            // Redirigir al usuario a la página de inicio de sesión
            header('Location: ../miinformacion.php');
            exit;
        } elseif ($responseData && isset($responseData['message'])) {
            // Mostrar mensaje de error si existe
            echo 'Error al registrar el usuario. Mensaje: ' . $responseData['message'];
        } else {
            // Manejar cualquier otro error
            echo 'Error desconocido al registrar el usuario. Detalles de la respuesta: ' . var_export($responseData, true);
        }
        
    } else {
        // Error al obtener la respuesta de la API
        echo 'Error al realizar la solicitud a la API.';
    }
    
    } else 
        // Si la solicitud no es POST, redirigir o manejar el error según tus necesidades
        echo 'Error: Solicitud no válida.';

?>