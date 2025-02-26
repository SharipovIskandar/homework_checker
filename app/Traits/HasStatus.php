<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasStatus
{
    public function scopeActive($query): void
    {
        if (DB::getSchemaBuilder()->getColumnType($this->getTable(), 'status') == 'bool') {
            $query->where('status', true);
        } else {
            $query->where('status', 'active');
        }
    }

    public function scopeActiveList($query, $request = null): void
    {
        $request = getRequest($request);
        $query->where(function ($query) use ($request) {
            if ($request->instance == 'active')
                $query->active();
        });
    }

    function setStatus($value = null): static
    {
        if ($value) {
            $this->status = $value;
        } else {
            if (gettype($this->status) == 'string') {
                $this->status == 'active' ? $this->status = 'inactive' : $this->status = 'active';
            } else {
                $this->status = !$this->status;
            }
        }
        $this->save();
        return $this;
    }
}
