<?php

/**
 * Wake_on_lan controller.
 *
 * @category   Apps
 * @package    Wake_on_lan
 * @subpackage Views
 * @author     Your name <your@e-mail>
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
 * @author     Your name <your@e-mail>
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
        $this->page->view_form('essai', NULL, lang('wake_on_lan_app_name'));
    }  
    
    
    
    
    
    /** Send WOL package
	 * @param	string		$addr		- IP address
	 * @param	string		$mac		- Media access control address (MAC)
	 * @param	integer		$port		- Port number at which the data will be sent
	 * @return	boolean
	 */
	function send() {
		// Throw exception if extension is not loaded
		$addr = $this->input->post($ip);
		$mac = $this->input->post($mac);
		$port = $this->input->post($port);

		if (!extension_loaded('sockets')) {
			self::throwError("Error: The sockets extension is not loaded!");
		}

		// Check if $addr is valid IP, if not try to resolve host
		if (!filter_var($addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
			// Try to get the IPv4 address of a given host name
			$originalAddr = gethostbyname($addr);
			if ($originalAddr == $addr) {
				self::throwError('Error: Domain name is unresolvable or IP address is invalid!');
			} else {
				$addr = $originalAddr;
			}
		}
		
		$macHex = str_replace(array(':', '-'), NULL, $mac);
		
		// Throw exception if mac address is not valid
		if (!ctype_xdigit($macHex) || strlen($macHex) != 12) {
			self::throwError('Error: Mac address is invalid!');
		}
		
		// Magic packet
		$packet = str_repeat(chr(255), 6) . str_repeat(pack('H12', $macHex), 16);
		
		// Send to the broadcast address using UDP
		self::$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		
		if (is_resource(self::$socket)) {
		
			// Set socket option
			if (!socket_set_option(self::$socket, SOL_SOCKET, SO_BROADCAST, TRUE)) {
				self::throwError();
			}
			
			// Send magic packet
			if (socket_sendto(self::$socket, $packet, strlen($packet), 0, $addr, $port) !== FALSE) {
				socket_close(self::$socket);
				return $msg;
				echo("connexion reussie");
			}
		}
		self::throwError();
	}
	/** Throw Last Error
	 * @param	string		$msg	- Error message
	 * @return	void
	 */
	private static function throwError($msg = NULL) {
		// Take last error if err msg is empty
		if (empty($msg)) {
			self::$errCode = socket_last_error(self::$socket);
			self::$errMsg = socket_strerror(self::$errCode);
			$msg = "Error (" . self::$errCode . "): " . self::$errMsg;
		}
		throw new Exception($msg);
	}
}


