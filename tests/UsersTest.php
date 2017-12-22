<?php

class UsersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Mockery
     */
    protected $client;
    
    /**
     * @var \UMFlint\Mailman\Users
     */
    protected $users;
    
    /**
     * Mock Guzzle.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = Mockery::mock(\GuzzleHttp\Client::class);
        $this->users = new \UMFlint\Mailman\Users($this->client);
    }
    
    public function testAll()
    {
        $data = [
            'entry 0'    => [
                'created_on'      => '2005-08-01T07:49:23',
                'display_name'    => 'Anne Person',
                'http_etag'       => '...',
                'is_server_owner' => false,
                'self_link'       => 'http://localhost:9001/3.0/users/1',
                'user_id'         => 1,
            ],
            'entry 1'    => [
                'created_on'      => '2005-08-01T07:49:23',
                'display_name'    => 'Anne Other Person',
                'http_etag'       => '...',
                'is_server_owner' => false,
                'self_link'       => 'http://localhost:9001/3.0/users/2',
                'user_id'         => 2,
            ],
            'http_etag'  => '...',
            'start'      => 0,
            'total_size' => 2,
        ];
        
        $this->client->allows()->get('users', [
            'query' => [
                'count' => 2,
                'page'  => 1,
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->users->all(2, 1);
        
        $this->assertEquals($data, $response);
    }
    
    public function testFind()
    {
        $data = [
            'created_on'      => '2005-08-01T07:49:23',
            'display_name'    => 'Aye Person',
            'http_etag'       => '...',
            'is_server_admin' => false,
            'password'        => '',
            'self_link'       => 'http://localhost:9001/3.0/users/3',
            'user_id'         => 3,
        ];
        
        $this->client->allows()->get('users/aperson@example.com', [])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->users->find('aperson@example.com');
        
        $this->assertEquals($data, $response);
    }
    
    public function testCreate()
    {
        $data = [
            'content-length' => 0,
            'content-type'   => 'application/json; charset=UTF-8',
            'date'           => '2017-12-20T13:14:15',
            'location'       => 'http://localhost:9001/3.0/users/3',
            'server'         => 'users-server',
            'status'         => 201,
        ];
        
        $this->client->allows()->post('users', [
            'json' => [
                'email'        => 'aperson@example.com',
                'display_name' => 'Aye Person',
                'password'     => '',
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->users->create('aperson@example.com', 'Aye Person', '');
        
        $this->assertEquals($data, $response);
    }
    
    public function testUpdateDisplayName()
    {
        $data = [
            'content-length' => 0,
            'date'           => '2017-12-20T13:14:15',
            'server'         => 'users-server',
            'status'         => 204,
        ];
        
        $this->client->allows()->patch('users/aperson@example.com', [
            'json' => [
                'display_name' => 'Aye Persons Email',
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->users->updateDisplayName('aperson@example.com', 'Aye Persons Email');
        
        $this->assertEquals($data, $response);
    }
    
    public function testUpdatePassword()
    {
        $data = [
            'content-length' => 0,
            'date'           => '12-20-2017T13:14:15',
            'server'         => 'users-server',
            'status'         => 204,
        ];
        
        $this->client->allows()->patch('users/aperson@example.com', [
            'json' => [
                'cleartext_password' => 'thisisnotasafepassword',
            ],
        ])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->users->updatePassword('aperson@example.com', 'thisisnotasafepassword');
        
        $this->assertEquals($data, $response);
    }
    
    public function testDelete()
    {
        $data = [
            'content-length' => 0,
            'date'           => '12-20-2017T13:14:15',
            'server'         => 'users-server',
            'status'         => 204,
        ];
        
        $this->client->allows()->delete('users/aperson@example.com', [])->andReturns(new \GuzzleHttp\Psr7\Response(200, [], json_encode($data)));
        
        $response = $this->users->delete('aperson@example.com');
        
        $this->assertEquals($data, $response);
    }
}