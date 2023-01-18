<?php
namespace esas\cmsgate\epos;

use esas\cmsgate\protocol\RequestParamsBitrix24Cloud;
use esas\cmsgate\BridgeConnector;
use esas\cmsgate\CmsConnectorBitrix24Cloud;
use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\epos\view\client\CompletionPageEpos;
use esas\cmsgate\epos\view\client\CompletionPanelEposBitrix24Cloud;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\utils\URLUtils;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ConfigFormBitrix24Cloud;
use Exception;

class RegistryEposBitrix24Cloud extends RegistryEpos
{
    public function __construct()
    {
        $config = new BridgeConfigEposBitrix24Cloud();
        $this->cmsConnector = new CmsConnectorBitrix24Cloud($config);
        $this->paysystemConnector = new PaysystemConnectorEpos();
        $this->registerService(BridgeConnector::BRIDGE_CONNECTOR_SERVICE_NAME, new BridgeConnectorEposBitrix24($config));
    }

    /**
     * Переопределение для упрощения типизации
     * @return $this
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    /**
     * @throws \Exception
     */
    public function createConfigForm()
    {
        $managedFields = $this->getManagedFieldsFactory()->getManagedFieldsExcept(AdminViewFields::CONFIG_FORM_COMMON, [
            ConfigFieldsEpos::paymentMethodName(),
            ConfigFieldsEpos::paymentMethodNameWebpay(),
            ConfigFieldsEpos::paymentMethodDetails(),
            ConfigFieldsEpos::paymentMethodDetailsWebpay(),
            ConfigFieldsEpos::useOrderNumber(),
            ConfigFieldsEpos::shopName()]);
        $configForm = new ConfigFormBitrix24Cloud(
            AdminViewFields::CONFIG_FORM_COMMON,
            $managedFields);
        return $configForm;
    }


    function getUrlAlfaclick($orderWrapper)
    {
        return "";
    }

    function getUrlWebpay($orderWrapper)
    {
        $currentURL = URLUtils::getCurrentURLNoParams();
        $currentURL = str_replace(BridgeConnectorEposBitrix24::PATH_INVOICE_ADD, BridgeConnectorEposBitrix24::PATH_INVOICE_VIEW, $currentURL);
        if (strpos($currentURL, BridgeConnectorEposBitrix24::PATH_INVOICE_VIEW) !== false) {
            return $currentURL
                . '?' . RequestParamsBitrix24Cloud::ORDER_ID . '=' . SessionUtilsBridge::getOrderCacheUUID();
        }
        else
            throw new CMSGateException('Incorrect URL generation');
    }

    public function createModuleDescriptor()
    {
        return new ModuleDescriptor(
            "bitrix24cloud-epos",
            new VersionDescriptor("1.17.0", "2023-01-13"),
            "Bitrix24 Cloud Epos",
            "https://github.com/esasby/cmsgate-bitrix24cloud-epos/src/master/",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в EPOS"
        );
    }

    public function getCompletionPanel($orderWrapper)
    {
        return new CompletionPanelEposBitrix24Cloud($orderWrapper);
    }

    /**
     * @param $orderWrapper
     * @param $completionPanel
     * @return CompletionPageEpos
     */
    public function getCompletionPage($orderWrapper, $completionPanel)
    {
        return new CompletionPageEpos($orderWrapper, $completionPanel);
    }

    public function createHooks()
    {
        return new HooksEposBitrix24Cloud();
    }
}