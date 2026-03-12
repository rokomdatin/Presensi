<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    use HasFactory;

    protected $table = 'import_logs';

    protected $fillable = [
        'user_id',
        'nama_file',
        'total_rows',
        'success_rows',
        'failed_rows',
        'error_details',
        'status',
    ];

    protected $casts = [
        'error_details' => 'array',
        'total_rows' => 'integer',
        'success_rows' => 'integer',
        'failed_rows' => 'integer',
    ];

    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
