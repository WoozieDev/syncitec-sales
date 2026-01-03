// User role names (frontend reference)
export type UserRole =
  | "superadmin"
  | "admin"
  | "manager"
  | "seller"
  | "customer"
  | "support";

// User row (list / index)
export interface UserListItem {
  id: number;
  name: string;
  email: string;
  is_active: boolean;
  deleted_at?: string | null;
  roles: UserRole[];
  created_at?: string;
}

// User detail (show / edit)
export interface UserDetail {
  id: number;
  name: string;
  email: string;
  is_active: boolean;
  roles: UserRole[];
  created_at?: string;
}

// Filters for index
export interface UserFilters {
  search?: string;
  role?: string | null;
  status?: "active" | "inactive" | null;
  trashed?: "without" | "with" | "only";
}

// Role option (for selects)
export interface RoleOption {
  id: number;
  name: UserRole;
}
