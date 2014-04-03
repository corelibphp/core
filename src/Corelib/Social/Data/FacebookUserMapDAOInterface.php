<?php
/**
 * Data access methods for FacebookUserMap
 *
 * @since  2014-03-06
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Social\Data;

/**
 * Data access methods for FacebookUserMap
 *
 * @since  2014-03-06
 * @author Patrick Forget <patforg@geekpad.ca>
 */
interface FacebookUserMapDAOInterface
{

    /**
     * retreive a single FacebookUserMapBO by its unique ID
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function getById($id, $options = array());

    /**
     * retreive a collection of FacebookUserMaps based on search criteria
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function search($condition = null, $options = array());

} //  FacebookUserMapDAOInterface class
