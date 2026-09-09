import {
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
} from 'lucide-react';
import { Button } from '../ui/button';
import { Label } from '../ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '../ui/select';

export function DataTablePagination({
    t,
    pageIndex,
    totalPages,
    pageSize,
    onPageChange,
    onPageSizeChange,
    pageSizeOptions,
}: {
    t: (key: string) => string;
    pageIndex: number;
    totalPages: number;
    pageSize: number;
    onPageChange: (page: number) => void;
    onPageSizeChange?: (size: number) => void;
    pageSizeOptions: number[];
}) {
    return (
        <div className="mb-4 flex items-center justify-between">
            {/* Rows per page */}
            <div className="flex items-center gap-2">
                <Label>{t('Rows per page')}</Label>
                <Select
                    value={pageSize.toString()}
                    onValueChange={(v) => onPageSizeChange?.(Number(v))}
                >
                    <SelectTrigger className="w-20">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        {pageSizeOptions.map((size) => (
                            <SelectItem key={size} value={size.toString()}>
                                {size}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
            </div>

            <div className="flex gap-2">
                {/* First */}
                <Button
                    type="button"
                    size="icon"
                    variant="outline"
                    onClick={() => onPageChange(0)}
                    disabled={pageIndex === 0}
                >
                    <ChevronsLeft />
                </Button>

                {/* Previous */}
                <Button
                    type="button"
                    size="icon"
                    variant="outline"
                    onClick={() => onPageChange(pageIndex - 1)}
                    disabled={pageIndex === 0}
                >
                    <ChevronLeft />
                </Button>

                {/* Next */}
                <Button
                    type="button"
                    size="icon"
                    variant="outline"
                    onClick={() => onPageChange(pageIndex + 1)}
                    disabled={pageIndex + 1 >= totalPages}
                >
                    <ChevronRight />
                </Button>

                {/* Last */}
                <Button
                    type="button"
                    size="icon"
                    variant="outline"
                    onClick={() => onPageChange(totalPages - 1)}
                    disabled={pageIndex + 1 >= totalPages}
                >
                    <ChevronsRight />
                </Button>
            </div>
        </div>
    );
}
