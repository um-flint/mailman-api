<?php

class MembersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Mockery
     */
    protected $client;
    
    /**
     * @var \UMFlint\Mailman\Members
     */
    protected $members;
    
    /**
     * Mock Guzzle.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->client = Mockery::mock(\GuzzleHttp\Client::class);
        $this->members = new \UMFlint\Mailman\Members($this->client);
    }
    
    public function testAll()
    {
        $data = [
            'entry 0' => [
                'address'       => 'http://localhost:9001/3.0/addresses/bperson@example.com',
                'delivery_mode' => 'regular',
                'email'         => 'bperson@example.com',
                'http_etag'     => '...',
                'list_id'       => 'bee.example.com',
                'member_id'     => 1,
                'role'          => 'member',
                'self_link'     => 'http://localhost:9001/3.0/members/1',
                'user'          => 'http://localhost:9001/3.0/users/1',
            ],
            'entry 1' => [
                'address'       => 'http://localhost:9001/3.0/addresses/cperson@example.com',
                'delivery_mode' => 'regular',
                'email'         => 'cperson@example.com',
                'http_etag'     => '...',
                'list_id'       => 'bee.example.com',
                'member_id'     => 2,
                'role'          => 'member',
                'self_link'     => 'http://localhost:9001/3.0/members/2',
                'user'          => 'http://localhost:9001/3.0/users/2',
            ],
            'entry 2' => [
                'address'       => 'http://localhost:9001/3.0/addresses/dperson@example.com',
                'delivery_mode' => 'regular',
                'email'         => 'dperson@example.com',
                'http_etag'     => '...',
                'list_id'       => 'bee.example.com',
                'member_id'     => 3,
                'role'          => 'member',
                'self_link'     => 'http://localhost:9001/3.0/members/3',
                'user'          => 'http://localhost:9001/3.0/users/3',
            ],
        ];
        
        $this->client->allows()->get('members', [
            'query' => [
                'count' => 3,
                'page'  => 1,
            ],
        ])->andReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->members->all(3, 1);
        
        $this->assertEquals($data, $response);
    }
    
    public function testFind()
    {
        $data = [
            'entry 0'    => [
                'address'       => 'http://localhost:9001/3.0/addresses/cperson@example.com',
                'delivery_mode' => 'regular',
                'email'         => 'cperson@example.com',
                'http_etag'     => '...',
                'list_id'       => 'bee.example.com',
                'member_id'     => 2,
                'role'          => 'member',
                'self_link'     => 'http://localhost:9001/3.0/members/3',
                'user'          => 'http://localhost:9001/3.0/users/2',
            ],
            'http_etag'  => '...',
            'start'      => 0,
            'total_size' => '1',
        ];
        
        $this->client->allows()->get('members/find', [
            'query' => [
                'list_id'    => 'see.example.com',
                'subscriber' => 'cperson@example.com',
                'role'       => 'member',
            ],
        ])->andReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->members->find('see.example.com', 'cperson@example.com', 'member');
        
        $this->assertEquals($data, $response);
    }
    
    public function testSubscribe()
    {
        $data = [
            'content-length' => 0,
            'content-type'   => 'application/json; charset=UTF-8',
            'date'           => '2017-12-20T13:14:15',
            'location'       => 'http://localhost:9001/3.0/members/8',
            'server'         => 'members-server',
            'status'         => 201,
        ];
        
        $this->client->allows()->post('members', [
            'form_params' => [
                'list_id'       => 'http://ant.example.com',
                'subscriber'    => 'aperson@example.com',
                'display_name'  => 'Aye Person',
                'pre_verified'  => true,
                'pre_confirmed' => true,
                'pre_approved'  => true,
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->members->subscribe('http://ant.example.com', 'aperson@example.com', 'Aye Person', true, true, true);
        
        $this->assertEquals($data, $response);
    }
    
    public function testUnsubscribe()
    {
        $data = [
            'content-length' => 0,
            'status'         => 204,
        ];
        
        $this->client->allows()->delete('members/5', [])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->members->unsubscribe(5);
        
        $this->assertEquals($data, $response);
    }
}