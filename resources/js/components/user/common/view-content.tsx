import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { jsPDF } from 'jspdf';
import { Copy, Download, Settings2 } from 'lucide-react';
import 'quill/dist/quill.snow.css';
import { toast } from 'sonner';
import { RenderMdContent } from './render-md-content';

export const ViewContent = ({
    t,
    content,
    page,
}: {
    t: (key: string) => string;
    content: string;
    page: string;
}) => {
    // export markdown
    const exportMarkdown = () => {
        const blob = new Blob([content], {
            type: 'text/markdown;charset=utf-8',
        });

        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');

        link.href = url;
        link.download = `generated-content-${crypto.randomUUID()}.md`;
        link.click();

        URL.revokeObjectURL(url);
    };

    // copy markdown
    const copyMarkdown = async () => {
        await navigator.clipboard.writeText(content);
        toast.success(t('Markdown copied!'));
    };

    // copy text
    const copyText = async () => {
        await navigator.clipboard.writeText(content);
        toast.success(t('Text copied!'));
    };

    // download pdf
    const downloadPdf = () => {
        const pdf = new jsPDF({
            unit: 'mm',
            format: 'a4',
        });

        const margin = 15;
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();

        pdf.setFont('helvetica', 'normal');
        pdf.setFontSize(11);

        const lines = pdf.splitTextToSize(content, pageWidth - margin * 2);

        let y = margin;

        lines.forEach((line: string) => {
            if (y > pageHeight - margin) {
                pdf.addPage();
                y = margin;
            }

            pdf.text(line, margin, y);
            y += 6; // Line height
        });

        pdf.save(`speech-to-text-${crypto.randomUUID()}.pdf`);
    };

    function actionButtons() {
        return page === 'code-generator' || page === 'content-generator' ? (
            <DropdownMenu>
                <DropdownMenuTrigger asChild>
                    <Button type="button" variant="outline">
                        <Settings2 className="h-4 w-4" />
                        {t('Actions')}
                    </Button>
                </DropdownMenuTrigger>

                <DropdownMenuContent align="end">
                    {/* Export Markdown */}
                    <DropdownMenuItem onClick={exportMarkdown}>
                        <Download className="h-4 w-4" />
                        {t('Export as Markdown')}
                    </DropdownMenuItem>

                    {/* Copy Markdown */}
                    <DropdownMenuItem onClick={copyMarkdown}>
                        <Copy className="h-4 w-4" />
                        {t('Copy Markdown')}
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        ) : (
            <DropdownMenu>
                <DropdownMenuTrigger asChild>
                    <Button type="button" variant="outline">
                        <Settings2 className="h-4 w-4" />
                        {t('Actions')}
                    </Button>
                </DropdownMenuTrigger>

                <DropdownMenuContent align="end">
                    {/* Copy Text */}
                    <DropdownMenuItem onClick={copyText}>
                        <Copy className="h-4 w-4" />
                        {t('Copy')}
                    </DropdownMenuItem>

                    {/* Export PDF */}
                    <DropdownMenuItem onClick={downloadPdf}>
                        <Download className="h-4 w-4" />
                        {t('Download PDF')}
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        );
    }

    return (
        <div className="space-y-4">
            {/* Export */}
            <div className="flex justify-end">{actionButtons()}</div>

            {/* Content */}
            <RenderMdContent content={content} />

            {/* Warning Text */}
            {route().current('dashboard.user.code-generator.*') && (
                <div className="mt-4 text-center">
                    <p className="text-xs text-muted-foreground">
                        {t(
                            'AI can make mistakes. Please review the content before using it.',
                        )}
                    </p>
                </div>
            )}
        </div>
    );
};
