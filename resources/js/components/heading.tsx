import { useTranslation } from "react-i18next";

export default function Heading({
    title,
    description,
}: {
    title: string;
    description?: string;
}) {
    // i18n
    const { t } = useTranslation();

    return (
        <div className="mb-6 space-y-0.5">
            <h2 className="text-xl font-semibold tracking-tight">{ t(title) }</h2>
            {description && (
                <p className="text-sm text-muted-foreground">{ t(description) }</p>
            )}
        </div>
    );
}
