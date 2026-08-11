import { Check, ChevronsUpDown } from "lucide-react";
import { cn } from "@/lib/utils";

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
import { Label } from "../ui/label";
import { Input } from "../ui/input";

type SearchableSelectOption = {
    value: string;
    label: string;
    searchValue?: string;
};

type SearchableSelectProps = {
    label?: string;
    value: string;
    onChange: (value: string) => void;
    options: SearchableSelectOption[];
    placeholder?: string;
    searchPlaceholder?: string;
    emptyMessage?: string;
    name?: string;
    error?: string;
    searchable?: boolean;
};

export function SearchableSelect({
    label,
    value,
    onChange,
    options,
    placeholder = "Select an option",
    searchPlaceholder = "Search...",
    emptyMessage = "No results found.",
    name,
    error,
    searchable = true,
}: SearchableSelectProps) {
    const selectedOption = options.find((option) => option.value === value);

    return (
        <div className="grid gap-2">
            {label && (
                <Label htmlFor={name} required>
                    {label}
                </Label>
            )}
            <Popover>
                <PopoverTrigger asChild>
                    <div className="relative">
                        <Input
                            id={name}
                            value={selectedOption?.label || ""}
                            placeholder={placeholder}
                            readOnly
                            className="cursor-pointer pr-10"
                        />

                        <ChevronsUpDown className="absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 opacity-50" />
                    </div>
                </PopoverTrigger>

                <PopoverContent
                    className="w-[var(--radix-popover-trigger-width)] p-0"
                    align="start"
                >
                    <Command>
                        {searchable && (
                            <CommandInput placeholder={searchPlaceholder} />
                        )}

                        <CommandList>
                            <CommandEmpty>{emptyMessage}</CommandEmpty>

                            <CommandGroup>
                                {options.map((option) => (
                                    <CommandItem
                                        key={option.value}
                                        onSelect={() => onChange(option.value)}
                                    >
                                        <Check
                                            className={cn(
                                                "mr-2 h-4 w-4",
                                                value === option.value
                                                    ? "opacity-100"
                                                    : "opacity-0",
                                            )}
                                        />

                                        {option.label}
                                    </CommandItem>
                                ))}
                            </CommandGroup>
                        </CommandList>
                    </Command>
                </PopoverContent>
            </Popover>

            {name && <input type="hidden" name={name} value={value} />}

            {error && <p className="text-sm text-destructive">{error}</p>}
        </div>
    );
}
