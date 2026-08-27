import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { Head } from "@inertiajs/react";
import type { BreadcrumbItem, LaravelPagination } from "@/types";
import { Mail, LogIn } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { assetUrl } from "@/helpers/asset-url";
import { useInitials } from "@/hooks/use-initials";
import { useState } from "react";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";
import GeneratedContents from "./generated-contents";
import GeneratedImages from "./generated-images";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Users",
        href: route("dashboard.admin.users"),
    },
    {
        title: "View User",
        href: "#",
    },
];

interface UserDetails {
    id: number;
    name: string;
    email: string | null;
    profile_image: string | null;
    role_id: number;
}

interface GeneratedContent {
    id: number;
    name: string;
    word_count: number;
    updated_at: string;
}

interface GeneratedImage {
    id: number;
    generate_id: string;
    generate_by: string;
    name: string;
    type: string;
    prompt: string;
    n: number;
    size: string;
    format: string;
    result: string;
    bookmark: boolean;
    status: boolean;
    created_at: string;
    updated_at: string;
}

interface ViewUserProps {
    user_details: UserDetails;
    settings: any;
    contents: LaravelPagination<GeneratedContent>;
    images: LaravelPagination<GeneratedImage>;
}

export default function ViewUser({
    user_details,
    contents,
    images,
}: ViewUserProps) {
    const email = user_details.email || "Not Available";
    const getInitials = useInitials();

    const [loginDialogOpen, setLoginDialogOpen] = useState(false);

    const handleLoginAsUser = () => {
        window.location.href = route(
            "dashboard.admin.login-as.user",
            user_details.id,
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View User" />

            <div className="mb-6">
                <Heading
                    title="View User"
                    description="View customer details and activity"
                />
            </div>

            <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                <div className="p-6 md:p-8">
                    <div className="flex flex-col gap-6 md:flex-row md:items-center">
                        <div className="flex min-w-0 flex-1 items-center gap-5">
                            <div className="shrink-0">
                                <Avatar className="size-20 md:size-24">
                                    <AvatarImage
                                        src={assetUrl(
                                            user_details.profile_image,
                                        )}
                                        alt={user_details.name}
                                    />

                                    <AvatarFallback className="bg-neutral-200 text-xl font-semibold text-black dark:bg-neutral-700 dark:text-white">
                                        {getInitials(user_details.name)}
                                    </AvatarFallback>
                                </Avatar>
                            </div>

                            {/* User Information */}
                            <div className="min-w-0">
                                <div className="flex flex-wrap items-center gap-2">
                                    <h2 className="truncate text-xl font-semibold tracking-tight md:text-2xl">
                                        {user_details.name}
                                    </h2>

                                    {user_details.role_id === 2 && (
                                        <span className="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            Customer
                                        </span>
                                    )}
                                </div>

                                <p className="mt-1 truncate text-sm text-muted-foreground">
                                    {email}
                                </p>
                            </div>
                        </div>

                        <div className="flex flex-col gap-2 sm:flex-row">
                            <Button variant="outline" asChild>
                                <a
                                    href={
                                        user_details.email
                                            ? `mailto:${user_details.email}`
                                            : "#"
                                    }
                                >
                                    <Mail className="size-4" />
                                    Email
                                </a>
                            </Button>

                            <Button onClick={() => setLoginDialogOpen(true)}>
                                <LogIn className="size-4" />
                                Login via Admin
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <div className="mt-6">
                <GeneratedContents contents={contents} />
            </div>

            <div className="mt-6">
                <GeneratedImages images={images} />
            </div>

            <ConfirmDialog
                open={loginDialogOpen}
                onOpenChange={setLoginDialogOpen}
                icon={<LogIn className="size-7" />}
                title="Login as User?"
                description="If you proceed, you will leave your current admin session and login as this user."
                cancelLabel="Cancel"
                confirmLabel="Yes, proceed"
                onConfirm={handleLoginAsUser}
            />
        </AppLayout>
    );
}
