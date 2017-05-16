<?php
return array(

	'app_begin'=>array(
		'Admin\Behavior\RequestValidateBehavior',
		'Admin\Behavior\ActionLogBehavior'
	),

	'template_filter'=>array(
		'Admin\Behavior\PermissionTemplateBehavior'
	)
);