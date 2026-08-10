import { Check, ChevronsUpDown } from "lucide-react";
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

    const selectedNames = Object.entries(languages)
        .filter(([code]) => value.includes(code))
        .map(([, name]) => name);

    return (
        <Popover open={open} onOpenChange={setOpen}>
            <PopoverTrigger asChild>
                <Button
                    type="button"
                    variant="outline"
                    role="combobox"
                    aria-expanded={open}
                    className="w-full justify-between font-normal"
                >
                    <span className="truncate">
                        {selectedNames.length > 0
                            ? selectedNames.join(", ")
                            : "Select languages"}
                    </span>

                    <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                </Button>
            </PopoverTrigger>

            <PopoverContent
                className="w-[var(--radix-popover-trigger-width)] p-0"
                align="start"
            >
                <Command>
                    <CommandInput placeholder="Search language..." />

                    <CommandList>
                        <CommandEmpty>
                            No language found.
                        </CommandEmpty>

                        <CommandGroup>
                            {Object.entries(languages).map(
                                ([code, name]) => {
                                    const isSelected = value.includes(code);

                                    return (
                                        <CommandItem
                                            key={code}
                                            value={`${code} ${name}`}
                                            onSelect={() =>
                                                toggleLanguage(code)
                                            }
                                        >
                                            <Check
                                                className={cn(
                                                    "mr-2 h-4 w-4",
                                                    isSelected
                                                        ? "opacity-100"
                                                        : "opacity-0",
                                                )}
                                            />

                                            {name}
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