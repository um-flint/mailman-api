<?php

namespace UMFlint\Mailman\Traits;

trait ValidatesDomain
{
    /**
     * Validate a domain.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string $domain
     * @throws \Exception
     */
    private function validateDomain(string $domain)
    {
        if (!filter_var($domain, FILTER_VALIDATE_URL)) {
            throw new \Exception("{$domain} is not a valid URL!");
        }
    }
}