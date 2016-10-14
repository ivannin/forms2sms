<?php
add_action( 'admin_menu', 'f2sms_add_admin_menu' );
add_action( 'admin_init', 'f2sms_settings_init' );


function f2sms_add_admin_menu(  ) { 

	add_options_page( 
        'Отправка форм на SMS', 
        'Формы на SMS', 
        'manage_options', 'forms2sms', 'f2sms_options_page' );

}


function f2sms_settings_init(  ) { 

	register_setting( 'pluginPageF2SMS', 'f2sms_settings' );

	add_settings_section(
		'f2sms_pluginPageF2SMS_section', 
		__( 'Настроки плагина', 'forms2sms' ), 
		'f2sms_settings_section_callback', 
		'pluginPageF2SMS'
	);

	add_settings_field( 
		'f2sms_text_field_0', 
		__( 'Список телефонов', 'forms2sms' ), 
		'f2sms_text_field_0_render', 
		'pluginPageF2SMS', 
		'f2sms_pluginPageF2SMS_section' 
	);

	add_settings_field( 
		'f2sms_text_field_1', 
		__( 'Поля форм CF7 с телефоном', 'forms2sms' ), 
		'f2sms_text_field_1_render', 
		'pluginPageF2SMS', 
		'f2sms_pluginPageF2SMS_section' 
	);
	
	add_settings_field( 
		'f2sms_text_field_2', 
		__( 'Поля форм CF7 с имененм', 'forms2sms' ), 
		'f2sms_text_field_2_render', 
		'pluginPageF2SMS', 
		'f2sms_pluginPageF2SMS_section' 
	);	
	
	add_settings_field( 
		'f2sms_text_field_3', 
		__( 'Логин', 'forms2sms' ), 
		'f2sms_text_field_3_render', 
		'pluginPageF2SMS', 
		'f2sms_pluginPageF2SMS_section' 
	);
	
	add_settings_field( 
		'f2sms_text_field_4', 
		__( 'API key', 'forms2sms' ), 
		'f2sms_text_field_4_render', 
		'pluginPageF2SMS', 
		'f2sms_pluginPageF2SMS_section' 
	);	
	
	add_settings_field( 
		'f2sms_text_field_5', 
		__( 'Отравитель', 'forms2sms' ), 
		'f2sms_text_field_5_render', 
		'pluginPageF2SMS', 
		'f2sms_pluginPageF2SMS_section' 
	);	
}


function f2sms_text_field_0_render(  ) { 
	$options = get_option( 'f2sms_settings' );
	?>
    <textarea name="f2sms_settings[tel_list]" style="width:300px;height:10em"><?php echo $options['tel_list']; ?></textarea>
	<?php

}

function f2sms_text_field_1_render(  ) { 
	$options = get_option( 'f2sms_settings' );
	?>
    <textarea name="f2sms_settings[field_list]" style="width:300px;height:10em"><?php echo $options['field_list']; ?></textarea>
	<?php

}

function f2sms_text_field_2_render(  ) { 
	$options = get_option( 'f2sms_settings' );
	?>
    <textarea name="f2sms_settings[field_name_list]" style="width:300px;height:10em"><?php echo $options['field_name_list']; ?></textarea>
	<?php

}


function f2sms_text_field_3_render(  ) { 
	$options = get_option( 'f2sms_settings' );
	?>
    <input type="text" name="f2sms_settings[login]" value="<?php echo $options['login']; ?>" style="width:300px" />
	<?php

}


function f2sms_text_field_4_render(  ) { 
	$options = get_option( 'f2sms_settings' );
	?>
    <input type="text" name="f2sms_settings[api_key]" value="<?php echo $options['api_key']; ?>" style="width:300px" />
	<?php

}

function f2sms_text_field_5_render(  ) { 
	$options = get_option( 'f2sms_settings' );
	?>
    <input type="text" name="f2sms_settings[sender]" value="<?php echo $options['sender']; ?>" style="width:300px" />
	<?php

}

function f2sms_settings_section_callback(  ) { ?>
    <p>Этот плагин отправляет формы на SMS по указанным нормерам</p>
<?php 
}


function f2sms_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		<h1>Отправка форм на SMS</h1>

		<?php
		settings_fields( 'pluginPageF2SMS' );
		do_settings_sections( 'pluginPageF2SMS' );
		submit_button();
		?>

	</form>
	<?php

}

?>