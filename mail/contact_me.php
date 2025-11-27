<?php
// Check for empty fields
if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }
	
$name = $_POST['name'];
$email_address = $_POST['email'];
$message = $_POST['message'];
	
// Create the email and send the message
$to = 'iotrack2025@gmail.com'; // Dirección de email de tu empresa
$email_subject = "Formulario de Contacto: $name"; // Asunto del email personalizado
$email_body = "Has recibido un nuevo mensaje desde el formulario de contacto de tu página web TRACKLY.\n\n"."Aquí están los detalles:\n\nNombre: $name\n\nEmail: $email_address\n\nMensaje:\n$message"; // Cuerpo del email personalizado
$headers = "From: iotrack2025@gmail.com\n"; // Dirección de correo del remitente
$headers .= "Reply-To: $email_address"; // Dirección de respuesta
mail($to, $email_subject, $email_body, $headers);
return true;			
?>
