export default function HeadingSmall({
    t,
    title,
    description,
}: {
    t: (key: string) => string;
    title: string;
    description?: string;
}) {
    return (
        <header>
            {/* Title */}
            <h3 className="mb-0.5 text-base font-medium">{t(title)}</h3>

            {/* Description */}
            {description && (
                <p className="text-sm text-muted-foreground">
                    {t(description)}
                </p>
            )}
        </header>
    );
}
