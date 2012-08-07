<?php
/**
 *  Map Manager Legend Template
 * 
 * @author Kyle Bock
 */
?>
<script type="text/javascript">
$(function() {

    // $('input[type=checkbox]').each(function() {
    //     var span = $('<span class="' + $(this).attr('type') + ' ' + $(this).attr('class') + '"></span>').click(doCheck).mousedown(doDown).mouseup(doUp);
    //     if ($(this).is(':checked')) {
    //         span.addClass('checked');
    //     }
    //     $(this).wrap(span).hide();
    // });

    function doCheck() {
        if ($(this).hasClass('checked')) {
            $(this).removeClass('checked');
            $(this).children().prop("checked", false);
        } else {
            $(this).addClass('checked');
            $(this).children().prop("checked", true);
        }
    }

    function doDown() {
        $(this).addClass('clicked');
    }

    function doUp() {
        $(this).removeClass('clicked');
    }
});
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
        margin: 0;
        color: #ffffff !important;
    }

    .checkbox, .radio {
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
     }
</style>
<div id="legend">
    <?php 
        $args = array(
            'hide_empty'    => false
        );
        $categories = get_categories($args);
    ?>
    <h2>Categories</h2>
    <ul id="category-list">
        <li>
            <span style="background-color: #ff0000;"><input type="checkbox" class="checkbox" value="all" checked="checked"/></span>All
        </li>
    <?php foreach ($categories as $category) : ?>
        <li>
            <span style="background-color: #ff0000;"><input type="checkbox" class="checkbox" value="<?php echo $category->cat_ID; ?>" checked="checked"/></span><?php echo $category->name; ?>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<div class="clearfix"></div>