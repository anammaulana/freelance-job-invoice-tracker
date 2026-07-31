<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Models\Client;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\User;
use App\Services\DocumentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function __construct(private readonly DocumentService $documents) {}

    public function storeForProject(StoreDocumentRequest $request, Project $project): RedirectResponse
    {
        $this->authorizeDocumentWrite($request->user(), $project);
        $this->documents->store($project, $request->file('document'), $request->user());

        return back()->with('status', 'Document berhasil diunggah.');
    }

    public function storeForTask(StoreDocumentRequest $request, Project $project, ProjectTask $task): RedirectResponse
    {
        abort_unless($task->project_id === $project->id, 404);

        $this->authorizeDocumentWrite($request->user(), $task);
        $this->documents->store($task, $request->file('document'), $request->user());

        return back()->with('status', 'Document berhasil diunggah.');
    }

    public function storeForClient(StoreDocumentRequest $request, Client $client): RedirectResponse
    {
        $this->authorizeDocumentWrite($request->user(), $client);
        $this->documents->store($client, $request->file('document'), $request->user());

        return back()->with('status', 'Document berhasil diunggah.');
    }

    public function storeForInvoice(StoreDocumentRequest $request, Invoice $invoice): RedirectResponse
    {
        $this->authorizeDocumentWrite($request->user(), $invoice);
        $this->documents->store($invoice, $request->file('document'), $request->user());

        return back()->with('status', 'Document berhasil diunggah.');
    }

    public function storeForPayment(StoreDocumentRequest $request, Invoice $invoice, Payment $payment): RedirectResponse
    {
        abort_unless($payment->invoice_id === $invoice->id, 404);

        $this->authorizeDocumentWrite($request->user(), $payment);
        $this->documents->store($payment, $request->file('document'), $request->user());

        return back()->with('status', 'Document berhasil diunggah.');
    }

    public function download(Document $document): StreamedResponse|Response
    {
        $this->authorizeDocumentRead(request()->user(), $document->attachable);

        abort_unless(Storage::disk($document->disk)->exists($document->stored_path), 404);

        return Storage::disk($document->disk)->download($document->stored_path, $document->original_filename);
    }

    public function destroy(Document $document): RedirectResponse
    {
        $this->authorizeDocumentWrite(request()->user(), $document->attachable);
        $this->documents->delete($document);

        return back()->with('status', 'Document berhasil dihapus.');
    }

    private function authorizeDocumentRead(?User $user, ?Model $attachable): void
    {
        abort_unless($user && $attachable, 403);

        $permission = match ($attachable::class) {
            Project::class => 'projects.view',
            ProjectTask::class => 'project-workflow.view',
            Client::class => 'clients.view',
            Invoice::class, Payment::class => 'invoices.view',
            default => null,
        };

        abort_unless($permission && $user->hasPermission($permission), 403);
    }

    private function authorizeDocumentWrite(?User $user, Model $attachable): void
    {
        abort_unless($user?->hasPermission('documents.manage'), 403);

        $permission = match ($attachable::class) {
            Project::class, ProjectTask::class => 'project-workflow.manage',
            Client::class => 'clients.update',
            Invoice::class => 'invoices.update',
            Payment::class => 'payments.update',
            default => null,
        };

        abort_unless($permission && $user->hasPermission($permission), 403);
    }
}
