<?php
class AkamaiHelper
{

    private static $_device=false;

    private static function _GET_DEVICE(){

        return AkamaiHelper::$_device;
    }

    private static function _SET_DEVICE($device){

        AkamaiHelper::$_device = $device;
    }

    public static function GET_DEVICE()
    {
        if(AkamaiHelper::_GET_DEVICE()){
            return AkamaiHelper::_GET_DEVICE();
        }

        $allheaders=getallheaders();
        $device = 'desktop';
        $akamai = $allheaders['x-akamai-device-characteristics'];

        if(!$akamai)
            $akamai = $allheaders['X-Akamai-Device-Characteristics'];

        if (!$akamai) {

            if (AkamaiHelper::IS_MOBILE()) {
                $device = 'mobile';
            } elseif (AkamaiHelper::IS_TABLET()) {
                $device = 'tablet';
            } else {
                $device = 'desktop';
            }

        } else {

            $akamaiTags = [];
            $pairs = \explode(';', $akamai);
            foreach ($pairs as $pair) {
                $pair = \explode('=', $pair);
                $akamaiTags[$pair[0]] = (('true' === \strtolower($pair[1]) || 1 === (int) $pair[1]) ? true : false);
            }

            if (!$akamaiTags['is_mobile'] && !$akamaiTags['is_tablet']) {
                //desktop lo servimos como cadena vacía
                $device = 'desktop';
            } elseif ($akamaiTags['is_tablet']) {
                $device = 'tablet';
            } elseif ($akamaiTags['is_mobile']) {
                $device = 'mobile';
            }
        }

        AkamaiHelper::_SET_DEVICE($device);

        return $device;
    }


    public static function IS_MOBILE()
    {
        $apple_phone = 'iPhone';
        $android_phone = '(?=.*\bAndroid\b)(?=.*\bMobile\b)';
        $amazon_phone = '(?=.*\bAndroid\b)(?=.*\bSD4930UR\b)';
        $windows_phone = 'IEMobile';
        $ua = getallheaders()['User-Agent'];

        return \mb_eregi($apple_phone, $ua) || \mb_eregi($amazon_phone, $ua) ||
            \mb_eregi($android_phone, $ua) || \mb_eregi($windows_phone, $ua);
    }

    public static function IS_TABLET()
    {
        $apple_phone = 'iPhone';
        $android_phone = '(?=.*\bAndroid\b)(?=.*\bMobile\b)';
        $amazon_phone = '(?=.*\bAndroid\b)(?=.*\bSD4930UR\b)';
        $apple_tablet = 'iPad';
        $android_tablet = 'Android';
        $amazon_tablet = '(?=.*\bAndroid\b)(?=.*\b(?:KFOT|KFTT|KFJWI|KFJWA|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|KFARWI|KFASWI|KFSAWI|KFSAWA)\b)';
        $windows_tablet = '(?=.*\bWindows\b)(?=.*\bARM\b)';

        $ua =  getallheaders()['User-Agent'];

        return (!\mb_eregi($apple_phone, $ua) && \mb_eregi($apple_tablet, $ua)) ||
            (!\mb_eregi($amazon_phone, $ua) && \mb_eregi($amazon_tablet, $ua)) ||
            (!\mb_eregi($amazon_phone, $ua) && !\mb_eregi($android_phone, $ua) &&
                (\mb_eregi($amazon_tablet, $ua) || \mb_eregi($android_tablet, $ua))) ||
            \mb_eregi($windows_tablet, $ua);
    }

}
?>