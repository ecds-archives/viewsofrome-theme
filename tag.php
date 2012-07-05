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
<?php $execute_query = false; ?>
<?php include 'includes/list-article.php'; ?>

<?php get_footer(); ?>
