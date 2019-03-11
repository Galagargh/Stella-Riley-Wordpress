<div id="card-congrats-lightbox" style="display:none">
	<div class="assistant-card card-congrats">
 		<div class="card-bg"></div>
		<div class="card-bg card-weave-red"></div>
		<div class="card-bg card-weave-blue"></div>
		
		<div id="card-congrats" class="card-step">
			<?php One_And_One_Assistant_View::load_template( 'card/header-check' ); ?>
		
			<div class="card-content">
				<div class="card-content-inner">
					<h2><?php esc_html_e( 'setup_assistant_customizer_title', '1and1-wordpress-wizard' ) ?></h2>
					<p><?php _e( 'setup_assistant_customizer_desc1', '1and1-wordpress-wizard' ); ?></p>
					<p><?php _e( 'setup_assistant_customizer_desc2', '1and1-wordpress-wizard' ); ?></p>
				</div>
			</div>
			
			<?php
				One_And_One_Assistant_View::load_template( 'card/footer', array(
					'card_actions' => array(
						'left'  => array(),
						'right' => array(
							'skip-congrats' => array(
								'label'   => esc_html__( 'setup_assistant_customizer_close_button', '1and1-wordpress-wizard' ),
								'class'   => 'btn btn-link',
								'onclick' => 'tb_remove()'
							)
						)
					)
				) );
			?>
		</div>
	</div>
</div>