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

<div id="footer" class="clearfix">

    <div id="footer-wrapper">

        <div class="remove-bottom-margin grid col-940">

            <div class="emory-logo">
                <a href="http://web.library.emory.edu"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/eul-logo-h.png" height="100px" /></a>
            </div>
            
            <div class="footer-nav">
                <h2>Project Partners</h2>
                <ul>
                    <li><a href="https://marbl.library.emory.edu">Manuscripts, Archives, and Rare Book Library (MARBL)</a></li>
                    <li><a href="https://arthistory.library.emory.edu">Emory Art History Department</a></li>
                    <li><a href="https://web.library.emory.edu/disc"> Digital Scholarship Commons (DiSC)</a></li>
                    <li><a href="https://carlos.emory.edu">Michael C. CarlosCarlos Museum</a></li>
                </ul>
            </div>

            <div class="clearfix"></div>
            <!-- <div class="copyright">
                Copyright 2012 Emory University
            </div> -->
        </div><!-- end of col-940 -->

        <div class="clearfix"></div>
    </div><!-- end #footer-wrapper -->
    
</div><!-- end #footer -->

<?php wp_footer(); ?>
</body>
</html>