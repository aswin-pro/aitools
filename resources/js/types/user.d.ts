export interface User {
    id: number;
    name: string;
    email: string;
    profile_image?: string;
    role_id: number;
    lang: string;
    created_at: string;
    updated_at: string;
}

export interface Plan {
    id: number;
    is_private: number;
    name: string;
    description: string | null;
    price: number;
    validity: number;
    content_templates_count: number;
    content_templates: Record<string, number>;
    ai_credits: number;
    ai_image_credits: number;
    speech_to_text: number;
    text_to_speech: number;
    code_generator: number;
    personalized_chat: number;
    document_analyzer: number;
    site_analyzer: number;
    is_recommended: number;
    customer_support: number;
    formatted_price: string;
}

export interface Currency {
    iso_code: string;
    symbol: string;
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
    formatted_amount: string;
    billing_details: Record<string, string | number>;
    transaction_date: string;
}

export interface Feature {
    key: string;
    label: string;
    type: string;
}

export interface ContentTemplateCategory {
    id: number;
    category_name: string;
}

export interface ContentTemplate {
    id: number;
    name: string;
    description: string;
    unique_slug: string;
    status: number;
    category: TemplateCategory;
    is_available?: boolean;
}

export interface ContentTemplateField {
    id: number;
    template_id: number;
    ai_input: string;
    field_type: 'textarea' | 'input';
    field_name: string;
    field_description: string;
    status: number;
}

export interface AIContent {
    id: number;
    generation_id: string;
    name: string;
    type: string;
    lang: string;
    content: string;
    word_count: string;
    template: Template;
    formatted_created_at: string;
}

export interface AIImage {
    id: number;
    generation_id: string;
    name: string;
    type: string;
    prompt: string;
    n: number;
    size: string;
    format: string;
    result: string[];
    formatted_created_at: string;
}

export interface ChatAssistant {
    id: number;
    chat_assistant_id: string;
    chat_assistant_image: string;
    chat_assistant_name: string;
    chat_assistant_expert: string;
    chat_assistant_description: string;
    chat_assistant_message: string;
    status: string;
}

export interface Chat {
    id: number;
    chat_id: string;
    chat_type: string;
    attachment: string;
    generated_by: number;
    chat_assistant_id: string;
    chat_title: string;
    word_count: number;
}

export interface ChatMessage {
    id: number;
    chat_message_id: string;
    chat_id: string;
    responsed_by: 'user' | 'system';
    chat_message: string;
    formatted_created_at: string;
}

interface SpeechRecognitionEvent extends Event {
    resultIndex: number;
    results: SpeechRecognitionResultList;
}

export interface SpeechRecognition {
    continuous: boolean;
    interimResults: boolean;
    lang: string;
    start(): void;
    stop(): void;
    onstart: (() => void) | null;
    onend: (() => void) | null;
    onresult: ((event: SpeechRecognitionEvent) => void) | null;
    maxAlternatives: number;
}

interface SpeechRecognitionConstructor {
    new (): SpeechRecognition;
}

declare global {
    interface Window {
        SpeechRecognition?: SpeechRecognitionConstructor;
        webkitSpeechRecognition?: SpeechRecognitionConstructor;
    }
}

export interface UserUpload {
    id: number;
    upload_id: string;
    user_id: string;
    file_name: string;
    file_type: string;
    file_url: string;
    file_size: number;
    formatted_created_at: string;
}

export interface UseChatWindowParams {
    initialChatId: string | null;
    initialChats: Chat[];
    initialMessages: ChatMessage[];
}

export interface ChatWindowState {
    chatId: string | null;
    conversation: ChatMessage[];
    filteredChats: Chat[];
    input: string;
    sending: boolean;
    search: string;
    setInput: (value: string) => void;
    setSearch: (value: string) => void;
    sendMessage: () => void;
}

interface ChatLayoutProps {
    t: (key: string) => string;
    typingLabel: ReactNode;
    emptyState: ReactNode;
    chats: Chat[];
    chatState: ChatWindowState;
    newChatHref: string;
    chatHref: (chatId: string) => string;
    onDeleteChat: (
        chatId: string,
        setDeleting: React.Dispatch<React.SetStateAction<boolean>>,
    ) => void | Promise<void>;
    renderEditForm: (chat: Chat | undefined, close: () => void) => ReactNode;
}

// Section Card
export interface SectionCard {
    title: string;
    value: number | string | Record<string, string>;
    description: string | Record<string, string>;
}

export interface LanguageList {
    lang_name: string;
    lang_key: string;
}

export interface Summary {
    subscription: {
        plan_name: string;
        validity: string;
    };
    ai_credits: {
        total: number;
        used: number;
    };
    ai_image_credits: {
        total: number;
        used: number;
    };
}

export type ChartData = {
    month: string;
    ai_credits: number;
};

export interface Chart {
    ai_credits_chart: {
        data: ChartData[];
    };
    ai_image_credits_chart: {
        data: ChartData[];
    };
}
