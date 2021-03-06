<?php

namespace SSitdikov\ATOL\Tests;

use PHPUnit\Framework\TestCase;
use SSitdikov\ATOL\Exception\ErrorException;
use SSitdikov\ATOL\Exception\ErrorGroupCodeToTokenException;
use SSitdikov\ATOL\Exception\ErrorIncomingBadRequestException;
use SSitdikov\ATOL\Exception\ErrorIncomingExistExternalIdException;
use SSitdikov\ATOL\Exception\ErrorIncomingExpiredTokenException;
use SSitdikov\ATOL\Exception\ErrorIncomingMissingTokenException;
use SSitdikov\ATOL\Exception\ErrorIncomingNotExistTokenException;
use SSitdikov\ATOL\Exception\ErrorIncomingOperationNotSupportException;
use SSitdikov\ATOL\Exception\ErrorIsNullExternalIdException;
use SSitdikov\ATOL\Exception\ErrorUndefinedException;
use SSitdikov\ATOL\Object\Info;
use SSitdikov\ATOL\Object\Item;
use SSitdikov\ATOL\Object\Payment;
use SSitdikov\ATOL\Object\Receipt;
use SSitdikov\ATOL\Object\ReceiptSno;
use SSitdikov\ATOL\Request\OperationRequest;
use SSitdikov\ATOL\Request\RequestInterface;
use SSitdikov\ATOL\Response\ErrorResponse;
use SSitdikov\ATOL\Response\TokenResponse;

class OperationRequestTest extends TestCase
{

    /**
     * @test
     */
    public function newItem()
    {
        $title = 'Title';
        $price = 1200.00;
        $quantity = 3;
        $tax = Item::TAX_NONE;
        $sum = $price * $quantity;
        $taxSum = 0;

        $item = new Item($title, $price, $quantity, $tax);

        $this->assertEquals($title, $item->getName());
        $this->assertEquals($price, $item->getPrice());
        $this->assertEquals($quantity, $item->getQuantity());
        $this->assertEquals($sum, $item->getSum());
        $this->assertEquals(Item::TAX_NONE, $item->getTax());
        $this->assertEquals($taxSum, $item->getTaxSum());
        $this->assertEquals(
                [
                    'name' => $title,
                    'price' => $price,
                    'quantity' => $quantity,
                    'sum' => $sum,
                    'tax' => $tax,
                    'tax_sum' => $taxSum,
                ],
            $item->jsonSerialize()
        );

        $item->setTax(Item::TAX_VAT18);
        $this->assertEquals(round($price * $quantity * 0.18, 2), $item->getTaxSum());
        $item->setTax(Item::TAX_VAT10);
        $this->assertEquals(round($price * $quantity * 0.10, 2), $item->getTaxSum());
        $item->setTax(Item::TAX_VAT110);
        $this->assertEquals(round($price * $quantity * 10 / 110, 2), $item->getTaxSum());
        $item->setTax(Item::TAX_VAT118);
        $this->assertEquals(round($price * $quantity * 18 / 118, 2), $item->getTaxSum());
        $item->setTax($tax);

        return $item;
    }

    /**
     * @test
     */
    public function newPayment()
    {
        $payment = new Payment(Payment::PAYMENT_TYPE_CASH, 3600);

        $this->assertEquals(3600, $payment->getSum());
        $this->assertEquals(Payment::PAYMENT_TYPE_CASH, $payment->getType());
        $this->assertEquals(\json_decode('{"sum":3600, "type": 0}', true), $payment->jsonSerialize());

        return $payment;
    }

    /**
     * @test
     * @depends newItem
     * @depends newPayment
     */
    public function newReceipt(Item $item, Payment $payment)
    {
        $this->assertEquals($item->getSum(), $payment->getSum());

        $email = 'test@test.ru';
        $phone = '1234567890';

        $receipt = new Receipt();
        $receipt->setSno(ReceiptSno::RECEIPT_SNO_USN_INCOME)
            ->setEmail($email)
            ->setPhone($phone)
            ->setPayments([$payment])
            ->setItems([$item]);

        $this->assertEquals($email, $receipt->getEmail());
        $this->assertEquals($phone, $receipt->getPhone());
        $this->assertEquals(
            ReceiptSno::RECEIPT_SNO_USN_INCOME,
            $receipt->getSno()
        );
        $this->assertEquals($item->getSum(), $receipt->getTotal());

        $this->assertEquals(
            [
                'attributes' => [
                    'sno' => ReceiptSno::RECEIPT_SNO_USN_INCOME,
                    'email' => $email,
                    'phone' => $phone,
                ],
                'items' => [$item],
                'total' => $payment->getSum(),
                'payments' => [$payment],
            ],
            $receipt->jsonSerialize()
        );

        $receipt->addItem($item);
        $this->assertEquals([$item, $item], $receipt->getItems());

        $receipt->addPayment($payment);
        $this->assertEquals([$payment, $payment], $receipt->getPayments());

        return $receipt;
    }

    /**
     * @test
     */
    public function newInfo()
    {
        $inn = '1111111111';
        $paymentAddress = 'test.mystore.dev';
        $callbackUrl = 'http://test.mystore.dev/callback/api/url';

        $info = new Info($inn, $paymentAddress, $callbackUrl);
        $this->assertEquals($inn, $info->getInn());
        $this->assertEquals($paymentAddress, $info->getPaymentAddress());
        $this->assertEquals($callbackUrl, $info->getCallbackUrl());
        $this->assertJson(
            \json_encode([
                'callbackUrl' => $callbackUrl,
                'inn' => $inn,
                'payment_address' => $paymentAddress,
            ]),
            \json_encode($info->jsonSerialize())
        );

        return $info;
    }

    /**
     * @test
     * @depends newReceipt
     * @depends newInfo
     */
    public function doOperation(Receipt $receipt, Info $info)
    {
        $groupId = 'test';
        $uuid = random_int(1, 100);
        $operationType = OperationRequest::OPERATION_SELL;
        $token = new TokenResponse(
            json_decode('{"code":0, "text":null, "token": "'.md5($uuid).'"}')
        );
        $operation = new OperationRequest(
            $groupId,
            $operationType,
            $uuid,
            $receipt,
            $info,
            $token
        );
        $this->assertEquals(RequestInterface::POST, $operation->getMethod());
        $this->assertEquals(
            $groupId.'/'.$operationType.'?tokenid='.$token->getToken(),
            $operation->getUrl()
        );
        $this->assertEquals(
            [
                'json' => [
                    'external_id' => $uuid,
                    'receipt' => $receipt,
                    'service' => $info,
                    'timestamp' => date('d.m.Y H:i:s'),
                ],
            ],
            $operation->getParams()
        );

        return $operation;
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getSuccessResponse(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'wait';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error":null, "status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->assertEquals($uuid, $request->getResponse($response)->getUuid());
        $this->assertEquals(
            $status,
            $request->getResponse($response)->getStatus()
        );
        $this->assertEquals(
            $timestamp,
            $request->getResponse($response)->getTimestamp()
        );
        $this->isNull();
        $request->getResponse($response)->getError();
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getError(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"1", "text":"", "type":""} , "status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(ErrorException::class);
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getErrorIncomingBadRequest(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"2", "text":"", "type":""} , "status":"'.$status.
            '", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(ErrorIncomingBadRequestException::class);
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getErrorIncomingOperationNotSupport(
        OperationRequest $request
    ) {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"3", "text":"", "type":""} , "status":"'.$status.
            '", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(
            ErrorIncomingOperationNotSupportException::class
        );
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getErrorIncomingMissingToken(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"4", "text":"", "type":""}' .
            ', "status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(ErrorIncomingMissingTokenException::class);
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getErrorIncomingNotExistToken(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"5", "text":"", "type":""} ,' .
            '"status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(ErrorIncomingNotExistTokenException::class);
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getErrorIncomingExpiredToken(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"6", "text":"", "type":""} ,' .
            '"status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(ErrorIncomingExpiredTokenException::class);
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getErrorIncomingExistExternalId(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"10", "text":"", "type":""} ,' .
            '"status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(ErrorIncomingExistExternalIdException::class);
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getErrorIncomingExistExternalIdWithoutUUID(OperationRequest $request)
    {
        $uuid = 0;
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"10", "text":"", "type":""} ,' .
            '"status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(ErrorIncomingExistExternalIdException::class);
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getErrorGroupCodeToToken(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"22", "text":"", "type":""} ,' .
            '"status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(ErrorGroupCodeToTokenException::class);
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getErrorIsNullExternalId(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"23", "text":"", "type":""} ,' .
            '"status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(ErrorIsNullExternalIdException::class);
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getException(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"400", "text":"", "type":""} ,' .
            '"status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(\Exception::class);
        $request->getResponse($response);
    }

    /**
     * @test
     * @depends doOperation
     */
    public function getErrorUndefined(OperationRequest $request)
    {
        $uuid = md5(time());
        $timestamp = date('Y-m-d H:i:s');
        $status = 'fail';

        $response = \json_decode(
            '{"uuid":"'.$uuid.'", "error": {"code":"26", "text":"", "type":""} ,' .
            '"status":"'.$status.'", "timestamp":"'.$timestamp.'"}'
        );

        $this->expectException(ErrorUndefinedException::class);
        $request->getResponse($response);
    }

    /**
     * @test
     */
    public function getErrorResponse()
    {
        $code = 400;
        $type = 'system';
        $text = 'test text';

        $errorResponse = new ErrorResponse(
            \json_decode(
                '{"code":'.$code.', "text":"'.$text.'", "type":"'.$type.'"}'
            )
        );

        $this->assertEquals($code, $errorResponse->getCode());
        $this->assertEquals($text, $errorResponse->getText());
        $this->assertEquals($type, $errorResponse->getType());
    }
}
