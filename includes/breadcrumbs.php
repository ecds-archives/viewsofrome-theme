<?php
/*
 *
 */
?>

<?php $options = get_option('responsive_theme_options'); ?>
<?php if ($options['breadcrumb'] == 0): ?>
    <?php echo responsive_breadcrumb_lists(); ?>
<?php endif; ?>
