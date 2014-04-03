<?php
/**
 * Defines method to store and update UserBO instances
 *
 * @since  2014-03-06
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\User\Data;

/**
 * Defines method to store and update UserBO instances
 *
 * @since  2014-03-06
 * @author Patrick Forget <patforg@geekpad.ca>
 */
interface UserDSOInterface
{

    /**
     * save and update 
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function save(\Corelib\User\Model\UserBO $user, $options=array());

    /**
     * delete element
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function delete($id, $options=array());

} //  UserDSOInterface class
