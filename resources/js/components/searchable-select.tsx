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
import { Label } from "./ui/label";

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
}: SearchableSelectProps) {
    const selectedOption = options.find(
        (option) => option.value === value,
    );

    return (
        <div className="grid gap-2">
              {label && (
                <Label htmlFor={name} required>
                    {label}
                </Label>
            )}
            <Popover>
                <PopoverTrigger asChild>
                    <Button
                        type="button"
                        variant="outline"
                        role="combobox"
                        className="max-w-[280px] justify-between font-normal"
                    >
                        <span className="truncate">
                            {selectedOption?.label || placeholder}
                        </span>

                        <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                    </Button>
                </PopoverTrigger>

                <PopoverContent
                    className="w-[var(--radix-popover-trigger-width)] p-0"
                    align="start"
                >
                    <Command>
                        <CommandInput placeholder={searchPlaceholder} />

                        <CommandList>
                            <CommandEmpty>
                                {emptyMessage}
                            </CommandEmpty>

                            <CommandGroup>
                                {options.map((option) => (
                                    <CommandItem
                                        key={option.value}
                                        value={
                                            option.searchValue ??
                                            `${option.value} ${option.label}`
                                        }
                                        onSelect={() =>
                                            onChange(option.value)
                                        }
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

            {name && (
                <input
                    type="hidden"
                    name={name}
                    value={value}
                />
            )}

            {error && (
                <p className="text-sm text-destructive">
                    {error}
                </p>
            )}
        </div>
    );
}