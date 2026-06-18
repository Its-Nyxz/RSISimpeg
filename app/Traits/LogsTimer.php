<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

trait LogsTimer
{
    /**
     * Log to a specific channel in storage/logs
     */
    public function logToChannel(string $channel, string $level, string $message, array $context = []): void
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $userId = auth()->id() ?? 'GUEST';
        $name = auth()->user()?->name ?? 'GUEST';
        
        // Add default context
        $defaultContext = [
            'timestamp' => $timestamp,
            'name' => $name,
            'user_id' => $userId,
            'class' => class_basename(static::class),
        ];
        
        $finalContext = array_merge($defaultContext, $context);
        
        Log::channel($channel)->$level($message, $finalContext);
    }

    /**
     * Log timer start event
     */
    public function logTimerStart(array $details = []): void
    {
        $this->logToChannel('timer', 'info', 'Timer Started', array_merge([
            'jadwal_id' => $this->jadwal_id ?? null,
            'time_in' => $this->timeIn ?? null,
            'is_late' => $this->late ?? false,
        ], $details));
    }

    /**
     * Log timer stop event
     */
    public function logTimerStop(array $details = []): void
    {
        $this->logToChannel('timer', 'info', 'Timer Stopped', array_merge([
            'jadwal_id' => $this->jadwal_id ?? null,
            'time_in' => $this->timeIn ?? null,
            'time_out' => $this->timeOut ?? null,
        ], $details));
    }

    /**
     * Log validation error
     */
    public function logValidationError(string $message, array $context = []): void
    {
        $this->logToChannel('timer', 'warning', $message, $context);
    }

    /**
     * Log location validation
     */
    public function logLocationValidation(bool $isValid, array $details = []): void
    {
        $level = $isValid ? 'info' : 'warning';
        $message = $isValid ? 'Location Validation Passed' : 'Location Validation Failed';
        
        $this->logToChannel('timer', $level, $message, array_merge([
            'latitude' => $this->latitude ?? null,
            'longitude' => $this->longitude ?? null,
            'accuracy' => $this->accuracy ?? null,
            'ip_address' => request()->ip(),
        ], $details));
    }

    /**
     * Log overtime event
     */
    public function logOvertimeEvent(string $action, array $details = []): void
    {
        $this->logToChannel('timer', 'info', "Overtime: {$action}", array_merge([
            'jadwal_id' => $this->jadwal_id ?? null,
            'time_in_lembur' => $this->timeInLembur ?? null,
            'is_lembur_running' => $this->isLemburRunning ?? false,
        ], $details));
    }

    /**
     * Log dinas (official assignment) event
     */
    public function logDinasEvent(string $action, array $details = []): void
    {
        $this->logToChannel('timer', 'info', "Dinas: {$action}", array_merge([
            'jadwal_id' => $this->jadwal_id ?? null,
            'akan_kembali' => $this->akanKembali ?? null,
            'deskripsi_dinas' => $this->deskripsi_dinas ?? null,
        ], $details));
    }

    /**
     * Log database operation
     */
    public function logDatabaseOperation(string $operation, string $model, array $data = []): void
    {
        $this->logToChannel('timer', 'info', "DB Operation: {$operation} - {$model}", $data);
    }

    /**
     * Log error event
     */
    public function logError(string $message, array $context = []): void
    {
        $this->logToChannel('timer', 'error', $message, $context);
    }

    /**
     * Log debug info
     */
    public function logDebug(string $message, array $context = []): void
    {
        $this->logToChannel('timer', 'debug', $message, $context);
    }
}
