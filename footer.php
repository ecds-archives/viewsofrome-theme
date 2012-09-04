<?php
/**
 * Footer Template
 *
 *
 * @file           footer.php
 * @package        ViewsOfRome
 * @author         Kyle Bock
 * @filesource     wp-content/themes/responsive/footer.php
 * @link           http://codex.wordpress.org/Theme_Development#Footer_.28footer.php.29
 */
?>
    </div><!-- end of #wrapper -->
    <?php responsive_wrapper_end(); // after wrapper hook ?>
</div><!-- end of #container -->
<?php responsive_container_end(); // after container hook ?>

<style>
    .emory-logo,
    .footer-nav {
        padding:10px;
        color: white;
    }

    .emory-logo {
        float: left;
        width: 250px;
        margin-top: 15px;
    }

    .footer-nav {
        float:right;
        width: 250px;
    }

    .footer-nav h2 {
        margin: 0;
    }

    .footer-nav a {
        color: white;
    }

    #footer-wrapper {
        background: #333;
        border: 1px solid #444;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    .remove-bottom-margin {
        margin-bottom: 0;
    }

    .copyright {
        text-align: center;
        color: white;
    }
</style>
<div id="footer" class="clearfix">

    <div id="footer-wrapper">

        <div class="remove-bottom-margin grid col-940">

            <div class="emory-logo">
                <a href="http://web.library.emory.edu"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/eul-logo-h.png" height="100px" /></a>
            </div>
            
            <div class="footer-nav">
                <h2>Links</h2>
                <ul>
                    <li><a href="https://marbl.library.emory.edu">MARBL</a></li>
                    <li><a href="https://arthistory.library.emory.edu">Art History</a></li>
                    <li><a href="https://web.library.emory.edu/disc">DiSC</a></li>
                    <li><a href="https://carlos.emory.edu">Carlos Museum</a></li>
                </ul>
            </div>

            <div class="clearfix"></div>
            <div class="copyright">
                Copyright 2012 Emory University
            </div>
        </div><!-- end of col-940 -->

        <div class="clearfix"></div>
    </div><!-- end #footer-wrapper -->
    
</div><!-- end #footer -->

<?php wp_footer(); ?>
</body>
</html>