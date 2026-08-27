import { useState } from "react";
import { X } from "lucide-react";

import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";

interface TagInputProps {
    label: string;
    value: string;
    onChange: (value: string) => void;
    error?: string;
    required?: boolean;
    disabled?: boolean;
    placeholder?: string;
    subLable?: string;
}

export default function TagInput({
    label,
    value,
    onChange,
    error,
    required = false,
    disabled = false,
    placeholder = "Enter a tag",
    subLable,
}: TagInputProps) {
    const [inputValue, setInputValue] = useState("");

    const tags = value
        ? value
              .split(",")
              .map((tag) => tag.trim())
              .filter(Boolean)
        : [];

    const updateTags = (newTags: string[]) => {
        onChange(newTags.join(", "));
    };

    const addTag = (tag: string) => {
        const cleanTag = tag.trim();

        if (!cleanTag) {
            return;
        }

        if (tags.includes(cleanTag)) {
            setInputValue("");
            return;
        }

        updateTags([...tags, cleanTag]);
        setInputValue("");
    };

    const removeTag = (index: number) => {
        updateTags(
            tags.filter((_, tagIndex) => tagIndex !== index),
        );
    };

    const handleKeyDown = (
        e: React.KeyboardEvent<HTMLInputElement>,
    ) => {
        if (
            e.key === "Enter" ||
            e.key === ","
        ) {
            e.preventDefault();
            addTag(inputValue);
        }

        if (
            e.key === "Backspace" &&
            !inputValue &&
            tags.length
        ) {
            removeTag(tags.length - 1);
        }
    };

    const handleChange = (
        e: React.ChangeEvent<HTMLInputElement>,
    ) => {
        const input = e.target.value;

        // Allow comma-separated input
        if (input.includes(",")) {
            const parts = input.split(",");

            parts.slice(0, -1).forEach((part) => {
                addTag(part);
            });

            setInputValue(parts.at(-1) ?? "");
            return;
        }

        setInputValue(input);
    };

    return (
        <div className="grid gap-2">
            <div className="flex items-center gap-4">
                <Label required={required}>
                    {label}
                </Label>

                {subLable && (
                    <span className="text-xs text-muted-foreground">
                        ({subLable})
                    </span>
                )}
            </div>

            <div
                className={`flex min-h-10 w-full flex-wrap items-center gap-2 rounded-md border bg-background px-3 py-2 text-sm shadow-xs transition-colors ${
                    error
                        ? "border-destructive"
                        : "border-input"
                } ${
                    disabled
                        ? "cursor-not-allowed opacity-50"
                        : "focus-within:ring-2 focus-within:ring-ring"
                }`}
            >
                {tags.map((tag, index) => (
                    <span
                        key={`${tag}-${index}`}
                        className="inline-flex items-center gap-1 rounded-md bg-secondary px-2 py-1 text-xs font-medium text-secondary-foreground"
                    >
                        {tag}

                        <button
                            type="button"
                            onClick={() =>
                                removeTag(index)
                            }
                            disabled={disabled}
                            className="rounded-full outline-none hover:bg-muted focus-visible:ring-2 focus-visible:ring-ring"
                            aria-label={`Remove ${tag}`}
                        >
                            <X className="size-3" />
                        </button>
                    </span>
                ))}

                <input
                    type="text"
                    value={inputValue}
                    onChange={handleChange}
                    onKeyDown={handleKeyDown}
                    disabled={disabled}
                    placeholder={
                        tags.length
                            ? ""
                            : placeholder
                    }
                    className="min-w-[120px] flex-1 border-0 bg-transparent p-0 text-sm outline-none placeholder:text-muted-foreground focus:ring-0"
                />
            </div>

            <InputError message={error} />
        </div>
    );
}