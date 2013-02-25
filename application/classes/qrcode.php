<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of QRCode
 *
 * @author Pierre-Henry Soria <ph7software@gmail.com>
 * @category RealtAsia API
 * @package Helpers
 * @subpackage QRCode
 * @version 1.1
 * @desc Compatible to vCard 4.0 or higher.
 * @copyright (c) 2012, Pierre-Henry Soria. All Rights Reserved.
 * @license          GNU General Public License.
 */
class QRCode {

    const API_URL = 'http://chart.apis.google.com/chart?chs=';

    private $_sData;

    public static function factory()
    {
        return new QRCode();
    }


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->_sData = 'BEGIN:VCARD' . "\n";
        $this->_sData .= 'VERSION:4.0' . "\n";
        return $this;
    }

    /**
     * The name of the person.
     *
     * @param string $sName
     * @return \QRCode
     */
    public function name($sName)
    {
        $this->_sData .= 'N:' . $sName . "\n";
        return $this;
    }

    /**
     * The full name of the person.
     *
     * @param string $sFullName
     * @return \QRCode
     */
    public function fullName($sFullName)
    {
        $this->_sData .= 'FN:' . $sFullName . "\n";
        return $this;
    }

    public function company($company)
    {
        $this->_sData .= 'ORG:' . $company . "\n";
        return $this;
    }

    /**
     * Delivery address.
     *
     * @param string $sAddress
     * @return \QRCode
     */
    public function address($sAddress)
    {
        $this->_sData .= 'ADR:' . $sAddress . "\n";
        return $this;
    }

    /**
     * Nickname.
     *
     * @param string $sNickname
     * @return \QRCode
     */
    public function nickName($sNickname)
    {
        $this->_sData .= 'NICKNAME:' . $sNickname . "\n";
        return $this;
    }

    /**
     * Email address.
     *
     * @param string $sMail
     * @return \QRCode
     */
    public function email($sMail)
    {
        $this->_sData .= 'EMAIL;TYPE=PREF,INTERNET:' . $sMail . "\n";
        return $this;
    }

    /**
     * Work Phone.
     *
     * @param string $sVal
     * @return \QRCode
     */
    public function workPhone($sVal)
    {
        $this->_sData .= 'TEL;TYPE=WORK:' . $sVal . "\n";
        return $this;
    }

    /**
     * Home Phone.
     *
     * @param string $sVal
     * @return \QRCode
     */
    public function homePhone($sVal)
    {
        $this->_sData .= 'TEL;TYPE=HOME:' . $sVal . "\n";
        return $this;
    }

    /**
     * URL address.
     *
     * @param string $sUrl
     * @return \QRCode
     */
    public function url($sUrl)
    {
        $sUrl = (substr($sUrl, 0, 4) != 'http') ? 'http://' . $sUrl : $sUrl;
        $this->_sData .= 'URL:' . $sUrl . "\n";
        return $this;
    }

    /**
     * SMS code.
     *
     * @param string $sPhone
     * @param string $sText
     * @return \QRCode
     */
    public function sms($sPhone, $sText)
    {
        $this->_sData .= 'SMSTO:' . $sPhone . ':' . $sText . "\n";
        return $this;
    }

    /**
     * Birthday.
     *
     * @param string $sBirthday Date in the format YYYY-MM-DD or ISO 8601
     * @return \QRCode
     */
    public function birthday($sBirthday)
    {
        $this->_sData .= 'BDAY:' . $sBirthday . "\n";
        return $this;
    }

    /**
     * Anniversary.
     *
     * @param string $sBirthDate Date in the format YYYY-MM-DD or ISO 8601
     * @return \QRCode
     */
    public function anniversary($sBirthDate)
    {
        $this->_sData .= 'ANNIVERSARY:' . $sBirthDate . "\n";
        return $this;
    }

    /**
     * Gender.
     *
     * @param string $sSex F = Female. M = Male
     * @return \QRCode
     */
    public function gender($sSex)
    {
        $this->_sData .= 'GENDER:' . $sSex . "\n";
        return $this;
    }

    /**
     * A list of "tags" that can be used to describe the object represented by this vCard.
     *
     * @param string $sCategory
     * @return \QRCode
     */
    public function categories($sCategories)
    {
        $this->_sData .= 'CATEGORIES:' . $sCategories . "\n";
        return $this;
    }

    /**
     * The instant messenger (Instant Messaging and Presence Protocol).
     *
     * @param string $sVal
     * @return \QRCode
     */
    public function impp($sVal)
    {
        $this->_sData .= 'IMPP:' . $sVal . "\n";
        return $this;
    }

    /**
     * Photo (avatar).
     *
     * @param string $sImgUrl URL of the image.
     * @return \QRCode
     * @throws InvalidArgumentException If the image format is invalid.
     */
    public function photo($sImgUrl)
    {
        $bIsImgExt = strtolower(pathinfo($sImgUrl, PATHINFO_EXTENSION)); // Get the file extension.

        if ($bIsImgExt == 'jpeg' || $bIsImgExt == 'jpg' || $bIsImgExt == 'png' || $bIsImgExt == 'gif')
            $sExt = strtoupper($bIsImgExt);
        else
            throw new InvalidArgumentException('Invalid format Image!');

        $this->_sData .= 'PHOTO;VALUE=URL;TYPE=' . $sExt . ':' . $sImgUrl . "\n";
        return $this;
    }

    /**
     * The role, occupation, or business category of the vCard object within an organization.
     *
     * @param string $sRole e.g.: Executive
     * @return \QRCode
     */
    public function role($sRole)
    {
        $this->_sData .= 'ROLE:' . $sRole . "\n";
        return $this;
    }

    /**
     * Specifies the job title, functional position or function of the individual associated with the vCard object within an
     * organization.
     *
     * @param string $sTitle e.g.: Executive
     * @return \QRCode
     */
    public function title($sTitle)
    {
        $this->_sData .= 'TITLE:' . $sTitle . "\n";
        return $this;
    }

    /**
     * The canonical number string for a mobile number for telephony communication with the vCard object.
     *
     * @param string $sMobile e.g.: +6590001234
     * @return \QRCode
     */
    public function mobile($sMobile)
    {
        $this->_sData .= 'TEL;TYPE=cell' . $sMobile . "\n";
        return $this;
    }

    /**
     * The supplemental information or a comment that is associated with the vCard.
     *
     * @param string $sText
     * @return \QRCode
     */
    public function note($sText)
    {
        $this->_sData .= 'NOTE:' . $sText . "\n";
        return $this;
    }

    /**
     * Bookmark.
     *
     * @param string $sTitle
     * @param string $sUrl
     * @return \QRCode
     */
    public function bookmark($sTitle, $sUrl)
    {
        $this->_sData .= 'MEBKM:TITLE:' . $sTitle . ';URL:' . $sUrl . "\n";
        return $this;
    }

    /**
     * Geo location.
     *
     * @param string $sLat Latitude
     * @param string $sLon Longitude
     * @param integer $iHeight Height
     * @return \QRCode
     */
    public function geo($sLat, $sLon, $iHeight)
    {
        $this->_sData .= 'GEO:' . $sLat . ',' . $sLon . ',' . $iHeight . "\n";
        return $this;
    }

    /**
     * The language that the person speaks.
     *
     * @param string $sLang e.g.: en-US
     * @return \QRCode
     */
    public function lang($sLang)
    {
        $this->_sData .= 'LANG:' . $sLang . "\n";
        return $this;
    }

    /**
     * Wifi.
     *
     * @param string $sType
     * @param string $sSsid
     * @param string $sPwd
     * @return \QRCode
     */
    public function wifi($sType, $sSsid, $sPwd)
    {
        $this->_sData .= 'WIFI:T:' . $sType . ';S' . $sSsid . ';' . $sPwd . "\n";
        return $this;
    }

    /**
     * Generate the QR code.
     *
     * @return \QRCode
     */
    public function finish()
    {
        $this->_sData .= 'END:VCARD';
        return $this;
    }

    /**
     * Get the URL of QR Code.
     *
     * @param integer $iSize Default 150
     * @param string $sECLevel Default L
     * @param integer $iMargin Default 1
     * @return string The API URL configure.
     */
    public function get($iSize = 150, $sECLevel = 'L', $iMargin = 1)
    {
        $this->_sData = urlencode($this->_sData);
        return static::API_URL . $iSize . 'x' . $iSize . '&amp;cht=qr&amp;chld=' . $sECLevel . '|' . $iMargin . '&amp;chl=' . $this->_sData;
    }

    /**
     * The HTML code for displaying the QR Code.
     *
     * @return void
     */
    public function display()
    {
        echo '<p class="center"><img src="' . $this->get() . '" alt="QR Code" /></p>';
    }

}
