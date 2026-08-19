<?php

namespace App\Services;

use App\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    public function log($action, $model = null, $modelId = null, $changes = null, $description = null)
    {
        // Ensure changes are stored as JSON string to avoid serialization issues
        $changesJson = null;
        if ($changes !== null) {
            // if array or object, encode; otherwise cast to string
            if (is_array($changes) || is_object($changes)) {
                $changesJson = json_encode($changes, JSON_UNESCAPED_UNICODE);
            } else {
                $changesJson = (string)$changes;
            }
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'changes' => $changesJson,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
