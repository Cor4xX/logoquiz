<form action='<?php echo base_url(); ?>manage/create_response' method='POST'>
	<label>LEVEL</label>
	<select id="level_id" name="level_id">
		<?php 
		foreach ($levels as $level) {
			echo "<option value='".$level->level_id."'>".$level->level_id."</option>";
		}
		?>
	</select>

	<label>GOOD ANSWER</label>
	<select id="good_answer" name="good_answer">
		<?php 
		foreach ($logos as $logo) {
			echo "<option value='".$logo->logo_id."'>".$logo->logo_name."</option>";
		}
		?>
	</select>

	<label>BAD ANSWER 1</label>
	<select id="bad_answer_1" name="bad_answer_1">
		<?php 
		foreach ($logos as $logo) {
			echo "<option value='".$logo->logo_id."'>".$logo->logo_name."</option>";
		}
		?>
	</select>

	<label>BAD ANSWER 2</label>
	<select id="bad_answer_2" name="bad_answer_2">
		<?php 
		foreach ($logos as $logo) {
			echo "<option value='".$logo->logo_id."'>".$logo->logo_name."</option>";
		}
		?>
	</select>

	<label>BAD ANSWER 3</label>
	<select id="bad_answer_3" name="bad_answer_3">
		<?php 
		foreach ($logos as $logo) {
			echo "<option value='".$logo->logo_id."'>".$logo->logo_name."</option>";
		}
		?>
	</select>

	<label>BAD ANSWER 4</label>
	<select id="bad_answer_4" name="bad_answer_4">
		<?php 
		foreach ($logos as $logo) {
			echo "<option value='".$logo->logo_id."'>".$logo->logo_name."</option>";
		}
		?>
	</select>

	<label>BAD ANSWER 5</label>
	<select id="bad_answer_5" name="bad_answer_5">
		<?php 
		foreach ($logos as $logo) {
			echo "<option value='".$logo->logo_id."'>".$logo->logo_name."</option>";
		}
		?>
	</select>
	<br>
	<input type='submit' class="btn btn-success" value="Submit" />
</form>

<?php 


?>