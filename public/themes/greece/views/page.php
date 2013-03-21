<div id="content">
	<div class="content-bg">
		<div class="big-block">
			<h1 class="page_title page_<?php echo $page_id;?>_title"><?php echo $page_title ?></h1>
			<div class="page_text">
			<div class="page_<?php echo $page_id;?>">
			<?php 
			echo htmlspecialchars_decode($page_description);
			Event::run('ushahidi_action.page_extra', $page_id);
			?></div></div>
		</div>
	</div>
</div>
