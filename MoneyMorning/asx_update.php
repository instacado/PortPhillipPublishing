<?php
function andy_addtocontent($andy_postarray) {
    $postcount = count($andy_postarray);
    $andy_posturl = get_permalink($andy_postarray[0]);
    for ($i=1; $i < $postcount; $i++) { 
        $content = get_post_field('post_content', $andy_postarray[$i]);
        $content = '<strong>UPDATE:</strong> Newer information can be found on this stock <a href="'.$andy_posturl.'">here</a><br/>'.$content;
        $andy_postargs = array('ID' => $andy_postarray[$i], 'post_content' => $content);
        wp_update_post($andy_postargs);
    }
}
function andy_fillpostarray($andy_asxcode) {
    $args = array('category' => '7', 'meta_key' => 'asx_code', 'meta_value' => $andy_asxcode);
    $andy_loadedcategory = get_posts($args);
    $andy_loadedcount = count($andy_loadedcategory);
    for ($i=0; $i < $andy_loadedcount; $i++) { 
        $andy_postarray[$i] = $andy_loadedcategory->ID;
    }
    return $andy_postarray;
}
function andy_postaction($new_status, $old_status, $post) {
    $andy_asxcode = get_post_meta($post->ID, 'asx_code', true);
    if ($new_status == 'publish') {
        $andy_postarray = andy_fillpostarray($andy_asxcode);
        andy_addtocontent($andy_postarray);
    }
}
add_action('transition_post_status', 'andy_postaction', 10, 3);
?>