<?php

/**
 * LICENSE: Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * PHP version 5
 *
 * @category  Microsoft
 * @package   PEAR2\Tests\Unit\WindowsAzure
 * @author    Abdelrahman Elogeel <Abdelrahman.Elogeel@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      http://pear.php.net/package/azure-sdk-for-php
 */

use PEAR2\WindowsAzure\Services\Core\HttpClient;
use PEAR2\WindowsAzure\Resources;
use PEAR2\Tests\Unit\TestResources;
use PEAR2\Tests\Mock\WindowsAzure\Services\Core\Filters\SimpleFilterMock;
use PEAR2\WindowsAzure\Core\ServiceException;

/**
 * Unit tests for class HttpClient
 *
 * @category  Microsoft
 * @package   PEAR2\Tests\Unit\WindowsAzure\Core
 * @author    Abdelrahman Elogeel <Abdelrahman.Elogeel@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/azure-sdk-for-php
 */
class HttpClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::__construct
     */
    public function test__construct()
    {
        // Test
        $channel = new HttpClient();
        $headers = $channel->getHeaders();
        
        // Assert
        $this->assertTrue(isset($channel));
        $this->assertContains(Resources::X_MS_VERSION, array_keys($headers));
        $this->assertNull($channel->getUrl());
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::reset
     */
    public function testReset()
    {
        // Setup
        $channel = new HttpClient();
        
        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER1_VALUE);
        $channel->setHeader(TestResources::HEADER2, TestResources::HEADER2_VALUE);
        
        // Test
        $channel->reset();
        
        // Assert
        $headers = $channel->getHeaders();
        $this->assertCount(1, $headers);
        $this->assertContains(Resources::X_MS_VERSION, array_keys($headers));
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::setUrl
     */
    public function testSetUrl()
    {
        // Setup
        $channel = new HttpClient();
        $url = new PEAR2\WindowsAzure\Core\Url(TestResources::VALID_URL);
        
        // Test
        $channel->setUrl($url);
        
        // Assert
        $this->assertInstanceOf('PEAR2\WindowsAzure\Core\IUrl', $channel->getUrl());
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::getUrl
     */
    public function testGetUrl()
    {
        // Setup
        $channel = new HttpClient();
        $url = new PEAR2\WindowsAzure\Core\Url(TestResources::VALID_URL);
        $channel->setUrl($url);
        
        // Test
        $channelUrl = $channel->getUrl();
        
        // Assert
        $this->assertInstanceOf('PEAR2\WindowsAzure\Core\IUrl', $channelUrl);
        $this->assertEquals(TestResources::VALID_URL, $channelUrl);
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::setMethod
     */
    public function testSetMethod()
    {
        // Setup
        $channel = new HttpClient();
        $httpMethod = 'GET';
        
        // Test
        $channel->setMethod($httpMethod);
        
        // Assert
        $this->assertEquals($httpMethod, $channel->getMethod());
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::getMethod
     */
    public function testGetMethod()
    {
        // Setup
        $channel = new HttpClient();
        $httpMethod = 'GET';
        $channel->setMethod($httpMethod);
        
        // Test
        $channelHttpMethod = $channel->getMethod();
        
        // Assert
        $this->assertEquals($httpMethod, $channelHttpMethod);
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::setHeaders
     */
    public function testSetHeaders()
    {
        // Setup
        $channel = new HttpClient();
        $header1 = TestResources::HEADER1;
        $header2 = TestResources::HEADER2;
        $value1  = TestResources::HEADER1_VALUE;
        $value2  = TestResources::HEADER2_VALUE;
        $headers = array ($header1 => $value1, $header2 => $value2);
        
        // Test
        $channel->setHeaders($headers);
        
        // Assert
        $channelHeaders = $channel->getHeaders();
        $this->assertCount(3, $channelHeaders);
        $this->assertEquals($value1, $channelHeaders[$header1]);
        $this->assertEquals($value2, $channelHeaders[$header2]);
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::getHeaders
     */
    public function testGetHeaders()
    {
        // Setup
        $channel = new HttpClient();
        $header1 = TestResources::HEADER1;
        $header2 = TestResources::HEADER2;
        $value1 = TestResources::HEADER1_VALUE;
        $value2 = TestResources::HEADER2_VALUE;
        $channel->setHeader($header1, $value1);
        $channel->setHeader($header2, $value2);
        
        // Test
        $headers = $channel->getHeaders();
        
        // Assert
        $this->assertCount(3, $headers);
        $this->assertEquals($value1, $headers[$header1]);
        $this->assertEquals($value2, $headers[$header2]);
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::setHeader
     */
    public function testSetHeaderNewHeader()
    {
        // Setup
        $channel = new HttpClient();
        
        // Test
        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER1_VALUE);
        
        // Assert
        $headers = $channel->getHeaders();
        $this->assertCount(2, $headers);
        $this->assertEquals(TestResources::HEADER1_VALUE, $headers[TestResources::HEADER1]);
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::setHeader
     */
    public function testSetHeaderExistingHeaderReplace()
    {
        // Setup
        $channel = new HttpClient();
        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER1_VALUE);
        
        // Test
        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER2_VALUE, true);
        
        // Assert
        $headers = $channel->getHeaders();
        $this->assertCount(2, $headers);
        $this->assertEquals(TestResources::HEADER2_VALUE, $headers[TestResources::HEADER1]);
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::setHeader
     */
    public function testSetHeaderExistingHeaderAppend()
    {
        // Setup
        $channel = new HttpClient();
        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER1_VALUE);
        $expected = TestResources::HEADER1_VALUE . ', ' . TestResources::HEADER2_VALUE;
        
        // Test
        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER2_VALUE);
        
        // Assert
        $headers = $channel->getHeaders();
        $this->assertCount(2, $headers);
        $this->assertEquals($expected, $headers[TestResources::HEADER1]);
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::send
     */
    public function testSendSimple()
    {
        // Setup
        $channel = new HttpClient();
        $url = new PEAR2\WindowsAzure\Core\Url('http://www.microsoft.com/');
        $channel->setSuccessfulStatusCode('200');
        
        // Test
        $response = $channel->send(array(), $url);
        
        // Assert
        $this->assertTrue(isset($response));
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::send
     */
    public function testSendWithOneFilter()
    {
        // Setup
        $channel = new HttpClient();
        $url = new PEAR2\WindowsAzure\Core\Url('http://www.microsoft.com/');
        $channel->setSuccessfulStatusCode('200');
        $expectedHeader = TestResources::HEADER1;
        $expectedResponseSubstring = TestResources::HEADER1_VALUE;
        $filter = new SimpleFilterMock($expectedHeader, $expectedResponseSubstring);
        $filters = array($filter);
        
        // Test
        $response = $channel->send($filters, $url);
        
        // Assert
        $this->assertArrayHasKey($expectedHeader, $channel->getHeaders());
        $this->assertTrue(isset($response));
        $this->assertContains($expectedResponseSubstring, $response);
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::send
     */
    public function testSendWithMultipleFilters()
    {
        // Setup
        $channel = new HttpClient();
        $url = new PEAR2\WindowsAzure\Core\Url('http://www.microsoft.com/');
        $channel->setSuccessfulStatusCode('200');
        $expectedHeader1 = TestResources::HEADER1;
        $expectedResponseSubstring1 = TestResources::HEADER1_VALUE;
        $expectedHeader2 = TestResources::HEADER2;
        $expectedResponseSubstring2 = TestResources::HEADER2_VALUE;
        $filter1 = new SimpleFilterMock($expectedHeader1, $expectedResponseSubstring1);
        $filter2 = new SimpleFilterMock($expectedHeader2, $expectedResponseSubstring2);
        $filters = array($filter1, $filter2);
        
        // Test
        $response = $channel->send($filters, $url);
        
        // Assert
        $this->assertArrayHasKey($expectedHeader1, $channel->getHeaders());
        $this->assertArrayHasKey($expectedHeader2, $channel->getHeaders());
        $this->assertTrue(isset($response));
        $this->assertContains($expectedResponseSubstring1, $response);
        $this->assertContains($expectedResponseSubstring2, $response);
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::send
     */
    public function testSendFail()
    {
        // Setup
        $channel = new HttpClient();
        $url = new PEAR2\WindowsAzure\Core\Url('http://www.microsoft.com/');
        $channel->setSuccessfulStatusCode('201');
        $this->setExpectedException(get_class(new ServiceException('200')));
        
        // Test
        $channel->send(array(), $url);
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::setSuccessfulStatusCode
     */
    public function testSetSuccessfulStatusCodeSimple()
    {
        // Setup
        $channel = new HttpClient();
        $code = '200';
        
        // Test
        $channel->setSuccessfulStatusCode($code);
        
        // Assert
        $this->assertContains($code, $channel->getSuccessfulStatusCode());
    }
    
    /**
     * @covers PEAR2\WindowsAzure\Services\Core\HttpClient::setSuccessfulStatusCode
     */
    public function testSetSuccessfulStatusCodeArray()
    {
        // Setup
        $channel = new HttpClient();
        $codes = array ('200', '201', '202');
        
        // Test
        $channel->setSuccessfulStatusCode($codes);
        
        // Assert
        $this->assertEquals($codes, $channel->getSuccessfulStatusCode());
    }
}

?>
