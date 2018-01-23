<?php

namespace UMFlint\Mailman;

class Domains extends Endpoint
{
    /**
     * Get all domains.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/domains.html#domains
     * @param int|null $count
     * @param int|null $page
     * @return array
     */
    public function all(?int $count, ?int $page)
    {
        $options = [];

        if (!is_null($count)) {
            $options['query']['count'] = $count;
        }

        if (!is_null($page)) {
            $options['query']['page'] = $page;
        }

        return $this->clientGet('domains', $options);
    }

    /**
     * Find a domain.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/domains.html#individual-domains
     * @param string $domain
     * @return array
     * @throws \Exception
     */
    public function find(string $domain)
    {
        return $this->clientGet("domains/{$domain}");
    }

    /**
     * Get the lists for a domain.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/domains.html#individual-domains
     * @param string    $domain
     * @param int|null  $count
     * @param int|null  $page
     * @param bool|null $advertised
     * @return array
     * @throws \Exception
     */
    public function lists(string $domain, ?int $count = null, ?int $page = null, ?bool $advertised = null)
    {
        $options = [];

        if (!is_null($count)) {
            $options['query']['count'] = $count;
        }

        if (!is_null($page)) {
            $options['query']['page'] = $page;
        }

        if (!is_null($advertised)) {
            $options['query']['advertised'] = $advertised;
        }


        return $this->clientGet("domains/{$domain}/lists", $options);
    }

    /**
     * Create a new domain.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/domains.html#creating-new-domains
     * @param string      $domain
     * @param null|string $description
     * @return array
     * @throws \Exception
     */
    public function create(string $domain, ?string $description = null)
    {
        return $this->clientPost('domains', [
            'form_params' => [
                'mail_host'   => $domain,
                'description' => $description ?? '',
            ],
        ]);
    }

    /**
     * Delete a domain.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/domains.html#deleting-domains
     * @param string $domain
     * @return mixed
     * @throws \Exception
     */
    public function delete(string $domain)
    {
        return $this->clientDelete("domains/{$domain}");
    }
}