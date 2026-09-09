import { Chat, ChatMessage } from '@/types/user';
import { router } from '@inertiajs/react';
import axios, { AxiosError } from 'axios';

interface SendMessageParams {
    url: string;
    message: string;
}

interface SendMessageResponse {
    chat: Chat;
    chat_id: string;
    user_message: ChatMessage;
    assistant_message: ChatMessage;
}

// Get Laravel CSRF token
function getCsrfToken(): string {
    return (
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content') ?? ''
    );
}

export async function sendSiteAnalyzerMessage({
    url,
    message,
}: SendMessageParams): Promise<SendMessageResponse> {
    try {
        const { data } = await axios.post(
            url,
            { message },
            {
                withCredentials: true,
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
            },
        );

        // Refresh only credits from middleware
        router.reload({
            only: ['credits'],
        });

        return data;
    } catch (error) {
        const axiosError = error as AxiosError<{ message: string }>;

        throw new Error(
            axiosError.response?.data?.message || 'Something went wrong.',
        );
    }
}
