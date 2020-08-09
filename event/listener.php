<?php
/**
*
* Who Is Online 4 Guests extension for the phpBB Forum Software package.
*
* @copyright (c) 2020 Rich McGirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\whoisonline4guests\event;

/**
* @ignore
*/
use phpbb\template\template;
use phpbb\user;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpBB root path */
	protected $phpbb_root_path;

	/** @var string phpEx */
	protected $php_ext;

    /**
	* Constructor for listener
	*
	* @param	 \phpbb\template\template	$template		Template object
	* @param 	\phpbb\user					$user			User object
	* @param	string						$phpbb_root_path
	* @param	string						$php_ext
	*
	* @access public
	*/
	public function __construct(template $template, user $user, $phpbb_root_path, $php_ext)
	{
		$this->template = $template;
		$this->user = $user;
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header_after'			=> 'page_header_after',
		);
	}

	/**
	* Change U_VIEWONLINE template var
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function page_header_after($event)
	{
		// an array of pages where viewing online users is allowed
		$pages = ['index','viewforum'];

		// the users page
		$page_name = substr($this->user->page['page_name'], 0, strpos($this->user->page['page_name'], '.'));

		if ($this->user->data['user_id'] == ANONYMOUS && in_array($page_name, $pages))
		{
			$this->template->assign_vars([
				'U_VIEWONLINE' => append_sid("{$this->root_path}viewonline.$this->php_ext"),
			]);
		}
	}
}
