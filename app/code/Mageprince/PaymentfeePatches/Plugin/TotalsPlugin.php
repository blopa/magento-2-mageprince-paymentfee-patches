<?php
namespace Mageprince\PaymentfeePatches\Plugin;

use Magento\Quote\Api\Data\CartInterface;
use PayPal\PPCP\Model\Request\Data\Helper\Totals as TotalsHelper;

class TotalsPlugin
{
    /**
     * After plugin for Totals::getAmountData().
     *
     * This adds a 'handling' key with the Payment Fee (if any) into the breakdown.
     *
     * @param TotalsHelper $subject
     * @param array $result
     * @param CartInterface $quote
     * @return array
     */
    public function afterGetAmountData(TotalsHelper $subject, array $result, CartInterface $quote): array
    {
        // Retrieve the payment fee from the quote (set by Mageprince_Paymentfee)
        $paymentFee = (float) $quote->getPaymentFee();

        if ($paymentFee > 0.0001) {
            $result['breakdown']['handling'] = [
                'currency_code' => $quote->getQuoteCurrencyCode(),
                'value'         => round($paymentFee, 2)
            ];
        }

        return $result;
    }
}
