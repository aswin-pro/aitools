import { DataTablePagination } from '@/components/table/pagination';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { assetUrl } from '@/helpers/asset-url';
import { ChatAssistant } from '@/types/user';
import { Link } from '@inertiajs/react';

export const PaginatedAssistants = ({
    t,
    assistants,
    pageIndex,
    pageSize,
    totalCount,
    onPageChange,
    onPageSizeChange,
}: {
    t: (key: string) => string;
    assistants: ChatAssistant[];
    pageIndex: number;
    pageSize: number;
    totalCount: number;
    onPageChange: (page: number) => void;
    onPageSizeChange?: (size: number) => void;
}) => {
    // total pages
    const totalPages = Math.ceil(totalCount / pageSize);

    return (
        <div className="mt-5 space-y-5">
            <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                {assistants.map((assistant) => (
                    <Card key={assistant.id} className="flex h-full flex-col">
                        <CardContent className="flex h-full flex-col justify-between">
                            <div className="flex flex-col items-center">
                                {/* Image */}
                                <img
                                    src={assetUrl(assistant.chat_assistant_image)}
                                    alt={assistant.chat_assistant_name}
                                    className="h-24 w-24 rounded-2xl object-cover"
                                />

                                {/* Assistant Name */}
                                <h3 className="mt-2 text-center text-base font-bold">
                                    {assistant.chat_assistant_name}
                                </h3>

                                {/* Expertise */}
                                <h4 className="text-center text-xs font-medium text-muted-foreground">
                                    {assistant.chat_assistant_expert}
                                </h4>

                                {/* Description */}
                                <p className="mt-2 line-clamp-2 text-center text-sm text-muted-foreground">
                                    {assistant.chat_assistant_description}
                                </p>
                            </div>

                            <Link
                                href={route(
                                    'dashboard.user.personalized-chat.chat',
                                    {
                                        assistant: assistant.chat_assistant_id,
                                    },
                                )}
                            >
                                <Button type="button" className="mt-4 w-full">
                                    {t('Chat')}
                                </Button>
                            </Link>
                        </CardContent>
                    </Card>
                ))}
            </div>

            {/* Pagination */}
            <DataTablePagination
                t={t}
                pageIndex={pageIndex}
                totalPages={totalPages}
                pageSize={pageSize}
                onPageChange={onPageChange}
                onPageSizeChange={onPageSizeChange}
                pageSizeOptions={[9, 12, 15, 18]}
            />
        </div>
    );
};
