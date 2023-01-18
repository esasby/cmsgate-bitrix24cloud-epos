<?php

use esas\cmsgate\epos\BridgeConnectorEposBitrix24;
use esas\cmsgate\epos\controllers\ControllerEposCallback;
use esas\cmsgate\epos\controllers\ControllerEposInvoiceAdd;
use esas\cmsgate\protocol\RequestParamsBitrix24Cloud;
use esas\cmsgate\BridgeConnector;
use esas\cmsgate\epos\controllers\ControllerEposCompletionPage;
use esas\cmsgate\epos\RegistryEposBitrix24Cloud;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\utils\StringUtils;
use esas\cmsgate\utils\Logger as LoggerCms;

require_once((dirname(__FILE__)) . '/src/init.php');

$request = $_SERVER['REDIRECT_URL'];
$logger = LoggerCms::getLogger('index');

if (strpos($request, 'api') !== false) {
    try {
        $logger->info('Got request from Bitrix24: ' . $_REQUEST);
        if (StringUtils::endsWith($request, BridgeConnectorEposBitrix24::PATH_INVOICE_ADD)) {
            BridgeConnector::fromRegistry()->getShopConfigService()->checkAuthAndLoadConfig($_REQUEST);
            BridgeConnector::fromRegistry()->getOrderCacheService()->addSessionOrderCache($_REQUEST);
            $orderWrapper = Registry::getRegistry()->getOrderWrapperForCurrentUser();
            if ($orderWrapper->getExtId() == null || $orderWrapper->getExtId() == '') {
                $controller = new ControllerEposInvoiceAdd();
                $controller->process($orderWrapper);
            }
            $controller = new ControllerEposCompletionPage();
            $completeionPage = $controller->process($orderWrapper);
            $completeionPage->render();
        } elseif (strpos($request, BridgeConnectorEposBitrix24::PATH_INVOICE_VIEW) !== false) {
            $uuid = RequestParamsBitrix24Cloud::getOrderId();
            SessionUtilsBridge::setOrderCacheUUID($uuid);
            $orderWrapper = Registry::getRegistry()->getOrderWrapperForCurrentUser();
            $controller = new ControllerEposCompletionPage();
            $completeionPage = $controller->process($orderWrapper);
            $completeionPage->render();
        } elseif (strpos($request, BridgeConnectorEposBitrix24::PATH_INVOICE_CALLBACK) !== false) {
            $controller = new ControllerEposCallback();
            $controller->process();
        } else {
            http_response_code(404);
            return;
        }
    } catch (Exception $e) {
        $logger->error("Exception", $e);
        $errorPage = RegistryEposBitrix24Cloud::getRegistry()->getCompletionPage(
            Registry::getRegistry()->getOrderWrapperForCurrentUser(),
            null
        );
        $errorPage->render();
    } catch (Throwable $e) {
        $logger->error("Exception", $e);
        $errorPage = RegistryEposBitrix24Cloud::getRegistry()->getCompletionPage(
            Registry::getRegistry()->getOrderWrapperForCurrentUser(),
            null
        );
        $errorPage->render();
    }
} else {
    http_response_code(200);
}

