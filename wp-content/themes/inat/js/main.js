jQuery('document').ready(function(){

	jQuery('body').on('click','.pum-trigger',function(){
		let cat = jQuery(this).closest('.lae-pricing-plan').find('.lae-top-header > h3').html();
		console.log(cat);
		jQuery('input[name="ticket-category"]').attr('value',cat);
	});

	jQuery('.tab-header-item').click(function(){
		jQuery('.tab-header-item').removeClass('active');
		jQuery(this).addClass('active');

		var target = '.' + jQuery(this).data('target');
		var date_target =  jQuery(this).data('date');
		console.log(target);

		var url = document.location.href + "?" + date_target;

		console.log(url);

		window.history.pushState("", "Agenda Date", '?date='+date_target);
 
		jQuery('.tab-item').removeClass('active');
		jQuery(target).addClass('active'); 
	});

	jQuery('body').on('click','.close-div',function(){
		jQuery('#alert-box').hide();
	});

	var i = 0;
    rotate(i);

	var tab_count = jQuery('.tab-header-item').length;
	console.log(tab_count);
	if(tab_count > 4){
		tab_width = 100/tab_count;
		//console.log(tab_width);
		jQuery('.tab-header-item').css('width',tab_width + "%");
	}

	var url_date = getUrlParameter('date');
	//console.log(url_date);

	if(url_date != undefined){
		jQuery('.tab-header-item').removeClass('active');
		jQuery('.tab-header-item[data-date="'+url_date+'"]').addClass('active');
		var target = '.'+jQuery('.tab-header-item[data-date="'+url_date+'"]').data('target');
		//console.log(target);
		jQuery('.tab-item').removeClass('active');
		jQuery(target).addClass('active');
	}
 
	if(screen.width < 900){
		jQuery("#newsletter-form").find(".btn-special").html("Submit");
	}
	var counter = 0;
	jQuery("#blog-category").find('h4').click(function(){ 
		jQuery("#blog-category").find("#blog-category-list").toggle(500);
		if(counter % 2 == 0){
			jQuery("#blog-category").find('i').removeClass('fa-chevron-right');
			jQuery("#blog-category").find('i').addClass('fa-chevron-down');
		}
		else{
			jQuery("#blog-category").find('i').removeClass('fa-chevron-down');
			jQuery("#blog-category").find('i').addClass('fa-chevron-right');		
		}
		counter++;
	});

	jQuery('.item-thumb').click(function(){	
		jQuery('html').css('overflow','hidden');
	});

	jQuery('button.close').click(function(){
		jQuery('html').css('overflow','auto');
	});

	var day = jQuery('.day-count').html();
	var hour = jQuery('.hour-count').html();
	var minute = jQuery('.minute-count').html();

	var date = new Date();

	var curr_day = date.getDay();
	var curr_month = date.getMonth();
	var curr_year = date.getYear();
	console.log(curr_month);
	//var curr_hour = date.getDay();
	//var curr_minute = date.getDay();

	var goal_day = jQuery('.goal-day').html();
	var goal_month = jQuery('.goal-month').html();
	var goal_year = jQuery('.goal-year').html();

	///console.log(goal_day);
	// console.log('testing');
	// var day = goal_month - curr_month;
	// goal_month = monthNameToNum(goal_month);

 // 	jQuery(".heroblock-subtitle3").find('p')
 //  		.countdown(goal_year+"/"+goal_month+"/"+goal_day, function(event) {
 //    	jQuery(this).text(
 //      		event.strftime('%D days %H hours %M minutes left')
 //    	);
 //  	});
});

var months = [
    'January', 'February', 'March', 'April', 'May',
    'June', 'July', 'August', 'September',
    'October', 'November', 'December'
    ];

function monthNameToNum(monthname) {
    var month = months.indexOf(monthname);
    return month ? month + 1 : 0;
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};


function rotate(degree) {        
	jQuery(".screw").css({ 'transform': 'rotate(' + degree + 'deg)'});  
    jQuery(".screw").css({ '-webkit-transform': 'rotate(' + degree + 'deg)'});  
    jQuery(".screw").css({ '-moz-transform': 'rotate(' + degree + 'deg)'});                      
    //console.log(degree);
    timer = setTimeout(function() {
        rotate(++degree);
    },20);
}

