<?php

namespace App\Support;

final class RbacPermissions
{
    public const ADMIN = 'admin';

    public const FINANCE = 'finance';

    public const PROJECT_MANAGER = 'project-manager';

    public const VIEWER = 'viewer';

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            'dashboard.view' => 'View dashboard',
            'clients.view' => 'View clients',
            'clients.create' => 'Create clients',
            'clients.update' => 'Update clients',
            'clients.delete' => 'Delete clients',
            'projects.view' => 'View projects',
            'projects.create' => 'Create projects',
            'projects.update' => 'Update projects',
            'projects.delete' => 'Delete projects',
            'invoices.view' => 'View invoices',
            'invoices.create' => 'Create invoices',
            'invoices.update' => 'Update invoices',
            'invoices.delete' => 'Delete invoices',
            'payments.create' => 'Create payments',
            'payments.update' => 'Update payments',
            'payments.delete' => 'Delete payments',
            'reports.view' => 'View reports',
            'reports.export' => 'Export reports',
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function rolePermissions(): array
    {
        return [
            self::ADMIN => array_keys(self::labels()),
            self::FINANCE => [
                'dashboard.view',
                'invoices.view',
                'invoices.create',
                'invoices.update',
                'invoices.delete',
                'payments.create',
                'payments.update',
                'payments.delete',
                'reports.view',
                'reports.export',
            ],
            self::PROJECT_MANAGER => [
                'dashboard.view',
                'clients.view',
                'clients.create',
                'clients.update',
                'clients.delete',
                'projects.view',
                'projects.create',
                'projects.update',
                'projects.delete',
            ],
            self::VIEWER => [
                'dashboard.view',
                'clients.view',
                'projects.view',
                'invoices.view',
                'reports.view',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function roleLabels(): array
    {
        return [
            self::ADMIN => 'Admin',
            self::FINANCE => 'Finance',
            self::PROJECT_MANAGER => 'Project Manager',
            self::VIEWER => 'Viewer',
        ];
    }
}
