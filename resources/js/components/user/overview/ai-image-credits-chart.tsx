'use client';

import { Bar, BarChart, CartesianGrid, XAxis } from 'recharts';

import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    ChartContainer,
    ChartTooltip,
    ChartTooltipContent,
    type ChartConfig,
} from '@/components/ui/chart';
import { ChartData } from '@/types/user';

const chartConfig = {
    ai_credits: {
        label: 'AI Image Credits',
        color: 'var(--primary)',
    },
} satisfies ChartConfig;

export function AIImageCreditsChart({
    t,
    title,
    description,
    data,
}: {
    t: (key: string) => string;
    title: string;
    description: string;
    data: ChartData[];
}) {
    return (
        <Card>
            <CardHeader>
                <CardTitle>{t(title)}</CardTitle>
                <CardDescription>{t(description)}</CardDescription>
            </CardHeader>

            <CardContent>
                <ChartContainer config={chartConfig}>
                    <BarChart accessibilityLayer data={data}>
                        <CartesianGrid vertical={false} />
                        <XAxis
                            dataKey="month"
                            tickLine={false}
                            tickMargin={10}
                            axisLine={false}
                            tickFormatter={(value) => value.slice(0, 3)}
                        />
                        <ChartTooltip
                            cursor={false}
                            content={<ChartTooltipContent hideLabel />}
                        />
                        <Bar
                            dataKey="ai_credits"
                            name="AI Image Credits"
                            fill="var(--color-ai_credits)"
                            radius={8}
                        />
                    </BarChart>
                </ChartContainer>
            </CardContent>
        </Card>
    );
}
