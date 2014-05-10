<?php 
	$this->view('Layout/summary');
	foreach ($quizzes as $quiz) {
		// Si l'utilisateur a assez de points
		if($quiz->quiz_limit_point <= $this->session->userdata('user_point')){
			?>
			<div class="unlocked quiz" onclick="nav('quiz', 'play', <?php echo $quiz->quiz_id; ?>)">
				<span class="quiz-title"><?php echo $quiz->quiz_name; ?> Quiz</span>
				<span class="quiz-information">
					<?php echo $quiz->details[0]->count_level_validate." / ".$quiz->count_level; ?>		
					<br>	
				</span>
			</div>
			<?php
		}

		// Si l'utilisateur n'a pas assez de points
		else{
			?>
			<div class="locked quiz">
				<span class="quiz-title"><?php echo $quiz->quiz_name; ?> Quiz</span>
				<span class="quiz-information">
					<?php echo $quiz->quiz_limit_point; ?> Points 	
				</span>
			</div>
			<?php
		}
	}
?>