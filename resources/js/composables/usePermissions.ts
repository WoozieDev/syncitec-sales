import type { AuthUser } from '@/types/auth';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

type PageProps = {
    auth?: {
        user?: AuthUser | null;
    };
};

export function usePermissions() {
    const page = usePage<PageProps>();

    const user = computed(() => page.props.auth?.user ?? null);

    const roles = computed<string[]>(() => user.value?.roles ?? []);
    const permissions = computed<string[]>(() => user.value?.permissions ?? []);

    const hasRole = (role: string) => roles.value.includes(role);

    const hasAnyRole = (list: string[]) =>
        list.some((r) => roles.value.includes(r));

    const can = (permission: string) => permissions.value.includes(permission);

    const canAny = (list: string[]) =>
        list.some((p) => permissions.value.includes(p));

    const isAuthenticated = computed(() => !!user.value);

    return {
        user,
        roles,
        permissions,
        isAuthenticated,
        hasRole,
        hasAnyRole,
        can,
        canAny,
    };
}
