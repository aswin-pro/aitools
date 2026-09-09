import AppLayout from '@/layouts/app/app-layout';
import { Head } from '@inertiajs/react';

import { useRef } from 'react';

import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Invoice } from '@/components/user/subscriptions/invoice';
import { BreadcrumbItem } from '@/types';
import html2canvas from 'html2canvas-pro';
import jsPDF from 'jspdf';
import { Download, Printer } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { Transaction } from '@/types/user';

export default function Index({ transaction }: { transaction: Transaction }) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Subscriptions',
            href: route('dashboard.user.subscriptions.index'),
        },
        {
            title: 'Invoice',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    // refererance
    const invoiceRef = useRef<HTMLDivElement>(null);

    // handle download
    const handleDownload = async () => {
        if (!invoiceRef.current) {
            return;
        }

        try {
            const canvas = await html2canvas(invoiceRef.current, {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff',
            });

            const imageData = canvas.toDataURL('image/jpeg', 0.98);

            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4',
            });

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            const imageWidth = pageWidth;
            const imageHeight = (canvas.height * imageWidth) / canvas.width;

            let heightLeft = imageHeight;
            let position = 0;

            pdf.addImage(
                imageData,
                'JPEG',
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
                    'JPEG',
                    0,
                    position,
                    imageWidth,
                    imageHeight,
                );

                heightLeft -= pageHeight;
            }

            const filename = `${transaction.invoice_prefix || 'TR'}${
                transaction.invoice_number || transaction.transaction_id
            }.pdf`;

            pdf.save(filename);
        } catch (error) {
            console.error('PDF DOWNLOAD ERROR:', error);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Invoice" />

            {/* Page Header */}
            <div className="flex items-center justify-between">
                <div>
                    <Heading
                        t={t}
                        title="Invoice"
                        description="View your invoice details"
                    />
                </div>

                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="outline">
                            <Printer className="size-4" />
                            {t('Actions')}
                        </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="end">
                        <DropdownMenuItem onClick={handleDownload}>
                            <Download className="mr-2 size-4" />
                            {t('Download')}
                        </DropdownMenuItem>

                        <DropdownMenuItem onClick={() => window.print()}>
                            <Printer className="mr-2 size-4" />
                            {t('Print')}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            {/* Invoice */}
            <div className='border rounded-3xl'>
                <div ref={invoiceRef} id="invoice-print" className="printable-invoice">
                    <Invoice t={t} transaction={transaction} />
                </div>
            </div>
        </AppLayout>
    );
}
