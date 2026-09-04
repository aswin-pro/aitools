export interface TranslationLanguage {
    name: string;
    code: string;
    type?: string;
}

export interface TranslationEditorRow {
    id: string;
    category: string;
    key: string;
    source: string;
    translation: string;
}