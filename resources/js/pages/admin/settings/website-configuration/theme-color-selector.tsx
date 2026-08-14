import { Check } from "lucide-react";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";
import { themeColors } from "@/data/theme-color";
import { useTranslation } from "react-i18next";

interface ThemeColorSelectorProps {
    value: string;
    onChange: (value: string) => void;
}

export default function ThemeColorSelector({
    value,
    onChange,
}: ThemeColorSelectorProps) {
    const { t } = useTranslation();

    return (
        <div className="space-y-4">
            <div>
                <Label required>{t("Theme Colors")}</Label>

                <p className="mt-1 text-xs text-muted-foreground">
                    {t("Choose the primary color for your website.")}
                </p>
            </div>

            <div className="flex flex-wrap gap-1">
                {themeColors.map((item) => {
                    const isSelected = value === item.value;

                    return (
                        <button
                            key={item.value}
                            type="button"
                            onClick={() => onChange(item.value)}
                            title={item.label}
                            aria-label={`Select ${item.label}`}
                            className={cn(
                                "flex h-8 w-8 items-center justify-center rounded-full border transition-all",
                                isSelected
                                    ? "border-foreground"
                                    : "border-transparent hover:scale-105",
                            )}
                        >
                            <span
                                className="flex h-5 w-5 items-center justify-center rounded-full"
                                style={{
                                    backgroundColor: item.color,
                                }}
                            >
                                {isSelected && (
                                    <Check className="h-4 w-4 text-white" />
                                )}
                            </span>
                        </button>
                    );
                })}
            </div>

            <input
                type="hidden"
                name="app_theme"
                value={value}
            />
        </div>
    );
}