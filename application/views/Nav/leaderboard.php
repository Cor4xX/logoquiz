<?php $this->view('Layout/summary'); ?>
<table id="leaderboard" class="table">
	<thead>
        <tr>
          <th>Ranking</th>
          <th>Name</th>
          <th>Points</th>
        </tr>
    </thead>
	<?php 
	foreach($users as $key => $user){
		echo "
			<tr>
				<td class='ranking'>
					".($key+1)."
				</td>
				<td class='username'>
					<a href='https://www.facebook.com/".$user->user_facebook_id."'>
						".$user->user_firstname." (".substr($user->user_name, 0, 3).")
					</a>
				</td>
				<td class='point'>
					".$user->point."
				</td>
			</tr>";
	}
	?>
</table>