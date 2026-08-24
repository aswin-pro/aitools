import { DataTablePagination } from "@/components/admin/pagination";
import { assetUrl } from "@/helpers/asset-url";
import { LaravelPagination } from "@/types";
import { router } from "@inertiajs/react";

interface GeneratedImage {
    id: number;
    name: string;
    n: number;
    size: string;
    format: string;
    result: string;
    updated_at: string;
}

interface GeneratedImagesProps {
    images: LaravelPagination<GeneratedImage>;
}

export default function GeneratedImages({ images }: GeneratedImagesProps) {
    return (
        <>
            <div className="rounded-xl border bg-card shadow-sm">
                {/* Header */}
                <div className="border-b px-6 py-4">
                    <h3 className="font-semibold">
                        Latest Generated AI Images
                    </h3>

                    <p className="mt-1 text-sm text-muted-foreground">
                        Recently generated images by this user
                    </p>
                </div>

                {/* Images */}
                <div className="p-6">
                    {images.data.length > 0 ? (
                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            {images.data.map((image) => {
                                let imageSrc = "";

                                try {
                                    const result = JSON.parse(image.result);

                                    if (image.format === "b64_json") {
                                        imageSrc = `data:image/png;base64,${result[0]?.b64_json ?? ""}`;
                                        console.log("base64", imageSrc);
                                    }

                                    if (image.format === "url") {
                                        imageSrc = assetUrl(result[0] ?? "");
                                    }
                                } catch {
                                    imageSrc = "";
                                }

                                return (
                                    <div
                                        key={image.id}
                                        className="overflow-hidden rounded-lg border transition-colors hover:bg-muted/50"
                                    >
                                        {/* Image */}
                                        {imageSrc ? (
                                            <img
                                                src={assetUrl(imageSrc)}
                                                alt={image.name}
                                                className="aspect-video w-full object-cover"
                                            />
                                        ) : (
                                            <div className="flex aspect-video items-center justify-center bg-muted text-sm text-muted-foreground">
                                                Image unavailable
                                            </div>
                                        )}

                                        {/* Details */}
                                        <div className="p-4">
                                            <h4 className="line-clamp-1 font-semibold uppercase">
                                                {image.name}
                                            </h4>

                                            <div className="mt-2 space-y-1 text-sm text-muted-foreground">
                                                <p>
                                                    Image Count:{" "}
                                                    <span className="font-semibold text-foreground">
                                                        {image.n}
                                                    </span>
                                                </p>

                                                <p>
                                                    Size:{" "}
                                                    <span className="font-semibold text-foreground">
                                                        {image.size}
                                                    </span>
                                                </p>

                                                <p>
                                                    Generated:{" "}
                                                    <span className="font-semibold text-foreground">
                                                        {image.updated_at}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    ) : (
                        <div className="py-12 text-center">
                            <p className="font-medium">No images found</p>

                            <p className="mt-1 text-sm text-muted-foreground">
                                There are no generated images for this user.
                            </p>
                        </div>
                    )}
                </div>
            </div>

            <div className="mt-6">
                <DataTablePagination
                    pageIndex={images.current_page - 1}
                    totalPages={images.last_page}
                    pageSize={images.per_page}
                    onPageChange={(page) => {
                        router.get(
                            window.location.pathname,
                            {
                                page: page + 1,
                                per_page: images.per_page,
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
