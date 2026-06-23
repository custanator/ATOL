<?php

declare(strict_types=1);

namespace custanator\ATOL\Client;

use custanator\ATOL\Request\CorrectionRequest;
use custanator\ATOL\Request\OperationRequest;
use custanator\ATOL\Request\ReportRequest;
use custanator\ATOL\Request\RequestInterface;
use custanator\ATOL\Request\TokenRequest;
use custanator\ATOL\Response\OperationResponse;
use custanator\ATOL\Response\ReportResponse;
use custanator\ATOL\Response\TokenResponse;

/**
 * Interface IClient.
 *
 * @package custanator\ATOL\Client
 */
interface IClient
{
    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    public function makeRequest(RequestInterface $request): string;


    /**
     * @param TokenRequest $request
     *
     * @return TokenResponse
     */
    public function getToken(TokenRequest $request): TokenResponse;


    /**
     * @param OperationRequest $request
     *
     * @return OperationResponse
     */
    public function doOperation(OperationRequest $request): OperationResponse;


    /**
     * @param CorrectionRequest $request
     *
     * @return OperationResponse
     */
    public function doCorrection(CorrectionRequest $request): OperationResponse;


    /**
     * @param ReportRequest $request
     *
     * @return ReportResponse
     */
    public function getReport(ReportRequest $request): ReportResponse;
}
