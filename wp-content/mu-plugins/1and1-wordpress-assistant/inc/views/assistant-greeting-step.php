<?php One_And_One_Assistant_View::load_template( 'card/header-check' ); ?>

<div class="card-content">
	<div class="card-content-inner">
		<h2><?php
			if ( One_And_One_Assistant_Config::get( 'name', 'branding' ) ) {
				echo sprintf(
					esc_html__( 'setup_assistant_greeting_title_by', '1and1-wordpress-wizard' ),
					One_And_One_Assistant_Config::get( 'name', 'branding' )
				);
			} else {
				esc_html_e( 'setup_assistant_greeting_title', '1and1-wordpress-wizard' );
			}
		?></h2>
		<p><?php _e( 'setup_assistant_greeting_description', '1and1-wordpress-wizard' ); ?></p>
	</div>
</div>

<?php
	One_And_One_Assistant_View::load_template( 'card/footer', array(
		'card_actions' => array(
			'left'  => array(),
			'right' => array(
				'goto-design' => array(
					'label' => esc_html__( 'setup_assistant_greeting_ok', '1and1-wordpress-wizard' ),
					'class' => 'btn btn-link'
				),
				'skip-greeting' => array(
					'label' => esc_html__( 'setup_assistant_greeting_cancel', '1and1-wordpress-wizard' ),
					'class' => 'btn btn-link btn-secondary',
					'href'  => admin_url( 'index.php?1and1-wordpress-wizard-cancel=1' )
				)
			)
		)
	) );
?>