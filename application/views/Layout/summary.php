<div class="row-fluid">
	<div id="summary">
		<h4 class="name">Hi <?php echo $this->session->userdata('first_name'); ?>,</h4>
		<p class="point">
			<?php
				echo $this->session->userdata('user_point'); 
				if($this->session->userdata('user_point') <= 1){
					echo " point";
				}else{
					echo " points";
				}
				echo "<br>Level : ";
				foreach ($quizzes as $quiz) {
					// Si l'utilisateur a assez de points
					if($quiz->quiz_limit_point <= $this->session->userdata('user_point')){
						?>
							<a href="#" onclick="nav('quiz','play', <?php echo $quiz->quiz_id; ?>)"><?php echo $quiz->quiz_name; ?></a>
						<?php
					}
				}
			?>
		</p>	
	</div>
</div>