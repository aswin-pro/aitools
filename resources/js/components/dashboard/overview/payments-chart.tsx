'use client';

import * as React from 'react';
import { Area, AreaChart, CartesianGrid, XAxis } from 'recharts';

import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

import {
    ChartContainer,
    ChartLegend,
    ChartLegendContent,
    ChartTooltip,
    ChartTooltipContent,
} from '@/components/ui/chart';

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

import { Separator } from '@/components/ui/separator';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';
import { useTranslation } from 'react-i18next';

interface PaymentChartData {
    month: string;
    purchases: number;
    sales: number;
}

interface ChartProps {
    data: PaymentChartData[];
    title?: string;
    description?: string;
}

const paymentsConfig = {
    purchases: {
        label: 'Purchase',
        color: 'hsl(217 91% 60%)', // Blue
    },
    sales: {
        label: 'Sales',
        color: 'hsl(25 95% 53%)', // Green
    },
};

type ChartKey = keyof typeof paymentsConfig;

export function PaymentsChart({ data, title, description }: ChartProps) {
    const { t } = useTranslation();

    const [timeRange, setTimeRange] = React.useState('12m');

    const filteredData = React.useMemo(() => {
        const currentMonth = new Date().getMonth();

        switch (timeRange) {
            case '3m':
                return data.slice(
                    Math.max(0, currentMonth - 2),
                    currentMonth + 1,
                );

            case '6m':
                return data.slice(
                    Math.max(0, currentMonth - 5),
                    currentMonth + 1,
                );

            default:
                return data;
        }
    }, [data, timeRange]);

    const keys = Object.keys(paymentsConfig) as ChartKey[];

    return (
        <Card className="@container/card shadow-sm">
            <CardHeader>
                <CardTitle>{t(title ?? '')}</CardTitle>

                <CardDescription>{t(description ?? '')}</CardDescription>

                <CardAction>
                    <ToggleGroup
                        type="single"
                        value={timeRange}
                        onValueChange={(value) => {
                            if (value) setTimeRange(value);
                        }}
                        variant="outline"
                        className="hidden @[767px]/card:flex"
                    >
                        <ToggleGroupItem value="12m">
                            {t('Last 12 months')}
                        </ToggleGroupItem>

                        <ToggleGroupItem value="6m">
                            {t('Last 6 months')}
                        </ToggleGroupItem>

                        <ToggleGroupItem value="3m">
                            {t('Last 3 months')}
                        </ToggleGroupItem>
                    </ToggleGroup>

                    <Select value={timeRange} onValueChange={setTimeRange}>
                        <SelectTrigger className="@[767px]/card:hidden">
                            <SelectValue />
                        </SelectTrigger>

                        <SelectContent>
                            <SelectItem value="12m">
                                {t('Last 12 months')}
                            </SelectItem>

                            <SelectItem value="6m">
                                {t('Last 6 months')}
                            </SelectItem>

                            <SelectItem value="3m">
                                {t('Last 3 months')}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </CardAction>
            </CardHeader>

            <Separator className="-mt-1" />

            <CardContent className="px-2 pt-4 sm:px-6">
                <ChartContainer
                    config={paymentsConfig}
                    className="mx-auto h-[270px] w-full"
                >
                    <AreaChart
                        data={filteredData}
                        margin={{
                            left: 16,
                            right: 16,
                            top: 8,
                            bottom: 16,
                        }}
                    >
                        <defs>
                            {keys.map((key) => (
                                <linearGradient
                                    key={key}
                                    id={`fill-${key}`}
                                    x1="0"
                                    y1="0"
                                    x2="0"
                                    y2="1"
                                >
                                    <stop
                                        offset="0%"
                                        stopColor={paymentsConfig[key].color}
                                        stopOpacity={0.45}
                                    />
                                    <stop
                                        offset="60%"
                                        stopColor={paymentsConfig[key].color}
                                        stopOpacity={0.18}
                                    />
                                    <stop
                                        offset="100%"
                                        stopColor={paymentsConfig[key].color}
                                        stopOpacity={0}
                                    />
                                </linearGradient>
                            ))}
                        </defs>

                        <CartesianGrid vertical={false} />

                        <XAxis
                            dataKey="month"
                            tickLine={false}
                            axisLine={false}
                            interval={0}
                            tickMargin={10}
                        />

                        <ChartTooltip
                            cursor={false}
                            content={
                                <ChartTooltipContent
                                    indicator="dot"
                                    formatter={(value, name) => [
                                        `${paymentsConfig[name as ChartKey]?.label ?? name} -`,
                                        `₹${Number(value).toLocaleString('en-IN')}`,
                                    ]}
                                />
                            }
                        />

                        {keys.map((key) => (
                            <Area
                                key={key}
                                dataKey={key}
                                type="monotoneX"
                                stroke={paymentsConfig[key].color}
                                fill={`url(#fill-${key})`}
                                strokeWidth={1.5}
                                activeDot={{ r: 3 }}
                            />
                        ))}

                        <ChartLegend content={<ChartLegendContent />} />
                    </AreaChart>
                </ChartContainer>
            </CardContent>
        </Card>
    );
}
