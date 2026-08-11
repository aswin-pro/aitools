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

    currencies: Currency[];

    dateTimeFormats: Record<string, string>;

    defaultLanguage: string;

    languages: Record<string, string>;

    selectedLanguages: string[];

    image_limit: {
        SIZE_LIMIT: string;
    };

}