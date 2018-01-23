<?php

namespace UMFlint\Mailman;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class Endpoint
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Endpoint constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the data from the response.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param Response $response
     * @return array|null
     */
    private function getData(Response $response): ?array
    {
        return json_decode($response->getBody(), 1);
    }

    /**
     * GET request.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string $uri
     * @param array  $options
     * @return array
     */
    protected function clientGet(string $uri, array $options = []): array
    {
        return $this->getData($this->client->get($uri, $options));
    }

    /**
     * HEAD request.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string $uri
     * @param array  $options
     * @return array
     */
    protected function clientHead(string $uri, array $options = []): array
    {
        return $this->getData($this->client->head($uri, $options));
    }

    /**
     * PUT request.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string $uri
     * @param array  $options
     * @return array
     */
    protected function clientPut(string $uri, array $options = []): array
    {
        return $this->getData($this->client->put($uri, $options));
    }

    /**
     * POST request.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string $uri
     * @param array  $options
     * @return array
     */
    protected function clientPost(string $uri, array $options = []): array
    {
        return $this->getData($this->client->post($uri, $options));
    }

    /**
     * PATCH request.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string $uri
     * @param array  $options
     * @return array
     */
    protected function clientPatch(string $uri, array $options = []): array
    {
        return $this->getData($this->client->patch($uri, $options));
    }

    /**
     * DELETE request.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string $uri
     * @param array  $options
     * @return array
     */
    protected function clientDelete(string $uri, array $options = []): array
    {
        return $this->getData($this->client->delete($uri, $options));
    }
}