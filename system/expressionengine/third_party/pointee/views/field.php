<div id="<?=$field_name?>_pointee_img" class="pointee_wrapper">
	<div class="inset">
		<? if($fixed_img_url != ''):?>
			<img src="<?=$fixed_img_url?>" class="pointee_image" />
			<div class="pointee_map_marker pointee_<?=$color?>"></div>
		<? elseif($display_image !=''): ?>
			<img src="<?=$display_image?>" class="pointee_image" />
			<div class="pointee_map_marker pointee_<?=$color?>"></div>
		<? else: ?>
			<p class="pointee_get_started"><?=lang('upload_select_prompt')?></p>
		<? endif ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$.ee_filebrowser.add_trigger($("#<?=$field_name?>_trigger"), function(a){
		
			if(!a.is_image)
			{
				alert('Must be an image!'); 
				return false;
			}
	
			var $img 		= a.thumb.replace("/_thumbs","");
			var $dir_id 	= a.upload_location_id;
			var $filename 	= a.file_name;
			
			// console.log(a);
		
			$("#<?=$field_name?>").val('{filedir_'+$dir_id+'}'+$filename);
			$("#<?=$field_name?>_pointee_img .inset").html('<img src="'+$img+'" class="pointee_image" /><div class="pointee_map_marker pointee_<?=$color?>"></div>');
			$(".pointee_var").show();
			$(".pointee_file_chooser").addClass('pointee_quiet');
			
		});
		
		var $marker_offset_x = <?=$offset_x?>;
		var $marker_offset_y = <?=$offset_y?>;
	
		$("#<?=$field_name?>_pointee_img img.pointee_image").live('click', function(eventObj ) {
			
			// console.log(eventObj)
			
			var elOffsetX = $(this).offset().left,
	        elOffsetY = $(this).offset().top,
	        $x = Math.round( eventObj.pageX - elOffsetX ),
	        $y = Math.round( eventObj.pageY - elOffsetY );
			
			// console.log(eventObj);
			
			$("#<?=$field_name?>_xc").val( $x );
			$("#<?=$field_name?>_yc").val( $y );
			
			if($x != 0 && $y != 0)
			{
				$("#<?=$field_name?>_pointee_img .pointee_map_marker").css({
					left: ($x - $marker_offset_x) + 'px', top: ($y - $marker_offset_y) + 'px'
				});
			}
	
		});
	
	});
</script>

<style type="text/css">
	#<?=$field_name?>_pointee_img .pointee_map_marker {
		left: <?=($xc != 0) ? ($xc-$offset_x) : -5000;?>px; 
		top:  <?=($yc != 0) ? ($yc-$offset_y) : -5000;?>px; 
	}
</style>

<div class="pointee_fields">
	<?= 
		form_input(array(
			'name'		=> $field_name.'[img]',
			'id'		=> $field_name,
			'value'		=> ($fixed_img_url != '') ? '[fixed]' : $image,
			'style'		=> 'display:none;'
		)); 
	?>
	<div class="pointee_x_wrapper pointee_var<?=($image =='' && $fixed_img_url == '') ? ' js_hide' : ''?>">
		<span>X:</span>
		<?= 
			form_input(array(
				'name'		=> $field_name.'[xc]',
				'id'		=> $field_name.'_xc',
				'value'		=> $xc,
				'class'		=> 'xc',
				'style'		=> 'width:40px;'
			)); 
		?>
	</div>
	<div class="pointee_y_wrapper pointee_var<?=($image =='' && $fixed_img_url == '') ? ' js_hide' : ''?>">
	<span>Y:</span>
		<?= 
			form_input(array(
				'name'		=> $field_name.'[yc]',
				'id'		=> $field_name.'_yc',
				'value'		=> $yc,
				'class'		=> 'yc',
				'style'		=> 'width:40px;'
			)); 
		?>
	</div>
</div>
<? if($fixed_img_url  == ''):?>
	<div class="pointee_file_chooser<?=($image !='') ? ' pointee_quiet' : ''?>">
		<button id="<?=$field_name?>_trigger" class="submit"><?=lang('select_file')?></button>
	</div>
<? endif ?>