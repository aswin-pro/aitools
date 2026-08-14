import { Check } from "lucide-react";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";
import { assetUrl } from "@/helpers/asset-url";
import { useTranslation } from "react-i18next";

type Theme = {
    theme_id: string | number;
    theme_name: string;
    cover_image: string;
};

interface ThemeSelectorProps {
    themes: Theme[];
    value: string;
    onChange: (value: string) => void;
}

export default function ThemeSelector({
    themes,
    value,
    onChange,
}: ThemeSelectorProps) {
    const { t } = useTranslation();

    return (
        <div className="space-y-4">
            <div>
                <Label required>{t("Themes")}</Label>

                <p className="mt-1 text-xs text-muted-foreground">
                    {t("Select one theme to continue.")}
                </p>
            </div>

            <RadioGroup
                value={value}
                onValueChange={onChange}
                className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
            >
                {themes.map((theme) => {
                    const themeId = String(theme.theme_id);
                    const isSelected = value === themeId;

                    return (
                        <div
                            key={theme.theme_id}
                            className="min-w-0"
                        >
                            <RadioGroupItem
                                id={`theme-${theme.theme_id}`}
                                value={themeId}
                                className="peer sr-only"
                            />

                            <Label
                                htmlFor={`theme-${theme.theme_id}`}
                                className={cn(
                                    "group block h-full cursor-pointer overflow-hidden rounded-lg border bg-background transition-all",
                                    "hover:border-primary/50 hover:shadow-sm",
                                    isSelected &&
                                        "border-primary ring-1",
                                )}
                            >
                                <div className="relative aspect-[16/10] w-full overflow-hidden bg-muted">
                                    <img
                                        src={assetUrl(theme.cover_image)}
                                        alt={theme.theme_name}
                                        className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
                                    />

                                    {isSelected && (
                                        <div className="absolute right-3 top-3 flex h-5 w-5 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-md">
                                            <Check className="h-4 w-4" />
                                        </div>
                                    )}
                                </div>

                                <div className="flex items-center justify-between px-4 py-3">
                                    <div className="min-w-0">
                                        <p className="truncate text-sm font-medium">
                                            {theme.theme_name}
                                        </p>

                                        <p className="mt-0.5 text-xs text-muted-foreground">
                                            {t("Theme")}
                                        </p>
                                    </div>

                                    {isSelected && (
                                        <span className="ml-3 shrink-0 rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800">
                                            {t("Selected")}
                                        </span>
                                    )}
                                </div>
                            </Label>
                        </div>
                    );
                })}
            </RadioGroup>

            <input
                type="hidden"
                name="theme_id"
                value={value}
            />
        </div>
    );
}