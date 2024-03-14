<?php

namespace App\Traits;
use Illuminate\Support\Facades\Schema;

Trait HandleInput
{
    public function smartSave($data, $ignoredFields = [])
    {
        $savefields = array_diff($this->getSavedFields(), $ignoredFields);

        foreach ($savefields as $field) {
            if ( isset($data[$field]) ) {
                $this->$field = $data[$field];
            }
        }
        
        return $this->save();
    }

    public function getSavedFields()
    {
        $fields = Schema::getColumnListing($this->getTable());

        $ignoredColumns = [
            $this->getKeyName(),
            $this->getUpdatedAtColumn(),
            $this->getCreatedAtColumn(),
        ];

        if (method_exists($this, 'getDeletedAtColumn')) {
            $ignoredColumns[] = $this->getDeletedAtColumn();
        }

        if ($this->ignored) {
            $ignoredColumns = array_diff($ignoredColumns, $this->ignored);
        }

        return array_diff($fields, $ignoredColumns);
    }

    public function mergeInput($input)
    {
        foreach ($input as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getRawValue($field)
    {
        if (!isset($this->attributes[$field])) {
            return null;
        }

        return $this->attributes[$field];
    }
}
