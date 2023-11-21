<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el folio del reporte a eliminar
    $folio = $_POST['id']; // Ajusta esto según tu lógica

    // URL de la API
    $url = 'http://APIRestCorrupcionEQ5.somee.com/api/reportes/EliminarReporte?Folio='.$folio;

    // Inicializar cURL
    $curl = curl_init($url);

    // Configurar las opciones de la solicitud
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Realizar la solicitud y obtener la respuesta
    $response = curl_exec($curl);

    // Mostrar la respuesta de la API
    echo 'Respuesta de la API: ' . $response;

    if (curl_errno($curl)) {
        echo 'Error cURL: ' . curl_error($curl);
    }

    // Verificar si la solicitud fue exitosa
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($httpcode == 200) {
        //echo "Reporte eliminado correctamente.";
        header('Location: ../MisReportes.php');
            exit;
    } else {
        echo "Hubo un problema al eliminar el reporte. Código de estado: " . $httpcode;
    }

    // Cerrar la conexión cURL
    curl_close($curl);
}
?>