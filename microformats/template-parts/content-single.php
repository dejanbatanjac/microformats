<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package microformats
 */

?>
<?php global $first_post; ?>

<article id="post-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/Article" <?php post_class(); ?>>
	<?php $canonical = get_permalink(  $post->ID ); 
	$description = sanitize_text_field($post->post_excerpt); 
	$published = get_post_time( "c");
	$modified = get_the_modified_time( "c" );


	?>
	<meta itemscope itemprop="mainEntityOfPage"  itemType="https://schema.org/WebPage" itemid="<?php echo $canonical; ?>" />
	<meta itemprop="datePublished" content="<?php echo $published; ?>"/>
    <meta itemprop="dateModified" content="<?php echo $modified; ?>"/>

    <?php if(!empty($description)) { ?>
	<strong>Description:</strong> <span itemprop="about" class="ita" ><?php echo $description; ?></span>
	<?php } ?>

	<header class="entry-header">
		
		<?php microformats_featured_image() ?>
		
		<?php 
			if ( $first_post == true ) {
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			} else {
				the_title( '<h1 itemprop="headline" class="entry-title">', '</h1>' ); 				
			}
		
		?>

		<?php
		if ( has_excerpt( $post->ID ) ) {
			echo '<div class="deck">';
			echo '<p>' . get_the_excerpt() . '</p>';
			echo '</div><!-- .deck -->';
		}
		?>
		
		<div class="entry-meta">
			<?php microformats_posted_on(); ?>
			<?php microformats_publisher(); ?>
			
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'microformats' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php microformats_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

