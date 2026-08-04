import { Button } from '@/components/ui/button';
import {
    Card,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { SectionCard } from '@/types';
import { router } from '@inertiajs/react';
import { CheckIcon, Funnel } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export function SectionCards({
    sectionCards,
}: {
    sectionCards: SectionCard[];
}) {
    const { t } = useTranslation();
    const periods = [
        { label: 'Overall', value: 'overall' },
        { label: 'Today', value: 'today' },
        { label: 'This Week', value: 'this_week' },
        { label: 'This Month', value: 'this_month' },
        { label: 'This Year', value: 'this_year' },
        { label: 'Last Month', value: 'last_month' },
        { label: 'Last Year', value: 'last_year' },
    ];

    const [period, setPeriod] = useState('overall');

    return (
        <>
            {/* Filter by period */}
            <div className="flex items-end">
                <Popover>
                    <PopoverTrigger asChild>
                        <Button
                            variant="outline"
                            className="ml-auto min-w-32 justify-between"
                        >
                            {periods.find((p) => p.value === period)?.label}
                            <Funnel />
                        </Button>
                    </PopoverTrigger>

                    <PopoverContent align="end" className="w-52 p-0">
                        <Command>
                            <CommandInput placeholder="Search period..." />

                            <CommandList>
                                <CommandEmpty>
                                    {t('No period found.')}
                                </CommandEmpty>

                                <CommandGroup>
                                    {periods.map((item) => (
                                        <CommandItem
                                            key={item.value}
                                            value={item.label}
                                            onSelect={() => {
                                                setPeriod(item.value);

                                                router.reload({
                                                    only: ['summary'],
                                                    data: {
                                                        period: item.value,
                                                    },
                                                });
                                            }}
                                        >
                                            <span>{t(item.label)}</span>

                                            <CheckIcon
                                                className={cn(
                                                    'ml-auto h-4 w-4',
                                                    period === item.value
                                                        ? 'opacity-100'
                                                        : 'opacity-0',
                                                )}
                                            />
                                        </CommandItem>
                                    ))}
                                </CommandGroup>
                            </CommandList>
                        </Command>
                    </PopoverContent>
                </Popover>
            </div>

            {/* Section cards */}
            <div className="grid grid-cols-1 gap-4 *:data-[slot=card]:bg-linear-to-t *:data-[slot=card]:from-primary/5 *:data-[slot=card]:to-card *:data-[slot=card]:shadow-xs @xl/main:grid-cols-2 @5xl/main:grid-cols-4 dark:*:data-[slot=card]:bg-card">
                {sectionCards.map((sectionCard) => (
                    <Card className="@container/card -space-y-3 p-4" key={sectionCard.title}>
                        <CardHeader className="p-0">
                            <CardDescription className="text-sm font-medium text-primary">
                                {t(sectionCard.title)}
                            </CardDescription>
                            <CardTitle className="text-2xl font-semibold tabular-nums">
                                {sectionCard.value}
                            </CardTitle>
                        </CardHeader>
                        <CardFooter className="p-0 text-sm">
                            <p className="text-muted-foreground">
                                {t(sectionCard.description)}
                            </p>
                        </CardFooter>
                    </Card>
                ))}
            </div>
        </>
    );
}
