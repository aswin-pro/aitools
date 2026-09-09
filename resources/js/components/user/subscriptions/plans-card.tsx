import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Separator } from '@/components/ui/separator';
import { ContentTemplate, Feature, Plan } from '@/types/user';
import { Link } from '@inertiajs/react';
import { Check, Info, Sparkles, X } from 'lucide-react';

export default function PlansCard({
    t,
    plans,
    templates,
}: {
    t: (key: string) => string;
    plans: Plan[];
    templates: ContentTemplate[];
}) {
    // features
    const features: Feature[] = [
        { key: 'content_templates_count', label: 'Templates', type: 'limit' },
        { key: 'ai_credits', label: 'AI Credits', type: 'limit' },
        { key: 'ai_image_credits', label: 'AI Image Credits', type: 'limit' },
        {
            key: 'speech_to_text',
            label: 'AI Speech to Text',
            type: 'boolean',
        },
        {
            key: 'text_to_speech',
            label: 'AI Text to Speech',
            type: 'boolean',
        },
        { key: 'code_generator', label: 'AI Code Generator', type: 'boolean' },
        {
            key: 'personalized_chat',
            label: 'Personalized Chat',
            type: 'boolean',
        },
        {
            key: 'document_analyzer',
            label: 'AI File Analyzer',
            type: 'boolean',
        },
        { key: 'site_analyzer', label: 'AI Web Chat', type: 'boolean' },
        { key: 'customer_support', label: 'Support', type: 'boolean' },
    ];

    // get validity
    const getValidity = (validity: number) =>
        ({
            30: t('Month'),
            31: t('Month'),
            365: t('Year'),
            366: t('Year'),
        })[validity] ?? `${validity} ${t('Days')}`;

    const getGroupedTemplates = (plan: Plan) => {
        const groups: Record<string, ContentTemplate[]> = {};

        Object.keys(plan.content_templates).forEach((slug) => {
            if (plan.content_templates[slug] !== 1) return;

            const template = templates.find(
                (item) => item.unique_slug === slug,
            );
            if (!template?.category) return;

            const id = template.category.id;
            groups[id] ??= [];
            groups[id].push(template);
        });

        return Object.values(groups);
    };

    return (
        <div className="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            {plans.map((plan) => (
                <Card className="relative mb-1" key={plan.id}>
                    {/* Recommended Badge */}
                    {plan.is_recommended !== 0 && (
                        <Badge className="absolute -top-3.5 left-1/2 -translate-x-1/2 gap-1 rounded-lg bg-green-500 px-3 py-1 text-white dark:bg-green-800">
                            <Sparkles className="-ms-0.5 h-3.5 w-3.5" />
                            {t('Recommended')}
                        </Badge>
                    )}

                    {/* Card Header */}
                    <CardHeader>
                        {/* Card Title */}
                        <CardTitle className="mt-1.5 text-base font-medium">
                            {/* Plan Name */}
                            <h3>{t(plan.name)}</h3>

                            {/* Plan Price */}
                            <h4 className="mt-0.5 text-2xl font-medium">
                                {plan.price === 0 ? (
                                    t('Free')
                                ) : (
                                    <>
                                        {plan.formatted_price}
                                        <span className="text-base text-muted-foreground">
                                            {' / '}
                                            {getValidity(plan.validity)}
                                        </span>
                                    </>
                                )}
                            </h4>
                        </CardTitle>

                        {/* Separator */}
                        <Separator />

                        {/* Plan Description */}
                        <CardDescription className="text-sm leading-relaxed">
                            {plan.description}
                        </CardDescription>
                    </CardHeader>

                    {/* Card Content */}
                    <CardContent>
                        {/* Features */}
                        <h3 className="-mt-1 font-medium">{t('Features')}</h3>

                        {/* Features List */}
                        <ul className="mt-2 space-y-1 text-sm leading-relaxed">
                            {features.map(({ key, label, type }) => {
                                // value
                                const value = plan[key as keyof Plan];

                                // check enabled
                                const enabled =
                                    type === 'limit'
                                        ? Number(value) > 0
                                        : value === 1;

                                return (
                                    <li
                                        key={key}
                                        className={`flex items-center gap-2 border-b border-dotted pb-1 last:border-b-0 ${
                                            enabled ? '' : 'opacity-70'
                                        }`}
                                    >
                                        {enabled ? (
                                            <Check
                                                size={18}
                                                className="rounded-full border border-green-300 bg-green-100 p-[2.5px] text-green-600 dark:border-green-500/20 dark:bg-green-500/10"
                                            />
                                        ) : (
                                            <X
                                                size={18}
                                                className="rounded-full border border-red-300 bg-red-100 p-[2.5px] text-red-600 opacity-70 dark:border-red-500/20 dark:bg-red-500/10"
                                            />
                                        )}

                                        <span className="flex items-center gap-1">
                                            {type === 'limit' && enabled
                                                ? `${value} ${t(label)}`
                                                : t(label)}

                                            {/* Popover Templates */}
                                            {key ===
                                                'content_templates_count' && (
                                                <Popover>
                                                    <PopoverTrigger asChild>
                                                        <Button
                                                            type="button"
                                                            variant="ghost"
                                                            size="icon"
                                                            className="h-4 w-4"
                                                        >
                                                            <Info className="h-3.5 w-3.5 text-muted-foreground" />
                                                        </Button>
                                                    </PopoverTrigger>

                                                    <PopoverContent className="h-68 w-68 overflow-auto p-3">
                                                        {getGroupedTemplates(
                                                            plan,
                                                        ).map((items) => {
                                                            const category =
                                                                items[0]
                                                                    .category!;

                                                            return (
                                                                <div
                                                                    key={
                                                                        category.id
                                                                    }
                                                                    className="mb-3 last:mb-0"
                                                                >
                                                                    <h3 className="mb-1 text-base font-medium">
                                                                        {
                                                                            category.category_name
                                                                        }
                                                                    </h3>

                                                                    <ul className="space-y-1 text-sm text-muted-foreground">
                                                                        {items.map(
                                                                            (
                                                                                template,
                                                                            ) => (
                                                                                <li
                                                                                    key={
                                                                                        template.unique_slug
                                                                                    }
                                                                                    className="flex items-center gap-1"
                                                                                >
                                                                                    <Check className="h-3.5 w-3.5 text-green-600" />
                                                                                    {t(
                                                                                        template.name,
                                                                                    )}
                                                                                </li>
                                                                            ),
                                                                        )}
                                                                    </ul>
                                                                </div>
                                                            );
                                                        })}
                                                    </PopoverContent>
                                                </Popover>
                                            )}
                                        </span>
                                    </li>
                                );
                            })}
                        </ul>
                    </CardContent>

                    {/* Card Footer */}
                    <CardFooter className="mt-auto">
                        {/* Buy Now Button */}
                        {plan.price > 0 ? (
                            <a
                                href={route('checkout.index', {
                                    plan: plan.id,
                                })}
                                className="w-full"
                            >
                                <Button type="button" className="w-full">
                                    {t('Buy Now')}
                                </Button>
                            </a>
                        ) : (
                            <Link
                                className="w-full"
                                href={route('checkout.index', {
                                    plan: plan.id,
                                })}
                            >
                                <Button type="button" className="w-full">
                                    {t('Activate')}
                                </Button>
                            </Link>
                        )}
                    </CardFooter>
                </Card>
            ))}
        </div>
    );
}
