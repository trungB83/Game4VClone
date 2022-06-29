<?php
/**
 * The template for the sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Lt_Theme
 * @since Ltheme 1.0
 */
?>
<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<aside id="sidebar-right-blog" class="sidebar widget-area sidebar-right-blog sidebar-right" role="complementary">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>
<?php if ( is_active_sidebar( 'sidebar-right-ws' ) ) : ?>
	<aside id="sidebar-right-ws" class="sidebar widget-area sidebar-right-ws sidebar-right" role="complementary">
		<?php dynamic_sidebar( 'sidebar-right-ws' ); ?>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>
<?php if ( is_active_sidebar( 'sidebar-left-blog' ) ) : ?>
	<aside id="sidebar-left-blog" class="sidebar widget-area sidebar-left-blog sidebar-left" role="complementary">
		<?php dynamic_sidebar( 'sidebar-left-blog' ); ?>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>
<?php if ( is_active_sidebar( 'sidebar-left-ws' ) ) : ?>
	<aside id="sidebar-left-ws" class="sidebar widget-area sidebar-left-ws sidebar-left" role="complementary">
		<?php dynamic_sidebar( 'sidebar-left-ws' ); ?>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>





