<?php
/**
 * Creates dictionary instances
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Dictionary;

/**
 * Creates dictionary instances
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class DictionaryFactory  extends \Corelib\Standard\Factory
{

    /**
     * Returns a dynamic function that will return translated strings given a message id
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param string $domain application domain where to extract the messages (the name of the dictionary)
     * @param array $options 
     *     * string "scope" defines the scope of the dictionary to use
     */
    public static function getDictionaryTermFunction($domain, $options = array()) {
        return self::getDomainFunctions($domain, $options)['singular'];
    } // getDictionaryTermFunction()

    /**
     * Returns a dynamic function that returns translated plural strings
     *
     * @since  2014-01-15
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public static function getDictionaryPluralTermFunction($domain, $options = array()) {
        return self::getDomainFunctions($domain, $options)['plural'];
    } // getDictionaryPluralTermFunction()


    /**
     * creates functions for a given domain if not already done
     *
     * @since  2014-01-15
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    private static function getDomainFunctions($domain, $options) {
        static $domainFunctions = array();
        if (!isset($domainFunctions[$domain])) {

            /* different scopes can have different settings nottably file paths  */
            $scope = ( isset($options['scope']) ? $options['scope'] : 'app' );
            
            $configDAO = \Corelib\Config\ConfigFactory::getConfigDAO();
            $dicConfig = $configDAO->getElementValuesById('dictionary');

            if (!isset($dicConfig['scopes'][$scope]['path'])) {
                throw new \Exception("Missing Path for dictionaries using scope key ({$scope})");
            } //if

            $encoding = ( isset($dicConfig['scopes'][$scope]['encoding']) ? $dicConfig['scopes'][$scope]['encoding'] : 'UTF-8' );

            bindtextdomain($domain, $dicConfig['scopes'][$scope]['path']);
            bind_textdomain_codeset($domain, $encoding);
            
            $domainFunctions[$domain] = array();

            /* anonymous function that retreives non varying strings (not plural) */
            $domainFunctions[$domain]['singular'] = function ($messageKey, $context = null) use ($domain) {

                if ($context === null) {
                    return dgettext($domain, $messageKey);
                } else {
                    $contextString = "{$context}\004{$messageKey}";
                    $translation = dgettext($domain, $contextString);
                    return ($translation === $contextString ? $messageKey : $translation);
                } //if
            };

            /* anonymous function that retreives the plural form  */
            $domainFunctions[$domain]['plural'] = function ($messageKey, $pluralKey, $number, $context = null) use ($domain) {

                if ($context === null) {
                    return dngettext($domain, $messageKey, $pluralKey, $number);
                } else {
                    $contextString = "{$context}\004{$messageKey}";
                    $pluralContextString = "{$context}\004{$messageKey}";
                    $translation = dngettext($domain, $contextString, $pluralContextString, $number);
                    return ($translation === $contextString ? $messageKey : $translation);
                } //if
            };
        } //if

        return $domainFunctions[$domain];
    } // getDomainFunctions()

} // DictionaryFactory class
