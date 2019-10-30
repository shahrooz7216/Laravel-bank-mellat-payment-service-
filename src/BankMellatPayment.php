<?php
/**
 * Created by PhpStorm.
 * User: Hasan Shafei [ www.netparadis.com ]

 */

namespace NetParadis\BankMellatPaymentService;

use SoapClient;

/**
 * Class BankMellatPayment
 * @author Hasan Shafei [ www.netparadis.com ] 
 * @see http://www.behpardakht.com/
 *
 */
class BankMellatPayment
{
    protected $soapClient;
    protected $wsdl;
    public $config;
    public $callBackURL;

    public function __construct()
    {
        $this->config = Config('BankMellatPayment');
        $this->callBackURL = $this->config['callBackURL'];
    }

    /**
     * Payment Request
     * @author Hasan Shafei [ www.netparadis.com ] 
     * @param $amount - IRR
     * @param $orderId - INT
     * @param string $additionalData
     * @param int $payerId
     * @return array
     * @throws \Exception
     */
    public function paymentRequest($amount, $orderId, $additionalData = '', $payerId = 0)
    {
        $this->soapClient = new SoapClient(Config('BankMellatPayment.wsdl'));

        if($amount && $amount > 100 && $orderId ) {
            $parameters = [
                'terminalId' => $this->config['terminalId'],
                'userName' => $this->config['userName'],
                'userPassword' => $this->config['userPassword'],
                'orderId' => $orderId,
                'amount' => $amount,
                'localDate' => date("Ymd"),
                'localTime' => date("His"),
                'additionalData' => $additionalData,
                'callBackUrl' => $this->callBackURL,
                'payerId' => $payerId
            ];

            try {

                // Call the SOAP method
                $result = $this->soapClient->bpPayRequest($parameters);
                // Display the result
                $res = explode(',', $result->return);
                if ($res[0] == "0") {
                    return [
                        'result' => true,
                        'res_code' => $res[0],
                        'ref_id' => $res[1]
                    ];
                } else {
                    return [
                        'result' => false,
                        'res_code' => $res[0],
                        'ref_id' => isset($res[1]) ? $res[1] : null
                    ];
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }

    /**
     * Verify Payment
     * @author Hasan Shafei [ www.netparadis.com ] 
     * @param $orderId
     * @param $saleOrderId
     * @param $saleReferenceId
     * @return mixed - false for failed
     */
    public function verifyPayment($orderId, $saleOrderId, $saleReferenceId)
    {
        $this->soapClient = new SoapClient(Config('BankMellatPayment.wsdl'));

        if($orderId && $saleOrderId && $saleReferenceId) {

            $parameters = [
                'terminalId' => $this->config['terminalId'],
                'userName' => $this->config['userName'],
                'userPassword' => $this->config['userPassword'],
                'orderId' => $orderId,
                'saleOrderId' => $saleOrderId,
                'saleReferenceId' => $saleReferenceId,
            ];

            try {

                // Call the SOAP method
                return $this->soapClient->bpVerifyRequest($parameters);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else
            return false;
    }
}