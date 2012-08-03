<?php
/**
 *  Map Manager Legend Template
 * 
 * @author Kyle Bock
 */
?>
<style type="text/css">
    #legend, #category-list {
        color: #fff;
    }

    ul#category-list {
        list-style: none;
    }
    #legend #category-list li {
        float: left;
        margin: 0 15px 0 0;
        color: #ffffff !important;
    }
</style>
<div id="legend">
    <?php 
        $args = array(
            'hide_empty'    => false
        );
        $categories = get_categories($args);
    ?>
    <ul id="category-list">
    <?php foreach ($categories as $category) : ?>
        <li>
            <input type="checkbox" class="filterbox" value="<?php echo $category->cat_ID; ?>" /><?php echo $category->name; ?>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<div class="clearfix"></div>