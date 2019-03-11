<div class="page-gradient-overlay"></div>

<section class="header">
	<?php
		$header_image_src = One_And_One_Assistant_Config::get( 'logo_variant1', 'branding' );
		$header_image_alt = One_And_One_Assistant_Config::get( 'name', 'branding' );
	?>
	<?php if ( $header_image_src ): ?>
		<img src="<?php echo $header_image_src; ?>" alt="<?php echo $header_image_alt; ?>">
	<?php endif; ?>
</section>