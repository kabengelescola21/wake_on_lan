<?php

/**
 * Wake_on_lan controller.
 *
 * @category   Apps
 * @package    Wake_on_lan
 * @subpackage Views
 * @author     scola0021@gmail.com
 * @copyright  2013 Your name / Company
 * @license    Your license
 */

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Wake_on_lan controller.
 *
 * @category   Apps
 * @package    Wake_on_lan
 * @subpackage Controllers
 * @author     scola0021@gmail.com
 * @copyright  2013 Your name / Company
 * @license    Your license
 */
//use \clearos\apps\wake_on_lan\Php_wol;
class Wake_on_lan extends ClearOS_Controller
{
    /**
     * Wake_on_lan default controller.
     *
     * @return view
     */
//  $socket = 0;
//  $errCode = 0;
//  $errMsg = NULL;

    function index()
    {
        // Load dependencies
        //------------------

        $this->lang->load('wake_on_lan');


        // Load views
        //-----------

        $this->page->view_form('wake_on_lan', NULL, lang('wake_on_lan_app_name'));
        $this->labrary->load('Php_wol');
        $this->page->view_form('essai', NULL, lang('wake_on_lan_app_name'));
    }  
    
    
    
    
    
    /** Send WOL package
	 * @param	string		$addr		- IP address
	 * @param	string		$mac		- Media access control address (MAC)
	 * @param	integer		$port		- Port number at which the data will be sent
	 * @return	boolean
	 */
	function WakeOnLan(){
	    
	    $addr = $this->input->post($ip);
        $mac = $this->input->post($mac);
        $socket_number = $this->input->post($port);

	    if (strlen($mac) != 17)
		    return FALSE;

	    if (preg_match('/[^A-Fa-f0-9:]/',$mac)) 
		    return FALSE;

	    $addr_byte = explode(':', $mac);
	    $hw_addr   = '';
	
	    for ($a=0; $a <6; $a++) 
		    $hw_addr .= chr(hexdec($addr_byte[$a]));
	
	        $msg = chr(255).chr(255).chr(255).chr(255).chr(255).chr(255);
	
	    for ($a = 1; $a <= 16; $a++) 
		    $msg .= $hw_addr;
	
	        $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	
	        if ($s == FALSE) {
		        echo "<div class=\"messageNOK\">Can't create socket!</div>\n";
		        echo "Error: '".socket_last_error($s)."' - " . socket_strerror(socket_last_error($s));
		        return FALSE;
	        } 
	        else {
		        $opt_ret = socket_set_option($s, SOL_SOCKET, SO_BROADCAST, TRUE);
	
		        if ($opt_ret < 0) {
			        echo "setsockopt() failed, error: " . strerror($opt_ret) . "<br />\n";
			        return FALSE;
		        }
	
		        if (socket_sendto($s, $msg, strlen($msg), 0, $addr, $socket_number)) {
			        $content = bin2hex($msg);
			        echo "<hr />\n";
			        echo "<div class=\"messageOK\">Magic Packet Sent!</div>\n";
			        echo "<b>Port:</b> ".$socket_number." <b>MAC:</b> ".$_GET['wake_machine']." <b>Data:</b>\n";
			        echo "<textarea readonly class=\"textarea\" name=\"content\" >".$content."</textarea><br />\n";
			        socket_close($s);
			        return TRUE;
		        }
		    else {
			    echo "<div class=\"messageNOK\">Magic Packet failed to send!</div>\n";
			    return FALSE;
		    } 
	    }
	    echo("connexion reussie!!!");
    }
 
}


