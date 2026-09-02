import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem, LaravelPagination, NavigateParams } from "@/types";
import { Head, router, useForm } from "@inertiajs/react";
import { useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import { DataTable } from "@/components/table/data-table";
import { toast } from "sonner";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";
import { UserCheck, UserX } from "lucide-react";
import { getColumns, PaymentMethod } from "./columns";
import { FormSheet } from "@/components/admin/form-sheet";

interface PaymentConfig {
    config_key: string;
    config_value: string | null;
}

interface PaymentMethodsProps {
    payment_methods: LaravelPagination<PaymentMethod>;
    config: PaymentConfig[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Payment Methods",
        href: "#",
    },
];

export default function Index({
    payment_methods,
    config,
}: PaymentMethodsProps) {
    const { t } = useTranslation();

    const [confirmOpen, setConfirmOpen] = useState(false);
    const [editOpen, setEditOpen] = useState(false);
    const [configureOpen, setConfigureOpen] = useState(false);
    const [actionLoading, setActionLoading] = useState(false);

    const [selectedPaymentMethod, setSelectedPaymentMethod] =
        useState<PaymentMethod | null>(null);

    const editForm = useForm({
        payment_gateway_id: "",
        payment_gateway_name: "",
        payment_gateway_image: null as File | null,
    });

    const configureForm = useForm({
        gateway_id: "",
        paypal_mode: "",
        paypal_client_key: "",
        paypal_secret: "",
        razorpay_client_key: "",
        razorpay_secret: "",
        clientId: "",
        clientVersion: "",
        clientSecret: "",
        stripe_publishable_key: "",
        stripe_secret: "",
        paystack_public_key: "",
        paystack_secret: "",
        merchant_email: "",
        mollie_key: "",
        transaction_cloud_login: "",
        transaction_cloud_password: "",
        bank_transfer: "",
        mercado_pago_public_key: "",
        mercado_pago_access_token: "",
        toyyibpay_mode: "",
        toyyibpay_api_key: "",
        toyyibpay_category_code: "",
        flw_public_key: "",
        flw_secret_key: "",
        flw_encryption_key: "",
    });

    const getConfigValue = (key: string) => {
        return (
            config.find((item) => item.config_key === key)?.config_value ?? ""
        );
    };

    const openActionDialog = (paymentMethod: PaymentMethod) => {
        setSelectedPaymentMethod(paymentMethod);
        setConfirmOpen(true);
    };

    const handleAction = () => {
        if (!selectedPaymentMethod) {
            return;
        }

        setActionLoading(true);

        router.get(
            route("dashboard.admin.delete.payment.method"),
            {
                id: selectedPaymentMethod.id,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    setConfirmOpen(false);
                    setSelectedPaymentMethod(null);
                },
                onError: () => {
                    toast.error(
                        t("Unable to update the payment method status."),
                    );
                },
                onFinish: () => {
                    setActionLoading(false);
                },
            },
        );
    };

    const handleEdit = (paymentMethod: PaymentMethod) => {
        setSelectedPaymentMethod(paymentMethod);

        editForm.setData({
            payment_gateway_id: String(paymentMethod.id),
            payment_gateway_name: paymentMethod.display_name ?? "",
            payment_gateway_image: null,
        });

        editForm.clearErrors();
        setEditOpen(true);
    };

    const handleEditSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        editForm.post(
            route("dashboard.admin.update.payment.method"),
            {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: () => {
                    setEditOpen(false);
                    setSelectedPaymentMethod(null);
                    editForm.reset();

                    toast.success(
                        t("Payment Gateway Details Updated Successfully!"),
                    );
                },
                onError: () => {
                    toast.error(
                        t("Unable to update payment method."),
                    );
                },
            },
        );
    };

    const handleConfigure = (paymentMethod: PaymentMethod) => {
        setSelectedPaymentMethod(paymentMethod);

        configureForm.setData({
            gateway_id: String(paymentMethod.id),

            paypal_mode: getConfigValue("paypal_mode"),
            paypal_client_key: getConfigValue("paypal_client_id"),
            paypal_secret: getConfigValue("paypal_secret"),

            razorpay_client_key: getConfigValue("razorpay_key"),
            razorpay_secret: getConfigValue("razorpay_secret"),

            clientId: getConfigValue("phonepe_client_id"),
            clientVersion: getConfigValue("phonepe_client_version"),
            clientSecret: getConfigValue("phonepe_client_secret"),

            stripe_publishable_key: getConfigValue(
                "stripe_publishable_key",
            ),
            stripe_secret: getConfigValue("stripe_secret"),

            paystack_public_key: getConfigValue(
                "paystack_public_key",
            ),
            paystack_secret: getConfigValue(
                "paystack_secret_key",
            ),
            merchant_email: getConfigValue("merchant_email"),

            mollie_key: getConfigValue("mollie_key"),

            transaction_cloud_login: getConfigValue(
                "transaction_cloud_api_key",
            ),
            transaction_cloud_password: getConfigValue(
                "transaction_cloud_api_password",
            ),

            bank_transfer: getConfigValue("bank_transfer"),

            mercado_pago_public_key: getConfigValue(
                "mercado_pago_public_key",
            ),
            mercado_pago_access_token: getConfigValue(
                "mercado_pago_access_token",
            ),

            toyyibpay_mode: getConfigValue("toyyibpay_mode"),
            toyyibpay_api_key: getConfigValue(
                "toyyibpay_api_key",
            ),
            toyyibpay_category_code: getConfigValue(
                "toyyibpay_category_code",
            ),

            flw_public_key: getConfigValue("flw_public_key"),
            flw_secret_key: getConfigValue("flw_secret_key"),
            flw_encryption_key: getConfigValue(
                "flw_encryption_key",
            ),
        });

        configureForm.clearErrors();
        setConfigureOpen(true);
    };

const getConfigureFields = () => {
    if (!selectedPaymentMethod) {
        return [];
    }

    switch (selectedPaymentMethod.id) {
        case 1:
            return [
                {
                    type: "select" as const,
                    name: "paypal_mode",
                    label: t("PayPal Mode"),
                    placeholder: t("Select PayPal mode"),
                    options: [
                        {
                            value: "sandbox",
                            label: t("Sandbox"),
                        },
                        {
                            value: "live",
                            label: t("Live"),
                        },
                    ],
                },
                {
                    type: "input" as const,
                    name: "paypal_client_key",
                    label: t("PayPal Client Key"),
                    placeholder: t("Enter PayPal client key"),
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "paypal_secret",
                    label: t("PayPal Secret"),
                    placeholder: t("Enter PayPal secret"),
                    inputType: "password",
                    required: true,
                },
            ];

        case 2:
            return [
                {
                    type: "input" as const,
                    name: "razorpay_client_key",
                    label: t("Razorpay Client Key"),
                    placeholder: t("Enter Razorpay client key"),
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "razorpay_secret",
                    label: t("Razorpay Secret"),
                    placeholder: t("Enter Razorpay secret"),
                    inputType: "password",
                    required: true,
                },
            ];

        case 8:
            return [
                {
                    type: "input" as const,
                    name: "clientId",
                    label: t("Client ID"),
                    placeholder: t("Enter client ID"),
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "clientVersion",
                    label: t("Client Version"),
                    placeholder: t("Enter client version"),
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "clientSecret",
                    label: t("Client Secret"),
                    placeholder: t("Enter client secret"),
                    inputType: "password",
                    required: true,
                },
            ];

        case 3:
            return [
                {
                    type: "input" as const,
                    name: "stripe_publishable_key",
                    label: t("Stripe Publishable Key"),
                    placeholder: t("Enter Stripe publishable key"),
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "stripe_secret",
                    label: t("Stripe Secret"),
                    placeholder: t("Enter Stripe secret"),
                    inputType: "password",
                    required: true,
                },
            ];

        case 4:
            return [
                {
                    type: "input" as const,
                    name: "paystack_public_key",
                    label: t("Paystack Public Key"),
                    placeholder: t("Enter Paystack public key"),
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "paystack_secret",
                    label: t("Paystack Secret"),
                    placeholder: t("Enter Paystack secret"),
                    inputType: "password",
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "merchant_email",
                    label: t("Merchant Email"),
                    placeholder: t("Enter merchant email"),
                    inputType: "email",
                    required: true,
                },
            ];

        case 5:
            return [
                {
                    type: "input" as const,
                    name: "mollie_key",
                    label: t("Mollie Key"),
                    placeholder: t("Enter Mollie key"),
                    inputType: "password",
                    required: true,
                },
            ];

        case 7:
            return [
                {
                    type: "input" as const,
                    name: "transaction_cloud_login",
                    label: t("Transaction Cloud Login"),
                    placeholder: t("Enter Transaction Cloud login"),
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "transaction_cloud_password",
                    label: t("Transaction Cloud Password"),
                    placeholder: t("Enter Transaction Cloud password"),
                    inputType: "password",
                    required: true,
                },
            ];

        case 6:
            return [
                {
                    type: "textarea" as const,
                    name: "bank_transfer",
                    label: t("Bank Transfer"),
                    placeholder: t("Enter bank transfer details"),
                },
            ];

        case 9:
            return [
                {
                    type: "input" as const,
                    name: "mercado_pago_public_key",
                    label: t("Mercado Pago Public Key"),
                    placeholder: t("Enter Mercado Pago public key"),
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "mercado_pago_access_token",
                    label: t("Mercado Pago Access Token"),
                    placeholder: t("Enter Mercado Pago access token"),
                    inputType: "password",
                    required: true,
                },
            ];

        case 10:
            return [
                {
                    type: "select" as const,
                    name: "toyyibpay_mode",
                    label: t("ToyyibPay Mode"),
                    placeholder: t("Select ToyyibPay mode"),
                    options: [
                        {
                            value: "sandbox",
                            label: t("Sandbox"),
                        },
                        {
                            value: "live",
                            label: t("Live"),
                        },
                    ],
                },
                {
                    type: "input" as const,
                    name: "toyyibpay_api_key",
                    label: t("ToyyibPay API Key"),
                    placeholder: t("Enter ToyyibPay API key"),
                    inputType: "password",
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "toyyibpay_category_code",
                    label: t("ToyyibPay Category Code"),
                    placeholder: t("Enter ToyyibPay category code"),
                    required: true,
                },
            ];

        case 11:
            return [
                {
                    type: "input" as const,
                    name: "flw_public_key",
                    label: t("Flutterwave Public Key"),
                    placeholder: t("Enter Flutterwave public key"),
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "flw_secret_key",
                    label: t("Flutterwave Secret Key"),
                    placeholder: t("Enter Flutterwave secret key"),
                    inputType: "password",
                    required: true,
                },
                {
                    type: "input" as const,
                    name: "flw_encryption_key",
                    label: t("Flutterwave Encryption Key"),
                    placeholder: t("Enter Flutterwave encryption key"),
                    inputType: "password",
                    required: true,
                },
            ];

        default:
            return [];
    }
};

    const handleConfigureSubmit = (
        e: React.FormEvent<HTMLFormElement>,
    ) => {
        e.preventDefault();

        if (!selectedPaymentMethod) {
            return;
        }

        configureForm.post(
            route(
                "dashboard.admin.update.payment.configuration",
                selectedPaymentMethod.id,
            ),
            {
                preserveScroll: true,
                onSuccess: () => {
                    setConfigureOpen(false);
                    setSelectedPaymentMethod(null);
                    configureForm.reset();

                    toast.success(
                        t(
                            "Payment configuration updated successfully!",
                        ),
                    );
                },
                onError: () => {
                    toast.error(
                        t(
                            "Unable to update payment configuration.",
                        ),
                    );
                },
            },
        );
    };

    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ["payment_methods"],
            data: params,
        });
    };

    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: payment_methods.current_page - 1,
                pageSize: payment_methods.per_page,
                t,
                onEdit: handleEdit,
                onConfigure: handleConfigure,
                onAction: openActionDialog,
            }),
        [
            payment_methods.current_page,
            payment_methods.per_page,
            t,
        ],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Payment Methods")} />

            <Heading
                title={t("Payment Methods")}
                description={t(
                    "Manage your payment methods and configurations",
                )}
            />

            <div className="">
                <DataTable
                    columns={columns}
                    data={payment_methods.data}
                    pageIndex={payment_methods.current_page - 1}
                    pageSize={payment_methods.per_page}
                    totalCount={payment_methods.total}
                    initialSearch={route().params.search ?? ""}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: payment_methods.per_page,
                            search: route().params.search,
                        })
                    }
                    onPageSizeChange={(size) =>
                        navigate({
                            page: 1,
                            per_page: size,
                            search: route().params.search,
                        })
                    }
                    onSearch={(search) =>
                        navigate({
                            page: 1,
                            search,
                        })
                    }
                />
            </div>

            {selectedPaymentMethod && (
                <ConfirmDialog
                    open={confirmOpen}
                    onOpenChange={setConfirmOpen}
                    icon={
                        selectedPaymentMethod.status === 0 ? (
                            <UserCheck className="size-7 text-green-600" />
                        ) : (
                            <UserX className="size-7 text-destructive" />
                        )
                    }
                    title={
                        selectedPaymentMethod.status === 0
                            ? t("Activate payment method?")
                            : t("Deactivate payment method?")
                    }
                    description={t(
                        "If you proceed, you will active/deactivate this payment method data.",
                    )}
                    cancelLabel={t("Cancel")}
                    confirmLabel={t("Yes, proceed")}
                    onConfirm={handleAction}
                    loading={actionLoading}
                />
            )}

            <FormSheet
                open={editOpen}
                onOpenChange={setEditOpen}
                title={t("Edit Payment Method")}
                description={t(
                    "Update the payment gateway name and logo",
                )}
                form={editForm}
                fields={[
                    {
                        type: "input",
                        name: "payment_gateway_name",
                        label: t("Payment Gateway Name"),
                        placeholder: t("Enter payment gateway name"),
                        required: true,
                    },
                    {
                        type: "file",
                        name: "payment_gateway_image",
                        label: t("Payment Gateway Logo"),
                    },
                ]}
                onSubmit={handleEditSubmit}
                submitLabel={t("Update")}
                cancelLabel={t("Cancel")}
            />

            <FormSheet
                open={configureOpen}
                onOpenChange={setConfigureOpen}
                title={t("Configure Payment Method")}
                description={
                    selectedPaymentMethod?.display_name ??
                    t("Payment Configuration")
                }
                form={configureForm}
                fields={getConfigureFields()}
                onSubmit={handleConfigureSubmit}
                submitLabel={t("Save")}
                cancelLabel={t("Cancel")}
            />
        </AppLayout>
    );
}