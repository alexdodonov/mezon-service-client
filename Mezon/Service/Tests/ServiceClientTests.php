<?php
namespace Mezon\Service\Tests;

/**
 * Class ServiceClientTests
 *
 * @package ServiceClient
 * @subpackage ServiceClientTests
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/17)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Common unit tests for ServiceClient and all derived client classes
 *
 * @author Dodonov A.A.
 * @group baseTests
 */
class ServiceClientTests extends \PHPUnit\Framework\TestCase
{

    /**
     * Client class name
     */
    protected $clientClassName = '';

    /**
     * Existing user's login
     *
     * @var string
     */
    protected $existingLogin = '';

    /**
     * Constructor
     *
     * @param string $existingLogin
     */
    public function __construct(string $existingLogin)
    {
        parent::__construct();

        $this->existingLogin = $existingLogin;
    }

    /**
     * Method creates client object
     *
     * @param string $password
     */
    protected function constructClient(string $password = 'root'): object
    {
        return new $this->clientClassName($this->existingLogin, $password);
    }

    /**
     * Testing API connection
     */
    public function testValidConnect(): void
    {
        $client = $this->constructClient();

        $this->assertNotEquals($client->getToken(), false, 'Connection failed');
        $this->assertEquals($client->getStoredLogin(), $this->existingLogin, 'Login was not saved');
    }

    /**
     * Testing invalid API connection
     */
    public function testInValidConnect(): void
    {
        $this->expectException(\Exception::class);
        $this->constructClient('1234567');
    }

    /**
     * Testing setting valid token
     */
    public function testSetValidToken(): void
    {
        $client = $this->constructClient();

        $newClient = new $this->clientClassName();
        $newClient->setToken($client->getToken());

        $this->assertNotEquals($newClient->getToken(), false, 'Token was not set(1)');
    }

    /**
     * Testing setting valid token and login
     */
    public function testSetValidTokenAndLogin(): void
    {
        $client = $this->constructClient();

        $newClient = new $this->clientClassName();
        $newClient->setToken($client->getToken(), 'alexey@dodonov.none');

        $this->assertNotEquals($newClient->getToken(), false, 'Token was not set(2)');
        $this->assertNotEquals($newClient->getStoredLogin(), false, 'Login was not saved');
    }

    /**
     * Testing setting invalid token
     */
    public function testSetInValidToken(): void
    {
        $client = new $this->clientClassName();

        $this->expectException(\Exception::class);
        $client->setToken('unexistingtoken');
    }

    /**
     * Testing loginAs method
     */
    public function testLoginAs(): void
    {
        $client = $this->constructClient();

        $client->loginAs($this->existingLogin);

        $this->addToAssertionCount(1);
    }

    /**
     * Testing loginAs method with failed call
     */
    public function testFailedLoginAs(): void
    {
        $client = $this->constructClient();

        $this->expectException(\Exception::class);
        $client->loginAs('alexey@dodonov.none', 'login');
    }
}
