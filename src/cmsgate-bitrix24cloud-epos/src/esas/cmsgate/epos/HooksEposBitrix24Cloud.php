<?php
namespace esas\cmsgate\epos;

use esas\cmsgate\BridgeConnector;
use esas\cmsgate\CmsConnectorBitrix24Cloud;
use esas\cmsgate\epos\protocol\EposCallbackRq;
use esas\cmsgate\epos\protocol\EposInvoiceAddRs;
use esas\cmsgate\epos\protocol\EposInvoiceGetRs;
use esas\cmsgate\OrderStatus;
use esas\cmsgate\OrderStatusBridge;
use esas\cmsgate\Registry;
use esas\cmsgate\wrappers\OrderWrapper;

class HooksEposBitrix24Cloud extends HooksEpos
{
    public function onCallbackRqRead(EposCallbackRq $rq)
    {
        parent::onCallbackRqRead($rq);
        BridgeConnector::fromRegistry()->getOrderCacheService()->loadSessionOrderCacheByExtId($rq->getInvoiceId());
    }

    public function onInvoiceAddSuccess(OrderWrapper $orderWrapper, EposInvoiceAddRs $resp) {
        $orderWrapper->saveExtId($resp->getInvoiceId());
        $this->updateStatues($orderWrapper, OrderStatusBridge::pending(), Registry::getRegistry()->getConfigWrapper()->getOrderStatusPending(), $resp->getInvoiceId());
    }

    public function onInvoiceAddFailed(OrderWrapper $orderWrapper, EposInvoiceAddRs $resp) {
        $this->updateStatues($orderWrapper, OrderStatusBridge::failed(), Registry::getRegistry()->getConfigWrapper()->getOrderStatusFailed());
    }


    private function updateStatues(OrderWrapper $orderWrapper, OrderStatus $newBridgeStatus, $newCmsStatus, $extId = null) {
        $setPayed = $newBridgeStatus->getOrderStatus() == OrderStatusBridge::payed()->getOrderStatus();
        $orderWrapper->updateStatusWithLogging($newBridgeStatus);
        CmsConnectorBitrix24Cloud::fromRegistry()->getBitrix24Api(true)->salePayment()->updateStatus(
            $orderWrapper->getPaymentId(),
            $newCmsStatus,
            $setPayed);
        CmsConnectorBitrix24Cloud::fromRegistry()->getBitrix24Api(true)->saleOrder()->updateStatus(
            $orderWrapper->getOrderId(),
            $newCmsStatus,
            $setPayed);
        if (!empty($extId))
            CmsConnectorBitrix24Cloud::fromRegistry()->getBitrix24Api(true)->salePayment()->saveExtId(
                $orderWrapper->getPaymentId(),
                $orderWrapper->getExtId()
            );
    }

    public function onCallbackStatusPending(OrderWrapper $orderWrapper, EposInvoiceGetRs $resp) {

    }

    public function onCallbackStatusPayed(OrderWrapper $orderWrapper, EposInvoiceGetRs $resp) {
        $this->updateStatues($orderWrapper, OrderStatusBridge::payed(), Registry::getRegistry()->getConfigWrapper()->getOrderStatusPayed());
    }

    public function onCallbackStatusCanceled(OrderWrapper $orderWrapper, EposInvoiceGetRs $resp) {
        $this->updateStatues($orderWrapper, OrderStatusBridge::canceled(), Registry::getRegistry()->getConfigWrapper()->getOrderStatusCanceled());
    }
}