<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Lt_Theme
 * @since Ltheme 1.0
 */
?>
		</div><!-- .site-content -->
	</div><!-- .site-inner -->
	    <!-- footer -->
    <div id="main-footer" class="main-footer">
				<!-- Right Menu -->
		<?php
		if ( is_active_sidebar( 'custom-header-widget' ) ) : ?>
		    <div id="top-bottom" class="top-bottom widget-area" role="complementary">
			    <div class="container">
					<?php dynamic_sidebar( 'custom-header-widget' ); ?>
				</div> <!-- /End container -->
		    </div>	
		<?php endif; ?>
		<!-- End Right Menu -->
    	<div class="container">
	        <!-- 1/4 -->
	        <div class="four columns footer1">
	            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('footer-1-widget') ) ?>
	        </div>
	        <!-- /End 1/4 -->
	        <!-- 2/4 -->
	        <div class="four columns footer2">
	            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('footer-2-widget') ) ?>
	        </div>
	        <!-- /End 2/4 -->
	        <!-- 3/4 -->
	        <div class="four columns footer3">
	            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('footer-3-widget') ) ?>
	        </div>
	        <!-- /End 3/4 -->
	        <!-- 4/4 -->
	        <div class="four columns footer4">
	            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('footer-4-widget') ) ?>
	        </div>
	        <!-- /End 4/4 -->
	    </div> <!-- /End container -->
    </div>
    <!-- /End Footer -->
		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php
				{
				?>
				<div class="footer-custom-code">
						<?php
						dynamic_sidebar('custom-footer');
						?>	
				</div>
				<?php	
				}
			?>
			<div class="site-info container">
				<?php
					/**
					 * Fires before the ltheme footer text for footer customization.
					 *
					 * @since Ltheme 1.0
					 */
					do_action( 'ltheme_credits' );
				?>
			    <div class="lt-footer">
			        <p class="lt-footer-left"><a href="https://ltheme.com/wordpress-themes/" target="_blank" title="Free Wordpress themes">Free Wordpress themes</a>  by <a href="https://ltheme.com/" target="_blank" title="Joomla templates & Wordpress themes Marketplace">L.Theme</a>
			        </p>
			    </div>
			</div><!-- .site-info -->
		</footer><!-- .site-footer -->
<?php wp_footer(); ?>
</div><!-- .site -->
</body>
</html>
