<?php

/**
 * Wake_on_lan controller.
 *
 * @category   Apps
 * @package    Wake_on_lan
 * @subpackage Controllers
 * @author     itot_africa
 * @copyright  2013 Your name / Company
 * @license    Your license
 */

///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('wake_on_lan');

///////////////////////////////////////////////////////////////////////////////
// Form
///////////////////////////////////////////////////////////////////////////////

echo infobox_highlight(lang('wake_on_lan_app_name'), '');



$this->lang->load('base');

$options['buttons']  = array(
    anchor_custom('/app/base/shutdown/confirm/on', lang('base_restart'), 'high')
  
    
);

echo form_header();

echo form_open(exemple/test);



echo form_label('Adresse IP:');
echo form_input($ip);
echo ('<br/>');
echo form_label('Adressse MAC :');
echo form_input($mac);
echo ('<br/>');
echo form_label('numero de port :');
echo form_input($port);
echo ('<br/>');
$mavar = 'wake_on_lan/send/';
echo anchor_ok($mavar, $importance = 'high', $options = NULL);

echo form_footer();
echo form_close();

echo infobox_highlight(
    lang('base_shutdown_restart'),
    lang('base_shutdown_restart_help'),
    $options
);



