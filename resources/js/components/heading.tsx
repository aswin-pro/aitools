export default function Heading({
    t,
    title,
    description,
}: {
    t: (key: string) => string;
    title: string;
    description?: string;
}) {
    return (
        <div className="mb-6 space-y-0.5">
            <h2 className="text-xl font-semibold tracking-tight">{t(title)}</h2>
            {description && (
                <p className="text-sm text-muted-foreground">
                    {t(description)}
                </p>
            )}
        </div>
    );
}
