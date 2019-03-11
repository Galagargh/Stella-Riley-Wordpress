<?php

echo var_dump($count);

if ($count == 4) {
	echo "<p>newrow</p>";
	$count = 1;
	$category_row = $category_row - 1;
	$tag = null;
}

echo var_dump($count);

if ( $category_row > 0 &&  $remainder > 1) {
		
	if ( $tag = null ) {
			
		if ( $category_row >= 1 ) {
				
			$tag = "col-md-4";
			$count++;
				
		} elseif ( $remainder > 1 ) {
				
			$tag = "col-md-6";
			$remainder = $remainder - 1;
			$count++;

		}

	} elseif ($tag != null) {

		if ($tag == "col-md-6"){

			$tag = "col-md-6";
			$count = 0;
			$remainder = 0;

		}elseif ($tag == "col-md-4"){

			$tag = "col-md-4";
			$count++;
				
		}elseif ($tag == "col-md-12"){
				
			$tag = "col-md-6";
			$count = 0;
				
		}

	}

}else{

	$tag = "col-md-12";
	$count = 4;
		
}

?>