<?php
/**
 * @title          pH7Client
 * @desc           pH7Client simulates a Web browser. It automates the task of retrieving web page content and posting forms, for example.
 *
 * @author         Pierre-Henry Soria <hello@ph7cms.com>
 * @copyright      (c) 2015-2017, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / External / HTTP / Client
 */

namespace PH7\External\Http\Client;

use InvalidArgumentException;

/**
 * First off, we check the requirements of the class.
 */
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
   exit('Your PHP version is ' . PHP_VERSION . '. pH7CMS.class.php requires PHP 5.4 or newer.');
}

if (!function_exists('curl_init')) {
    exit('pH7CMS.class.php requires cURL PHP library. Please install it before running the class.');
}


class PH7Client
{
    const PLAIN_TYPE = 1;
    const ARR_TYPE = 2;
    const OBJ_TYPE = 3;

    const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:11.0) Gecko/20100101 Firefox/11.0';

    /** @var  resource */
    private $_rCurl;

    /** @var string */
    private $_sRemoteDomain;

    /** @var string */
    private $_sUrl;

    /** @var string */
    private $_sType;

    /** @var null|string */
    private $_sSslPath;

    /** @var string */
    private $_sResponse;

    /** @var string */
    private $_sCookieFileName = 'cookie_log.txt';

    /** @var bool */
    private $_bHeader = true;

    /** @var array */
    private $_aParams;

    /** @var array */
    private $_aAllowTypes;

    /**
     * Assign values to the attributes.
     *
     * @param string $sRemoteDomain The URL of where you want to execute the actions.
     * @param string $sSslPath If the URL where your installed pH7CMS used SSL certificate, you have to specify the certificate directory here. Ex: "/path/certificate.pem". Default: NULL
     */
    public function __construct($sRemoteDomain, $sSslPath = null)
    {
        $this->_rCurl = curl_init();
        $this->_sRemoteDomain = (substr($sRemoteDomain, -1) != '/' ? $sRemoteDomain . '/' : $sRemoteDomain); // The domain has to finished by a Slash "/"
        $this->_sSslPath = $sSslPath;
        $this->_aAllowTypes = array('GET', 'POST', 'PUT', 'DELETE');
    }

    /**
     * @param string $sUrl
     * @param array $aParms
     *
     * @return self
     */
    public function get($sUrl, array $aParms)
    {
        $this->_set($sUrl, $aParms, 'GET');

        return $this;
    }

    /**
     * @param string $sUrl
     * @param array $aParms
     *
     * @return self
     */
    public function post($sUrl, array $aParms)
    {
        $this->_set($sUrl, $aParms, 'POST');
        return $this;
    }

    /**
     * @param string $sUrl
     * @param array $aParms
     *
     * @return self
     */
    public function put($sUrl, array $aParms)
    {
        $this->_set($sUrl, $aParms, 'PUT');

        return $this;
    }

    /**
     * @param string $sUrl
     * @param array $aParms
     *
     * @return self
     */
    public function delete($sUrl, array $aParms)
    {
        $this->_set($sUrl, $aParms, 'DELETE');

        return $this;
    }

    /**
     * @param bool $bHeader If TRUE, it passes headers to the data stream. Default: TRUE
     *
     * @return self
     */
    public function setHeader($bHeader = true)
    {
        $this->_bHeader = $bHeader;

        return $this;
    }

    /**
     * Get the response.
     *
     * @param integer $sType The type of response. Can be 'PH7CMS::OBJ_TYPE', 'PH7CMS::ARR_TYPE', or 'PH7CMS::PLAIN_TYPE'
     *
     * @return string|array|object The response into Plain, Array or Object format.
     *
     * @throws InvalidArgumentException If the type (specified in $sType parameter) is invalid.
     */
    public function getResponse($sType = self::PLAIN_TYPE)
    {
        switch ($sType) {
            case static::OBJ_TYPE:
                return json_decode($this->_sResponse);
            break;

            case static::ARR_TYPE:
                return json_decode($this->_sResponse, true);
            break;

            case static::PLAIN_TYPE:
                return $this->_sResponse;
            break;

            default:
                throw new InvalidArgumentException ('Invalide Response Type. The type can only be "PH7CMS::OBJ_TYPE", "PH7CMS::ARR_TYPE", or "PH7CMS::PLAIN_TYPE"');
        }
    }

    public function getCookieFile()
    {
        return $this->_sCookieFileName;
    }

    /**
     * Change the location of the cookie file (where the cookies are stored).
     *
     * @param string $sFileName Path to the file.
     *
     * @return self
     */
    public function setCookieFile($sFileName)
    {
        $this->_sCookieFileName = $sFileName;

        return $this;
    }

    /**
     * Sent data to the remote site.
     *
     * @return self
     *
     * @throws InvalidArgumentException If the type (specified in $sType parameter) is invalid.
     */
    public function send()
    {
        if (!in_array($this->_sType, $this->_aAllowTypes)) {
            throw new InvalidArgumentException ('The Request Type can be only "GET", "POST", "PUT" or "DELETE!"');
        }

        $sPostString = http_build_query($this->_aParams, '', '&');
        curl_setopt($this->_rCurl, CURLOPT_URL, $this->_sRemoteDomain . $this->_sUrl);
        curl_setopt($this->_rCurl, CURLOPT_HEADER, $this->_bHeader);
        curl_setopt($this->_rCurl, CURLOPT_POSTFIELDS, $sPostString);
        curl_setopt($this->_rCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_rCurl, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->_rCurl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->_rCurl, CURLOPT_CUSTOMREQUEST, "{$this->_sType}");
        curl_setopt($this->_rCurl, CURLOPT_COOKIESESSION, true);
        curl_setopt($this->_rCurl, CURLOPT_COOKIEJAR, $this->getCookieFile());
        curl_setopt($this->_rCurl, CURLOPT_COOKIEFILE, $this->getCookieFile());
        curl_setopt($this->_rCurl, CURLOPT_USERAGENT, static::USER_AGENT);

        if (!empty($this->_sSslPath)) {
            curl_setopt($this->_rCurl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($this->_rCurl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($this->_rCurl, CURLOPT_CAINFO, $this->_sSslPath);
        }

        // Set the Response into an attribute
        $this->_sResponse = curl_exec($this->_rCurl);

        return $this;
    }

    /**
     * Assign values.
     *
     * @param string $sUrl The target URL to send the data.
     * @param array $aParms The request parameters to send.
     * @param string $sType The type of request. Choose only between: 'GET', 'POST', 'PUT' and 'DELETE'.
     *
     * @return self
     */
    private function _set($sUrl, array $aParms, $sType)
    {
        $this->_sUrl = $sUrl;
        $this->_aParams = $aParms;
        $this->_sType = $sType;

        return $this;
    }

    /**
     * Close cURL connection.
     */
    public function __destruct()
    {
        curl_close($this->_rCurl);
    }
}
