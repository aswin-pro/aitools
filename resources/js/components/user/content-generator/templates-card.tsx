import { CustomBadge } from '@/components/custom-badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';
import { ContentTemplate } from '@/types/user';
import { router } from '@inertiajs/react';
import { BotMessageSquare, Crown } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';

export default function TemplatesCard({
    t,
    templates,
}: {
    t: (key: string) => string;
    templates: Record<number, ContentTemplate[]>;
}) {
    // category ids
    const categoryIds = Object.keys(templates);

    // selected category
    const [selectedCategory, setSelectedCategory] = useState(
        categoryIds[0] ?? '',
    );

    // category templates
    const selectedTemplates = templates[Number(selectedCategory)] ?? [];

    // set template
    const setTemplate = (template: ContentTemplate) => {
        if (!template.is_available) {
            toast(t('Upgrade your plan to unlock this template.'), {
                action: (
                    <Button
                        size="sm"
                        type="button"
                        onClick={() =>
                            router.get(
                                route('dashboard.user.subscriptions.plans'),
                            )
                        }
                    >
                        {t('Upgrade')}
                    </Button>
                ),
            });
        } else {
            router.get(
                route('dashboard.user.content-generator.generate', {
                    template: template.unique_slug,
                }),
            );
        }
    };

    return (
        <div className="space-y-5">
            {/* Tabs */}
            <div className="flex justify-start">
                <ToggleGroup
                    type="single"
                    value={selectedCategory}
                    onValueChange={(value) => {
                        if (value) {
                            setSelectedCategory(value);
                        }
                    }}
                    className="flex flex-wrap justify-start"
                >
                    {categoryIds.map((categoryId) => (
                        <ToggleGroupItem
                            key={categoryId}
                            value={categoryId}
                            variant="outline"
                            size="sm"
                        >
                            {
                                templates[Number(categoryId)][0]?.category
                                    .category_name
                            }
                        </ToggleGroupItem>
                    ))}
                </ToggleGroup>
            </div>

            {/* Templates */}
            {selectedTemplates.length > 0 ? (
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    {/* Render Templates */}
                    {selectedTemplates.map((template) => (
                        <Card
                            key={template.id}
                            onClick={() => setTemplate(template)}
                            className="relative cursor-pointer"
                        >
                            {/* Badge */}
                            {!template.is_available ? (
                                <CustomBadge
                                    type="danger"
                                    className="absolute top-0 right-0 rounded-tl-none rounded-tr-lg rounded-br-none rounded-bl-lg px-2 py-1"
                                >
                                    <Crown className="h-5 w-5" />
                                </CustomBadge>
                            ) : (
                                <CustomBadge
                                    type="success"
                                    className="absolute top-0 right-0 rounded-tl-none rounded-tr-lg rounded-br-none rounded-bl-lg px-2 py-1"
                                >
                                    <BotMessageSquare className="h-5 w-5" />
                                </CustomBadge>
                            )}

                            <CardContent className="relative space-y-1.5 pt-3">
                                {/* Title */}
                                <h3 className="text-lg font-medium">
                                    {template.name}
                                </h3>

                                {/* Description */}
                                <p className="text-sm text-muted-foreground">
                                    {template.description}
                                </p>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            ) : (
                <div className="py-12 text-center text-sm text-muted-foreground">
                    {t('No templates available.')}
                </div>
            )}
        </div>
    );
}
