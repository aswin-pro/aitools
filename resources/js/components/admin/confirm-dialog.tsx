"use client";

import * as React from "react";

import { useMediaQuery } from "@/hooks/use-media-query";
import { Button } from "@/components/ui/button";

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";

import {
    Drawer,
    DrawerContent,
    DrawerDescription,
    DrawerFooter,
    DrawerHeader,
    DrawerTitle,
} from "@/components/ui/drawer";

interface ConfirmDialogProps {
    open: boolean;
    onOpenChange: (open: boolean) => void;

    icon?: React.ReactNode;

    title: string;
    description: string;

    confirmLabel?: string;
    cancelLabel?: string;

    onConfirm: () => void;

    loading?: boolean;
}

export function ConfirmDialog({
    open,
    onOpenChange,
    icon,
    title,
    description,
    confirmLabel = "Confirm",
    cancelLabel = "Cancel",
    onConfirm,
    loading = false,
}: ConfirmDialogProps) {
    const isDesktop = useMediaQuery("(min-width: 768px)");

    if (isDesktop) {
        return (
            <Dialog open={open} onOpenChange={onOpenChange}>
                <DialogContent className="sm:max-w-[430px] p-0 overflow-hidden">
                    <div className="p-6">
                        <DialogHeader className="space-y-5">
                            {/* Icon */}
                            {icon && (
                                <div className="flex justify-center">
                                    <div className="flex size-14 items-center justify-center rounded-2xl border bg-muted/50 shadow-sm">
                                        {icon}
                                    </div>
                                </div>
                            )}

                            {/* Content */}
                            <div className="space-y-2 text-center">
                                <DialogTitle className="text-xl font-semibold tracking-tight">
                                    {title}
                                </DialogTitle>

                                <DialogDescription className="mx-auto max-w-sm text-sm leading-6">
                                    {description}
                                </DialogDescription>
                            </div>
                        </DialogHeader>

                        {/* Actions */}
                        <DialogFooter className="mt-7 grid grid-cols-2 gap-3">
                            <Button
                                type="button"
                                variant="outline"
                                className="w-full"
                                onClick={() => onOpenChange(false)}
                                disabled={loading}
                            >
                                {cancelLabel}
                            </Button>

                            <Button
                                type="button"
                                className="w-full"
                                onClick={onConfirm}
                                disabled={loading}
                            >
                                {confirmLabel}
                            </Button>
                        </DialogFooter>
                    </div>
                </DialogContent>
            </Dialog>
        );
    }

    return (
        <Drawer open={open} onOpenChange={onOpenChange}>
            <DrawerContent>
                <DrawerHeader className="px-6 pt-6 text-center">
                    {/* Icon */}
                    {icon && (
                        <div className="flex justify-center">
                            <div className="flex size-14 items-center justify-center rounded-2xl border bg-muted/50 shadow-sm">
                                {icon}
                            </div>
                        </div>
                    )}

                    <DrawerTitle className="mt-4 text-xl font-semibold tracking-tight">
                        {title}
                    </DrawerTitle>

                    <DrawerDescription className="mx-auto max-w-sm text-sm leading-6">
                        {description}
                    </DrawerDescription>
                </DrawerHeader>

                <DrawerFooter className="grid grid-cols-2 gap-3 px-6 pb-6">
                    <Button
                        type="button"
                        variant="outline"
                        className="w-full"
                        onClick={() => onOpenChange(false)}
                        disabled={loading}
                    >
                        {cancelLabel}
                    </Button>

                    <Button
                        type="button"
                        className="w-full"
                        onClick={onConfirm}
                        disabled={loading}
                    >
                        {confirmLabel}
                    </Button>
                </DrawerFooter>
            </DrawerContent>
        </Drawer>
    );
}