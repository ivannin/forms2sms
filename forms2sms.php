<?php
/*
Plugin Name: Forms to SMS
Plugin URI:  http://in-soft.pro/plugins/forms2sms
Description: Плагин отправлят заполенные формы на SMS
Version:     1.0
Author:      Иван Никитин
Author URI:  http://ivannikitin.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /lang
Text Domain: forms2sms
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Основные константы плагина
define('F2SMS_DIR', plugin_dir_path( __FILE__ ));

// Файлы плагина
include( F2SMS_DIR.'classes/sms2manager.php' );
include( F2SMS_DIR.'options.php' );

// Хук CF7
add_action('wpcf7_before_send_mail', 'f2sms_send_sms');
function f2sms_send_sms( $cf7 )
{
    
    // Читаем данные и конвердируем объект формы
    // См. CFDBIntegrationContactForm7.php
    $formData = $cf7;
    if (!isset($cf7->posted_data) && class_exists('WPCF7_Submission')) 
    {
        // Contact Form 7 version 3.9 removed $cf7->posted_data and now
        // we have to retrieve it from an API
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $data = array();
            $data['title'] = $cf7->title();
            $data['posted_data'] = $submission->get_posted_data();
            $data['uploaded_files'] = $submission->uploaded_files();
            $data['WPCF7_ContactForm'] = $cf7;
            $formData = (object) $data;
        }
    }
   
   /* DEBUG: file_put_contents(F2SMS_DIR.'form.txt', var_export($formData, true) ); */
    
    
    // Параметры плагина
    $options = get_option( 'f2sms_settings' );
    
    // Ищем поле формы с телефоном, которое перечислено в параметрах
    $tel = '';
    foreach ($formData->posted_data as $key => $value)
    {
        if ( strpos( $options['field_list'], $key ) !== false )
        {
            $tel = $value;
            break;                   
        }
    } 
    
    
    // Ищем поле формы с имененм, которое перечислено в параметрах
    $name = '';
    foreach ($formData->posted_data as $key => $value)
    {
        if ( strpos( $options['field_name_list'], $key ) !== false )
        {
            $name = $value;
            break;                   
        }
    }    
    
    
    // Если нашли номер телефона, отправляем SMS
    if ( !empty( $tel ))
    {
        $text = "Перезвоните на $tel";
        
        if ( !empty( $name ))
            $text .= " " . PHP_EOL . "Заказчик $name";
        
        // Отправляем SMS
        $sms = new SMS2Manager();
        $sms->send( $text );
        
    }
    else
    {
        // В форме не найден номер телефона
        file_put_contents( F2SMS_DIR.'no-phone-form.txt', $formData->title . PHP_EOL, FILE_APPEND );
    }
    
    
    // Все! Возвращаем объект дальше!
    return $cf7;
}