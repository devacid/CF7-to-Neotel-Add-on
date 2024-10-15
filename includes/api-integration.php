<?php
// Función para enviar datos a la API de Neotel después de enviar el formulario
add_action('wpcf7_mail_sent', 'send_data_to_neotel_api');

function send_data_to_neotel_api($contact_form) {
    $form_id = $contact_form->id();
    
    // Verificar si la integración está activa
    $integration_active = get_post_meta($form_id, 'neotel_integration_active', true);
    if ($integration_active !== '1') {
        return; // Si no está activa, no hacemos nada
    }

    $submission = WPCF7_Submission::get_instance();
    
    if (!$submission) {
        return;
    }

    // Obtener la configuración guardada
    $id_task = get_post_meta($form_id, 'neotel_id_task', true);
    $neotel_url = get_post_meta($form_id, 'neotel_url', true);
    $neotel_params = get_post_meta($form_id, 'neotel_params', true);

    if (empty($id_task) || empty($neotel_url) || !is_array($neotel_params)) {
        return;
    }

    // Obtener los datos enviados en el formulario
    $posted_data = $submission->get_posted_data();

    // Preparar los parámetros para la API
    $api_params = array();
    foreach ($neotel_params as $key => $value) {
        if ($key === 'IDLOTE' || $key === 'USUARIO_PREASIGNADO') {
            $api_params[$key] = $value;
        } else {
            // Si el valor es un campo de CF7, obtener el valor enviado
            $form_value = isset($posted_data[trim($value, '[]')]) ? $posted_data[trim($value, '[]')] : $value;

            // Verificar si el valor es un array y convertirlo a una cadena
            if (is_array($form_value)) {
                $form_value = implode(', ', $form_value); // Convierte el array a una cadena, separada por comas
            }

            $api_params[$key] = $form_value;
        }
    }

    // Crear el cuerpo de la solicitud
    $body = array(
        'idTask' => $id_task,
        'param1' => json_encode(array($api_params))
    );

    // Realizar la llamada a la API
    $response = wp_remote_post($neotel_url, array(
        'method' => 'POST',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
        'body' => $body
    ));

    // Manejar la respuesta (puedes personalizar esto según tus necesidades)
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        error_log("Error en la llamada a la API de Neotel: $error_message");
    } else {
        $response_body = wp_remote_retrieve_body($response);
        error_log("Respuesta de la API de Neotel: $response_body");
    }
}