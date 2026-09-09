import { useAppearance } from '@/hooks/use-appearance';
import { Check, Copy } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Prism as SyntaxHighlighter } from 'react-syntax-highlighter';
import { oneDark, prism } from 'react-syntax-highlighter/dist/esm/styles/prism';

export function CodeBlock({
    code,
    language,
}: {
    code: string;
    language: string;
}) {
    const [copied, setCopied] = useState(false);

    const copyCode = async () => {
        await navigator.clipboard.writeText(code);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };
    const { t } = useTranslation();

    const { isDark } = useAppearance();

    return (
        <div className="relative my-3 overflow-hidden rounded-xl border">
            {/* Header */}
            <div className="flex items-center justify-between border-b bg-muted px-3 py-2 text-xs">
                <span className="font-medium">{t(language || 'text')}</span>

                <button
                    onClick={copyCode}
                    className="flex items-center gap-1 text-muted-foreground hover:text-foreground"
                >
                    {copied ? (
                        <>
                            <Check className="h-3.5 w-3.5" />
                            {t('Copied')}
                        </>
                    ) : (
                        <>
                            <Copy className="h-3.5 w-3.5" />
                            {t('Copy')}
                        </>
                    )}
                </button>
            </div>

            <SyntaxHighlighter
                language={language}
                style={isDark ? oneDark : prism}
                PreTag="div"
                customStyle={{
                    margin: 0,
                    padding: '16px',
                    borderRadius: 0,
                    background: 'transparent',
                }}
                codeTagProps={{
                    style: { background: 'transparent' },
                }}
            >
                {code}
            </SyntaxHighlighter>
        </div>
    );
}
