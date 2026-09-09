import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { AIContent } from '@/types/user';
import { ColumnDef } from '@tanstack/react-table';
import { MoreVertical, ScrollText, SquarePen, Trash2 } from 'lucide-react';

export const getColumns = ({
    pageIndex,
    t,
    setDialogOpen,
    setDialogType,
    setGenerationId,
}: {
    pageIndex: number;
    t: (key: string) => string;
    setDialogOpen: (open: boolean) => void;
    setDialogType: (type: 'view' | 'update' | 'delete') => void;
    setGenerationId: (id: string | null) => void;
}): ColumnDef<AIContent>[] => [
    {
        accessorKey: t('S.No'),
        header: t('S.No'),
        cell: ({ row }) => pageIndex * 10 + row.index + 1,
    },
    {
        accessorKey: t('Date'),
        header: t('Date'),
        cell: ({ row }) => (
            <div className="min-w-28!">{row.original.formatted_created_at}</div>
        ),
    },
    {
        accessorKey: t('Prompt'),
        header: t('Prompt'),
        cell: ({ row }) => (
            <div className="max-w-44 truncate">{row.original.name}</div>
        ),
    },
    {
        accessorKey: t('Template'),
        header: t('Template'),
        cell: ({ row }) => (
            <Badge variant="secondary" className="min-w-max rounded-sm">
                {row.original.template.name ?? '-'}
            </Badge>
        ),
    },
    {
        accessorKey: t('Language'),
        header: t('Language'),
        cell: ({ row }) => row.original.lang,
    },
    {
        accessorKey: t('No. of Credits'),
        header: t('No. of Credits'),
        cell: ({ row }) => (
            <Badge variant="secondary" className="rounded-sm">
                {row.original.word_count}
            </Badge>
        ),
    },
    {
        accessorKey: t('Action'),
        header: t('Action'),
        cell: ({ row }) => {
            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button type="button" variant="outline" size="icon">
                            <MoreVertical className="h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        {/* Edit */}
                        <DropdownMenuItem
                            onClick={() => {
                                setGenerationId(row.original.generation_id);
                                setDialogType('update');
                                setDialogOpen(true);
                            }}
                        >
                            <SquarePen className="h-4 w-4" />
                            {t('Edit')}
                        </DropdownMenuItem>
                        {/* View */}
                        <DropdownMenuItem
                            onClick={() => {
                                setGenerationId(row.original.generation_id);
                                setDialogType('view');
                                setDialogOpen(true);
                            }}
                        >
                            <ScrollText className="h-4 w-4" />
                            {t('View')}
                        </DropdownMenuItem>

                        {/* Seperator */}
                        <DropdownMenuSeparator />

                        {/* Delete */}
                        <DropdownMenuItem
                            className="text-destructive data-highlighted:bg-destructive/10 data-highlighted:text-destructive"
                            onClick={() => {
                                setGenerationId(row.original.generation_id);
                                setDialogType('delete');
                                setDialogOpen(true);
                            }}
                        >
                            <Trash2 className="h-4 w-4 text-destructive" />
                            {t('Delete')}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];