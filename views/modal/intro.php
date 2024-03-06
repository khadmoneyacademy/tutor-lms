<?php
/**
 * Cours Preview 
 *
 * @since 1.0.0
 * @author themeum
 * @link https://themeum.com
 *
 * @package TutorLMS/Templates
 */

?>


<div class="tutor-modal tutor-intro-modal " >
	<div class="tutor-modal-overlay"></div>
	<div class="tutor-modal-window tutor-modal-window-xl">
		<div class="tutor-modal-content tutor-modal-content-white tutor-p-32">
			<button class="tutor-iconic-btn tutor-modal-close-o" data-tutor-modal-close>
				<span class="tutor-icon-times" area-hidden="true"></span>
			</button>
            <div class="modal-header">
			<h3 class="tutor-fs-5 tutor-fw-bold tutor-color-black tutor-mb-24 tutor-course-content-title">
				<?php
					echo __( 'معاينة الدورة', 'tutor' );
				?>
			</h3>

			<h3 class="tutor-fs-3 tutor-fw-bold tutor-color-black tutor-mb-24 tutor-course-content-title">
				<?php
					echo __(get_the_title(), 'tutor' );
				?>
			</h3>

            <div class='tutor-course-thumbnail tutor-course-details-page'>
                <?php tutor_utils()->has_video_in_single() ? tutor_course_video() : get_tutor_course_thumbnail(); ?>
            </div>
            </div>
			<div class="tutor-pt-4" style="overflow-y:scroll;  height: 200px;">
				<div >
				<h3 class="tutor-fs-3 tutor-fw-bold tutor-color-black tutor-mb-24 tutor-course-content-title" style="font-size: 24px;">
					<?php
						echo __( ' يمكنك الآن معاينة بعض المقاطع المختارة من الدورة ', 'tutor' );
					?>
				</h3>
					<?php 
							tutor_load_template( 'single.course.course-preview-topics' );
					
					?>
				</div>
			</div>
		</div>
	</div>
</div>