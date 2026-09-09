import { Button } from '@/components/ui/button';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';
import { Input } from '@/components/ui/input';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Skeleton } from '@/components/ui/skeleton';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { cn } from '@/lib/utils';
import {
    ColumnDef,
    flexRender,
    getCoreRowModel,
    getSortedRowModel,
    SortingState,
    useReactTable,
    VisibilityState,
} from '@tanstack/react-table';
import { CheckIcon, Settings2 } from 'lucide-react';
import { useRef, useState } from 'react';
import { DataTablePagination } from './pagination';

interface DataTableProps<TData> {
    t: (key: string) => string;
    columns: ColumnDef<TData, TData>[];
    data: TData[];
    pageIndex: number;
    pageSize: number;
    totalCount: number;
    initialSearch?: string;
    onPageChange: (page: number) => void;
    onPageSizeChange?: (size: number) => void;
    onSearch?: (search: string) => void;
    loading?: boolean;
}

export function DataTable<TData>({
    t,
    columns,
    data,
    pageIndex,
    pageSize,
    totalCount,
    initialSearch,
    onPageChange,
    onPageSizeChange,
    onSearch,
    loading = false,
}: DataTableProps<TData>) {
    // search
    const [search, setSearch] = useState(initialSearch ?? '');

    // refs
    const inputRef = useRef<HTMLInputElement>(null);

    // sortings
    const [sorting, setSorting] = useState<SortingState>([]);

    // column visibility
    const [columnVisibility, setColumnVisibility] = useState<VisibilityState>(
        {},
    );

    // search timeout
    const searchTimeout = useRef<ReturnType<typeof setTimeout> | null>(null);

    // handle search
    const handleSearch = (value: string) => {
        setSearch(value);

        if (searchTimeout.current) {
            clearTimeout(searchTimeout.current);
        }

        searchTimeout.current = setTimeout(() => {
            onSearch?.(value);
        }, 500);
    };

    const table = useReactTable({
        data,
        columns,
        manualPagination: true,
        getCoreRowModel: getCoreRowModel(),
        getSortedRowModel: getSortedRowModel(),

        onSortingChange: setSorting,
        onColumnVisibilityChange: setColumnVisibility,

        state: {
            sorting,
            columnVisibility,
            pagination: { pageIndex, pageSize },
        },
    });

    // total pages
    const totalPages = Math.ceil(totalCount / pageSize);

    return (
        <div>
            {/* Search + Column toggle */}
            <div className="mt-4 flex items-center gap-4">
                <Input
                    ref={inputRef}
                    placeholder={t('Search...')}
                    className="max-w-xs"
                    value={search}
                    onChange={(e) => handleSearch(e.target.value)}
                />

                <Popover>
                    <PopoverTrigger asChild>
                        <Button
                            variant="outline"
                            type="button"
                            className="ms-auto px-3"
                        >
                            <Settings2 />
                            {t('View')}
                        </Button>
                    </PopoverTrigger>
                    <PopoverContent align="end" className="w-44 p-0">
                        <Command>
                            <CommandInput
                                placeholder={t('Search columns...')}
                            />
                            <CommandList>
                                <CommandEmpty>
                                    {t('No columns found.')}
                                </CommandEmpty>
                                <CommandGroup>
                                    {table
                                        .getAllColumns()
                                        .filter((c) => c.getCanHide())
                                        .map((column) => (
                                            <CommandItem
                                                key={column.id}
                                                onSelect={() =>
                                                    column.toggleVisibility(
                                                        !column.getIsVisible(),
                                                    )
                                                }
                                            >
                                                <span>{column.id}</span>
                                                <CheckIcon
                                                    className={cn(
                                                        'ms-auto size-4',
                                                        column.getIsVisible()
                                                            ? 'opacity-100'
                                                            : 'opacity-0',
                                                    )}
                                                />
                                            </CommandItem>
                                        ))}
                                </CommandGroup>
                            </CommandList>
                        </Command>
                    </PopoverContent>
                </Popover>
            </div>

            {/* Table */}
            <div className="my-4 overflow-hidden rounded-md border">
                <Table>
                    <TableHeader>
                        {table.getHeaderGroups().map((hg) => (
                            <TableRow key={hg.id}>
                                {hg.headers.map((header) => (
                                    <TableHead key={header.id}>
                                        {flexRender(
                                            header.column.columnDef.header,
                                            header.getContext(),
                                        )}
                                    </TableHead>
                                ))}
                            </TableRow>
                        ))}
                    </TableHeader>

                    <TableBody>
                        {loading ? (
                            Array.from({ length: pageSize }).map((_, i) => (
                                <TableRow key={i}>
                                    {columns.map((_, j) => (
                                        <TableCell key={j}>
                                            <Skeleton className="h-4 w-full" />
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                        ) : table.getRowModel().rows.length ? (
                            table.getRowModel().rows.map((row) => (
                                <TableRow key={row.id}>
                                    {row.getVisibleCells().map((cell) => (
                                        <TableCell key={cell.id}>
                                            {flexRender(
                                                cell.column.columnDef.cell,
                                                cell.getContext(),
                                            )}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                        ) : (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="text-center"
                                >
                                    {t('No results.')}
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>

            {/* Pagination */}
            <DataTablePagination
                t={t}
                pageIndex={pageIndex}
                totalPages={totalPages}
                pageSize={pageSize}
                onPageChange={onPageChange}
                onPageSizeChange={onPageSizeChange}
                pageSizeOptions={[10, 20, 30, 40, 50]}
            />
        </div>
    );
}
