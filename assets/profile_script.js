jQuery(function($) {
	var websites = [];
	if($('input[name=websites]').length)
	if($('input[name=websites]').val().length){
		var cur_websites = $('input[name=websites]').val();
		websites = cur_websites.split("|");
	}

	$('.btn_add_website').click(function(){
		var website = $('input[name=website]').val();
		if(website.length>0){		
			websites.push(website);
			$('input[name=website]').val('');
			$('.added_websites').append('<span class="label label-success"><span>'+website+'</span><span class="glyphicon glyphicon-remove-sign remove_website" aria-hidden="true"></span></span>');
			combine_websites();
		}
	});

	$('.added_websites').on('click','.remove_website',function(){
		var index = websites.indexOf($(this).siblings().text());
		if (index > -1) {
			$(this).parent().remove();
		    websites.splice(index, 1);
		    combine_websites();
		}
	});

	$('.container-pp-update').click(function(){
		$('.profile_photo').trigger('click');
	});

	$('#form_profilephoto input[type=file]').change(function(){
		$('#form_profilephoto').submit();
	});
	$('.btn-changepassword').data('changepass',0);
	$('.btn-changepassword').click(function(){
		if($('.btn-changepassword').text() === 'Cancel'){
			$('.btn-changepassword').html('Change Password').data('changepass',0).removeClass('btn-default').addClass('btn-info');
			$('.container-changepassword input[type=password]').attr('required',false)
		}else{
			$('.btn-changepassword').html('Cancel').data('changepass',1).addClass('btn-default').removeClass('btn-info');
			$('.container-changepassword input[type=password]').attr('required',true)
		}
		$('.container-changepassword').toggle();
	});

	$('#form-account').submit(function(event){
		event.preventDefault();
		var password = $('.container-changepassword input[name=new_password]').val();
		var o_password = $('.container-changepassword input[name=old_password]').val();
		var c_password = $('.container-changepassword input[name=c_new_password]').val();
		if($('.btn-changepassword').data('changepass')===0){
			$(this).unbind('submit').submit();
		}else if(o_password.length&&password.length&&password==c_password){
			$(this).unbind('submit').submit();
		}else{
			$('.container-changepassword input[type=password]').addClass('error');
		} 
	});

	var delay = (function(){
	  var timer = 0;
	  return function(callback, ms){
	    clearTimeout (timer);
	    timer = setTimeout(callback, ms);
	  };
	})();

	$( ".inp_location" ).autocomplete({
      source: function( request, response ) {
      	console.log(request);
      	inp = $.param({'s':request.term});
        $.get(window.location.origin+"/wp-json/api/location?"+inp, function(data, status){
      		var data = JSON.parse(data);
        	if(data.length){
        		var location = [];
        		$.each(data,function(i,l){
        			location.push(l.country_name+', '+l.city_name);
        		});
        		response(location);
        	}else{
        		response([]);
        	}
    	});
      },
      minLength: 2,
      select: function( event, ui ) {
      	$( ".inp_location" ).val(ui.item.value);
      }
    } );

	function combine_websites(){
		if(websites.length){
			$('input[name=websites]').val(websites.join('|'));
		}else{
			$('input[name=websites]').val('');
		}
	}
});