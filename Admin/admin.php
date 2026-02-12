<?php

?>
<script>
  BR_AJAX_URL = '<?php echo esc_url(admin_url( 'admin-ajax.php', 'relative' )); ?>';
</script>
<h1>Booker Administration</h1>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="wpbakery-tab" data-bs-toggle="tab" data-bs-target="#wpbakery"
                type="button" role="tab" aria-controls="WPBakery Instructions" aria-selected="true">WP Bakery Directions</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="data-tab" data-bs-toggle="tab" data-bs-target="#data"
                type="button" role="tab" aria-controls="Data Management" aria-selected="false">Data & Language Management</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                type="button" role="tab" aria-controls="Contact" aria-selected="false">Contact</button>
    </li>
</ul>
<div class="tab-content" id="adminTabsContent">
    <div class="tab-pane fade show active" id="wpbakery" role="tabpanel" aria-labelledby="wpbakery-tab">
        <?php include(plugin_dir_path(__FILE__) . 'partial/wpbakery/wpbakery.php'); ?>
    </div>
    <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">
        <?php include( plugin_dir_path( __FILE__ ) . 'partial/data/data.php' ); ?>
        <?php wp_enqueue_script('data-js', PP_ADMIN_URL . '/partial/data/data.js"', array('jquery')); ?>
    </div>
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <?php include( plugin_dir_path( __FILE__ ) . 'partial/contact/contact.php' ); ?>
        <?php wp_enqueue_script('contact-js', PP_ADMIN_URL . '/partial/contact/contact.js', array('jquery')); ?>
    </div>
</div>