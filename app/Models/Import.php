<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends Model
{
    use HasFactory, ScopeLike;

    protected $guarded = [];

    protected $connection = 'mysql_log';

    protected $table = 'imports';

    /**
     * The data that belong to the file.
     */
    public function logs(): HasMany
    {
        return $this->setConnection('mysql_log')->hasMany(ImportDetail::class, 'import_id');
    }

    /**
     * The data that belong to the user.
     */
    public function file(): BelongsTo
    {
        return $this->setConnection(config('database.default'))->belongsTo(File::class, 'file_id');
    }

    /**
     * The data that belong to the user.
     */
    public function user(): BelongsTo
    {
        return $this->setConnection(config('database.default'))->belongsTo(User::class, 'user_id');
    }
}
