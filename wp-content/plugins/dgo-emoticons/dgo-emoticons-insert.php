<?php

?>
<div id="wp_emoticons">
    <?php
    global $wpsmiliestrans;
    
    foreach($wpsmiliestrans AS $code=>$filename){
        $path = includes_url().'images/smilies/'.$filename;
        $title = basename($path, ".gif");
        ?>
        <a href="javascript:grin('<?php echo addslashes($code); ?>')" ><img src="<?php echo $path; ?>"  alt="<?php echo $title; ?>"  title="<?php echo $title; ?>" /></a>
        <?php
    }
    ?>
</div>
<?php
    
?>


















