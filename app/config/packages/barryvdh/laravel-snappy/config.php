<?php

return array(

	'pdf' => array(
		'enabled' => true,
		'binary' => $_ENV['WKHTML_PDF_BIN'],
		'timeout' => false,
		'options' => array(),
	),
	'image' => array(
		'enabled' => true,
		'binary' => $_ENV['WKHTML_IMG_BIN'],
		'timeout' => false,
		'options' => array(),
	),

);
