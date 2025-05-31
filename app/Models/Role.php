<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    // Use SoftDeletes trait to enable soft deleting of models
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $with = ['permissions'];

    protected $fillable = [
        // The name of the role. This is the name that is used to identify the
        // role in the system.
        'name',

        // The guard name of the role. This is the name of the guard that is
        // used to protect the role.
        'guard_name',

        // A boolean indicating whether the role is active or not.
        'active',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Global scope to exclude super admin, developer and tester roles.
     *
     * The global scope is used to exclude certain roles from the results of the
     * `Role` model. This is useful when we want to prevent certain roles from
     * being displayed in the UI, for example when listing all the roles.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('excludeRoles', function (Builder $builder) {
            $user = Auth::user();

            /**
             * Allow developers to view all roles, otherwise exclude super admin, developer, and tester.
             *
             * @var App\Models\User $user
             */
            if (! $user || ! $user->hasRole('developer')) {
                $builder->whereNotIn('id', [1, 2, 3]);
            }
        });
    }
}
