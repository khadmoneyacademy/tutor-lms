<?php
/**
 * Template for course archive filter
 *
 * @package Tutor\Templates
 * @subpackage Course_Filter
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 1.4.3
 */

if ( ! tutor_utils()->get_option( 'course_archive_filter_sorting', true, true, true ) ) {
	return;
}

$sort_by = \TUTOR\Input::get( 'course_order', '' );
?>
	

<!--
	Note: Do not remove tutor-course-filter attr. It required by _archive.js for filter function.
!-->
<div style=" " class="tutor-course-filter" tutor-course-filter>
	<form style="display: inline-block;width: 100%;">
	<div class="tutor-widget tutor-widget-search">
			<div class="tutor-form-wrap">
				<span class="tutor-icon-search tutor-form-icon" area-hidden="true"></span>
				<input type="Search" class="tutor-form-control" name="keyword" placeholder="<?php esc_attr_e( 'Search', 'tutor' ); ?>"/>
			</div>
		</div>

	</form>
</div>
<br/>
