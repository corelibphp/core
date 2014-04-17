<?php
/**
 * Defines method to store and update FacebookUserMap
 *
 * @since  2014-04-16
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Social\Data;

/**
 * Defines method to store and update FacebookUserMap
 *
 * @since  2014-04-16
 * @author Patrick Forget <patforg@geekpad.ca>
 */
interface FacebookUserMapDSOInterface
{

    /**
     * save and update 
     *
     * @since  2014-04-16
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function save(\Corelib\Social\Model\FacebookUserMapBO $item, $options = array());

    /**
     * delete element
     *
     * @since  2014-04-16
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function delete($id, $options = array());

} //  FacebookUserMapDAOInterface class
