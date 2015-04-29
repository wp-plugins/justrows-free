<div class="wrap">
	<p><?php
    	printf(
		__('If you are not automatically redirected, click %shere%s', 'justrows'),
		'<a href="'.admin_url('admin.php?page=justrows-index').'">',
		'</a>'
		)
	?></p>
	<script> window.location.href = '<?php echo admin_url('admin.php?page=justrows-index');?>'; </script>
</div>