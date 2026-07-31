<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\RbacPermissions;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect(RbacPermissions::labels())
            ->mapWithKeys(fn (string $name, string $slug) => [
                $slug => Permission::updateOrCreate(['slug' => $slug], ['name' => $name]),
            ]);

        foreach (RbacPermissions::roleLabels() as $slug => $name) {
            $role = Role::updateOrCreate(['slug' => $slug], ['name' => $name]);
            $role->permissions()->sync(
                $permissions
                    ->only(RbacPermissions::rolePermissions()[$slug])
                    ->pluck('id')
                    ->all()
            );
        }

        $demoUser = User::where('email', 'demo@example.com')->first();

        if ($demoUser) {
            $demoUser->roles()->syncWithoutDetaching([
                Role::where('slug', RbacPermissions::ADMIN)->value('id'),
            ]);
        }
    }
}
