<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Model\Service;

use Magento\Framework\Serialize\SerializerInterface;
use Laminas\Http\ClientFactory;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Laminas\Http\Request;

class ApiData
{
    /**
     * Authentication token
     * @var string
     */
    private string $authToken = '';

    /**
     * Timestamp when token is valid
     * @var int
     */
    private int $authTokenValidTimestamp = 0;

    /**
     * Token header
     */
    private const API_AUTH_HEADER = 'your_token';


    private const API_AUTH_EXPIRE_KEY = 'expires';


    /**
     * @param ClientFactory $clientFactory
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ClientFactory       $clientFactory,
        private readonly SerializerInterface $serializer,

    )
    {
    }

    /**
     * @param $uri
     * @param array $params
     * @param string $method
     * @param $authRequired
     * @return array
     * @throws LocalizedException
     */
    public function execute($uri, array $params = [], string $method = Request::METHOD_GET, $authRequired = false): array
    {
        $headers = [
            'accept' => 'application/json'
        ];
        if ($authRequired) {
            $headers['Authorization'] = 'Bearer ' . $this->getAuthToken();
        }
        /** @var ApiData $client */
        $client = $this->clientFactory->create();
        $client->setHeaders($headers)
            ->setMethod($method)
            ->setUri($uri)
            ->setOptions([
                'maxredirects' => 0,
                'timeout' => 30,
            ]);

        if ($params) {
            $client->setParameterGet($params);
        }

        try {
            $httpResponse = $client->send();
            $responseJson = $httpResponse->getBody();
            $responseArray = $this->serializer->unserialize($responseJson);
        } catch (\Exception $e) {

            throw new LocalizedException(__('There is an error during cars api request. Please try again.'));
        }

        if (!$httpResponse->isSuccess()) {
            throw new LocalizedException(__(
                'There is an error in cars api response. Please try again.'
            ));
        }
        if ($responseArray === false) {
            throw new LocalizedException(__(
                'There is an error in cars api response format. Please try again.'
            ));
        }

        /** Token updated with each request that has it in headers */
        /** @var bool|\Laminas\Http\Header\HeaderInterface $tokenHeader */
        if (array_key_exists(static::API_AUTH_EXPIRE_KEY, $responseArray) &&
            $tokenHeader = $httpResponse->getHeaders()->get(name: static::API_AUTH_HEADER)
        ) {
            $this->setAuthToken($tokenHeader->getFieldValue());
            $this->setAuthTokenValidTimestamp($responseArray[static::API_AUTH_EXPIRE_KEY]);
        }

        return $responseArray;
    }

    /**
     * Validate existing token
     */
    public function isTokenValid(): bool
    {
        return $this->getAuthTokenValidTimestamp() > time();
    }

    /**
     * @return string
     */
    protected function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @param string $token
     * @return $this
     */
    protected function setAuthToken(string $token): self
    {
        $this->authToken = $token;
        return $this;
    }

    /**
     * @return int
     */
    protected function getAuthTokenValidTimestamp(): int
    {
        return $this->authTokenValidTimestamp;
    }

    /**
     * @param int $tokenTimestamp
     * @return $this
     */
    protected function setAuthTokenValidTimestamp(int $tokenTimestamp): self
    {
        $this->authTokenValidTimestamp = $tokenTimestamp;
        return $this;
    }
}
