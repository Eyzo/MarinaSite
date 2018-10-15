jQuery(document).ready(function ($)
{
	var scrolltop = $('#nav').offset().top;

	$(window).scroll(function(){
	    
	    if ($(this).scrollTop() > scrolltop)
	    {
	        $('#nav').addClass('fixNavigation');
	    }
	    else
		{
	    	$('#nav').removeClass('fixNavigation');
		}
	});
});