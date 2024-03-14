<?php

namespace App\Traits;

trait HandleSearch
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder|static $query
     * @param string $keyword
     * @param boolean $matchAllFields
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeSearch($query, $isPaginate = true)
    {
        $keyword = request()->query('s');

        $builder = $query->where(function ($query) use ($keyword) {
            foreach (static::getSearchableFields() as $field) {
                $query->orWhere($field, 'LIKE', "%$keyword%");
            }

        });

        if ($isPaginate) {
            $result = $builder->paginate();
            $result->appends(['s' => $keyword]);
            return $result;
        }

        return $builder->get();
    }

    /**
     * Get all searchable fields
     *
     * @return array
     */
    public static function getSearchableFields()
    {
        $model = new static;
        return $model->search;
    }
}
