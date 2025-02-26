<?php

namespace App\Traits;

use App\Models\Additions\Action;
use App\Models\Additions\UserLog;
use Illuminate\Support\Str;


trait Logs
{
    public function makeLog($model, $action_type, $t_name = null)
    {
        $action = Action::where('key', $action_type)->first();

        UserLog::create([
            'user_id'       => auth()->user()->id,
            'ip'            => request()->ip(),
            'table_name'    => $model->getTable().($t_name ? '.'.$t_name : null),
            'field_id'      => $model->id,
            'action_id'     => $action->id,
        ]);
    }

   
}
