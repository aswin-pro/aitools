import { Form, Head } from "@inertiajs/react";
import { useState } from "react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";
import { FileText } from "lucide-react";

import AppLayout from "@/layouts/app/app-layout";
import SettingsLayout from "@/layouts/settings/layout";
import HeadingSmall from "@/components/heading-small";
import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { type BreadcrumbItem } from "@/types";
import { Label } from "@/components/ui/label";
import { MultiSelect } from "@/components/admin/multi-select";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "System",
        href: route("dashboard.admin.system.login-activity"),    
    },
    {
        title: "Sitemap",
        href: "#",
    },
];

export default function Index() {
    const { t } = useTranslation();

    const [categories, setCategories] = useState<string[]>([]);

    const categoryOptions = [
        {
            value: "all",
            label: t("All"),
        },
        {
            value: "pages",
            label: t("Website Pages"),
        },
        {
            value: "blog",
            label: t("Blogs"),
        },
        {
            value: "webtools",
            label: t("Web Tools"),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Generate Sitemap")} />

            <div className=" space-y-6">
                <HeadingSmall
                    title={t("Generate Sitemap")}
                    description={t(
                        "Select the content categories you want to include in your sitemap.",
                    )}
                />

                <Form
                    action={route("dashboard.admin.system.generate.sitemap")}
                    method="post"
                    resetOnSuccess={false}
                    options={{
                        preserveScroll: true,
                    }}
                    onSuccess={() => {
                        toast.success(t("Sitemap generated successfully!"));
                    }}
                    onError={() => {
                        toast.error(t("Unable to generate sitemap."));
                    }}
                >
                    {({ errors, processing, clearErrors }) => (
                        <div className=" space-y-2">
                            <Label className="text-sm font-medium" required>
                                {t("Categories")}
                            </Label>
                            <div className="flex items-center flex-wrap gap-2">
                                <div className="w-[500px]">
                                    <MultiSelect
                                        options={categoryOptions}
                                        value={categories}
                                        onChange={(value: any) => {
                                            setCategories(value);
                                            clearErrors("categories");
                                        }}
                                        placeholder={t("Select categories")}
                                        searchPlaceholder={t(
                                            "Search categories...",
                                        )}
                                        emptyMessage={t("No categories found.")}
                                    />
                                </div>

                                {categories.map((category) => (
                                    <input
                                        key={category}
                                        type="hidden"
                                        name="categories[]"
                                        value={category}
                                    />
                                ))}

                                <InputError message={errors.categories} />

                                <Button
                                    type="submit"
                                    disabled={
                                        processing || categories.length === 0
                                    }
                                >
                                    <LoadingSwap isLoading={processing}>
                                        <span className="flex items-center">
                                            <FileText className="mr-2 size-4" />
                                            {t("Generate Sitemap")}
                                        </span>
                                    </LoadingSwap>
                                </Button>
                            </div>
                        </div>
                    )}
                </Form>
            </div>
        </AppLayout>
    );
}
