<?php

namespace App\Scopes;

use App\UserGroup;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class SchoolCategoryScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $userGroupId = Auth::user()->user_group_id ?? false;

        $user = Auth::user();
        if ($user->userable_type === 'App\Student') {
            return;
        }

        $userGroup = $user->userGroup()->first();
        if ($userGroup) {
            $schoolCategories = $userGroup->schoolCategories()->get()->pluck(['id']);
            $builder->whereIn('school_category_id', $schoolCategories);
        }
    }
}