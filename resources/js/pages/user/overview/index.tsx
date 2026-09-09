import { AICreditsChart } from '@/components/user/overview/ai-credits-chart';
import { AIImageCreditsChart } from '@/components/user/overview/ai-image-credits-chart';
import { SectionCards } from '@/components/user/overview/section-cards';
import AppLayout from '@/layouts/app/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Chart, Summary } from '@/types/user';
import { Head } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Index({
    summary,
    charts,
}: {
    summary: Summary;
    charts: Chart;
}) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Overview',
            href: '#',
        },
    ];

    // transalation
    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />

            <div className="mb-4 flex flex-1 flex-col space-y-5">
                <div className="@container/main flex flex-1 flex-col gap-4">
                    <div className="flex flex-col gap-4">
                        <SectionCards t={t} summary={summary} />
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    {/* AI Credits Chart */}
                    <div>
                        <AICreditsChart
                            t={t}
                            title="AI Credits Usage"
                            description="Monitor your AI credits usage on a monthly basis throughout the year"
                            data={charts.ai_credits_chart.data}
                        />
                    </div>

                    {/* AI Image Credits Chart */}
                    <div>
                        <AIImageCreditsChart
                            t={t}
                            title="AI Image Credits Usage"
                            description="Monitor your AI image credits usage on a monthly basis throughout the year"
                            data={charts.ai_image_credits_chart.data}
                        />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
