<?php

declare(strict_types=1);

namespace custanator\ATOL\Client;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use custanator\ATOL\Request\CorrectionRequest;
use custanator\ATOL\Request\OperationRequest;
use custanator\ATOL\Request\ReportRequest;
use custanator\ATOL\Request\RequestInterface;
use custanator\ATOL\Request\TokenRequest;
use custanator\ATOL\Response\OperationResponse;
use custanator\ATOL\Response\ReportResponse;
use custanator\ATOL\Response\TokenResponse;
use function json_decode;
use function json_encode;
use function json_last_error;

/**
 * Class ApiClient.
 *
 * @package custanator\ATOL\Client
 *
 * @author  Salavat Sitdikov <sitsalavat@gmail.com>
 */
class ApiClient implements IClient
{
    private $http;

    /**
     * @var string
     */
    private $version = 'v5';


    /**
     * ApiClient constructor.
     *
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->http = $client;
        if (null === $client) {
            $this->http = new Client(
                [
                    'base_uri' => 'https://online.atol.ru/possystem/' . $this->getVersion() . '/',
                ]
            );
        }
    }


    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }


    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }


    /**
     * @param TokenRequest $request
     *
     * @throws Exception
     * @return TokenResponse
     */
    public function getToken(TokenRequest $request): TokenResponse
    {
        return $request->getResponse(
            json_decode(
                $this->makeRequest(
                    $request
                )
            )
        );
    }


    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    public function makeRequest(RequestInterface $request): string
    {
        try {
            $response = $this->http->request(
                $request->getMethod(),
                $request->getUrl(),
                $request->getParams()
            );

            $message = $response->getBody()->getContents();
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
            if ($response) {
                $message = $response->getBody()->getContents();
                json_decode($message);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $message = json_encode(
                        [
                            'error' => [
                                'code' => $exception->getCode(),
                                'text' => $exception->getMessage(),
                            ],
                        ]
                    );
                }
            } else {
                $message = json_encode(
                    [
                        'error' => [
                            'code' => $exception->getCode(),
                            'text' => $exception->getMessage(),
                        ],
                    ]
                );
            }
        } catch (GuzzleException $exception) {
            $message = json_encode(
                [
                    'error' => [
                        'code' => $exception->getCode(),
                        'text' => $exception->getMessage(),
                    ],
                ]
            );
        }

        return $message;
    }


    /**
     * @param OperationRequest $request
     *
     * @throws Exception
     * @return OperationResponse
     *
     */
    public function doOperation(OperationRequest $request): OperationResponse
    {
        return $request->getResponse(
            json_decode(
                $this->makeRequest(
                    $request
                )
            )
        );
    }


    public function doCorrection(CorrectionRequest $request): OperationResponse
    {
        return $request->getResponse(
            json_decode(
                $this->makeRequest(
                    $request
                )
            )
        );
    }


    /**
     * @param ReportRequest $request
     *
     * @throws Exception
     * @return ReportResponse
     */
    public function getReport(ReportRequest $request): ReportResponse
    {
        return $request->getResponse(
            json_decode(
                $this->makeRequest(
                    $request
                )
            )
        );
    }

}
