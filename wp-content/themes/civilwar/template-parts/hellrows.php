<?php

//rows
$category_row = floor($book_category / 3);
//remainder
$remainder = $book_category % 3;





// if one row of books has been checked
// check to see if the next row is a full row
if ( $count == 3 && $category_row >= 1 && $tag != "col-md-6") {
		
	//if there are full rows left the tag is set
	if ( $category_row > 1 ) {
		$book_category == $book_category - 3;
		//$category_row == $category_row - 1;
		$tag = "col-md-4";
		$count = 0;
			
	} else {

		switch ($book_category % 3) {
			case 1:
				$tag = "col-md-12";
				break;
			case 2:
				$tag = "col-md-6";
				break;
			case 0:
				$tag = "col-md-4";
				break;

				$count = 0;

		}

	}
		
} elseif ( $count < 3 && $category_row < 1 && $count < $remainder && $tag != "col-md-6") {
		
	switch ($book_category % 3) {
		case 1:
			$tag = "col-md-12";
			break;
		case 2:
			$tag = "col-md-6";
			break;
		case 0:
			$tag = "col-md-4";
			break;
				
			$count = 0;
				
	}
		
} elseif ($tag == "col-md-6"){
		
	$tag = "col-md-6";
	$count = 0;
		
}else {
		
	$tag = "col-md-4";
	$count++;
}

?>