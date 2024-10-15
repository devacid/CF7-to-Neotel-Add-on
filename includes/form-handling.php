<?php
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