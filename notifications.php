<?php

/**
 * Notifications Class
 * 
 * The Notifications class allows you to easily set and retrieve 
 * system notifications within your app. It allows you to set a 
 * message for the current request or you can make it be available 
 * on the next request (for when you want to set a notification 
 * and then redirect to another page).
 *
 * @author     Johnny Freeman
 * @license    MIT License
 * @copyright  2011 Johnny Freeman
 * @link       http://johnnyfreeman.us
 */

class Notifications
{
    /**
    * @var	array	holds messages
    */
    protected static $_messages = array();

	/**
	 * Set a notification message
	 *
	 * @param	string	key
     * @param   mixed   value
	 * @param	bool	if true, the notification will persist through a redirect
	 * @access	public
	 * @return	void
	 */
    public static function set($type, $value, $persist = false)
    {
    	if ($persist)
    	{
    		$notifications = \Session::get_flash('notifications');
    		$notifications[] = array('type' => $type, 'text' => $value);
    		\Session::set_flash('notifications', $notifications);
    	}
    	else 
    	{
    		static::$_messages[] = array('type' => $type, 'text' => $value);
    	}
    }
    
    /**
	 * Get all notifications
     * 
     *      <?php foreach (Notifications::get() as $n): ?>
     *          
     *          <div class="<?php echo $n['type']; ?>">
     *              <?php echo $n['text']; ?>
     *          </div>
     *          
     *      <?php endforeach; ?>
	 *
	 * @access	public
	 * @return	Array
	 */
    public static function get()
    {
    	// merge flash messages with Notifications::$_messages
    	if (!is_null(\Session::get_flash('notifications')))
    	{
    		static::$_messages = array_merge( static::$_messages, \Session::get_flash('notifications') );
    	}

        // get all messages
    	return static::$_messages;
    }

    /**
     * Make all notifications persist through to the next request
     * 
     *      <?php 
     * 
     *          Notifications::persist();
     *          Response::redirect('somewhere');
     *          
     *      ?>
     *
     * @param   string  notification type
     * @access  public
     * @return  void
     */
    public static function persist()
    {
        // merge messages with flash messages
        if (!is_null(\Session::get_flash('notifications')))
        {
            static::$_messages = array_merge( (array)static::$_messages, \Session::get_flash('notifications') );
        }

        // save all messages
        \Session::set_flash('notifications', static::$_messages);
    }
}