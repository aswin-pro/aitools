import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

// export interface NavGroup {
//     title: string;
//     items: NavItem[];
// }

export interface NavItem {
    title: string;
    url: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface NavGroup {
    label: string;
    items: NavItem[];
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    [key: string]: unknown;

    flash: {
        success?: string;
        error?: string;
    }

    config: ConfigItem[];

    timezonelist: string[];

    currencies: Currency[];

    dateTimeFormats: Record<string, string>;

    defaultLanguage: string;

    languages: Record<string, string>;

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
    profile_picture?: string;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}


type ConfigItem = {
    id: number;
    config_key: string;
    config_value: string | null;
    status: number;
    created_at: string;
    updated_at: string;
};

export interface Currency {
    id: number;
    priority: number;
    iso_code: string;
    name: string;
    symbol: string;
    subunit: string;
    subunit_to_unit: number;
    symbol_first: number;
    html_entity: string;
    decimal_mark: string;
    thousands_separator: string;
    iso_numeric: string;
    status: number;
    created_at: string;
    updated_at: string;
};

