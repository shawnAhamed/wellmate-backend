<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\VoteRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class VoteService
{
    public function __construct(private VoteRepositoryInterface $votes)
    {
    }

    public function vote(User $user, Model $votable): int
    {
        $this->votes->castVote($user, $votable);

        return $votable->votes()->count();
    }

    public function unvote(User $user, Model $votable): int
    {
        $this->votes->removeVote($user, $votable);

        return $votable->votes()->count();
    }
}
