import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';
import { InputHTMLAttributes } from 'react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    url: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
    permissions: string[];
}

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

type SelectOption = {
    label: string;
    value: string;
};

export type FieldRenderType =
    | 'input'
    | 'input-group'
    | 'text-area'
    | 'select'
    | 'date-picker'
    | 'checkbox';

type DynamicValue<T, V> = V | ((item: T) => V);

type FieldProps<T> = Omit<InputHTMLAttributes<HTMLInputElement>, 'max'> & {
    options?: SelectOption[];
    onValueChange?: (value: string) => void;
    max?: DynamicValue<T, React.InputHTMLAttributes<HTMLInputElement>['max']>;
};

export type FieldType<T = never> = {
    show?: boolean;
    id: string;
    label?: string;
    fieldType: FieldRenderType;
    required?: boolean;
    password?: boolean;

    props: FieldProps<T>;

    inputGroup?: {
        align: 'inline-start' | 'inline-end';
        content: DynamicValue<T, React.ReactNode>;
    };

    value?: DynamicValue<
        T,
        React.InputHTMLAttributes<HTMLInputElement>['value']
    >;
};

export interface User {
    user_id: string;
    name: string;
    email: string;
    phone?: string;
    avatar?: string;
    email_verified_at: string | null;
    permissions: string[];
    status: string;
    formatted_created_at: string;
}

export interface Customer {
    customer_id: string;
    company_name: string;
    name: string;
    email: string;
    mobile_number: string;
    alternative_mobile_number?: string;
    address?: string;
    business_address: string;
    bank_name?: string;
    bank_account_holder_name?: string;
    bank_account_number?: string;
    ifsc_code?: string;
    upi_id?: string;
    status: string;
    formatted_created_at: string;
}

export interface Supplier {
    supplier_id: string;
    company_name: string;
    name: string;
    email: string;
    mobile_number: string;
    alternative_mobile_number?: string;
    address?: string;
    business_address: string;
    bank_name?: string;
    bank_account_holder_name?: string;
    bank_account_number?: string;
    ifsc_code?: string;
    upi_id?: string;
    status: string;
    formatted_created_at: string;
}

export interface ProductCategory {
    product_category_id: string;
    category_name: string;
    color: string;
    status: string;
    formatted_created_at: string;
}

export interface MeasurementUnit {
    measurement_unit_id: string;
    unit_name: string;
    unit: string;
    status: string;
    formatted_created_at: string;
}

export interface RequiredMaterial {
    product_id: string;
    quantity: number | string;
}

export interface ProductionOutput {
    production_output_id?: string;
    production_id?: string;
    product_id: string;
    quantity: number;
}

export interface Product {
    product_id: string;
    product_category_id: string;
    category: ProductCategory;
    measurement_unit_id: string;
    measurement_unit: MeasurementUnit;
    inventory_sum_stock: number;
    product_name: string;
    purchase_price: number;
    selling_price: number;
    tax_percentage: number;
    required_materials: RequiredMaterial[] | null;
    status: string;
    formatted_created_at: string;
}

export interface Company {
    company_id: string;
    company_name: string;
    email?: string;
    mobile_number: string;
    address?: string;
    gstin?: string;
    status: string;
    formatted_created_at: string;
}

export interface Purchase {
    id: string;
    purchase_history_id: string;
    company_id: string;
    company: Company;
    supplier_id: string;
    supplier_name: string;
    supplier_phone: string;
    billing_address?: string;
    payment_mode: string;
    subtotal: number;
    discount: number;
    grand_total: number;
    status: string;
    created_at: string;
    formatted_created_at: string;
    purchase_items: PurchaseItem[];
}

export interface PurchaseItem {
    purchase_item_id?: string;
    purchase_history_id?: string;
    product_id: string;
    product_name?: string;
    category?: string;
    measurement_unit?: string;
    quantity: number;
    unit_price: number;
    discount: number;
    tax_percentage: number;
    tax_amount?: number;
    total?: number;
}

export type PurchaseItemColumn = {
    key: keyof PurchaseItem | 'total';
    type: 'select' | 'input';
    props: {
        // Common
        readonly?: boolean;

        // Input
        inputType?: 'text' | 'number';
        min?: number;
        step?: string;

        // Select
        placeholder?: string;
        options?: {
            label: string;
            value: string;
        }[];

        // Input Group
        inputGroup?: {
            align: 'inline-start' | 'inline-end';
            value: string;
        };
    };
    value?: (item: PurchaseItem) => string;
};

export interface Sale {
    id: string;
    sale_history_id: string;
    company_id: string;
    company: Company;
    customer_id: string;
    customer_name: string;
    customer_phone: string;
    billing_address?: string;
    payment_mode: string;
    subtotal: number;
    discount: number;
    grand_total: number;
    transportation_details?: Record<string, string>;
    status: string;
    created_at: string;
    formatted_created_at: string;
    sale_items: SaleItem[];
}

export interface SaleItem {
    sale_item_id?: string;
    sale_history_id?: string;
    product_id: string;
    product_name?: string;
    category?: string;
    measurement_unit?: string;
    quantity: number;
    unit_price: number;
    discount: number;
    tax_percentage: number;
    tax_amount?: number;
    total?: number;
}

export type SaleItemColumn = {
    key: keyof SaleItem | 'total';
    type: 'select' | 'input';
    props: {
        // Common
        readonly?: boolean;

        // Input
        inputType?: 'text' | 'number';
        min?: number;
        step?: string;

        // Select
        placeholder?: string;
        options?: {
            label: string;
            value: string;
        }[];

        // Input Group
        inputGroup?: {
            align: 'inline-start' | 'inline-end';
            value: string;
        };
    };
    value?: (item: PurchaseItem) => string;
};

export interface ExpenseCategory {
    expense_category_id: string;
    category_name: string;
    status: string;
    formatted_created_at: string;
}

export interface Expense {
    expense_id: string;
    expense_category_id: string;
    category: ExpenseCategory;
    company_id: string;
    company: Company;
    notes?: string;
    amount: number;
    status: string;
    formatted_created_at: string;
}

export interface PurchasePayment {
    purchase_payment_id: string;
    purchase_history_id: string;
    payment_mode: string;
    payment_date: string;
    amount: number;
    status: string;
    formatted_created_at: string;
}

export interface SalePayment {
    sale_payment_id: string;
    sale_history_id: string;
    payment_mode: string;
    payment_date: string;
    amount: number;
    status: string;
    formatted_created_at: string;
}

export type RequiredMaterialsItemColumn = {
    key: string;
    type: 'select' | 'number';
    props: {
        min?: number;
        step?: string;
        placeholder?: string;
        options?: {
            label: string;
            value: string;
        }[];
        inputGroup?: {
            align: 'inline-start' | 'inline-end';
            value: string;
        };
    };
    value?: (item: Product) => string;
};

export type ProductionItemColumn = RequiredMaterialsItemColumn;

export type ProductionDeliveryColumn = RequiredMaterialsItemColumn;

export type CalculateColumn = RequiredMaterialsItemColumn;

type RequiredMaterialProduct = {
    product_id: string;
    product_name: string;
    stock: number;
    required_qty: number;
    balance_qty: number;
    measuring_unit: string;
};

// Employee
export interface Employee {
    employee_id: string;
    name: string;
    email?: string;
    mobile_number: string;
    alternative_mobile_number?: string;
    address?: string;
    employee_type: string;
    per_day_wage: number;
    gender: string;
    dob?: string;
    aadhaar_number?: string;
    pan_number?: string;
    status: string;
    formatted_created_at: string;
}

// Production
export interface Production {
    production_id: string;
    production_type: string;
    company_id: string;
    company: Company;
    employee_id: string;
    employee: Employee;
    supplier_id: string;
    supplier: Supplier;
    production_cost: number;
    production_date: string;
    expected_delivery_date: string;
    delivery_date: string;
    notes?: string;
    production_status: string;
    status: string;
    formatted_created_at: string;
    production_inputs?: ProductionItem[];
    production_outputs?: ProductionItem[];
}

// Production Item
export interface ProductionItem {
    product_id: string;
    quantity: number;
}

// Section Card
export interface SectionCard {
    title: string;
    description: string;
    value: number | string;
}
