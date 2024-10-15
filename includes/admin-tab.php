<?php
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
    
    include NEOTEL_INTEGRATION_PATH . 'templates/admin-tab-content.php';
}