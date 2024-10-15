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