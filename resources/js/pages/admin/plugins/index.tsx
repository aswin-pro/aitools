import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";
import { BreadcrumbItem, SharedData } from "@/types";
import { Head, router, useForm, usePage } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";
import { Settings, Trash2, Upload } from "lucide-react";

interface Plugin {
    plugin_id: string;
    name: string;
    description: string;
    version: string;
    img: string;
    main_route: string;
}

interface Props {
    plugins: Plugin[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Plugins",
        href: "#",
    },
];

const systemPlugins = [
    "CookieConsent",
    "GoogleAdSense",
    "GoogleAnalytics",
    "GoogleOAuth",
    "GoogleRecaptcha",
    "TawkChat",
    "WhatsAppChatButton",
    "SMTP",
];

export default function Index({ plugins }: Props) {
    const { t } = useTranslation();
    const { flash } = usePage<SharedData>().props;

    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [pluginToDelete, setPluginToDelete] = useState<Plugin | null>(null);
    const [actionLoading, setActionLoading] = useState(false);

    const fileInputRef = useRef<HTMLInputElement | null>(null);
    const lastMessage = useRef<string | null>(null);

    const uploadForm = useForm<{
        zip_file: File | null;
    }>({
        zip_file: null,
    });

    useEffect(() => {
        if (flash?.success && lastMessage.current !== flash.success) {
            toast.success(flash.success);
            lastMessage.current = flash.success;
        }

        if (flash?.error && lastMessage.current !== flash.error) {
            toast.error(flash.error);
            lastMessage.current = flash.error;
        }
    }, [flash]);

    const handleUploadClick = () => {
        fileInputRef.current?.click();
    };

    const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0];

        if (!file) {
            return;
        }

        uploadForm.setData("zip_file", file);

        uploadForm.post(route("dashboard.admin.plugin.upload"), {
            forceFormData: true,
            preserveScroll: true,

            onSuccess: () => {
                uploadForm.reset();
            },

            onFinish: () => {
                if (fileInputRef.current) {
                    fileInputRef.current.value = "";
                }
            },
        });
    };

    const handleDeleteClick = (plugin: Plugin) => {
        setPluginToDelete(plugin);
        setDeleteDialogOpen(true);
    };

    const handleDelete = () => {
        if (!pluginToDelete) {
            return;
        }

        setActionLoading(true);

        router.delete(
            route("dashboard.admin.plugins.delete", pluginToDelete.plugin_id),
            {
                preserveScroll: true,

                onSuccess: () => {
                    setDeleteDialogOpen(false);
                    setPluginToDelete(null);
                },

                onFinish: () => {
                    setActionLoading(false);
                },
            },
        );
    };

    const defaultPlugins = plugins.filter((plugin) =>
        systemPlugins.includes(plugin.plugin_id),
    );

    const customPlugins = plugins.filter(
        (plugin) => !systemPlugins.includes(plugin.plugin_id),
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Plugins")} />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between gap-4">
                    <Heading
                        title={t("Plugins")}
                        description={t(
                            "Manage and configure your installed plugins.",
                        )}
                    />

                    <div>
                        <input
                            ref={fileInputRef}
                            type="file"
                            accept=".zip"
                            className="hidden"
                            onChange={handleFileChange}
                        />

                        <Button
                            onClick={() => fileInputRef.current?.click()}
                            disabled={uploadForm.processing}
                        >
                            <Upload className="size-4" />

                            {uploadForm.processing
                                ? t("Uploading...")
                                : t("Upload")}
                        </Button>
                    </div>
                </div>

                {/* Plugin list */}
                {plugins.length === 0 ? (
                    <div className="flex min-h-[350px] items-center justify-center rounded-lg border border-dashed">
                        <div className="max-w-md text-center">
                            <h3 className="text-lg font-medium">
                                {t("Coming Soon!")}
                            </h3>

                            <p className="mt-2 text-sm text-muted-foreground">
                                {t(
                                    "Plugins are used to add extra functionality to AI Tools.",
                                )}
                            </p>
                        </div>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        {plugins.map((plugin) => {
                            const isSystem = systemPlugins.includes(
                                plugin.plugin_id,
                            );

                            return (
                                <PluginCard
                                    key={plugin.plugin_id}
                                    plugin={plugin}
                                    isSystem={isSystem}
                                    t={t}
                                    onDelete={handleDeleteClick}
                                />
                            );
                        })}
                    </div>
                )}

                {/* Delete confirmation */}
                <ConfirmDialog
                    open={deleteDialogOpen}
                    onOpenChange={setDeleteDialogOpen}
                    title={t("Delete Plugin")}
                    icon={<Trash2 className="size-6" />}
                    description={
                        pluginToDelete
                            ? t(
                                  `Are you sure you want to remove ${pluginToDelete.name}? This action cannot be undone.`,
                              )
                            : t(
                                  "Are you sure you want to remove this plugin? This action cannot be undone.",
                              )
                    }
                    confirmLabel={t("Remove")}
                    cancelLabel={t("Cancel")}
                    onConfirm={handleDelete}
                    loading={actionLoading}
                />
            </div>
        </AppLayout>
    );
}

function PluginCard({
    plugin,
    isSystem,
    t,
    onDelete,
}: {
    plugin: Plugin;
    isSystem: boolean;
    t: (key: string) => string;
    onDelete: (plugin: Plugin) => void;
}) {
    return (
        <Card className="h-full border-border/70 transition-all hover:border-border hover:shadow-sm">
            <CardContent className="flex h-full  flex-col p-5">
                <div className="flex items-start gap-4">
                    <div className="flex size-12 shrink-0 items-center justify-center rounded-xl border bg-muted/30">
                        <img
                            src={`/img/plugins/${plugin.img}`}
                            alt={plugin.name}
                            className="size-8 object-contain"
                            onError={(event) => {
                                event.currentTarget.src =
                                    "/img/plugins/default.png";
                            }}
                        />
                    </div>

                    <div className="min-w-0 flex-1">
                        <h3
                            className=" font-semibold leading-5"
                            title={t(plugin.name)}
                        >
                            {t(plugin.name)}
                        </h3>

                        <span className="mt-1 inline-block text-xs text-muted-foreground">
                            v{plugin.version}
                        </span>

                        <p
                            className="mt-1 line-clamp-2 text-sm leading-5 text-muted-foreground"
                            title={t(plugin.description)}
                        >
                            {t(plugin.description)}
                        </p>
                    </div>

             
                    <div className="flex shrink-0 flex-col items-center gap-2">
                        <Button
                            type="button"
                            variant="outline"
                            size="icon"
                            className="size-9 border-border/70 bg-background text-foreground/70 shadow-none hover:bg-muted hover:text-foreground"
                            onClick={() =>
                                router.visit(route(plugin.main_route))
                            }
                            aria-label={t("Settings")}
                        >
                            <Settings className="size-5" />
                        </Button>

                        {!isSystem && (
                            <Button
                                type="button"
                                variant="outline"
                                size="icon"
                                className="size-9 border-border/70 bg-background text-foreground/70 shadow-none hover:border-destructive/30 hover:bg-destructive/5 hover:text-destructive"
                                onClick={() => onDelete(plugin)}
                                aria-label={t("Delete")}
                            >
                                <Trash2 className="size-5" />
                            </Button>
                        )}
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}
