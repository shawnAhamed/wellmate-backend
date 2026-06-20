<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ConsultationMessageRepositoryInterface extends RepositoryInterface
{
    public function forConsultation(int $consultationId): Collection;
}
