import { marked } from 'marked';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import { useEffect, useRef } from 'react';
import TurndownService from 'turndown';

const turndown = new TurndownService();

export function RichTextEditor({
    t,
    loading,
    content,
    generationId,
    onChange,
}: {
    t: (key: string) => string;
    loading: boolean;
    content?: string;
    generationId?: string | null;
    onChange: (content: string) => void;
}) {
    // editor reference
    const editorRef = useRef<HTMLDivElement>(null);

    // quill reference
    const quillRef = useRef<Quill | null>(null);

    // updating reference
    const updatingRef = useRef(false);

    // Initialize Quill
    useEffect(() => {
        if (!editorRef.current || quillRef.current) return;

        const quill = new Quill(editorRef.current, {
            theme: 'snow',
            placeholder: t('Your generated content will appear here...'),
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'link'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                ],
            },
        });

        quill.on('text-change', () => {
            if (!updatingRef.current) {
                onChange(turndown.turndown(quill.root.innerHTML));
            }
        });

        quillRef.current = quill;

        return () => {
            quill.off('text-change');
            quillRef.current = null;
        };
    }, []);

    // Clear editor and update placeholder while generating
    useEffect(() => {
        const quill = quillRef.current;
        if (!quill) return;

        updatingRef.current = true;

        if (loading) {
            quill.setText('');
            quill.root.dataset.placeholder = t('Thinking...');
        } else {
            quill.root.dataset.placeholder = t(
                'Your generated content will appear here...',
            );
        }

        updatingRef.current = false;
    }, [loading, t]);

    // Load new generated Markdown only when generation changes
    useEffect(() => {
        const quill = quillRef.current;

        if (!quill || loading || !content || !generationId) return;

        updatingRef.current = true;

        quill.clipboard.dangerouslyPasteHTML(marked.parse(content) as string);

        updatingRef.current = false;

        // Sync Quill content to parent
        onChange(turndown.turndown(quill.root.innerHTML));
    }, [generationId, content]);

    return (
        <div>
            {/* Quill Editor */}
            <div ref={editorRef} />

            {/* Precautions */}
            {(route().current('dashboard.user.content-generator.*') ||
                route().current('dashboard.user.speech-to-text.*')) && (
                <div className="mt-2 text-center">
                    <p className="text-xs text-muted-foreground">
                        {t(
                            'AI can make mistakes. Please review the content before using it.',
                        )}
                    </p>
                </div>
            )}
        </div>
    );
}
