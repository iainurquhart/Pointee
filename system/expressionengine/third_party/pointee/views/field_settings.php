<?php

	
	

	$this->table->add_row(
			lang('image_per_entry').':',
			form_dropdown("options[image_per_entry][]", $options)
		);
		
	echo $this->table->generate();
?>