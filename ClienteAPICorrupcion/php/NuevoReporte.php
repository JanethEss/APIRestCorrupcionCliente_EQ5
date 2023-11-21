<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // URL de la API REST para registrar un nuevo reporte
    $api_url = 'http://APIRestCorrupcionEQ5.somee.com/api/reportes/GuardarReporte';

    // Datos del nuevo reporte
    $newReportData = [
        'Descripcion' => $_POST['Descripcion'],
        'Fecha_Reporte' => $_POST['Fecha_Reporte'],       
        'Status' => $_POST['Status'],
        'Usuario' => $_POST['Usuario']
    ];

    // Inicializar cURL
    $ch = curl_init($api_url);

    // Convertir los datos del usuario a formato JSON
    $jsonData = json_encode($newReportData);

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
            // Redirigir al usuario a la página de mis reportes
            header('Location: ../MisReportes.php');
            exit;
        } elseif ($result && isset($result['message'])) {
            // Mostrar mensaje de error si existe
            echo 'Error al ingresar el nuevo reporte. Mensaje: ' . $result['message'];
        } else {
            // Manejar cualquier otro error
            echo 'Error desconocido al ingresar el nuevo reporte. Detalles de la respuesta: ' . var_export($result, true);
        }
    } else 
        // Si la solicitud no es POST, redirigir o manejar el error según tus necesidades
        echo 'Error: Solicitud no válida.';

?>