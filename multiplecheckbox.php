<?php
add_action( 'add_meta_boxes', function() {
    add_meta_box( 'custom-metabox', 'Select Hotels', 'fill_metabox', 'tour', 'normal','high' );
});

function fill_metabox( $post ) {
   // Add nonce for security and authentication.
wp_nonce_field( 'autore_id_nonce_action', 'autore_id_nonce' );
// Retrieve an existing value from the database.
$autore_id = get_post_meta( $post->ID, 'autore_id', true );
//print_r($autore_id);
global $post; // required
$args=array(
'post_type'=>'hotel',
'post_per_page'=>-1,
'orderby'=> ID,
'order' => ASC
);
$custom_posts = get_posts($args);

foreach($custom_posts as $p) :
$checked = "";
if(is_array( $autore_id )){
if( in_array( $p->ID, $autore_id ) )
$checked = 'checked="checked"';
}

echo '<p><label><input type="checkbox"
name="autore_id[]"
class="check_autori_field"
value="' . $p->ID . '" ' . $checked . '> ' . $p->post_title . '</label><p>';
endforeach;
}


add_action( 'save_post', function( $post_id ) {
    
    // Add nonce for security and authentication.
$nonce_name = isset( $_POST['autore_id_nonce'] ) ? $_POST['autore_id_nonce'] : '';
$nonce_action = 'autore_id_nonce_action';

// Check if a nonce is set.
if ( ! isset( $nonce_name ) )
return;

// Check if a nonce is valid.
if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
return;

// Check if the user has permissions to save data.
if ( ! current_user_can( 'edit_post', $post_id ) )
return;

// Check if it's not an autosave.
if ( wp_is_post_autosave( $post_id ) )
return;

// Check if it's not a revision.
if ( wp_is_post_revision( $post_id ) )
return;

$data = $_POST['autore_id'];
if($data)
update_post_meta( $post_id, 'autore_id', $data );


});
