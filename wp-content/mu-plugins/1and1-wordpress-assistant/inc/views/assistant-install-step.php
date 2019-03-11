<?php One_And_One_Assistant_View::load_template( 'card/header-default' ); ?>

<div class="card-content" style="padding-bottom: 0">
	<div class="card-content-inner">
		<h2><?php esc_html_e( 'setup_assistant_progress_title', '1and1-wordpress-wizard' ); ?></h2>
		<p><?php _e( 'setup_assistant_progress_desc', '1and1-wordpress-wizard' ); ?></p>
	</div>

	<div class="linear-progress-container">
		<div class="linear-progress">
			<div class="indeterminate"></div>
		</div>
	</div>
</div>