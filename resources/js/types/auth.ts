export type RoleName =
  | "superadmin"
  | "admin"
  | "manager"
  | "seller"
  | "customer"
  | "support";

export interface AuthUser {
  id: number;
  name: string;
  email: string;
  roles?: string[];        // provided via Inertia shared props
  permissions?: string[];  // optional: can be derived via backend
  is_active?: boolean;
}

export interface AuthProps {
  user: AuthUser | null;
}
