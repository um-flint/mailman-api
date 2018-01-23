<?php

namespace UMFlint\Mailman;

use UMFlint\Mailman\Traits\ValidatesEmail;

class Users extends Endpoint
{
    use ValidatesEmail;
    
    /**
     * Get all users.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/users.html#users
     * @param int|null $count
     * @param int|null $page
     * @return array
     */
    public function all(?int $count = null, ?int $page = null)
    {
        $options = [];
        
        if (!is_null($count)) {
            $options['query']['count'] = $count;
        }
        
        if (!is_null($page)) {
            $options['query']['page'] = $page;
        }
        
        return $this->clientGet('users', $options);
    }
    
    /**
     * Find a user by their email.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string $email
     * @return array
     * @throws \Exception
     */
    public function find(string $email)
    {
        $this->validateEmail($email);
        
        return $this->clientGet("users/{$email}");
    }
    
    /**
     * Create a new user.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/users.html#creating-users
     * @param string $email
     * @param null|string $displayName
     * @param null|string $password
     * @return array
     * @throws \Exception
     */
    public function create(string $email, ?string $displayName = null, ?string $password = null)
    {
        $this->validateEmail($email);
        $data = [
            'email' => $email,
        ];
        
        if (!is_null($displayName)) {
            $data['display_name'] = $displayName;
        }
        
        if (!is_null($password)) {
            $data['password'] = $password;
        }
        
        return $this->clientPost('users', [
            'form_params' => $data,
        ]);
    }
    
    /**
     * Update a users display name.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/users.html#updating-users
     * @param string $email
     * @param string $displayName
     * @return array
     * @throws \Exception
     */
    public function updateDisplayName(string $email, string $displayName)
    {
        $this->validateEmail($email);
        
        return $this->clientPatch("users/{$email}", [
            'form_params' => [
                'display_name' => $displayName,
            ],
        ]);
    }
    
    /**
     * Update a users password.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/users.html#updating-users
     * @param string $email
     * @param string $password
     * @return array
     * @throws \Exception
     */
    public function updatePassword(string $email, string $password)
    {
        $this->validateEmail($email);
        
        return $this->clientPatch("users/{$email}", [
            'form_params' => [
                'cleartext_password' => $password,
            ],
        ]);
    }
    
    /**
     * Delete a user.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/users.html#deleting-users-via-the-api
     * @param string $email
     * @return array
     * @throws \Exception
     */
    public function delete(string $email)
    {
        $this->validateEmail($email);
        
        return $this->clientDelete("users/{$email}");
    }
}