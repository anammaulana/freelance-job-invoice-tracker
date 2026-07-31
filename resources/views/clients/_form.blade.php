@csrf
<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="name" class="block text-sm font-medium text-zinc-700">Name</label>
        <input id="name" name="name" value="{{ old('name', $client->name ?? '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-zinc-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $client->email ?? '') }}" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="phone_number" class="block text-sm font-medium text-zinc-700">Phone number</label>
        <input id="phone_number" name="phone_number" value="{{ old('phone_number', $client->phone_number ?? '') }}" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="company" class="block text-sm font-medium text-zinc-700">Company</label>
        <input id="company" name="company" value="{{ old('company', $client->company ?? '') }}" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div class="md:col-span-2">
        <label for="address" class="block text-sm font-medium text-zinc-700">Address</label>
        <textarea id="address" name="address" rows="4" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('address', $client->address ?? '') }}</textarea>
    </div>
</div>
