import {
    Card,
    CardDescription,
    CardFooter,
    CardHeader,
} from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { Summary } from '@/types/user';

export function SectionCards({
    t,
    summary,
}: {
    t: (key: string) => string;
    summary: Summary;
}) {
    // ai credits progress
    const aiPreditsProgress = Math.round(
        Math.min(
            100,
            (summary.ai_credits.used / summary.ai_credits.total) * 100,
        ),
    );

    // ai images progress
    const aiImagesProgress = Math.round(
        Math.min(
            100,
            (summary.ai_image_credits.used / summary.ai_image_credits.total) *
                100,
        ),
    );

    // format credits
    const formatCredits = (value: number) =>
        new Intl.NumberFormat('en', {
            notation: 'compact',
            maximumFractionDigits: 1,
        }).format(value);

    const cards: {
        title: string;
        content: React.ReactNode;
        footer: string;
    }[] = [
        {
            title: 'Subscription',
            content: (
                <h3 className="text-2xl font-semibold">
                    {t(summary.subscription.plan_name)}
                </h3>
            ),
            footer: t(summary.subscription.validity),
        },
        {
            title: 'AI Credits',
            content: (
                <Progress
                    className="mt-1 h-4 rounded-[5px]"
                    value={aiPreditsProgress}
                />
            ),
            footer: `${formatCredits(
                summary.ai_credits.total - summary.ai_credits.used,
            )} ${t('Credits Left')}`,
        },
        {
            title: 'AI Image Credits',
            content: (
                <Progress
                    className="mt-1 h-4 rounded-[5px]"
                    value={aiImagesProgress}
                />
            ),
            footer: `${formatCredits(
                summary.ai_image_credits.total - summary.ai_image_credits.used,
            )} ${t('Credits Left')}`,
        },
    ];

    return (
        <div className="grid grid-cols-1 gap-4 *:data-[slot=card]:bg-linear-to-t *:data-[slot=card]:from-primary/5 *:data-[slot=card]:to-card *:data-[slot=card]:shadow-xs @xl/main:grid-cols-2 @5xl/main:grid-cols-3 dark:*:data-[slot=card]:bg-card">
            {cards.map((card) => (
                <Card
                    key={card.title}
                    className="@container/card flex h-full flex-col -space-y-4"
                >
                    <CardHeader className="flex-1 -space-y-2">
                        <CardDescription className="text-sm font-medium">
                            {t(card.title)}
                        </CardDescription>

                        <div className="flex min-h-10 items-center">
                            {card.content}
                        </div>
                    </CardHeader>

                    <CardFooter className="mt-auto text-sm">
                        <p className="text-muted-foreground">
                            {card.footer}
                        </p>
                    </CardFooter>
                </Card>
            ))}
        </div>
    );
}
