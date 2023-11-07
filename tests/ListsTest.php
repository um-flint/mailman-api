<?php

class ListsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Mockery
     */
    protected $client;
    
    /**
     * @var \UMFlint\Mailman\Lists
     */
    protected $lists;
    
    /**
     * Mock Guzzle.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->client = Mockery::mock(GuzzleHttp\Client::class);
        $this->lists = new \UMFlint\Mailman\Lists($this->client);
    }
    
    public function testAll()
    {
        $data = [
            'entry 0'    => [
                'display_name'  => 'Ant',
                'fqdn_listname' => 'ant@example.com',
                'http_etag'     => '...',
                'list_id'       => 'ant.example.com',
                'list_name'     => 'ant',
                'mail_host'     => 'example.com',
                'member_count'  => 0,
                'self_link'     => 'http://localhost:9001/3.0/lists/ant.example.com',
                'volume'        => 1,
            ],
            'entry 1'    => [
                'display_name'  => 'Elk',
                'fqdn_listname' => 'elk@example.com',
                'http_etag'     => '...',
                'list_id'       => 'elk.example.com',
                'list_name'     => 'elk',
                'mail_host'     => 'example.com',
                'member_count'  => 3,
                'self_link'     => 'http://localhost:9001/3.0/lists/elk.example.com',
                'volume'        => 1,
            ],
            'entry 2'    => [
                'display_name'  => 'Bee',
                'fqdn_listname' => 'bee@example.com',
                'http_etag'     => '...',
                'list_id'       => 'bee.example.com',
                'list_name'     => 'bee',
                'mail_host'     => 'example.com',
                'member_count'  => 0,
                'self_link'     => 'http://localhost:9001/3.0/lists/bee.example.com',
                'volume'        => 1,
            ],
            'http_etag'  => '...',
            'start'      => 0,
            'total_size' => 3,
        ];
        
        $this->client->allows()->get('lists', [
            'query' => [
                'count'      => 3,
                'page'       => 1,
                'advertised' => true,
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->lists->all(3, 1, true);
        
        $this->assertEquals($data, $response);
    }
    
    public function testMembers()
    {
        $data = [
            'entry 0'    => [
                'address'       => 'http://localhost:9001/3.0/addresses/aperson@example.com',
                'delivery_mode' => 'regular',
                'email'         => 'aperson@example.com',
                'http_etag'     => '...',
                'list_id'       => 'ant.example.com',
                'member_id'     => 4,
                'role'          => 'member',
                'self_link'     => 'http://localhost:9001/3.0/members/4',
                'user'          => 'http://localhost:9001/3.0/users/3',
            ],
            'entry 1'    => [
                'address'       => 'http://localhost:9001/3.0/addresses/bperson@example.com',
                'delivery_mode' => 'regular',
                'email'         => 'bperson@example.com',
                'http_etag'     => '...',
                'list_id'       => 'ant.example.com',
                'member_id'     => 5,
                'role'          => 'member',
                'self_link'     => 'http://localhost:9001/3.0/members/5',
                'user'          => 'http://localhost:9001/3.0/users/7',
            ],
            'entry 2'    => [
                'address'       => 'http://localhost:9001/3.0/addresses/cperson@example.com',
                'delivery_mode' => 'regular',
                'email'         => 'cperson@example.com',
                'http_etag'     => '...',
                'list_id'       => 'ant.example.com',
                'member_id'     => 6,
                'role'          => 'member',
                'self_link'     => 'http://localhost:9001/3.0/members/6',
                'user'          => 'http://localhost:9001/3.0/users/9',
            ],
            'http_etag'  => '...',
            'start'      => 0,
            'total_size' => 3,
        ];
        
        $this->client->allows()->get('lists/bee@example.com/roster/member', [
            'query' => [
                'count' => 3,
                'page'  => 1,
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->lists->members('bee@example.com', 3, 1);
        
        $this->assertEquals($data, $response);
    }
    
    public function testCreate()
    {
        $data = [
            'content-length' => 0,
            'content-type'   => 'application/json; charset=UTF-8',
            'date'           => '2017-12-22T13:14:15',
            'location'       => 'http://localhost:9001/3.0/lists/bee,example.com',
        ];
        
        $this->client->allows()->post('lists', [
            'form_params' => [
                'fqdn_listname' => 'bee@example.com',
                'style_name'    => 'legacy-announce',
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->lists->create('bee@example.com', 'legacy-announce');
        
        $this->assertEquals($data, $response);
    }
    
    public function testDelete()
    {
        $data = [
            'content-length' => 0,
            'date'           => '2017-12-22T13:14:15',
            'server'         => 'list-server',
            'status'         => 204,
        ];
        
        $this->client->allows()->delete('lists/http://bee.example.com', [])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->lists->delete('http://bee.example.com');
        
        $this->assertEquals($data, $response);
    }
}