<div align='center'>
<p>
	Are you sure you want to add <?= $webfinger ?><br><br>
	<p>
		<button id='confirm'>YES</button>
	</p>
	<script>
	$('#confirm').bind("click", function(){
		window.location.href = document.referrer;
	});
	</script>
</p>
</div>