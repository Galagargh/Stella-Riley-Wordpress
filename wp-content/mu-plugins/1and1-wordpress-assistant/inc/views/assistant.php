	<?php
	/*
	 * WARNING: DO NOT REMOVE THIS TAG!
	 * We use the admin_head() hook to locate the template where we want, and that imply manually closing the <head> tag
	 */
	?>
	</head>

	<?php
		$action = isset( $_GET[ 'setup_action' ] ) ? sanitize_text_field( $_GET[ 'setup_action' ] ) : 'choose_appearance';
	?>

	<body class="assistant-page branding">
		<?php One_And_One_Assistant_View::load_template( 'parts/header' ); ?>

		<section class="assistant-card-container">
			<div class="assistant-card animate">
				<div class="card-bg"></div>
				<div class="card-bg card-weave-red"></div>
				<div class="card-bg card-weave-blue"></div>

				<div class="card-step<?php echo ( $action === 'greeting' ) ? ' active' : '' ?>" id="card-greeting">
					<?php One_And_One_Assistant_View::load_template( 'assistant-greeting-step' ); ?>
				</div>

				<div class="card-step<?php echo ( $action === 'choose_appearance' ) ? ' active' : '' ?>" id="card-design">
					<?php One_And_One_Assistant_View::load_template( 'assistant-design-step', array(
						'site_types' => $site_types
					) ); ?>
				</div>

				<div class="card-step" id="card-install">
					<?php One_And_One_Assistant_View::load_template( 'assistant-install-step' ); ?>
				</div>
			</div>
		</section>

		<?php
			do_action( 'admin_footer', '' );
			do_action( 'admin_print_footer_scripts' );
		?>
	</body>
</html>