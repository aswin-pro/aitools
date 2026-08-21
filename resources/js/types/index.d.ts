import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    route: string;
    url: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface NavGroup {
    label: string;
    items: NavItem[];
}

type Settings = {
    site_logo: string | null;
    site_logo_light: string | null;
    favicon: string | null;
};  

export interface SharedData {
    name: string;
    auth: Auth;
    [key: string]: unknown;
    role: number;

    upload: {
        size_limit: number;
    };

    flash: {
        success?: string;
        error?: string;
    }

    settings: Settings;


}

export interface ProfileForm {
    name: string;
    email: string;
    profile_picture: File | null;
}


export interface User {
    id: number;
    name: string;
    email: string;
    profile_image?: string;
    role_id: number;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
}

export interface NavigateParams {
    page?: number;
    per_page?: number;
    search?: string;    
    [key: string]: FormDataConvertible;
}

export interface LaravelPagination<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
}

export interface Transaction {
    id: number;
    transaction_id: string;
    user_id: number;
    plan_id: number;
    description: string;
    payment_gateway_name: string;
    transaction_currency: string;
    transaction_amount: number;
    invoice_number: number;
    invoice_prefix: string;
    invoice_details: string;
    payment_status: string;
    status: string;
    formatted_created_at: string;
    user: User;
    plan: Plan;
    currency: Currency;
    formatted_amount: string;
}






