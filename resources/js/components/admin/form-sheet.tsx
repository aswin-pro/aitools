import React from "react";
import { Button } from "@/components/ui/button";
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetFooter,
    SheetHeader,
    SheetTitle,
} from "@/components/ui/sheet";
import FormFile from "./form-file";
import FormInput from "./form-input";
import { SearchableSelect } from "./searchable-select";
import { LoadingSwap } from "../ui/loading-swap";
import FormTextarea from "./form-textarea";

interface SelectOption {
    value: string;
    label: string;
}

interface FormField {
    type: "input" | "select" | "textarea" | "file";
    id?: string;
    name: string;
    label: string;
    placeholder?: string;
    required?: boolean;

    inputType?: React.HTMLInputTypeAttribute;

    options?: SelectOption[];
    searchable?: boolean;
}

interface FormSheetProps {
    open: boolean;
    onOpenChange: (open: boolean) => void;

    title: string;
    description?: string;

    form: any;

    fields: FormField[];

    onSubmit: (e: React.FormEvent<HTMLFormElement>) => void;

    submitLabel?: string;
    cancelLabel?: string;
}

export function FormSheet({
    open,
    onOpenChange,
    title,
    description,
    form,
    fields,
    onSubmit,
    submitLabel = "Save",
    cancelLabel = "Cancel",
}: FormSheetProps) {
    return (
        <Sheet open={open} onOpenChange={onOpenChange}>
            <SheetContent
                className="flex w-full max-w-full flex-col sm:max-w-lg"
                onOpenAutoFocus={(e) => e.preventDefault()}
            >
                <SheetHeader>
                    <SheetTitle>{title}</SheetTitle>

                    {description && (
                        <SheetDescription>{description}</SheetDescription>
                    )}
                </SheetHeader>

                <form onSubmit={onSubmit} className="flex h-full flex-col">
                    <div className="flex-1 space-y-5 overflow-y-auto px-1 py-6">
                        {fields.map((field) => {
                            const value = form.data[field.name] ?? "";

                            if (field.type === "input") {
                                return (
                                    <FormInput
                                        key={field.name}
                                        id={field.id ?? field.name}
                                        name={field.name}
                                        type={field.inputType ?? "text"}
                                        label={field.label}
                                        required={field.required}
                                        value={value}
                                        placeholder={field.placeholder}
                                        error={form.errors[field.name]}
                                        disabled={form.processing}
                                        onChange={(e) => {
                                            form.setData(
                                                field.name,
                                                e.target.value,
                                            );

                                            form.clearErrors(field.name);
                                        }}
                                    />
                                );
                            }

                            if (field.type === "file") {
                                return (
                                    <FormFile
                                        key={field.name}
                                        id={field.id ?? field.name}
                                        name={field.name}
                                        label={field.label}
                                        required={field.required}
                                        error={form.errors[field.name]}
                                        disabled={form.processing}
                                        onChange={(e) => {
                                            const file =
                                                e.target.files?.[0] ?? null;

                                            form.setData(field.name, file);
                                            form.clearErrors(field.name);
                                        }}
                                    />
                                );
                            }

                            if (field.type === "select") {
                                return (
                                    <SearchableSelect
                                        key={field.name}
                                        name={field.name}
                                        label={field.label}
                                        value={value}
                                        onChange={(value) => {
                                            form.setData(field.name, value);

                                            form.clearErrors(field.name);
                                        }}
                                        options={field.options ?? []}
                                        placeholder={field.placeholder}
                                        searchable={field.searchable ?? false}
                                        error={form.errors[field.name]}
                                    />
                                );
                            }

                            if (field.type === "textarea") {
                                return (
                                    <FormTextarea
                                        key={field.name}
                                        id={field.id ?? field.name}
                                        name={field.name}
                                        label={field.label}
                                        required={field.required}
                                        value={value}
                                        placeholder={field.placeholder}
                                        error={form.errors[field.name]}
                                        disabled={form.processing}
                                        onChange={(e) => {
                                            form.setData(
                                                field.name,
                                                e.target.value,
                                            );

                                            form.clearErrors(field.name);
                                        }}
                                    />
                                );
                            }

                            return null;
                        })}
                    </div>

                    <SheetFooter className="w-full">
                        <div className="flex w-full flex-col gap-4">
                            <Button type="submit" disabled={form.processing}>
                                <LoadingSwap isLoading={form.processing}>
                                    {submitLabel}
                                </LoadingSwap>
                            </Button>

                            <Button
                                type="button"
                                variant="outline"
                                onClick={() => onOpenChange(false)}
                                disabled={form.processing}
                            >
                                <LoadingSwap isLoading={false}>
                                    {cancelLabel}
                                </LoadingSwap>
                            </Button>
                        </div>
                    </SheetFooter>
                </form>
            </SheetContent>
        </Sheet>
    );
}
