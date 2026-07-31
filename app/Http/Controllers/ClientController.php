<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::withCount('projects')->latest()->paginate(10);

        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = Client::create($request->validated());

        return redirect()->route('clients.show', $client)->with('status', 'Client berhasil dibuat.');
    }

    public function show(Client $client): View
    {
        $client->load([
            'documents' => fn ($query) => $query->with('uploadedBy')->latest(),
            'projects' => fn ($query) => $query->latest(),
        ]);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return redirect()->route('clients.show', $client)->with('status', 'Client berhasil diperbarui.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        if ($client->activeProjects()->exists()) {
            return back()->withErrors([
                'client' => 'Client tidak dapat dihapus karena masih memiliki project Active.',
            ]);
        }

        $client->delete();

        return redirect()->route('clients.index')->with('status', 'Client berhasil dihapus.');
    }
}
