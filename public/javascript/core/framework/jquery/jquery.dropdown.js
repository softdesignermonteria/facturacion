jQuery(document).ready(function(){

	if(jQuery("#ul.dropdown").length) {
		jQuery("#ul.dropdown li").dropdown();
	}

});

jQuery.fn.dropdown = function() {

	return this.each(function() {

		$(this).hover(function(){
			$(this).addClass("hover");
			$('> .dir',this).addClass("open");
			$('ul:first',this).css('visibility', 'visible');
		},function(){
			$(this).removeClass("hover");
			$('.open',this).removeClass("open");
			$('ul:first',this).css('visibility', 'hidden');
		});

	});

}