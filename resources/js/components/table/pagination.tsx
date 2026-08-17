import { Button } from "../ui/button";
import { Label } from "../ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "../ui/select";
import { useTranslation } from "react-i18next";
import {
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
} from "lucide-react";

export function DataTablePagination({
    pageIndex,
    totalPages,
    pageSize,
    onPageChange,
    onPageSizeChange,
}: {
    pageIndex: number;
    totalPages: number;
    pageSize: number;
    onPageChange: (page: number) => void;
    onPageSizeChange?: (size: number) => void;
}) {
    const { t } = useTranslation();
    return (
        <div className="flex items-center justify-between mb-4">
            <div className="flex items-center gap-2">
                <Label>{t("Rows per page")}</Label>
                <Select
                    value={pageSize.toString()}
                    onValueChange={(v) => onPageSizeChange?.(Number(v))}
                >
                    <SelectTrigger className="w-20">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        {[10, 20, 30, 40, 50].map((size) => (
                            <SelectItem key={size} value={size.toString()}>
                                {size}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
            </div>

            <div className="flex gap-2">
                <Button
                    size="icon"
                    variant="outline"
                    onClick={() => onPageChange(0)}
                    disabled={pageIndex === 0}
                >
                    <ChevronsLeft />
                </Button>
                <Button
                    size="icon"
                    variant="outline"
                    onClick={() => onPageChange(pageIndex - 1)}
                    disabled={pageIndex === 0}
                >
                    <ChevronLeft />
                </Button>
                <Button
                    size="icon"
                    variant="outline"
                    onClick={() => onPageChange(pageIndex + 1)}
                    disabled={pageIndex + 1 >= totalPages}
                >
                    <ChevronRight />
                </Button>
                <Button
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
