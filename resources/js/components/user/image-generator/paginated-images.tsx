import { CustomBadge } from '@/components/custom-badge';
import { DataTablePagination } from '@/components/table/pagination';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { assetUrl } from '@/helpers/asset-url';
import { cn } from '@/lib/utils';
import { AIImage } from '@/types/user';
import {
    Download,
    Kanban,
    LayoutGrid,
    MoreVertical,
    Trash2,
} from 'lucide-react';
import { useState } from 'react';

export const PaginatedImages = ({
    t,
    images,
    pageIndex,
    pageSize,
    totalCount,
    onPageChange,
    onPageSizeChange,
    setGenerationId,
    setDialogOpen,
}: {
    t: (key: string) => string;
    images: AIImage[];
    pageIndex: number;
    pageSize: number;
    totalCount: number;
    onPageChange: (page: number) => void;
    onPageSizeChange?: (size: number) => void;
    setGenerationId: (id: string | null) => void;
    setDialogOpen: (dialogOpen: boolean) => void;
}) => {
    // total pages
    const totalPages = Math.ceil(totalCount / pageSize);

    // states
    const [view, setView] = useState<'grid' | 'masonry'>('grid');

    // image card
    const ImageCard = ({
        image,
        view,
    }: {
        image: AIImage;
        view: 'grid' | 'masonry';
    }) => (
        <div
            key={image.generation_id}
            className={cn(
                'relative overflow-hidden rounded-2xl border border-sidebar-border/70 shadow-xs dark:border-sidebar-border',
                view === 'grid'
                    ? 'aspect-square'
                    : cn(
                          'mb-4 break-inside-avoid',
                          (image.size === '1024x1024' ||
                              image.size === '1:1') &&
                              'aspect-square',
                          (image.size === '1024x1792' ||
                              image.size === '9:16') &&
                              'aspect-[3/4]',
                          (image.size === '1792x1024' ||
                              image.size === '16:9') &&
                              'aspect-[4/3]',
                      ),
            )}
        >
            {/* style */}
            <div className="absolute inset-s-2.5 top-2.5">
                <CustomBadge type="info" className="px-3.5 py-1">
                    {t(image.type)}
                </CustomBadge>
            </div>

            {/* options */}
            <div className="absolute inset-e-2.5 top-2.5">
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button
                            type="button"
                            variant="outline"
                            size="icon"
                            className="rounded-full"
                        >
                            <MoreVertical className="h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        {/* Download */}
                        <DropdownMenuItem
                            onClick={() =>
                                downloadImage(image.result[0], image.name)
                            }
                        >
                            <Download className="h-4 w-4" />
                            {t('Download')}
                        </DropdownMenuItem>

                        {/* Seperator */}
                        <DropdownMenuSeparator />

                        {/* Delete */}
                        <DropdownMenuItem
                            className="text-destructive data-highlighted:bg-destructive/10 data-highlighted:text-destructive"
                            onClick={() => {
                                setGenerationId(image.generation_id);
                                setDialogOpen(true);
                            }}
                        >
                            <Trash2 className="h-4 w-4 text-destructive" />
                            {t('Delete')}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            {/* created at */}
            <div className="absolute inset-x-0 bottom-0 z-10 rounded-b-2xl bg-gradient-to-t from-black/70 via-black/30 to-transparent p-3 pt-8 text-white">
                {image.formatted_created_at}
            </div>

            {/* image */}
            <img
                src={assetUrl(image.result[0])}
                alt={image.name}
                className="h-full w-full object-cover"
                onError={(e) => {
                    e.currentTarget.src = 'https://placehold.co/500x500/transparent/9CA3AF?text=Image+Not+Found';
                }}
            />
        </div>
    );

    // Download image
    const downloadImage = async (imagePath: string, imageName: string) => {
        // image url
        const imageUrl = assetUrl(imagePath);

        // fetch image
        const response = await fetch(imageUrl);
        // make as blob
        const blob = await response.blob();

        // create url
        const url = window.URL.createObjectURL(blob);

        // create link
        const link = document.createElement('a');

        // set link
        link.href = url;
        link.download = `${imageName || 'generated-image'}.png`;

        // append link
        document.body.appendChild(link);
        // click link
        link.click();
        // remove link
        link.remove();

        // revoke url
        window.URL.revokeObjectURL(url);
    };

    return (
        <div className="mt-5 space-y-5">
            {/* Images */}
            {images.length > 0 ? (
                <>
                    {/* Tabs */}
                    <div className="flex justify-end">
                        <Tabs
                            value={view}
                            onValueChange={(value) =>
                                setView(value as 'grid' | 'masonry')
                            }
                        >
                            <TabsList className="p-1.5">
                                <TabsTrigger value="grid">
                                    <LayoutGrid className="h-4 w-4" />
                                </TabsTrigger>

                                <TabsTrigger value="masonry">
                                    <Kanban className="h-4 w-4" />
                                </TabsTrigger>
                            </TabsList>
                        </Tabs>
                    </div>

                    {/* Images */}
                    <div
                        className={
                            view === 'grid'
                                ? 'grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3'
                                : 'columns-1 gap-4 md:columns-2 lg:columns-3'
                        }
                    >
                        {images.map((image) => (
                            <ImageCard
                                key={image.generation_id}
                                image={image}
                                view={view}
                            />
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
                        pageSizeOptions={[6, 9, 12, 15]}
                    />
                </>
            ) : (
                <div className="flex min-h-[77vh] flex-col items-center justify-center gap-6 border rounded-2xl">
                    {/* Image */}
                    <img
                        src={assetUrl('images/no-data.svg')}
                        alt="Empty State"
                        className="h-auto w-full max-w-xs"
                    />

                    {/* No Data Text */}
                    <h3 className="text-sm font-medium text-muted-foreground">
                        {t('No generated images found')}
                    </h3>
                </div>
            )}
        </div>
    );
};
