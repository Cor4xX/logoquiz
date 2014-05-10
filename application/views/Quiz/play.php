<?php $this->view('Layout/summary'); ?>
<div id="listLevel">
<?php  
	foreach ($levels as $level) {
		//Si l'utilisateur a déjà réussi le niveau
		if(!empty($level->score) && $level->score[0]->score_id_user != null){
			if($level->logo_picture != "" && file_exists(FCPATH.$level->logo_picture)){
			?>
				<div class='unlocked level'>
					<img  onclick='nav("level", "play", <?php echo $level->level_id; ?>)' src='<?php echo base_url().$level->logo_picture; ?>' alt='Level<?php echo $level->level_id ?>' width="120px" height="120px" />
				</div>
			<?php	
			}else{
				?>
				<div class='unlocked level' onclick='nav("level", "play", <?php echo $level->level_id; ?>)'>N° <?php echo $level->level_id; ?></div>
				<?php	
			}
		}
		//Si l'utilisateur n'a pas encore réussi le niveau
		elseif(empty($level->score)){
			//Si l'image est trouvée / existe
			if($level->logo_picture != "" && file_exists(FCPATH.$level->logo_picture)){
				// Si l'utilisateur a déjà joué 2 fois aujourd'hui
				if(!empty($level->details) && $level->details[0]->count_submit >= 2){
					?>
					<div class='played level'>
						<img class="timer" alt='timer' src='<?php echo base_url(); ?>assets/images/timer.png' width="64px" height="64px" />
						<img class="picture" alt='Level<?php echo $level->level_id ?>' src='<?php echo base_url().$level->logo_picture; ?>' width="120px" height="120px" />
					</div>
					<?php
				// Si l'utilisateur n'a pas encore joué 2 fois aujourd'hui
				}else{
					?>
					<div class='locked level' onclick='nav("level", "play", <?php echo $level->level_id; ?>)' src='<?php echo base_url().$level->logo_picture; ?>'>
						<img alt='Level<?php echo $level->level_id ?>' src='<?php echo base_url().$level->logo_picture; ?>' width="120px" height="120px" />
					</div>
					<?php
				}	
			}

			//Si l'image l'image n'est pas trouvée, n'existe pas
			else{
				// Si l'utilisateur a déjà joué 2 fois aujourd'hui
				if(!empty($level->details) && $level->details[0]->count_submit >= 2){
					?>
					<div class='played level'>N° <?php echo $level->level_id; ?></div>
					<?php
				// Si l'utilisateur n'a pas encore joué 2 fois aujourd'hui
				}else{
					?>
					<div class='locked level' onclick='nav("level", "play", <?php echo $level->level_id; ?>)'>N° <?php echo $level->level_id; ?></div>
					<?php
				}	
			}
		}
	}
?>
</div>