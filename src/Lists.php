<?php

namespace UMFlint\Mailman;

use UMFlint\Mailman\Traits\ValidatesDomain;
use UMFlint\Mailman\Traits\ValidatesEmail;

class Lists extends Endpoint
{
    use ValidatesDomain, ValidatesEmail;
    
    /**
     * Get all lists.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/lists.html#mailing-lists
     * @param int|null $count
     * @param int|null $page
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
    
    /**
     * Get the members of a list.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/membership.html#paginating-over-member-records
     * @param string $name
     * @param int|null $count
     * @param int|null $page
     * @return array
     * @throws \Exception
     */
    public function members(string $name, ?int $count = null, ?int $page = null)
    {
        $this->validateEmail($name);
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
     * @param string $name
     * @param null|string $style
     * @return array
     * @throws \Exception
     */
    public function create(string $name, ?string $style = null)
    {
        $this->validateEmail($name);
        
        $data = [
            'fqdn_listname' => $name,
        ];
        
        if (!is_null($style)) {
            $data['style_name'] = $style;
        }
        
        return $this->clientPost('lists', [
            'json' => $data,
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
        $this->validateDomain($domain);
        
        return $this->clientDelete("lists/{$domain}");
    }
}