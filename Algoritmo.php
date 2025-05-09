<?php
// Configuración de email
$destinatario = "AGN@archivodigital.gob.do";  // Asegúrate de que este correo sea válido
$asunto = "Nuevo mensaje desde el formulario de contacto";

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger datos del formulario y sanear
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $asunto_mensaje = trim($_POST['asunto'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    // Validación básica
    $errores = [];

    if (empty($nombre)) $errores[] = "El nombre es obligatorio";
    if (empty($apellido)) $errores[] = "El apellido es obligatorio";
    if (empty($email)) {
        $errores[] = "El correo electrónico es obligatorio";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Por favor introduce un correo electrónico válido";
    }
    if (empty($mensaje)) $errores[] = "El mensaje es obligatorio";

    // Si no hay errores, enviar el correo
    if (empty($errores)) {
        $contenido = "Nombre: " . htmlspecialchars($nombre) . " " . htmlspecialchars($apellido) . "\n";
        $contenido .= "Email: " . htmlspecialchars($email) . "\n";
        $contenido .= "Teléfono: " . htmlspecialchars($telefono) . "\n";
        $contenido .= "Asunto: " . htmlspecialchars($asunto_mensaje) . "\n\n";
        $contenido .= "Mensaje:\n" . htmlspecialchars($mensaje) . "\n";

        // Cabeceras del correo
        $cabeceras = "From: " . filter_var($email, FILTER_SANITIZE_EMAIL) . "\r\n";
        $cabeceras .= "Reply-To: " . filter_var($email, FILTER_SANITIZE_EMAIL) . "\r\n";
        $cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Enviar email
        $envio_exitoso = mail($destinatario, $asunto, $contenido, $cabeceras);

        if ($envio_exitoso) {
            $mensaje_confirmacion = "¡Gracias por contactarnos! Tu mensaje ha sido enviado correctamente.";
        } else {
            $mensaje_error = "Lo sentimos, hubo un problema al enviar tu mensaje. Por favor intenta nuevamente.";
        }
    }
}
?>
