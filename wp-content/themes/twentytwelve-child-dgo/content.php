<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package Twenty_Twelve_Child_DGO
 * @since Twenty Twelve 1.0
 * @author Arjen Breur
 */
?>

<?php
// set additional acticle css classes
$addClasses = array('boxcontainer');
// ease our selection a little 
$is_single_or_page = (is_single() || is_page())?true:false;
if( $is_single_or_page   && in_array(get_post_type(),array('post','page')) ) $addClasses[] = ' post-header'; // post-content will be shown in different box;
if( is_single() 	     && in_array(get_post_type(),array('dgoquote','dgotransaction')) )    $addClasses[] = ' open'; // show quote content by opening the box
if( !is_user_logged_in() && !$is_single_or_page )                	         $addClasses[] = ' open'; // always show content for non-logged in users, except on single|page
$addClasses = array_unique($addClasses);
$addClasses = implode(" ", $addClasses);

// set box face content
$boxfaceContentFunc = array('front'=>NULL, 'top'=>NULL, 'back'=>NULL, 'bottom'=>NULL);
$tmpContentFuncOrder = array();
switch(get_post_type()){
	case 'page';
		array_push($tmpContentFuncOrder,
			array( 'func'=>'twentytwelve_child_dgo_the_excerpt',       			'args'=>array() ),
			array( 'func'=>'twentytwelve_child_dgo_the_access_groups', 			'args'=>array() )	
		);
		if ( is_singular() ) array_shift($tmpContentFuncOrder); // dont show excerpt on single page
		break;

	case 'post';
		if( is_singular() ){
			array_push($tmpContentFuncOrder,
				array( 'func'=>'twentytwelve_child_dgo_the_forum_excerpt', 			'args'=>array(NULL, 5) ),	
				array( 'func'=>'twentytwelve_child_dgo_the_access_groups', 			'args'=>array() )	
			);
		}else{
			array_push($tmpContentFuncOrder,
				array( 'func'=>'twentytwelve_child_dgo_the_excerpt_and_comments',	'args'=>array() ),
				array( 'func'=>'twentytwelve_child_dgo_the_forum_excerpt', 			'args'=>array(NULL, 7) ),	
				array( 'func'=>'twentytwelve_child_dgo_the_access_groups', 			'args'=>array() )	
			);
		}
		break;

	case 'dgoforumtopic';
		array_push($tmpContentFuncOrder,
			array( 'func'=>'twentytwelve_child_dgo_the_forum_excerpt', 			'args'=>array(NULL, 5) ),
			array( 'func'=>'twentytwelve_child_dgo_the_access_groups', 			'args'=>array() ),	
			array( 'func'=>'twentytwelve_child_dgo_the_forum_statistics',		'args'=>array() )	
		);
		if ( is_singular() ) array_shift($tmpContentFuncOrder); // dont show excerpt on single page
		break;

	case 'dgoquote';
		array_push($tmpContentFuncOrder,
			array( 'func'=>'twentytwelve_child_dgo_the_excerpt',     			'args'=>array() ),
			array( 'func'=>'twentytwelve_child_dgo_the_access_groups', 			'args'=>array() )	
		);
		break;

	case 'dgotransaction';
		array_push($tmpContentFuncOrder,
			array( 'func'=>'twentytwelve_child_dgo_the_excerpt',     			'args'=>array() )
		);
		break;

	case 'dgoalbum';
		array_push($tmpContentFuncOrder,
			array( 'func'=>'twentytwelve_child_dgo_the_excerpt',     			'args'=>array() ),
			array( 'func'=>'twentytwelve_child_dgo_the_access_groups', 			'args'=>array() )	
		);
		break;

	default:
		array_push($tmpContentFuncOrder,
			array( 'func'=>'twentytwelve_child_dgo_the_excerpt',     			'args'=>array() )
		);
}

// load the face content array with the content functions
foreach($boxfaceContentFunc as $boxface=>&$content){
	$content = array_shift($tmpContentFuncOrder);
}

?>

    <article id="post-<?php the_ID(); ?>" <?php post_class($addClasses); ?>>
        <div class="box show-front">
            <?php 
            foreach(array('front','back','top','bottom') AS $face){
            	?>
	            <section class="boxface <?php echo $face;?>">
	            	<?php if ('front' == $face ){ ?>		
		                <header class="entry-header">
		                    <?php the_post_thumbnail(); ?>
		                    <?php if ( is_single() ) : ?>
		                    	<h1 class="entry-title"><?php the_title(); ?></h1>
		                    <?php else : ?>
			                    <h1 class="entry-title">
			                        <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentytwelve' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			                    </h1>
		                    <?php endif; // is_single() ?>
		                </header><!-- .entry-header -->
	            	<?php
	            	}
					if ( post_password_required() ) { echo get_the_password_form(); }
					if( function_exists( $boxfaceContentFunc[$face]['func'] ) ) call_user_func_array( $boxfaceContentFunc[$face]['func'], $boxfaceContentFunc[$face]['args'] );
					?>            
	            </section><!-- .boxface.<?php echo $face; ?> -->
	            <?php
            }
			?>            
        </div><!-- .box -->
	</article><!-- #post* .boxcontainer -->
	
	<?php if( $is_single_or_page && in_array(get_post_type(),array('post','page')) ) : ?>
	<article <?php post_class('post-content'); ?> >
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</article>
	<?php endif;
	
