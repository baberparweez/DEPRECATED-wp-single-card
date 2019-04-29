<?php

/**
 * Create multi dropdown param type
 */
if (function_exists('vc_add_shortcode_param')) {
    vc_add_shortcode_param( 'dropdown_multi', 'dropdown_multi_settings_field' );
}
function dropdown_multi_settings_field( $param, $value ) {
    $param_line = '';
    $param_line .= '<select name="'. esc_attr($param['param_name']).'" class="wpb_vc_param_value wpb-input wpb-select '. esc_attr( $param['param_name'] ).' '. esc_attr($param['type']).'">';

    $param_line .= '<option value="">Select a post...</option>';

    foreach ($param['value'] as $post_type => $posts) {

        $param_line .= '<optgroup label="'.ucfirst($post_type).'">';

        foreach ($posts as $post_id) {

            $selected = '';

            if(!is_array($value)) {
                $param_value_arr = explode(',',$value);
            } else {
                $param_value_arr = $value;
            }

            if ($value !== '' && in_array($post_id, $param_value_arr)) {
                $selected = 'selected="selected"';
            }

            $title = get_the_title($post_id);
            $param_line .= '<option value="'.$post_id.'" '.$selected.'>'.$title.'</option>';
        }

        $param_line .= '</optgroup>';
    }

    $param_line .= '</select>';

    return  $param_line;
}

/**
 * Use select2 for all VC dropdowns
 */
add_filter( 'vc_edit_form_fields_optional_params', function( $array ){

    ?>
    <style>
        body > .select2-container {
            z-index: 100000 !important;
        }
        .edit_form_line .select2-container {
            width: 100% !important;
        }
    </style>

    <script>
        !function($){try{$('.wpb-select').select2();}catch(e){}}(window.jQuery);
    </script>
    <?php

    return $array;
});
