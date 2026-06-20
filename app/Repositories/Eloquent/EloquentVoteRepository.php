<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Vote;
use App\Repositories\Contracts\VoteRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class EloquentVoteRepository extends BaseRepository implements VoteRepositoryInterface
{
    public function __construct(Vote $model)
    {
        parent::__construct($model);
    }

    public function findForUserAndVotable(User $user, Model $votable): ?Vote
    {
        return $this->model
            ->where('user_id', $user->id)
            ->where('votable_type', $votable->getMorphClass())
            ->where('votable_id', $votable->getKey())
            ->first();
    }

    public function castVote(User $user, Model $votable): Vote
    {
        return $this->model->firstOrCreate([
            'user_id' => $user->id,
            'votable_type' => $votable->getMorphClass(),
            'votable_id' => $votable->getKey(),
        ], [
            'value' => 1,
        ]);
    }

    public function removeVote(User $user, Model $votable): bool
    {
        return (bool) $this->model
            ->where('user_id', $user->id)
            ->where('votable_type', $votable->getMorphClass())
            ->where('votable_id', $votable->getKey())
            ->delete();
    }
}
