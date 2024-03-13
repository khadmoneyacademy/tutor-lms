<?php
/**
 * Required Topic Form
 *
 * 
 * by:Hamido
 * 
 */

use Tutor\Models\LessonModel;
//LessonModel::mark_lesson_complete( $lesson_id );





$required_topics=get_posts_by_meta_value_and_parent('tutor_topic_required ','1',$data['course_id']);
$not_required_topics=get_posts_by_meta_value_and_parent('tutor_topic_required ','0',$data['course_id']);



if ($_POST['insert_required_topic'] === 'insert_required_topic') {

    $topic_selected = array();
    $topic_required = $_POST["topic_required"];
    $user_id = get_current_user_id();

    // Assuming $not_required_topics is an array of objects
    $not_required_topics_array = array_map(function ($item) {
        return $item->ID;
    }, $not_required_topics);

    foreach ($topic_required as $topic_id) {
        array_push($topic_selected, $topic_id);
        insert_tutor_require_topic_row($user_id, $data['course_id'], $topic_id);
    }

    $filtered_topics = array_diff($not_required_topics_array, $topic_selected);


    foreach($filtered_topics as $topic_id_completed) {
        $lessons=get_lesson_ids_by_parent_id($topic_id_completed);

        foreach ($lessons as $lesson) {
            LessonModel::mark_lesson_complete( $lesson );
        }
    }
    var_dump($_POST);
}
else {

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
                 <?php //echo $data['course_id']; ?>
				<?php //var_dump($not_required_topics); ?>
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
					<form class="optional-lessons-form" method="POST" action="" onsubmit="refreshPage()">
                        <input type="hidden" name="insert_required_topic" value="insert_required_topic"/>
						<?php
							foreach($not_required_topics as $not_required){
								?>
									<label for="<?php echo $not_required->ID; ?>">
										<input type="checkbox" id="<?php echo $not_required->ID; ?>" 
                                        name="topic_required[]" value="<?php echo $not_required->ID; ?>">
										<?php echo $not_required->post_title; ?>
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

<script>
    function refreshPage() {
        window.location.reload();
    }
</script>
<?php }