import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem, LaravelPagination } from "@/types";
import { Head, router, useForm } from "@inertiajs/react";
import { useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import { Pencil, Plus, Trash2, UserCheck, UserX } from "lucide-react";
import { toast } from "sonner";

import { Button } from "@/components/ui/button";
import { FormSheet } from "@/components/admin/form-sheet";

import { ChatGenius } from "@/types/admin";
import { getColumns } from "./columns";
import { DataTable } from "@/components/table/data-table";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Chat Assistant",
        href: "#",
    },
];

export default function Index({
    chatgenius,
}: {
    chatgenius: LaravelPagination<ChatGenius>;
}) {
    const { t } = useTranslation();

    const [editOpen, setEditOpen] = useState(false);

    const [confirmOpen, setConfirmOpen] = useState(false);

    const [actionLoading, setActionLoading] = useState(false);

    const [selectedAction, setSelectedAction] = useState<
        "activate" | "deactivate" | "delete" | null
    >(null);

    const [selectedChatGenius, setSelectedChatGenius] =
        useState<ChatGenius | null>(null);

    const [createOpen, setCreateOpen] = useState(false);

    const form = useForm({
        chat_genius_image: null as File | null,
        chat_genius_name: "",
        chat_genius_expert: "",
        chat_genius_description: "",
        chat_genius_message: "",
    });

    const editForm = useForm({
        chat_genius_id: "",
        chat_genius_name: "",
        chat_genius_expert: "",
        chat_genius_description: "",
        chat_genius_message: "",
        chat_genius_image: null as File | null,
    });

    const openActionDialog = (
        chatgenius: ChatGenius,
        action: "activate" | "deactivate" | "delete",
    ) => {
        setSelectedChatGenius(chatgenius);
        setSelectedAction(action);
        setConfirmOpen(true);
    };

    const handleCreate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        form.post(route("dashboard.admin.save.chatgenius"), {
            forceFormData: true,
            preserveScroll: true,

            onSuccess: () => {
                setCreateOpen(false);

                form.reset();

                toast.success(t("Chat Assistant created successfully!"));
            },

            onError: () => {
                toast.error(t("Please check the form errors."));
            },
        });
    };

    //edit sheet action
    const handleEdit = (chatgenius: ChatGenius) => {
        setSelectedChatGenius(chatgenius);

        editForm.setData({
            chat_genius_id: chatgenius.chat_genius_id,
            chat_genius_name: chatgenius.chat_genius_name,
            chat_genius_expert: chatgenius.chat_genius_expert,
            chat_genius_description: chatgenius.chat_genius_description,
            chat_genius_message: chatgenius.chat_genius_message,
            chat_genius_image: null,
        });

        editForm.clearErrors();

        setEditOpen(true);
    };

    //update chat assistant
    const handleUpdate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        editForm.post(route("dashboard.admin.update.chatgenius"), {
            forceFormData: true,
            preserveScroll: true,

            onSuccess: () => {
                setEditOpen(false);
                setSelectedChatGenius(null);

                editForm.reset();

                toast.success(t("Chat Assistant updated successfully!"));
            },

            onError: () => {
                toast.error(t("Please check the form errors."));
            },
        });
    };

    const handleAction = () => {
        if (!selectedChatGenius || !selectedAction) {
            return;
        }

        setActionLoading(true);

        const routeName =
            selectedAction === "delete"
                ? "dashboard.admin.delete.chatgenius"
                : "dashboard.admin.action.chatgenius";

        router.get(
            route(routeName),
            {
                id: selectedChatGenius.chat_genius_id,
            },
            {
                preserveScroll: true,

                onSuccess: (page) => {
                    const flash = page.props.flash as {
                        success?: string;
                        error?: string;
                    };

                    if (flash?.success) {
                        toast.success(flash.success);
                    }

                    if (flash?.error) {
                        toast.error(flash.error);
                    }

                    setConfirmOpen(false);
                    setSelectedChatGenius(null);
                    setSelectedAction(null);
                },

                onError: () => {
                    toast.error(t("Unable to complete the action."));
                },

                onFinish: () => {
                    setActionLoading(false);
                },
            },
        );
    };

    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: chatgenius.current_page - 1,
                pageSize: chatgenius.per_page,
                t,
                onEdit: handleEdit,
                onAction: openActionDialog,
            }),
        [chatgenius.current_page, chatgenius.per_page, t],
    );

    const dialogContent = {
        activate: {
            icon: <UserCheck className="size-7 text-green-600" />,
            title: t("Activate Chat Assistant?"),
            description: t(
                "If you proceed, this chat assistant will be activated.",
            ),
            confirmLabel: t("Yes, activate"),
        },

        deactivate: {
            icon: <UserX className="size-7 text-destructive" />,
            title: t("Deactivate Chat Assistant?"),
            description: t(
                "If you proceed, this chat assistant will be deactivated.",
            ),
            confirmLabel: t("Yes, deactivate"),
        },
        delete: {
            icon: <Trash2 className="size-7 text-destructive" />,
            title: t("Delete Chat Assistant?"),
            description: t(
                "This action cannot be undone. Are you sure you want to delete this chat assistant?",
            ),
            confirmLabel: t("Yes, delete"),
        },
    };

    const currentDialog = selectedAction ? dialogContent[selectedAction] : null;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Chat Assistant")} />

            <div className="mb-4 flex items-start justify-between">
                <Heading
                    title={t("Chat Assistant")}
                    description={t("Create and manage your chat assistants")}
                />

                <Button onClick={() => setCreateOpen(true)}>
                    <Plus />
                    {t("Create")}
                </Button>
            </div>

            <DataTable
                columns={columns}
                data={chatgenius.data}
                pageIndex={chatgenius.current_page - 1}
                pageSize={chatgenius.per_page}
                totalCount={chatgenius.total}
                initialSearch={route().params.search ?? ""}
                onPageChange={(page) =>
                    router.get(
                        route("dashboard.admin.chatgenius"),
                        {
                            page: page + 1,
                            per_page: chatgenius.per_page,
                            search: route().params.search,
                        },
                        {
                            preserveState: true,
                            preserveScroll: true,
                        },
                    )
                }
                onPageSizeChange={(size) =>
                    router.get(
                        route("dashboard.admin.chatgenius"),
                        {
                            page: 1,
                            per_page: size,
                            search: route().params.search,
                        },
                        {
                            preserveState: true,
                            preserveScroll: true,
                        },
                    )
                }
                onSearch={(search) =>
                    router.get(
                        route("dashboard.admin.chatgenius"),
                        {
                            page: 1,
                            per_page: chatgenius.per_page,
                            search,
                        },
                        {
                            preserveState: true,
                            preserveScroll: true,
                        },
                    )
                }
            />

            {/* Create Chat Assistant */}
            <FormSheet
                open={createOpen}
                onOpenChange={setCreateOpen}
                title={t("Create Chat Assistant")}
                description={t("Create a new chat assistant")}
                form={form}
                fields={[
                    {
                        type: "file",
                        name: "chat_genius_image",
                        label: t("Thumbnail"),
                        required: true,
                    },
                    {
                        type: "input",
                        name: "chat_genius_name",
                        label: t("Name"),
                        placeholder: t("Ex: Fitness Guru"),
                        required: true,
                        inputType: "text",
                    },
                    {
                        type: "input",
                        name: "chat_genius_expert",
                        label: t("Expert"),
                        placeholder: t("Ex: Personal Trainer"),
                        required: true,
                        inputType: "text",
                    },
                    {
                        type: "textarea",
                        name: "chat_genius_description",
                        label: t("Description"),
                        placeholder: t(
                            "Ex: I am a personal trainer and I can help you achieve your fitness goals.",
                        ),
                        required: true,
                    },
                    {
                        type: "textarea",
                        name: "chat_genius_message",
                        label: t("System Prompt"),
                        placeholder: t(
                            "Ex: Hi, I'm John and I'm a personal trainer. How can I help you achieve your fitness goals?",
                        ),
                        required: true,
                    },
                ]}
                onSubmit={handleCreate}
                submitLabel={t("Create")}
                cancelLabel={t("Cancel")}
            />

            {/* Edit Chat Assistant */}
            <FormSheet
                open={editOpen}
                onOpenChange={setEditOpen}
                title={t("Edit Chat Assistant")}
                description={t("Update the chat assistant details")}
                form={editForm}
                fields={[
                    {
                        type: "input",
                        name: "chat_genius_name",
                        label: t("Name"),
                        placeholder: t("Enter chat assistant name"),
                        required: true,
                        inputType: "text",
                    },

                    {
                        type: "input",
                        name: "chat_genius_expert",
                        label: t("Expert"),
                        placeholder: t("Enter expert name"),
                        required: true,
                        inputType: "text",
                    },

                    {
                        type: "textarea",
                        name: "chat_genius_description",
                        label: t("Description"),
                        placeholder: t("Enter description"),
                        required: true,
                    },

                    {
                        type: "textarea",
                        name: "chat_genius_message",
                        label: t("System Prompt"),
                        placeholder: t("Enter system prompt"),
                        required: true,
                    },

                    {
                        type: "file",
                        name: "chat_genius_image",
                        label: t("Thumbnail"),
                        required: false,
                    },
                ]}
                onSubmit={handleUpdate}
                submitLabel={t("Update")}
                cancelLabel={t("Cancel")}
            />

            {selectedAction && currentDialog && (
                <ConfirmDialog
                    open={confirmOpen}
                    onOpenChange={setConfirmOpen}
                    icon={currentDialog.icon}
                    title={currentDialog.title}
                    description={currentDialog.description}
                    cancelLabel={t("Cancel")}
                    confirmLabel={currentDialog.confirmLabel}
                    onConfirm={handleAction}
                    loading={actionLoading}
                />
            )}
        </AppLayout>
    );
}
