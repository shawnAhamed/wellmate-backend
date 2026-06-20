<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;

interface VoteRepositoryInterface extends RepositoryInterface
{
    public function findForUserAndVotable(User $user, Model $votable): ?Vote;

    public function castVote(User $user, Model $votable): Vote;

    public function removeVote(User $user, Model $votable): bool;
}
