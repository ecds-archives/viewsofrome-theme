<?php
 /*
  * Tag template
  *
  *
  * @file       tag.php
  * @package    Views of rome
  * @author     Kyle Bock
  */
?>

<?php get_header(); ?>

<?php $page_title = ucwords(single_tag_title('', false)); ?>
<div id="content-blog" class="grid col-620">
    <?php include 'includes/breadcrumbs.php'; ?>
    <h1><?php echo $page_title; ?></h1>

    <?php include 'includes/list-article.php'; ?>
</div>
<?php get_sidebar('right'); ?>
<?php get_footer(); ?>
