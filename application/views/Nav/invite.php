<?php $this->view('Layout/summary'); ?>
<script type="text/javascript">
	$( document ).ready(function() {
	  newInvite();
	});
	function newInvite(){
	    var receiverUserIds = FB.ui({ 
		            method : 'apprequests',
		            message: 'Play with me and find all the logo !',
			    },
			    function(receiverUserIds) {
	            	console.log("IDS : " + receiverUserIds.request_ids);
	            }
	    );
	}
</script>
