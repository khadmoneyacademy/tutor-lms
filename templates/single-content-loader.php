<?php
/**
 * Template for displaying single lesson, assignment, quiz etc.
 *
 * @package Tutor\Templates
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 1.0.0
 */

global $post;
//phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
$currentPost = $post;

$method_map = array(
	'lesson'     => 'tutor_lesson_content',
	'assignment' => 'tutor_assignment_content',
);

$content_id  = tutor_utils()->get_post_id();
$course_id   = tutor_utils()->get_course_id_by_subcontent( $content_id );
$contents    = tutor_utils()->get_course_prev_next_contents_by_id( $content_id );
$previous_id = $contents->previous_id;
$next_id     = $contents->next_id;

$enable_spotlight_mode = tutor_utils()->get_option( 'enable_spotlight_mode' );
//phpcs:ignore WordPress.PHP.DontExtract.extract_extract
extract( $data ); // $data variable consist $context, $html_content.

/**
 * Single course sidebar content
 *
 * @param boolean $echo echo the content or not.
 * @param string  $context device context (mobile/desktop).
 * @return string HTML output string.
 */
function tutor_course_single_sidebar( $echo = true, $context = 'desktop' ) {
	ob_start();
	tutor_load_template( 'single.lesson.lesson_sidebar', array( 'context' => $context ) );
	$output = apply_filters( 'tutor_lesson/single/lesson_sidebar', ob_get_clean() );

	if ( $echo ) {
		add_filter( 'wp_kses_allowed_html', 'tutor_kses_allowed_html', 10, 2 );
		echo wp_kses_post( $output );
		remove_filter( 'wp_kses_allowed_html', 'tutor_kses_allowed_html' );
	}

	return $output;
}

do_action( 'tutor/course/single/content/before/all', $course_id, $content_id );

get_tutor_header();

$show_mark_as_complete = false;

if ( tutor()->lesson_post_type === $post->post_type ) {
	$show_mark_as_complete = apply_filters( 'tutor_lesson_show_mark_as_complete', true );
}

?>

<?php do_action( 'tutor_' . $context . '/single/before/wrap' ); ?>
<div class="tutor-course-single-content-wrapper<?php echo $enable_spotlight_mode ? ' tutor-spotlight-mode' : ''; ?>">
	<div class="tutor-course-single-sidebar-wrapper tutor-<?php echo esc_attr( $context ); ?>-sidebar">
		<?php tutor_course_single_sidebar(); ?>
	</div>
	<div id="tutor-single-entry-content" class="tutor-quiz-single-entry-wrap">
		<?php ( isset( $method_map[ $context ] ) && is_callable( $method_map[ $context ] ) ) ? $method_map[ $context ]() : 0; ?>
		<?php
			/**
			 * Note: $html_content comes from extracted $data variable
			 * $html_content consist dynamic HTML content which is loaded by tutor_load_template_from_custom_path
			 */
			echo isset( $html_content ) ? $html_content : ''; //phpcs:ignore 
		?>
	</div>
</div>

<!-- Course Progressbar on sm/mobile  -->
<?php
	// Get total content count.
	$course_stats = tutor_utils()->get_course_completed_percent( $course_id, 0, true );

	// Is Lesstion Complete.
	$is_completed_lesson = tutor_utils()->is_completed_lesson();
?>

<?php if ( ! \TUTOR\Course_List::is_public( $course_id ) ) : ?>
	<div class="tutor-spotlight-mobile-progress-complete tutor-px-20 tutor-py-16 tutor-mt-20 tutor-d-xl-none tutor-d-block">
		<div class="tutor-row tutor-align-center">
			<div class="tutor-spotlight-mobile-progress-left <?php echo ! $is_completed_lesson ? 'tutor-col-sm-8 tutor-col-6' : 'tutor-col-12'; ?>">
				<div class="tutor-fs-7 tutor-color-muted">
					<?php echo esc_html( $course_stats['completed_percent'] ) . '% '; ?><span><?php esc_html_e( 'Complete', 'tutor' ); ?></span>
				</div>
				<div class="list-item-progress tutor-my-16">
					<div class="tutor-progress-bar tutor-mt-12" style="--tutor-progress-value:<?php echo esc_attr( $course_stats['completed_percent'] ); ?>%;">
						<span class="tutor-progress-value" area-hidden="true"></span>
					</div>
				</div>
			</div>

			<?php if ( ! $is_completed_lesson ) : ?>
				<div class="tutor-spotlight-mobile-progress-right tutor-col-sm-4 tutor-col-6">
					<?php
					if ( $show_mark_as_complete ) {
						tutor_lesson_mark_complete_html();
					}
					?>
				</div>
			<?php endif; ?>

		</div>
	</div>
<?php endif; ?>
<?php
do_action( 'tutor_' . $context . '/single/after/wrap' );

function get_lesson_ids_by_parent_id($parent_id) {
    global $wpdb;

    // Define the custom query
    $query = $wpdb->prepare(
        "SELECT ID FROM {$wpdb->prefix}posts 
        WHERE post_type = %s AND post_parent = %d",
        'lesson',
        $parent_id
    );

    // Execute the query
    $results = $wpdb->get_col($query);

    // Return the IDs
    return $results;
}

 function get_posts_by_meta_value_and_parent($meta_key, $meta_value, $parent_post_id) {
    global $wpdb;

    $sql = "SELECT p.ID, p.post_title
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = %s
            WHERE p.post_parent = %d
            AND (pm.meta_value = %s OR (pm.meta_value IS NULL AND %s = '0'))";

    $query = $wpdb->prepare($sql, $meta_key, $parent_post_id, $meta_value, $meta_value);
    $results = $wpdb->get_results($query);
    return $results;
}

// Function to insert a new row into the tutor_require_topic table
function insert_tutor_require_topic_row($user_id, $cours_id, $topic_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'tutor_require_topic';

    $wpdb->insert(
        $table_name,
        array(
            'user_id'  => $user_id,
            'cours_id' => $cours_id,
            'topic_id' => $topic_id,
        ),
        array('%d', '%d', '%d')
    );

    // Return the ID of the inserted row
    return $wpdb->insert_id;
}

// Function to get all rows based on cours_id and user_id
function get_tutor_require_topic_rows($user_id, $cours_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'tutor_require_topic';

    $query = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE user_id = %d AND cours_id = %d",
        $user_id,
        $cours_id
    );

    // Retrieve the results as an associative array
    $results = $wpdb->get_results($query, ARRAY_A);

    return $results;
}
$have_topic_saved=get_tutor_require_topic_rows(get_current_user_id(),$course_id);
 if(!$have_topic_saved) {
		tutor_load_template_from_custom_path(
			tutor()->path . '/views/modal/required_popup.php',
			array(
				"course_id"=>$course_id
			),
			true
		);
 }
get_tutor_footer();
