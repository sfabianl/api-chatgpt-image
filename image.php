<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar imagen</title>
</head>
<body>
    <h1>Generar imagen</h1>
    <form action="" method="post">
        <label for="query">Escribe la descripción de la imagen:</label>
        <input type="text" id="query" name="query" required>
        <input type="submit" value="Generar">
    </form>
    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuración

    $user_query = $_POST["query"];

    $url  = "https://api.openai.com/v1/images/generations";
    $data = array(
        "model"           => "image-alpha-001",
        "prompt"          => $user_query,
        "num_images"      => 1,
        "size"            => "256x256",
        "response_format" => "url",
    );
    $data_string = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer <API_KEY>',
    ));

    $response = curl_exec($ch);

    if ($response === false) {
        // Manejar el error de conexión
        echo 'Error en la conexión: ' . curl_error($ch);
    } else {
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200) {
            $result    = json_decode($response, true);
            $image_url = $result["data"][0]["url"];
            echo '<p>Imagen generada:</p>';
            echo '<img src="' . $image_url . '" alt="Imagen generada">';
        } else {
            // Manejar otros errores en la respuesta
            echo "Error en la respuesta: Código de estado HTTP $http_code";
        }
    }

    curl_close($ch);
}
?>
</body>
</html>
