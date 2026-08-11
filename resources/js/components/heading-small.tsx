import { useTranslation } from 'react-i18next';

export default function HeadingSmall({
    title,
    description,
}: {
    title: string;
    description?: string;
}) {
    const { t } = useTranslation();
    return (
        <header>
            {/* Title */}
            <h3 className="mb-0.5 text-base font-medium">{ t(title) }</h3>

            {/* Description */}
            {description && (
                <p className="text-sm text-muted-foreground">{ t(description) }</p>
            )}
        </header>
    );
}
