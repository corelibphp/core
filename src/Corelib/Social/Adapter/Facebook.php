<?php
/**
 * Facebook related functions
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Social\Adapter;


/**
 * Facebook related functions
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class Facebook
{
    /**
     * @var array
     */
    private $config = array();

    /**
     * @var \Corelib\DataStore\StorageAdapterInterface
     */
    private $dataStore = null;

    /**
     * class constructor
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct($config, $dataStore) {

        $this->config = $config;
        $this->dataStore = $dataStore;

    } // __construct()

    /**
     * reteive token to make requests
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     * @return mixed false if no token exists or string if a token is found
     */
    public function getToken() {
        $tokenInfo = $this->dataStore->get('tokenInfo');
        if ($tokenInfo !== false) {
            return $tokenInfo['token'];
        } else {
            return false;
        } //if

    } // getToken()

    /**
     * validate returned response from facebook
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function validateLoginResponse($response) {
        $valid = true; 
        $errors = array();

        /* Check that we have the needed data in the data store  */
        if (!isset($response['code'])) {
            $valid = false;
            $errors[] = 'code not found in provided params';
        } //if

        if (!$valid) {
            return array($valid, $errors);
        } //if

        $responseURL = $this->dataStore->get('responseURL');

        if ($responseURL === false) {
            $valid = false;
            $errors[] = 'responseURL not found in data store';
        } //if

        $state = $this->dataStore->get('state');
        if ($state === false) {
            $valid = false;
            $errors[] = 'state not found in data store';
        } elseif (!isset($response['state'])){
            $valid = false;
            $errors[] = 'state not found in provided params';
        } elseif ($response['state'] !== $state) {
            $valid = false;
            $errors[] = 'state does not match';
        } //if

        if (!$valid) {
            return array($valid, $errors);
        } //if

        /* Validate code with facebook  */

        $appId = urlencode($this->config['appId']);
        $appSecret = urlencode($this->config['appSecret']);
        $responseURL = urlencode($responseURL);
        $code = urlencode($response['code']);

        $codeValidateURL = "https://graph.facebook.com/oauth/access_token?client_id={$appId}&redirect_uri={$responseURL}&client_secret={$appSecret}&code={$code}";

        list($status, $response) = $this->doRequest($codeValidateURL);

        if ($status === false) {
            $valid = false;
            $errors[] = $repsonse;
            return array($valid, $errors);
        } //if
        
        /* extract and validate token from code response  */
        parse_str($response, $tokenParams);

        if (isset($tokenParams['access_token'])) {
            list($success, $returnValue) = $this->validateToken($tokenParams['access_token']);

            if ($success === false) {
                $valid = false;
                $errors = array_merge($errors, $returnValue);
            } //if

        } else {
            $valid = false;
            $errors[] = 'no token returned';
        } //if

        return array($valid, $errors);
    } // validateLoginResponse()


    /**
     * get facebook login request URL
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function getLoginURL($responseURL) {
        $appId = urlencode($this->config['appId']);

        $this->dataStore->set('responseURL', $responseURL);
        $responseURL = urlencode($responseURL);

        $validCharacters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $upperLimit = strlen($validCharacters) - 1;
        ob_start();
        for ($n = 1; $n <= 32; $n++) {
            $index = rand(0, $upperLimit);
            echo $validCharacters{$index};
        } //for
        $randomState = ob_get_contents();
        ob_end_clean();
        
        $this->dataStore->set('state', $randomState);

        $loginURL = "https://www.facebook.com/dialog/oauth?client_id={$appId}&redirect_uri={$responseURL}&state={$randomState}";

        if (isset($this->config['scope'])) {
            $loginURL .= "&scope=". urlencode($this->config['scope']);
        } //if

        return $loginURL;

    } // getLoginURL()

    /**
     * retreive current user profile
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function getUserProfile($userId = null) {

        $valid = true;
        $errors = array();
        
        $token = $this->getToken();

        if ($token === false) {

            $valid = false;
            $errors[] = 'token no available';

        } else {

            $userId = ($userId === null ? 'me' : $userId);

            $userInfoURL = "https://graph.facebook.com/{$userId}?access_token={$token}";

            list($status, $response) = $this->doRequest($userInfoURL);

            if ($status === false) {
                $valid = false;
                $errors = array_merge($errors, $response);
            } else {
                $userInfo = json_decode($response);
                if ($userInfo === false) {
                    $valid = false;
                    $errors[] = 'could not parse json respone';
                } else {
                    return array(true, $userInfo);
                } //if
            } //if

        } //if

        return array($valid, $errors);

    } // getUserProfile()


    /**
     * validates a token and store info in datastore
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    private function validateToken($token) {
        $valid = true;
        $errors = array();
        $appId = urlencode($this->config['appId']);

        $appSecret = urlencode($this->config['appSecret']);
        $encodedToken = rawurlencode($token);

        $tokenURL = "https://graph.facebook.com/debug_token?input_token={$encodedToken}&access_token={$appId}|{$appSecret}";

        list($status, $response) = $this->doRequest($tokenURL);

        if ($status === false) {
            $valid = false;
            $errors = array_merge($errors, $response);
        } else {
            $tokenData = json_decode($response);

            if ($tokenData === null) {
                $valid = false;
                $errors[] = 'response could not be decoded as json';

            } elseif (isset($tokenData->data->is_valid) && $tokenData->data->is_valid) {
                $tokenInfo = array(
                    'token' => $token,
                    'expires' => $tokenData->data->expires_at,
                    'scopes' => $tokenData->data->scopes
                );

                $this->dataStore->set('tokenInfo', $tokenInfo);
            } else {
                $valid = false;
                $errors[] = 'token could not be validated';
            } //if
        } //if

        return array($valid, $errors);
    } // validateToken()


    /**
     * does a CURL request and returns response
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     * @return array two value array
     *   * first value is status of operation (true/false)
     *   * second value is the reponse in case of success, the error message in case of failures
     */
    private function doRequest($URL) {
        
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $URL);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($curlHandle);
            curl_close($curlHandle);
            
            if ($response === false) {
                return array(false, curl_error($curlHandle));
            } else {
                return array(true, $response);
            } //if
    } // doRequest()

} //  Facebook class
