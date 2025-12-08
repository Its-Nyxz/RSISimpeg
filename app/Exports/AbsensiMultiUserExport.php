<?php

namespace App\Exports;

use App\Models\User;
use App\Exports\AbsensiPerUserSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AbsensiMultiUserExport implements WithMultipleSheets
{
    protected $userIds;
    protected $month;
    protected $year;

    public function __construct(array $userIds, $month, $year)
    {
        $this->userIds = $userIds;
        $this->month = $month;
        $this->year = $year;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->userIds as $userId) {
            $user = User::find($userId);
            if (!$user)
                continue;

            $sheets[] = new AbsensiUserSheet(
                $user,
                $this->month,
                $this->year
            );
        }

        return $sheets;
    }
}
