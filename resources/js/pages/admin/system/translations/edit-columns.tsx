import { useEffect, useState } from "react";
import { ColumnDef } from "@tanstack/react-table";
import { Textarea } from "@/components/ui/textarea";
import { TranslationEditorRow } from "@/types/translation-manager";

interface Props {
    t: (key: string) => string;
    sourceLocale: string;
    locale: string;
    translations: Record<string, string>;
    onTranslationChange: (key: string, value: string) => void;
}

function TranslationInput({
    value,
    onChange,
    placeholder,
}: {
    value: string;
    onChange: (value: string) => void;
    placeholder: string;
}) {
    const [localValue, setLocalValue] = useState(value);

    useEffect(() => {
        setLocalValue(value);
    }, [value]);

    return (
        <Textarea
            value={localValue}
            onChange={(e) => setLocalValue(e.target.value)}
            onBlur={() => onChange(localValue)}
            placeholder={placeholder}
            rows={2}
            className="min-h-[70px] min-w-[280px] resize-y"
        />
    );
}

export function getEditColumns({
    t,
    sourceLocale,
    locale,
    translations,
    onTranslationChange,
}: Props): ColumnDef<TranslationEditorRow>[] {
    return [
        {
            accessorKey: "category",
            header: t("Category"),
            cell: ({ row }) => (
                <span className="text-sm">{row.original.category}</span>
            ),
        },
        {
            accessorKey: "key",
            header: t("Value"),
            cell: ({ row }) => (
                <div className="max-w-[300px] whitespace-normal break-words">
                    {row.original.key}
                </div>
            ),
        },
        {
            accessorKey: "source",
            header: `${t("Source")} (${sourceLocale.toUpperCase()})`,
            cell: ({ row }) => (
                <div className="max-w-[400px] whitespace-normal break-words text-sm text-muted-foreground">
                    {row.original.source}
                </div>
            ),
        },
        {
            id: "translation",
            header: locale.toUpperCase(),
            cell: ({ row }) => (
                <TranslationInput
                    value={translations[row.original.id] ?? ""}
                    onChange={(value) =>
                        onTranslationChange(row.original.id, value)
                    }
                    placeholder={t("Enter translation...")}
                />
            ),
        },
    ];
}
