<?php

namespace App\Services;

use App\Models\Report;
use App\Models\User;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Support\AuditLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class ReportService
{
    public function __construct(
        private ReportRepositoryInterface $reports,
        private AuditLogger $auditLogger,
    ) {
    }

    public function report(User $user, Model $reportable, array $data): Report
    {
        if ($this->reports->existsForUserAndReportable($user, $reportable)) {
            throw ValidationException::withMessages([
                'reportable' => ['You have already reported this content.'],
            ]);
        }

        return $this->reports->create([
            'user_id' => $user->id,
            'reportable_type' => $reportable->getMorphClass(),
            'reportable_id' => $reportable->getKey(),
            'reason' => $data['reason'],
            'details' => $data['details'] ?? null,
            'status' => 'pending',
        ]);
    }

    public function listByStatus(?string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->reports->paginatedByStatus($status, $perPage);
    }

    public function resolve(Report $report, User $admin): Report
    {
        $this->reports->update($report, [
            'status' => 'resolved',
            'resolved_by' => $admin->id,
            'resolved_at' => now(),
        ]);

        $this->auditLogger->log('report.resolved', $report, [], [], $admin->id);

        return $report;
    }

    public function dismiss(Report $report, User $admin): Report
    {
        $this->reports->update($report, [
            'status' => 'dismissed',
            'resolved_by' => $admin->id,
            'resolved_at' => now(),
        ]);

        $this->auditLogger->log('report.dismissed', $report, [], [], $admin->id);

        return $report;
    }
}
