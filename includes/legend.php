<?php
/**
 *  Map Manager Legend Template
 * 
 * @author Kyle Bock
 */
?>
<script type="text/javascript">

</script>
<style type="text/css">
    #legend {
        margin-top: 20px;
        background: #222;
        border-radius: 5px;
        border: 1px solid #444;
        padding: 5px;
    }

    #legend h3 {
        margin: 0 0 10px 0;
    }

    #legend, #category-list {
        color: #fff;
    }

    ul#category-list {
        list-style: none;
        margin: 0;
    }
    #legend #category-list li {
        margin: 0 0 5px 0;
        color: #ffffff !important;
        font-size: 85%;
    }

 /*   .checkbox, .radio {
        width: 19px;
        height: 20px;
        padding: 0px;
        background: url("http://i48.tinypic.com/raz13m.jpg");
        display: block;
        clear: left;
        float: left;
     }

    .checked {
         background-position: 0px -50px;   
    }

    .clicked {
         background-position: 0px -25px;
    }

    .clicked.checked {
         background-position: 0px -75px;
    }


    .green {
        background-color: green;
     }

     .red {
        background-color: red;
     }

    .purple {
        background-color: purple;
     }*/
     span.category-name {
        margin-left: 5px;
     }
     span.color-block {
        display: inline-block;
        width: 15px;
        height: 15px;
        float: right;
        border: 1px solid #444;
        border-radius: 5px;
     }
</style>
<div id="legend">
    <?php 
        $args = array(
            'hide_empty'    => false
        );
        $categories = get_categories($args);

        $category_colors = get_option('vor_category_colors');
    ?>
    <h2>Categories</h2>
    <ul id="category-list">
        <li>
            <input type="checkbox" class="checkbox" value="all" checked="checked"/>
            <span class="color-block" style="background-color: none;border:none;"></span>
            <span class="category-name">All</span>
        </li>
    <?php foreach ($categories as $category) : ?>
        <li>
            <span class="color-block"  style="background-color:<?php echo $category_colors[$category->cat_ID]; ?>;"></span>
            <span style="display:table-cell;">
                <input type="checkbox" class="checkbox" value="<?php echo $category->cat_ID; ?>" checked="checked"/>
                <span class="category-name">
                    <?php echo $category->name; ?> (<?php echo $category->category_count; ?>)
                </span>
            </span>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<div class="clearfix"></div>