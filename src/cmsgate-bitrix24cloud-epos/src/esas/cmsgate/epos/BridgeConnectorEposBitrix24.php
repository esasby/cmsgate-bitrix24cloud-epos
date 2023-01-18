<?php
namespace esas\cmsgate\epos;

use esas\cmsgate\BridgeConnectorBitrix24;
use esas\cmsgate\security\CryptServiceImpl;
use Exception;
use PDO;

class BridgeConnectorEposBitrix24 extends BridgeConnectorBitrix24
{
    const PATH_INVOICE_ADD = '/api/invoice/add';
    const PATH_INVOICE_VIEW = '/api/invoice/view';
    const PATH_INVOICE_CALLBACK = '/api/invoice/callback';

    /**
     * @var BridgeConfigEposBitrix24Cloud
     */
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getPDO()
    {
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO(
            $this->config->getPDO_DSN(),
            $this->config->getPDOUsername(),
            $this->config->getPDOPassword(),
            $opt);
    }


    public function isSandbox()
    {
        throw new Exception('Not implemented. Bitrix24Cloud bridge is working in mixed mode');
    }

    protected function createCryptService()
    {
        return new CryptServiceImpl('/opt/cmsgate/storage');
    }

    public function getHandlerActionUrl()
    {
        return $this->config->getBridgeHost() . self::PATH_INVOICE_ADD;
    }
}