/**
 * Custom shortcode to display form entries in a table.
 * And Show the current user's name 
 * Usage [wpforms_entries_table id="FORMID"]
 *
 * @param array $atts
 */
    $current_user = wp_get_current_user();


function wpf_dev_entries_table( $atts ) {
    $atts = shortcode_atts( array(
        'id' => ''
    ), $atts );
    if ( empty( $atts['id'] ) || !function_exists( 'wpforms' ) ) {
        return;
    }
    $form = wpforms()->form->get( absint( $atts['id'] ) );
    if ( empty( $form ) ) {
        return;
    }
    $form_data = !empty( $form->post_content ) ? wpforms_decode( $form->post_content ) : '';
    $entries   = wpforms()->entry->get_entries( array( 'form_id' => absint( $atts['id'] ), 'number' => -1 ) );
    $disallow  = apply_filters( 'wpforms_frontend_entries_table_disallow', array( 'divider', 'html', 'pagebreak', 'captcha' ) );
    $ids       = array();
    ob_start();
    echo '<table class="wpforms-frontend-entries">';
        echo '<thead><tr>';
            foreach( $form_data['fields'] as $field ) {
                if ( !in_array( $field['type'], $disallow  ) ) {
                    $ids[] = $field['id'];
             
                
                    echo '<th>' . sanitize_text_field( $field['label'] ) . '</th>';
                   
                    
                }
            }
        echo '</tr></thead>';
        echo '<tbody>';
            foreach( $entries as $entry ) {
                echo '<tr>';
                $fields = wpforms_decode( $entry->fields );
                foreach( $fields as $field ) {
                    if ( in_array( $field['id'], $ids ) ) {
                        echo '<td>' . apply_filters( 'wpforms_html_field_value', wp_strip_all_tags( $field['value'] ), $field, $form_data, 'entry-frontend-table' );
                    }
                }
                echo '</tr>';
            }
        echo '</tbody>';
    echo '</table>';
 
    $output = ob_get_clean();
    return $output;
}
add_shortcode( 'wpforms_entries_table', 'wpf_dev_entries_table' );
