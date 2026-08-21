import AppLayout from "@/layouts/app/app-layout";
import { Head } from "@inertiajs/react";

import { assetUrl } from "@/helpers/asset-url";
import { Currencies, InvoicePlan, InvoiceTransaction } from "@/types/admin";
import { useRef } from "react";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Download, Printer } from "lucide-react";
import { Button } from "@/components/ui/button";
import html2canvas from "html2canvas-pro";
import jsPDF from "jspdf";
import { BreadcrumbItem } from "@/types";
import Heading from "@/components/heading";

interface InvoiceProps {
    transaction: InvoiceTransaction;
    settings: any;
    config: any;
    currencies: Currencies[];
    planDetails: InvoicePlan;
    term: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Transactions",
        href: "dashboard.admin.transactions",
    },
    {
        title: "Invoice",
        href: "#",
    },
];

export default function Invoice({
    transaction,
    settings,
    config,
    currencies,
    planDetails,
    term,
}: InvoiceProps) {
    const billing = transaction.billing_details;

    const invoiceRef = useRef<HTMLDivElement>(null);

    const handlePrint = () => {
        window.print();
    };

    const handleDownload = async () => {
        if (!invoiceRef.current) {
            return;
        }

        try {
            const canvas = await html2canvas(invoiceRef.current, {
                scale: 2,
                useCORS: true,
                backgroundColor: "#ffffff",
            });

            const imageData = canvas.toDataURL("image/jpeg", 0.98);

            const pdf = new jsPDF({
                orientation: "portrait",
                unit: "mm",
                format: "a4",
            });

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            const imageWidth = pageWidth;
            const imageHeight = (canvas.height * imageWidth) / canvas.width;

            let heightLeft = imageHeight;
            let position = 0;

            pdf.addImage(
                imageData,
                "JPEG",
                0,
                position,
                imageWidth,
                imageHeight,
            );

            heightLeft -= pageHeight;

            while (heightLeft > 0) {
                position = heightLeft - imageHeight;

                pdf.addPage();

                pdf.addImage(
                    imageData,
                    "JPEG",
                    0,
                    position,
                    imageWidth,
                    imageHeight,
                );

                heightLeft -= pageHeight;
            }

            const filename = `${transaction.invoice_prefix || "TR"}${
                transaction.invoice_number || transaction.transaction_id
            }.pdf`;

            pdf.save(filename);
        } catch (error) {
            console.error("PDF DOWNLOAD ERROR:", error);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Invoice" />

            <div className="page-wrapper">
                <div className="container-fluid">
                    {/* Page Header */}
                    <div className="flex items-center justify-between py-4">
                        <div>
                            <Heading
                                title="Invoice"
                                description="View and download invoice details"
                            />
                        </div>

                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="outline">
                                    <Printer className="size-4" />
                                    Actions
                                </Button>
                            </DropdownMenuTrigger>

                            <DropdownMenuContent align="end">
                                <DropdownMenuItem onClick={handleDownload}>
                                    <Download className="mr-2 size-4" />
                                    Download
                                </DropdownMenuItem>

                                <DropdownMenuItem onClick={handlePrint}>
                                    <Printer className="mr-2 size-4" />
                                    Print
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>

                    {/* Invoice */}
                    <div className="rounded-lg border bg-background shadow-sm">
                        <div ref={invoiceRef} id="invoice" className="p-6">
                            {" "}
                            {/* Header Section */}
                            <div className="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                                {/* Company */}
                                <div>
                                    {settings?.site_logo && (
                                        <img
                                            src={assetUrl(settings.site_logo)}
                                            className="mb-2 max-h-16 max-w-[200px] object-contain"
                                            alt="Company Logo"
                                        />
                                    )}

                                    <div className="text-lg font-semibold">
                                        {billing.from_billing_name}
                                    </div>

                                    <div className="mt-1 text-sm">
                                        {billing.from_billing_address},{" "}
                                        {billing.from_billing_city},{" "}
                                        {billing.from_billing_state}{" "}
                                        {billing.from_billing_country}
                                    </div>

                                    <div className="mt-1 text-sm">
                                        <strong>Email:</strong>{" "}
                                        {billing.from_billing_email}
                                    </div>

                                    {billing.from_billing_phone && (
                                        <div className="mt-1 text-sm">
                                            <strong>Phone:</strong>{" "}
                                            {billing.from_billing_phone}
                                        </div>
                                    )}

                                    {billing.from_vat_number && (
                                        <div className="mt-1 text-sm">
                                            <strong>Tax Number:</strong>{" "}
                                            {billing.from_vat_number}
                                        </div>
                                    )}
                                </div>

                                {/* Invoice Details */}
                                <div className="text-left md:text-right">
                                    <h1 className="text-3xl font-bold">
                                        INVOICE
                                    </h1>

                                    <h4 className="mt-2 text-lg font-semibold">
                                        #{transaction.invoice_prefix}
                                        {transaction.invoice_number}
                                    </h4>
                                </div>
                            </div>
                            {/* Billing Information */}
                            <div className="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                                {/* Bill To */}
                                <div>
                                    <h4 className="mb-2 text-muted-foreground">
                                        Bill To
                                    </h4>

                                    <div className="text-lg font-semibold">
                                        {billing.to_billing_name}
                                    </div>

                                    <div className="mt-1 text-sm">
                                        {billing.to_billing_address},{" "}
                                        {billing.to_billing_city},{" "}
                                        {billing.to_billing_state}{" "}
                                        {billing.to_billing_country}
                                    </div>

                                    <div className="mt-1 text-sm">
                                        <strong>Email:</strong>{" "}
                                        {billing.to_billing_email}
                                    </div>

                                    {billing.to_billing_phone && (
                                        <div className="mt-1 text-sm">
                                            <strong>Phone:</strong>{" "}
                                            {billing.to_billing_phone}
                                        </div>
                                    )}

                                    {billing.to_vat_number && (
                                        <div className="mt-1 text-sm">
                                            <strong>Tax Number:</strong>{" "}
                                            {billing.to_vat_number}
                                        </div>
                                    )}
                                </div>

                                {/* Invoice Meta */}
                                <div className="text-left md:text-right">
                                    <p className="text-sm">
                                        <strong>Date:</strong>{" "}
                                        {new Date(
                                            transaction.transaction_date,
                                        ).toLocaleDateString("en-US", {
                                            month: "short",
                                            day: "2-digit",
                                            year: "numeric",
                                        })}
                                    </p>

                                    <p className="mt-2 text-sm">
                                        <strong>Payment Terms:</strong> {term}
                                    </p>

                                    <h5 className="mt-4 text-base font-semibold">
                                        Balance Due:{" "}
                                        {billing.invoice_amount === 0
                                            ? "0"
                                            : "0"}
                                    </h5>
                                </div>
                            </div>
                            {/* Items */}
                            <div className="overflow-x-auto">
                                <table className="w-full border text-sm">
                                    <thead className="border-b">
                                        <tr>
                                            <th className="px-4 py-3 text-left">
                                                Item
                                            </th>

                                            <th className="px-4 py-3 text-right">
                                                Quantity
                                            </th>

                                            <th className="px-4 py-3 text-right">
                                                Rate
                                            </th>

                                            <th className="px-4 py-3 text-right">
                                                Amount
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr className="border-b font-semibold">
                                            <td className="px-4 py-3">
                                                {planDetails.name} -{" "}
                                                {planDetails.price}/{term}
                                            </td>

                                            <td className="px-4 py-3 text-right">
                                                1
                                            </td>

                                            <td className="px-4 py-3 text-right">
                                                {planDetails.price}
                                            </td>

                                            <td className="px-4 py-3 text-right">
                                                {planDetails.price}
                                            </td>
                                        </tr>
                                    </tbody>

                                    <tfoot className="font-semibold">
                                        {/* Subtotal */}
                                        <tr>
                                            <td
                                                colSpan={3}
                                                className="px-4 py-2 text-right"
                                            >
                                                Subtotal
                                            </td>

                                            <td className="px-4 py-2 text-right">
                                                {billing.subtotal}
                                            </td>
                                        </tr>

                                        {/* Tax */}
                                        {billing.tax_amount > 0 && (
                                            <tr>
                                                <td
                                                    colSpan={3}
                                                    className="px-4 py-2 text-right"
                                                >
                                                    {billing.tax_name} (
                                                    {billing.tax_value}%)
                                                </td>

                                                <td className="px-4 py-2 text-right">
                                                    {billing.tax_amount}
                                                </td>
                                            </tr>
                                        )}

                                        {/* Coupon */}
                                        {billing.applied_coupon && (
                                            <tr>
                                                <td
                                                    colSpan={3}
                                                    className="px-4 py-2 text-right"
                                                >
                                                    Applied Coupon:{" "}
                                                    {billing.applied_coupon}
                                                </td>

                                                <td className="px-4 py-2 text-right">
                                                    -{billing.discounted_price}
                                                </td>
                                            </tr>
                                        )}

                                        {/* Total */}
                                        <tr>
                                            <td
                                                colSpan={3}
                                                className="px-4 py-2 text-right"
                                            >
                                                <strong>Total</strong>
                                            </td>

                                            <td className="px-4 py-2 text-right">
                                                <strong>
                                                    {billing.invoice_amount}
                                                </strong>
                                            </td>
                                        </tr>

                                        {/* Amount Paid */}
                                        <tr>
                                            <td
                                                colSpan={3}
                                                className="px-4 py-2 text-right"
                                            >
                                                Amount Paid
                                            </td>

                                            <td className="px-4 py-2 text-right">
                                                {billing.invoice_amount}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            {/* Notes */}
                            <div className="mt-8">
                                <strong>Notes</strong>

                                <p className="mt-1 text-sm text-muted-foreground">
                                    Payment from{" "}
                                    {transaction.payment_gateway_name}
                                    <br />
                                    Transaction ID:{" "}
                                    {transaction.transaction_id || "-"}
                                </p>
                            </div>
                            {/* Footer Message */}
                            <p className="mt-8 text-center text-sm text-muted-foreground">
                                {config?.[29]?.config_value ??
                                    "Thank you for your business!"}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
