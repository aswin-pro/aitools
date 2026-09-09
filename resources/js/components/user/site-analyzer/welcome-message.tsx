import WebAnalyzerForm from '../forms/web-analyzer-form';

export function WelcomeMessage({ t }: { t: (key: string) => string }) {
    return (
        <div className="flex flex-1 items-center justify-center">
            <div className="max-w-lg text-center">
                <h2 className="text-2xl font-semibold tracking-tight">
                    {t('Welcome to Site Analyzer')}
                </h2>

                <p className="mt-2 text-sm text-muted-foreground">
                    {t(
                        'Ask questions about any website and get accurate, context-aware answers and insights in seconds.',
                    )}
                </p>

                {/* Form */}
                <WebAnalyzerForm t={t} />
            </div>
        </div>
    );
}
