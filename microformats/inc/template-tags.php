<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package microformats
 */

if ( ! function_exists( 'microformats_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function microformats_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'published %s', 'post date', 'microformats' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'microformats' ),
		'<span class="author vcard" itemprop="author" itemtype="https://schema.org/Person"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);
	
	// Display the author avatar if the author has a Gravatar
	$author_id = get_the_author_meta( 'ID' );
	if ( microformats_validate_gravatar( $author_id ) ) {
		echo '<div class="meta-content has-avatar">';
		echo '<div class="author-avatar">' . get_avatar( $author_id ) . '</div>';
	} else {
		echo '<div class="meta-content">';
	}
	
	echo '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'microformats' ), esc_html__( '1 Comment', 'microformats' ), esc_html__( '% Comments', 'microformats' ) );
		echo '</span>';
	}
	echo '</div><!-- .meta-content -->';
}
endif;


if ( ! function_exists( 'microformats_publisher' ) ) :
function microformats_publisher() {?>

<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">  	
	<?php 
	if(has_site_icon()){
		$ico = get_site_icon_url();
	 ?>
	<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">  
	  <meta itemprop="url" content="<?php echo esc_url($ico) ?>">
      <meta itemprop="width" content="16">
      <meta itemprop="height" content="16">
    </div>
    <?php }	?>	
	<?php $url = site_url( '/'); ?>
	<meta itemprop="name" content="<?php echo esc_url($url) ?>">

</div><?php
}
endif;


if ( ! function_exists( 'microformats_featured_image' ) ) :
function microformats_featured_image(){
	if ( has_post_thumbnail() ) { 

		global $post;
		$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), null );
		//var_dump($image_data);
		
		$uri = $image_data[0];
		$width = $image_data[1];
		$height = $image_data[2];
		?>

		<figure class="featured-image" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
		<meta itemprop="url" content="<?php echo esc_url($uri) ?>">
		<meta itemprop="width" content="<?php echo intval($width) ?>">
   		<meta itemprop="height" content="<?php echo intval($height) ?>">
		<?php the_post_thumbnail(); ?>
		</figure>
		<?php }
	}
endif;

if ( ! function_exists( 'microformats_index_posted_on' ) ) :
/**
 * Prints HTML with meta information for post-date/time and author on index pages.
 */
function microformats_index_posted_on() {
	
	$author_id = get_the_author_meta( 'ID' );
	
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Published %s', 'post date', 'microformats' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'microformats' ),
		'<span class="author vcard" itemprop="author" itemtype="https://schema.org/Person"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);
	
	echo '<div class="meta-content">';
	echo '<span class="byline">' . $byline . ' </span><span class="posted-on">' . $posted_on . ' </span>'; // WPCS: XSS OK.
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'microformats' ), esc_html__( '1 Comment', 'microformats' ), esc_html__( '% Comments', 'microformats' ) );
		echo '</span>';
	}
	echo '</div><!-- .meta-content -->';

}
endif;


if ( ! function_exists( 'microformats_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function microformats_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'microformats' ) );
		if ( $categories_list && microformats_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'microformats' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'microformats' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'microformats' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'microformats' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function microformats_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'microformats_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'microformats_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so microformats_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so microformats_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in microformats_categorized_blog.
 */
function microformats_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'microformats_categories' );
}
add_action( 'edit_category', 'microformats_category_transient_flusher' );
add_action( 'save_post',     'microformats_category_transient_flusher' );


/**
 * Utility function to check if a gravatar exists for a given email or id
 * @param int|string|object $id_or_email A user ID,  email address, or comment object
 * @return bool if the gravatar exists or not
 * Original found at https://gist.github.com/justinph/5197810
 */

function microformats_validate_gravatar($id_or_email) {
  //id or email code borrowed from wp-includes/pluggable.php
	$email = '';
	if ( is_numeric($id_or_email) ) {
		$id = (int) $id_or_email;
		$user = get_userdata($id);
		if ( $user )
			$email = $user->user_email;
	} elseif ( is_object($id_or_email) ) {
		// No avatar for pingbacks or trackbacks
		$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
		if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
			return false;

		if ( !empty($id_or_email->user_id) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_userdata($id);
			if ( $user)
				$email = $user->user_email;
		} elseif ( !empty($id_or_email->comment_author_email) ) {
			$email = $id_or_email->comment_author_email;
		}
	} else {
		$email = $id_or_email;
	}

	$hashkey = md5(strtolower(trim($email)));
	$uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';

	$data = wp_cache_get($hashkey);
	if (false === $data) {
		$response = wp_remote_head($uri);
		if( is_wp_error($response) ) {
			$data = 'not200';
		} else {
			$data = $response['response']['code'];
		}
	    wp_cache_set($hashkey, $data, $group = '', $expire = 60*5);

	}		
	if ($data == '200'){
		return true;
	} else {
		return false;
	}
}

/**
 * Customize the excerpt read-more indicator
 */
function microformats_excerpt_more( $more ) {
	return "...";
}
add_filter( 'excerpt_more', 'microformats_excerpt_more' );


if ( ! function_exists( 'microformats_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 * 
 */

function microformats_paging_nav() {
	global $wp_query, $wp_rewrite;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $wp_query->max_num_pages,
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '&larr; Pre', 'microformats' ),
		'next_text' => __( 'Next &rarr;', 'microformats' ),
		'type'		=> 'list',
	) );

	if ( $links ) :

	?>
	<nav class="navigation paging-navigation" role="navigation">
		<div class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'microformats' ); ?></div>
		<?php echo $links; ?>
	</nav><!-- .navigation -->
	<?php
	endif;
}
endif;
