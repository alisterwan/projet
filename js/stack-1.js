$(function () { 
	// Stack initialize
	var openspeed = 300;
	var closespeed = 300;
	$('.stack>img').toggle(function(){
		var vertical = 0;
		var horizontal = 0;
		var $el=$(this);
		$el.next().children().each(function(){
			$(this).animate({top: '-' + vertical + 'px', left: horizontal + 'px'}, openspeed);
			vertical = vertical + 85;
			horizontal = (horizontal+.75)*2;
		});
		$el.next().animate({top: '-50px', left: '15px'}, openspeed).addClass('openStack')
		   .find('li a>img').animate({width: '70px',marginRight: '0'}, openspeed);
		$el.animate({paddingTop: '20px'});
	}, function(){
		//reverse above
		var $el=$(this);
		$el.next().removeClass('openStack').children('li').animate({top: '55px', left: '-10px'}, closespeed);
		$el.next().find('li a>img').animate({width: '79px', marginLeft: '0'}, closespeed);
		$el.animate({paddingTop: '35px'});
	});
	
	// Stacks additional animation
	$('.stack li a').hover(function(){
		$("img",this).animate({width: '79px'}, 100);
		$("span",this).animate({marginRight: '30px'});
	},function(){
		$("img",this).animate({width: '79px'}, 100);
		$("span",this).animate({marginRight: '0'});
	});
});