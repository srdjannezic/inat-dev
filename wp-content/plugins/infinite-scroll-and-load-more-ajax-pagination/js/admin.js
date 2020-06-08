jQuery(document).ready(function($){
	
	/*
	Image Uploader
	*/
	jQuery('.pix-upload').on('click', function( event ){
		var file_frame='';
		event.preventDefault();
		var obj=$(this);
		if ( file_frame ) {
		  // Open frame
		  file_frame.open();
		  return;
		} 
		
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: 'Loader Image',
		  button: {
			text: 'Select',
		  },
		  multiple: false  
		});
	
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  attachment = file_frame.state().get('selection').first().toJSON();
		  obj.prev('input[type=text]').val(attachment.url);
		  obj.siblings('.pix-upload-snap').html("<img src=\""+attachment.url+"\">");
		});
		// Finally, open the modal
		file_frame.open();
  });
	
	/*
	Enable/Disable button
	*/
	$(".pix-enable").click(function(){
		var parent = $(this).parents('tr');
		$('.pix-disable',parent).removeClass('selected');
		$(this).addClass('selected');
		$(parent).find('input[type="checkbox"]').attr('checked', true);
	});
	$(".pix-disable").click(function(){
		var parent = $(this).parents('tr');
		$('.pix-enable',parent).removeClass('selected');
		$(this).addClass('selected');
		$(parent).find('input[type="checkbox"]').attr('checked', false);
	});

	/*
	Conditionally show/hide fields
	*/
	$('select[name="pix_infinite_load\[pagination_type\]"]').on('change',function(){
		var type=$(this).val();
		if(type=='infinite')
		{
			$('input[name="pix_infinite_load\[button_label\]"],input[name="pix_infinite_load\[button_class\]"]').closest('tr').hide();
			$('input[name="pix_infinite_load\[loading_image\]"]').closest('tr').slideDown();
		}
		else if(type=='more')
		{
			$('input[name="pix_infinite_load\[button_label\]"],input[name="pix_infinite_load\[button_class\]"]').closest('tr').slideDown();
			$('input[name="pix_infinite_load\[loading_image\]"]').closest('tr').hide();
		}
	});
	

});