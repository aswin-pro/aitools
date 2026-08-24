// export interface Currency {
//     id: number;
//     priority: number;
//     iso_code: string;
//     name: string;
//     symbol: string;
//     subunit: string;
//     subunit_to_unit: number;
//     symbol_first: number;
//     html_entity: string;
//     decimal_mark: string;
//     thousands_separator: string;
//     iso_numeric: string;
//     status: number;
//     created_at: string;
//     updated_at: string;
// };

type ConfigItem = {
    id: number;
    config_key: string;
    config_value: string | null;
    status: number;
    created_at: string;
    updated_at: string;
};

export interface systemSetting {

    [key: string]: any;

    config: ConfigItem[];

    timezonelist: string[];

    currencies: Currencies[];

    dateTimeFormats: Record<string, string>;

    defaultLanguage: string;

    languages: Record<string, string>;

    selectedLanguages: string[];

    image_limit: {
        SIZE_LIMIT: string;
    };

}


export interface AuthenticationLog {
    id: number;
    ip_address: string;
    user_agent: string | null;

    location: string | null;
    state_name: string | null;
    city: string | null;
    country: string | null;
    postal_code: string | null;

    platform: string | null;
    browser: string | null;

    login_at: string | null;
    login_successful: number;
    logout_at: string | null;
    cleared_by_user: number;
}



export interface Currencies {
    id: number;
    priority: number;

    iso_code: string | null;
    name: string ;
    symbol: string | null;
    subunit: string | null;
    symbol_first: string | null;

    iso_numeric: string | null;
    subunit_to_unit: string | null;
    html_entity: string | null;
    decimal_mark: string | null;
    thousands_separator: string | null;
}

export interface InvoiceBillingDetails {
    from_billing_name: string;
    from_billing_address: string;
    from_billing_city: string;
    from_billing_state: string;
    from_billing_country: string;
    from_billing_email: string;
    from_billing_phone: string | null;
    from_vat_number: string | null;

    to_billing_name: string;
    to_billing_address: string;
    to_billing_city: string;
    to_billing_state: string;
    to_billing_country: string;
    to_billing_email: string;
    to_billing_phone: string | null;
    to_vat_number: string | null;

    subtotal: number;
    tax_amount: number;
    tax_name: string | null;
    tax_value: number | null;
    applied_coupon?: string | null;
    discounted_price?: number | null;
    invoice_amount: number;
}

export interface Plan {
    id: number;
    is_private: number;
    name: string;
    description: string | null;
    price: number;
    validity: number;
    template_counts: number;
    templates: Record<string, number>;
    max_words: number;
    max_images: number;
    ai_speech_to_text: number;
    ai_text_to_speech: number;
    ai_code: number;
    ai_chatgenius: number;
    ai_docsassist: number;
    ai_webchat: number;
    additional_tools: number;
    recommended: number;
    support: number;
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
    currency: Currencies;
}

export interface User {
    id: number;
    name: string;
    email: string;
    profile_image?: string;
    role_id: number;
    created_at: string;
    updated_at: string;
}

export interface InvoiceTransaction extends Transaction {
    transaction_date: string;
    invoice_details: string;
    billing_details: InvoiceBillingDetails;
}

export interface InvoicePlan {
    id: number;
    name: string;
    price: number;
    validity: number;
}

export interface BlogCategory {
    blog_category_id: string;
    blog_category_title: string;
    blog_category_slug: string;
    published_by: number;
    status: number;
    created_at: string;
    updated_at: string;
    formatted_created_at: string;
}