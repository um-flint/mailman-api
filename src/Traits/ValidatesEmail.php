<?php

namespace UMFlint\Mailman\Traits;

trait ValidatesEmail
{
    /**
     * Validate a email.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string $email
     * @throws \Exception
     */
    private function validateEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("{$email} is not a valid email!");
        }
    }
}