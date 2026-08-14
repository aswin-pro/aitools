import { Check, X } from "lucide-react";
import { useState } from "react";

import { Button } from "@/components/ui/button";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/components/ui/command";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import { cn } from "@/lib/utils";

type LanguageMultiSelectProps = {
    languages: Record<string, string>;
    value: string[];
    onChange: (value: string[]) => void;
};

export function LanguageMultiSelect({
    languages,
    value,
    onChange,
}: LanguageMultiSelectProps) {
    const [open, setOpen] = useState(false);

    const toggleLanguage = (code: string) => {
        if (value.includes(code)) {
            onChange(value.filter((item) => item !== code));
        } else {
            onChange([...value, code]);
        }
    };

    const removeLanguage = (code: string) => {
        onChange(value.filter((item) => item !== code));
    };

    return (
        <Popover open={open} onOpenChange={setOpen}>
    
            <PopoverTrigger asChild>
                <div
                    role="combobox"
                    aria-expanded={open}
                    className={cn(
                        "min-h-10 w-full cursor-pointer rounded-md border border-input bg-background px-3 py-2",
                        // "text-sm ring-offset-background focus-within:outline-none",
                        // "focus-within:ring-2 focus-within:ring-ring focus-within:ring-offset-2",
                    )}
                >
                    <div className="flex flex-wrap gap-1">
                        {value.length === 0 ? (
                            <span className="py-1 text-muted-foreground">
                                Select languages
                            </span>
                        ) : (
                            value.map((code) => (
                                <span
                                    key={code}
                                    className="inline-flex items-center gap-1 rounded-md bg-muted px-2 py-1 text-xs font-medium"
                                >
                                    {languages[code]}

                                    <button
                                        type="button"
                                        className="rounded-sm opacity-60 hover:opacity-100"
                                        onPointerDown={(e) => {
                                            e.preventDefault();
                                            e.stopPropagation();
                                        }}
                                        onClick={(e) => {
                                            e.preventDefault();
                                            e.stopPropagation();
                                            removeLanguage(code);
                                        }}
                                    >
                                        <X className="h-3 w-3 cursor-pointer" />
                                    </button>
                                </span>
                            ))
                        )}
                    </div>
                </div>
            </PopoverTrigger>

            <PopoverContent
                align="start"
                className="w-[var(--radix-popover-trigger-width)] min-w-0 p-0"
            >
                <Command>
                    <CommandInput placeholder="Search language..." />

                    <CommandList className="max-h-60">
                        <CommandEmpty>No language found.</CommandEmpty>

                        <CommandGroup>
                            {Object.entries(languages).map(([code, name]) => {
                                const isSelected = value.includes(code);

                                return (
                                    <CommandItem
                                        key={code}
                                        value={`${code} ${name}`}
                                        onSelect={() => toggleLanguage(code)}
                                    >
                                        <Check
                                            className={cn(
                                                "mr-2 h-4 w-4 shrink-0",
                                                isSelected
                                                    ? "opacity-100"
                                                    : "opacity-0",
                                            )}
                                        />

                                        <span className="truncate">{name}</span>
                                    </CommandItem>
                                );
                            })}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}



