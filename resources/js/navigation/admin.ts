export type NavItem = {
    label: string;
    href: string;
    icon?: string;
    rolesAny?: string[];
    permissionsAny?: string[];
};

export const adminNav: NavItem[] = [
    { 
        label: 'Dashboard', 
        href: '/admin' 
    },
    {
        label: 'Ventas POS',
        href: '/admin/sales',
        permissionsAny: ['sales.view'],
    },
    {
        label: 'Pedidos',
        href: '/admin/orders',
        permissionsAny: ['orders.view'],
    },

    {
        label: 'Productos',
        href: '/admin/products',
        permissionsAny: ['products.view'],
    },
    {
        label: 'Categorías',
        href: '/admin/categories',
        permissionsAny: ['categories.view'],
    },
    { 
        label: 'Marcas', 
        href: '/admin/brands', 
        permissionsAny: ['brands.view'] 
    },
    {
        label: 'Inventario',
        href: '/admin/inventory',
        permissionsAny: ['inventory.view'],
    },

    {
        label: 'Cupones',
        href: '/admin/coupons',
        permissionsAny: ['coupons.view'],
    },
    {   
        label: 'Usuarios', 
        href: '/admin/users', 
        permissionsAny: ['users.view'] 
    },
    {   
        label: 'Roles', 
        href: '/admin/roles', 
        permissionsAny: ['roles.view'] 
    },
    {
        label: 'Configuración',
        href: '/admin/settings',
        permissionsAny: ['settings.manage'],
    },
];
