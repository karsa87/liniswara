<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportDetail extends Model
{
    use HasFactory, ScopeLike;

    protected $guarded = [];

    protected $connection = 'mysql_log';

    protected $table = 'import_logs';

    protected $casts = [
        'reason' => 'array',
    ];

    /**
     * The data that belong to the file.
     */
    public function import(): BelongsTo
    {
        return $this->setConnection('mysql_log')->belongsTo(Import::class);
    }
}
