<?php

namespace UMFlint\Mailman;

class Members extends Endpoint
{
    /**
     * Get all members.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/membership.html#membership
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

        return $this->clientGet('members', $options);
    }

    /**
     * Find members.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/membership.html#finding-members
     * @param null|string $listId
     * @param null|string $subscriber
     * @param null|string $role
     * @return array
     * @throws \Exception
     */
    public function find(?string $listId = null, ?string $subscriber = null, ?string $role = null)
    {
        $query = [];

        if (!is_null($listId)) {
            $query['list_id'] = $listId;
        }

        if (!is_null($subscriber)) {
            $query['subscriber'] = $subscriber;
        }

        if (!is_null($role)) {
            $query['role'] = $role;
        }

        if (count($query) === 0) {
            throw new \Exception("You must provide at least one search criteria!");
        }

        return $this->clientGet('members/find', [
            'query' => $query,
        ]);
    }

    /**
     * Subscribe a user to a list.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/membership.html#joining-a-mailing-list
     * @param string      $listId
     * @param string      $subscriber
     * @param null|string $displayName
     * @param bool        $verified
     * @param bool        $confirmed
     * @param bool        $approved
     * @return array
     * @throws \Exception
     */
    public function subscribe(string $listId, string $subscriber, ?string $displayName = null, bool $verified = false, bool $confirmed = false, bool $approved = false)
    {
        $data = [
            'list_id'       => $listId,
            'subscriber'    => $subscriber,
            'pre_verified'  => $verified,
            'pre_confirmed' => $confirmed,
            'pre_approved'  => $approved,
        ];

        if (!is_null($displayName)) {
            $data['display_name'] = $displayName;
        }

        return $this->clientPost('members', [
            'form_params' => $data,
        ]);
    }

    /**
     * Unsubscribe a user from a list.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @see    http://mailman.readthedocs.io/en/release-3.1/src/mailman/rest/docs/membership.html#leaving-a-mailing-list
     * @param string $memberId
     * @return array
     */
    public function unsubscribe(string $memberId)
    {
        return $this->clientDelete("members/{$memberId}");
    }
}