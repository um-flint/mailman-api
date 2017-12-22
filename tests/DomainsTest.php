<?php

class DomainsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Mockery
     */
    protected $client;
    
    /**
     * @var \UMFlint\Mailman\Domains
     */
    protected $domains;
    
    /**
     * Mock Guzzle.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = Mockery::mock(GuzzleHttp\Client::class);
        $this->domains = new \UMFlint\Mailman\Domains($this->client);
    }
    
    public function testAll()
    {
        $data = [
            'entry 0'    => [
                'description' => 'An example domain',
                'http_etag'   => '...',
                'mail_host'   => 'example.com',
                'self_link'   => 'http://localhost:9001/3.0/domains/example.com',
            ],
            'entry 1'    => [
                'description' => 'None',
                'http_etag'   => '...',
                'mail_host'   => 'example.org',
                'self_link'   => 'http://localhost:9001/3.0/domains/example.org',
            ],
            'entry 2'    => [
                'description' => 'Porkmasters',
                'http_etag'   => '...',
                'mail_host'   => 'lists.example.net',
                'self_link'   => 'http://localhost:9001/3.0/domains/lists.example.net',
            ],
            'http_etag'  => '...',
            'start'      => 0,
            'total_size' => 3,
        ];
        
        $this->client->allows()->get('domains', [
            'query' => [
                'count' => 3,
                'page'  => 1,
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->domains->all(3, 1);
        
        $this->assertEquals($data, $response);
    }
    
    public function testFind()
    {
        $data = [
            'description' => 'Test example',
            'http_etag'   => '...',
            'mail_host'   => 'example.edu',
            'self_link'   => 'http://localhost:9001/3.0/domains/example.edu',
        ];
        
        $this->client->allows()->get('domains/http://test.example.edu', [])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->domains->find('http://test.example.edu');
        
        $this->assertEquals($data, $response);
    }
    
    public function testLists()
    {
        $data = [
            'entry 0'    => [
                'display_name'  => 'Test-domains',
                'fqdn_listname' => 'test-domains@example.com',
                'http_etag'     => '...',
                'member_count'  => 0,
                'self_link'     => 'http://localhost:9001/3.0/lists/test-domains.example.com',
                'volume'        => 1,
            ],
            'http_etag'  => '...',
            'start'      => 0,
            'total_size' => 1,
        ];
        
        $this->client->allows()->get('domains/http://test-domains.com/lists', [
            'query' => [
                'count'      => 3,
                'page'       => 1,
                'advertised' => true,
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->domains->lists('http://test-domains.com', 3, 1, true);
        
        $this->assertEquals($data, $response);
    }
    
    public function testCreate()
    {
        $data = [
            'content-length' => 0,
            'content-type'   => 'application/json; charset=UTF-8',
            'date'           => '12-20-2017',
            'location'       => 'http://localhost:9001/3.0/domains/lists.example.com',
        ];
        
        $this->client->allows()->post('domains', [
            'json' => [
                'mail_host'   => 'http://test-domain.edu',
                'description' => 'This is a description of a test domain...',
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->domains->create('http://test-domain.edu', 'This is a description of a test domain...');
        
        $this->assertEquals($data, $response);
    }
    
    public function testDelete()
    {
        $data = [
            'content-length' => 0,
            'date'           => '12-20-2017',
            'server'         => 'test-server',
            'status'         => 204,
        ];
        
        $this->client->allows()->delete('domains/http://test-domain.org', [])
            ->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->domains->delete('http://test-domain.org');
        
        $this->assertEquals($data, $response);
    }
}