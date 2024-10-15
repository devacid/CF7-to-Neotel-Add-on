// Agregar pestaña "Integración Neotel" a Contact Form 7
add_filter('wpcf7_editor_panels', 'add_neotel_integration_tab');

function add_neotel_integration_tab($panels) {
    $panels['neotel-integration-panel'] = array(
        'title' => 'Integración Neotel',
        'callback' => 'neotel_integration_tab_content'
    );
    return $panels;
}

// Contenido de la pestaña "Integración Neotel"
function neotel_integration_tab_content($post) {
    $form_id = $post->id();
    $integration_active = get_post_meta($form_id, 'neotel_integration_active', true);
    $id_task = get_post_meta($form_id, 'neotel_id_task', true);
    $neotel_url = get_post_meta($form_id, 'neotel_url', true);
    $neotel_params = get_post_meta($form_id, 'neotel_params', true);
    if (!is_array($neotel_params)) {
        $neotel_params = array(
            'IDLOTE' => '',
            'USUARIO_PREASIGNADO' => ''
        );
    }
    ?>
    <h2>Configuración de Integración Neotel</h2>
    <fieldset>
        <legend>Activar Integración</legend>
        <label>
            <input type="checkbox" name="neotel_integration_active" value="1" <?php checked($integration_active, '1'); ?> />
            Activar integración con Neotel
        </label>
    </fieldset>
    <fieldset>
        <legend>URL de Neotel</legend>
        <input type="url" name="neotel_url" class="large-text" value="<?php echo esc_url($neotel_url); ?>" />
    </fieldset>
    <fieldset>
        <legend>ID de Tarea Neotel</legend>
        <input type="text" name="neotel_id_task" class="large-text" value="<?php echo esc_attr($id_task); ?>" />
    </fieldset>

    <h2>Campos del formulario disponibles</h2>
    <fieldset class="tag-cloud">
        <?php
        $form_tags = $post->scan_form_tags();
        foreach ($form_tags as $tag) {
            if (!empty($tag['name'])) {
                echo sprintf(
                    '<span class="mailtag code">[%1$s]</span>',
                    esc_html($tag['name'])
                );
            }
        }
        ?>
    </fieldset>

    <h2>Parámetros</h2>
    <fieldset>
        <table id="neotel-params-table" class="widefat">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($neotel_params as $key => $value): ?>
                <tr>
                    <td><input type="text" name="neotel_params[keys][]" value="<?php echo esc_attr($key); ?>" <?php echo in_array($key, ['IDLOTE', 'USUARIO_PREASIGNADO']) ? 'readonly' : ''; ?>></td>
                    <td><input type="text" name="neotel_params[values][]" value="<?php echo esc_attr($value); ?>"></td>
                    <td><?php if (!in_array($key, ['IDLOTE', 'USUARIO_PREASIGNADO'])): ?><button type="button" class="button remove-row">Eliminar</button><?php endif; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="button" id="add-param-row">Agregar Parámetro</button>
    </fieldset>

    <script>
    jQuery(document).ready(function($) {
        $('#add-param-row').on('click', function() {
            var row = '<tr>' +
                '<td><input type="text" name="neotel_params[keys][]" value=""></td>' +
                '<td><input type="text" name="neotel_params[values][]" value=""></td>' +
                '<td><button type="button" class="button remove-row">Eliminar</button></td>' +
                '</tr>';
            $('#neotel-params-table tbody').append(row);
        });

        $('#neotel-params-table').on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });

        // Agregar funcionalidad para copiar al hacer clic en las etiquetas de campo
        $('.mailtag.code').on('click', function() {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(this).text()).select();
            document.execCommand("copy");
            $temp.remove();
            alert('Etiqueta copiada: ' + $(this).text());
        });
    });
    </script>
    <style>
		#neotel-params-table {
			margin-bottom: 1rem!important;
		}
		
		fieldset {
			margin-bottom: 1rem;
		}
		
		.tag-cloud {
			display: flex;
			gap: .25rem .5rem;
			flex-wrap: wrap;
		}
		
		.mailtag {
			display: inline-block;
			font-weight: 700;
			margin: 2px 0;
			padding: 3px 5px;
			background-color: #f0f0f0;
			border: 1px solid #ccc;
			border-radius: 3px;
			cursor: pointer;
		}
		
		.mailtag:hover {
			background-color: #e0e0e0;
		}
    </style>
    <?php
}

// Guardar los valores de los campos personalizados
add_action('wpcf7_save_contact_form', 'save_neotel_integration_fields');

function save_neotel_integration_fields($contact_form) {
    $form_id = $contact_form->id();
    
    $integration_active = isset($_POST['neotel_integration_active']) ? '1' : '0';
    update_post_meta($form_id, 'neotel_integration_active', $integration_active);
    
    if (isset($_POST['neotel_id_task'])) {
        $id_task = sanitize_text_field($_POST['neotel_id_task']);
        update_post_meta($form_id, 'neotel_id_task', $id_task);
    }
    
    if (isset($_POST['neotel_url'])) {
        $url = esc_url_raw($_POST['neotel_url']);
        update_post_meta($form_id, 'neotel_url', $url);
    }
    
    if (isset($_POST['neotel_params'])) {
        $params = array();
        $keys = $_POST['neotel_params']['keys'];
        $values = $_POST['neotel_params']['values'];
        foreach ($keys as $index => $key) {
            if (!empty($key)) {
                $params[sanitize_text_field($key)] = sanitize_text_field($values[$index]);
            }
        }
        update_post_meta($form_id, 'neotel_params', $params);
    }
}

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