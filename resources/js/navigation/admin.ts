import {
    BadgePercent,
    Boxes,
    KeyRound,
    LayoutDashboard,
    Package,
    Receipt,
    Settings,
    Shield,
    ShoppingCart,
    Tags,
    Users,
} from 'lucide-vue-next';
import type { Component } from 'vue';

export type NavItem = {
    label: string;
    href: string;
    icon: Component; // ✅ componente, no string
    rolesAny?: string[];
    permissionsAny?: string[];
};

export const adminNav: NavItem[] = [
    {
        label: 'Dashboard',
        href: '/admin',
        icon: LayoutDashboard,
    },
    {
        label: 'Ventas POS',
        href: '/admin/sales',
        icon: ShoppingCart,
        permissionsAny: ['sales.view'],
    },
    {
        label: 'Pedidos',
        href: '/admin/orders',
        icon: Receipt,
        permissionsAny: ['orders.view'],
    },

    {
        label: 'Productos',
        href: '/admin/products',
        icon: Package,
        permissionsAny: ['products.view'],
    },
    {
        label: 'Categorías',
        href: '/admin/categories',
        icon: Tags,
        permissionsAny: ['categories.view'],
    },
    {
        label: 'Marcas',
        href: '/admin/brands',
        icon: Tags,
        permissionsAny: ['brands.view'],
    },
    {
        label: 'Inventario',
        href: '/admin/inventory',
        icon: Boxes,
        permissionsAny: ['inventory.view'],
    },

    {
        label: 'Cupones',
        href: '/admin/coupons',
        icon: BadgePercent,
        permissionsAny: ['coupons.view'],
    },

    {
        label: 'Usuarios',
        href: '/admin/users',
        icon: Users,
        permissionsAny: ['users.view'],
    },
    {
        label: 'Roles',
        href: '/admin/roles',
        icon: Shield,
        permissionsAny: ['roles.view'],
    },
    {
        label: 'Permisos',
        href: '/admin/permissions',
        icon: KeyRound,
        permissionsAny: ['permissions.view'],
    },

    {
        label: 'Configuración',
        href: '/admin/settings',
        icon: Settings,
        permissionsAny: ['settings.manage'],
    },
];
