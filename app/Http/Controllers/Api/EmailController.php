<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailResource;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $folder = $request->query('folder', 'INBOX');

        $emails = $request->user()->emails()
            ->where('folder', strtoupper($folder))
            ->orderBy('date', 'desc')
            ->paginate(50);

        return EmailResource::collection($emails);
    }

    /**
     * Display the specified resource.
     */
    public function show(Email $email)
    {
        if ($email->user_id !== Auth::id()) {
            abort(403);
        }

        return new EmailResource($email);
    }
}
