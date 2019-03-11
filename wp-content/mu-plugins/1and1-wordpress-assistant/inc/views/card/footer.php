<div class="card-footer">
	<?php
		if ( isset( $card_actions ) && is_array( $card_actions ) ) {

			foreach ( $card_actions as $group_name => $group_actions ) {
				$html = '';
				
				foreach ( $group_actions as $id => $params ) {
					$label = isset( $params[ 'label' ] ) ? esc_html__( $params[ 'label' ] ) : '';
	
					$href = isset( $params[ 'href' ] ) ? esc_url( $params[ 'href' ] ) : '#';
	
					$class = isset( $params[ 'class' ] ) ? esc_attr( $params[ 'class' ] ) : '';
					$class = ! empty( $class ) ? ' class="' . $class . '"' : '';
					
					$onclick = isset( $params[ 'onclick' ] ) ? ' onclick="' . esc_js( $params[ 'onclick' ] ) . '"' : '';
	
					$html .= sprintf(
						'<a href="%s" id="%s"%s%s>%s</a>',
						$href,
						$id,
						$class,
						$onclick,
						$label
					);
				}
				
				echo sprintf( '<div class="btn-group %s">%s</div>', $group_name, $html );
			}
		}
	?>
</div>