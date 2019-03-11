/*
*	jQuery Debugger - Example
*
*	Copyright (c) 2008 Petr Staníček (pixy@pixy.cz)
*	January 2009
*
*	usage:

	var debug = new jQuery.debug( options ); // see var Prefs for options properties
	debug.dump(x);

example:

	var debug = new jQuery.debug({ posTo: {x:'right',y:'bottom'}, height:'150px',width:'150px' });
	var myObj = { 'arr' : [1,2,3], cnt : 3, elm : document.createElement('DIV') };
	debug.dump(myObj);	

*/
var debug = new jQuery.debug();

var debug1 = new jQuery.debug({
	posTo : { x:'left', y:'top' },
	width: '250px',
	height: '50%',
});

var debug2 = new jQuery.debug({
	posTo : { x:'right', y:'bottom' },
	width: '480px',
	height: '50%',
	itemDivider : '<hr />',
	listDOM : [ 'tagName','id', 'innerText', 'href' ]
});

var debug3 = new jQuery.debug({
	continuous : true,
	posTo : { x:'right', y:'top' },
	width: '75px',
	height: '50px',
	background: '#600',
	color: '#ffc',
	labelColor: '#996'
});

var debug4 = new jQuery.debug({
	continuous : false,
	posTo : { x:'right', y:'top' },
	y: '100px',
	width: '20em',
	height: '20em',
	listDOM : 'props-only'
});

jQuery(document).ready( function($){

	$('input').bind('keyup', function() {
		debug1.dump(this.value,'Text');
	}).trigger('keyup');
	
	$('input #test').bind('click', function() {
		var d = new Date();
		debug2.dump(document.getElementsByTagName('A'),'Links in document');
	}).trigger('click');
	
	$('#wpwrap').bind('mouseover', function(e) {
		debug3.dump(e.pageX,'X');
		debug3.dump(e.pageY,'Y');
	})
	
	$(document).click( function(e) {
		debug4.dump({ x:e.pageX, y:e.pageY, eventTarget:$(e.originalTarget) }, 'Click event')
	});
	
});