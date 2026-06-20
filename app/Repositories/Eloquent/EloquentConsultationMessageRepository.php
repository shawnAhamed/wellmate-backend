<?php

namespace App\Repositories\Eloquent;

use App\Models\ConsultationMessage;
use App\Repositories\Contracts\ConsultationMessageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentConsultationMessageRepository extends BaseRepository implements ConsultationMessageRepositoryInterface
{
    public function __construct(ConsultationMessage $model)
    {
        parent::__construct($model);
    }

    public function forConsultation(int $consultationId): Collection
    {
        return $this->model->with('sender')
            ->where('consultation_id', $consultationId)
            ->oldest()
            ->get();
    }
}
