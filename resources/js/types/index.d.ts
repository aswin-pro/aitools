import { PageProps as InertiaPageProps } from '@inertiajs/core';
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
    url: string;
    route: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData extends InertiaPageProps {
    name: string;
    auth: Auth;
    upload_limit: number;
    flash: {
        success?: string;
        error?: string;
    };
    role: number;
    logo_light?: string;
    logo_dark?: string;
    favicon?: string;
    credits: {
        ai_credits: {
            total: number;
            used: number;
        };
        ai_image_credits: {
            total: number;
            used: number;
        };
    };
    theme: string;
}

export type FieldRenderType =
    | 'input'
    | 'input-group'
    | 'text-area'
    | 'select'
    | 'date-picker'
    | 'checkbox';

type DynamicValue<T, V> = V | ((item: T) => V);

type SelectOption = {
    label: string;
    value?: string;
    items?: {
        label: string;
        value: string;
    }[];
};

type FieldProps = React.InputHTMLAttributes<
    HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement
> & {
    options?: SelectOption[];
    onValueChange?: (value: string) => void;
};

export type FieldType<T = never> = {
    id: string;
    show?: boolean;
    label?: string;
    fieldType: FieldRenderType;
    props: FieldProps<T>;
    inputGroup?: {
        align: 'inline-start' | 'inline-end';
        content: DynamicValue<T, React.ReactNode>;
    };
    value?: DynamicValue<
        T,
        React.InputHTMLAttributes<HTMLInputElement>['value']
    >;
    className?: string;
    helperText?: string;
};

export interface NavigateParams {
    [key: string]: FormDataConvertible;

    page?: number;
    per_page?: number;
    search?: string;
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
    lang: string;
    [key: string]: unknown;
}






