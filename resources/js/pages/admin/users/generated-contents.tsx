import { DataTablePagination } from "@/components/admin/pagination";
import { LaravelPagination } from "@/types";
import { router } from "@inertiajs/react";

interface GeneratedContent {
    id: number;
    name: string;
    word_count: number;
    updated_at: string;
}

interface GeneratedContentsProps {
    contents: LaravelPagination<GeneratedContent>;
}

export default function GeneratedContents({
    contents,
}: GeneratedContentsProps) {
    return (
        <>
            <div className="rounded-xl border bg-card shadow-sm">
                {/* Header */}
                <div className="border-b px-6 py-4">
                    <h3 className="font-semibold">
                        Latest Generated AI Contents
                    </h3>

                    <p className="mt-1 text-sm text-muted-foreground">
                        Recently generated content by this user
                    </p>
                </div>

                {/* Content */}
                <div className="p-6">
                    {contents.data.length > 0 ? (
                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-2">
                            {contents.data.map((content) => (
                                <div
                                    key={content.id}
                                    className="rounded-lg border p-4 transition-colors hover:bg-muted/50"
                                >
                                    <h4 className="line-clamp-1 font-semibold uppercase">
                                        {content.name}
                                    </h4>

                                    <div className="mt-3 space-y-1 text-sm text-muted-foreground">
                                        <p>
                                            Allocated Words:{" "}
                                            <span className="font-semibold text-foreground">
                                                {content.word_count}
                                            </span>
                                        </p>

                                        <p>
                                            Generated:{" "}
                                            <span className="font-semibold text-foreground">
                                                {content.updated_at}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="py-12 text-center">
                            <p className="font-medium">No contents found</p>

                            <p className="mt-1 text-sm text-muted-foreground">
                                There are no generated contents for this user.
                            </p>
                        </div>
                    )}
                </div>
            </div>
            <div className="mt-6">
                <DataTablePagination
                    pageIndex={contents.current_page - 1}
                    totalPages={contents.last_page}
                    pageSize={contents.per_page}
                    onPageChange={(page) => {
                        router.get(
                            window.location.pathname,
                            {
                                page: page + 1,
                                per_page: contents.per_page,
                                paginate: "content",
                            },
                            {
                                preserveScroll: true,
                                preserveState: true,
                            },
                        );
                    }}
                    onPageSizeChange={(size) => {
                        router.get(
                            window.location.pathname,
                            {
                                page: 1,
                                per_page: size, 
                                paginate: "content",
                            },
                            {
                                preserveScroll: true,
                                preserveState: true,
                            },
                        );
                    }}
                />
            </div>
        </>
    );
}
