<?php
/**
 * Database Authentication adapter
 *
 * @since  2014-01-30
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Zend\Authentication\Adapter;

use \Zend\Authentication\Result;

/**
 * Database Authentication adapter
 *
 * @since  2014-01-30
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class Facebook implements \Zend\Authentication\Adapter\AdapterInterface
{

    /**
     * @var \Corelib\Social\Facebook
     */
    private $facebook = null;

    /**
     * @var string
     */
    private $callbackURL = '';

    /**
     * @var array
     */
    private $response = array();

    /**
     * retrieve value for response
     *
     * @since  2014-02-17
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @return array current value of response
     */
    public function getResponse() {
        return $this->response;
    } // getResponse()

    /**
     * assign value for response
     *
     * @since  2014-02-17
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param array value to assign to response
     */
    public function setResponse($value) {
        $this->response = $value;
    } // setResponse()

    /**
     * class constructor
     *
     * @since  2014-01-30
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param string $callbackURL url to redirect with facebook response (to be set via setResponse method)
     * 
     * @param \Corelib\Social\Adapter\Facebook $facebook utility class that handles facebook's API
     *
     */
    public function __construct($callbackURL, $facebook) {

        $this->facebook = $facebook;

        $this->callbackURL = $callbackURL;

    } // __construct()


    /**
     * authenticate method
     *
     * Important to note that when  Result::FAILURE_IDENTITY_AMBIGUOUS is returned 
     * you need to redirect the user to a URL provided in the messages of the result 
     * objec under key 'redirect', this will prompt user to grant access, control will
     * be returned to the callbackURL, at this point you need to setup the response value
     * to successfully complete the auth
     *
     * @since  2014-01-30
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function authenticate() {

        $code = Result::FAILURE;
        $identity = '';
        $messages = array();

        $response = $this->getResponse();

        /* only validate response if response is not empty  */
        if (sizeof($response) > 0) {

            list($status, $response) = $this->facebook->validateLoginResponse($this->response); 

            if ($status === false) {
                $code = Result::FAILURE_CREDENTIAL_INVALID;
            } else {
                $code = Result::SUCCESS;
            } //if

        } else {
            
            $token = $this->facebook->getToken();

            if ($token === false) {
                $code = Result::FAILURE_IDENTITY_AMBIGUOUS;
                $url = $this->facebook->getLoginURL($this->callbackURL);
                $messages['redirect'] = $url;

            } else {
                
                list($status, $response) = $this->facebook->getUserProfile();

                if ($status === false) {
                    $code = Result::FAILURE_IDENTITY_NOT_FOUND;
                } else {
                    $code = Result::SUCCESS;
                    $identity = $response->id;
                } //if

            } //if

        } //if

        return new Result($code, $identity, $messages);
    } // authenticate()

} //  Facebook class
