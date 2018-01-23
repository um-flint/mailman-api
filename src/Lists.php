<?php

namespace UMFlint\Mailman;

class Lists extends Endpoint
{
    /**
     * Get all lists.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/lists.html#mailing-lists
     * @param int|null  $count
     * @param int|null  $page
     * @param bool|null $advertised
     * @return array
     */
    public function all(?int $count = null, ?int $page = null, ?bool $advertised = null)
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

        return $this->clientGet('lists', $options);
    }

    public function find(string $name)
    {
        return $this->clientGet("lists/{$name}");
    }

    /**
     * Get the members of a list.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/membership.html#paginating-over-member-records
     * @param string   $name
     * @param int|null $count
     * @param int|null $page
     * @return array
     * @throws \Exception
     */
    public function members(string $name, ?int $count = null, ?int $page = null)
    {
        $options = [];

        if (!is_null($count)) {
            $options['query']['count'] = $count;
        }

        if (!is_null($page)) {
            $options['query']['page'] = $page;
        }

        return $this->clientGet("lists/{$name}/roster/member", $options);
    }

    /**
     * Create a new list.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/lists.html#creating-lists-via-the-api
     * @param string      $name
     * @param null|string $style
     * @return array
     * @throws \Exception
     */
    public function create(string $name, ?string $style = null)
    {
        $data = [
            'fqdn_listname' => $name,
        ];

        if (!is_null($style)) {
            $data['style_name'] = $style;
        }

        return $this->clientPost('lists', [
            'form_params' => $data,
        ]);
    }

    /**
     * Delete a list.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/lists.html#deleting-lists-via-the-api
     * @param string $domain
     * @return array
     * @throws \Exception
     */
    public function delete(string $domain)
    {
        return $this->clientDelete("lists/{$domain}");
    }
}