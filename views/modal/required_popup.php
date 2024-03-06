<?php
/**
 * Required Topic Form
 *
 * 
 * by:Hamido
 * 
 */

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




$required_topics=get_posts_by_meta_value_and_parent('tutor_topic_required ','1',$data['course_id']);
$not_required_topics=get_posts_by_meta_value_and_parent('tutor_topic_required ','0',$data['course_id']);

?>
<style>
.topics-body{
    margin: 0;
    padding: 0;
    direction: rtl; /* Set text direction to right-to-left */
}
.topic-container {
	text-align: right;
}
.page-title {
	font-size: 22px;
    padding: 0px;
    margin: 5px;
}

.topic {
    background-color: #3498db;
    color: #fff;
    padding: 10px 20px;
    margin: 5px;
    border-radius: 5px;
    display: inline-block;
    font-size: 18px;
}



.optional-lessons-form label {
    padding: 8px 24px;
    margin: 8px;
    border: 2px solid #3498db;
    border-radius: 12px;
    color: #3498db;
}
</style>
<div id="" class="tutor-modal tutor-course-required-topic">
    <div class="tutor-modal-overlay"></div>
    <div class="tutor-modal-window tutor-modal-window-xl">
        <div class="tutor-modal-content tutor-modal-content-white tutor-p-32">
            <button class="tutor-iconic-btn tutor-modal-close-o" data-tutor-modal-close>
                <span class="tutor-icon-times" area-hidden="true"></span>
            </button>
            <div class="modal-header">
                video <?php echo $data['course_id']; ?>
				<?php var_dump($not_required_topics); ?>
                <div class="tutor-video-player">
                    <div class="tutor-video-player">
                        <input type="hidden" id="tutor_video_tracking_information"
                            value="<?php echo esc_attr( json_encode( $jsonData ?? null ) ); ?>">
                        <div class="loading-spinner" area-hidden="true"></div>
                        <div class="tutor-ratio tutor-ratio-16x9">
                            <iframe width="560" height="315"
                                src="https://www.youtube.com/embed/OMHGaDE_1RM?si=-GHLGTR7Jb5KDQjR"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tutor-pt-4 topics-body">
				<h1 class="page-title">الدروس الإجبارية</h1>
					<div class="topic-container">
						<?php 
							foreach($required_topics as $topic){
								?>
									<div class="topic"><?php echo $topic->post_title; ?></div>
								<?php
							}
						
						?>
					</div>
					<h1 class="page-title">الدروس الاختيارية</h1>
					<form class="optional-lessons-form">
						<?php
							foreach($not_required_topics as $not_required){
								?>
									<label for="adab">
										<input type="checkbox" id="adab" name="optional-lesson" value="adab">
										<?php echo $topic->post_title; ?>
									</label>
								<?php
							}
						
						?>
						<div style=" display: flex; justify-content: center; ">
						<input class="tutor-btn tutor-btn-primary" 
						style="margin-top: 5%;text-align: center;padding: 1% 12%;font-size: 22px;"
						 type="submit" value="حفظ">
						</div>
					</form>
            </div>
        </div>
    </div>
</div>