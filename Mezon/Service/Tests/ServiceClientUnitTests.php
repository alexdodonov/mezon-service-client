<?php
namespace Mezon\Service\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Service\ServiceClient;
use Mezon\DnsClient\DnsClient;

/**
 * Basic tests for service client
 *
 * @author Dodonov A.
 * @group baseTests
 * @codeCoverageIgnore
 */
class ServiceClientUnitTests extends TestCase
{

    /**
     * Client class name
     */
    protected $clientClassName = ServiceClient::class;

    /**
     * Common setup for all tests
     */
    public function setUp(): void
    {
        DnsClient::clear();
        DnsClient::setService('existing-service', 'https://existing-service.com');
    }

    /**
     * Method creates mock for the service client
     *
     * @param array $methods
     *            mocking methods
     * @return object Mock
     */
    protected function getServiceClientRawMock(
        array $methods = [
            'sendPostRequest',
            'sendGetRequest',
            'sendPutRequest',
            'sendDeleteRequest'
        ]): object
    {
        return $this->getMockBuilder($this->clientClassName)
            ->setMethods($methods)
            ->setConstructorArgs([
            'http://some-service-url'
        ])
            ->getMock();
    }

    /**
     * Method creates mock with setup
     *
     * @param string $dataFile
     *            File name with testing data
     * @return object Mock object
     */
    protected function getServiceClientMock(string $dataFile = 'login-with-invalid-session-id'): object
    {
        $mock = $this->getServiceClientRawMock([
            'sendRequest'
        ]);

        $mock->method('sendRequest')->will(
            $this->returnValue(json_decode(file_get_contents(__DIR__ . '/Conf/' . $dataFile . '.json'), true)));

        return $mock;
    }

    /**
     * Testing construction with login and password
     */
    public function testConstructWithLogin(): void
    {
        // setup
        $mock = $this->getServiceClientMock('construct-with-login');

        // test body
        $mock->__construct('http://example.com/', 'login', 'password');

        // assertions
        $this->assertEquals('login', $mock->getStoredLogin(), 'Login was not set');
        $this->assertEquals('session id', $mock->getToken(), 'SessionId was not set');
    }

    /**
     * Testing constructor
     */
    public function testSetHeader(): void
    {
        // setup
        $client = new $this->clientClassName('http://example.com/');

        // test body and assertions
        $this->assertEquals('', $client->getService(), 'Field was init but it must not');
    }

    /**
     * Checking exception throwing if the service was not found
     */
    public function testNoServiceFound(): void
    {
        $this->expectException(\Exception::class);

        new $this->clientClassName('auth');
    }

    /**
     * Testing that service was found.
     */
    public function testServiceFound(): void
    {
        $client = new $this->clientClassName('existing-service');

        $this->assertEquals('existing-service', $client->getService(), 'Field was init but it must not');
    }

    /**
     * Data provider for the test testSendRequest
     *
     * @return array test data
     */
    public function sendRequestDataProvider(): array
    {
        return [
            [
                'sendGetRequest'
            ],
            [
                'sendPostRequest'
            ],
            [
                'sendPutRequest'
            ],
            [
                'sendDeleteRequest'
            ]
        ];
    }

    /**
     * Testing send[Post|Get|Put|Delete]Request
     *
     * @param string $methodName
     *            testing method name
     * @dataProvider sendRequestDataProvider
     */
    public function testSendRequest(string $methodName): void
    {
        $mock = $this->getServiceClientMock('test-request');

        $result = $mock->$methodName('http://ya.ru', []);

        $this->assertEquals(1, $result->result);
    }

    /**
     * Testing setToken method
     */
    public function testSetToken(): void
    {
        // setup
        $mock = $this->getServiceClientRawMock(); // we need this function, as we need mock without any extra setup

        // test body
        $mock->setToken('token', 'login');

        // assertions
        $this->assertEquals('token', $mock->getToken(), 'SessionId was not set');
        $this->assertEquals('login', $mock->getStoredLogin(), 'Login was not set');
    }

    /**
     * Testing getToken method
     */
    public function testGetToken(): void
    {
        // setup
        $mock = $this->getServiceClientRawMock(); // we need this function, as we need mock without any extra setup

        // test body
        $sessionId = $mock->getToken();

        // assertions
        $this->assertEquals('', $sessionId, 'Invalid session id');
    }

    /**
     * Testing setToken method
     */
    public function testSetTokenException(): void
    {
        // setup
        $mock = $this->getServiceClientRawMock(); // we need this function, as we need mock without any extra setup

        // test body and assertions
        $this->expectException(\Exception::class);
        $mock->setToken('');
    }

    /**
     * Testing getSelfId method
     */
    public function testGetSelfId(): void
    {
        // setup
        $mock = $this->getServiceClientMock('self-id');

        // test body
        $selfId = $mock->getSelfId();

        // assertions
        $this->assertEquals('123', $selfId, 'Invalid self id');
    }

    /**
     * Testing getSelfLogin method
     */
    public function testGetSelfLogin(): void
    {
        // setup
        $mock = $this->getServiceClientMock('self-login');

        // test body
        $selfLogin = $mock->getSelfLogin();

        // assertions
        $this->assertEquals('admin', $selfLogin, 'Invalid self login');
    }

    /**
     * Testing loginAs method
     */
    public function testLoginAsWithInvalidSessionId(): void
    {
        // setup
        $mock = $this->getServiceClientMock();

        // test body and assertions
        $this->expectException(\Exception::class);

        $mock->loginAs('registered-user', 'login');
    }

    /**
     * Testing loginAs method
     */
    public function testLoginAsWithInvalidSessionId2(): void
    {
        // setup
        $mock = $this->getServiceClientMock();

        // test body
        $mock->loginAs('registered', 'id');

        // assertions
        $this->assertEquals('', $mock->getStoredLogin());
    }

    /**
     * Testing loginAs method
     */
    public function testLoginAs(): void
    {
        // setup
        $mock = $this->getServiceClientMock('login-as');

        // test body
        $mock->loginAs('registered', 'login');

        // assertions
        $this->assertEquals('session-id', $mock->getToken(), 'Invalid self login');
    }

    /**
     * Testing construction with login and password and invalid session_id
     */
    public function testConstructWithLoginAndInvalidSessionId(): void
    {
        // setup
        $mock = $this->getServiceClientMock();

        // test body and assertions
        $this->expectException(\Exception::class);
        $mock->__construct('http://example.com/', 'login', 'password');
    }

    /**
     * Testing setting and getting rewrite mode
     */
    public function testRewriteMode(): void
    {
        // setup
        $mock = $this->getServiceClientMock();

        // test body and assertions
        $mock->setRewriteMode(true);
        $this->assertTrue($mock->getRewriteMode());

        $mock->setRewriteMode(false);
        $this->assertFalse($mock->getRewriteMode());
    }

    /**
     * Testing authentication headers
     */
    public function testAuthenticationHeaders(): void
    {
        // setup and assertions
        $client = $this->getServiceClientRawMock([
            'sendRequest'
        ]);
        $client->method('sendRequest')
            ->with(
            $this->callback(function () {
                return true;
            }),
            $this->callback(
                function ($headers) {
                    $this->assertContains('Authentication: Basic some-token', $headers);
                    $this->assertContains('Cgi-Authorization: Basic some-token', $headers);
                    return true;
                }))
            ->willReturn([
            "{\"session_id\":\"some-password\"}",
            200
        ]);
        $client->setToken('some-token');

        // test body
        $client->connect('some-login', 'some-password');
    }

    /**
     * Testing method
     */
    public function testGetRequestUrlException(): void
    {
        // setup and assertions
        $this->expectException(\Exception::class);
        $client = new TestingServiceClient('https://some-service');

        // test body
        $client->getRequestUriPublic('unexistingUri');
    }

    /**
     * Testing exception throwing if error response was got
     */
    public function testGetReuqetsUrlWithHandlingError(): void
    {
        // setup and assertions
        $this->expectException(\Exception::class);
        $client = new TestingServiceClient('https://some-service');
        $client->sendRequestResult = [
            '{"message":"", "code": 1}',
            200
        ];

        // test body
        $client->sendGetRequest('some endpoint');
    }

    /**
     * Mtrhod tests case when sendRequest method have returned invalid json
     */
    public function testInvalidJsonReturnedFromSendRequest(): void
    {
        // setup and assertions
        $this->expectException(\Mezon\Rest\Exception::class);
        $client = new TestingServiceClient('https://some-service');
        $client->sendRequestResult = [
            'some crap',
            200
        ];

        // test body
        $client->sendGetRequest('some endpoint');
    }
}
