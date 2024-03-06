<?php
/**
 * Topic Form
 *
 * @package Tutor\Views
 * @subpackage Tutor\Modal
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 1.0.0
 */

?>
<div id="<?php echo esc_attr( $data['wrapper_id'] ); ?>" class="tutor-modal tutor-modal-scrollable<?php echo esc_attr( is_admin() ? ' tutor-admin-design-init' : '' ); ?> <?php echo esc_attr( $data['wrapper_class'] ); ?>">
	<div class="tutor-modal-overlay"></div>
	<div class="tutor-modal-window">
		<div class="tutor-modal-content">
			<div class="tutor-modal-header">
				<div class="tutor-modal-title">
					<?php echo esc_html( $data['modal_title'] ); ?>
				</div>

				<button class="tutor-iconic-btn tutor-modal-close" data-tutor-modal-close>
					<span class="tutor-icon-times" area-hidden="true"></span>
				</button>
			</div>

			<div class="tutor-modal-body">
				<div class="tutor-mb-32">
					<label class="tutor-form-label"><?php esc_html_e( 'Topic Name', 'tutor' ); ?></label>
					<input type="text" name="topic_title" class="tutor-form-control" value="<?php echo esc_attr( ! empty( $data['title'] ) ? $data['title'] : '' ); ?>"/>
					<div class="tutor-form-feedback">
						<i class="tutor-icon-circle-info-o tutor-form-feedback-icon"></i>
						<div><?php esc_html_e( 'Topic titles are displayed publicly wherever required. Each topic may contain one or more lessons, quiz and assignments.', 'tutor' ); ?></div>
					</div>
				</div>

				<div>
					<label class="tutor-form-label"><?php esc_html_e( 'Topic Summary', 'tutor' ); ?></label>
					<textarea name="topic_summery" class="tutor-form-control tutor-mb-12"><?php echo esc_attr( ! empty( $data['summary'] ) ? $data['summary'] : '' ); ?></textarea>
					<input type="hidden" name="topic_course_id" value="<?php echo esc_attr( $data['course_id'] ); ?>">
					<input type="hidden" name="topic_id" value="<?php echo esc_attr( $data['topic_id'] ); ?>">
					<div class="tutor-form-feedback">
						<i class="tutor-icon-circle-info-o tutor-form-feedback-icon"></i>
						<div><?php esc_html_e( 'Add a summary of short text to prepare students for the activities for the topic. The text is shown on the course page beside the tooltip beside the topic name.', 'tutor' ); ?></div>
					</div>
				</div>
				<div class="tutor-col-sm-4 tutor-col-md-4 tutor-mt-4 tutor-mb-4">
					<label class="tutor-form-toggle tutor-nowrap-ellipsis">
						<input type="checkbox" class="tutor-form-toggle-input" value="1" name="tutor_topic_required<?php echo esc_attr( $data['topic_id'] ); ?>" <?php checked( '1', get_post_meta($data['topic_id'],'tutor_topic_required',true) ); ?> />
						<span class="tutor-form-toggle-control"></span> <?php esc_html_e( 'Topic Required', 'tutor' ); ?>
					</label>
				</div>
			</div>

			<div class="tutor-modal-footer">
				<button class="tutor-btn tutor-btn-outline-primary" data-tutor-modal-close>
					<?php esc_html_e( 'Cancel', 'tutor' ); ?>
				</button>

				<button id="tutor_update_topic_<?php echo esc_attr( $data['topic_id'] ); ?>" type="button" class="tutor-btn tutor-btn-primary <?php echo esc_attr( ! empty( $data['button_class'] ) ? $data['button_class'] : '' ); ?>" id="<?php echo esc_attr( ! empty( $data['button_id'] ) ? $data['button_id'] : '' ); ?>">
					<?php echo esc_attr( $data['button_text'] ); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function($) {
    // Your click event, assuming you have a button to trigger this
    jQuery('#tutor_update_topic_<?php echo esc_attr( $data['topic_id'] ); ?>').on('click', function() {
	
        // Get the value of tutor_topic_required
        var tutorTopicRequired = $('input[name="tutor_topic_required<?php echo esc_attr( $data['topic_id'] ); ?>"]').is(':checked') ? 1 : 0;
		var topicid = <?php echo esc_attr( $data['topic_id'] ); ?>;

		console.log(tutorTopicRequired);
        // Prepare the data to be sent
        var data = {
            'action': 'tutor_save_topic_required',
            'tutor_topic_required': tutorTopicRequired,
			'topic_id': topicid,
        };

        // Make the AJAX request
        $.ajax({
            type: 'POST',
            url: ajaxurl, // WordPress AJAX URL
            data: data,
            success: function(response) {
                // Handle the response if needed
                console.log(response);
            },
            error: function(error) {
                // Handle errors if any
                console.log(error);
            }
        });

    });
});

</script>