import { Check, X } from "lucide-react";
import { useState } from "react";

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

export type MultiSelectOption = {
    value: string;
    label: string;
    searchValue?: string;
};

type MultiSelectProps = {
    options: MultiSelectOption[];
    value: string[];
    onChange: (value: string[]) => void;
    placeholder?: string;
    searchPlaceholder?: string;
    emptyMessage?: string;
    className?: string;
    disabled?: boolean;
};

export function MultiSelect({
    options,
    value,
    onChange,
    placeholder = "Select options",
    searchPlaceholder = "Search...",
    emptyMessage = "No results found.",
    className,
    disabled = false,
}: MultiSelectProps) {
    const [open, setOpen] = useState(false);

    const toggleOption = (optionValue: string) => {
        if (value.includes(optionValue)) {
            onChange(
                value.filter(
                    (item) => item !== optionValue,
                ),
            );
        } else {
            onChange([
                ...value,
                optionValue,
            ]);
        }
    };

    const removeOption = (optionValue: string) => {
        onChange(
            value.filter(
                (item) => item !== optionValue,
            ),
        );
    };

    const getLabel = (optionValue: string) => {
        return (
            options.find(
                (option) =>
                    option.value === optionValue,
            )?.label || optionValue
        );
    };

    return (
        <Popover
            open={open}
            onOpenChange={setOpen}
        >
            <PopoverTrigger asChild>
                <div
                    role="combobox"
                    aria-expanded={open}
                    aria-disabled={disabled}
                    className={cn(
                        "h-10 w-full rounded-md border border-input bg-background px-3 py-2",
                        disabled
                            ? "cursor-not-allowed opacity-50"
                            : "cursor-pointer",
                        className,
                    )}
                >
                    <div className="flex flex-wrap gap-1">
                        {value.length === 0 ? (
                            <span className=" text-sm text-muted-foreground">
                                {placeholder}
                            </span>
                        ) : (
                            value.map(
                                (optionValue) => (
                                    <span
                                        key={
                                            optionValue
                                        }
                                        className="inline-flex items-center gap-1 rounded-md bg-muted px-2 py-1 text-xs font-medium"
                                    >
                                        {getLabel(
                                            optionValue,
                                        )}

                                        <button
                                            type="button"
                                            disabled={
                                                disabled
                                            }
                                            className="rounded-sm opacity-60 hover:opacity-100 disabled:cursor-not-allowed"
                                            onPointerDown={(
                                                event,
                                            ) => {
                                                event.preventDefault();
                                                event.stopPropagation();
                                            }}
                                            onClick={(
                                                event,
                                            ) => {
                                                event.preventDefault();
                                                event.stopPropagation();

                                                removeOption(
                                                    optionValue,
                                                );
                                            }}
                                        >
                                            <X className="size-3 cursor-pointer" />
                                        </button>
                                    </span>
                                ),
                            )
                        )}
                    </div>
                </div>
            </PopoverTrigger>

            <PopoverContent
                align="start"
                className="w-[var(--radix-popover-trigger-width)] min-w-0 p-0"
            >
                <Command>
                    <CommandInput
                        placeholder={
                            searchPlaceholder
                        }
                    />

                    <CommandList className="max-h-60">
                        <CommandEmpty>
                            {emptyMessage}
                        </CommandEmpty>

                        <CommandGroup>
                            {options.map(
                                (option) => {
                                    const isSelected =
                                        value.includes(
                                            option.value,
                                        );

                                    return (
                                        <CommandItem
                                            key={
                                                option.value
                                            }
                                            value={
                                                option.searchValue ??
                                                `${option.value} ${option.label}`
                                            }
                                            disabled={
                                                disabled
                                            }
                                            onSelect={() =>
                                                toggleOption(
                                                    option.value,
                                                )
                                            }
                                        >
                                            <Check
                                                className={cn(
                                                    "mr-2 size-4 shrink-0",
                                                    isSelected
                                                        ? "opacity-100"
                                                        : "opacity-0",
                                                )}
                                            />

                                            <span className="truncate">
                                                {
                                                    option.label
                                                }
                                            </span>
                                        </CommandItem>
                                    );
                                },
                            )}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}