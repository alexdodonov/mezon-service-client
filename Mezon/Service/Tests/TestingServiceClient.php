<?php
namespace Mezon\Service\Tests;

use Mezon\Service\ServiceClient;

/**
 * Class ServiceClientUnitTests
 *
 * @package ServiceClient
 * @subpackage ServiceClientUnitTests
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/20)
 * @copyright Copyright (c) 2019, aeon.org
 */
class TestingServiceClient extends ServiceClient
{

    /**
     * Method returns concrete url by it's locator
     *
     * @param string $urlLocator
     *            url locator
     * @return string concrete URL
     */
    public function getRequestUriPublic(string $urlLocator): string
    {
        return $this->getRequestUrl($urlLocator);
    }

    /**
     * Result of the sendRequest method
     *
     * @var array
     */
    public $sendRequestResult = [
        'body',
        1
    ];

    /**
     *
     * @param string $url
     *            URL
     * @param array $headers
     *            Headers
     * @param string $method
     *            Request HTTP Method
     * @param array $data
     *            Request data
     * @return array Response body and HTTP code
     * @codeCoverageIgnore
     */
    protected function sendRequest(string $url, array $headers, string $method, array $data = []): array
    {
        return $this->sendRequestResult;
    }
}
