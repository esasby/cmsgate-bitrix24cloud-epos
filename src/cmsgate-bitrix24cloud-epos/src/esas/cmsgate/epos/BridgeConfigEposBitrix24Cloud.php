<?php


namespace esas\cmsgate\epos;


use esas\cmsgate\BridgeConfigBitrix24Cloud;
use esas\cmsgate\BridgeConfigPDO;

class BridgeConfigEposBitrix24Cloud implements BridgeConfigBitrix24Cloud, BridgeConfigPDO
{
    public function getAppId() {
        return 'BITRIX24_APP_ID';
    }

    public function getAppSecret() {
        return 'BITRIX24_APP_SECRET';
    }

    public function isDebugMode() {
        return false;
    }

    public function getPDO_DSN() {
        return "mysql:host=127.0.0.1;dbname=cmsgate;charset=utf8";
    }

    public function getPDOUsername() {
        return 'username';
    }

    public function getPDOPassword() {
        return 'password';
    }

    public function getBridgeHost() {
        return 'https://cmsgate-test.esas.by/cmsgate-bitrix24cloud-epos';
    }
}