<?php

namespace UMFlint\Mailman;

use GuzzleHttp\Client;

class Mailman
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var array
     * @link http://docs.guzzlephp.org/en/stable/request-options.html#auth
     */
    protected $auth = [];

    /**
     * @var Client
     */
    protected $client;

    /**
     * Mailman constructor.
     *
     * @param       $baseUri
     * @param       $version
     * @param array $auth
     * @param array $options
     */
    public function __construct($baseUri, $version, array $auth, array $options = [])
    {
        $this->baseUri = $baseUri;
        $this->version = $version;
        $this->auth = $auth;
        $this->client = $this->createClient($options);
    }

    /**
     * Create a new Guzzle client.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param array $options
     * @return Client
     */
    protected function createClient(array $options): Client
    {
        $baseUri = trim($this->baseUri, '/') . '/' . trim($this->version, '/') . '/';

        $options = array_merge_recursive($options, [
            'base_uri' => $baseUri,
            'auth'     => $this->auth,
        ]);

        return new Client($options);
    }

    /**
     * Get the Guzzle client.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return Client
     */
    protected function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Domains.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return Domains
     */
    public function domains(): Domains
    {
        return new Domains($this->getClient());
    }

    /**
     * Lists.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return Lists
     */
    public function lists(): Lists
    {
        return new Lists($this->getClient());
    }

    /**
     * Users.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return Users
     */
    public function users(): Users
    {
        return new Users($this->getClient());
    }

    /**
     * Members.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return Members
     */
    public function members(): Members
    {
        return new Members($this->getClient());
    }
}