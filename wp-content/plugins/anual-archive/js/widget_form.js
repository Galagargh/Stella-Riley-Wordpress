/*
 * Annual Archive Widget Scripts v0.2
 * https://wordpress.org/plugins/anual-archive/
 *
 * Copyright 2018, Twinpictures
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, blend, trade,
 * bake, hack, scramble, difiburlate, digest and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

(function($){
	$(document).on('change', '.annual_archive_type_select', function(event) {
		var sel_id = this.id;
		var sel_opt = $('option:selected', this).attr('data-orderfield');
		//console.log(sel_id, sel_opt);
		$( '.order_' + sel_id ).each(function( index ) {
			//console.log(index, $( this ).attr('id'));
			if( $( this ).attr('id') == sel_opt ){
				$( this ).show();
			}
			else{
				$( this ).hide();
			}
		});
	})
})(jQuery);
