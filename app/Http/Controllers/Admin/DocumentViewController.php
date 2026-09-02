<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationDocument;
use Illuminate\Support\Facades\Storage;

class DocumentViewController extends Controller
{
    // Reached only via routes/admin-web.php, already behind 'auth' + 'admin'
    // middleware — that's what actually makes this safe, not the URL being
    // hard to guess.
    public function show(VerificationDocument $document)
    {
        abort_unless(
            Storage::disk('private')->exists($document->file_path),
            404
        );

        return Storage::disk('private')->response($document->file_path);
    }
}
